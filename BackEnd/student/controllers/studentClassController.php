<?php
declare(strict_types=1);
require_once __DIR__ . '/../models/studentClassModel.php';
require_once __DIR__ . '/../../Exceptions/IdNotFoundException.php';
class studentClassController {
    protected $studentModel;
    public function __construct() {
        $this->studentModel = new studentClassModel();
    }
    //API
    public function apiHistoricalStudentGradeRecords(int $studentId):array {
        try {
            if(is_null($studentId)) {
                throw new IdNotFoundException('Student ID not found');
            }
            $data = $this->studentModel->getStudentHistoricalGrades($studentId);
            if(empty($data)) {
                return [
                    'httpcode'=> 200,
                    'success'=> false,
                    'message'=> 'No historical grade found',
                    'data'=> []
                ];
            }
            $grouped = [];
            foreach($data as $row) {
                $schoolYearId = $row['School_Year_Details_Id'];
                if(!isset($grouped[$schoolYearId])) {
                    $grouped[$schoolYearId] = [
                        'start_year'=> $row['start_year'],
                        'end_year'=> $row['end_year'],
                        'grades'=> []
                    ];
                }
                $grouped[$schoolYearId]['grades'][]= [
                    'Section_Subjects_Id' => $row['Section_Subjects_Id'],
                    'Subject_Name'=> $row['Subject_Name'],
                    'Q1' => $row['Q1'] ?? null,
                    'Q2' => $row['Q2'] ?? null,
                    'Q3' => $row['Q3'] ?? null,
                    'Q4' => $row['Q4'] ?? null
                ];
            }
            return [
                'httpcode' => 200,
                'success'  => true,
                'message'  => 'Historical grades retrieved successfully',
                'data' => array_values($grouped)
            ];
        }
        catch(DatabaseException $e) {
            return [
                'httpcode'=> 500,
                'success'=> false,
                'message'=> 'There was a server problem. '.$e->getMessage(),
                'data'=> []
            ];
        }
        catch(Exception $e) {
            return [
                'httpcode'=> 400,
                'success'=> false,
                'message'=> 'There was an unexpected problem. Please wait for it to be fixed',
                'data'=> []
            ];
        }
    }
    //HELPERS
    private function returnPivotedSchedule(array $data) : array {
        try {
            
        }
        catch(DatabaseException $e) {
            return [
                'success'=> false,
                'message'=> 'There was a server problem. ',
                'data'=> []
            ];
        }
        catch(Exception $e) {
            return [
                'success'=> false,
                'message'=> 'There was an unexpected problem. Please wait for it to be fixed',
                'data'=> []
            ];
        }
    }
    //VIEW
    public function viewThisStudentSimpleDetails(?int $studentId):array {
        try {
            if(is_null($studentId)) {
                throw new IdNotFoundException('Student ID not found');
            }
            $data = $this->studentModel->getThisStudentsSimpleDetails($studentId);
            if(empty($data)) {
                return [
                    'success'=> true,
                    'message'=> 'This student has no LRN',
                    'data'=> []
                ];
            }
            return [
                'success'=> true,
                'message'=> 'LRN successfully fetched',
                'data'=> $data
            ];
        }
        catch(DatabaseException $e) {
            return [
                'success'=> false,
                'message'=> 'There was a server problem. ',
                'data'=> []
            ];
        }
        catch(Exception $e) {
            return [
                'success'=> false,
                'message'=> 'There was an unexpected problem. Please wait for it to be fixed',
                'data'=> []
            ];
        }
    }
    public function viewClassSchedule(?int $studentId):array {
        try {
            if(is_null($studentId)) {
                throw new IdNotFoundException('Student ID not found or recognized');
            }
            $data = $this->studentModel->getStudentClassSchedules($studentId);
            if(empty($data)) {
                return [
                    'success'=> true,
                    'message'=> 'Class schedules are empty',
                    'data'=> $data
                ];
            }
            return [
                'success'=> true,
                'message'=>'Class schedules successfully fetched',
                'data'=> $data
            ];
        }
        catch(DatabaseException $e) {
            return [
                'success'=> false,
                'message'=> 'There was a server problem. ',
                'data'=> []
            ];
        }
        catch(Exception $e) {
            return [
                'success'=> false,
                'message'=> 'There was an unexpected problem. Please wait for it to be fixed',
                'data'=> []
            ];
        }
    }
    public function viewStudentGrades(?int $studentId):array {
        try {
            $data = $this->studentModel->getStudentGrades($studentId);
            if(empty($data)) {
                return [
                    'success'=> true,
                    'message'=> 'There are no student grades yet',
                    'data'=> []
                ];
            }
            return [
                'success'=> true,
                'message'=> "Student's grades successfully fetched",
                'data'=> $data
            ];
        }
        catch(DatabaseException $e) {
            return [
                'success'=> false,
                'message'=> 'There was a server problem',
                'data'=> []
            ];
        }
        catch(Exception $e) {
            return [
                'success'=> false,
                'message'=> 'There was an unexpected problem. Please wait for it to be fixed',
                'data'=> []
            ];
        }
    }
    public function viewStudentSectionClassmates(?int $studentId) {
        try {
            if(is_null($studentId)) {
                throw new IdNotFoundException('Student ID not found');
            }
            $data = $this->studentModel->getStudentSectionClassmates($studentId);
            $sectionName = $this->studentModel->getStudentSectionName($studentId);
            if(empty($data) && empty($sectionName)) {
                return [
                    'success'=> true,
                    'message'=> 'No section details to show. Please wait to be assigned to a section',
                    'section_name'=> '',
                    'data'=> []
                ];
            }
            return [
                'success'=> true,
                'message'=> 'Section details successfully fetched',
                'section_name'=> $sectionName,
                'data'=> $data
            ];
        }
        catch(DatabaseException $e) {
            return [
                'success'=> false,
                'message'=> 'There was a server problem',
                'data'=> []
            ];
        }
        catch(Exception $e) {
            return [
                'success'=> false,
                'message'=> 'There was an unexpected problem. Please wait for it to be fixed',
                'data'=> []
            ];
        }
    }
}