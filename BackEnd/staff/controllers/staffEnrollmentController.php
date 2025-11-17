<?php
declare(strict_types=1);
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
        $this->transactionsModel = new staffEnrollmentTransactionsModel();
        $this->adminStudentModel = new adminStudentsModel();
        $this->adminEnrolleeModel = new adminEnrolleesModel();
        $this->staffEnrolleeModel = new staffEnrolleesModel();
        $this->smsService = new SendEnrollmentStatus();
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
            if(!in_array($status, [1,2,4])) {
                return [
                    'httpcode'=> 400,
                    'success'=> false,
                    'message'=> 'Invalid enrollment status provided',
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

            // NEW WORKFLOW: Teacher directly finalizes enrollment
            if($status === 1) {
                // ENROLL: Direct path to student table
                $result = $this->processEnrollment($enrolleeId, $transactionCode, $staffId, $remarks);
                return $result;
            }
            else if($status === 2) {
                // DENY: Mark as denied
                $result = $this->processDenial($enrolleeId, $transactionCode, $staffId, $remarks);
                return $result;
            }
            else if($status === 4) {
                // FOLLOW-UP: Flag for additional review
                $result = $this->processFollowUp($enrolleeId, $transactionCode, $staffId, $remarks);
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
                'message'=> 'Database error occurred',
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
            1 => 'E',
            2 => 'D',
            4 => 'F'
        ][$status];
        
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
            $db = new Connect();
            $conn = $db->getConnection();
            $conn->beginTransaction();

            // 1. Update enrollee status to enrolled
            if(!$this->adminEnrolleeModel->updateEnrollee($enrolleeId, 1)) {
                $conn->rollBack();
                return [
                    'httpcode'=> 500,
                    'success'=> false,
                    'message'=> 'Failed to update enrollee status'
                ];
            }

            // 2. Set Is_Handled flag
            if(!$this->adminEnrolleeModel->setIsHandledStatus($enrolleeId, self::BOOL_TRUE)) {
                $conn->rollBack();
                return [
                    'httpcode'=> 500,
                    'success'=> false,
                    'message'=> 'Failed to set handled status'
                ];
            }

            // 3. Insert transaction record with Is_Approved=1 (finalized)
            if(!$this->transactionsModel->insertEnrolleeTransaction($enrolleeId, $transactionCode, 1, $staffId, $remarks, self::BOOL_TRUE)) {
                $conn->rollBack();
                return [
                    'httpcode'=> 500,
                    'success'=> false,
                    'message'=> 'Failed to create transaction record'
                ];
            }

            // 4. Insert enrollee to students table
            if(!$this->adminStudentModel->insertEnrolleeToStudent($enrolleeId)) {
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

    private function processDenial(int $enrolleeId, string $transactionCode, int $staffId, string $remarks): array {
        try {
            // 1. Update enrollee status to denied
            if(!$this->adminEnrolleeModel->updateEnrollee($enrolleeId, 2)) {
                return [
                    'httpcode'=> 500,
                    'success'=> false,
                    'message'=> 'Failed to update enrollee status to denied'
                ];
            }

            // 2. Set Is_Handled flag
            if(!$this->adminEnrolleeModel->setIsHandledStatus($enrolleeId, self::BOOL_TRUE)) {
                return [
                    'httpcode'=> 500,
                    'success'=> false,
                    'message'=> 'Failed to set handled status'
                ];
            }

            // 3. Insert transaction record with Is_Approved=1 (finalized)
            if(!$this->transactionsModel->insertEnrolleeTransaction($enrolleeId, $transactionCode, 2, $staffId, $remarks, self::BOOL_TRUE)) {
                return [
                    'httpcode'=> 500,
                    'success'=> false,
                    'message'=> 'Failed to create transaction record'
                ];
            }

            // 4. Send SMS notification
            $smsResult = $this->sendEnrollmentStatusSMS($enrolleeId, 'Denied');

            return [
                'httpcode'=> 200,
                'success'=> true,
                'message'=> 'Enrollment denied. ' . $smsResult,
                'data'=> []
            ];
        }
        catch(Exception $e) {
            error_log("[".date('Y-m-d H:i:s')."] Denial Error: ".$e->getMessage()."\n", 3, __DIR__ . '/../../errorLogs.txt');
            throw $e;
        }
    }

    private function processFollowUp(int $enrolleeId, string $transactionCode, int $staffId, string $remarks): array {
        try {
            // 1. Update enrollee status to follow-up
            if(!$this->adminEnrolleeModel->updateEnrollee($enrolleeId, 4)) {
                return [
                    'httpcode'=> 500,
                    'success'=> false,
                    'message'=> 'Failed to update enrollee status to follow-up'
                ];
            }

            // 2. Set Is_Handled flag
            if(!$this->adminEnrolleeModel->setIsHandledStatus($enrolleeId, self::BOOL_TRUE)) {
                return [
                    'httpcode'=> 500,
                    'success'=> false,
                    'message'=> 'Failed to set handled status'
                ];
            }

            // 3. Insert transaction record with Is_Approved=0 (requires follow-up)
            if(!$this->transactionsModel->insertEnrolleeTransaction($enrolleeId, $transactionCode, 4, $staffId, $remarks, self::BOOL_FALSE)) {
                return [
                    'httpcode'=> 500,
                    'success'=> false,
                    'message'=> 'Failed to create transaction record'
                ];
            }

            // 4. Send SMS notification
            $smsResult = $this->sendEnrollmentStatusSMS($enrolleeId, 'Follow-Up');

            return [
                'httpcode'=> 200,
                'success'=> true,
                'message'=> 'Enrollment flagged for follow-up. ' . $smsResult,
                'data'=> []
            ];
        }
        catch(Exception $e) {
            error_log("[".date('Y-m-d H:i:s')."] Follow-up Error: ".$e->getMessage()."\n", 3, __DIR__ . '/../../errorLogs.txt');
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

    //VIEW
    public function viewPendingEnrollees(): array {
        try {
            $data = $this->staffEnrolleeModel->getPendingEnrollees();
            if(empty($data)) {
                return [
                    'success'=> false,
                    'message'=> 'Pending enrollees are empty',
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