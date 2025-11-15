-- Migration script to update existing report_card_submissions table
-- Run this if you already created the table with the old structure

ALTER TABLE report_card_submissions 
ADD COLUMN report_card_front_path VARCHAR(500) NULL AFTER student_lrn,
ADD COLUMN report_card_back_path VARCHAR(500) NULL AFTER report_card_front_path;

-- Migrate existing data (if any)
UPDATE report_card_submissions 
SET report_card_front_path = report_card_path 
WHERE report_card_front_path IS NULL AND report_card_path IS NOT NULL;

-- Drop old column
ALTER TABLE report_card_submissions 
DROP COLUMN report_card_path;

-- Make front path NOT NULL (after migration)
ALTER TABLE report_card_submissions 
MODIFY COLUMN report_card_front_path VARCHAR(500) NOT NULL;

