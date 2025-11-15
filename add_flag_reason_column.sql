-- Add flag_reason column to track why report cards were flagged
ALTER TABLE report_card_submissions 
ADD COLUMN flag_reason TEXT NULL AFTER status;
