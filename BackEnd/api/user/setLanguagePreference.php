<?php
/**
 * Set Language Preference API Endpoint
 * Saves user's preferred language to session and database
 */

header('Content-Type: application/json');
session_start();

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../../app/Translation/TranslationConfig.php';
require_once __DIR__ . '/../../../app/Database/Database.php';

use app\Translation\TranslationConfig;
use app\Database\Database;

try {
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input) {
        throw new Exception('Invalid JSON input');
    }

    // Validate required fields
    if (!isset($input['language']) || empty($input['language'])) {
        throw new Exception('Language code is required');
    }

    $languageCode = $input['language'];

    // Validate language code
    $config = TranslationConfig::getInstance();
    if (!$config->isLanguageSupported($languageCode)) {
        throw new Exception('Language not supported');
    }

    // Store in session
    $_SESSION['preferred_language'] = $languageCode;

    // If user is logged in, save to database
    if (isset($_SESSION['User']['User-Id'])) {
        try {
            $db = Database::getInstance();
            $userId = $_SESSION['User']['User-Id'];

            $stmt = $db->prepare("
                INSERT INTO user_preferences (user_id, preference_key, preference_value, updated_at)
                VALUES (?, 'preferred_language', ?, NOW())
                ON DUPLICATE KEY UPDATE 
                preference_value = VALUES(preference_value),
                updated_at = NOW()
            ");

            $stmt->bind_param('is', $userId, $languageCode);
            $stmt->execute();
        } catch (Exception $e) {
            // Log error but don't fail the request
            error_log("Failed to save language preference to database: " . $e->getMessage());
        }
    }

    echo json_encode([
        'success' => true,
        'language' => $languageCode,
        'language_name' => $config->getLanguageName($languageCode),
        'message' => 'Language preference updated successfully'
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
