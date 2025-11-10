<?php
declare(strict_types=1);

require_once __DIR__ . '/../models/adminTeachersModel.php';
require_once __DIR__ . '/../../Exceptions/DatabaseException.php';
require_once __DIR__ . '/../../common/sendPassword.php';
require_once __DIR__ . '/../../core/generatePassword.php';

class adminTeacherController {
    protected $teacherModel;
    private $sendPassword;
    private $generatePassword;

    public function  __construct() {
        $this->teacherModel = new adminTeachersModel();
        $this->sendPassword = new sendPassword();
        $this->generatePassword = new generatePassword();
    }
    //API
    public function apiFetchCurrentlyAssignedTeacher(int $sectionSubjectId) : array {
        try {
            if(empty($sectionSubjectId)) {
                return [
                    'httpcode'=> 400,
                    'success'=> false,
                    'message'=> 'No ID found',
                    'data'=>[]
                ];
            }
            $data = $this->teacherModel->selectAllTeachers();
            if(empty($data)) {
                return [
                    'httpcode'=> 200,
                    'success'=> false,
                    'message'=> 'There are no Teachers found',
                    'data'=> []
                ];
            }
            //return the staff id of matching section subject id
            $isCurrentlyAssigned = $this->teacherModel->checkCurrentSubjectTeacherOfSectionSubject($sectionSubjectId);
            //append an isChecked row in the data array
            foreach ($data as &$teacher) {
                $teacherId = (int)$teacher['Staff_Id'];
                $teacher['isChecked'] = ($teacherId === (int)$isCurrentlyAssigned);
            }
            unset($teacher);

            return [
                'httpcode'=> 200,
                'success'=> true,
                'message'=> 'successfully fetched',
                'data'=> $data
            ];
        }
        catch(DatabaseException $e) {
            return [
                'httpcode'=> 500,
                'success'=> false,
                'message'=> 'There was a problem on our side: ' . $e->getMessage(),
                'data'=> []
            ];
        }
        catch(Exception $e) {
            return [
                'httpcode'=> 500,
                'success'=> false,
                'message'=> 'There was an unexpected problem: ' . $e->getMessage(),
                'data'=> []
            ];
        }
    }
    public function apiPostAssignTeacher(?int $staffId, int $sectionSubjectId) : array {
        try {
            if(empty($staffId)) {
                return [
                    'httpcode'=> 400,
                    'success'=>false,
                    'message'=> 'Please select a Teacher',
                    'data'=> []
                ];
            }
            $data = $this->teacherModel->upsertSubjectTeacherToSectionSubjects($staffId, $sectionSubjectId);
            if(!$data) {
                return [
                    'httpcode'=> 500,
                    'success'=> false,
                    'message'=> 'Teacher failed to assign',
                    'data'=> []
                ];
            }
            return [
                'httpcode'=> 201,
                'success'=> true,
                'message'=> 'Teacher successfully assigned',
                'data'=> $data
            ];
        }
        catch(DatabaseException $e) {
            return [
                'httpcode'=> 500,
                'success'=> false,
                'message'=> 'There was a problem on our side: ' . $e->getMessage(),
                'data'=> []
            ];
        }
        catch(Exception $e) {
            return [
                'httpcode'=> 500,
                'success'=> false,
                'message'=> 'There was an unexpected problem: ' . $e->getMessage(),
                'data'=> []
            ];
        }
    }
    public function apiPostRegisterTeacher(string $fname, string $mname,string $lname, 
                    string $email, string $cpnumber) : array {
        try {
            $maximumCpDigits = 11;
            if(empty($fname) || empty($lname)) {
                $name = empty($fname) ? 'First name' : 'Last name';
                return [
                    'httpcode'=> 400,
                    'success'=> false,
                    'message'=> $name . 'cannot be empty',
                    'data'=> []
                ];
            }
            $isInvalidNumber = (strlen($cpnumber) < $maximumCpDigits || strlen($cpnumber) > $maximumCpDigits);
            if($isInvalidNumber) {
                $isLessThan = strlen($cpnumber) < $maximumCpDigits ? 'less than' : 'greater than';
                return [
                    'httpcode'=> 400,
                    'success'=> false,
                    'message'=> 'Phone number cannot be '.$isLessThan .' 11 digits',
                    'data'=> []
                ];
            }
            $password = $this->generatePassword->getPassword();
            $hashPassword = password_hash($password, PASSWORD_DEFAULT);
            $insert =$this->teacherModel->insertToStaffAndUser($fname, $mname,$lname, $email, $cpnumber, $hashPassword);
            $this->sendPassword->send_password($lname, $fname, $mname, $cpnumber, $password);
            if(!$insert) {
                return [
                    'httpcode'=> 400,
                    'success'=> false,
                    'message'=> 'Something went wrong with the insert',
                    'data'=> []
                ];
            }
            return [
                'httpcode'=> 201,
                'success'=> true,
                'message'=> 'Teacher successfully registered',
                'data'=> $insert
            ];
        }
        catch(SMSFailureException $e) {
            return [
                'httpcode'=> 500,
                'success'=> false,
                'message'=> 'SMS Error: ' .$e->getMessage(),
                'data'=> []
            ];
        }
        catch(DatabaseException $e) {
            return [
                'httpcode'=> 500,
                'success'=> false,
                'message'=> 'There was a problem on our side: ' . $e->getMessage(),
                'data'=> []
            ];
        }
        catch(Exception $e) {
            return [
                'httpcode'=> 500,
                'success'=> false,
                'message'=> 'There was an unexpected problem: ' . $e->getMessage(),
                'data'=> []
            ];
        }
    }
    //HELPERS
    //VIEW
    public function viewAllTeachers() : array {
        try {
            $data = $this->teacherModel->selectAllTeachers();
            if(empty($data)) {
                return [
                    'success'=> false,
                    'message'=> 'No teachers found',
                    'data'=> []
                ];
            }
            return [
                'success'=> true,
                'message'=> 'Teachers successfully fetched',
                'data'=> $data
            ];
        }
        catch(DatabaseException $e) {
            return [
                'success'=> false,
                'message'=> 'There was a problem on our side' . $e->getMessage(),
                'data'=> []
            ];
        }
        catch(Exception $e) {
            return [
                'success'=> false,
                'message'=> 'There was an unexpected problem' . $e->getMessage(),
                'data'=> []
            ];
        }
    }
}