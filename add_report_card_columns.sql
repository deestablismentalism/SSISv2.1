-- Migration: Add columns to report_card_submissions table
-- Purpose: Support pre-validation of report cards before enrollment creation
-- Instructions: Execute in phpMyAdmin. If column exists error occurs, skip that statement.

-- Add user_id column
ALTER TABLE `report_card_submissions` 
ADD COLUMN `user_id` BIGINT(20) DEFAULT NULL AFTER `student_lrn`;

-- Add index for user_id
ALTER TABLE `report_card_submissions` 
ADD INDEX `idx_user_id` (`user_id`);

-- Add session_id column
ALTER TABLE `report_card_submissions` 
ADD COLUMN `session_id` VARCHAR(255) DEFAULT NULL AFTER `user_id`;

-- Add index for session_id
ALTER TABLE `report_card_submissions` 
ADD INDEX `idx_session_id` (`session_id`);

-- Add form_data_json column
ALTER TABLE `report_card_submissions` 
ADD COLUMN `form_data_json` TEXT DEFAULT NULL AFTER `ocr_json`;

-- Add validation_only flag
ALTER TABLE `report_card_submissions` 
ADD COLUMN `validation_only` TINYINT(1) DEFAULT 0 AFTER `enrollee_id`;

-- Add index for validation_only
ALTER TABLE `report_card_submissions` 
ADD INDEX `idx_validation_only` (`validation_only`);

-- Add foreign key constraint
-- Note: If constraint already exists, you'll get error. Drop it first:
-- ALTER TABLE `report_card_submissions` DROP FOREIGN KEY `fk_report_card_enrollee`;
ALTER TABLE `report_card_submissions`
ADD CONSTRAINT `fk_report_card_enrollee` 
FOREIGN KEY (`enrollee_id`) 
REFERENCES `enrollee` (`Enrollee_Id`) 
ON DELETE SET NULL 
ON UPDATE CASCADE;

