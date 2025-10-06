<?php
declare(strict_types=1);
require_once __DIR__ . '/../models/adminStudentsModel.php';
require_once __DIR__ . '/../../Exceptions/DatabaseException.php';

class adminStudentsController {
    protected $studentsModel;

    public function __construct() {
        $this->studentsModel = new adminStudentsModel();
    }

    //API
    
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