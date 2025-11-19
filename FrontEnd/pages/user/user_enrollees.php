<?php 
ob_start();
require_once __DIR__ . '/../session_init.php';

// Initialize translation configuration - use absolute paths for Docker compatibility
$translationEnabled = false;
$supportedLanguages = ['en' => 'English', 'tl' => 'Filipino (Tagalog)'];
$currentLanguage = 'en';

// Get the base directory (SSISv2.1)
$baseDir = dirname(dirname(dirname(__DIR__)));
$vendorPath = $baseDir . '/vendor/autoload.php';
$translationConfigPath = $baseDir . '/app/Translation/TranslationConfig.php';

if (file_exists($vendorPath) && file_exists($translationConfigPath)) {
    require_once $vendorPath;
    require_once $translationConfigPath;
    
    $config = \app\Translation\TranslationConfig::getInstance();
    $supportedLanguages = $config->getSupportedLanguages();
    $currentLanguage = $_SESSION['preferred_language'] ?? $config->getDefaultLanguage();
    $translationEnabled = true;
}

$pageCss = '<link rel="stylesheet" href="../../assets/css/user/user-enrollees.css">
<link rel="stylesheet" href="../../assets/css/user/user-enrollees-modal.css">
<link rel="stylesheet" href="../../assets/css/user/user-enrollment-status.css">
<link rel="stylesheet" href="../../assets/css/user/user-announcements.css">
<link rel="stylesheet" href="../../assets/css/language-switcher.css">';
$pageJs = '<script src="../../assets/js/user/user-enrollees-modal.js" type="module" defer></script>
<script src="../../assets/js/announcements.js" defer></script>
<script src="../../assets/js/translation.js"></script>
<script src="../../assets/js/language-switcher-toggle.js"></script>';
$pageTitle = 'My Enrollees';
require_once __DIR__ . '/../../../BackEnd/user/views/userEnrolleesView.php';
$enrollee = new displayEnrollmentForms();
?>
</head>
    <!--START OF THE MAIN CONTENT-->
    <div class="content" id="content">
        <!-- Announcements Section -->
        <div class="shadow-container">
            <div class="title-header">
                <p class="title" data-translate=" Mga Announcements">Announcements</p>
            </div>
            <div class="wrapper">
                <!-- Loading State -->
                <div id="announcements-loading" style="display: none;">
                    <div class="loading-spinner"></div>
                    <p data-translate="Naglo-load ang Announcements...">Loading Announcements...</p>
                </div>

                <!-- Empty State -->
                <div id="announcements-empty" style="display: none;">
                    <p data-translate="Walang available na Announcements sa ngayon.">No announcements available at this time.</p>
                </div>

                <!-- Announcements Grid -->
                <div id="announcements-container"></div>
            </div>
        </div>

        <!-- Enrollment Forms Section -->
        <div class="shadow-container">
            <div class="title-header">
                <p class = "title" data-translate="Mga Naipasang Form ng Pag-enrol"> Enrollment Forms Submitted </p> 
            </div>
            <div class="wrapper">
                <div class="table-container">
                    <?php
                    $enrollee->displaySubmittedForms();
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Language Switcher - Bottom Right -->
    <div class="language-switcher-wrapper" style="position: fixed; bottom: 20px; right: 20px; z-index: 1000;">
      <button type="button" class="language-icon-button" id="language-toggle-btn" aria-label="Toggle Language Switcher">
        <img src="../../assets/imgs/globe-icon.svg" alt="Language" class="language-icon">
      </button>
      <div class="language-switcher-container" id="language-switcher-dropdown">
        <select id="language-switcher" class="language-switcher-select" aria-label="Select Language">
          <?php foreach ($supportedLanguages as $code => $name): ?>
            <option value="<?= htmlspecialchars($code) ?>" <?= $code === $currentLanguage ? 'selected' : '' ?>>
              <?= htmlspecialchars($name) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h2 data-translate="I-edit ang Form ng Pag-enrol">Edit Enrollment Form</h2>
                <span class="close-edit-modal">&times;</span>
            </div>
            <form id="editEnrollmentForm">
                <div class="form-fields">
                    <!-- Form fields will be dynamically generated here -->
                </div>
                <!-- Hidden fields for address names -->
                <input type="hidden" id="region-name" name="region_name">
                <input type="hidden" id="province-name" name="province_name">
                <input type="hidden" id="city-municipality-name" name="city_municipality_name">
                <input type="hidden" id="barangay-name" name="barangay_name">
                <div class="form-actions">
                    <button type="button" class="cancel-btn" data-translate="Kanselahin">Cancel</button>
                    <button type="submit" data-translate="I-update ang Pag-enrol">Update Enrollment</button>
                </div>
            </form>
        </div>
    </div>
<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/./user_base_designs.php';
?>
