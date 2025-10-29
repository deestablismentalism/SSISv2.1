<?php
declare(strict_types=1);
require_once __DIR__ . '/../models/teacherSubjectsModel.php';
require_once __DIR__ . '/../../Exceptions/DatabaseException.php';

class teacherSubjectsHandledController {
    protected $subjectsModel;

    public function __construct() {
        $this->subjectsModel = new teacherSubjectsModel();
    }
    //API
    //HELPERS
    //VIEW
    public function viewSubjectsHandled(?int $staffId) : array {
        try {
            if(empty($staffId)) {
                return [
                    'success'=> false,
                    'message'=> 'Staff ID not found',
                    'data'=> []
                ];
            }
            if(!is_int($staffId)) {
                return [
                    'success'=> false,
                    'message'=> 'Invalid ID',
                    'data'=> []
                ];
            }
            $data = $this->subjectsModel->getTeacherSubjectsHandled($staffId);
            if(!$data) {
                return [
                    'success'=> false,
                    'message'=> 'Failed to fetch subjects handled',
                    'data'=> []
                ];
            }
            if(empty($data)) {
                return [
                    'success'=> false,
                    'message'=> 'No  subjects handled yet',
                    'data'=> []
                ];
            }
            return [
                'success'=> true,
                'message'=> 'Subjects handled successfully fetched',
                'data'=> $data
            ];
        }
        catch(DatabaseException $e) {
            return[
                'success'=> false,
                'message'=> 'There was a problem on our side ' .$e->getMessage(),
                'data'=> [] 
            ];
        }
        catch(Exception $e) {
            return [
                'success'=> false,
                'message'=> 'There was an unexpected problem ' . $e->getMessage(),
                'data'=> []
            ];
        }
    }
}