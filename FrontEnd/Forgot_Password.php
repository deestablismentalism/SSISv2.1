<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - SSIS</title>
    <link rel="stylesheet" href="./assets/css/fonts.css">
    <link rel="stylesheet" href="./assets/css/reset.css">
    <link rel="stylesheet" href="./assets/css/forgot-password.css">
    <link rel="stylesheet" href="./assets/css/notifications.css">
    <link rel="icon" href="../favicon.ico">
</head>
<body>
    <div class="forgot-password-container">
        <div class="forgot-password-card">
            <div class="logo-section">
                <img src="./assets/imgs/graduation-cap.png" alt="SSIS Logo" class="logo">
                <h1>Forgot Password</h1>
                <p class="subtitle">Enter your phone number to reset your password</p>
            </div>

            <!-- Step 1: Phone Number Entry -->
            <form id="phone-form" class="form-section active">
                <div class="form-group">
                    <label for="phone-number">Phone Number</label>
                    <input 
                        type="text" 
                        id="phone-number" 
                        name="phone_number" 
                        placeholder="09XXXXXXXXX" 
                        pattern="^(09|\+639)\d{9}$"
                        required
                        maxlength="11"
                    >
                    <small class="form-hint">Enter your registered mobile number</small>
                </div>
                <button type="submit" class="btn btn-primary" id="send-otp-btn">
                    <span class="btn-text">Send OTP</span>
                    <span class="btn-loader" style="display: none;">Sending...</span>
                </button>
                <a href="Login.php" class="back-link">Back to Login</a>
            </form>
        </div>
    </div>

    <!-- OTP Verification Modal -->
    <div id="otp-modal" class="modal">
        <div class="modal-content otp-modal-content">
            <span class="close-modal">&times;</span>
            <h2>Enter OTP</h2>
            <p class="otp-info">We've sent a 6-digit code to <span id="masked-phone"></span></p>
            
            <form id="otp-form">
                <div class="otp-inputs">
                    <input type="text" maxlength="1" class="otp-input" data-index="0" autocomplete="off">
                    <input type="text" maxlength="1" class="otp-input" data-index="1" autocomplete="off">
                    <input type="text" maxlength="1" class="otp-input" data-index="2" autocomplete="off">
                    <input type="text" maxlength="1" class="otp-input" data-index="3" autocomplete="off">
                    <input type="text" maxlength="1" class="otp-input" data-index="4" autocomplete="off">
                    <input type="text" maxlength="1" class="otp-input" data-index="5" autocomplete="off">
                </div>
                
                <div class="timer-section">
                    <p>Code expires in: <span id="countdown">5:00</span></p>
                </div>

                <button type="submit" class="btn btn-primary" id="verify-otp-btn">
                    <span class="btn-text">Verify OTP</span>
                    <span class="btn-loader" style="display: none;">Verifying...</span>
                </button>

                <button type="button" class="btn btn-secondary" id="resend-otp-btn" disabled>
                    Resend OTP
                </button>
            </form>
        </div>
    </div>

    <!-- Reset Password Modal -->
    <div id="reset-modal" class="modal">
        <div class="modal-content reset-modal-content">
            <h2>Reset Password</h2>
            <p class="reset-info">Enter your new password</p>
            
            <form id="reset-form">
                <div class="form-group">
                    <label for="new-password">New Password</label>
                    <input 
                        type="password" 
                        id="new-password" 
                        name="new_password" 
                        required
                        minlength="8"
                        placeholder="Enter new password"
                    >
                    <small class="form-hint">Minimum 8 characters</small>
                </div>

                <div class="form-group">
                    <label for="confirm-password">Confirm Password</label>
                    <input 
                        type="password" 
                        id="confirm-password" 
                        name="confirm_password" 
                        required
                        placeholder="Confirm new password"
                    >
                </div>

                <button type="submit" class="btn btn-primary" id="reset-password-btn">
                    <span class="btn-text">Reset Password</span>
                    <span class="btn-loader" style="display: none;">Resetting...</span>
                </button>
            </form>
        </div>
    </div>

    <script src="./assets/js/notifications.js"></script>
    <script src="./assets/js/forgot-password.js"></script>
</body>
</html>
