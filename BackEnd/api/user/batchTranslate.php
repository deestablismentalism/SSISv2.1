<?php
/**
 * Batch Translation API Endpoint
 * Handles multiple text translations in a single request
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
    if (!isset($input['texts']) || !is_array($input['texts']) || empty($input['texts'])) {
        throw new Exception('Texts array is required');
    }

    if (!isset($input['target_lang'])) {
        throw new Exception('Target language is required');
    }

    // Get parameters
    $texts = $input['texts'];
    $targetLang = $input['target_lang'];
    $sourceLang = $input['source_lang'] ?? 'auto';
    $useGlossary = $input['use_glossary'] ?? true;

    // Limit batch size for performance
    if (count($texts) > 100) {
        throw new Exception('Maximum 100 texts per batch');
    }

    // Initialize translation service
    $translationService = new TranslationService();

    // Perform batch translation
    $results = $translationService->batchTranslate($texts, $targetLang, $sourceLang, $useGlossary);

    // Return results
    echo json_encode([
        'success' => true,
        'translations' => $results,
        'count' => count($results)
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
