<?php

declare(strict_types=1);
require_once __DIR__ . '/../models/adminSchedulesModel.php';
require_once __DIR__ . '/../../Exceptions/DatabaseException.php';
require_once __DIR__ . '/../../Exceptions/IdNotFoundException.php';

class adminSchedulesController {
    protected $schedulesModel;

    public function __construct() {
        $this->schedulesModel = new adminSchedulesModel();
    }
    //API
    public function apiPostSectionSchedule(int $sectionSubjectId, array $schedules): array {
        try {
            $result = $this->schedulesModel->upsertSectionSchedule($sectionSubjectId, $schedules);
            if (!$result['success']) {
                return [
                    'httpcode' => 400,
                    'success' => false,
                    'message' => $result['message'],
                    'data' => $result
                ];
            }
            return [
                'httpcode' => 201,
                'success' => true,
                'message' => $result['message'],
                'data' => $result
            ];

        } catch (DatabaseException $e) {
            return [
                'httpcode' => 500,
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage(),
                'data' => []
            ];
        } catch (Exception $e) {
            return [
                'httpcode' => 400,
                'success' => false,
                'message' => 'Unexpected error: ' . $e->getMessage(),
                'data' => []
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
    public function apiGetSectionScheduleSummaryByGradeLevel() : array {
        try {
            $data = $this->schedulesModel->getSectionsGroupedBySchedulesCount();
            if (empty($data)) {
                return [
                    'httpcode' => 200,
                    'success' => false,
                    'message' => 'No sections found',
                    'data' => []
                ];
            }
            $grouped = [];
            foreach ($data as $row) {
                $gradeLevelId = $row['Grade_Level_Id'];
                if (!isset($grouped[$gradeLevelId])) {
                    $grouped[$gradeLevelId] = [
                        'Grade_Level_Id' => $gradeLevelId,
                        'Grade_Level' => $row['Grade_Level'],
                        'Sections' => []
                    ];
                }
                $grouped[$gradeLevelId]['Sections'][] = [
                    'Section_Id' => $row['Section_Id'],
                    'Section_Name' => $row['Section_Name'],
                    'Total_Subjects' => (int)$row['Total_Subjects'],
                    'Scheduled_Subjects' => (int)$row['Scheduled_Subjects']
                ];
            }
            return [
                'httpcode' => 200,
                'success' => true,
                'message' => 'Section summaries fetched successfully',
                'data' => array_values($grouped)
            ];
        } catch (DatabaseException $e) {
            return [
                'httpcode' => 500,
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }
    public function apiFetchSectionSchedulesGroupedByDays(?int $sectionSubjectId):array {
        try {
            if(is_null($sectionSubjectId)) {
                throw new IdNotFoundException('Section Subject ID not found');
            }
            $result = $this->schedulesModel->getSchedulesGroupedByDay($sectionSubjectId);
            $allDays = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
            $data = [];
            foreach($allDays as $day) {
                $entry = array_values(array_filter($result, fn($r)=> $r['Day'] === $day));
                if ($entry) {
                $data[$day] = [
                    'Time_Start' => $entry[0]['Time_Start'] ?? '',
                    'Time_End' => $entry[0]['Time_End'] ?? ''
                    ];
                } else {
                    $data[$day] = [
                        'Time_Start' => '',
                        'Time_End' => ''
                    ];
                }
            }
            return [
                'httpcode'=> 200,
                'success'=> true,
                'data'=>$data
            ];
        }
        catch(DatabaseException $e) {
            return [
                'httpcode' => 500,
                'success' => false,
                'message' => 'There was a server problem: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }
    //GETTERS
    //VIEW
    public function viewSectionSubjectTimetable(?int $sectionId) {
        try {
            if(is_null($sectionId))  {
                throw new IdNotFoundException('Section ID not found');
            }
            $data = $this->schedulesModel->getSectionSubjectsTimetable($sectionId);
            if(empty($data)) {
                return [
                    'success'=> true,
                    'message'=> 'No schedules yet',
                    'data'=>[]
                ];
            }
            return [
                'success'=> true,
                'message'=> 'Schedules successfully fetched',
                'data'=> $data
            ];
        }
        catch(DatabaseException $e) {
            return ['success'=>false,'message'=>'There was a server problem: '.$e->getMessage()];
        }
    }
    public function viewCurrentSectionName(?int $sectionId):array {
        try {
            if(is_null($sectionId)) {
                throw new IdNotFoundException('Section ID not found');
            }
            $data = $this->schedulesModel->getSelectedSection($sectionId);
            if(is_null($data)) {
                return [
                    'success'=> true,
                    'message'=> 'Section name not found',
                    'section_name'=>$data
                ];
            }
            return ['success'=> true,'message'=>'Section name successfully fetched','section_name'=>$data];
        }
        catch (DatabaseException $e) {
            return [
                'success' => false,
                'message' => 'There was a server problem: ' . $e->getMessage(),
            ];
        }
    }
    public function viewSectionSubjectsAndSchedulesById(?int $sectionId): array {
        try {
            if (is_null($sectionId)) {
                throw new IdNotFoundException('Section ID not provided');
            }
            $data = $this->schedulesModel->getSectionSubjectsAndSchedulesById($sectionId);
            if (empty($data)) {
                return [
                    'success' => true,
                    'message' => 'No subjects found for this section',
                    'data' => [],
                ];
            }
            return [
                'success' => true,
                'message' => 'Subjects successfully fetched',
                'data' => $data,
            ];
        }  
        catch (DatabaseException $e) {
            return ['success' => false,'message' => 'There was a server problem: ' . $e->getMessage(),];
        }
    }
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
}