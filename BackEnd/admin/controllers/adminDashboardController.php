<?php

declare(strict_types=1);
require_once __DIR__ . '/../../Exceptions/DatabaseException.php';
require_once __DIR__ . '/../models/adminDashboardModel.php';

class adminDashboardController {
    protected $dashboardModel;
    //API Endoints: apiFunctionName
    //View: viewFunctionName 
    //private getters: private function returnFunctionName
    public function __construct() {
        $this->dashboardModel = new adminDashboardModel();
    }
    //APIs
    public function apiEnrolleesByDays(int $days) : array {
        try {
            if(!is_numeric($days) || $days <= 0) {
                return [
                    'httpcode' => 200,
                    'success' => false,
                    'message' => 'Invalid input for days',
                    'data' => []
                ];
            }

            $data = $this->dashboardModel->EnrolleesByDays($days);
            $result = [];
            foreach($data as $days => $rows) {
                $result[] = [
                    'label' => $days,
                    'value' => $rows
                ];
            }
            return [
                'httpcode' => 200,
                'success'=> true,
                'message' => 'Enrollees successfully fetched',
                'data' => $result
            ];
        }
        catch(DatabaseException $e) {
            return [
                'httpcode' => 500,
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage(),
                'data' => []
            ];
        }
        catch(Exception $e) {
            return [
                'httpcode' => 400,
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'data'=> []
            ];
        }
    }
    public function apiDashboardChart() : array {
        try {
            $enrolleeStatuses = $this->returnEnrolleeStatuses();
            $enrolleeByGradeLevel = $this->returnEnrolleeGradeLevels();     
            $enrolleeByBiologicalSex = $this->returnEnrolleeBiologicalSex();
            $studentStatuses = $this->returnStudentStatuses();
            $studentByGradeLevel = $this->returnStudentGradeLevels();
            $studentByBiologicalSex = $this->returnStudentsBiologicalSex();

            $result = [
                'chart1' => $enrolleeStatuses,
                'chart2' => $enrolleeByGradeLevel,
                'chart3' => $enrolleeByBiologicalSex,
                'chart4' => $studentStatuses,
                'chart5' => $studentByGradeLevel,
                'chart6' => $studentByBiologicalSex
            ];
            $failed = [];
            foreach($result as $chart) {
                if($chart['success'] == false) {
                    $failed[] = [
                        $chart['message']
                    ];
                }
            }
            if(!empty($failed)) {
                return [
                    'httpcode' => 200,
                    'success' => true,
                    'message' => 'Some fetch failed',
                    'data' => $result,
                    'failed' => $failed
                ];
            }
            return [
                'httpcode' => 200,
                'success' => true,
                'message' => 'Charts successfully fetched',
                'data' => $result
            ];
        }
        catch(DatabaseException $e) {
            return [
                'httpcode' => 500,
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage(),
                'data' => []
            ];
        }
        catch(Exception $e) {
            return [
                'httpcode' => 400,
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }
    //Views
    public function viewEnrolleesCount() : array {
        try {
            $data = $this->dashboardModel->countTotalEnrollees();

            if(!is_numeric($data)) {
                return [
                    'success' => false,
                    'message' => 'Invalid Input',
                    'data' => 0,
                ];
            }
            return [
                'success' => true,
                'message' => 'Enrollees counted successfully',
                'data' => $data
            ];
        }
        catch(DatabaseException $e) {
            return [
                'success'=> false,
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
    public function viewStudentsCount() : array {
        try {
            $data = $this->dashboardModel->countTotalStudents();

            if(!is_numeric($data)) {
                return [
                    'success' => false,
                    'message' => 'Invalid Input',
                    'data' => 0,
                ];
            }
            return [
                'success' => true,
                'message' => 'Enrollees counted successfully',
                'data' => $data
            ];
        }
        catch(DatabaseException $e) {
            return [
                'success'=> false,
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
    public function viewDeniedAndFollowedUpCount() : array {
        try {
            $data = $this->dashboardModel->countTotalStudents();

            if(!is_numeric($data)) {
                return [
                    'success' => false,
                    'message' => 'Invalid Input',
                    'data' => 0,
                ];
            }
            return [
                'success' => true,
                'message' => 'Enrollees counted successfully',
                'data' => $data
            ];
        }
        catch(DatabaseException $e) {
            return [
                'success'=> false,
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
    public function viewPendingEnrollees() : array {
        try {
            $data = $this->dashboardModel->pendingEnrolleesInformation();

            if(empty($data)) {
                return [
                    'success' => false,
                    'message' => 'Pending Enrollees are empty',
                    'data' => []
                ];
            }
            return [
                'success'=> true,
                'message'=> 'Pending Enrollees successfully fetched',
                'data'=> $data
            ];
        }
        catch(DatabaseException $e) {
            return [
                'success'=> false,
                'message'=> 'Database error: ' . $e->getMessage(),
                'data' => []
            ];
        }
        catch(Exception $e) {
            return [
                'success'=> false,
                'message'=> 'Error: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }
    //private getters
    private function returnEnrolleeStatuses() : array {
        try {
            $data = $this->dashboardModel->EnrolleeStatuses();

            if(empty($data)) {
                return [
                    'httpcode' => 200,
                    'success' => false,
                    'message' => 'Enrollee Statuses are empty',
                    'data' => []
                ];
            }
            return [
                'httpcode' => 200,
                'success' => true,
                'message' => 'Enrollee statuses successfully returned',
                'data' => [
                    ['label' => 'Enrolled', 'value' => $data['enrolled_count'] ],
                    ['label' => 'Denied', 'value' => $data['denied_count']] ,
                    ['label' => 'Pending', 'value' => $data['pending_count'] ],
                    ['label' => 'Followed Up', 'value' => $data['follow_up_count'] ]
                ]
            ];
        }
        catch(DatabaseException $e) {
            return [
                'httpcode' => 500,
                'success' => false,
                'message' => 'Database error: ' .$e->getMessage(),
                'data' => []
            ];
        }
        catch(Exception $e) {
            return [
                'httpcode' => 400,
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }
       private function returnEnrolleeGradeLevels() : array {
        try {
            $data = $this->dashboardModel->EnrolleeGradeLevels();

            if(empty($data)) {
                return [
                    'httpcode' => 200,
                    'success' => false,
                    'message' => 'Enrollee by grade level is empty',
                    'data' => []
                ];
            }
            return [
                'httpcode' => 200,
                'success' => true,
                'message' => 'Enrollee by grade level successfully returned',
                'data' => [
                        ['label' => 'Kinder I', 'value' => $data['Kinder1']] ,
                        ['label' => 'Kinder II', 'value' => $data['Kinder2']] ,
                        ['label' => 'Grade 1', 'value' => $data['Grade1']] ,
                        ['label' => 'Grade 2', 'value' => $data['Grade2']] ,
                        ['label' => 'Grade 3', 'value' => $data['Grade3']] ,
                        ['label' => 'Grade 4', 'value' => $data['Grade4']] ,
                        ['label' => 'Grade 5', 'value' => $data['Grade5']] ,
                        ['label' => 'Grade 6', 'value' => $data['Grade6']] 
                ]
            ];
        }
        catch(DatabaseException $e) {
            return [
                'httpcode' => 500,
                'success' => false,
                'message' => 'Database error: ' .$e->getMessage(),
                'data' => []
            ];
        }
        catch(Exception $e) {
            return [
                'httpcode' => 400,
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }
       private function returnEnrolleeBiologicalSex() : array {
        try {
            $data = $this->dashboardModel->EnrolleeBiologicalSex();

            if(empty($data)) {
                return [
                    'httpcode' => 200,
                    'success' => false,
                    'message' => 'Enrollee by biological sex is empty',
                    'data' => []
                ];
            }
            return [
                'httpcode' => 200,
                'success' => true,
                'message' => 'Enrollee by biological sex successfully returned',
                'data' => [
                    ['label' => 'Male', 'value' => $data['Male']],
                    ['label' => 'Female', 'value' => $data['Female']]
                ]
            ];
        }
        catch(DatabaseException $e) {
            return [
                'httpcode' => 500,
                'success' => false,
                'message' => 'Database error: ' .$e->getMessage(),
                'data' => []
            ];
        }
        catch(Exception $e) {
            return [
                'httpcode' => 400,
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }
       private function returnStudentStatuses() : array {
        try {
            $data = $this->dashboardModel->StudentStatuses();

            if(empty($data)) {
                return [
                    'httpcode' => 200,
                    'success' => false,
                    'message' => 'Student Statuses are empty',
                    'data' => []
                ];
            }
            return [
                'httpcode' => 200,
                'success' => true,
                'message' => 'Student statuses successfully returned',
                'data' => [
                    ['label' => 'Waiting', 'value'=> $data['Waiting']],
                    ['label' => 'Active', 'value' => $data['ActiveStudents'] ],
                    ['label' => 'Inactive', 'value' => $data['InactiveStudents']] ,
                    ['label' => 'Dropped', 'value' => $data['DroppedStudents']],
                    ['label'=> 'Transferred', 'value'=> $data['Transferred']],
                    ['label'=> 'Graduated','value'=>$data['Graduated']]

                ]
            ];
        }
        catch(DatabaseException $e) {
            return [
                'httpcode' => 500,
                'success' => false,
                'message' => 'Database error: ' .$e->getMessage(),
                'data' => []
            ];
        }
        catch(Exception $e) {
            return [
                'httpcode' => 400,
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }
       private function returnStudentGradeLevels() : array {
        try {
            $data = $this->dashboardModel->StudentGradeLevels();

            if(empty($data)) {
                return [
                    'httpcode' => 200,
                    'success' => false,
                    'message' => 'Students by grade level is empty',
                    'data' => []
                ];
            }
            return [
                'httpcode' => 200,
                'success' => true,
                'message' => 'Students by grade level successfully returned',
                'data' =>  [
                        ['label' => 'Kinder I', 'value' => $data['Kinder1']] ,
                        ['label' => 'Kinder II', 'value' => $data['Kinder2']] ,
                        ['label' => 'Grade 1', 'value' => $data['Grade1']] ,
                        ['label' => 'Grade 2', 'value' => $data['Grade2']] ,
                        ['label' => 'Grade 3', 'value' => $data['Grade3']] ,
                        ['label' => 'Grade 4', 'value' => $data['Grade4']] ,
                        ['label' => 'Grade 5', 'value' => $data['Grade5']] ,
                        ['label' => 'Grade 6', 'value' => $data['Grade6']] 
                ]
            ];
        }
        catch(DatabaseException $e) {
            return [
                'httpcode' => 500,
                'success' => false,
                'message' => 'Database error: ' .$e->getMessage(),
                'data' => []
            ];
        }
        catch(Exception $e) {
            return [
                'httpcode' => 400,
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }
       private function returnStudentsBiologicalSex() : array {
        try {
            $data = $this->dashboardModel->StudentsBiologicalSex();

            if(empty($data)) {
                return [
                    'httpcode' => 200,
                    'success' => false,
                    'message' => 'Students by biological sex is empty',
                    'data' => []
                ];
            }
            return [
                'httpcode' => 200,
                'success' => true,
                'message' => 'Students by biological sex successfully returned',
                'data' => [
                    ['label' => 'Male', 'value' => $data['Male']],
                    ['label' => 'Female', 'value' => $data['Female']]
                ]
            ];
        }
        catch(DatabaseException $e) {
            return [
                'httpcode' => 500,
                'success' => false,
                'message' => 'Database error: ' .$e->getMessage(),
                'data' => []
            ];
        }
        catch(Exception $e) {
            return [
                'httpcode' => 400,
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }
}