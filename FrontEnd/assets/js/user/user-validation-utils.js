// Shared validation utilities
const ValidationUtils = {
    emptyError: "This field is required",
    notNumber: "This field must be a number",
    
    isEmpty(element) {
        return !element.value.trim();
    },
    clearError(errorElement, childElement) {
        let container = childElement.parentElement.querySelector('.error-msg');
        if (!container) {
            container = childElement.closest('div').querySelector('.error-msg');
        }
        if (!container) {
            container = childElement.parentElement.parentElement.querySelector('.error-msg');
        }
        const errorSpan = container.querySelector('.' + errorElement);

        container.classList.remove('show');
        childElement.style.border = "1px solid #616161";
        if (errorSpan) {
            errorSpan.innerHTML = '';
        }
    },
    errorMessages(errorElement, message, childElement) {
        let container = childElement.parentElement.querySelector('.error-msg');
        if (!container) {
            container = childElement.closest('div').querySelector('.error-msg');
        }
        if (!container) {
            container = childElement.parentElement.parentElement.querySelector('.error-msg');
        }
        const errorSpan = container.querySelector('.' + errorElement);

        container.classList.add('show');
        childElement.style.border = "1px solid red";
        if (errorSpan) {
            errorSpan.innerHTML = message;
        }
        return false;
    },
    checkEmptyFocus(element, errorElement) {
        element.addEventListener('blur', () => this.clearError(errorElement, element));
    },

    validateEmpty(element, errorElement) {
        if(this.isEmpty(element)) {
            this.errorMessages(errorElement, this.emptyError, element);
            this.checkEmptyFocus(element, errorElement);
            return false;
        }
        this.clearError(errorElement, element);
        return true;
    },
    // Central validation state tracker
    validationState: {
        studentInfo: true,
        parentInfo: true,
        addressInfo: true,
        previousSchool: true
    },

    // Method to check if all validations pass
    isFormValid() {
        return Object.values(this.validationState).every(state => state === true);
    }
};
// Export for use in other files
window.ValidationUtils = ValidationUtils; 