<?php
declare(strict_types=1);
require_once __DIR__ . '/../../Exceptions/IdNotFoundException.php';
require_once __DIR__ . '/../../staff/models/staffEnrollmentTransactionsModel.php';
require_once __DIR__ . '/../../admin/models/adminEnrolleesModel.php';
require_once __DIR__ . '/../../admin/models/adminStudentsModel.php';
require_once __DIR__ . '/../../staff/models/staffEnrolleesModel.php';
require_once __DIR__ . '/../../common/sendEnrollmentStatus.php';
require_once __DIR__ . '/../../core/dbconnection.php';

class staffEnrollmentController {
    protected $transactionsModel;
    protected $adminStudentModel;
    protected $adminEnrolleeModel;
    protected $staffEnrolleeModel;
    protected $smsService;
    private const BOOL_FALSE = 0;
    private const BOOL_TRUE = 1;
    public function __construct() { 
        $this->init();
    }
    private function init():void {
        try {
            $this->transactionsModel = new staffEnrollmentTransactionsModel();
            $this->adminStudentModel = new adminStudentsModel();
            $this->adminEnrolleeModel = new adminEnrolleesModel();
            $this->staffEnrolleeModel = new staffEnrolleesModel();
            $this->smsService = new SendEnrollmentStatus();
        }
        catch(DatabaseConnectionException $e) {
            header("Location: ../../../FrontEnd/pages/errorPage/500.php?from=staff/staff_pending_enrollments.php");
            die();
        }
    }
    //API
    public function apiPostUpdateEnrolleeStatus(int $staffId, int $status, int $enrolleeId, ?string $remarks) : array {
        try {
            if(empty($enrolleeId)) {
                return [
                    'httpcode'=> 400,
                    'success'=> false,
                    'message'=> 'Enrollee ID is invalid',
                    'data'=> []
                ];
            }
            // Updated: Only accept status 1 (enroll) or 5 (request resubmission)
            if(!in_array($status, [1, 5])) {
                return [
                    'httpcode'=> 400,
                    'success'=> false,
                    'message'=> 'Invalid enrollment action provided',
                    'data'=> []
                ];
            }
            if(!isset($staffId)) {
                return [
                    'httpcode'=> 401,
                    'success'=> false,
                    'message'=> 'Staff ID not found. Cannot save changes',
                    'data'=> []
                ];
            }
            $transactionCode = $this->generateTransactionCode($status);
            $remarks = $remarks ?? '';
            // NEW WORKFLOW: Teacher has 2 options only
            if($status === 1) {
                // ENROLL: Direct path to student table
                $result = $this->processEnrollment($enrolleeId, $transactionCode, $staffId, $remarks);
                return $result;
            }
            else if($status === 5) {
                // REQUEST RESUBMISSION: Flag for user to edit and resubmit
                $result = $this->processResubmissionRequest($enrolleeId, $transactionCode, $staffId, $remarks);
                return $result;
            }
            else {
                return [
                    'httpcode'=> 400,
                    'success'=> false,
                    'message'=> 'Invalid status code',
                    'data'=> []
                ];
            }
        }
        catch(DatabaseException $e) {
            error_log("[".date('Y-m-d H:i:s')."] DB Error: ".$e->getMessage()."\n", 3, __DIR__ . '/../../errorLogs.txt');
            return [
                'httpcode'=> 500,
                'success'=> false,
                'message'=> 'Database error occurred: '.$e->getMessage(),
                'error_code'=> $e->getCode(),
                'data'=> []
            ];
        }
        catch(Exception $e) {
            error_log("[".date('Y-m-d H:i:s')."] Error: ".$e->getMessage()."\n", 3, __DIR__ . '/../../errorLogs.txt');
            return [
                'httpcode'=> 500,
                'success'=> false,
                'message'=> 'An unexpected error occurred',
                'data'=> []
            ];
        }
    }
    //HELPERS
    private function generateTransactionCode(int $status): string {
        $statusCode = [
            1 => 'E',  // Enroll
            5 => 'R'   // Resubmission Request
        ][$status] ?? 'U'; // Unknown fallback
        $rand = '';
        for($i = 0; $i < 8; $i++) {
            $rand .= random_int(0, 9);
        }
        $time = time();
        
        return $statusCode . "-" . $rand . "-" . $time;
    }
    private function processEnrollment(int $enrolleeId, string $transactionCode, int $staffId, string $remarks): array {
        $conn = null;
        try {
            // Get database connection for transaction management
            $conn = (new Connect())->getConnection();
            $conn->beginTransaction();
            // 1. Update enrollee status to enrolled
            if(!$this->adminEnrolleeModel->updateEnrollee($conn,$enrolleeId, 1)) {
                $conn->rollBack();
                return [
                    'httpcode'=> 500,
                    'success'=> false,
                    'message'=> 'Failed to update enrollee status'
                ];
            }
            // 2. Set Is_Handled flag
            if(!$this->adminEnrolleeModel->setIsHandledStatus($conn,$enrolleeId, self::BOOL_TRUE)) {
                $conn->rollBack();
                return [
                    'httpcode'=> 500,
                    'success'=> false,
                    'message'=> 'Failed to set handled status'
                ];
            }
            // 3. Insert transaction record with Is_Approved=1 (finalized)
            if(!$this->transactionsModel->insertEnrolleeTransaction($conn,$enrolleeId, $transactionCode, 1, $staffId, $remarks, self::BOOL_TRUE)) {
                $conn->rollBack();
                return [
                    'httpcode'=> 500,
                    'success'=> false,
                    'message'=> 'Failed to create transaction record'
                ];
            }
            //  4. GET THIS TEACHER'S SECTION ID
            $sectionId = $this->staffEnrolleeModel->getThisTeacherSectionAdvisoryId($conn,$staffId);
            // 5. Insert enrollee to students table with the teacher advisory section
            if(!$this->adminStudentModel->insertEnrolleeToStudent($conn,$enrolleeId,$sectionId)) {
                $conn->rollBack();
                return [
                    'httpcode'=> 500,
                    'success'=> false,
                    'message'=> 'Failed to insert student record'
                ];
            }
            $conn->commit();
            // 5. Send SMS notification
            $smsResult = $this->sendEnrollmentStatusSMS($enrolleeId, 'Enrolled');

            return [
                'httpcode'=> 201,
                'success'=> true,
                'message'=> 'Student successfully enrolled. ' . $smsResult,
                'data'=> []
            ];
        }
        catch(Exception $e) {
            if($conn) {
                $conn->rollBack();
            }
            error_log("[".date('Y-m-d H:i:s')."] Enrollment Error: ".$e->getMessage()."\n", 3, __DIR__ . '/../../errorLogs.txt');
            throw $e;
        }
    }
    private function processResubmissionRequest(int $enrolleeId, string $transactionCode, int $staffId, string $remarks): array {
        $conn = null;
        try {
            $conn = (new Connect())->getConnection();
            $conn->beginTransaction();
            // 1. Set enrollee to Follow-up status (4) to remove from pending queue
            // Enrollee will return to pending (3) when parent resubmits
            if(!$this->adminEnrolleeModel->updateEnrollee($conn,$enrolleeId, 4)) {
                $conn->rollBack();
                return [
                    'httpcode'=> 500,
                    'success'=> false,
                    'message'=> 'Failed to update enrollee status'
                ];
            }
            // 2. Insert transaction with Transaction_Status=1 (allow resubmit), Is_Approved=0
            if(!$this->transactionsModel->insertEnrolleeTransactionWithStatus($conn, $enrolleeId, $transactionCode, 3, $staffId, $remarks, self::BOOL_FALSE, 1)) {
                $conn->rollBack();
                return [
                    'httpcode'=> 500,
                    'success'=> false,
                    'message'=> 'Failed to create resubmission transaction'
                ];
            }

            $conn->commit();
            
            // 3. Send SMS notification
            $smsResult = $this->sendResubmissionRequestSMS($enrolleeId, $remarks);
            return [
                'httpcode'=> 200,
                'success'=> true,
                'message'=> 'Resubmission requested. User can now edit their enrollment form. ' . $smsResult,
                'data'=> []
            ];
        }
        catch(Exception $e) {
            if($conn) {
                $conn->rollBack();
            }
            error_log("[".date('Y-m-d H:i:s')."] Resubmission Error: ".$e->getMessage()."\n", 3, __DIR__ . '/../../errorLogs.txt');
            throw $e;
        }
    }

