-- Translation System Database Migration
-- Creates tables for translation caching and user language preferences

-- Translation Cache Table
CREATE TABLE IF NOT EXISTS `translation_cache` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `original_text` TEXT NOT NULL,
  `translated_text` TEXT NOT NULL,
  `source_lang` VARCHAR(10) NOT NULL,
  `target_lang` VARCHAR(10) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_cache_lookup` (`source_lang`, `target_lang`, `created_at`),
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- User Language Preferences Table
CREATE TABLE IF NOT EXISTS `user_preferences` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `preference_key` VARCHAR(100) NOT NULL,
  `preference_value` TEXT NOT NULL,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_user_preference` (`user_id`, `preference_key`),
  INDEX `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add foreign key constraint if users table exists
-- Uncomment and adjust based on your actual users table structure
-- ALTER TABLE `user_preferences` 
-- ADD CONSTRAINT `fk_user_preferences_user` 
-- FOREIGN KEY (`user_id`) REFERENCES `users` (`User-Id`) 
-- ON DELETE CASCADE ON UPDATE CASCADE;

-- Create index for faster text lookups (first 255 characters)
-- Note: Full-text search is not used here as text length varies
ALTER TABLE `translation_cache` 
ADD INDEX `idx_original_text_prefix` (`source_lang`, `target_lang`, `original_text`(255));

-- Optional: Add a cleanup event to remove old cached translations (older than 30 days)
DELIMITER $$

CREATE EVENT IF NOT EXISTS `cleanup_old_translations`
ON SCHEDULE EVERY 1 DAY
STARTS CURRENT_TIMESTAMP
DO
BEGIN
  DELETE FROM `translation_cache` 
  WHERE `created_at` < DATE_SUB(NOW(), INTERVAL 30 DAY);
END$$

DELIMITER ;

-- Insert default language preference for existing users (optional)
-- This sets all existing users to Tagalog by default
-- Uncomment if you want to initialize preferences for existing users
-- INSERT IGNORE INTO `user_preferences` (`user_id`, `preference_key`, `preference_value`)
-- SELECT `User-Id`, 'preferred_language', 'tl'
-- FROM `users`
-- WHERE `User-Type` = 3; -- Assuming 3 is the user/parent type
