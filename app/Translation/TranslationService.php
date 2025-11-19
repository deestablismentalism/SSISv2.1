<?php

namespace app\Translation;

use Google\Cloud\Translate\V3\TranslationServiceClient;
use Google\Cloud\Translate\V3\TranslateTextRequest;
use app\Database\Database;

/**
 * Translation Service
 * Main service for translating content with caching and glossary support
 */
class TranslationService
{
    private TranslationConfig $config;
    private GlossaryManager $glossaryManager;
    private ?TranslationServiceClient $client = null;
    private ?Database $db = null;

    public function __construct()
    {
        $this->config = TranslationConfig::getInstance();
        $this->glossaryManager = new GlossaryManager();

        // Initialize Google Cloud Translation client if configured
        if ($this->config->isConfigured()) {
            try {
                $this->client = new TranslationServiceClient([
                    'credentials' => $this->config->getCredentialsPath()
                ]);
            } catch (\Exception $e) {
                error_log("Translation API initialization failed: " . $e->getMessage());
            }
        }

        // Initialize database connection for caching
        if ($this->config->isCacheEnabled()) {
            try {
                $this->db = Database::getInstance();
            } catch (\Exception $e) {
                error_log("Database connection failed for translation cache: " . $e->getMessage());
            }
        }
    }

    /**
     * Translate text from source language to target language
     */
    public function translate(
        string $text,
        string $targetLang,
        string $sourceLang = 'auto',
        bool $useGlossary = true
    ): array {
        // Validate input
        if (empty($text)) {
            return [
                'success' => false,
                'original' => $text,
                'translated' => $text,
                'message' => 'Empty text provided'
            ];
        }

        // Check if target language is supported
        if (!$this->config->isLanguageSupported($targetLang)) {
            return [
                'success' => false,
                'original' => $text,
                'translated' => $text,
                'message' => 'Target language not supported'
            ];
        }

        // If source and target are the same, no translation needed
        if ($sourceLang !== 'auto' && $sourceLang === $targetLang) {
            return [
                'success' => true,
                'original' => $text,
                'translated' => $text,
                'source_lang' => $sourceLang,
                'target_lang' => $targetLang,
                'from_cache' => false
            ];
        }

        // Check cache first
        if ($this->config->isCacheEnabled()) {
            $cached = $this->getFromCache($text, $sourceLang, $targetLang);
            if ($cached !== null) {
                return [
                    'success' => true,
                    'original' => $text,
                    'translated' => $cached,
                    'source_lang' => $sourceLang,
                    'target_lang' => $targetLang,
                    'from_cache' => true
                ];
            }
        }

        // Perform translation
        $translated = $this->performTranslation($text, $sourceLang, $targetLang, $useGlossary);

        if ($translated !== null) {
            // Store in cache
            if ($this->config->isCacheEnabled()) {
                $this->storeInCache($text, $translated, $sourceLang, $targetLang);
            }

            return [
                'success' => true,
                'original' => $text,
                'translated' => $translated,
                'source_lang' => $sourceLang,
                'target_lang' => $targetLang,
                'from_cache' => false
            ];
        }

        return [
            'success' => false,
            'original' => $text,
            'translated' => $text,
            'message' => 'Translation failed'
        ];
    }

    /**
     * Perform the actual translation
     */
    private function performTranslation(
        string $text,
        string $sourceLang,
        string $targetLang,
        bool $useGlossary
    ): ?string {
        // Try Google Cloud Translation API if configured
        if ($this->client !== null) {
            try {
                return $this->translateWithGoogle($text, $sourceLang, $targetLang);
            } catch (\Exception $e) {
                error_log("Google Translation API error: " . $e->getMessage());
            }
        }

        // Fallback to glossary-based translation for common terms
        if ($useGlossary) {
            $glossaryTranslated = $this->glossaryManager->translateWithGlossary($text, $sourceLang, $targetLang);
            if ($glossaryTranslated !== $text) {
                return $glossaryTranslated;
            }
        }

        // No translation available
        return null;
    }

