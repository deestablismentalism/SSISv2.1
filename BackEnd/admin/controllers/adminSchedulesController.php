<?php

declare(strict_types=1);
require_once __DIR__ . '/../models/adminSchedulesModel.php';
require_once __DIR__ . '/../../Exceptions/DatabaseException.php';

class adminSchedulesController {
    protected $schedulesModel;

    public function __construct() {
        $this->schedulesModel = new adminSchedulesModel();
    }
    //API
    public function apiPostSectionSchedule(int $sectionSubjectId, $day,$timeStart, $timeEnd) : array {
        try {
            $data = $this->schedulesModel->insertSectionSchedule($sectionSubjectId, $day, $timeStart, $timeEnd);

            if(empty($day)) {
                return[
                    'httpcode'=> 400,
                    'success'=> false,
                    'message'=> 'Pick a day',
                    'data'=> []
                ];
            }
            if(empty($timeStart) || empty($timeEnd)) {
                return [
                    'httpcode'=> 400,
                    'success'=> false,
                    'message'=> 'Please set the time properly',
                    'data'=> []
                ];
            }
            if(!$data) {
                return [
                    'httpcode'=> 500,
                    'success'=> false,
                    'message'=> 'There was a problem on our side',
                    'data'=> []
                ];
            }
            return [
                'httpcode'=> 201,
                'success'=> true,
                'message'=> 'Section schedule successfully inserted',
                'data'=> $data
            ];
        }
        catch(DatabaseException $e) {
            return [
                'httpcode'=> 500,
                'success'=> false,
                'message'=> 'There was a problem on our side: ' . $e->getMessage(),
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
    public function apiFetchAllSectionSubjects() : array {
        try {
            $data = $this->schedulesModel->getAllSectionSubjects();

            if(empty($data)) {
                return [
                    'httpcode'=> 200,
                    'success' => false,
                    'message' => 'No subject found',
                    'data'=> []
                ];
            }

            return [
                'httpcode'=> 200,
                'success'=> true,
                'message'=> 'Subjects successfully fetched',
                'data'=> $data
            ];
        }
        catch(DatabaseException $e) {
            return [
                'httpcode'=> 500,
                'success'=> false,
                'message'=> 'There was a problem on our side: ' . $e->getMessage(),
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
    //VIEW
    public function viewAllSchedules() : array {
        try {
            $data = $this->schedulesModel->getAllSchedules();
            if(empty($data)) {
                return [
                    'success'=> false,
                    'message'=> 'Schedules list is empty',
                    'data'=> []
                ];
            }
            return [
                'success'=> true,
                'message'=> 'Schedules list successfully fetched',
                'data'=> $data
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
    //GETTERS
    
}