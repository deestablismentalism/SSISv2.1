<?php
declare(strict_types=1);

require_once __DIR__ . '/../models/teacherGradesModel.php';

class teacherGradesController {
    protected $gradesModel;
    private const GRADE_THRESHOLD = 100;
    private const QUARTER_ARRAY  = [1,2,3,4];
    public function __construct() {
        $this->gradesModel = new teacherGradesModel();
    }
    //API
    public function apiPostStudentGrades(array $formData):array {
        $validationErrors = [
            'errors'=> [],
        ];
        try {
            foreach($formData as $index => $data) {
                $stuIndex = $index + 1;
                if(!isset($data['sec-sub-id'])) {
                    $validationErrors['errors'][] = "Invalid section subject for Student No. {$stuIndex}";
                }
                if(!isset($data['student-id'])) {
                    $validationErrors['errors'][] = 'Student No.' .$index + 1 . 'is not found'; 
                }
                if(!in_array($data['quarter'],self::QUARTER_ARRAY)) {
                    $validationErrors['errors'][] = "Quarter for Student No. {$stuIndex} is not within the acceptable quarters";
                }
                if($data['grade-value'] > self::GRADE_THRESHOLD) {
                    $validationErrors['errors'][] = "Grade value for Student No. {$stuIndex} exceeded 100";
                }
            }
            if(!empty($validationErrors['errors'])) {
                return [
                    'httpcode'=> 400,
                    'success'=> false,
                    'message'=> $validationErrors['errors'],
                    'data'=> []
                ];
            }
            $result = $this->gradesModel->upsertStudentGrades($formData);
            if($result['all-failed']) {
                return [
                    'httpcode'=>400,
                    'success'=> false,
                    'message'=> 'There were no successful changes',
                    'details'=> !empty($result['details']) ? $result['details'] : '',
                    'data'=> []
                ];
            }
            if(!empty($result['no_value']) && !empty($result['success'])) {
                return [
                    'httpcode'=> 200,
                    'success'=> true,
                    'message'=> 'Grades successfully changed. Some were not given values',
                    'data'=> []    
                ];
            }
            if(!empty($result['failed']) && !empty($result['success'])) {
                return [
                    'httpcode'=>200,
                    'success'=> true,
                    'message'=> 'Some changes failed',
                    'failed'=> $result['failed'],
                    'data'=> $result['success']
                ];
            }
            return [
                'httpcode'=> 200,
                'success'=> true,
                'message'=> 'All changes are successfully saved',
                'details'=> !empty($result['details']) ? $result['details'] : '',
                'data'=> $result['success']
            ];
        }
        catch(DatabaseException $e){
            return ['httpcode'=> 500,'success'=> false,'message'=> 'There was a problem on our side: ' .$e->getMessage(),'data'=> []];       
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
    public function apiFetchSectionSubjectStudents(int $sectionSubjectId,int $staffId, int $quarter) : array {
        try {
            $data = $this->gradesModel->getStudentsOfSectionSubject($sectionSubjectId, $staffId, $quarter);
            if(empty($data)) {
                return [
                    'httpcode'=> 200,
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