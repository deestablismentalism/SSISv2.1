<?php
declare(strict_types=1);
require_once __DIR__ . '/../models/teacherSectionAdvisersModel.php';
require_once __DIR__ . '/../models/teacherSubjectsModel.php';
require_once __DIR__ . '/../../Exceptions/DatabaseException.php';

class teacherAdvisoryController {
    protected $sectionsModel;
    protected $subjectsModel;

    public function __construct() {
        $this->sectionsModel = new teacherSectionAdvisersModel();
        $this->subjectsModel = new teacherSubjectsModel();
    }
    //API
    //HELPERS
    private function returnMaleStudents(int $sectionId) : array {
        try {
            if(empty($sectionId)) {
                return [
                    'success'=> false,
                    'message'=> 'Section ID not found',
                    'data'=> []
                ];
            }
            $maleStudents = $this->sectionsModel->getMaleStudents($sectionId);
            if(empty($maleStudents)) {
                return [
                    'success'=> false,
                    'message'=> 'Failed to fetch male students',
                    'data'=> []
                ];
            }
            return [
                'success'=> true,
                'message'=> 'Male students successfully fetched',
                'data'=> $maleStudents
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
                'message'=> 'There was an unexpected problem: ' . $e->getMessage(),
                'data'=> []
            ];
        }
    }
    private function returnFemaleStudents( int $sectionId) : array {
        try {
            if(empty($sectionId)) {
                return [
                    'success'=> false,
                    'message'=> 'Section ID not found',
                    'data'=> []
                ];
            }
            $femaleStudents = $this->sectionsModel->getFemaleStudents();
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
                'message'=> 'There was an unexpected problem: ' . $e->getMessage(),
                'data'=> []
            ];
        }
    }
    //VIEW
    public function viewSectionSubjects(int $sectionId) : array {
        try {
            if(empty($sectionId)) {
                return [
                    'success'=> false,
                    'message'=> 'Section ID not found',
                    'data'=> []
                ];
            }
            $subjects = $this->subjectsModel->getSectionSubjects($sectionId);
            if(empty($subjects)) {
                return [
                    'success'=> false,
                    'message'=> 'No section subjects found',
                    'data'=> []
                ];
            }
            return [
                'success'=> true,
                'message'=> 'Section subjects successfully fetched',
                'data'=> $subjects
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
                'message'=> 'There was an unexpected problem: ' . $e->getMessage(),
                'data'=> []
            ];
        }
    }
    public function viewCheckIfAdvisory(int $staffId, int $sectionId) : array {
        try {
            if(empty($sectionId)) {
                return [
                    'success'=> false,
                    'message'=> 'Section ID not found',
                    'data'=> []
                ];
            }
            if(empty($staffId)) {
                return [
                    'success'=> false,
                    'message'=> 'Staff ID not found',
                    'data'=> []
                ];
            }
            $isAdvisory = $this->sectionsModel->checkIfAdvisory($staffId,$sectionId);
            return [
                'success'=> $isAdvisory,
                'message'=> $isAdvisory ? 'This is your advisory' : 'Unauthorized access. No data to display',
                'data'=> []
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
                'message'=> 'There was an unexpected problem: ' . $e->getMessage(),
                'data'=> []
            ];
        }
    }
    public function viewSectionName(int $sectionId) : array {
        try {
            if(empty($sectionId)) {
                return [
                    'success'=> false,
                    'message'=> 'Section ID not found',
                    'data'=> []
                ];
            }
            $sectionName = $this->sectionsModel->getSectionName($sectionId);
            if(empty($sectionId)) {
                return [
                    'success'=> false,
                    'message'=> 'No section name yet',
                    'data'=> []
                ];
            }
            return [
                'success'=> true,
                'message'=> 'Section name successfully fetched',
                'data'=> $sectionName
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
                'message'=> 'There was an unexpected problem: ' . $e->getMessage(),
                'data'=> []
            ];
        }
    }
    public function viewStudents(int $sectionId) : array {
        try {
            if(empty($sectionId)) {
                return [
                    'success'=> false,
                    'message'=> 'Section ID not found',
                    'data'=> []
                ];
            }
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
                'message'=> 'There was an unexpected problem: ' . $e->getMessage(),
                'data'=> []
            ];
        }
    }
    public function viewSectionStudents(int $sectionId) : array {
        try {
            if(empty($sectionId)) {
                return [
                    'success'=> false,
                    'message'=> 'Section ID not found',
                    'data'=> []
                ];
            }
            $students = $this->sectionsModel->getSectionStudents($sectionId);
            if(empty($students)) {
                return [
                    'success'=> false,
                    'message'=> 'No students yet',
                    'data'=> []
                ];
            }
            return [
                'success'=> true,
                'message'=> 'Students successfully fetched',
                'data'=> $students
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
                'message'=> 'There was an unexpected problem: ' . $e->getMessage(),
                'data'=> []
            ];
        }
    }
}