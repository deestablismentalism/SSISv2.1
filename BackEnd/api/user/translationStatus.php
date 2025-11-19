<?php
/**
 * Get Translation Status API Endpoint
 * Returns translation service configuration and status
 */

header('Content-Type: application/json');
session_start();

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../../app/Translation/TranslationService.php';

use app\Translation\TranslationService;

try {
    // Initialize translation service
    $translationService = new TranslationService();

    // Get status
    $status = $translationService->getStatus();

    echo json_encode([
        'success' => true,
        'status' => $status
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
