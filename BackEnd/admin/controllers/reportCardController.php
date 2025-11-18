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
                // Check if running in Docker or native Windows
                if (file_exists('/.dockerenv')) {
                    $pythonCmd = 'python3'; // Docker container
                } else {
                    $pythonCmd = 'python'; // Native Windows
                }
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
        // If either OCR failed, return detailed error
        if (!$frontResult['success'] || !$backResult['success']) {
            $errorDetails = [];
            
            if (!$frontResult['success']) {
                $errorDetails[] = 'Front image: ' . ($frontResult['error'] ?? 'Unknown error');
            }
            if (!$backResult['success']) {
                $errorDetails[] = 'Back image: ' . ($backResult['error'] ?? 'Unknown error');
            }
            
            return [
                'success' => false,
                'data' => null,
                'error' => implode('; ', $errorDetails),
                'front_error' => !$frontResult['success'] ? ($frontResult['error'] ?? 'Unknown error') : null,
                'back_error' => !$backResult['success'] ? ($backResult['error'] ?? 'Unknown error') : null
            ];
        }
        
        $frontData = $frontResult['data'];
        $backData = $backResult['data'];
        
        // Combine data from both sides - report cards vary in layout
        // LRN can appear on either side
        $combinedLrn = $frontData['lrn'] ?? $backData['lrn'] ?? null;
        
        // Sum grades from both sides (grades can appear on either side)
        $frontGrades = $frontData['grades_found'] ?? 0;
        $backGrades = $backData['grades_found'] ?? 0;
        $combinedGrades = $frontGrades + $backGrades;
        
        // Sum word count from both sides
        $combinedWords = ($frontData['word_count'] ?? 0) + ($backData['word_count'] ?? 0);
        
        // Merge all flags without side-specific judgments
        $frontFlags = $frontData['flags'] ?? [];
        $backFlags = $backData['flags'] ?? [];
        $combinedFlags = array_unique(array_merge($frontFlags, $backFlags));
        
        // Apply validation rules to COMBINED data
        $validationFlags = [];
        
        if ($combinedGrades < 5) {
            $validationFlags[] = 'no_grades';
        }
        
        if ($combinedWords < 50) {
            $validationFlags[] = 'low_text';
        }
        
        // Track if BOTH images have critical issues (for rejection logic)
        $bothHaveNoText = in_array('no_text', $frontFlags) && in_array('no_text', $backFlags);
        $bothHaveNoKeywords = in_array('no_keywords', $frontFlags) && in_array('no_keywords', $backFlags);
        $bothHaveNoGrades = ($frontGrades === 0) && ($backGrades === 0);
        $bothHaveLowText = in_array('low_text', $validationFlags) && 
                          ($frontData['word_count'] ?? 0) < 25 && 
                          ($backData['word_count'] ?? 0) < 25;
        
        // Add metadata for rejection decision
        if ($bothHaveNoText) {
            $validationFlags[] = 'both_no_text';
        }
        if ($bothHaveNoKeywords) {
            $validationFlags[] = 'both_no_keywords';
        }
        if ($bothHaveNoGrades) {
            $validationFlags[] = 'both_no_grades';
        }
        if ($bothHaveLowText && $combinedGrades < 5) {
            $validationFlags[] = 'both_low_quality';
        }
        
        // Merge validation flags with extraction flags
        $allFlags = array_unique(array_merge($combinedFlags, $validationFlags));
        
        return [
            'success' => true,
            'data' => [
                'lrn' => $combinedLrn,
                'grades_found' => $combinedGrades,
                'word_count' => $combinedWords,
                'flags' => array_values($allFlags),
                'front_ocr' => $frontData,
                'back_ocr' => $backData,
                'lrn_source' => !empty($frontData['lrn']) ? 'front' : (!empty($backData['lrn']) ? 'back' : null),
                'grades_primary_source' => $backGrades > $frontGrades ? 'back' : ($frontGrades > 0 ? 'front' : null)
            ],
            'error' => null
        ];
    }
    
    private function determineStatus(array $ocrResult, string $submittedLrn): array {
        $frontOcr = $ocrResult['front_ocr'] ?? [];
        $backOcr = $ocrResult['back_ocr'] ?? [];
        
        $frontFlags = $frontOcr['flags'] ?? [];
        $backFlags = $backOcr['flags'] ?? [];
        
        $frontGrades = $frontOcr['grades_found'] ?? 0;
        $backGrades = $backOcr['grades_found'] ?? 0;
        
        // Helper functions for flag checks
        $hasFlag = function($flags, $flag) {
            return in_array($flag, $flags);
        };
        
        // REJECTION CONDITIONS (checked first)
        
        // 1. At least one picture has "no_text" flag
        if ($hasFlag($frontFlags, 'no_text') || $hasFlag($backFlags, 'no_text')) {
            return [
                'status' => 'rejected',
                'reason' => 'Unrelated picture, please submit a report card'
            ];
        }
        
        // 3. One side low_text + other side no_grades
        if (($hasFlag($frontFlags, 'low_text') && $backGrades === 0) || 
            ($hasFlag($backFlags, 'low_text') && $frontGrades === 0)) {
            return [
                'status' => 'rejected',
                'reason' => 'Please submit a report card or send a higher quality image'
            ];
        }
        
        // 4. One side low_text + other side no_keywords
        if (($hasFlag($frontFlags, 'low_text') && $hasFlag($backFlags, 'no_keywords')) || 
            ($hasFlag($backFlags, 'low_text') && $hasFlag($frontFlags, 'no_keywords'))) {
            return [
                'status' => 'rejected',
                'reason' => 'Please submit a report card or send a higher quality image'
            ];
        }
        
        // 5. Both sides have low_text
        if ($hasFlag($frontFlags, 'low_text') && $hasFlag($backFlags, 'low_text')) {
            return [
                'status' => 'rejected',
                'reason' => 'Please submit a report card or send a higher quality image'
            ];
        }
        
        // 6. One has grades but other has no_keywords (one side valid, other invalid)
        if (($frontGrades > 0 && $hasFlag($backFlags, 'no_keywords') && $backGrades === 0) || 
            ($backGrades > 0 && $hasFlag($frontFlags, 'no_keywords') && $frontGrades === 0)) {
            return [
                'status' => 'rejected',
                'reason' => 'Please submit a report card'
            ];
        }
        
        // 7. One has keywords but other has no grades (one side valid, other invalid)
        if ((!$hasFlag($frontFlags, 'no_keywords') && $frontGrades > 0 && $backGrades === 0 && $hasFlag($backFlags, 'no_keywords')) || 
            (!$hasFlag($backFlags, 'no_keywords') && $backGrades > 0 && $frontGrades === 0 && $hasFlag($frontFlags, 'no_keywords'))) {
            return [
                'status' => 'rejected',
                'reason' => 'Please submit a report card'
            ];
        }
        
        // FLAGGED CONDITION (pass to manual review)
        // One image must have good text + keywords, other must have at least 3 grades
        
        // Front has good text + keywords, Back has >= 3 grades
        if (!$hasFlag($frontFlags, 'low_text') && !$hasFlag($frontFlags, 'no_keywords') && $backGrades >= 3) {
            return [
                'status' => 'flagged_for_review',
                'reason' => $this->generateFlagReason($ocrResult, $frontGrades + $backGrades, ($frontOcr['word_count'] ?? 0) + ($backOcr['word_count'] ?? 0))
            ];
        }
        
        // Back has good text + keywords, Front has >= 3 grades
        if (!$hasFlag($backFlags, 'low_text') && !$hasFlag($backFlags, 'no_keywords') && $frontGrades >= 3) {
            return [
                'status' => 'flagged_for_review',
                'reason' => $this->generateFlagReason($ocrResult, $frontGrades + $backGrades, ($frontOcr['word_count'] ?? 0) + ($backOcr['word_count'] ?? 0))
            ];
        }
        
        // 2. Default: Anything not caught by flagged conditions = rejected
        return [
            'status' => 'rejected',
            'reason' => 'Please submit a report card'
        ];
    }
    
    private function generateFlagReason(array $ocrResult, int $gradesFound, int $wordCount): string {
        $reasons = [];
        $flags = $ocrResult['flags'] ?? [];
        
        // Default reason for manual review
        $baseReason = 'Manual verification required for report card authenticity';
        
        // Check for critical OCR errors
        if (in_array('file_not_found', $flags)) {
            $reasons[] = 'Image file could not be found';
        }
        if (in_array('processing_error', $flags)) {
            $reasons[] = 'Error occurred during image processing';
        }
        if (in_array('ocr_error', $flags)) {
            $reasons[] = 'OCR extraction failed';
        }
        if (in_array('no_text', $flags)) {
            $reasons[] = 'No readable text detected in image';
        }
        if (in_array('missing_dependencies', $flags)) {
            $reasons[] = 'OCR dependencies not installed (pytesseract/pillow)';
        }
        
        // Check for side-specific extraction issues
        if (isset($ocrResult['front_ocr'])) {
            $frontFlags = $ocrResult['front_ocr']['flags'] ?? [];
            if (in_array('ocr_error', $frontFlags)) {
                $reasons[] = 'Front image: OCR extraction failed';
            }
            if (in_array('no_text', $frontFlags)) {
                $reasons[] = 'Front image: No readable text detected';
            }
        }
        
        if (isset($ocrResult['back_ocr'])) {
            $backFlags = $ocrResult['back_ocr']['flags'] ?? [];
            if (in_array('ocr_error', $backFlags)) {
                $reasons[] = 'Back image: OCR extraction failed';
            }
            if (in_array('no_text', $backFlags)) {
                $reasons[] = 'Back image: No readable text detected';
            }
        }
        
        // Check combined content quality (informational, not blocking)
        if (in_array('no_grades', $flags) || $gradesFound < 5) {
            $frontGrades = $ocrResult['front_ocr']['grades_found'] ?? 0;
            $backGrades = $ocrResult['back_ocr']['grades_found'] ?? 0;
            $reasons[] = "Low grade count detected (Total: {$gradesFound} [Front: {$frontGrades}, Back: {$backGrades}])";
        }
        
        if (in_array('low_text', $flags) || $wordCount < 50) {
            $frontWords = $ocrResult['front_ocr']['word_count'] ?? 0;
            $backWords = $ocrResult['back_ocr']['word_count'] ?? 0;
            $reasons[] = "Low text content (Total: {$wordCount} [Front: {$frontWords}, Back: {$backWords}] words)";
        }
        
        // Check for additional warnings
        if (in_array('no_keywords', $flags)) {
            $reasons[] = 'Missing expected report card keywords (quarter, grade, subject, etc.)';
        }
        
        // Return reasons or default message
        if (empty($reasons)) {
            return $baseReason;
        }
        
        return $baseReason . '; ' . implode('; ', $reasons);
    }
    
    public function processReportCardUpload(?int $userId, string $studentName, string $studentLrn, ?array $frontFile, ?array $backFile, ?int $enrolleeId = null, ?string $sessionId = null, int $validationOnly = 0): array {
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
            
            // Check if both images are identical (same content)
            $frontHash = hash_file('sha256', $saveFrontImage['full_path']);
            $backHash = hash_file('sha256', $saveBackImage['full_path']);
            
            if ($frontHash === $backHash) {
                // Store as rejected submission with flag reason
                $ocrJson = json_encode([
                    'error' => 'Duplicate images detected',
                    'front_ocr' => null,
                    'back_ocr' => null
                ]);
                
                $submissionId = $this->model->insertSubmission(
                    $studentName,
                    $studentLrn,
                    $saveFrontImage['filepath'],
                    $saveBackImage['filepath'],
                    $ocrJson,
                    'rejected',
                    $enrolleeId,
                    'Front and back images are identical. Please submit different images for front and back of report card.',
                    $userId,
                    $sessionId,
                    $validationOnly
                );
                
                return [
                    'httpcode' => 400,
                    'success' => false,
                    'message' => 'Front and back images are identical',
                    'data' => [
                        'submission_id' => $submissionId,
                        'status' => 'rejected',
                        'flag_reason' => 'Front and back images are identical. Please submit different images for front and back of report card.'
                    ]
                ];
            }
            
            // Run OCR on both images and combine results
            $frontOcrResult = $this->runOCR($saveFrontImage['full_path']);
            $backOcrResult = $this->runOCR($saveBackImage['full_path']);
            
            // Combine OCR results - merge LRN, sum grades, sum word count, combine flags
            $combinedOcrResult = $this->combineOcrResults($frontOcrResult, $backOcrResult);
            
            $flagReason = null;
            if (!$combinedOcrResult['success']) {
                // Build detailed OCR failure data
                $ocrErrorData = [
                    'error' => $combinedOcrResult['error'] ?? 'OCR processing failed',
                    'front_error' => $combinedOcrResult['front_error'] ?? null,
                    'back_error' => $combinedOcrResult['back_error'] ?? null,
                    'front_ocr' => $frontOcrResult['data'] ?? null,
                    'back_ocr' => $backOcrResult['data'] ?? null
                ];
                
                $ocrJson = json_encode($ocrErrorData);
                $status = 'flagged_for_review';
                
                // Generate detailed flag reason
                $reasonParts = [];
                if (!empty($combinedOcrResult['front_error'])) {
                    $reasonParts[] = 'Front image error: ' . $combinedOcrResult['front_error'];
                }
                if (!empty($combinedOcrResult['back_error'])) {
                    $reasonParts[] = 'Back image error: ' . $combinedOcrResult['back_error'];
                }
                
                // If we have partial OCR data, analyze it for additional context
                if ($frontOcrResult['success'] && isset($frontOcrResult['data'])) {
                    $frontData = $frontOcrResult['data'];
                    if (empty($frontData['lrn']) && in_array('no_lrn', $frontData['flags'] ?? [])) {
                        $reasonParts[] = 'Front: No LRN detected';
                    }
                    if (($frontData['grades_found'] ?? 0) < 5) {
                        $reasonParts[] = 'Front: Insufficient grades (' . ($frontData['grades_found'] ?? 0) . ')';
                    }
                    if (($frontData['word_count'] ?? 0) < 25) {
                        $reasonParts[] = 'Front: Low text content (' . ($frontData['word_count'] ?? 0) . ' words)';
                    }
                }
                
                if ($backOcrResult['success'] && isset($backOcrResult['data'])) {
                    $backData = $backOcrResult['data'];
                    if (($backData['grades_found'] ?? 0) < 5) {
                        $reasonParts[] = 'Back: Insufficient grades (' . ($backData['grades_found'] ?? 0) . ')';
                    }
                    if (($backData['word_count'] ?? 0) < 25) {
                        $reasonParts[] = 'Back: Low text content (' . ($backData['word_count'] ?? 0) . ' words)';
                    }
                }
                
                $flagReason = !empty($reasonParts) ? implode('; ', $reasonParts) : 'OCR processing failed on one or both images';
            } else {
                $ocrJson = json_encode($combinedOcrResult['data']);
                $statusResult = $this->determineStatus($combinedOcrResult['data'], $studentLrn);
                $status = $statusResult['status'];
                $flagReason = $statusResult['reason'];
            }
            
            $submissionId = $this->model->insertSubmission(
                $studentName,
                $studentLrn,
                $saveFrontImage['filepath'],
                $saveBackImage['filepath'],
                $ocrJson,
                $status,
                $enrolleeId,
                $flagReason,
                $userId,
                $sessionId,
                $validationOnly
            );
            
            return [
                'httpcode' => 201,
                'success' => true,
                'message' => $status === 'rejected' ? 'Report card rejected' : 'Report card processed successfully',
                'data' => [
                    'submission_id' => $submissionId,
                    'status' => $status,
                    'flag_reason' => $flagReason,
                    'ocr_result' => $combinedOcrResult['data'] ?? null
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
            $allowedStatuses = ['approved', 'flagged_for_review', 'pending_review', 'reupload_needed', 'rejected'];
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

