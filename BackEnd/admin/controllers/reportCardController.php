<?php
declare(strict_types=1);
require_once __DIR__ . '/../models/reportCardModel.php';
require_once __DIR__ . '/../../Exceptions/DatabaseException.php';

class reportCardController {
    protected $model;
    
    public function __construct() {
        $this->model = new reportCardModel();
    }
    
    private function storeReportCardImage(?int $userId, ?array $file): array {
        try {
            if (empty($file)) {
                return [
                    'success' => false,
                    'message' => 'Report card file is empty'
                ];
            }
            
            if (!isset($file['name']) || !isset($file['tmp_name']) || !isset($file['type'])) {
                return [
                    'success' => false,
                    'message' => 'Invalid file upload'
                ];
            }
            
            if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
                return [
                    'success' => false,
                    'message' => 'Error during file upload'
                ];
            }
            
            $relPath = 'ImageUploads/report_cards/' . date('Y') . '/';
            $uploadDirectory = __DIR__ . '/../../../' . $relPath;
            
            if (!is_dir($uploadDirectory)) {
                if (!mkdir($uploadDirectory, 0777, true)) {
                    return [
                        'success' => false,
                        'message' => 'Failed to create upload directory'
                    ];
                }
            }
            
            $image = $file['name'];
            $imageTmpName = $file['tmp_name'];
            $extractImageExtension = explode('.', $image);
            $imageExtension = strtolower(end($extractImageExtension));
            $allowedFileTypes = ['jpg', 'jpeg', 'png'];
            
            if (!in_array($imageExtension, $allowedFileTypes)) {
                return [
                    'success' => false,
                    'message' => 'File type not allowed. Must be jpg, jpeg, or png'
                ];
            }
            
            $imageTime = time();
            $imageRandString = bin2hex(random_bytes(5));
            $userIdentifier = $userId ?? 'admin_' . time();
            $imageCustomFileName = $userIdentifier . '-' . $imageTime . '-' . $imageRandString;
            $imageFileName = $imageCustomFileName . '.' . $imageExtension;
            $imageFilePath = $uploadDirectory . $imageFileName;
            
            if (!move_uploaded_file($imageTmpName, $imageFilePath)) {
                return [
                    'success' => false,
                    'message' => 'Failed to store the image'
                ];
            }
            
            return [
                'success' => true,
                'message' => 'Image stored successfully',
                'filename' => $imageFileName,
                'filepath' => $relPath . $imageFileName,
                'full_path' => realpath($imageFilePath)
            ];
        }
        catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Unexpected error storing image: ' . $e->getMessage()
            ];
        }
    }
    
    private function runOCR(string $imagePath): array {
        try {
            $pythonScript = realpath(__DIR__ . '/../../../scripts/validate_card.py');
            if ($pythonScript === false || !file_exists($pythonScript)) {
                return [
                    'success' => false,
                    'data' => null,
                    'error' => 'Python script not found at: ' . __DIR__ . '/../../../scripts/validate_card.py'
                ];
            }
            $pythonCmd = 'python3';
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $pythonCmd = 'python';
            }
            $command = escapeshellarg($pythonCmd) . ' ' . escapeshellarg($pythonScript) . ' ' . escapeshellarg($imagePath) . ' 2>&1';
            
            $output = shell_exec($command);
            
            if ($output === null) {
                return [
                    'success' => false,
                    'data' => null,
                    'error' => 'OCR script execution failed'
                ];
            }
            
            $result = json_decode(trim($output), true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                return [
                    'success' => false,
                    'data' => null,
                    'error' => 'Invalid JSON from OCR script: ' . $output
                ];
            }
            
            return [
                'success' => true,
                'data' => $result,
                'error' => null
            ];
        }
        catch (Exception $e) {
            return [
                'success' => false,
                'data' => null,
                'error' => 'OCR execution error: ' . $e->getMessage()
            ];
        }
    }
    
    private function determineStatus(array $ocrResult, string $submittedLrn): string {
        $ocrLrn = $ocrResult['lrn'] ?? null;
        $gradesFound = $ocrResult['grades_found'] ?? 0;
        $wordCount = $ocrResult['word_count'] ?? 0;
        $flags = $ocrResult['flags'] ?? [];
        
        $criticalFlags = ['no_lrn', 'no_grades', 'low_text', 'file_not_found', 'processing_error', 'ocr_error'];
        $hasCriticalFlag = !empty(array_intersect($flags, $criticalFlags));
        
        if ($ocrLrn && 
            $ocrLrn === $submittedLrn && 
            $gradesFound >= 5 && 
            $wordCount >= 50 && 
            !$hasCriticalFlag) {
            return 'approved';
        }
        
        return 'flagged_for_review';
    }
    
    public function processReportCardUpload(?int $userId, string $studentName, string $studentLrn, ?array $file, ?int $enrolleeId = null): array {
        try {
            if (empty($studentName) || empty($studentLrn)) {
                return [
                    'httpcode' => 400,
                    'success' => false,
                    'message' => 'Student name and LRN are required',
                    'data' => []
                ];
            }
            
            if (empty($file)) {
                return [
                    'httpcode' => 400,
                    'success' => false,
                    'message' => 'Report card image is required',
                    'data' => []
                ];
            }
            
            $saveImage = $this->storeReportCardImage($userId, $file);
            if (!$saveImage['success']) {
                return [
                    'httpcode' => 400,
                    'success' => false,
                    'message' => $saveImage['message'],
                    'data' => []
                ];
            }
            
            $fullImagePath = $saveImage['full_path'];
            $ocrResult = $this->runOCR($fullImagePath);
            
            if (!$ocrResult['success']) {
                $ocrJson = json_encode(['error' => $ocrResult['error']]);
                $status = 'flagged_for_review';
            } else {
                $ocrJson = json_encode($ocrResult['data']);
                $status = $this->determineStatus($ocrResult['data'], $studentLrn);
            }
            
            $submissionId = $this->model->insertSubmission(
                $studentName,
                $studentLrn,
                $saveImage['filepath'],
                $ocrJson,
                $status,
                $enrolleeId
            );
            
            return [
                'httpcode' => 201,
                'success' => true,
                'message' => 'Report card processed successfully',
                'data' => [
                    'submission_id' => $submissionId,
                    'status' => $status,
                    'ocr_result' => $ocrResult['data'] ?? null
                ]
            ];
        }
        catch (DatabaseException $e) {
            return [
                'httpcode' => 500,
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage(),
                'data' => []
            ];
        }
        catch (Exception $e) {
            error_log("[".date('Y-m-d H:i:s')."]" . $e->getMessage() . "\n", 3, __DIR__ . '/../../../errorLogs.txt');
            return [
                'httpcode' => 500,
                'success' => false,
                'message' => 'Unexpected error: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }
    
    public function updateSubmissionStatus(int $id, string $status): array {
        try {
            $allowedStatuses = ['approved', 'flagged_for_review', 'pending_review', 'reupload_needed'];
            if (!in_array($status, $allowedStatuses)) {
                return [
                    'httpcode' => 400,
                    'success' => false,
                    'message' => 'Invalid status',
                    'data' => []
                ];
            }
            
            $success = $this->model->updateSubmissionStatus($id, $status);
            
            if ($success) {
                return [
                    'httpcode' => 200,
                    'success' => true,
                    'message' => 'Status updated successfully',
                    'data' => []
                ];
            } else {
                return [
                    'httpcode' => 404,
                    'success' => false,
                    'message' => 'Submission not found',
                    'data' => []
                ];
            }
        }
        catch (DatabaseException $e) {
            return [
                'httpcode' => 500,
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }
    
    public function getAllSubmissions(?string $status = null): array {
        try {
            $submissions = $this->model->getAllSubmissions($status);
            return [
                'httpcode' => 200,
                'success' => true,
                'message' => 'Submissions retrieved successfully',
                'data' => $submissions
            ];
        }
        catch (DatabaseException $e) {
            return [
                'httpcode' => 500,
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }
    
    public function getSubmissionById(int $id): array {
        try {
            $submission = $this->model->getSubmissionById($id);
            if ($submission === null) {
                return [
                    'httpcode' => 404,
                    'success' => false,
                    'message' => 'Submission not found',
                    'data' => []
                ];
            }
            return [
                'httpcode' => 200,
                'success' => true,
                'message' => 'Submission retrieved successfully',
                'data' => $submission
            ];
        }
        catch (DatabaseException $e) {
            return [
                'httpcode' => 500,
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }
}

