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
    //VIEW
    //GETTERS
}