<?php
session_start();
// Bypass authentication for testing purposes
if (!isset($_SESSION['User'])) {
    $_SESSION['User'] = [
        'User-Id' => 9999,
        'User-Type' => 'Admin'
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Card Upload Test</title>
    <link rel="stylesheet" href="../../assets/css/admin/test-report-card.css">
</head>
<body>
    <div class="test-container">
        <div class="test-header">
            <h1>Report Card Verification Test</h1>
            <p>Test the OCR scanner and auto-approval logic</p>
        </div>

        <div class="test-content">
            <form id="test-form" class="test-form" enctype="multipart/form-data">
                <div class="form-section">
                    <h2>Student Information</h2>
                    
                    <div class="form-group">
                        <label for="student_name">Student Name <span class="required">*</span></label>
                        <input type="text" id="student_name" name="student_name" class="form-input" 
                               placeholder="Juan Dela Cruz" required>
                    </div>

                    <div class="form-group">
                        <label for="student_lrn">Student LRN <span class="required">*</span></label>
                        <input type="text" id="student_lrn" name="student_lrn" class="form-input" 
                               placeholder="123456789012" maxlength="12" pattern="\d{12}" required>
                        <small>12-digit Learner Reference Number</small>
                    </div>

                    <div class="form-group">
                        <label for="enrollee_id">Enrollee ID (Optional)</label>
                        <input type="number" id="enrollee_id" name="enrollee_id" class="form-input" 
                               placeholder="Leave blank for testing">
                    </div>
                </div>

                <div class="form-section">
                    <h2>Report Card Images</h2>
                    
                    <div class="form-group">
                        <label for="report_card_front">Report Card - Front (Student Info Side) <span class="required">*</span></label>
                        <input type="file" id="report_card_front" name="report_card_front" 
                               class="form-input-file" accept="image/jpeg,image/jpg,image/png" required>
                        <div id="preview_front" class="image-preview"></div>
                    </div>

                    <div class="form-group">
                        <label for="report_card_back">Report Card - Back (Grades Side) <span class="required">*</span></label>
                        <input type="file" id="report_card_back" name="report_card_back" 
                               class="form-input-file" accept="image/jpeg,image/jpg,image/png" required>
                        <div id="preview_back" class="image-preview"></div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" id="submit-btn">
                        Test Upload & Verify
                    </button>
                    <button type="reset" class="btn btn-secondary" id="reset-btn">
                        Clear Form
                    </button>
                </div>
            </form>

            <div id="results-section" class="results-section" style="display: none;">
                <div class="results-header">
                    <h2>Verification Results</h2>
                    <button class="btn btn-small" id="close-results">Close</button>
                </div>
                <div id="results-content" class="results-content">
                    <!-- Results will be populated here -->
                </div>
            </div>
        </div>
    </div>

    <div id="loading-overlay" class="loading-overlay" style="display: none;">
        <div class="spinner"></div>
        <p>Processing images with OCR...</p>
    </div>

    <script src="../../assets/js/admin/test-report-card.js"></script>
</body>
</html>
