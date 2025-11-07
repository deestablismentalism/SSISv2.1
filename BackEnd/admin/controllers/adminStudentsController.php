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
    //private getters
}