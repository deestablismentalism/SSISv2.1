-- Migration: Remove PSA birth certificate requirement
-- Purpose: Transition from PSA to Report Card system
-- Execute this in phpMyAdmin or MySQL client

-- Step 1: Make Psa_Image_Id nullable in enrollee table
ALTER TABLE `enrollee` 
MODIFY COLUMN `Psa_Image_Id` INT(11) DEFAULT NULL;

-- Step 2: Set all existing PSA references to NULL
UPDATE `enrollee` SET `Psa_Image_Id` = NULL;

-- Step 3: Do the same for archive_enrollees
ALTER TABLE `archive_enrollees` 
MODIFY COLUMN `Psa_Image_Id` INT(11) DEFAULT NULL;

UPDATE `archive_enrollees` SET `Psa_Image_Id` = NULL;

-- Optional Step 4: Drop foreign key constraint if it exists
-- (Uncomment if foreign key constraint exists)
-- ALTER TABLE `enrollee` DROP FOREIGN KEY fk_enrollee_psa_image;
-- ALTER TABLE `archive_enrollees` DROP FOREIGN KEY fk_archive_enrollees_psa_image;

-- Note: We're keeping Psa_directory table for historical data
-- but new enrollments won't use it
