document.addEventListener('DOMContentLoaded', function(){
    const textFields = document.querySelectorAll('input[type="text"]');
    const psaNumber = document.getElementById("PSA-number");
    const lrn = document.getElementById("LRN");
    const lname = document.getElementById("lname");
    const fname = document.getElementById("fname");
    const birthDate = document.getElementById("bday");
    const age = document.getElementById("age");
    const language = document.getElementById("language");
    const religion = document.getElementById("religion");
    const disability = document.getElementById("boolsn");
    const assistiveTech = document.getElementById("atdevice");
    const nativeGroup = document.getElementById("community");

    const disabilityFields = [
        {element: nativeGroup, error: "em-community"},
        {element: disability, error: "em-boolsn"},
        {element: assistiveTech, error: "em-atdevice"}
    ];
    // Set max date to today and min date to 3 years ago
    const today = new Date();
    const minDate = new Date();
    minDate.setFullYear(today.getFullYear() - 25); // Assuming max age is 25 for enrollment
    const maxDate = new Date();
    maxDate.setFullYear(today.getFullYear() - 3); // Assuming minimum age is 3 for enrollment

    // Set the date input constraints
    birthDate.max = formatDate(maxDate);
    birthDate.min = formatDate(minDate);

    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }
    function validateDisability(element, error) {
        if (!element.disabled) {
            if (ValidationUtils.isEmpty(element)) {
                ValidationUtils.errorMessages(error, ValidationUtils.emptyError, element);
            }
            else {
                ValidationUtils.clearError(error, element);
            }
        }
        else {
            ValidationUtils.clearError(error, element);
        }
    }
    textFields.forEach(input=> {
        input.addEventListener('input', function(e) {
             const value = e.target.value;
             const cursorPos = e.target.selectionStart;

             if(value.length > 0) {
                const firstChar = value.charAt(0);
                if (firstChar === firstChar.toLowerCase() && firstChar !== firstChar.toUpperCase()) {
                    e.target.value = firstChar.toUpperCase() + value.slice(1);
                    e.target.setSelectionRange(cursorPos, cursorPos);
                }
             }
             if (cursorPos > 0) {
                e.target.setSelectionRange(cursorPos, cursorPos);
             }
        })
    })
    disabilityFields.forEach(({element, error}) => {
        if(!element.disabled) {
            element.addEventListener('input', () => validateDisability(element, error));
        }
        else {
            ValidationUtils.clearError(error, element);
        }
    });
    document.querySelectorAll('input[name="LRN"]').forEach(radio => {
        radio.addEventListener('change', function() {
            if (radio.value === "0" || radio.value === "2") {
                lrn.disabled = true;
                lrn.style.opacity = "0.2";
                lrn.value ="";
                ValidationUtils.clearError("em-LRN", lrn);
            } else {
                lrn.disabled = false;
                lrn.style.opacity = "1";
            }
        })
    });
    const allInfo = [
        {element: lname, error: "em-lname"},
        {element: fname, error: "em-fname"},
        {element: language, error: "em-language"},
        {element: religion, error: "em-religion"}
    ];
    const radioGroups = [
        {textBoxElement: "community", nameValue: "group",error: "em-community"},
        {textBoxElement: "boolsn", nameValue: "sn",error: "em-boolsn"},
        {textBoxElement: "atdevice", nameValue: "at",error: "em-atdevice"}
    ];
    //regex
    const lrnRegex = /^([0-9]){12}$/;
    const bCertRegex = /^([0-9]){13}$/;
    //emptyError
    const emptyError = "This field is required";
    const notNumber = "This field must be a number";
    // Add these validation state objects after the regex declarations
    const validationState = {
        psa: {
            isEmpty: false,
            isInvalidFormat: false,
            isNonNumeric: false
        },
        lrn: {
            isEmpty: false,
            isInvalidFormat: false,
            isNonNumeric: false
        }
    };
    //functions
    function checkIndigenous(textBoxElement, nameValue, error) {
        const radioInput = document.querySelector(`input[name="${nameValue}"]:checked`);
        const textbox = document.getElementById(textBoxElement);
        if (radioInput.value === "0") {
            textbox.disabled = true;
            textbox.style.opacity = "0.2";
            textbox.value ="";
            if (textbox.disabled) {
                ValidationUtils.clearError(error, textbox);
            }
        } else {
            textbox.disabled = false;
            textbox.style.opacity = "1";
        }
    }
    function validateAge(ageValue) {
        const minAge = 3;
        const maxAge = 25;
        
        if (isNaN(ageValue)) {
            return ValidationUtils.errorMessages("em-age", "Age must be a number", age);
        }
        
        if (ageValue < minAge) {
            return ValidationUtils.errorMessages("em-age", "Student must be at least 3 years old", age);
        }
        
        if (ageValue > maxAge) {
            return ValidationUtils.errorMessages("em-age", "Student must be 25 years old or younger", age);
        }

        ValidationUtils.clearError("em-age", age);
        return true;
    }
    function setBirthYear() {
        let ageValue = parseInt(age.value, 10);
        
        if (validateAge(ageValue)) {
            let currentYear = today.getFullYear();
            let setYear = currentYear - ageValue;
            let formattedDate = `${setYear}-01-01`;
            birthDate.value = formattedDate;
        }
    }
    function getAge() {
        let currentYear = today.getFullYear();
        let currentMonth = today.getMonth()+1;
        let currentDay = today.getDate(); 

        let bday = birthDate.value;
        if (!bday) {
            ValidationUtils.errorMessages("em-bday", "Please select a birth date", birthDate);
            return false;
        }

        let getDate = new Date(bday);
        
        // Validate if date is in the future
        if (getDate > today) {
            ValidationUtils.errorMessages("em-bday", "Birth date cannot be in the future", birthDate);
            return false;
        }

        let birthYear = getDate.getFullYear();
        let birthMonth = getDate.getMonth()+1;
        let birthDay = getDate.getDate();

        let ageValue = currentYear - birthYear;
        if (currentMonth < birthMonth || (currentMonth === birthMonth && currentDay < birthDay)) {
            ageValue--;
        }

        if (validateAge(ageValue)) {
            age.value = ageValue;
            ValidationUtils.clearError("em-bday", birthDate);
            return true;
        } else {
            age.value = "";
            return false;
        }
    }
    // Clear error messages on input/change
    age.addEventListener('input', function(e) {
        const value = e.target.value;

        if(/\D/.test(value)) {
            const cursorPos = e.target.selectionStart;
            const sanitizedValue = value.replace(/\D/g, '');
            const posDiff = value.length - sanitizedValue.length;
            e.target.value = sanitizedValue.slice(0, 2);
            e.target.setSelectionRange(cursorPos - posDiff, cursorPos - posDiff);
            value = sanitizedValue;
        }
        
        if(value.length > 2) {
            e.target.value = value.slice(0, 2);
        }

        if (value) {
            validateAge(parseInt(value, 10));
        }
        setBirthYear();
    });
    birthDate.addEventListener('change', function() {
        getAge();
    });
    function validatePSA() {
        const value = psaNumber.value.trim();
        
        // Reset validation state
        validationState.psa = {
            isEmpty: false,
            isInvalidFormat: false,
            isNonNumeric: false
        };

        // Check for empty value
        if (!value) {
            validationState.psa.isEmpty = true;
            ValidationUtils.errorMessages("em-PSA-number", emptyError, psaNumber);
            return false;
        }

        // Check for non-numeric values
        if (!/^\d*$/.test(value)) {
            validationState.psa.isNonNumeric = true;
            ValidationUtils.errorMessages("em-PSA-number", notNumber, psaNumber);
            return false;
        }

        // Check length and format
        if (!bCertRegex.test(value)) {
            validationState.psa.isInvalidFormat = true;
            ValidationUtils.errorMessages("em-PSA-number", 
                value.length > 13 ? "Only 13 digits are allowed" : "Enter a valid birth certificate number", 
                psaNumber
            );
            return false;
        }

        ValidationUtils.clearError("em-PSA-number", psaNumber);
        return true;
    }
    function validateLRN() {
        const value = lrn.value.trim();
        
        // Reset validation state
        validationState.lrn = {
            isEmpty: false,
            isInvalidFormat: false,
            isNonNumeric: false
        };

        // Skip validation if LRN is disabled
        if (lrn.disabled) {
            ValidationUtils.clearError("em-LRN", lrn);
            return true;
        }

        // Check for empty value
        if (!value) {
            validationState.lrn.isEmpty = true;
            ValidationUtils.errorMessages("em-LRN", emptyError, lrn);
            return false;
        }

        // Check for non-numeric values
        if (!/^\d*$/.test(value)) {
            validationState.lrn.isNonNumeric = true;
            ValidationUtils.errorMessages("em-LRN", notNumber, lrn);
            return false;
        }

        // Check length and format
        if (!lrnRegex.test(value)) {
            validationState.lrn.isInvalidFormat = true;
            ValidationUtils.errorMessages("em-LRN", 
                value.length > 12 ? "Only 12 digits are allowed" : "Enter a valid LRN",
                lrn
            );
            return false;
        }

        ValidationUtils.clearError("em-LRN", lrn);
        return true;
    }
    //event trigger
    age.addEventListener('change', setBirthYear);
    birthDate.addEventListener('change', getAge);

    radioGroups.forEach(({textBoxElement, nameValue})=>{
        document.querySelectorAll(`input[name="${nameValue}"]`).forEach(radio=>{
            radio.addEventListener('change',()=>checkIndigenous(textBoxElement, nameValue));
        });
    });
    allInfo.forEach(({element, error}) => {
        element.addEventListener('keyup', ()=>ValidationUtils.validateEmpty(element, error));
    });

    // Add PSA and LRN to required fields
    const numberFields = [
        {element: psaNumber, error: "em-PSA-number"},
        {element: lrn, error: "em-LRN"},
        {element: age, error: "em-age"}
    ];

    // Add empty validation for number fields
    numberFields.forEach(({element, error}) => {
        element.addEventListener('blur', function() {
            if (element === lrn && lrn.disabled) {
                ValidationUtils.clearError(error, element);
                return;
            }
            if (ValidationUtils.isEmpty(element)) {
                ValidationUtils.errorMessages(error, emptyError, element);
                ValidationUtils.checkEmptyFocus(element, error);
            }
        });
    });
    psaNumber.addEventListener('input', function(e) {
        const value = e.target.value;
        // Only sanitize if there are actual changes to sanitize
        if (/\D/.test(value)) {
            // Preserve cursor position
            const cursorPos = e.target.selectionStart;
            const sanitizedValue = value.replace(/\D/g, '');
            e.target.value = sanitizedValue.slice(0, 13);
            // Restore cursor position accounting for removed characters
            const posDiff = value.length - sanitizedValue.length;
            e.target.setSelectionRange(cursorPos - posDiff, cursorPos - posDiff);
            value = sanitizedValue;
        }
        validatePSA();
    });

    // Add keydown handler for special keys
    psaNumber.addEventListener('keydown', function(e) {
        // Allow: backspace, delete, tab, escape, enter
        if ([46, 8, 9, 27, 13].indexOf(e.key) !== -1 ||
            // Allow: Ctrl+A, Command+A
            (e.key === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
            // Allow: Ctrl+C, Command+C
            (e.key === 67 && (e.ctrlKey === true || e.metaKey === true)) ||
            // Allow: Ctrl+V, Command+V
            (e.key === 86 && (e.ctrlKey === true || e.metaKey === true)) ||
            // Allow: Ctrl+X, Command+X
            (e.key === 88 && (e.ctrlKey === true || e.metaKey === true)) ||
            // Allow: home, end, left, right, up, down
            (e.key >= 35 && e.key <= 40)) {
            return;  // let it happen, don't do anything
        }
    });

    psaNumber.addEventListener('blur', validatePSA);

    lrn.addEventListener('input', function(e) {
        const value = e.target.value;
        
        // Only sanitize if there are actual changes to sanitize
        if (/\D/.test(value)) {
            // Preserve cursor position
            const cursorPos = e.target.selectionStart;
            const sanitizedValue = value.replace(/\D/g, '');
            e.target.value = sanitizedValue.slice(0, 12);
            // Restore cursor position accounting for removed characters
            const posDiff = value.length - sanitizedValue.length;
            e.target.setSelectionRange(cursorPos - posDiff, cursorPos - posDiff);
            value = sanitizedValue;
        }
        else if (lrn.length > 12) {
            e.preventDefault();
        }
        validateLRN();
    });

    // Add keydown handler for special keys
    lrn.addEventListener('keydown', function(e) {
        // Allow: backspace, delete, tab, escape, enter
        if ([46, 8, 9, 27, 13].indexOf(e.keyCode) !== -1 ||
            // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
            // Allow: Ctrl+C, Command+C
            (e.keyCode === 67 && (e.ctrlKey === true || e.metaKey === true)) ||
            // Allow: Ctrl+V, Command+V
            (e.keyCode === 86 && (e.ctrlKey === true || e.metaKey === true)) ||
            // Allow: Ctrl+X, Command+X
            (e.keyCode === 88 && (e.ctrlKey === true || e.metaKey === true)) ||
            // Allow: home, end, left, right, up, down
            (e.keyCode >= 35 && e.keyCode <= 40)) {
            return;  // let it happen, don't do anything
        }
    });

    lrn.addEventListener('blur', validateLRN);

    function validateStudentInfo() {
        let isValid = true;

        // Validate required fields
        const requiredFields = [
            {element: lname, error: "em-lname"},
            {element: fname, error: "em-fname"},
            {element: language, error: "em-language"},
            {element: religion, error: "em-religion"}
        ];

        requiredFields.forEach(({element, error}) => {
            if (!ValidationUtils.validateEmpty(element, error)) {
                isValid = false;
            }
        });

        // Validate PSA number
        if (!validatePSA()) {
            isValid = false;
        }

        // Validate LRN if enabled
        if (!lrn.disabled && !validateLRN()) {
            isValid = false;
        }

        // Validate age and birth date
        if (!validateAge(parseInt(age.value)) || !birthDate.value || !getAge()) {
            isValid = false;
        }

        ValidationUtils.validationState.studentInfo = isValid;
        return isValid;
    }
    // Expose the validation function to the global scope
    window.validateStudentInfo = validateStudentInfo;
});