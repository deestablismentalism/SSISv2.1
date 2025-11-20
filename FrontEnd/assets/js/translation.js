/**
 * Translation Helper JavaScript
 * Simple client-side translation using data-translate attributes
 */

class TranslationHelper {
    constructor() {
        this.currentLanguage = this.getStoredLanguage() || 'en'; // Default to English
        this.originalTexts = new Map(); // Store original English text
        
        // Initialize on DOM ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.initialize());
        } else {
            this.initialize();
        }
    }

    /**
     * Initialize translation system
     */
    initialize() {
        // Store original texts first
        this.storeOriginalTexts();
        
        // Set up language switcher event listeners
        this.setupLanguageSwitcher();
        
        // Apply initial language
        this.applyCurrentLanguage();
        
        // Add mobile-specific optimizations
        this.setupMobileOptimizations();
        
        console.log('Translation system initialized. Current language:', this.currentLanguage);
    }
    
    /**
     * Setup mobile-specific optimizations
     */
    setupMobileOptimizations() {
        // Prevent double-tap zoom on language switcher
        const languageSwitcher = document.getElementById('language-switcher');
        if (languageSwitcher) {
            languageSwitcher.addEventListener('touchend', (e) => {
                e.preventDefault();
                languageSwitcher.focus();
                languageSwitcher.click();
            }, { passive: false });
        }
        
        // Add touch-friendly styling for mobile
        if ('ontouchstart' in window || navigator.maxTouchPoints > 0) {
            document.body.classList.add('touch-device');
        }
        
        // Handle orientation change
        window.addEventListener('orientationchange', () => {
            setTimeout(() => {
                this.applyCurrentLanguage();
            }, 100);
        });
    }
    
    /**
     * Store original English texts
     */
    storeOriginalTexts() {
        const elements = document.querySelectorAll('[data-translate]');
        elements.forEach(element => {
            const originalText = element.textContent.trim();
            this.originalTexts.set(element, {
                text: originalText,
                translation: element.getAttribute('data-translate')
            });
        });
    }

    /**
     * Get stored language preference
     */
    getStoredLanguage() {
        // Check localStorage first
        const stored = localStorage.getItem('preferred_language');
        if (stored) return stored;
        
        // Check session storage
        const session = sessionStorage.getItem('current_language');
        if (session) return session;
        
        return null;
    }

    /**
     * Set and store language preference
     */
    setLanguage(languageCode) {
        console.log('Switching language to:', languageCode);
        this.currentLanguage = languageCode;
        localStorage.setItem('preferred_language', languageCode);
        sessionStorage.setItem('current_language', languageCode);
        
        // Apply translations immediately
        this.applyCurrentLanguage();
    }

    /**
     * Apply translations to current page
     */
    applyCurrentLanguage() {
        console.log('Applying language:', this.currentLanguage);
        
        // Add a subtle animation for better mobile UX
        document.body.style.opacity = '0.95';
        
        // Use requestAnimationFrame for smooth rendering on mobile
        requestAnimationFrame(() => {
            // If English, restore original texts
            if (this.currentLanguage === 'en') {
                this.originalTexts.forEach((data, element) => {
                    if (element && data && data.text) {
                        element.textContent = data.text;
                    }
                });
            } else {
                // Apply translations from data-translate attribute
                this.originalTexts.forEach((data, element) => {
                    if (element && data && data.translation) {
                        element.textContent = data.translation;
                    }
                });
            }
            
            // Restore opacity
            setTimeout(() => {
                document.body.style.opacity = '1';
            }, 100);
            
            console.log('Translation applied successfully');
        });
    }

    /**
     * Setup language switcher event listeners
     */
    setupLanguageSwitcher() {
        const languageSwitcher = document.getElementById('language-switcher');
        const toggleBtn = document.getElementById('language-toggle-btn');
        const dropdown = document.getElementById('language-switcher-dropdown');
        
        // Setup toggle button functionality
        if (toggleBtn && dropdown) {
            toggleBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                dropdown.classList.toggle('show');
                toggleBtn.classList.toggle('active');
                
                // Haptic feedback
                if ('vibrate' in navigator) {
                    navigator.vibrate(30);
                }
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', (e) => {
                if (!toggleBtn.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.classList.remove('show');
                    toggleBtn.classList.remove('active');
                }
            });
            
            // Close on escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && dropdown.classList.contains('show')) {
                    dropdown.classList.remove('show');
                    toggleBtn.classList.remove('active');
                }
            });
        }
        
        if (languageSwitcher) {
            console.log('Language switcher found, setting up event listener');
            
            // Handle change event
            languageSwitcher.addEventListener('change', (e) => {
                console.log('Language changed via dropdown:', e.target.value);
                this.setLanguage(e.target.value);
                
                // Close dropdown after selection
                if (dropdown) {
                    dropdown.classList.remove('show');
                }
                if (toggleBtn) {
                    toggleBtn.classList.remove('active');
                }
                
                // Provide haptic feedback on mobile
                if ('vibrate' in navigator) {
                    navigator.vibrate(50);
                }
            });
            
            // Handle touch events for better mobile experience
            languageSwitcher.addEventListener('touchstart', (e) => {
                // Add active class for visual feedback
                languageSwitcher.classList.add('active-touch');
            });
            
            languageSwitcher.addEventListener('touchend', (e) => {
                // Remove active class
                setTimeout(() => {
                    languageSwitcher.classList.remove('active-touch');
                }, 150);
            });
            
            // Set current value
            languageSwitcher.value = this.currentLanguage;
            console.log('Language switcher value set to:', this.currentLanguage);
        } else {
            console.warn('Language switcher element not found!');
        }
    }

    /**
     * Get current language
     */
    getCurrentLanguage() {
        return this.currentLanguage;
    }
}

// Create global instance
window.translationHelper = new TranslationHelper();

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = TranslationHelper;
}
