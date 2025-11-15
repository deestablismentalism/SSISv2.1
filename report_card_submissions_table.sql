CREATE TABLE IF NOT EXISTS report_card_submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_name VARCHAR(255) NOT NULL,
    student_lrn VARCHAR(12) NOT NULL,
    report_card_path VARCHAR(500) NOT NULL,
    ocr_json TEXT,
    status ENUM('approved', 'flagged_for_review', 'pending_review', 'reupload_needed') DEFAULT 'pending_review',
    enrollee_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_lrn (student_lrn),
    INDEX idx_status (status),
    INDEX idx_enrollee (enrollee_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