    /**
     * Translate using Google Cloud Translation API
     */
    private function translateWithGoogle(string $text, string $sourceLang, string $targetLang): ?string
    {
        if ($this->client === null) {
            return null;
        }

        try {
            $projectId = $this->config->getProjectId();
            $location = 'global';
            $formattedParent = $this->client->locationName($projectId, $location);

            // Prepare request
            $request = (new TranslateTextRequest())
                ->setContents([$text])
                ->setTargetLanguageCode($targetLang)
                ->setParent($formattedParent);

            // Set source language if specified
            if ($sourceLang !== 'auto') {
                $request->setSourceLanguageCode($sourceLang);
            }

            // Execute translation
            $response = $this->client->translateText($request);
            $translations = $response->getTranslations();

            if (count($translations) > 0) {
                return $translations[0]->getTranslatedText();
            }

            return null;
        } catch (\Exception $e) {
            error_log("Google Translation error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Batch translate multiple texts
     */
    public function batchTranslate(
        array $texts,
        string $targetLang,
        string $sourceLang = 'auto',
        bool $useGlossary = true
    ): array {
        $results = [];

        foreach ($texts as $key => $text) {
            $results[$key] = $this->translate($text, $targetLang, $sourceLang, $useGlossary);
        }

        return $results;
    }

    /**
     * Get translation from cache
     */
    private function getFromCache(string $text, string $sourceLang, string $targetLang): ?string
    {
        if ($this->db === null) {
            return null;
        }

        try {
            $stmt = $this->db->prepare("
                SELECT translated_text, created_at 
                FROM translation_cache 
                WHERE original_text = ? 
                AND source_lang = ? 
                AND target_lang = ?
                AND created_at > DATE_SUB(NOW(), INTERVAL ? SECOND)
                LIMIT 1
            ");

            $stmt->bind_param(
                'sssi',
                $text,
                $sourceLang,
                $targetLang,
                $this->config->getCacheDuration()
            );

            $stmt->execute();
            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {
                return $row['translated_text'];
            }

            return null;
        } catch (\Exception $e) {
            error_log("Cache retrieval error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Store translation in cache
     */
    private function storeInCache(
        string $originalText,
        string $translatedText,
        string $sourceLang,
        string $targetLang
    ): void {
        if ($this->db === null) {
            return;
        }

        try {
            $stmt = $this->db->prepare("
                INSERT INTO translation_cache 
                (original_text, translated_text, source_lang, target_lang, created_at) 
                VALUES (?, ?, ?, ?, NOW())
                ON DUPLICATE KEY UPDATE 
                translated_text = VALUES(translated_text),
                created_at = NOW()
            ");

            $stmt->bind_param('ssss', $originalText, $translatedText, $sourceLang, $targetLang);
            $stmt->execute();
        } catch (\Exception $e) {
            error_log("Cache storage error: " . $e->getMessage());
        }
    }

    /**
     * Clear translation cache
     */
    public function clearCache(): bool
    {
        if ($this->db === null) {
            return false;
        }

        try {
            $this->db->query("DELETE FROM translation_cache WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY)");
            return true;
        } catch (\Exception $e) {
            error_log("Cache clearing error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Detect language of text
     */
    public function detectLanguage(string $text): ?string
    {
        if ($this->client === null) {
            return null;
        }

        try {
            $projectId = $this->config->getProjectId();
            $location = 'global';
            $formattedParent = $this->client->locationName($projectId, $location);

            // Use translation API to detect language
            $request = (new TranslateTextRequest())
                ->setContents([$text])
                ->setTargetLanguageCode('en')
                ->setParent($formattedParent);

            $response = $this->client->translateText($request);
            $translations = $response->getTranslations();

            if (count($translations) > 0) {
                return $translations[0]->getDetectedLanguageCode();
            }

            return null;
        } catch (\Exception $e) {
            error_log("Language detection error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get glossary manager instance
     */
    public function getGlossaryManager(): GlossaryManager
    {
        return $this->glossaryManager;
    }

    /**
     * Check if translation service is ready
     */
    public function isReady(): bool
    {
        return $this->config->isConfigured() && $this->client !== null;
    }

    /**
     * Get service status
     */
    public function getStatus(): array
    {
        return [
            'configured' => $this->config->isConfigured(),
            'api_ready' => $this->client !== null,
            'cache_enabled' => $this->config->isCacheEnabled(),
            'cache_ready' => $this->db !== null,
            'supported_languages' => $this->config->getSupportedLanguages(),
            'glossary_terms_count' => count($this->glossaryManager->getGlossaryTerms())
        ];
    }
}
