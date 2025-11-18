<?php
declare(strict_types=1);
require_once __DIR__ . '/../models/teacherDashboardModel.php';
require_once __DIR__ . '/../../Exceptions/DatabaseException.php';

class teacherDashboardController {
    protected $model;

    public function __construct() {
        $this->model = new teacherDashboardModel();
    }

    public function getDashboardData(int $staffId): array {
        try {
            $subjectsCount = $this->model->getSubjectsCount($staffId);
            $studentsCount = $this->model->getTotalStudentsToGrade($staffId);
            $lockerFilesCount = $this->model->getLockerFilesCount($staffId);
            $isAdviser = $this->model->isAdviser($staffId);
            $advisorySectionId = $isAdviser ? $this->model->getAdvisorySectionId($staffId) : null;

            return [
                'success' => true,
                'data' => [
                    'subjects_count' => $subjectsCount,
                    'students_count' => $studentsCount,
                    'locker_files_count' => $lockerFilesCount,
                    'is_adviser' => $isAdviser,
                    'advisory_section_id' => $advisorySectionId
                ]
            ];
        }
        catch(DatabaseException $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
        catch(Exception $e) {
            return [
                'success' => false,
                'message' => 'An error occurred while fetching dashboard data'
            ];
        }
    }

    // API for dashboard charts
    public function apiDashboardCharts(int $staffId): array {
        try {
            $studentsBioSex = $this->returnStudentsBiologicalSex($staffId);
            $enrolleesGradeLevel = $this->returnEnrolleesGradeLevelDistribution();
            $enrolleesBioSex = $this->returnEnrolleesBiologicalSex();

            $result = [
                'chart1' => $studentsBioSex,
                'chart3' => $enrolleesGradeLevel,
                'chart4' => $enrolleesBioSex
            ];

            $failed = [];
            foreach($result as $chart) {
                if($chart['success'] == false) {
                    $failed[] = $chart['message'];
                }
            }

            if(!empty($failed)) {
                return [
                    'httpcode' => 200,
                    'success' => true,
                    'message' => 'Some chart data fetch failed',
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

    // Private helper methods for chart data
    private function returnStudentsBiologicalSex(int $staffId): array {
        try {
            $data = $this->model->getStudentsBiologicalSexByTeacher($staffId);

            if(!is_array($data) || !isset($data['Male']) || !isset($data['Female'])) {
                return [
                    'success' => false,
                    'message' => 'Students biological sex data is empty',
                    'data' => []
                ];
            }

            return [
                'success' => true,
                'message' => 'Students biological sex successfully returned',
                'data' => [
                    ['label' => 'Male', 'value' => (int)$data['Male']],
                    ['label' => 'Female', 'value' => (int)$data['Female']]
                ]
            ];
        }
        catch(DatabaseException $e) {
            return [
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }

    private function returnEnrolleesGradeLevelDistribution(): array {
        try {
            $data = $this->model->getEnrolleesGradeLevelDistribution();

            if(!is_array($data)) {
                return [
                    'success' => false,
                    'message' => 'Enrollees grade level distribution is empty',
                    'data' => []
                ];
            }

            return [
                'success' => true,
                'message' => 'Enrollees grade level distribution successfully returned',
                'data' => [
                    ['label' => 'Kinder I', 'value' => (int)($data['Kinder1'] ?? 0)],
                    ['label' => 'Kinder II', 'value' => (int)($data['Kinder2'] ?? 0)],
                    ['label' => 'Grade 1', 'value' => (int)($data['Grade1'] ?? 0)],
                    ['label' => 'Grade 2', 'value' => (int)($data['Grade2'] ?? 0)],
                    ['label' => 'Grade 3', 'value' => (int)($data['Grade3'] ?? 0)],
                    ['label' => 'Grade 4', 'value' => (int)($data['Grade4'] ?? 0)],
                    ['label' => 'Grade 5', 'value' => (int)($data['Grade5'] ?? 0)],
                    ['label' => 'Grade 6', 'value' => (int)($data['Grade6'] ?? 0)]
                ]
            ];
        }
        catch(DatabaseException $e) {
            return [
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }

    private function returnEnrolleesBiologicalSex(): array {
        try {
            $data = $this->model->getEnrolleesBiologicalSex();

            if(!is_array($data) || !isset($data['Male']) || !isset($data['Female'])) {
                return [
                    'success' => false,
                    'message' => 'Enrollees biological sex data is empty',
                    'data' => []
                ];
            }

            return [
                'success' => true,
                'message' => 'Enrollees biological sex successfully returned',
                'data' => [
                    ['label' => 'Male', 'value' => (int)$data['Male']],
                    ['label' => 'Female', 'value' => (int)$data['Female']]
                ]
            ];
        }
        catch(DatabaseException $e) {
            return [
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }
}

