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
  <title>School Portal</title>
  <link rel="stylesheet" href="./assets/css/reset.css" />
  <link rel="stylesheet" href="./assets/css/login.css" />
  <link rel="stylesheet" href="./assets/css/fonts.css" />
  <link rel="stylesheet" href="./assets/css/loader.css" />
  <link rel="stylesheet" href="./assets/css/notifications.css">
  <link rel="stylesheet" href="./assets/css/language-switcher.css">
  <script src="./assets/js/loader.js"></script>
  <script src="./assets/js/login-validation.js"></script> 
  <script src="./assets/js/notifications.js"></script>
  <script src="./assets/js/translation.js"></script>

</head>

<body>
    <!-- Language Switcher - Top Right -->
    <div class="language-switcher-fixed-wrapper" style="top: 20px;">
      <div class="language-switcher-wrapper">
        <button type="button" class="language-icon-button" id="language-toggle-btn" aria-label="Toggle Language Switcher">
          <img src="./assets/imgs/globe-icon.svg" alt="Language" class="language-icon">
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
    
    <?php
        include './pages/loader.php';
    ?>
    <?php
        include_once './pages/notifications.php'
    ?>
  <div class="login-container">
    <!-- LEFT ILLUSTRATION -->
    <div class="login-illustration">
      <div class="ls2-logo"> 
        <img src="./assets/imgs/LS2ES.png" alt="LS2ES Logo">
      </div>
      <img src="./assets/imgs/users-login.png" alt="Users Illustration" />
    </div>

    <!-- RIGHT LOGIN FORM -->
    <div class="login-form">
      <h1 data-translate="Maligayang Pagdating!">Welcome!</h1>
      <p class="subtitle" data-translate="Maglogin na sa iyong Account">Please log in to your account</p>

     <form id="login-form" action="..\BackEnd\common\postLoginVerify.php" method="post">
        <div class="input-group">
          <input type="text" id="phone_number" name="phone_number" placeholder=" " maxlength="11" pattern="[0-9]{11}" required />
          <label for="phone_number" data-translate="Numero ng Telepono">Phone Number</label>
        </div>

        <div class="input-group password-group">
          <input type="password" id="password" name="password" placeholder=" " required />
          <label for="password" data-translate="Password">Password</label>
          <button type="button" class="password-toggle" id="toggle-password">
            <img src="./assets/imgs/eye-regular.svg" alt="Toggle Password" class="eye-icon" />
          </button>
        </div>

        <button type="submit" class="btn-primary" data-translate="Mag-login">Log In <span style=" place-items: center;"></span></button>
        <br>
        <br>
        <a class="forgot-password-link" href="./Forgot_Password.php" data-translate="Nakalimutan ang Password?">Forgot Password?</a>
        <p><span style="color: black;" data-translate="Wala pang account?">Don't have an account?</span>
            <a href="./Registration.php" class="register-link" data-translate="Gumawa ng Bagong Account">
                Create New Account
            </a>
        </p>



      </form>
    </div>
  </div>
</body>
</html>
