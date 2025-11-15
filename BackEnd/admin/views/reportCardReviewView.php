<?php
declare(strict_types=1);
require_once __DIR__ . '/../controllers/reportCardReviewController.php';
require_once __DIR__ . '/../../core/tableDataTemplate.php';
require_once __DIR__ . '/../../core/safeHTML.php';

class reportCardReviewView {
    protected $tableTemplate;
    protected $controller;
    
    public function __construct() {
        $this->tableTemplate = new tableCreator();
        $this->controller = new reportCardReviewController();
    }
    
    private function getStatusBadge(string $status): string {
        $badges = [
            'approved' => '<span style="background: #4CAF50; color: white; padding: 4px 8px; border-radius: 4px;">Approved</span>',
            'flagged_for_review' => '<span style="background: #FF9800; color: white; padding: 4px 8px; border-radius: 4px;">Flagged</span>',
            'pending_review' => '<span style="background: #2196F3; color: white; padding: 4px 8px; border-radius: 4px;">Pending</span>',
            'reupload_needed' => '<span style="background: #F44336; color: white; padding: 4px 8px; border-radius: 4px;">Reupload Needed</span>'
        ];
        return $badges[$status] ?? $status;
    }
    
    public function displayAllSubmissions(): void {
        try {
            $response = $this->controller->viewAllSubmissions();
            if (!$response['success']) {
                echo '<div class="error-message"><span>' . htmlspecialchars($response['message']) . '</span></div>';
                return;
            }
            
            $submissions = $response['data'];
            
            if (empty($submissions)) {
                echo '<div class="no-data">No report card submissions found.</div>';
                return;
            }
            
            echo '<table class="report-card-submissions">';
            echo $this->tableTemplate->returnHorizontalTitles(
                ['ID', 'Student Name', 'LRN', 'Status', 'Created At', 'Actions'],
                'submissions-titles'
            );
            
            foreach ($submissions as $submission) {
                $id = (int)$submission['id'];
                $studentName = htmlspecialchars($submission['student_name']);
                $lrn = htmlspecialchars($submission['student_lrn']);
                $status = $this->getStatusBadge($submission['status']);
                $createdAt = date('Y-m-d H:i', strtotime($submission['created_at']));
                
                $viewButton = new safeHTML(
                    '<button class="view-submission" data-id="' . $id . '">View Details</button>'
                );
                
                echo $this->tableTemplate->returnHorizontalRows(
                    [$id, $studentName, $lrn, $status, $createdAt, $viewButton],
                    'submissions-row'
                );
            }
            
            echo '</tbody></table>';
        }
        catch (Throwable $t) {
            echo '<div class="error-message">Error loading submissions: ' . htmlspecialchars($t->getMessage()) . '</div>';
        }
    }
    
    public function displaySubmissionDetails(int $id): void {
        try {
            $response = $this->controller->viewSubmissionById($id);
            if (!$response['success']) {
                echo '<div class="error-message"><span>' . htmlspecialchars($response['message']) . '</span></div>';
                return;
            }
            
            $submission = $response['data'];
            $ocrData = !empty($submission['ocr_json']) ? json_decode($submission['ocr_json'], true) : null;
            
            echo '<div class="submission-details">';
            echo '<h3>Submission Details</h3>';
            echo '<div class="detail-row"><strong>ID:</strong> ' . htmlspecialchars($submission['id']) . '</div>';
            echo '<div class="detail-row"><strong>Student Name:</strong> ' . htmlspecialchars($submission['student_name']) . '</div>';
            echo '<div class="detail-row"><strong>LRN:</strong> ' . htmlspecialchars($submission['student_lrn']) . '</div>';
            echo '<div class="detail-row"><strong>Status:</strong> ' . $this->getStatusBadge($submission['status']) . '</div>';
            echo '<div class="detail-row"><strong>Created At:</strong> ' . date('Y-m-d H:i:s', strtotime($submission['created_at'])) . '</div>';
            
            if (!empty($submission['report_card_path'])) {
                $imagePath = htmlspecialchars($submission['report_card_path']);
                echo '<div class="detail-row"><strong>Report Card:</strong></div>';
                echo '<div class="report-card-image"><img src="../../' . $imagePath . '" alt="Report Card" style="max-width: 100%; height: auto;"></div>';
            }
            
            if ($ocrData !== null) {
                echo '<div class="detail-row"><strong>OCR Results:</strong></div>';
                echo '<div class="ocr-results">';
                echo '<div><strong>LRN Found:</strong> ' . ($ocrData['lrn'] ?? 'Not found') . '</div>';
                echo '<div><strong>Grades Found:</strong> ' . ($ocrData['grades_found'] ?? 0) . '</div>';
                echo '<div><strong>Word Count:</strong> ' . ($ocrData['word_count'] ?? 0) . '</div>';
                if (!empty($ocrData['flags'])) {
                    echo '<div><strong>Flags:</strong> ' . implode(', ', array_map('htmlspecialchars', $ocrData['flags'])) . '</div>';
                }
                echo '</div>';
            }
            
            if ($submission['status'] === 'flagged_for_review' || $submission['status'] === 'pending_review') {
                echo '<div class="action-buttons">';
                echo '<button class="approve-btn" data-id="' . $id . '">Approve</button>';
                echo '<button class="reject-btn" data-id="' . $id . '">Reject</button>';
                echo '<button class="reupload-btn" data-id="' . $id . '">Request Re-upload</button>';
                echo '</div>';
            }
            
            echo '</div>';
        }
        catch (Throwable $t) {
            echo '<div class="error-message">Error loading submission details: ' . htmlspecialchars($t->getMessage()) . '</div>';
        }
    }
}

