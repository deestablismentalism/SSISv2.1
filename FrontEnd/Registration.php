<?php 
session_start();
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Translation/TranslationConfig.php';

use app\Translation\TranslationConfig;

$config = TranslationConfig::getInstance();
$supportedLanguages = $config->getSupportedLanguages();
$currentLanguage = $_SESSION['preferred_language'] ?? $config->getDefaultLanguage();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registration</title>

    <!-- CSS Files -->
    <link rel="stylesheet" href="./assets/css/reset.css" />
    <link rel="stylesheet" href="./assets/css/Registration.css" />
    <link rel="stylesheet" href="./assets/css/fonts.css" />
    <link rel="stylesheet" href="./assets/css/notifications.css" />
    <link rel="stylesheet" href="./assets/css/loader.css">
    <link rel="stylesheet" href="./assets/css/language-switcher.css">

  <!-- JS -->
  <script src="./assets/js/loader.js"></script>
  <script src="./assets/js/registration-validation.js" defer></script>
  <script src="./assets/js/registration.js" defer></script>
  <script src="./assets/js/notifications.js" defer></script>
  <script src="./assets/js/terms-conditions.js" defer></script>
  <script src="./assets/js/translation.js"></script>
</head>

<body>
    <!-- Language Switcher - Top Right -->
    <div style="position: fixed; top: 20px; right: 20px; z-index: 1000;">
      <div class="language-switcher-container">
        <label for="language-switcher" class="language-switcher-label">
          <img src="./assets/imgs/globe-icon.svg" alt="Language" class="language-icon" style="width: 20px; height: 20px;">
        </label>
        <select id="language-switcher" class="language-switcher-select" aria-label="Select Language">
          <?php foreach ($supportedLanguages as $code => $name): ?>
            <option value="<?= htmlspecialchars($code) ?>" <?= $code === $currentLanguage ? 'selected' : '' ?>>
              <?= htmlspecialchars($name) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
    
    <?php
        include_once './pages/loader.php';
    ?>
    <?php
        include_once './pages/notifications.php';
    ?>

  <div class="login-container">
    <!-- LEFT ILLUSTRATION -->
   <div id="img-container">
      <div class="page-title">
          <h3 data-translate="Maligayang Pagdating!">Welcome!</h3>
          <p class="subtitle" data-translate="Magregister ng iyong Account">Register your Account</p>
      </div>      
      <div class="ls2-logo"> 
        <img src="./assets/imgs/LS2ES.png" alt="LS2ES Logo">
      </div>
      <img src="./assets/imgs/users-login.png" id="image" />     

    </div>

    <!-- RIGHT LOGIN FORM -->

    <!-- Registration Form -->
    <div class="container">
        <form id="registration-form" action="../BackEnd/api/postRegistrationForm.php" method="post">
            <div class="user-details">

                <!-- Informative Label -->
                <div class="form-info-section">
                    <p class="form-instruction-label">
                        <strong data-translate="Impormasyon ng Tagapag-alaga">Guardian Information</strong>
                        <span class="required" style="color: red;">*</span>
                    </p>
                </div>

                <!-- Guardian First Name -->
                <div class="input-box">
                    <input type="text" name="Guardian-First-Name" id="guardian-first-name" placeholder=" " required />
                    <span class="details" data-translate="Unang Pangalan">First Name</span>
                </div>

                <!-- Guardian Middle Name -->
                <div class="input-box">
                    <input type="text" name="Guardian-Middle-Name" id="guardian-middle-name" placeholder=" " />
                    <span class="details" data-translate="Gitnang Pangalan">Middle Name</span>
                </div>

                <!-- Guardian Last Name -->
                <div class="input-box">
                    <input type="text" name="Guardian-Last-Name" id="guardian-last-name" placeholder=" " required />
                    <span class="details" data-translate="Apelyido">Last Name</span>
                </div>

                <!-- Guardian Contact Number -->
                <div class="input-box">
                    <input type="text" name="Contact-Number" id="contact-number" placeholder=" " maxlength="11" pattern="[0-9]{11}" required />
                    <span class="details" data-translate="Numero ng Telepono">Phone Number</span>
                </div>

                <!-- Terms and Conditions Section -->
                <div class="terms-conditions-section">
                    <div class="terms-header">
                        <p class="terms-instruction">
                            <span data-translate="Basahin ang">Please read the</span> <span class="terms-link" id="open-terms-link" data-translate="Privacy Policy and Terms & Conditions">Privacy Policy and Terms & Conditions</span> <span data-translate="bago magpatuloy">before continuing</span>.
                        </p>
                    </div>
                    
                    <div class="terms-agreement">
                        <input type="checkbox" name="terms-acceptance" id="terms-acceptance" disabled required />
                        <label for="terms-acceptance" class="terms-label" id="terms-label">
                            <span data-translate="Ako'y sumasang-ayon sa nilalaman ng Terms & Conditions and Privacy Policy">I agree to the Terms & Conditions and Privacy Policy</span>
                            <span class="required">*</span>
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="button">
                <button type="submit" name="submit" class="btn submit-btn" data-translate="Magparehistro">Register</button>
                </div>
            </div>
            <!-- Sign In Link -->
            <p>
              <span data-translate="Mayroon ng account?">Already have an account?</span> 
              <a href="Login.php" class="signin-link" style="text-decoration: none;" data-translate="Mag-sign In">Sign In.</a>
            </p>
        </form>
    </div>
  </div>

  <!-- Terms and Conditions Modal -->
  <div id="terms-modal" class="terms-modal">
    <div class="terms-modal-content">
      <div class="terms-modal-header">
        <h2 data-translate="Patakaran sa Pagkapribado at Mga Tuntunin at Kondisyon">Privacy Policy and Terms & Conditions</h2>
        <span class="close-modal" id="close-terms-modal">&times;</span>
      </div>
      <div class="terms-modal-body" id="terms-modal-body">
        <div class="scroll-indicator" data-translate="Mag-scroll pababa upang magpatuloy">Scroll down to continue</div>
        
        <h3 data-translate="Patakaran sa Pagkapribado">Privacy Policy</h3>
        <p data-translate="Inilalarawan ng Patakaran sa Pagkapribado na ito kung paano kinukolekta, ginagamit, at ibinabahagi ang inyong personal na impormasyon kapag ginagamit ninyo ang aming Student Information System.">This Privacy Policy describes how your personal information is collected, used, and shared when you use our Student Information System.</p>
        
        <h4 data-translate="Impormasyong Kinukolekta Namin">Information We Collect</h4>
        <p data-translate="Kinukolekta namin ang sumusunod na impormasyon:">We collect the following information:</p>
        <ul>
          <li data-translate="Buong pangalan ng tagapag-alaga (Unang, Gitnang, Huling pangalan)">Guardian's full name (First, Middle, Last)</li>
          <li data-translate="Numero ng contact ng tagapag-alaga">Guardian's contact number</li>
          <li data-translate="Impormasyon sa pag-enrol ng mag-aaral">Student enrollment information</li>
          <li data-translate="Mga rekord at dokumento sa akademiko">Academic records and documents</li>
          <li data-translate="Personal na impormasyon ayon sa mga regulasyon ng DepEd">Personal information as required by DepEd regulations</li>
        </ul>

        <h4 data-translate="Paano Namin Ginagamit ang Inyong Impormasyon">How We Use Your Information</h4>
        <p data-translate="Ginagamit namin ang nakolektang impormasyon upang:">We use the information we collect to:</p>
        <ul>
          <li data-translate="Iproseso ang mga aplikasyon sa pag-enrol ng mag-aaral">Process student enrollment applications</li>
          <li data-translate="Panatilihing tama ang tala o records ng mga mag-aaral.">Maintain accurate student records</li>
          <li data-translate="Makipag-ugnayan tungkol sa mahalagang impormasyon hinggil sa pag-enrol at usaping pang-akademiko.">Communicate important information regarding enrollment and academic matters</li>
          <li data-translate="Sumunod sa mga kinakailangan at regulasyon ng DepEd">Comply with DepEd requirements and regulations</li>
          <li data-translate="Pagbutihin ang aming mga serbisyo gayundin ang pagpoproseso ng sistema">Improve our services and system functionality</li>
        </ul>

        <h4 data-translate="Seguridad ng Datos">Data Security</h4>
        <p data-translate="Nagsasagawa kami ng mga angkop na hakbang sa seguridad upang protektahan ang inyong personal na impormasyon mula sa walang pahintulot na pag-access, pagbabago, pagsisiwalat, o pagwasak.">We implement appropriate security measures to protect your personal information from unauthorized access, alteration, disclosure, or destruction.</p>

        <h4 data-translate="Paghawak ng Datos">Data Retention</h4>
        <p data-translate="Pinapanatili namin ang inyong impormasyon hangga't kinakailangan upang tuparin ang mga layuning nakasaad sa patakaran sa pagkapribado na ito at alinsunod sa mga kinakailangan sa pag-iingat ng rekord ng DepEd.">We retain your information as long as necessary to fulfill the purposes outlined in this privacy policy and in compliance with DepEd record-keeping requirements.</p>

        <h3 data-translate="Mga Tuntunin at Kondisyon">Terms & Conditions</h3>
        
        <h4 data-translate="Pagtanggap ng mga Tuntunin">Acceptance of Terms</h4>
        <p data-translate="Sa pamamagitan ng pagrerehistro at paggamit ng Student Information System na ito, sumasang-ayon kayong sundin ang mga Tuntunin at Kondisyon na ito.">By registering and using this Student Information System, you agree to be bound by these Terms and Conditions.</p>

        <h4 data-translate="Mga Kinakailangan sa Pagrerehistro">Registration Requirements</h4>
        <p data-translate="Kailangan ninyong magbigay ng tumpak at kumpletong impormasyon sa panahon ng pagrerehistro. Ang pagbibigay ng maling impormasyon ay maaaring magresulta sa pagtanggi o pagkansela ng pag-enrol.">You must provide accurate and complete information during registration. Providing false information may result in rejection or cancellation of enrollment.</p>

        <h4 data-translate="Mga Responsibilidad ng Gumagamit">User Responsibilities</h4>
        <ul>
          <li data-translate="Panatilihing konpidensiyal ang inyong mga kredensyal ng account">Maintain confidentiality of your account credentials</li>
          <li data-translate="Magbigay ng tumpak at totoo na impormasyon">Provide accurate and truthful information</li>
          <li data-translate="I-update ang impormasyon kaagad kapag may nagbago">Update information promptly when changes occur</li>
          <li data-translate="Gamitin lamang ang sistema para sa nilalayon nitong layunin">Use the system only for its intended purpose</li>
          <li data-translate="Sumunod sa lahat ng naaangkop na batas at mga regulasyon ng DepEd">Comply with all applicable laws and DepEd regulations</li>
        </ul>

        <h4 data-translate="Paggamit ng Sistema">System Usage</h4>
        <p data-translate="Ang sistema ay inilaan para sa pag-enrol at pamamahala ng impormasyon ng mag-aaral. Ang maling paggamit ng sistema ay maaaring magresulta sa suspensyon o pagtatapos ng account.">The system is provided for enrollment and student information management purposes. Misuse of the system may result in account suspension or termination.</p>

        <h4 data-translate="Limitasyon ng Pananagutan">Limitation of Liability</h4>
        <p data-translate="Ang paaralan at mga tagapangasiwa ng sistema ay hindi mananagot para sa anumang hindi direkta, hindi sinasadya, o kahihinatnang pinsala na dulot ng paggamit ng sistemang ito.">The school and system administrators shall not be liable for any indirect, incidental, or consequential damages arising from the use of this system.</p>

        <h4 data-translate="Mga Pagbabago">Modifications</h4>
        <p data-translate="Nakalaan sa amin ang karapatang baguhin ang mga tuntunin at kondisyon na ito anumang oras. Ang patuloy na paggamit ng sistema ay nangangahulugang pagtanggap ng mga binagong tuntunin.">We reserve the right to modify these terms and conditions at any time. Continued use of the system constitutes acceptance of modified terms.</p>

        <h4 data-translate="Impormasyon sa Pakikipag-ugnayan">Contact Information</h4>
        <p data-translate="Kung mayroon kayong mga katanungan tungkol sa Patakaran sa Pagkapribado o Mga Tuntunin at Kondisyon na ito, mangyaring makipag-ugnayan sa administrasyon ng paaralan.">If you have questions about this Privacy Policy or Terms & Conditions, please contact the school administration.</p>

        <p class="end-marker"><strong data-translate="Katapusan ng Mga Tuntunin at Kondisyon - Maaari na ninyong tanggapin ang kasunduan sa pamamagitan ng paglalagay ng tsek sa kahon. ">End of Terms & Conditions - You may now accept the agreement by putting check in the box.</strong></p>
      </div>
      <div class="terms-modal-footer">
        <button type="button" id="close-terms-btn" class="btn-close-terms" data-translate="Isara">Close</button>
      </div>
    </div>
  </div>

</body>
</html>
