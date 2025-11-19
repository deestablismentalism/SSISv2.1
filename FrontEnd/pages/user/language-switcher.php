<?php
/**
 * Language Switcher Component
 * Dropdown selector for changing interface language
 */

// Get the base directory (SSISv2.1) - go up 3 levels from FrontEnd/pages/user/
$baseDir = dirname(dirname(dirname(__DIR__)));
$vendorPath = $baseDir . '/vendor/autoload.php';
$translationConfigPath = $baseDir . '/app/Translation/TranslationConfig.php';

// Default values
$supportedLanguages = ['en' => 'English', 'tl' => 'Filipino (Tagalog)'];
$currentLanguage = 'en';

// Load translation config if available
if (file_exists($vendorPath) && file_exists($translationConfigPath)) {
    require_once $vendorPath;
    require_once $translationConfigPath;
    
    $config = \app\Translation\TranslationConfig::getInstance();
    $supportedLanguages = $config->getSupportedLanguages();
    $currentLanguage = $_SESSION['preferred_language'] ?? $config->getDefaultLanguage();
}
?>

<div class="language-switcher-container">
    <label for="language-switcher" class="language-switcher-label">
        <img src="../../assets/imgs/globe-icon.svg" alt="Language" class="language-icon">
    </label>
    <select id="language-switcher" class="language-switcher-select" aria-label="Select Language">
        <?php foreach ($supportedLanguages as $code => $name): ?>
            <option value="<?= htmlspecialchars($code) ?>" 
                    <?= $code === $currentLanguage ? 'selected' : '' ?>>
                <?= htmlspecialchars($name) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>
