<?php
declare(strict_types=1);
require_once __DIR__ . '/../models/adminEnrollmentTransactionsModel.php';
require_once __DIR__ . '/../../Exceptions/DatabaseException.php';

class adminUnprocessedEnrollmentsController {
    protected $transactionsModel;

    public function __construct() {
        $this->transactionsModel = new adminEnrollmentTransactionsModel();
    }
    //API
    //VIEW
    public function viewMarkedEnrolledTransactions() : array {
        try {
            $data = $this->transactionsModel->getEnrolledTransactions();
            if(empty($data)) {
                return [
                    'success'=> false,
                    'message'=> 'No enrolled transactions found',
                    'data'=> []
                ];
            }
            return [
                'success'=> true,
                'message'=> 'Enrolled transactions successfully fetched',
                'data'=> $data
            ];
        }
        catch(DatabaseException $e) {
            return [
                'success'=> false,
                'message'=> 'There was a problem on our side: ' . $e->getMessage(),
                'data'=>[]
            ];
        }
        catch(Exception $e){
            return [
                'success'=> false,
                'message'=> 'There was an unexpected problem: ' . $e->getMessage(),
                'data'=>[]
            ];
        }
    }
    public function viewMarkedFollowedUpTransactions() : array {
        try {
            $data = $this->transactionsModel->getFollowedUpTransactions();
            if(empty($data)) {
                return [
                    'success'=> false,
                    'message'=> 'No followed up transactions found',
                    'data'=> []
                ];
            }
            return [
                'success'=> true,
                'message'=> 'Followed up transactions successfully fetched',
                'data'=> $data
            ];
        }
        catch(DatabaseException $e) {
            return [
                'success'=> false,
                'message'=> 'There was a problem on our side: ' . $e->getMessage(),
                'data'=>[]
            ];
        }
        catch(Exception $e){
            return [
                'success'=> false,
                'message'=> 'There was an unexpected problem: ' . $e->getMessage(),
                'data'=>[]
            ];
        }
    }
    public function viewMarkedDeniedTransactions() : array {
        try {
            $data = $this->transactionsModel->getDeniedTransactions();
            if(empty($data)) {
                return [
                    'success'=> false,
                    'message'=> 'No enrolled transactions found',
                    'data'=> []
                ];
            }
            return [
                'success'=> true,
                'message'=> 'Enrolled transactions successfully fetched',
                'data'=> $data
            ];
        }
        catch(DatabaseException $e) {
            return [
                'success'=> false,
                'message'=> 'There was a problem on our side: ' . $e->getMessage(),
                'data'=>[]
            ];
        }
        catch(Exception $e){
            return [
                'success'=> false,
                'message'=> 'There was an unexpected problem: ' . $e->getMessage(),
                'data'=>[]
            ];
        }
    }
    //GETTERS
    
}