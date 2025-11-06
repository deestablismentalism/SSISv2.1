<?php
declare(Strict_types=1);
require_once __DIR__ . '/../models/adminSystemManagementModel.php';
class adminSystemManagementController {
    protected $adminSysModel;
    public function __construct() {
        $this->adminSysModel = new adminSystemManagementModel();
    }
    //API
    public function apiUpsertSchoolYearDetails(?string $startDate, ?string $endDate):array {
        try {
            if(is_null($startDate) || is_null($endDate)) {
                return [
                    'httpcode'=> 400,
                    'success'=> false,
                    'message'=> 'Dates cannot be empty'
                ];
            }
            $data = $this->adminSysModel->upsertSchoolYearDetails($startDate,$endDate);
            if(!$data)  {
                return [
                    'httpcode'=> 500,
                    'success'=> false,
                    'message'=> 'Update/Insert failed. Please wait while we look into it'
                ];
            }
            return [
                'httpcode'=> 200,
                'success'=> true,
                'message'=> "Update/Insert successful. School year will start at {$startDate} and end at {$endDate}"
            ];
        }
        catch(DatabaseException $e) {
            return [
                'httpcode'=> 500,
                'success'=> false,
                'message'=> 'There was a server problem. Please wait for it to be fixed'
            ];
        }
        catch(Exception $e) {
            return [
                'success'=> false,
                'message'=> 'There was an unexpected problem'
            ];
        }
    }
    //HELPERS
    //VIEW
    public function viewSchoolYearDetails():array {
        try {
            $data = $this->adminSysModel->getSchoolYearDetails();
            if(empty($data)) {
                return [
                    'success'=> true,
                    'message'=> 'School year details still not set',
                    'data'=> []
                ];
            }
            return [
                'success'=> true,
                'message'=> 'School year details successfully fetched',
                'data'=> $data
            ];
        }
        catch(DatabaseException $e) {
            return [
                'success'=> false,
                'message'=> 'There was a server problem. Please wait for it to be fixed',
                'data'=> []
            ];
        }
        catch(Exception $e) {
            return [
                'success'=> false,
                'message'=> 'There was an unexpected problem',
                'data'=> []
            ];
        }
    }
    public function viewPartialUserLoginActivity():array {
        try {
            $data = $this->adminSysModel->getPartialUserLoginActivity();
            if(empty($data)) {
                return [
                    'success'=> true,
                    'message'=> 'No User login activity recorded yet',
                    'data'=> []
                ];
            }
            return [
                'success'=> true,
                'message'=> 'User login activity successfully fetched',
                'data'=> $data 
            ];
        }
        catch(DatabaseException $e) {
            return [
                'success'=> false,
                'message'=> 'There was a server problem. Please wait for it to be fixed',
                'data'=> []
            ];
        }
        catch(Exception $e) {
            return [
                'success'=> false,
                'message'=> 'There was an unexpected problem',
                'data'=> []
            ];
        }
    }
    public function viewPartialTeacherLoginActivity():array {
        try {
            $data = $this->adminSysModel->getPartialTeacherLoginActivity();
            if(empty($data)) {
                return [
                    'success'=> true,
                    'message'=> 'No Teacher login activity recorded yet',
                    'data'=> []
                ];
            }
            return [
                'success'=> true,
                'message'=> 'Teacher login activity successfully fetched',
                'data'=> $data 
            ];
        }
        catch(DatabaseException $e) {
            return [
                'success'=> false,
                'message'=> 'There was a server problem. Please wait for it to be fixed',
                'data'=> []
            ];
        }
        catch(Exception $e) {
            return [
                'success'=> false,
                'message'=> 'There was an unexpected problem',
                'data'=> []
            ];
        }
    }
    public function viewArchivedStudents():array {
        try {
            $data = $this->adminSysModel->getArchivedStudents();
            if(empty($data)) {
                return [
                    'success'=> true,
                    'message'=> 'No archived Students yet',
                    'data'=> []
                ];
            }
            return [
                'success'=> true,
                'message'=> 'Archived Students successfully fetched',
                'data'=> $data
            ];
        }
        catch(DatabaseException $e) {
            return [
                'success'=> false,
                'message'=> 'There was a server problem. Please wait for it to be fixed',
                'data'=> []
            ];
        }
        catch(Exception $e) {
            return [
                'success'=> false,
                'message'=> 'There was an unexpected problem',
                'data'=> []
            ];
        }
    }
    public function viewArchivedTeachers():array {
        try {
            $data = $this->adminSysModel->getArchivedTeachers();
            if(empty($data)) {
                return [
                    'success'=> true,
                    'message'=> 'No archived Teachers yet',
                    'data'=> []
                ];
            }
            return [
                'success'=> true,
                'message'=> 'Archived Teachers successfully fetched',
                'data'=> $data
            ];
        }
        catch(DatabaseException $e) {
            return [
                'success'=> false,
                'message'=> 'There was a server problem. Please wait for it to be fixed',
                'data'=> []
            ];
        }
        catch(Exception $e) {
            return [
                'success'=> false,
                'message'=> 'There was an unexpected problem',
                'data'=> []
            ];
        }
    }
}