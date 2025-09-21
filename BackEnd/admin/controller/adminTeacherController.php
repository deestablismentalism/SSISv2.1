<?php
declare(strict_types=1);

require_once __DIR__ . '/../models/adminTeachersModel.php';
require_once __DIR__ . '/../../Exceptions/DatabaseException.php';

class adminTeacherController {
    protected $teacherModel;

    public function  __construct() {
        $this->teacherModel = new adminTeachersModel();
    }
    //API
    public function apiPostAssignTeacher(int $staffId, int $sectionSubjectId) : array {
        try {
            if($staffId <= 0) {
                return [
                    'httpcode'=> 400,
                    'success'=>false,
                    'message'=> 'Please select a Teacher',
                    'data'=> []
                ];
            }
            $data = $this->teacherModel->updateSubjectTeacherToSectionSubjects($staffId, $sectionSubjectId);
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
                'message'=> 'There was a problem on our side: ' . $e->getMesage(),
                'data'=> []
            ];
        }
        catch(Exception $e) {
            return [
                'httpcode'=> 500,
                'success'=> false,
                'message'=> 'There was an unexpected problem: ' . $e->getMesage(),
                'data'=> []
            ];
        }
    }
    //VIEW
    //GETTERS
}