    private function sendEnrollmentStatusSMS(int $enrolleeId, string $enrollmentStatus): string {
        try {
            $smsData = $this->adminEnrolleeModel->getEnrolleeDetailsForSMS($enrolleeId);
            $smsData['Enrollment_Status'] = $enrollmentStatus;
            $this->smsService->sendEnrollmentStatus($smsData);
            return "SMS sent successfully.";
        }
        catch(Exception $e) {
            error_log("[".date('Y-m-d H:i:s')."] SMS Error: ".$e->getMessage()."\n", 3, __DIR__ . '/../../errorLogs.txt');
            return "SMS failed to send: " . $e->getMessage();
        }
    }
    private function sendResubmissionRequestSMS(int $enrolleeId, string $remarks): string {
        try {
            $smsData = $this->adminEnrolleeModel->getEnrolleeDetailsForSMS($enrolleeId);
            $smsData['Enrollment_Status'] = 'Resubmission';
            $smsData['Remarks'] = $remarks;
            // Note: Ensure SendEnrollmentStatus class handles 'Resubmission' status
            $this->smsService->sendEnrollmentStatus($smsData);
            return "SMS sent successfully.";
        }
        catch(Exception $e) {
            error_log("[".date('Y-m-d H:i:s')."] SMS Error: ".$e->getMessage()."\n", 3, __DIR__ . '/../../errorLogs.txt');
            return "SMS failed to send: " . $e->getMessage();
        }
    }
    //VIEW
    public function viewPendingEnrollees(?int $staffId): array {
        try {
            if(is_null($staffId)) {
                throw new IdNotFoundException('Unauthorized access! Your Staff ID is not found.');
            }
            $data = $this->staffEnrolleeModel->getPendingEnrolleesByTeacherAdvisoryLevel($staffId);
            if(empty($data)) {
                return [
                    'success'=> false,
                    'message'=> 'Pending enrollees are empty. Please double check if you are an adviser of a section',
                    'data'=> []
                ];
            }
            return [
                'success'=> true,
                'message'=> 'Pending enrollees successfully fetched',
                'data'=> $data
            ];
        }
        catch(DatabaseException $e) {
            return [
                'success'=> false,
                'message'=> 'There was a problem on our side: '.$e->getMessage(),
                'error_code'=> $e->getCode(),
                'error_message'=> $e->getPrevious->getMessage(),
                'data'=> []
            ];
        }
        catch(Exception $e) {
            return [
                'success'=> false,
                'message'=> 'There was an unexpected problem: '.$e->getMessage(),
                'data'=> []
            ];
        }
    }
}