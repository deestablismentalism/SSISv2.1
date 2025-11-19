<?php

namespace app\Translation;

use Dotenv\Dotenv;

/**
 * Translation Configuration Manager
 * Handles Google Cloud Translation API credentials and settings
 */
class TranslationConfig
{
    private static ?TranslationConfig $instance = null;
    private string $projectId;
    private string $credentialsPath;
    private array $supportedLanguages;
    private string $defaultLanguage;
    private string $glossaryId;
    private bool $cacheEnabled;
    private int $cacheDuration;

    private function __construct()
    {
        // Load environment variables
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->safeLoad();

        // Load configuration from environment or use defaults
        $this->projectId = $_ENV['GOOGLE_CLOUD_PROJECT_ID'] ?? '';
        $this->credentialsPath = $_ENV['GOOGLE_CLOUD_CREDENTIALS_PATH'] ?? __DIR__ . '/../../credentials/google-cloud-key.json';
        $this->glossaryId = $_ENV['TRANSLATION_GLOSSARY_ID'] ?? 'ssis-education-glossary';
        $this->defaultLanguage = $_ENV['DEFAULT_LANGUAGE'] ?? 'tl'; // Tagalog default
        $this->cacheEnabled = filter_var($_ENV['TRANSLATION_CACHE_ENABLED'] ?? 'true', FILTER_VALIDATE_BOOLEAN);
        $this->cacheDuration = (int)($_ENV['TRANSLATION_CACHE_DURATION'] ?? 86400); // 24 hours

        // Supported languages for the system
        $this->supportedLanguages = [
            'tl' => 'Filipino (Tagalog)',
            'en' => 'English'
        ];
    }

    /**
     * Get singleton instance
     */
    public static function getInstance(): TranslationConfig
    {
        if (self::$instance === null) {
            self::$instance = new TranslationConfig();
        }
        return self::$instance;
    }

    /**
     * Get Google Cloud Project ID
     */
    public function getProjectId(): string
    {
        return $this->projectId;
    }

    /**
     * Get credentials file path
     */
    public function getCredentialsPath(): string
    {
        return $this->credentialsPath;
    }

    /**
     * Get glossary ID
     */
    public function getGlossaryId(): string
    {
        return $this->glossaryId;
    }

    /**
     * Get default language
     */
    public function getDefaultLanguage(): string
    {
        return $this->defaultLanguage;
    }

    /**
     * Get all supported languages
     */
    public function getSupportedLanguages(): array
    {
        return $this->supportedLanguages;
    }

    /**
     * Check if language is supported
     */
    public function isLanguageSupported(string $languageCode): bool
    {
        return array_key_exists($languageCode, $this->supportedLanguages);
    }

    /**
     * Get language name
     */
    public function getLanguageName(string $languageCode): ?string
    {
        return $this->supportedLanguages[$languageCode] ?? null;
    }

    /**
     * Check if caching is enabled
     */
    public function isCacheEnabled(): bool
    {
        return $this->cacheEnabled;
    }

    /**
     * Get cache duration in seconds
     */
    public function getCacheDuration(): int
    {
        return $this->cacheDuration;
    }

    /**
     * Validate configuration
     */
    public function isConfigured(): bool
    {
        return !empty($this->projectId) && file_exists($this->credentialsPath);
    }

    /**
     * Get configuration errors
     */
    public function getConfigErrors(): array
    {
        $errors = [];

        if (empty($this->projectId)) {
            $errors[] = 'Google Cloud Project ID is not set in .env file';
        }

        if (!file_exists($this->credentialsPath)) {
            $errors[] = 'Google Cloud credentials file not found at: ' . $this->credentialsPath;
        }

        return $errors;
    }
}
