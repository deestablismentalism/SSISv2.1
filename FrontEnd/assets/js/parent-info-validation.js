document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("enrollment-form");
    const fatherLname = document.getElementById("Father-Last-Name");
    const fatherMname = document.getElementById("Father-Middle-Name");
    const fatherFname = document.getElementById("Father-First-Name");
    const fatherCPnum = document.getElementById("F-number");
    const motherLname = document.getElementById("Mother-Last-Name");
    const motherMname = document.getElementById("Mother-Middle-Name");
    const motherFname = document.getElementById("Mother-First-Name");
    const motherCPnum = document.getElementById("M-number");
    const guardianLname = document.getElementById("Guardian-Last-Name");
    const guardianMname = document.getElementById("Guardian-Middle-Name");
    const guardianFname = document.getElementById("Guardian-First-Name");
    const guardianCPnum = document.getElementById("G-number");
    
    const emptyError = "This field is required";
    const notNumber = "This field must be a number";

    const allInfo = [
        {element: fatherLname, error: "em-father-last-name"},
        {element: fatherFname, error: "em-father-first-name"},
        {element: motherLname, error: "em-mother-last-name"},
        {element: motherFname, error: "em-mother-first-name"},
        {element: guardianLname, error: "em-guardian-last-name"},
        {element: guardianFname, error: "em-guardian-first-name"}
    ];
    const phoneInfo = [
        {element: fatherCPnum, error: "em-f-number"},
        {element: motherCPnum, error: "em-m-number"},   
        {element: guardianCPnum, error: "em-g-number"}
    ];

    function validatePhoneNumber(element, errorElement, e) {
        if(ValidationUtils.isEmpty(element)) {
            ValidationUtils.errorMessages(errorElement, ValidationUtils.emptyError, element);
            ValidationUtils.checkEmptyFocus(element, errorElement);
            return false;
        }
        
        if(isNaN(e.key) && e.key !== "Backspace") {
            ValidationUtils.errorMessages(errorElement, ValidationUtils.notNumber, element);
            ValidationUtils.checkEmptyFocus(element, errorElement);
            e.preventDefault();
            return false;
        }
        
        if(element.value.length >= 11 && e.key !== "Backspace") {
            ValidationUtils.errorMessages(errorElement, "Not a valid phone number", element);
            ValidationUtils.checkEmptyFocus(element, errorElement);
            e.preventDefault();
            return false;
        }
        
        ValidationUtils.clearError(errorElement, element);
        return true;
    }

    function validateParentInfo() {
        let isValid = true;

        // Validate all required name fields
        allInfo.forEach(({element, error}) => {
            if (!ValidationUtils.validateEmpty(element, error)) {
                isValid = false;
            }
        });

        // Validate all phone numbers
        phoneInfo.forEach(({element, error}) => {
            if(ValidationUtils.isEmpty(element)) {
                ValidationUtils.errorMessages(error, ValidationUtils.emptyError, element);
                isValid = false;
            }
            else if (element.value.length !== 11) {
                ValidationUtils.errorMessages(error, "Phone number must be 11 digits", element);
                isValid = false;
            }
        });

        ValidationUtils.validationState.parentInfo = isValid;
        return isValid;
    }

    // Event Listeners
    phoneInfo.forEach(({element, error}) => {
        element.addEventListener('keydown', (e)=> validatePhoneNumber(element, error, e));
        element.addEventListener('blur', function() {
            if(ValidationUtils.isEmpty(element)) {
                ValidationUtils.errorMessages(error, ValidationUtils.emptyError, element);
                ValidationUtils.checkEmptyFocus(element, error);
            }
            validateParentInfo();
        });
    });

    allInfo.forEach(({element, error}) => {
        element.addEventListener('keyup', () => {
            ValidationUtils.validateEmpty(element, error);
            validateParentInfo();
        });
    });

    // Expose the validation function to the global scope
    window.validateParentInfo = validateParentInfo;
});