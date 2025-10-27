<?php
declare(strict_types=1);
require_once __DIR__ . '/../../staff/models/staffEnrollmentTransactionsModel.php';
require_once __DIR__ . '/../../admin/models/adminEnrolleesModel.php';
require_once __DIR__ . '/../../admin/models/adminStudentsModel.php';
require_once __DIR__ . '/../../staff/models/staffEnrolleesModel.php';

class staffEnrollmentController {
    protected $transactionsModel;
    protected $adminStudentModel;
    protected $adminEnrolleeModel;
    protected $staffEnrolleeModel;
    private int $isApproved = 0;
    private const IS_HANDLED = 1;
    public function __construct() { 
        $this->transactionsModel = new staffEnrollmentTransactionsModel();
        $this->adminStudentModel = new adminStudentsModel();
        $this->adminEnrolleeModel = new adminEnrolleesModel();
        $this->staffEnrolleeModel = new staffEnrolleesModel();
    }
    //API
    public function apiPostUpdateEnrolleeStatus(int $staffId,int $staffType, int $status,int $enrolleeId,?string $remarks) : array {
        try {
            if(empty($enrolleeId)) {
                return [
                    'httpcode'=> 500,
                    'success'=> false,
                    'message'=> 'Enrollee ID is invalid',
                    'data'=> []
                ];
            }
            if(!in_array($status, [1,2,4])) {
                return [
                    'httpcode'=> 500,
                    'success'=> false,
                    'message'=> 'Invalid Enrollee status provided',
                    'data'=> []
                ];
            }
            if(!in_array($staffType, [1,2]) || !isset($staffId)) {
                return [
                    'httpcode'=> 400,
                    'success'=> false,
                    'message'=> 'User is non-Staff or Unknown. Cannot save changes',
                    'data'=> []
                ];
            }
            $statusCode = [
                1 => 'E',
                2 => 'D',
                4 => 'F'
            ][$status];
            $date = date('Ymd');
            $time = time();
            $transactionCode = $statusCode . "-" . $date . "-" . $time;
            if($staffType === 1) {
                $adminUpdate = $this->executeAdminUpdate($enrolleeId, $transactionCode, $status, $staffId, $remarks);
                if(!$adminUpdate['success']) {
                    return [
                        'httpcode'=> 400,
                        'success'=> false,
                        'message'=> $adminUpdate['message'],
                        'data'=> []
                    ];
                }
                return [
                    'httpcode'=> 200,
                    'success'=> true,
                    'message'=> $adminUpdate['message'],
                    'data'=> []
                ];
            }
            else {
                $teacherUpdate = $this->executeTeacherUpdate($enrolleeId,$transactionCode,$status,$staffId,$remarks);
                if(!$teacherUpdate['success']) {
                    return [
                        'httpcode'=> 400,
                        'success'=> false,
                        'message'=> $teacherUpdate['message'],
                        'data'=> []
                    ];
                }
                return [
                    'httpcode'=> 200,
                    'success'=> true,
                    'message'=> $teacherUpdate['message'],
                    'data'=> []
                ];
            }
        }
        catch(DatabaseException $e) {
            return [
                'httpcode'=> 500,
                'success'=> false,
                'message'=> 'There was a problem on our side: '.$e->getMessage(),
                'error_code'=> $e->getCode(),
                'error_message'=> $e->getPrevious->getMessage(),
                'data'=> []
            ];
        }
        catch(Exception $e) {
            return [
                'httpcode'=> 400,
                'success'=> false,
                'message'=> 'There was an unexpected problem: '.$e->getMessage(),
                'data'=> []
            ];
        }
    }
    //HELPERS
    private function executeTeacherUpdate(int $enrolleeId, string $transactionCode, int $status, int $staffId, ?string $remarks, int $isHandled) : array {
        try {
            if(!$this->transactionsModel->insertEnrolleeTransaction($enrolleeId, $transactionCode, $status, $staffId, $remarks, $this->isApproved)) {
                return ['success'=> false,'message'=> 'Inserting enrollee transaction failed'];
            }
            if(!$this->staffEnrolleeModel->setIsHandledStatus($enrolleeId, self::IS_HANDLED)) {
                return ['success'=> false,'message'=> 'Handled status not updated'];
            }
            return ['success'=> true,'message'=> 'Teacher successfully updated changes'];
        }
        catch(DatabaseException $e) {
            return [
                'httpcode'=> 500,
                'success'=> false,
                'message'=> 'There was a problem on our side: '. $e->getMessage(),
                'error_code'=> $e->getCode(),
                'error_message'=> $e->getPrevious()?->getMessage(),
                'data'=> []
            ];
        }
        catch(Exception $e) {
            return [
                'httpcode'=> 500,
                'success'=> false,
                'message'=> 'There was an unexpected problem: '.$e->getMessage(),
                'error_code'=> $e->getCode(),
                'error_message'=> $e->getPrevious()?->getMessage()
            ];
        }
    }
    private function executeAdminUpdate(int $enrolleeId, string $transactionCode, int $status, int $staffId, ?string $remarks) : array {
        $this->isApproved = 1;
        try {
            if($status === 1) {
                if(!$this->adminEnrolleeModel->updateEnrollee($enrolleeId, $status)) {
                    return ['success'=> false,'message'=> 'Update enrollee failed'];
                }
                if(!$this->transactionsModel->insertEnrolleeTransaction($enrolleeId,$transactionCode,$status, $staffId, $remarks, $isApproved)) {
                    return ['success'=> false,'message'=> 'Inserting enrollee transaction failed'];
                }
                if(!$this->adminStudentModel->insertEnrolleeToStudent($enrolleeId)) {
                    return ['success'=> false,'message'=> 'Enrollee insert to student failed'];
                }
                if($this->staffEnrolleeModel->setIsHandledStatus($enrolleeId, self::IS_HANDLED)) {
                    return ['success'=> false,'message'=>'Handled status not updated'];
                }
            }
            else if($status === 4 || $status === 2) {
                if(!$this->$transactionsModel->insertEnrolleeTransaction($enrolleeId,$transactionCode,$status, $staffId, $remarks, $isApproved)) {
                    return ['success'=> false,'message'=> 'Inserting enrollee transaction failed'];
                }
                if(!$this->staffEnrolleeModel->setIsHandledStatus($enrolleeId, $isHandled)) {
                    return ['success'=> false,'message'=>'Handled status not updated'];
                }
            }
            return ['success'=> true,'message'=> 'Admin successfully updated changes'];
        }
        catch(DatabaseException $e) {
            return [
                'httpcode'=> 500,
                'success'=> false,
                'message'=> 'There was a problem on our side: '.$e->getMessage(),
                'error_code'=> $e->getCode(),
                'error_message'=> $e->getPrevious()?->getMessage(),
                'data'=> []
            ];
        }
        catch(Exception $e) {
            return [
                'success'=> false,
                'message'=> 'There was an unexpected problem: '.$e->getMessage(),
            ];
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