<?php
declare(strict_types=1);
require_once __DIR__ . '/../models/userEnrolleesModel.php';

class userEnrolleesController {
    protected $enrolleesModel;

    public function __construct() {
        $this->enrolleesModel = new userEnrolleesModel();
    }
    //API
    //HELPERS
    //VIEW
    public function viewUserEnrollees(int $userId) : array {
        try {
            if(!is_numeric($userId)) {
                return [
                    'success'=> false,
                    'message'=> 'Invalid user ID',
                    'data'=> []
                ];
            }
            $data = $this->enrolleesModel->getUserEnrollees($userId);
            if(empty($data)) {
                return [
                    'success'=> true,
                    'message'=> 'User Enrollees are empty',
                    'data'=> []
                ];
            }
            return [
                'success'=> true,
                'message'=> 'User Enrollees successfully submitted',
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