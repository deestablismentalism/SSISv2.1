/**
 * Check if contact number exists in staffs or registrations table
 * @param {string} contactNumber - The contact number to check
 * @returns {Promise<Object>} - Result object with exists flag and message
 */
async function checkContactNumberExists(contactNumber) {
    try {
        const formData = new FormData();
        formData.append('contact_number', contactNumber);
        
        const response = await fetch('../BackEnd/api/checkContactNumber.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        return result;
    } catch (error) {
        console.error('Error checking contact number:', error);
        return {
            success: false,
            exists: null,
            message: 'Failed to validate contact number. Please try again.'
        };
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const registrationForm = document.getElementById('registration-form');
    
    if (registrationForm) {
        registrationForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Get contact number from form
            const contactNumberInput = document.querySelector('input[name="Contact-Number"]');
            const contactNumber = contactNumberInput ? contactNumberInput.value.trim() : null;
            
            if (!contactNumber) {
                if (typeof showNotification !== 'undefined') {
                    showNotification('Please enter a contact number.', 'error');
                } else {
                    alert('Please enter a contact number.');
                }
                return;
            }
            
            // Show loader
            if (typeof Loader !== 'undefined') {
                Loader.show();
            }
            
            // Check if contact number exists in database
            const checkResult = await checkContactNumberExists(contactNumber);
            
            if (!checkResult.success && checkResult.exists) {
                // Hide loader
                if (typeof Loader !== 'undefined') {
                    Loader.hide();
                }
                
                // Show error notification
                if (typeof showNotification !== 'undefined') {
                    showNotification(checkResult.message, 'error');
                } else {
                    alert(checkResult.message);
                }
                return;
            }
            
            // If check failed due to network error, show warning but allow to continue
            if (!checkResult.success && checkResult.exists === null) {
                if (typeof showNotification !== 'undefined') {
                    showNotification('Unable to verify contact number. Proceeding with registration.', 'warning');
                }
            }
            
            // Get form data
            const formData = new FormData(this);
            
            try {
                // Correct path from FrontEnd to BackEnd/api
                const response = await fetch('../BackEnd/api/postRegistrationForm.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                // Hide loader
                if (typeof Loader !== 'undefined') {
                    Loader.hide();
                }
                
                if (result.success) {
                    // Show success notification
                    if (typeof showNotification !== 'undefined') {
                        showNotification(result.message || 'Registration successful!', 'success');
                    } else {
                        alert(result.message || 'Registration successful!');
                    }
                    
                    // Redirect to login after 2 seconds
                    setTimeout(() => {
                        window.location.href = 'Login.php';
                    }, 2000);
                } else {
                    // Show error notification
                    if (typeof showNotification !== 'undefined') {
                        showNotification(result.message || 'Registration failed. Please try again.', 'error');
                    } else {
                        alert(result.message || 'Registration failed. Please try again.');
                    }
                }
            } catch (error) {
                // Hide loader
                if (typeof Loader !== 'undefined') {
                    Loader.hide();
                }
                
                console.error('Registration error:', error);
                
                if (typeof showNotification !== 'undefined') {
                    showNotification('An error occurred during registration. Please try again.', 'error');
                } else {
                    alert('An error occurred during registration. Please try again.');
                }
            }
        });
    }
});
