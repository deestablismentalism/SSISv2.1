<?php
declare(strict_types=1);
require_once __DIR__ . '/../models/adminSectionsModel.php';
require_once __DIR__ . '/../../Exceptions/DatabaseException.php';

class adminSectionsController {
    protected $sectionsModel;

    public function __construct() {
        $this->sectionsModel = new adminSectionsModel();
    }
    //API
    public function apiAddSectionForm(string $sectionName, ?int $gradeLevelId) : array {
        try {
            if(empty($sectionName)) {
                return [
                    'httpcode'=> 409,
                    'success'=>  false,
                    'message'=> 'Section name cannot be empty',
                    'data'=> []
                ];
            }
            if($gradeLevelId !== null && !is_numeric($gradeLevelId)) {
                return [
                    'httpcode'=> 409,
                    'success'=> false,
                    'message'=> 'Invalid grade level provided',
                    'data'=> []
                ];
            }
            $sectionNameExists = $this->sectionsModel->checkIfSectionNameExists($sectionName);
            if($sectionNameExists) {
                return [
                    'httpcode'=> 409,
                    'success'=> false,
                    'message'=> 'Section name provided already exists. Please choose another one',
                    'data'=> []
                ];
            }
            else {
                $action = $this->sectionsModel->insertSections($sectionName, $gradeLevelId);
                if(!$action) {
                    return [
                        'httpcode'=> 500,
                        'success'=> false,
                        'message'=> 'There was an unexpected problem during the insert. Try Again later',
                        'data'=> []                
                    ];
                }
                return [
                    'httpcode'=> 201,
                    'success'=> true,
                    'message'=> 'Section inserted successfully',
                    'data'=> $action
                ];
            }
        }
        catch(DatabaseException $e) {
            return [
                'httpcode'=> 500,
                'success'=> false,
                'message'=>'Database error: ' .$e->getMessage(),
                'data'=>[]
            ];
        }
        catch(Exception $e) {
            return [
                'httpcode'=>400,
                'success'=> false,
                'message'=> 'Error: '.$e->getMessage(),
                'data'=>[]
            ];
        }
    }
    public function apiSectionsListInformation() : array {
        try {
            $data = $this->sectionsModel->getSectionsListInformation();

            if(empty($data)) {
                return [
                    'httpcode'=> 200,
                    'success'=> false,
                    'message'=> 'Sections list are empty',
                    'data' => []
                ];
            }

            return [
                'httpcode'=>200,
                'success'=> true,
                'message'=> 'Sections list successfully returned',
                'data'=> $data
            ];
        }
        catch(DatabaseException $e) {
            return [
                'httpcode'=> 500,
                'success'=> false,
                'message'=>'Database error: ' .$e->getMessage(),
                'data'=>[]
            ];
        }
        catch(Exception $e) {
            return [
                'httpcode'=>400,
                'success'=> false,
                'message'=> 'Error: '.$e->getMessage(),
                'data'=>[]
            ];
        }
    }
    //VIEW
    public function viewSectionName(int $sectionId) : array {
        try {
            if(!is_numeric($sectionId)) {
                throw new Exception('Invalid section ID');
            }
            $data = $this->sectionsModel->getSectionName($sectionId);

            if(empty($data)) {
                return [
                    'success'=> false,
                    'message'=> 'Failed to fetch section name',
                    'data'=> []
                ];
            }

            return [
                'success'=> true,
                'message'=> 'Section name successfully fetched',
                'data'=> $data
            ];
        }
        catch(DatabaseException $e) {
            return [
                'httpcode'=> 500,
                'success'=> false,
                'message'=>'Database error: ' .$e->getMessage(),
                'data'=>[]
            ];
        }
        catch(Exception $e) {
            return [
                'httpcode'=>400,
                'success'=> false,
                'message'=> 'Error: '.$e->getMessage(),
                'data'=>[]
            ];
        }
    }
    public function viewAdviserName(int $sectionId) : array {
        try {
            if(!is_numeric($sectionId)) {
                throw new Exception('Invalid section ID');
            }
            $data = $this->sectionsModel->getSectionAdviserName($sectionId);
            if(empty($data)) {
                return [
                    'success'=> false,
                    'message'=> 'Adviser name is emtpy',
                    'data'=> []
                ];
            }

            return [
                'success'=> true,
                'message'=> 'Adviser name successfully fetched',
                'data'=> $data
            ];
        }
        catch(DatabaseException $e) {
            return [
                'httpcode'=> 500,
                'success'=> false,
                'message'=>'Database error: ' .$e->getMessage(),
                'data'=>[]
            ];
        }
        catch(Exception $e) {
            return [
                'httpcode'=>400,
                'success'=> false,
                'message'=> 'Error: '.$e->getMessage(),
                'data'=>[]
            ];
        }
    }
    public function viewSectionDetails(int $sectionId) : array {
        try {
            if(!is_numeric($sectionId)) {
                throw new Exception('Invalid section ID');
            }
            $maleData = $this->returnMaleStudents($sectionId);
            $femaleData = $this->returnFemaleStudents($sectionId);
            $result = [];
            $result = [
                'male'=> [
                    'success'=> $maleData['success'],
                    'message'=> $maleData['message'],
                    'students'=> $maleData['data'] ?? []
                ],
                'female'=> [
                    'success'=> $femaleData['success'],
                    'message'=> $femaleData['message'],
                    'students'=> $femaleData['data'] ?? []
                ]
            ];
            if(empty($result['male']['students']) && empty($result['female']['students'])) {
                return [
                    'success'=> false,
                    'message'=> 'Students are empty',
                    'data'=> []
                ];
            }
            return [
                'success'=> true,
                'message'=> 'All students successfully fetched',
                'data'=> $result
            ];

        }
        catch(DatabaseException $e) {
            return [
                'httpcode'=> 500,
                'success'=> false,
                'message'=>'Database error: ' .$e->getMessage(),
                'data'=>[]
            ];
        }
        catch(Exception $e) {
            return [
                'httpcode'=>400,
                'success'=> false,
                'message'=> 'Error: '.$e->getMessage(),
                'data'=>[]
            ];
        }
    }
    //GETTERS
    private function returnMaleStudents(int $sectionId) : array {
        try {
            $data = $this->sectionsModel->getSectionMaleStudents($sectionId);

            if(empty($data)) {
                return [
                    'success'=> false,
                    'message'=> 'Male students are empty',
                    'gender'=> 'Male',
                    'data'=> []
                ];
            }
            return [
                'success'=>true,
                'message'=>'Male students successfully returned',
                'gender'=> 'Male',
                'data'=> $data
            ];
        }
        catch(DatabaseException $e) {
            return [
                'httpcode'=> 500,
                'success'=> false,
                'message'=>'Database error: ' .$e->getMessage(),
                'gender'=> 'male',
                'data'=>[]
            ];
        }
        catch(Exception $e) {
            return [
                'httpcode'=>400,
                'success'=> false,
                'message'=> 'Error: '.$e->getMessage(),
                'gender'=> 'Male',
                'data'=>[]
            ];
        }
    }
    private function returnFemaleStudents(int $sectionId) : array {
        try {
            $data = $this->sectionsModel->getSectionFemaleStudents($sectionId);

            if(empty($data)) {
                return [
                    'success'=> false,
                    'message'=> 'Female students are empty',
                    'gender'=> 'Female',
                    'data'=> []
                ];
            }
            return [
                'success'=>true,
                'message'=>'Female students successfully returned',
                'gender'=> 'Female',
                'data'=> $data
            ];
        }
        catch(DatabaseException $e) {
            return [
                'httpcode'=> 500,
                'success'=> false,
                'message'=>'Database error: ' .$e->getMessage(),
                'gender'=> 'Female',
                'data'=>[]
            ];
        }
        catch(Exception $e) {
            return [
                'httpcode'=>400,
                'success'=> false,
                'message'=> 'Error: '.$e->getMessage(),
                'gender'=> 'Female',
                'data'=>[]
            ];
        }
    }
}