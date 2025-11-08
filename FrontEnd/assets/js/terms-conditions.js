document.addEventListener('DOMContentLoaded', function() {
    const termsLink = document.getElementById('open-terms-link');
    const termsModal = document.getElementById('terms-modal');
    const closeModalBtn = document.getElementById('close-terms-modal');
    const closeTermsBtn = document.getElementById('close-terms-btn');
    const termsModalBody = document.getElementById('terms-modal-body');
    const termsCheckbox = document.getElementById('terms-acceptance');
    const termsLabel = document.getElementById('terms-label');
    const scrollIndicator = document.querySelector('.scroll-indicator');
    
    // Track if user has opened the modal
    let hasOpenedModal = false;
    let hasScrolledToBottom = false;
    
    // Open modal when link is clicked
    if (termsLink) {
        termsLink.addEventListener('click', function(e) {
            e.preventDefault();
            termsModal.style.display = 'block';
            hasOpenedModal = true; // Mark that user opened the modal
            resetScrollState();
        });
    }
    
    // Close modal handlers
    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', closeModal);
    }
    
    if (closeTermsBtn) {
        closeTermsBtn.addEventListener('click', closeModal);
    }
    
    // Close when clicking outside modal
    window.addEventListener('click', function(e) {
        if (e.target === termsModal) {
            closeModal();
        }
    });
    
    // Scroll detection to enable checkbox
    if (termsModalBody) {
        termsModalBody.addEventListener('scroll', function() {
            const scrollPosition = termsModalBody.scrollTop + termsModalBody.clientHeight;
            const scrollHeight = termsModalBody.scrollHeight;
            
            // User scrolled to bottom (with 10px threshold)
            if (scrollPosition >= scrollHeight - 10) {
                hasScrolledToBottom = true;
                enableCheckbox();
            }
        });
    }
    
    function closeModal() {
        termsModal.style.display = 'none';
    }
    
    function enableCheckbox() {
        // Only enable if user has BOTH opened modal AND scrolled to bottom
        if (hasOpenedModal && hasScrolledToBottom && termsCheckbox && termsLabel && scrollIndicator) {
            termsCheckbox.disabled = false;
            termsCheckbox.classList.add('enabled');
            termsLabel.classList.add('enabled');
            termsLabel.style.cursor = 'pointer';
            scrollIndicator.style.display = 'none';
        }
    }
    
    function resetScrollState() {
        // Reset scroll position but keep hasOpenedModal = true
        if (termsModalBody) {
            termsModalBody.scrollTop = 0;
        }
        
        // Don't reset hasScrolledToBottom if user already scrolled before
        // This allows checkbox to remain enabled after first complete read
        if (hasScrolledToBottom) {
            if (scrollIndicator) {
                scrollIndicator.style.display = 'none';
            }
        } else {
            if (scrollIndicator) {
                scrollIndicator.style.display = 'block';
            }
        }
    }
});
