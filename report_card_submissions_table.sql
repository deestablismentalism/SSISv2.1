CREATE TABLE IF NOT EXISTS report_card_submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_name VARCHAR(255) NOT NULL,
    student_lrn VARCHAR(12) NOT NULL,
    user_id BIGINT(20) DEFAULT NULL,
    session_id VARCHAR(255) DEFAULT NULL,
    report_card_front_path VARCHAR(500) NOT NULL,
    report_card_back_path VARCHAR(500) NULL,
    ocr_json TEXT,
    form_data_json TEXT DEFAULT NULL,
    status ENUM('approved', 'flagged_for_review', 'pending_review', 'reupload_needed', 'rejected') DEFAULT 'pending_review',
    flag_reason TEXT DEFAULT NULL,
    enrollee_id INT NULL,
    validation_only TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_lrn (student_lrn),
    INDEX idx_status (status),
    INDEX idx_enrollee (enrollee_id),
    INDEX idx_user_id (user_id),
    INDEX idx_session_id (session_id),
    INDEX idx_validation_only (validation_only),
    CONSTRAINT fk_report_card_enrollee FOREIGN KEY (enrollee_id) REFERENCES enrollee (Enrollee_Id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


