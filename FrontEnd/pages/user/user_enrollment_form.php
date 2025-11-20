<?php
// Start session first before accessing $_SESSION
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ob_start();

// Initialize translation configuration
$translationEnabled = false;
$supportedLanguages = ['en' => 'English', 'tl' => 'Filipino (Tagalog)'];
$currentLanguage = 'en';

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

$pageTitle = 'SSIS-Enrollment Form';
$pageCss = '<link rel="stylesheet" href="../../assets/css/user/user-enrollment-form.css" media="all">';
$pageCss2 = '<link rel="stylesheet" href="../../assets/css/user/user-enrollment-form-errors.css" media="all">';
$pageCss3 = '<link rel="stylesheet" href="../../assets/css/user/user-enrollment-form-mq.css" media="all">';
$pageCss4 = '<link rel="stylesheet" href="../../assets/css/notifications.css" media="all">
<link rel="stylesheet" href="../../assets/css/language-switcher.css">
<style>
    /* Override z-index for enrollment form to ensure language switcher appears above modals */
    .language-switcher-fixed-wrapper {
        z-index: 10001 !important;
    }
    .language-switcher-container {
        z-index: 10002 !important;
    }
</style>';
$pageJs = '<script type="module" src="../../assets/js/user/user-enrollment-form.js" defer></script>';
$pageJs2 = '<script src="../../assets/js/notifications.js"></script>
<script src="../../assets/js/translation.js"></script>';
require_once __DIR__ .'/../../../BackEnd/common/getGradeLevels.php';
require_once __DIR__ . '/../../../BackEnd/common/isAcademicYearSet.php';
$set = new isAcademicYearSet();
$view = new getGradeLevels();
?>
<div class="enrollment-form-content">
    <div class="content-title">
        <p data-translate="Form ng Pag-enrol ng Mag-aaral">Learner's Enrollment Form</p>
    </div>

    
    <!-- DISABILITY MODAL - Shows on page load -->
    <div id="disability-modal" class="disability-modal-overlay">
        <div class="disability-modal-content">
            <div class="disability-modal-header">
                <h2 data-translate="PARA SA MGA MAG-AARAL NA MAY KAPANSANAN">FOR LEARNERS WITH DISABILITIES</h2>
            </div>
            <div class="disability-modal-body">
                <!-- Step 1: Has Disability -->
                <div class="has-disability">
                    <p class="dfont" data-translate="Mayroon bang kapansanan ang mag-aaral?">Does the learner have a disability? <span class="required">*</span></p>
                    <div>
                        <div class="radio-option">
                            <input type="radio" name="has-disability" id="has-disability-yes" value="Yes" class="radio" required>
                            <label for="has-disability-yes" data-translate="Mayroon">Yes</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="has-disability" id="has-disability-no" value="No" class="radio" required>
                            <label for="has-disability-no" data-translate="Wala">No</label>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Can Read/Write (appears if Yes to disability) -->
                <div class="can-read-write" style="display: none;">
                    <p class="dfont" data-translate="Nakakabasa at nakakasulat ba ang mag-aaral?">Can the learner read and write? <span class="required">*</span></p>
                    <div>
                        <div class="radio-option">
                            <input type="radio" name="can-read-write" id="can-read-write-yes" value="Yes" class="radio">
                            <label for="can-read-write-yes" data-translate="Oo">Yes</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="can-read-write" id="can-read-write-no" value="No" class="radio">
                            <label for="can-read-write-no" data-translate="Hindi">No</label>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Disability Details (appears if Yes to read/write) -->
                <div class="disability-details" style="display: none;">
                    <div class="disability-description">
                        <div class="error-msg">
                            <span class="em-disability-desc"></span>
                        </div>
                        <p class="dfont" data-translate="Ano ang kapansanan ng mag-aaral?">What is the learner's disability? <span class="required">*</span></p>
                        <input type="text" name="disability-description" id="disability-description" class="textbox" placeholder="Example: Visual Impairment, Hearing Impairment, Physical Disability, etc." data-translate-placeholder="Halimbawa: Visual Impairment, Hearing Impairment, Physical Disability, etc.">
                    </div>
                    <div class="assistive-tech">
                        <div class="error-msg">
                            <span class="em-assistive-tech"></span>
                        </div>
                        <p class="dfont" data-translate="Gumagamit ba ng assistive technology? (Isulat 'N/A' kung hindi)">Does the learner uses assistive technology? (Write 'N/A' if not) <span class="required">*</span></p>
                        <input type="text" name="assistive-technology" id="assistive-technology" class="textbox" placeholder="Example: Braille, Wheelchair, Hearing Aid, or N/A" data-translate-placeholder="Halimbawa: Braille, Wheelchair, Hearing Aid, o N/A">
                    </div>
                </div>
            </div>
            <div class="disability-modal-footer">
                <button type="button" class="disability-modal-btn" id="disability-modal-continue" disabled data-translate="Magpatuloy">Continue</button>
            </div>
        </div>
    </div>

    <!-- Popup Modal for Template (appears if No to read/write) -->
    <div id="disability-template-popup" class="popup-overlay" style="display: none;">
        <div class="popup-content">
            <div class="popup-header">
                <h2 data-translate="Paalala para sa mga Magulang/Tagapagalaga">Notice to Parents/Guardians</h2>
            </div>
            <div class="popup-body">
                <p><strong data-translate="Sa mga magulang o tagapagalaga ng mga mag-aaral na may kapansanan at hindi nakakabasa o nakakasulat:">For parents or guardians of learners with disabilities who cannot read or write:</strong></p>
                <p data-translate="Mangyaring makipag-ugnayan sa aming tanggapan upang matulungan kayo sa proseso ng enrollment. Mayroon kaming espesyal na tulong at gabay para sa inyong sitwasyon.">Please contact our office for assistance with the enrollment process. We have special support and guidance for your situation.</p>
                <p><strong data-translate="Mga Contact Details:">Contact Details:</strong></p>
                <ul>
                    <li data-translate="Telepono: 09354876649">Phone: 09354876649</li>
                    <li data-translate="Email: 109732@deped.gov.ph">Email: 109732@deped.gov.ph</li>
                    <li data-translate="Oras ng Tanggapan: Lunes - Biyernes, 8:00 AM - 4:00 PM / Sabado - Linggo, 10:00 AM - 2:00 PM">Office Hours: Monday - Friday, 8:00 AM - 4:00 PM / Saturday - Sunday, 10:00 AM - 2:00 PM</li>
                                                                                                                
                </ul>
                <p data-translate="Salamat sa inyong pag-unawa.">Thank you for your understanding.</p>
            </div>
            <div class="popup-footer">
                <button type="button" class="popup-btn" id="confirm-disability-popup" data-translate="Naiintindihan Ko">I Understand</button>
            </div>
        </div>
    </div>

    <div class="content-wrapper">
        <form id="enrollment-form" class="form-main" enctype="multipart/form-data">
            <!-- Hidden fields for disability data mapping -->
            <input type="hidden" name="sn" id="sn-hidden" value="0">
            <input type="hidden" name="boolsn" id="boolsn-hidden" value="">
            <input type="hidden" name="at" id="at-hidden" value="0">
            <input type="hidden" name="atdevice" id="atdevice-hidden" value="">
            
            <!--ANTAS AT IMPORMASYON NG PAARALAN-->
            <div class="previous-school border-75">
                <div class="previous-school-title">
                    <span class="title" data-translate="ANTAS NG IMPORMASYON NG PAARALAN">GRADE LEVEL AND SCHOOL INFORMATION</span>
                </div>
                <div class="previous-school-row-1">
                <div class="school-year">
                        <p class="dfont-acad-year"><span data-translate="Taong Panuruan">School Year</span> <span class="required">*</span></p>
                        <!--ERROR MESSAGES DIVS FOR WRONG INPUTS -DAVID -->
                        <div class="error-msg">
                            <span class="em-start-year"> Error Message here.</span>
                        </div>
                        <div class="academic-year-input">
                            <div class="acad-year-tbox">
                                <input type="number" name="start-year" id="start-year" class="textbox">
                                <p> - </p>
                                <input type="number" name="end-year" id="end-year" class="textbox">
                            </div>
                        </div>
                    </div>
                    <div class="learner-radio">
                        <p class="dfont"><span data-translate="I-check lamang naaangkop">Check only what applies</span> <span class="required">*</span></p>
                        <div class="lrn-radio-buttons-selections">
                            <div class="radio-option">
                                <input type="radio" id="no-lrn" name="bool-LRN" value="0" class="radio">
                                <label for="no-lrn" data-translate="Walang LRN">Without LRN</label>
                            </div>
                            <div class="radio-option">
                                <input type="radio" id="with-lrn" name="bool-LRN" value="1" class="radio">
                                <label for="with-lrn" data-translate="Mayroong LRN">With LRN</label>
                            </div>
                            <div class="radio-option">
                                <input type="radio" id="returning" name="bool-LRN" value="0" class="radio">
                                <label for="returning" data-translate="Returning (Balik Aral)">Returning (Back to School)</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="previous-school-wrapper">
                    
                    <div class="grade">
                        <div class="enrolling-grade-level-container">
                            <div class="error-msg">
                                <span class="em-enrolling-grade-level"> Error Message Here. </span>
                            </div>
                            <p class="dfont"><span data-translate="Baitang na nais ipatala">Grade level to enroll in</span> <span class="required">*</span></p>
                            <select name="grades-tbe" id="grades-tbe" class="select">
                                <option value="" data-translate="Pumili ng baitang"> Select a grade level </option>
                                <?php
                                    $view->createSelectValues();
                                ?>
                            </select>
                        </div>
                        <div class="last-grade-level-container">
                            <div class="error-msg">
                                <span class="em-last-grade-level"> Error Message Here. </span>
                            </div>
                            <p class="dfont"><span data-translate="Huling baitang na natapos">Last grade level completed</span> <span class="required">*</span></p>
                            <select name="last-grade" id="last-grade" class="select">
                                <option value="" data-translate="Pumili ng baitang"> Select a grade level </option>
                                <?php 
                                    $view->createSelectValues();
                                ?>
                            </select>
                        </div>
                        <div class="last-year-finished">
                            <div class="error-msg">
                                <span class="em-last-year-finished"> Error Message Here. </span>
                            </div>
                            <p class="dfont"><span data-translate="Huling natapos na taon">Last school year completed</span> <span class="required">*</span></p>
                            <input type="number" name="last-year" id="last-year" class="textbox">
                        </div>
                    </div>
                    <div class="heducation">
                        <div class="lschool-wrapper">
                            <div class="last-school">
                                <div class="error-msg">
                                    <span class="em-lschool"> Error Message Here. </span>
                                </div>
                                <p class="dfont"><span data-translate="Huling paaralang pinasukan">Last school attended</span> <span class="required">*</span></p>
                                <input type="text" name="lschool" id="lschool" class="textbox" placeholder="South II Elementary School">
                            </div>
                                <div class="lschoolID">
                                    <div class="error-msg">
                                        <span class="em-lschoolID"> Error Message Here. </span>
                                    </div>
                                <p class="dfont"><span data-translate="ID ng paaralan">School ID</span> <span class="required">*</span></p>
                                <input type="number" name="lschoolID" id="lschoolID" class="textbox">
                            </div>
                        </div>
                        <div class="last-school-address">
                            <div class="error-msg">
                                <span class="em-lschoolAddress"> Error Message Here.</span>
                            </div>
                            <p class="dfont" data-translate="Address ng paaralan">School address <span class="required">*</span></p>
                            <input type="text" name="lschoolAddress" id="lschoolAddress" class="textbox"> 
                        </div> 
                        <p class="dfont"><span data-translate="Anong klase ng paaralan">What type of school</span> <span class="required">*</span></p>
                        <div> 
                            <div class="radio-option">
                                <input type="radio" name="school-type" id="private" class="radio" value="Private">
                                <label for="private" data-translate="Pribado">Private</label>
                            </div>
                            <div class="radio-option">
                                <input type="radio" name="school-type" id="public" class="radio" value="Public">
                                <label for="public" data-translate="Pampubliko">Public</label>
                            </div>
                        </div>
                    </div>
                    <div class="nais-paaralan">
                        <div class="fschool-wrapper">
                            <div class="fschool">
                                <div class="error-msg">
                                    <span class="em-fschool"> Error Message Here. </span>
                                </div>
                                <p class="dfont"><span data-translate="Nais na paaralan">Preferred school</span> <span class="required">*</span></p>
                                <input type="text" name="fschool" id="fschool" class="textbox" placeholder="South II Elementary School">
                            </div>
                            <div class="fschoolID">
                                <div class="error-msg">
                                    <span class="em-fschoolID"> Error Message Here. </span>
                                </div>
                                <p class="dfont"><span data-translate="ID ng paaralan">School ID</span> <span class="required">*</span></p>
                                <input type="number" name="fschoolID" id="fschoolID" class="textbox">
                            </div>
                        </div>
                        <div>
                            <div class="error-msg">
                                <span class="em-fschoolAddress"> Error Message Here. </span>
                            </div>
                            <p class="dfont" data-translate="Address ng paaralan">School address <span class="required">*</span></p>
                        <input type="text" name="fschoolAddress" id="fschoolAddress" class="textbox">
                        </div>
                    </div>
                </div>
            </div>
            <!--IMPORMASYON NG STUDYANTE-->
            <div class="student-information border-75">
                <div class="student-information-title">
                    <span class="title" data-translate="IMPORMASYON NG ESTUDYANTE">STUDENT INFORMATION</span>
                </div>
                <!--ROW 1-->
                <div class="student-info-row-1">
                    <div class="LRN">
                        <div class="error-msg">
                            <span class="em-LRN"></span>
                        </div>
                        <p class="dfont"><span data-translate="Learner's Reference Number (LRN) kung mayroon">Learner's Reference Number (LRN) if available</span> <span class="required">*</span></p>
                        <input type="number" name="LRN" id="LRN" class="textbox">
                    </div>
                </div>
                <div class="student-information-wrapper">
                    <!--ROW 2-->
                    <div class="student-info-row-2">
                        <div class="lname">
                            <div class="error-msg">
                                <span class="em-lname"></span>
                            </div>
                            <p class="dfont"><span data-translate="Apelyido">Last Name</span> <span class="required">*</span></p>
                            <input type="text" name="lname" id="lname" class="textbox" placeholder="Dela Cruz">
                        </div>
                        <div class="fname">
                            <div class="error-msg">
                                <span class="em-fname"></span>
                            </div>
                            <p class="dfont"><span data-translate="Pangalan">First Name</span> <span class="required">*</span></p>
                            <input type="text" name="fname" id="fname" class="textbox" placeholder="John Mark">
                        </div>
                        <div class="mname">
                            <div class="error-msg">
                                <span class="em-mname"></span>
                            </div>
                            <p class="dfont" data-translate="Gitnang Pangalan">Middle Name</p>
                            <input type="text" name="mname" id="mname" class="textbox" placeholder="Jimenez" data-optional="true">
                        </div>
                        <div class="extension">
                            <div class="error-msg">
                                <span class="em-extension"></span>
                            </div>
                            <p class="dfont" data-translate="Extensyon(Jr., Sr.)">Extension (Jr., Sr.)</p>
                            <input type="text" name="extension" id="extension" class="textbox" placeholder="III" data-optional="true">
                        </div>
                    </div>
                    <!--ROW 3-->
                    <div class="student-info-row-3">
                        <div class="bday">
                            <div class="error-msg">
                                <span class="em-bday"></span>
                            </div>
                            <p class="dfont"><span data-translate="Petsa ng Kapanganakan">Date of Birth</span> <span class="required">*</span></p>
                            <input type="date" name="bday" id="bday" class="textbox">
                        </div>
                        <div class="age">
                            <div class="error-msg">
                                <span class="em-age"></span>
                            </div>
                            <p class="dfont"><span data-translate="Edad">Age</span> <span class="required">*</span></p>
                            <input type="text" name="age" id="age" class="textbox" readonly>
                        </div>
                        <div class="gender-group-wrapper">
                            <div class="gender">
                                <p class="dfont"><span data-translate="Kasarian">Gender</span> <span class="required">*</span></p>
                                <div> 
                                    <div class="radio-option">
                                        <input type="radio" name="gender" id="male" class="radio" value="Male">
                                        <label for="male" data-translate="Lalake">Male</label>
                                    </div>
                                    <div class="radio-option">
                                        <input type="radio" name="gender" id="female" class="radio" value="Female">
                                        <label for="female" data-translate="Babae">Female</label>
                                    </div>
                                </div>
                            </div>
                            <div class="community">
                                <p class="dfont"><span data-translate="Nabibilang sa katutubong grupo/ Komunidad ng Katutubong Kultural">Belongs to indigenous group/ <br class="responsive-text-break">
                                            Indigenous Cultural Community</span> <span class="required">*</span></p>
                                <div>
                                    <div class="radio-option">
                                        <input type="radio" name="group" id="is-ethnic" class="radio" value="1">
                                        <label for="is-ethnic" data-translate="Oo">Yes</label>
                                    </div>
                                    <div class="radio-option">
                                        <input type="radio" name="group" id="not-ethnic" class="radio" value="0">
                                        <label for="not-ethnic" data-translate="Hindi">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="true-community">
                            <div class="error-msg">
                                <span class="em-community"></span>
                            </div>
                            <p class="dfont" data-translate="Kung oo, saang grupo nabilang">If yes, which group</p>
                            <input type="text" name="community" id="community" class="textbox" placeholder="sama-badjau">
                        </div>
                    </div>
                    <!--ROW 4-->
                    <div class="student-info-row-4">
                        <div class="native-language">
                            <div class="language">
                                <div class="error-msg">
                                    <span class="em-language"></span>
                                </div>
                                <p class="dfont"><span data-translate="Kinagisnang wika">Mother tongue/Native language</span> <span class="required">*</span></p>
                                <input type="text" name="language" id="language" class="textbox" placeholder="Tagalog">
                            </div>
                        </div>
                        <div class="religion">
                            <div class="error-msg">
                                <span class="em-religion"></span>
                            </div>
                            <p class="dfont"><span data-translate="Relihiyon">Religion</span> <span class="required">*</span></p>
                            <input type="text" name="religion" id="religion" class="textbox" placeholder="Catholic">
                        </div>
                        <div class="email">
                            <div class="error-msg">
                                <span class="em-email"></span>
                            </div>
                            <p class="dfont">Email Address</p>
                            <input type="email" name="email" id="email" class="textbox" placeholder="sampleemail@gmail.com" data-optional="true">
                        </div>
                    </div>
                </div>
            </div>
            <!--TIRAHAN-->
            <div class="address border-75">
                <div class="address-title">
                    <span class="title" data-translate="TIRAHAN">ADDRESS</span>
                </div>
                <div class="address-wrapper">
                    <div class="region">
                        <div class="error-msg">
                            <span class="em-region"></span>
                        </div>
                        <p class="dfont"><span data-translate="Rehiyon">Region</span> <span class="required">*</span></p>
                        <input type="hidden" name="region-name" id="region-name">
                        <select name="region" id="region" class="textbox" >
                            
                        </select>
                    </div>
                    <div class="province">
                        <div class="error-msg">
                            <span class="em-province"></span>
                        </div>
                        <p class="dfont"><span data-translate="Probinsya/lalawigan">Province</span> <span class="required">*</span></p>
                        <input type="hidden" name="province-name" id="province-name">
                        <select name="province" id="province" class="textbox">
                            
                        </select>
                    </div>
                    <div class="city">
                        <div class="error-msg">
                            <span class="em-city"></span>
                        </div>
                        <p class="dfont"><span data-translate="Lungsod/Munisipalidad">City/Municipality</span> <span class="required">*</span></p>
                        <input type="hidden" name="city-municipality-name" id="city-municipality-name">
                        <select name="city-municipality" id="city-municipality" class="textbox">
                            
                        </select>
                    </div>
                    <div class="barangay">
                        <div class="error-msg">
                            <span class="em-barangay"></span>
                        </div>
                        <p class="dfont"><span data-translate="Barangay">Barangay</span> <span class="required">*</span></p>
                        <input type="hidden" name="barangay-name" id="barangay-name">
                        <select name="barangay" id="barangay" class="textbox">
                            
                        </select>
                    </div>
                    <div class="subdivision">
                        <div class="error-msg">
                            <span class="em-subdivision"></span>
                        </div>
                        <p class="dfont"><span data-translate="Subdivision/ baryo/ purok/ sitio">Subdivision/Village/Purok/Sitio</span> <span class="required">*</span></p>
                        <input type="text" name="subdivision" id="subdivision" class="textbox" placeholder="Talipan">
                    </div>
                    <div class="house-number">
                        <div class="error-msg">
                            <span class="em-house-number"></span>
                        </div>
                        <p class="dfont"><span data-translate="Numero ng Bahay at kalye">House Number and Street</span> <span class="required">*</span></p>
                        <input type="text" name="house-number" id="house-number" class="textbox" placeholder="32">
                    </div>
                </div>
            </div>

            <!--IMPORMASYON NG MAGULANG/TAGAPAGALAGA-->
            <div class="parents-guardian-information border-75">
                <div class="parents-guardian-information-title">
                    <span class="title" data-translate="IMPORMASYON NG TAGAPAGALAGA">PARENT/GUARDIAN INFORMATION</span>
                </div>
                <!-- Nagdagdag ako ng seperate names dito dabid (Kinit)-->
                <div class="parents-guardian-information-wrapper">
                    <!-- Nagdagdag ako ng seperate names dito dabid (Kinit)-->
                    <div class="G-fullname">
                        <div class="error-msg">
                            <span class="em-guardian-last-name"></span>
                        </div>
                        <p class="dfont"><span data-translate="Apilyedo">Last Name</span> <span class="required">*</span></p>
                        <input type="text" class="textbox" name="Guardian-Last-Name" id="Guardian-Last-Name" placeholder="Dela Cruz">
                    </div>
                    <div class="Guardian-Middle-Name">
                        <div class="error-msg">
                            <span class="em-guardian-middle-name"></span>
                        </div>
                        <p class="dfont" data-translate="Gitnang Pangalan">Middle Name</p>
                        <input type="text" class="textbox" name="Guardian-Middle-Name" id="Guardian-Middle-Name" placeholder="Jimenez">
                    </div>
                    <div class="Guardian-First-Name">
                        <div class="error-msg">
                            <span class="em-guardian-first-name"></span>
                        </div>
                        <p class="dfont" data-translate="Pangalan">First Name <span class="required">*</span></p>
                        <input type="text" class="textbox" name="Guardian-First-Name" id="Guardian-First-Name" placeholder="Maria">
                    </div>
                    <div class="G-highest-education">
                        <label for="G-highest-education"><span data-translate="Pinakamataas na antas na natapos sa pag-aaral">Highest educational attainment</span> <span class="required">*</span></label><br>
                        <select name="G-highest-education" id="G-highest-education" class="select">
                            <option value="Hindi Nakapag-aral" data-translate="Hindi Nakapag-aral">No formal education</option>
                            <option value="Hindi Nakapag-aral pero marunong magbasa at magsulat" data-translate="Hindi Nakapag-aral pero marunong magbasa at magsulat">No formal education but can read and write</option>
                            <option value="Nakatuntong ng Elementarya" data-translate="Nakatuntong ng Elementarya">Elementary level</option>
                            <option value="Nakapagtapos ng Elementarya" data-translate="Nakapagtapos ng Elementarya">Elementary graduate</option>
                            <option value="Nakatuntong ng Sekundarya" data-translate="Nakatuntong ng Sekundarya">High school level</option>
                            <option value="Nakapagtapos ng Sekundarya" data-translate="Nakapagtapos ng Sekundarya">High school graduate</option>
                            <option value="Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal" data-translate="Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal/Bokasyonal">Post-secondary or Technical/Vocational</option>
                        </select>
                    </div>
                    <div class="G-Relationship">
                        <label for="G-Relationship"><span data-translate="Relasyon sa Mag-aaral">Relationship to learner</span> <span class="required">*</span></label><br>
                        <select name="G-Relationship" id="G-Relationship" class="select">
                            <option value="Magulang" data-translate="Magulang">Parent</option>
                            <option value="Lola" data-translate="Lola">Grandmother</option>
                            <option value="Lolo" data-translate="Lolo">Grandfather</option>
                            <option value="Tiya" data-translate="Tiya">Aunt</option>
                            <option value="Kapatid" data-translate="Kapatid">Sibling</option>
                            <option value="Ninang" data-translate="Ninang">Godmother</option>
                            <option value="Ninong" data-translate="Ninong">Godfather</option>
                            <option value="Iba pa" data-translate="Iba pa">Others</option>
                        </select>
                    </div>
                    <div class="G-number">
                        <div class="error-msg">
                            <span class="em-g-number"></span>
                        </div>
                        <p class="dfont"><span data-translate="Numero sa telepono (cellphone/ telephone/)">Contact Number (cellphone/telephone)</span> <span class="required">*</span></p>
                        <input type="text" name="G-Number" id="G-number" class="textbox" placeholder="09123456789">
                    </div> 
                </div>
            </div>
            <!--4PS, IMAGES AND SUBMIT BUTTON-->            
            <div class="confirmation border-75">
                <div class="fourPS">
                    <p class="dfont"><span data-translate="Kabilang ba ang inyong pamilya sa 4Ps ng DSWD?">Is your family a 4Ps beneficiary of DSWD?</span> <span class="required">*</span></p>
                    <div>
                        <div class="radio-option">
                            <input type="radio" name="fourPS" id="is-4ps" class="radio" value="yes">
                            <label for="is-4ps" data-translate="Oo">Yes</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="fourPS" id="not-4ps" class="radio" value="no">
                            <label for="not-4ps" data-translate="Hinde">No</label>
                        </div>
                    </div>
                </div>
                <div class="image-confirm">
                    <p class="dfont" id="report-card-label">Ipasa ang malinaw na larawan ng <b>REPORT CARD <span class="required" id="report-card-required">*</span></b></p>
                    <p class="dfont" id="kinder1-exemption-message" style="display: none; color: #2196F3; font-weight: bold;">Kinder 1 students are not required to submit report cards.</p>
                    <div id="report-card-inputs">
                        <label for="report-card-front" style="display: block; margin-bottom: 8px;">Front Side <span class="required">*</span></label>
                        <input type="file" id="report-card-front" name="report-card-front" accept="image/jpeg,image/jpg,image/png" required style="margin-bottom: 15px;"> 
                        <label for="report-card-back" style="display: block; margin-bottom: 8px;">Back Side <span class="required">*</span></label>
                        <input type="file" id="report-card-back" name="report-card-back" accept="image/jpeg,image/jpg,image/png" required>
                    </div>
                </div>
                <?php if($set->isSet()): ?>
                    <button type="submit" class="submit-button" data-translate="Isumite">Submit</button>
                <?php else: ?>
                    <button class="submit-button" style="opacity:0.5; background-color: gray; pointer-events:none;" disabled data-translate="Isumite">Submit</button>
                <?php endif; ?>
            </div>
        </form>
    </div>
    
    <!-- Language Switcher - Bottom Right -->
    <div class="language-switcher-fixed-wrapper">
      <div class="language-switcher-wrapper">
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
    </div>
</div>
<div class="error-message" id="error-message"></div>
<div class="success-message" id="success-message"></div>
<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/./user_base_designs.php';
?>
