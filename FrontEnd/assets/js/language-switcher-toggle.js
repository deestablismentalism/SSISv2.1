/**
 * Language Switcher Toggle Functionality
 * Handles the interactive language icon button and dropdown
 * Optimized for mobile devices
 */

(function() {
    'use strict';
    
    const toggleBtn = document.getElementById('language-toggle-btn');
    const dropdown = document.getElementById('language-switcher-dropdown');
    
    if (!toggleBtn || !dropdown) {
        console.warn('Language switcher elements not found');
        return; // Exit if elements don't exist on the page
    }
    
    console.log('Language switcher initialized');
    
    let isTouch = false;
    
    // Detect touch device
    if ('ontouchstart' in window || navigator.maxTouchPoints > 0) {
        isTouch = true;
        document.body.classList.add('touch-device');
    }
    
    // Toggle dropdown on button click/tap
    toggleBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const isActive = dropdown.classList.contains('show');
        
        if (isActive) {
            closeDropdown();
        } else {
            openDropdown();
        }
        
        // Haptic feedback on mobile
        if (isTouch && 'vibrate' in navigator) {
            navigator.vibrate(30);
        }
    });
    
    // Handle touch events separately for better mobile UX
    if (isTouch) {
        toggleBtn.addEventListener('touchstart', function(e) {
            toggleBtn.classList.add('touch-active');
        }, { passive: true });
        
        toggleBtn.addEventListener('touchend', function(e) {
            setTimeout(() => {
                toggleBtn.classList.remove('touch-active');
            }, 150);
        }, { passive: true });
    }
    
    // Close dropdown when clicking/tapping outside
    const closeOnOutsideClick = function(e) {
        if (!toggleBtn.contains(e.target) && !dropdown.contains(e.target)) {
            closeDropdown();
        }
    };
    
    if (isTouch) {
        document.addEventListener('touchstart', closeOnOutsideClick, { passive: true });
    } else {
        document.addEventListener('click', closeOnOutsideClick);
    }
    
    // Keep dropdown open when interacting with select
    dropdown.addEventListener('click', function(e) {
        e.stopPropagation();
    });
    
    if (isTouch) {
        dropdown.addEventListener('touchstart', function(e) {
            e.stopPropagation();
        }, { passive: true });
    }
    
    // Close dropdown when language is selected
    const languageSelect = document.getElementById('language-switcher');
    if (languageSelect) {
        languageSelect.addEventListener('change', function() {
            // Haptic feedback
            if (isTouch && 'vibrate' in navigator) {
                navigator.vibrate(50);
            }
            
            // Small delay to show selection before closing
            setTimeout(closeDropdown, 300);
        });
        
        // Improve mobile select experience
        if (isTouch) {
            languageSelect.addEventListener('touchend', function(e) {
                // Ensure select opens properly on mobile
                setTimeout(() => {
                    if (document.activeElement !== languageSelect) {
                        languageSelect.focus();
                    }
                }, 10);
            });
        }
    }
    
    // Close dropdown on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && dropdown.classList.contains('show')) {
            closeDropdown();
            toggleBtn.focus();
        }
    });
    
    // Handle orientation change on mobile
    if (isTouch) {
        window.addEventListener('orientationchange', function() {
            if (dropdown.classList.contains('show')) {
                // Reposition dropdown after orientation change
                setTimeout(() => {
                    dropdown.style.transition = 'none';
                    dropdown.offsetHeight; // Force reflow
                    dropdown.style.transition = '';
                }, 100);
            }
        });
    }
    
    // Prevent scroll when dropdown is open on mobile
    function preventScroll(e) {
        e.preventDefault();
    }
    
    // Helper functions
    function openDropdown() {
        dropdown.classList.add('show');
        toggleBtn.classList.add('active');
        toggleBtn.setAttribute('aria-expanded', 'true');
        
        // Prevent background scroll on mobile when dropdown is open
        if (isTouch) {
            document.body.style.overflow = 'hidden';
        }
    }
    
    function closeDropdown() {
        dropdown.classList.remove('show');
        toggleBtn.classList.remove('active');
        toggleBtn.setAttribute('aria-expanded', 'false');
        
        // Restore scroll on mobile
        if (isTouch) {
            document.body.style.overflow = '';
        }
    }
})();
