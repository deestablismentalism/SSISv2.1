document.addEventListener('DOMContentLoaded', function() {
    const phoneForm = document.getElementById('phone-form');
    const otpModal = document.getElementById('otp-modal');
    const resetModal = document.getElementById('reset-modal');
    const otpForm = document.getElementById('otp-form');
    const resetForm = document.getElementById('reset-form');
    const closeModalBtns = document.querySelectorAll('.close-modal');
    const otpInputs = document.querySelectorAll('.otp-input');
    const resendBtn = document.getElementById('resend-otp-btn');
    const phoneInput = document.getElementById('phone-number');
    
    let countdownInterval;
    let phoneNumber = '';
    let otpToken = '';

    phoneInput.addEventListener('input', function(e) {
        this.value = this.value.replace(/\D/g, '').substring(0, 11);
    });

    phoneForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        phoneNumber = document.getElementById('phone-number').value.trim();
        const sendBtn = document.getElementById('send-otp-btn');
        
        setButtonLoading(sendBtn, true);

        try {
            const response = await fetch('../BackEnd/common/sendOTP.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    action: 'request_otp',
                    phone_number: phoneNumber
                })
            });

            const data = await response.json();

            if (data.success) {
                otpToken = data.token;
                Notification.show({
                    type: "success",
                    title: "Success",
                    message: data.message
                });
                showOTPModal();
                startCountdown(300);
            } else {
                Notification.show({
                    type: "error",
                    title: "Error",
                    message: data.message
                });
            }
        } catch (error) {
            Notification.show({
                type: "error",
                title: "Error",
                message: 'An error occurred. Please try again.'
            });
        } finally {
            setButtonLoading(sendBtn, false);
        }
    });

    otpInputs.forEach((input, index) => {
        input.addEventListener('input', function(e) {
            const value = e.target.value;
            
            if (!/^\d*$/.test(value)) {
                e.target.value = '';
                return;
            }

            if (value.length === 1) {
                input.classList.add('filled');
                if (index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
            } else {
                input.classList.remove('filled');
            }
        });

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && !e.target.value && index > 0) {
                otpInputs[index - 1].focus();
                otpInputs[index - 1].value = '';
                otpInputs[index - 1].classList.remove('filled');
            }
        });

        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const pasteData = e.clipboardData.getData('text').trim();
            
            if (/^\d{6}$/.test(pasteData)) {
                pasteData.split('').forEach((char, i) => {
                    if (otpInputs[i]) {
                        otpInputs[i].value = char;
                        otpInputs[i].classList.add('filled');
                    }
                });
                otpInputs[5].focus();
            }
        });
    });

    otpForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const otp = Array.from(otpInputs).map(input => input.value).join('');
        
        if (otp.length !== 6) {
            Notification.show({
                type: "error",
                title: "Error",
                message: 'Please enter the complete 6-digit OTP.'
            });
            return;
        }

        const verifyBtn = document.getElementById('verify-otp-btn');
        setButtonLoading(verifyBtn, true);

        try {
            const response = await fetch('../BackEnd/common/sendOTP.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    action: 'verify_otp',
                    phone_number: phoneNumber,
                    otp: otp,
                    token: otpToken
                })
            });

            const data = await response.json();

            if (data.success) {
                Notification.show({
                    type: "success",
                    title: "Success",
                    message: data.message
                });
                clearInterval(countdownInterval);
                hideModal(otpModal);
                showResetModal();
            } else {
                Notification.show({
                    type: "error",
                    title: "Error",
                    message: data.message
                });
                otpInputs.forEach(input => {
                    input.value = '';
                    input.classList.remove('filled');
                });
                otpInputs[0].focus();
            }
        } catch (error) {
            Notification.show({
                type: "error",
                title: "Error",
                message: "An error occurred. Please try again."
            });
        } finally {
            setButtonLoading(verifyBtn, false);
        }
    });

    resendBtn.addEventListener('click', async function() {
        setButtonLoading(resendBtn, true);

        try {
            const response = await fetch('../BackEnd/common/sendOTP.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    action: 'request_otp',
                    phone_number: phoneNumber
                })
            });

            const data = await response.json();

            if (data.success) {
                otpToken = data.token;
                Notification.show({
                    type: "success",
                    title: "Success",
                    message: data.message
                });
                startCountdown(300);
                otpInputs.forEach(input => {
                    input.value = '';
                    input.classList.remove('filled');
                });
                otpInputs[0].focus();
            } else {
                Notification.show({
                    type: "error",
                    title: "Error",
                    message: data.message
                });
            }
        } catch (error) {
            Notification.show({
                type: "error",
                title: "Error",
                message: 'An error occurred. Please try again.'
            });
        } finally {
            setButtonLoading(resendBtn, false);
        }
    });

    resetForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const newPassword = document.getElementById('new-password').value;
        const confirmPassword = document.getElementById('confirm-password').value;

        if (newPassword !== confirmPassword) {
            Notification.show({
                type: "error",
                title: "Error",
                message: 'Passwords do not match.'
            });
            return;
        }

        if (newPassword.length < 8) {
            Notification.show({
                type: "error",
                title: "Error",
                message: 'Password must be at least 8 characters long.'
            });
            return;
        }

        const resetBtn = document.getElementById('reset-password-btn');
        setButtonLoading(resetBtn, true);

        try {
            const response = await fetch('../BackEnd/common/resetPassword.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    phone_number: phoneNumber,
                    new_password: newPassword,
                    token: otpToken
                })
            });

            const data = await response.json();

            if (data.success) {
                Notification.show({
                    type: "success",
                    title: "Success",
                    message: data.message
                });
                setTimeout(() => {
                    window.location.href = 'Login.php';
                }, 2000);
            } else {
                Notification.show({
                    type: "error",
                    title: "Error",
                    message: data.message
                });
            }
        } catch (error) {
            Notification.show({
                type: "error",
                title: "Error",
                message: "An error occurred. Please try again."
            });
        } finally {
            setButtonLoading(resetBtn, false);
        }
    });

    closeModalBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const modal = this.closest('.modal');
            hideModal(modal);
            clearInterval(countdownInterval);
        });
    });

    window.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal')) {
            hideModal(e.target);
            clearInterval(countdownInterval);
        }
    });

    function showOTPModal() {
        const maskedPhone = maskPhoneNumber(phoneNumber);
        document.getElementById('masked-phone').textContent = maskedPhone;
        otpModal.classList.add('show');
        otpInputs[0].focus();
    }

    function showResetModal() {
        resetModal.classList.add('show');
        document.getElementById('new-password').focus();
    }

    function hideModal(modal) {
        modal.classList.remove('show');
    }

    function maskPhoneNumber(phone) {
        const cleaned = phone.replace(/\D/g, '');
        if (cleaned.length === 11) {
            return `${cleaned.substring(0, 4)}***${cleaned.substring(7)}`;
        }
        return phone;
    }

    function startCountdown(seconds) {
        resendBtn.disabled = true;
        let timeLeft = seconds;
        const countdownEl = document.getElementById('countdown');
        countdownEl.classList.remove('expired');

        countdownInterval = setInterval(() => {
            timeLeft--;
            const minutes = Math.floor(timeLeft / 60);
            const secs = timeLeft % 60;
            countdownEl.textContent = `${minutes}:${secs.toString().padStart(2, '0')}`;

            if (timeLeft <= 0) {
                clearInterval(countdownInterval);
                countdownEl.classList.add('expired');
                countdownEl.textContent = 'Expired';
                resendBtn.disabled = false;
                Notification.show({
                    type: "error",
                    title: "Error",
                    message: 'OTP has expired. Please resend OTP.'
                });
            }
        }, 1000);
    }

    function setButtonLoading(button, isLoading) {
        if (isLoading) {
            button.classList.add('loading');
            button.disabled = true;
        } else {
            button.classList.remove('loading');
            button.disabled = false;
        }
    }
});
