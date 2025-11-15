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
    
    private function combineOcrResults(array $frontResult, array $backResult): array {
        // If either OCR failed, return error
        if (!$frontResult['success'] || !$backResult['success']) {
            return [
                'success' => false,
                'data' => null,
                'error' => 'OCR failed on one or both images'
            ];
        }
        
        $frontData = $frontResult['data'];
        $backData = $backResult['data'];
        
        // Report card structure: Front = student info (name, section, LRN), Back = grades
        // Prioritize LRN from front (where student info is), but fallback to back if not found
        $combinedLrn = $frontData['lrn'] ?? $backData['lrn'] ?? null;
        
        // Grades are typically on the back, but sum both sides in case some grades appear on front
        // Prioritize back side for grades count
        $frontGrades = $frontData['grades_found'] ?? 0;
        $backGrades = $backData['grades_found'] ?? 0;
        $combinedGrades = $backGrades + $frontGrades; // Back grades are primary
        
        // Word count from both sides
        $combinedWords = ($frontData['word_count'] ?? 0) + ($backData['word_count'] ?? 0);
        
        // Merge flags, but adjust based on expected content location
        $frontFlags = $frontData['flags'] ?? [];
        $backFlags = $backData['flags'] ?? [];
        
        // If no LRN found on front (where it should be), add flag
        if (empty($frontData['lrn']) && !empty($backData['lrn'])) {
            $frontFlags[] = 'lrn_not_on_front';
        }
        
        // If no grades found on back (where they should be), add flag
        if ($backGrades === 0 && $frontGrades > 0) {
            $backFlags[] = 'grades_not_on_back';
        }
        
        $combinedFlags = array_unique(array_merge($frontFlags, $backFlags));
        
        return [
            'success' => true,
            'data' => [
                'lrn' => $combinedLrn,
                'grades_found' => $combinedGrades,
                'word_count' => $combinedWords,
                'flags' => array_values($combinedFlags),
                'front_ocr' => $frontData,
                'back_ocr' => $backData,
                'lrn_source' => !empty($frontData['lrn']) ? 'front' : (!empty($backData['lrn']) ? 'back' : null),
                'grades_primary_source' => $backGrades > 0 ? 'back' : ($frontGrades > 0 ? 'front' : null)
            ],
            'error' => null
        ];
    }
    
    private function determineStatus(array $ocrResult, string $submittedLrn): string {
        $ocrLrn = $ocrResult['lrn'] ?? null;
        $gradesFound = $ocrResult['grades_found'] ?? 0;
        $wordCount = $ocrResult['word_count'] ?? 0;
        $flags = $ocrResult['flags'] ?? [];
        
        $criticalFlags = ['no_lrn', 'no_grades', 'low_text', 'file_not_found', 'processing_error', 'ocr_error'];
        $hasCriticalFlag = !empty(array_intersect($flags, $criticalFlags));
        
        // If submitted LRN is empty or placeholder, skip LRN matching but still check other criteria
        $isPlaceholderLrn = empty($submittedLrn) || $submittedLrn === '000000000000';
        $lrnMatches = $isPlaceholderLrn ? true : ($ocrLrn && $ocrLrn === $submittedLrn);
        
        if ($lrnMatches && 
            $gradesFound >= 5 && 
            $wordCount >= 50 && 
            !$hasCriticalFlag) {
            return 'approved';
        }
        
        return 'flagged_for_review';
    }
    
    public function processReportCardUpload(?int $userId, string $studentName, string $studentLrn, ?array $frontFile, ?array $backFile, ?int $enrolleeId = null): array {
        try {
            if (empty($studentName)) {
                return [
                    'httpcode' => 400,
                    'success' => false,
                    'message' => 'Student name is required',
                    'data' => []
                ];
            }
            
            if (empty($frontFile)) {
                return [
                    'httpcode' => 400,
                    'success' => false,
                    'message' => 'Report card front image is required',
                    'data' => []
                ];
            }
            
            if (empty($backFile)) {
                return [
                    'httpcode' => 400,
                    'success' => false,
                    'message' => 'Report card back image is required',
                    'data' => []
                ];
            }
            
            $saveFrontImage = $this->storeReportCardImage($userId, $frontFile);
            if (!$saveFrontImage['success']) {
                return [
                    'httpcode' => 400,
                    'success' => false,
                    'message' => 'Failed to save front image: ' . $saveFrontImage['message'],
                    'data' => []
                ];
            }
            
            $saveBackImage = $this->storeReportCardImage($userId, $backFile);
            if (!$saveBackImage['success']) {
                return [
                    'httpcode' => 400,
                    'success' => false,
                    'message' => 'Failed to save back image: ' . $saveBackImage['message'],
                    'data' => []
                ];
            }
            
            // Run OCR on both images and combine results
            $frontOcrResult = $this->runOCR($saveFrontImage['full_path']);
            $backOcrResult = $this->runOCR($saveBackImage['full_path']);
            
            // Combine OCR results - merge LRN, sum grades, sum word count, combine flags
            $combinedOcrResult = $this->combineOcrResults($frontOcrResult, $backOcrResult);
            
            if (!$combinedOcrResult['success']) {
                $ocrJson = json_encode(['error' => $combinedOcrResult['error'] ?? 'OCR processing failed']);
                $status = 'flagged_for_review';
            } else {
                $ocrJson = json_encode($combinedOcrResult['data']);
                $status = $this->determineStatus($combinedOcrResult['data'], $studentLrn);
            }
            
            $submissionId = $this->model->insertSubmission(
                $studentName,
                $studentLrn,
                $saveFrontImage['filepath'],
                $saveBackImage['filepath'],
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

