<?php
declare(strict_types=1);
require_once __DIR__ . '/../models/reportCardModel.php';
require_once __DIR__ . '/../../Exceptions/DatabaseException.php';

class reportCardReviewController {
    protected $model;
    
    public function __construct() {
        $this->model = new reportCardModel();
    }
    
    public function viewAllSubmissions(): array {
        try {
            $submissions = $this->model->getAllSubmissions(null);
            return [
                'success' => true,
                'message' => 'Submissions retrieved successfully',
                'data' => $submissions
            ];
        }
        catch (DatabaseException $e) {
            return [
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }
    
    public function viewSubmissionById(int $id): array {
        try {
            $submission = $this->model->getSubmissionById($id);
            if ($submission === null) {
                return [
                    'success' => false,
                    'message' => 'Submission not found',
                    'data' => []
                ];
            }
            return [
                'success' => true,
                'message' => 'Submission retrieved successfully',
                'data' => $submission
            ];
        }
        catch (DatabaseException $e) {
            return [
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }
}

