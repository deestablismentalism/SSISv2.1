<?php
declare(strict_types=1);

require_once __DIR__ . '/../models/teacherGradesModel.php';
require_once __DIR__ . '/../../Exceptions/DatabaseException.php';

class teacherGradesController {
    protected $gradesModel;

    public function __construct() {
        $this->gradesModel = new teacherGradesModel();
    }

    //API
    public function apiFetchSectionSubjectStudents(int $sectionSubjectId,int $staffId) : array {
        try {
            $data = $this->gradesModel->getStudentsOfSectionSubject($sectionSubjectId, $staffId);
            if(empty($data)) {
                return [
                    'httpcode'=> 204,
                    'success'=> false,
                    'message'=> 'No students yet',
                    'data'=> []
                ];
            }
            return [
                'httpcode'=> 200,
                'success'=> true,
                'message'=> 'Students successfully fetched',
                'data'=> $data
            ];
        }
        catch(DatabaseException $e) {
            return [
                'httpcode'=> 500,
                'success'=> false,
                'message'=> 'There was a problem on our side: ' .$e->getMessage(),
                'data'=> []
            ];
        }
        catch(Exception $e) {
            return [
                'httpcode'=> 400,
                'success'=> false,
                'message'=> 'There was an unexpected problem: ' . $e->getMessage(),
                'data'=> []
            ];
        }
    }
    //HELPERS
    //VIEW
    public function viewSubjectsToGrade(int $staffId) : array {
        try {
            if(empty($staffId)) {
                return [
                    'success'=> false,
                    'message'=> 'Staff ID not found',
                    'data'=> []
                ];
            }
            $subjectsToGrade = $this->gradesModel->getSubjectsToGrade($staffId);
            if(empty($subjectsToGrade)) {
                return [
                    'success'=> false,
                    'message'=> 'No subjects to grade',
                    'data'=> []
                ];
            }
            return [
                'success'=> true,
                'message'=> 'Subjects to grade successfully fetched',
                'data'=> $subjectsToGrade
            ];
        }
        catch(DatabaseException $e) {
            return [
                'success'=> false,
                'message'=> 'There was a problem on our side: ' . $e->getMessage(),
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