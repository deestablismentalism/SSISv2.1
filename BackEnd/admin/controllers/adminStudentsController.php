<?php
declare(strict_types=1);
require_once __DIR__ . '/../models/adminStudentsModel.php';
require_once __DIR__ . '/../../Exceptions/DatabaseException.php';
require_once __DIR__ . '/../../Exceptions/IdNotFoundException.php';

class adminStudentsController {
    protected $studentsModel;

    public function __construct() {
        $this->studentsModel = new adminStudentsModel();
    }
    //API
    public function apiDeleteAndArchiveStudent(?int $studentId):array {
        try {
            if(is_null($studentId)) {
                throw new IdNotFoundException('Student ID not found');
            }
            $data = $this->studentsModel->deleteAndArchiveStudent($studentId);
            if(!$data['success']) {
                return [
                    'httpcode'=> 400,
                    'success'=> $data['success'],
                    'message'=>$data['message'],
                    'data'=> []
                ];
            }
            return [
                'httpcode'=> 200,
                'success'=> $data['success'],
                'message'=> $data['message'],
                'data'=>[]
            ];
        }
        catch(DatabaseException $e) {
            return [
                'httpcode'=> 500,
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage(),
                'data' => []
            ];
        }
        catch(Exception $e) {
            return [
                'httpcode'=> 500,
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }
    //Views
    public function viewStudents() : array {
        try {
            $data = $this->studentsModel->getAllStudents();

            if(empty($data)) {
                return [
                    'success' => false,
                    'message' => 'Students are empty',
                    'data' => []
                ];
            }
            return [
                'success'=> true,
                'message' => 'Students successfully fetched',
                'data' => $data
            ];
        }
        catch(DatabaseException $e) {
            return [
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage(),
                'data' => []
            ];
        }
        catch(Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }
    public function viewStudentInformation(?int $studentId):array {
        try {
            if(is_null($studentId)) {
                throw new IdNotFoundException('Student ID not found');
            }
            $data = $this->studentsModel->getStudentPersonalInfo($studentId);
            $parents = $this->formatParentsInformation($studentId);
            if(!$parents['success']) {
                return [
                    'success'=> false,
                    'message'=> $parents['message'],
                ];
            }
            if(empty($data) && empty($parents['data'])) {
                return [
                    'success'=> false,
                    'message'=> 'No Student information found',
                ];
            }
            return [
                'success'=> true,
                'message'=> 'No Student information found',
                'data'=> $data,
                'parent'=> $parents['data']
            ];
        }
        catch(DatabaseException $e) {
            return [
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage(),
                'data'=> []
            ];
        }
        catch(Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'data'=> []
            ];
        }
    }
    //private getters
    private function formatParentsInformation(int $studentId) :array {
        try {
            $parents = $this->studentsModel->getStudentParentInformation($studentId);
            if(empty($parents)) {
                return [
                    'success'=> true,
                    'message'=> 'No Parent information found',
                    'data'=> $parents
                ];
            }
            else {
                $parentArray = [];
                foreach($parents as $parent) {
                    $parentType = $parent['Parent_Type'];
                    $parentArray[ucfirst($parentType)] = $parent;
                }
                return  [
                    'success'=> true,
                    'data'=> $parentArray
                ];
            }
        }
        catch(DatabaseException $e) {
            return [
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage(),
            ];
        }
        catch(Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ];
        }
    }
}