<?php
declare(strict_types=1);

require_once __DIR__ . '/../models/adminSubjectsModel.php';
require_once __DIR__ . '/../../Exceptions/DatabaseException.php';

class adminSubjectsController {
    protected $subjectsModel;

    public function __construct() {
        $this->subjectsModel = new adminSubjectsModel();
    }
    //API
    public function apiGetSubjectsPerGradeLevel() : array {
        try {
            $data = $this->subjectsModel->getSubjectsPerGradeLevel();

            if (empty($data)) {
                return [
                    'httpcode'=> 200,
                    'success'=> false,
                    'message'=> 'Subjects list are empty',
                    'data'=> []
                ];
            }

            return [
                'httpcode'=> 200,
                'success'=> true,
                'message'=> 'Subjects list successfully fetched',
                'data'=> $data
            ];
        }
        catch(DatabaseException $e) {
            return [
                'httpcode'=> 500,
                'success'=> false,
                'message'=> 'Database error: ' . $e->getMessage(),
                'data' => []
            ];
        }
        catch(Exception $e) {
            return [
                'httpcode'=> '400',
                'success'=> false,
                'message'=> 'Error: ' . $e->getMessage(),
                'data'=> []
            ];
        }
    }
    public function apiPostAddSubject(string $subjectName, $subjectId) : array {
        try {
            if(empty($subjectName)) {
                return [
                    'httpcode'=> 409,
                    'success'=> false,
                    'message'=> 'Subject name cannot be empty',
                    'data'=> []
                ];
            }
            $upperCaseName = strtoupper($subjectName);
            if(is_array($subjectId)) {
                if(empty($subjectId)) {
                    return [
                        'httpcode'=> 400,
                        'success'=> false,
                        'message'=> 'Please select grade levels',
                        'data'=> []
                    ];
                }
                $failedCount = [];
                $insertCount = [];
                foreach($subjectId as $values) {
                    $insert = $this->subjectsModel->insertSubjectAndLevel($upperCaseName, $values);
                    if(!$insert) {
                        $failedCount[] = [
                            'ID'=> $values
                        ];
                    }
                    $insertCount[] = [
                        'ID'=> $values
                    ];
                }
                if(count($failedCount) === count($insertCount)) {
                    return [
                        'httpcode'=> 400,
                        'success'=> false,
                        'message'=> 'All subjects failed to insert',
                        'data'=> $failedCount
                    ];
                }
                if(!empty($failedCount) && empty($failedCount) < empty($insertCount)) {
                    return [
                        'httpcode'=> 207,
                        'success'=> true,
                        'message'=> 'Some subjects failed to insert',
                        'data'=> $failedCount
                    ];
                }
                return [
                    'httpcode'=> 201,
                    'success'=> true,
                    'message'=> 'All subjects inserted successfully',
                    'data'=> $insertCount
                ];
            }
            else {
                if(!is_numeric($subjectId)) {
                    return [
                        'httpcode'=> 409,
                        'success'=> false,
                        'message'=> 'Invalid ID',
                        'data'=> []
                    ];
                }
                $insert = $this->subjectsModel->insertSubjectAndLevel($upperCaseName, $subjectId);
                if(!$insert) {
                    return [
                        'httpcode'=> 500,
                        'success'=> false,
                        'message'=> 'Subject failed to insert',
                        'data'=> $insert
                    ];
                }
                return [
                    'httpcode'=> 201,
                    'success'=> true,
                    'message'=> 'Subject inserted successfully',
                    'data'=> $insert
                ];
            }
            
        }
        catch(DatabaseException $e) {
            return [
                'httpcode'=> 500,
                'success'=> false,
                'message'=> 'Database error: ' . $e->getMessage(),
                'data' => []
            ];
        }
        catch(Exception $e) {
            return [
                'httpcode'=> '400',
                'success'=> false,
                'message'=> 'Error: ' . $e->getMessage(),
                'data'=> []
            ];
        }
    }
    public function apiPostAssignSubjectTeacher() : array {
        try {
            $data = $this->sectionsModel->insertSubjectTeacher();
        }
        catch(DatabaseException $e) {
            return [
                'httpcode'=> 500,
                'success'=> false,
                'message'=> 'Database error: ' . $e->getMessage(),
                'data' => []
            ];
        }
        catch(Exception $e) {
            return [
                'httpcode'=> '400',
                'success'=> false,
                'message'=> 'Error: ' . $e->getMessage(),
                'data'=> []
            ];
        }
    }
    //GETTERS 
}