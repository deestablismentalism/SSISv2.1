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
            $data = $this->subjectsModel->getSubjectsPerSection();

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
    public function apiPostAddSubject(string $subjectName, $gradeLevelId) : array {
        try {
            if(empty($subjectName)) {
                return [
                    'httpcode'=> 409,
                    'success'=> false,
                    'message'=> 'Subject name cannot be empty',
                    'data'=> []
                ];
            }
            if(empty($gradeLevelId)) {
                return [
                    'httpcode'=> 400,
                    'success'=> false,
                    'message'=> 'Please select grade levels',
                    'data'=> []
                ];
            }
            $upperCaseName = strtoupper($subjectName);
            if(is_array($gradeLevelId)) {
                if(is_numeric($gradeLevelId)) {
                    return [
                        'httpcode'=> 400,
                        'success'=> false,
                        'message'=> 'Invalid ID',
                        'data'=> []
                    ];
                }
                $insert = $this->subjectsModel->insertSubjectAndLevel($upperCaseName, $gradeLevelId);
                if(empty($insert['success'])) {
                    return [
                        'httpcode'=> 400,
                        'success'=> false,
                        'message'=> 'All subjects failed to insert',
                        'data'=> [
                            'failed'=> $insert['failed']
                        ]
                    ];
                }
                if(!empty($insert['failed']) &&  count($insert['failed']) < count($insert['success'])) {
                    return [
                        'httpcode'=> 207,
                        'success'=> true,
                        'message'=> 'Some subjects failed to insert',
                        'data'=> [
                            'success'=> $insert['success'],
                            'failed'=> $insert['failed']
                        ]
                    ];
                }
                if(!empty($insert['existing']) && count($insert['existing']) < count($gradeLevelId)) {
                    return [
                        'httpcode'=> 207,
                        'success'=> true,
                        'message'=> 'Some subjects failed to insert due to conflicts with other subjects',
                        'data'=> [
                            'failed'=> $insert['existing']
                        ]
                        ];
                }
                return [
                    'httpcode'=> 201,
                    'success'=> true,
                    'message'=> 'All subjects inserted successfully',
                    'data'=> $insert['success']
                ];
            }   
            else {
                if(!is_numeric($gradeLevelId)) {
                    return [
                        'httpcode'=> 409,
                        'success'=> false,
                        'message'=> 'Invalid ID',
                        'data'=> []
                    ];
                }
                $insert = $this->subjectsModel->insertSubjectAndLevel($upperCaseName, [$gradeLevelId]);
                if(!empty($insert['failed'])) {
                    return [
                        'httpcode'=> 500,
                        'success'=> false,
                        'message'=> 'Subject failed to insert. Make sure the input does not exist',
                        'data'=> $insert['failed']
                    ];
                }
                 if(!empty($insert['existing'])) {
                    return [
                        'httpcode'=> 400,
                        'success'=> true,
                        'message'=> 'Some subjects failed to insert due to same existing subjects',
                        'data'=> [
                            'failed'=> $insert['existing']
                        ]
                    ];
                }
                return [
                    'httpcode'=> 201,
                    'success'=> true,
                    'message'=> 'Subject inserted successfully',
                    'data'=> $insert['success']
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
    public function apiGetSubjectsGrouped() : array {
        try {
            $data = $this->subjectsModel->getSubjectsGrouped();
            if(empty($data)) {
                return [
                    'httpcode'=> 200,
                    'success'=> false,
                    'message'=> 'No subjects found',
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
                'message'=> 'Database error: ' . $e->getMessage(),
                'data'=> []
            ];
        }
        catch(Exception $e) {
            return [
                'httpcode'=> 500,
                'success'=> false,
                'message'=> 'Error: ' . $e->getMessage(),
                'data'=> []
            ];
        }
    }
    public function apiGetSectionsBySubject(int $subjectId) : array {
        try {
            if(empty($subjectId)) {
                return [
                    'httpcode'=> 400,
                    'success'=> false,
                    'message'=> 'Subject ID is required',
                    'data'=> []
                ];
            }
            $data = $this->subjectsModel->getSectionsBySubjectId($subjectId);
            if(empty($data)) {
                return [
                    'httpcode'=> 200,
                    'success'=> false,
                    'message'=> 'No sections found for this subject',
                    'data'=> []
                ];
            }
            return [
                'httpcode'=> 200,
                'success'=> true,
                'message'=> 'Sections successfully fetched',
                'data'=> $data
            ];
        }
        catch(DatabaseException $e) {
            return [
                'httpcode'=> 500,
                'success'=> false,
                'message'=> 'Database error: ' . $e->getMessage(),
                'data'=> []
            ];
        }
        catch(Exception $e) {
            return [
                'httpcode'=> 500,
                'success'=> false,
                'message'=> 'Error: ' . $e->getMessage(),
                'data'=> []
            ];
        }
    }
    //VIEW 
    public function viewSubjectsPerSection() : array {
        try {
            $data = $this->subjectsModel->getSubjectsPerSection();
            if(empty($data)) {
                return [
                    'success'=> false,
                    'message'=> 'No subject found',
                    'data'=> []
                ];
            }
            return [
                'success'=> true,
                'message'=> 'Subjects successfully fetched',
                'data'=>  $data
            ];
        }
        catch(DatabaseException $e) {
            return [
                'success'=> false,
                'message'=> 'There was a problem on our side' . $e->getMessage(),
                'data'=> []
            ];
        }
        catch(Exception $e)  {
            return [
                'success'=> false,
                'message'=> 'There was an unexpected problem: ' . $e->getMessage(),
                'data'=> []
            ];
        }
    }
    //GETTERS 
}