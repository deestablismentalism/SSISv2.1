<?php
/**
 * Translation API Endpoint
 * Handles text translation requests from frontend
 */

header('Content-Type: application/json');
session_start();

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../../app/Translation/TranslationService.php';

use app\Translation\TranslationService;

try {
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input) {
        throw new Exception('Invalid JSON input');
    }

    // Validate required fields
    if (!isset($input['text']) || empty($input['text'])) {
        throw new Exception('Text is required');
    }

    if (!isset($input['target_lang'])) {
        throw new Exception('Target language is required');
    }

    // Get parameters
    $text = $input['text'];
    $targetLang = $input['target_lang'];
    $sourceLang = $input['source_lang'] ?? 'auto';
    $useGlossary = $input['use_glossary'] ?? true;

    // Initialize translation service
    $translationService = new TranslationService();

    // Perform translation
    $result = $translationService->translate($text, $targetLang, $sourceLang, $useGlossary);

    // Return result
    echo json_encode($result);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
