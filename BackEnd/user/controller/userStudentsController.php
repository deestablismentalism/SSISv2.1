<?php
declare(strict_types=1);
require_once __DIR__ . '/../models/userStudentsModel.php';

class userStudentsController {
    protected $studentsModel;
    public function __construct() {
        $this->studentsModel = new userStudentsModel();
    }
    //API
    //HELPERS
    //VIEW
    public function viewUserStudents(int $userId) : array {
        try {
            if(!is_numeric($userId)) {
                return [
                    'success'=> false,
                    'message'=> 'Invalid ID',
                    'data'=> []
                ];
            }
            $data = $this->studentsModel->getUserStudents($userId);
            if(empty($data)) {
                return [
                    'success'=> true,
                    'message'=> 'User students are empty',
                    'data'=> []
                ];
            }
            return [
                'success'=> true,
                'message'=> 'User students found',
                'data'=> $data
            ];
        }
        catch(DatabaseException $e) {
            return [
                'success'=> false,
                'message'=> 'There was a problem on our side: '.$e->getMessage(),
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