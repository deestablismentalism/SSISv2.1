import {ValidationUtils,capitalizeFirstLetter,generateOptions, getRegions, getProvinces, getCities, getBarangays, preventCharactersByRegex, limitCharacters} from "../utils.js";

// Report Card Validation State
let reportCardValidationStatus = null;
let isValidatingReportCard = false;

// Form submission state
let isSubmittingForm = false;

document.addEventListener('DOMContentLoaded',function(){
    // |=================|
    // |===== ORDER =====|
    // |=================|
    //1ST PREVIOUS SCHOOL INFO
    //2ND STUDENT INFO
    //3RD DISABILITY
    //4TH ADDRESS
    //5TH PARENT INFO 
    //|=========================|
    //|===== HTML ELEMENTS =====|
    //|=========================|
    // === PREVIOUS SCHOOL INFORMATION (FORM 1ST PART) ===
    const startYear = document.getElementById("start-year");
    const endYear = document.getElementById("end-year");
    const lastYear = document.getElementById("last-year");
    const lschool = document.getElementById("lschool");
    const lschoolAddr = document.getElementById("lschoolAddress");
    const lschoolId = document.getElementById("lschoolID");
    const fschool = document.getElementById("fschool");
    const fschoolAddr = document.getElementById("fschoolAddress");
    const fschoolId = document.getElementById("fschoolID");
    const enrollingGradeLevel = document.getElementById("grades-tbe");
    const lastGradeLevel = document.getElementById("last-grade");
    // === STUDENT INFORMATION(FORM 2ND PART) ===
    const psaNumber = document.getElementById("PSA-number");
    const lrn = document.getElementById("LRN");
    const lname = document.getElementById("lname");
    const mname = document.getElementById("mname");
    const fname = document.getElementById("fname");
    const birthDate = document.getElementById("bday");
    const age = document.getElementById("age");
    const nativeGroup = document.getElementById("community");
    const language = document.getElementById("language");
    const religion = document.getElementById("religion");
    //=== STUDENT IF DISABLED (FORM 3RD PART) ===
    // Old fields - kept for backwards compatibility but may not be in use
    const disability = document.getElementById("boolsn"); // Old field
    const assistiveTech = document.getElementById("atdevice"); // Old field
    //=== STUDENT ADDRESS (FORM 4TH PART) ===
    const regions = document.getElementById("region");
    const provinces = document.getElementById("province");
    const cityOrMunicipality = document.getElementById("city-municipality");
    const barangay = document.getElementById("barangay");
    const subdivsion = document.getElementById("subdivision");
    const houseNumber = document.getElementById("house-number");
    //===STUDENT PARENTS INFORMATION (FORM 5TH PART) ===
    const motherMname = document.getElementById("Mother-Middle-Name");
    const guardianLname = document.getElementById("Guardian-Last-Name");
    const guardianFname = document.getElementById("Guardian-First-Name");
    const guardianMname = document.getElementById("Guardian-Middle-Name");
    const guardianCPnum = document.getElementById("G-number");
    //===REPORT CARD INPUTS===
    const reportCardFront = document.getElementById("report-card-front");
    const reportCardBack = document.getElementById("report-card-back");
    //===FORM && FORM BUTTON ===
    const form = document.getElementById('enrollment-form');
    const submitButton = form.querySelector('button[type="submit"]');
    // |============================|
    // |===== DECLARED VALUES ======|
    // |============================|
    // === DATES ===
    const today = new Date();
    const year = today.getFullYear();
    const minDate = new Date();
    minDate.setFullYear(today.getFullYear() - 25);
    const maxDate = new Date();
    maxDate.setFullYear(today.getFullYear() - 3);
    // === REGEX ===
    const lrnRegex = /^([0-9]){12}$/;
    const bCertRegex = /^([0-9]){13}$/;
    const yearRegex = /^(1[0-9]{3}|2[0-9]{3}|3[0-9]{3})$/;
    const idRegex = /^([0-9]){6}$/;
    const charRegex = /^[A-Za-z0-9\s.,'-]{3,100}$/;
    const onlyDigits = /^[0-9]+$/;
    const nonAlphaRegex = /[^A-Za-z\s]/g; 
    const nonNumericRegex = /[^0-9]/g;
    const nameRegex = /^[\p{L}\s'\-\.]+$/u;
    const nonNameRegex = /[^\p{L}\s'\-\.]/gu; 

    const numLimitLRN = 12;
    const numLimitPSA = 13;
    const numLimitSchoolID = 6;
    const numLimitPhone = 11;

    // |=============================|
    // |===== VALIDATION UTILS ======|
    // |=============================|
    function initialSelectValue(selectElement, parentElement) {
        selectElement.innerHTML = `<option value=""> Select a ${parentElement} first </option>`;
    }
    async function replaceTextBox(replaceElement, addressType) {
        let createTBox = document.createElement("input");
        createTBox.type = "text";
        createTBox.id = addressType;
        createTBox.name = replaceElement.name || addressType;
        createTBox.placeholder = `Enter ${addressType} manually`;
        createTBox.className = "textbox";
        replaceElement.replaceWith(createTBox);
        
        // If replacing city/municipality, also replace barangay with text input
        if (addressType === "city-municipality" || addressType === "city") {
            const barangayElement = document.getElementById("barangay");
            if (barangayElement && barangayElement.tagName === "SELECT") {
                const barangayTextBox = document.createElement("input");
                barangayTextBox.type = "text";
                barangayTextBox.id = "barangay";
                barangayTextBox.name = "barangay";
                barangayTextBox.placeholder = "Enter barangay manually";
                barangayTextBox.className = "textbox";
                barangayElement.replaceWith(barangayTextBox);
                // Update the reference and add event listener
                const newBarangay = document.getElementById("barangay");
                if (newBarangay) {
                    // Set the barangay-name hidden field
                    const barangayNameField = document.getElementById("barangay-name");
                    if (barangayNameField) {
                        barangayNameField.value = newBarangay.value || '';
                    }
                    // Add input event listener
                    newBarangay.addEventListener('input', function() {
                        if (barangayNameField) {
                            barangayNameField.value = this.value;
                        }
                        validateAddressInfo();
                    });
                }
            }
        }
        return createTBox;
    }
    async function changeAddressValues() {
        try {
            const addressData = {
                region: { code: '', text: '' },
                province: { code: '', text: '' },
                city: { code: '', text: '' },
                barangay: { code: '', text: '' }
            };
            if(regions.value && regions.selectedIndex > 0) {
                addressData.region.code = regions.value;
                addressData.region.text = regions.options[regions.selectedIndex].text;
            }            
            if(provinces.value && provinces.selectedIndex > 0) {
                addressData.province.code = provinces.value;
                addressData.province.text = provinces.options[provinces.selectedIndex].text;
            }
            if(cityOrMunicipality.value) {
                if (cityOrMunicipality.tagName === "SELECT" && cityOrMunicipality.selectedIndex > 0) {
                    addressData.city.code = cityOrMunicipality.value;
                    addressData.city.text = cityOrMunicipality.options[cityOrMunicipality.selectedIndex].text;
                } else if (cityOrMunicipality.tagName === "INPUT") {
                    addressData.city.code = '';
                    addressData.city.text = cityOrMunicipality.value;
                }
            }
            if(barangay.value) {
                if (barangay.tagName === "SELECT" && barangay.selectedIndex > 0) {
                    addressData.barangay.code = barangay.value;
                    addressData.barangay.text = barangay.options[barangay.selectedIndex].text;
                } else if (barangay.tagName === "INPUT") {
                    addressData.barangay.code = '';
                    addressData.barangay.text = barangay.value;
                }
            }
            Object.entries(addressData).forEach(([key, value]) => {
                let codeInput = form.querySelector(`input[name="${key}_code"]`);
                if (!codeInput) {
                    codeInput = document.createElement('input');
                    codeInput.type = 'hidden';
                    codeInput.name = `${key}_code`;
                    form.appendChild(codeInput);
                }
                codeInput.value = value.code;
                let textInput = form.querySelector(`input[name="${key}_text"]`);
                if (!textInput) {
                    textInput = document.createElement('input');
                    textInput.type = 'hidden';
                    textInput.name = `${key}_text`;
                    form.appendChild(textInput);
                }
                textInput.value = value.text;
            });
            
            // Also set the name fields that PHP expects
            if (addressData.city.text) {
                const cityNameField = document.getElementById("city-municipality-name");
                if (cityNameField) {
                    cityNameField.value = addressData.city.text;
                }
            }
            if (addressData.barangay.text) {
                const barangayNameField = document.getElementById("barangay-name");
                if (barangayNameField) {
                    barangayNameField.value = addressData.barangay.text;
                }
            }
            
            return addressData;
        } catch(error) {
            console.error('Error in changeAddressValues:', error);
            return null;
        }
    }
    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }
    function checkIfNumericInput(element) {
        element.addEventListener('beforeinput', function(e) {
            if (e.inputType === 'insertText' && /\D/.test(e.data)) {
                e.preventDefault(); // stop invalid character before it appears
                return;
            }
        });
    }
    // |========================|
    // |===== VALIDATIONS ======|
    // |========================|
    // === ENROLLING AND LAST GRADE LEVEL SCHOOL VALIDATION + INIT ===
    // lgl = egl - 1 && egl = lgl+ 1
    function syncSelects(currentSelect,otherSelect) { // OK
        const selectedIndex = currentSelect.selectedIndex;
        if(currentSelect.id === 'grades-tbe') {
            const prevIndex = selectedIndex - 1;
            if(selectedIndex === 0) {
                otherSelect.selectedIndex = 0;
            }
            if(prevIndex >= 0) {
                otherSelect.selectedIndex = prevIndex;
            }
        }
        else {
            if(selectedIndex === 0) {
                otherSelect.selectedIndex = 0;
            }
            else {
                const nextIndex = selectedIndex + 1;
                if(nextIndex === 1) {
                    currentSelect.disabled = true;
                    lastGradeLevel.style.opacity = 0.5;
                    lastGradeLevel.value = '';
                    ValidationUtils.clearError('em-last-grade-level',lastGradeLevel);
                }
                if(nextIndex < otherSelect.options.length) {
                    otherSelect.selectedIndex = nextIndex;
                }
            }
        }
    }
    function toggleEnrollingGradeLevelRelatedDisables() { // OK
        const selectedIndex = enrollingGradeLevel.selectedIndex;
        if(selectedIndex === 1) {
            //DISABLE LAST GRADE LEVEL
            lastGradeLevel.disabled = true;
            lastGradeLevel.style.opacity = 0.5;
            lastGradeLevel.value = '';
            ValidationUtils.clearError('em-last-grade-level',lastGradeLevel);
            //DISABLE LAST YEAR ATTENDED
            lastYear.disabled = true;
            lastYear.style.opacity = 0.5;
            lastYear.value = '';
            ValidationUtils.clearError('em-last-year-finished',lastYear);
            lastYear.removeEventListener('input', validateYearFinished);
        }
        else {
            lastGradeLevel.disabled = false;
            lastGradeLevel.style.opacity = 1;
            //DISABLE LAST YEAR ATTENDED
            lastYear.disabled = false;
            lastYear.style.opacity = 1;
            lastYear.value = saveYear;
            if(saveYear && saveYear.trim() !== '') {
                lastYear.dispatchEvent(new Event('input'));
            }
        }
    }
    function validateEnrollingLevel() { //OK
        const enrollingLevelSelected = enrollingGradeLevel.selectedIndex
        if( enrollingLevelSelected === 0) {
            return ValidationUtils.errorMessages('em-enrolling-grade-level', 'Please select an enrolling grade level', enrollingGradeLevel);
        }
        ValidationUtils.clearError('em-enrolling-grade-level',enrollingGradeLevel);
        return true;
    }
    function validateLastGradeLevel() { //OK
        if (lastGradeLevel.disabled) return true;
        const lastGradeLevelSelected = lastGradeLevel.selectedIndex;
        if(lastGradeLevelSelected === lastGradeLevel.options.length - 1) {
            return ValidationUtils.errorMessages("em-last-grade-level", 'This is already the last grade level possible', lastGradeLevel);
        }
        ValidationUtils.clearError('em-last-grade-level', lastGradeLevel);
        return true;
    }
    function validateEnrollingAndLastGradeLevel() { // OK
        if(!validateLastGradeLevel()) {
            return false;
        }
        const lastGradeLevelSelected = lastGradeLevel.selectedIndex;
        const enrollingGradeLevelSelected = enrollingGradeLevel.selectedIndex;
        if(lastGradeLevelSelected > enrollingGradeLevelSelected) {
            return ValidationUtils.errorMessages("em-last-grade-level", 'Last grade level cannot be greater than enrolling grade level', lastGradeLevel);
        }
        ValidationUtils.clearError('em-last-grade-level',lastGradeLevel);
        return true;
    }
    function validateStartYear() {
        let startYearVal = parseInt(startYear.value);
        let endYearVal = parseInt(endYear.value);
        if(ValidationUtils.isEmpty(startYear)) {
            return ValidationUtils.errorMessages("em-start-year", ValidationUtils.emptyError, startYear);
        }
        if (!yearRegex.test(startYear.value)) {
            return ValidationUtils.errorMessages("em-start-year", "Enter a valid year", startYear);
        }
        if (startYearVal == endYearVal) {
            return ValidationUtils.errorMessages("em-start-year", "Academic year cannot be equal", startYear);
        }
        if(startYearVal < year) {
            return ValidationUtils.errorMessages("em-start-year", "Year is lower than the current year", startYear);
        }
        if(startYearVal > endYearVal) {
            return ValidationUtils.errorMessages("em-start-year", "Starting year cannot be greater than the end year", startYear);
        }
        ValidationUtils.clearError("em-start-year", startYear);
        return true;
    }
    function validateAcademicYear(){
        const endYearVal = parseInt(endYear.value);
        const startYearVal = parseInt(startYear.value);
        if(!validateStartYear()) {
            return false;
        }
        if (ValidationUtils.isEmpty(endYear)) {
            return ValidationUtils.errorMessages("em-start-year", ValidationUtils.emptyError, endYear);
        }
        else if (!yearRegex.test(endYear.value) ) {
            return ValidationUtils.errorMessages("em-start-year", "Enter a valid year", endYear);
        }
        else if (endYearVal == startYearVal) {
            return ValidationUtils.errorMessages("em-start-year", "Academic year cannot be equal", endYear);
        }     
        else if(endYearVal < startYearVal) {
            return ValidationUtils.errorMessages("em-start-year", "End year cannot be lower than the starting year", endYear);
        }
        else if(endYearVal != startYearVal + 1) {
            return ValidationUtils.errorMessages("em-start-year", "Academic year should be 1 year apart", endYear);
        }
        ValidationUtils.clearError("em-start-year", endYear);
        return true;
    }
    function validateYearFinished(){
        const lastYearVal = parseInt(lastYear.value);
            
        if (ValidationUtils.isEmpty(lastYear)) {
            return ValidationUtils.errorMessages("em-last-year-finished", ValidationUtils.emptyError, lastYear);
        }
        else if (!yearRegex.test(lastYear.value)){
            return ValidationUtils.errorMessages("em-last-year-finished", "Enter a valid year", lastYear);
        }
        else if (lastYearVal > year) {
            return ValidationUtils.errorMessages("em-last-year-finished", "Value cannot be greater than the current year", lastYear);
        }
        else if (lastYearVal < 1950) {
            return ValidationUtils.errorMessages("em-last-year-finished", "Year is too low", lastYear);
        }
        ValidationUtils.clearError("em-last-year-finished", lastYear);
        return true;
    }
    function validateSchoolId(element, errorElement) {
        if (ValidationUtils.isEmpty(element)) {
            return ValidationUtils.errorMessages(errorElement, ValidationUtils.emptyError, element);
        }
        else if(!idRegex.test(element.value)) {
            return ValidationUtils.errorMessages(errorElement, "Not a valid school Id", element);
        }
        else if(!onlyDigits.test(element.value)) {
            return ValidationUtils.errorMessages(errorElement, "Contains non numeric characters", element);
        }
        ValidationUtils.clearError(errorElement, element);
        return true;
    }
    function validateSchool(element, errorElement){
        if (ValidationUtils.isEmpty(element)) { 
            return ValidationUtils.errorMessages(errorElement, ValidationUtils.emptyError, element);
        }
        else if (!charRegex.test(element.value)) {
            return ValidationUtils.errorMessages(errorElement, "Enter 3 or more characters", element);
        }
        ValidationUtils.clearError(errorElement, element);
        return true;
    }
    function validatePreviousSchoolInfo() {
        let isValid = true;
        const fields = [
            {element: lschool, error: "em-lschool"},
            {element: lschoolAddr, error: "em-lschoolAddress"},
            {element: fschool, error: "em-fschool"},
            {element: fschoolAddr, error: "em-fschoolAddress"}
        ];
        fields.forEach(({element, error}) => {
            if (!validateSchool(element, error)) {
                isValid = false;
            }
        });
        const idFields = [
            {element: lschoolId, error: "em-lschoolID"},
            {element: fschoolId, error: "em-fschoolID"}
        ];
        idFields.forEach(({element, error}) => {
            if (!validateSchoolId(element, error)) {
                isValid = false;
            }
        });
        if (!validateStartYear()) isValid = false;
        if (!validateAcademicYear()) isValid = false;
        if (!lastYear.disabled && !validateYearFinished()) isValid = false;
        if(!validateEnrollingAndLastGradeLevel()) isValid = false;
        ValidationUtils.validationState.previousSchool = isValid;
        return isValid;
    }
     // === STUDENT INFO VALIDATION ===
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
    function validatePSA() {
        const value = psaNumber.value.trim();
        if (!value) {
            return ValidationUtils.errorMessages("em-PSA-number", ValidationUtils.emptyError, psaNumber);
        }
        if (!/^\d*$/.test(value)) {
            return ValidationUtils.errorMessages("em-PSA-number", ValidationUtils.notNumber, psaNumber);
        }
        if (!bCertRegex.test(value)) {
            return ValidationUtils.errorMessages("em-PSA-number", 
                value.length > 13 ? "Only 13 digits are allowed" : "Enter a valid birth certificate number", 
                psaNumber
            );
        }
        ValidationUtils.clearError("em-PSA-number", psaNumber);
        return true;
    }
    function validateLRN() {
        const value = lrn.value.trim();
        if (lrn.disabled) {
            ValidationUtils.clearError("em-LRN", lrn);
            return true;
        }
        if (!value) {
            return ValidationUtils.errorMessages("em-LRN", ValidationUtils.emptyError, lrn);
        }
        if (!/^\d*$/.test(value)) {
            return ValidationUtils.errorMessages("em-LRN", ValidationUtils.notNumber, lrn);
        }
        if (!lrnRegex.test(value)) {
            return ValidationUtils.errorMessages("em-LRN", 
                value.length > 12 ? "Only 12 digits are allowed" : "Enter a valid LRN",
                lrn
            );
        }
        ValidationUtils.clearError("em-LRN", lrn);
        return true;
    }
    function validateNativeGroup() {
        if(nativeGroup.disabled) {
            ValidationUtils.clearError('em-community', nativeGroup);
        }
        if(ValidationUtils.isEmpty(nativeGroup)) {
            return ValidationUtils.errorMessages("em-community", ValidationUtils.emptyError, nativeGroup);
        }
        ValidationUtils.clearError('em-community',nativeGroup);
        return true;
    }
    function validateStudentInfo() {
        let isValid = true;
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
        if (!validatePSA()) {
            isValid = false;
        }
        if (!lrn.disabled && !validateLRN()) {
            isValid = false;
        }
        if(!nativeGroup.disabled && !validateNativeGroup()) { 
            isValid = false;
        }
        if (!validateAge(parseInt(age.value)) || !birthDate.value || !getAge()) {
            isValid = false;
        }
        ValidationUtils.validationState.studentInfo = isValid;
        return isValid;
    }
    // === DISABLED STUDENT VALIDATION ===
    function validateDisabilities(element, errorElement) {
        if(ValidationUtils.isEmpty(element)) {
            return ValidationUtils.errorMessages(errorElement, ValidationUtils.emptyError, element);
        }
        ValidationUtils.clearError(errorElement,element);
        return true;
    }
    function validateDisabilityInfo() {
        let isValid = true;
        
        // Check if disability question is answered
        const hasDisabilityRadios = document.querySelectorAll('input[name="has-disability"]');
        const hasDisabilityChecked = Array.from(hasDisabilityRadios).some(radio => radio.checked);
        
        if (!hasDisabilityChecked) {
            isValid = false;
            // Could add error display here if needed
        }
        
        // If they have disability, check subsequent questions
        const hasDisabilityYesRadio = document.getElementById('has-disability-yes');
        if (hasDisabilityYesRadio && hasDisabilityYesRadio.checked) {
            // Check if can-read-write is answered
            const canReadWriteRadios = document.querySelectorAll('input[name="can-read-write"]');
            const canReadWriteChecked = Array.from(canReadWriteRadios).some(radio => radio.checked);
            
            if (!canReadWriteChecked) {
                isValid = false;
            }
            
            // If they can read/write, validate the disability details
            const canReadWriteYesRadio = document.getElementById('can-read-write-yes');
            if (canReadWriteYesRadio && canReadWriteYesRadio.checked) {
                const disabilityDescInput = document.getElementById('disability-description');
                const assistiveTechInput = document.getElementById('assistive-technology');
                
                if (disabilityDescInput && !ValidationUtils.validateEmpty(disabilityDescInput, 'em-disability-desc')) {
                    isValid = false;
                }
                
                if (assistiveTechInput && !ValidationUtils.validateEmpty(assistiveTechInput, 'em-assistive-tech')) {
                    isValid = false;
                }
            }
        }
        
        ValidationUtils.validationState.disabledInfo = isValid;
        return isValid;
    }
    // === ADDRESS VALIDATION ===
    function validateAddressInfo() {
        let isValid = true;
        const addressFields = [
            { element: regions, error: "em-region", label: "Region" },
            { element: provinces, error: "em-province", label: "Province" },
            { element: cityOrMunicipality, error: "em-city", label: "City/Municipality" },
            { element: barangay, error: "em-barangay", label: "Barangay" },
            { element: subdivsion, error: "em-subdivision", label: "Subdivision/Street" },
            { element: houseNumber, error: "em-house-number", label: "House Number" }
        ];
        addressFields.forEach(({ element, error, label }) => {
            if (!element) return;
            if (element.tagName === "SELECT") {
                if (!element.value) {
                    ValidationUtils.errorMessages(error, `Please select a ${label}`, element);
                    isValid = false;
                } else {
                    ValidationUtils.clearError(error, element);
                }
            }
            else if (element.tagName === "INPUT") {
                if (ValidationUtils.isEmpty(element)) {
                    ValidationUtils.errorMessages(error, ValidationUtils.emptyError, element);
                    isValid = false;
                } else {
                    ValidationUtils.clearError(error, element);
                }
                if (element === houseNumber && !ValidationUtils.isEmpty(element)) {
                    if (isNaN(element.value)) {
                        ValidationUtils.errorMessages(error, ValidationUtils.notNumber, element);
                        isValid = false;
                    }
                }
            }
        });
        ValidationUtils.validationState.addressInfo = isValid;
        return isValid;
    }
    function validateAddress(errorElement,addressElement) {
        const selected = addressElement.selectedIndex;
        if(selected === 0) {
            ValidationUtils.errorMessages(errorElement, 'Please select an address',addressElement);
        }
        else {
            ValidationUtils.clearError(errorElement, addressElement);
        }
    }
    // === PARENT INFO VALIDATION ===
    function validatePhoneNumber(element, errorElement) {
        if(ValidationUtils.isEmpty(element)) {
            ValidationUtils.errorMessages(errorElement, ValidationUtils.emptyError, element);
            return false;
        }
        if(element.value.length > 11 || element.value.length < 11 || element.value.charAt(0) !== '0') {
            ValidationUtils.errorMessages(errorElement, "Not a valid phone number", element);
            return false;
        }
        ValidationUtils.clearError(errorElement, element);
        return true;
    }
    function validateParentInfo() {
        let isValid = true;
        const allInfo = [
            {element: guardianLname, error: "em-guardian-last-name"},
            {element: guardianFname, error: "em-guardian-first-name"},
            {element: guardianMname, error: "em-guardian-middle-name"}
        ];
        allInfo.forEach(({element, error}) => {
            if (!ValidationUtils.validateEmpty(element, error)) {
                isValid = false;
            }
        });
        const phoneInfo = [ 
            {element: guardianCPnum, error: "em-g-number"}
        ];
        phoneInfo.forEach(({element, error}) => {
            if (!validatePhoneNumber(element, error)) {
                isValid = false;
            }
        });
        ValidationUtils.validationState.parentInfo = isValid;
        return isValid;
    }
    // |============================|
    // |===== INITIALIZATIONS ======|
    // |============================|
    // === PREVIOUS SCHOOL INFO INIT ===
    // INIT pampublikong paaralan radio button
    const publicSchool = document.getElementById('public');
    if(publicSchool) {
        publicSchool.checked = true;
    }
    const radios = document.querySelectorAll('input[name="bool-LRN"]');
    // INIT lrn radio button to YES
    if(![...radios].some(r=>r.checked)) {
        const defaultVal = [...radios].find(r=>r.value === '1');
        if(defaultVal) {
            defaultVal.checked = true;
            defaultVal.dispatchEvent(new Event('change'));
        }
    }
    // INIT SELECT VALUES
    if (lastGradeLevel && enrollingGradeLevel) {
        let saveOptions = lastGradeLevel.innerHTML;
        if (lastGradeLevel.options.length === 0  || enrollingGradeLevel.options.length === 0) {//fallback values
            const gradeOptions = `
                <option value=""> Select a grade level</option>
                <option value="1">Kinder I</option>
                <option value="2">Kinder II</option>
                <option value="3">Grade 1</option>       
                <option value="4">Grade 2</option>
                <option value="5">Grade 3</option>
                <option value="6">Grade 4</option>
                <option value="7">Grade 5</option>
                <option value="8">Grade 6</option>
            `;
            enrollingGradeLevel.innerHTML = gradeOptions;
            lastGradeLevel.innerHTML = gradeOptions;
        }
        if(enrollingGradeLevel.selectedIndex === 1) {//disable if lastGradeLevel if index equals to 1(Kinder I)
            lastGradeLevel.disabled = true;
            lastGradeLevel.style.opacity = 0.5;
            lastGradeLevel.textContent = '';
        }
        else {
            lastGradeLevel.disabled = false;
            lastGradeLevel.style.opacity = 1;
            lastGradeLevel.innerHTML = saveOptions;
        }
    }
    if (startYear && endYear) {
        startYear.value = year;
        endYear.value = year + 1;
    }
    // === STUDENT INFO INIT ===
    // Set date constraints
    if (birthDate) {
        birthDate.max = formatDate(maxDate);
        birthDate.min = formatDate(minDate);
    }
    //  INIT gender to babae
    const female = document.getElementById('female');
    if(female) {
        female.checked = true;
    }
    //  INIT kabilang sa katutubong grupo to yes
    const isEthnic = document.getElementById('is-ethnic');
    if(isEthnic) {
        isEthnic.checked = true;
    }  
    // === ADDRESS INIT ===
    let regionCode = "";
    let provinceCode = "";
    let cityCode = "";
    (async() =>{
        try {
            const controller = new AbortController();
            const timeOut = setTimeout(() => {
                controller.abort();
                console.error("Request timed out");
                replaceTextBox(regions, "region");
            }, 10000);
            const response = await getRegions();
            clearTimeout(timeOut);
            regions.innerHTML = `<option value=""> Select a Region </option>`;
            generateOptions(response,regions);
        }
        catch(error) {
            console.error("Error fetching regions:", error.message);
            replaceTextBox(regions, "region");
        }
        initialSelectValue(provinces, "Region");
        initialSelectValue(cityOrMunicipality, "Province");
        initialSelectValue(barangay, "City/Municipality");
        
        // Check if city is already a text input (manually entered) and replace barangay accordingly
        setTimeout(() => {
            const cityElement = document.getElementById("city-municipality");
            const barangayElement = document.getElementById("barangay");
            if (cityElement && cityElement.tagName === "INPUT" && cityElement.value.trim() !== "" && 
                barangayElement && barangayElement.tagName === "SELECT") {
                replaceTextBox(barangayElement, "barangay");
            }
        }, 100);
    })();
    async function getProvinceOptions() {
        try {
            regionCode = regions.value;
            if (!regionCode) {
                initialSelectValue(provinces, "Region");
                return;
            }
            const data = await getProvinces(regionCode);
            provinces.innerHTML = `<option value=""> Select a Province</option>`;
            generateOptions(data,provinces);
        }
        catch (error) {
            console.error("Error fetching provinces:", error);
            if (regions.value !== "") {
                replaceTextBox(provinces, "province");
            }
        }
    }
    async function getCityOptions() {
        try {
            provinceCode = provinces.value;
            if (!provinceCode) {
                initialSelectValue(cityOrMunicipality, "Province");
                return;
            }
            const data = await getCities(provinceCode);
            cityOrMunicipality.innerHTML = `<option value=""> Select a City/Municipality</option>`;
            generateOptions(data,cityOrMunicipality);
        }
        catch (error) {
            console.error("Error fetching cities:", error);
            if (provinces.value !== "") {
                replaceTextBox(cityOrMunicipality, "city");
            } 
        }
    }
    async function getBarangayOptions() {
        try {
            cityCode = cityOrMunicipality.value;
            if (!cityCode) {
                initialSelectValue(barangay, "City/Municipality");
                return;
            }
            const data = await getBarangays(cityCode);
            barangay.innerHTML = `<option value=""> Select a Barangay</option>`;
            generateOptions(data,barangay);
        }
        catch(error) {
            console.error("Error fetching barangays:", error);
            if (cityOrMunicipality.value !== "") {
                replaceTextBox(barangay, "barangay");
            }
        }
    }
    // === DISABLED STUDENT INIT ===
    // DISABILITY MODAL - Shows on page load
    const disabilityModal = document.getElementById('disability-modal');
    const disabilityModalContinueBtn = document.getElementById('disability-modal-continue');
    const hasDisabilityYes = document.getElementById('has-disability-yes');
    const hasDisabilityNo = document.getElementById('has-disability-no');
    const canReadWriteSection = document.querySelector('.can-read-write');
    const canReadWriteYes = document.getElementById('can-read-write-yes');
    const canReadWriteNo = document.getElementById('can-read-write-no');
    const disabilityDetailsSection = document.querySelector('.disability-details');
    const disabilityDescInput = document.getElementById('disability-description');
    const assistiveTechInput = document.getElementById('assistive-technology');
    const disabilityPopup = document.getElementById('disability-template-popup');
    const confirmPopupBtn = document.getElementById('confirm-disability-popup');

    // Show modal on page load
    if (disabilityModal) {
        disabilityModal.style.display = 'flex';
    }

    // Track modal completion state
    let canProceedFromModal = false;

    // Function to check if continue button should be enabled
    function checkModalContinueState() {
        let canEnable = false;

        if (hasDisabilityNo && hasDisabilityNo.checked) {
            // No disability - can proceed
            canEnable = true;
        } else if (hasDisabilityYes && hasDisabilityYes.checked) {
            // Has disability - check if can read/write is answered
            if (canReadWriteYes && canReadWriteYes.checked) {
                // Can read/write - check if details are filled
                if (disabilityDescInput && assistiveTechInput) {
                    const hasDesc = disabilityDescInput.value.trim().length > 0;
                    const hasTech = assistiveTechInput.value.trim().length > 0;
                    canEnable = hasDesc && hasTech;
                }
            } else if (canReadWriteNo && canReadWriteNo.checked) {
                // Cannot read/write - popup will handle redirect
                canEnable = false;
            }
        }

        if (disabilityModalContinueBtn) {
            disabilityModalContinueBtn.disabled = !canEnable;
        }
    }

    // Handle disability question
    const hasDisabilityRadios = document.querySelectorAll('input[name="has-disability"]');
    hasDisabilityRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (hasDisabilityYes.checked) {
                // Show can read/write section
                canReadWriteSection.style.display = 'flex';
                // Reset can read/write selection
                canReadWriteYes.checked = false;
                canReadWriteNo.checked = false;
                // Hide details section
                disabilityDetailsSection.style.display = 'none';
            } else {
                // Hide everything if No disability
                canReadWriteSection.style.display = 'none';
                disabilityDetailsSection.style.display = 'none';
                // Reset all fields
                canReadWriteYes.checked = false;
                canReadWriteNo.checked = false;
                if (disabilityDescInput) disabilityDescInput.value = '';
                if (assistiveTechInput) assistiveTechInput.value = '';
            }
            checkModalContinueState();
        });
    });

    // Handle can read/write question
    const canReadWriteRadios = document.querySelectorAll('input[name="can-read-write"]');
    canReadWriteRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (canReadWriteNo.checked) {
                // Show popup
                disabilityPopup.style.display = 'flex';
                // Hide details section
                disabilityDetailsSection.style.display = 'none';
            } else if (canReadWriteYes.checked) {
                // Show details section
                disabilityDetailsSection.style.display = 'flex';
            }
            checkModalContinueState();
        });
    });

    // Popup confirm button - redirect to user_enrollees.php
    if (confirmPopupBtn) {
        confirmPopupBtn.addEventListener('click', function() {
            // Redirect to parent page
            window.location.href = './user_enrollees.php';
        });
    }

    // Close popup when clicking outside
    if (disabilityPopup) {
        disabilityPopup.addEventListener('click', function(e) {
            if (e.target === disabilityPopup) {
                disabilityPopup.style.display = 'none';
                // Reset can read/write selection
                canReadWriteYes.checked = false;
                canReadWriteNo.checked = false;
                checkModalContinueState();
            }
        });
    }

    // Validation for disability description
    if (disabilityDescInput) {
        disabilityDescInput.addEventListener('input', function() {
            validateDisabilities(this, 'em-disability-desc');
            checkModalContinueState();
        });
    }

    // Validation for assistive technology
    if (assistiveTechInput) {
        assistiveTechInput.addEventListener('input', function() {
            validateDisabilities(this, 'em-assistive-tech');
            checkModalContinueState();
        });
    }

    // Continue button - close modal and allow form access
    if (disabilityModalContinueBtn) {
        disabilityModalContinueBtn.addEventListener('click', function() {
            // Populate hidden fields for backend
            const snHidden = document.getElementById('sn-hidden');
            const boolsnHidden = document.getElementById('boolsn-hidden');
            const atHidden = document.getElementById('at-hidden');
            const atdeviceHidden = document.getElementById('atdevice-hidden');
            
            if (hasDisabilityNo.checked) {
                // No disability
                if (snHidden) snHidden.value = '0';
                if (boolsnHidden) boolsnHidden.value = '';
                if (atHidden) atHidden.value = '0';
                if (atdeviceHidden) atdeviceHidden.value = '';
            } else if (hasDisabilityYes.checked && canReadWriteYes.checked) {
                // Has disability and can read/write
                if (snHidden) snHidden.value = '1';
                if (boolsnHidden) boolsnHidden.value = disabilityDescInput ? disabilityDescInput.value : '';
                if (atHidden) atHidden.value = '1';
                if (atdeviceHidden) atdeviceHidden.value = assistiveTechInput ? assistiveTechInput.value : '';
            }
            
            canProceedFromModal = true;
            if (disabilityModal) {
                disabilityModal.style.display = 'none';
            }
        });
    }

    // === OLD DISABLED STUDENT INIT (KEEPING FOR BACKWARDS COMPATIBILITY) ===
    const isDisabled = document.getElementById('is-disabled');
    if(isDisabled) isDisabled.checked = true;
    const hasAssistiveTech = document.getElementById('has-assistive-tech');
    if(hasAssistiveTech) hasAssistiveTech.checked = true; 
    // === PARENT INFO INIT ===
    const not4ps = document.getElementById('not-4ps');
    if(not4ps) {
        not4ps.checked = true;
    } 
    // |===================|
    // |===== EVENTS ======|
    // |===================|
    // === PREVIOUS SCHOOL EVENTS ===
    const schoolFields = [
        {element: lschool, error: "em-lschool"},
        {element: lschoolAddr, error: "em-lschoolAddress"},
        {element: fschool, error: "em-fschool"},
        {element: fschoolAddr, error: "em-fschoolAddress"}
    ];
    schoolFields.forEach(({element, error}) => {
        if (element) {
            element.addEventListener('input', () => validateSchool(element, error));
        }
    });
    const idFields = [
        {element: lschoolId, error: "em-lschoolID"},
        {element: fschoolId, error: "em-fschoolID"}
    ];
    idFields.forEach(({element, error}) => {
        if (element) {
            checkIfNumericInput(element);
            element.addEventListener('input', () => validateSchoolId(element, error));
        }
    });
    const yearFields = [startYear, endYear, lastYear];
    yearFields.forEach(element => {
        if (element) {
            checkIfNumericInput(element);
        }
    });
    if (startYear) startYear.addEventListener('input', validateStartYear);
    if (endYear) endYear.addEventListener('input', validateAcademicYear);
    //VARIABLE FOR SAVING VALUE
    let saveYear = null;
    if (lastYear) {
       lastYear.addEventListener('input',function(){
        if(!this.disabled) {
            saveYear = this.value;
            validateYearFinished();
        }
       }) 
    }
    enrollingGradeLevel.addEventListener('change', function() {
        toggleEnrollingGradeLevelRelatedDisables();
        if(!lastGradeLevel.disabled) syncSelects(this,lastGradeLevel);
        validateEnrollingLevel();
        validateEnrollingAndLastGradeLevel();
    });
    lastGradeLevel.addEventListener('change', function() {
        if(this.disabled) return;
        syncSelects(this,enrollingGradeLevel);
        validateLastGradeLevel();
        validateEnrollingAndLastGradeLevel(); 
    });
    // === STUDENT INFO EVENTS ===
    let saveNativeGroup = '';
    const hasNativeGroup = document.querySelectorAll('input[name="group"].radio');
    hasNativeGroup.forEach(radio=>{
        radio.addEventListener('change',function(){
            if(!isEthnic.checked) {
                saveNativeGroup = nativeGroup.value;
                nativeGroup.disabled = true;
                nativeGroup.style.opacity = 0.5;
                nativeGroup.value = '';
                ValidationUtils.clearError('em-community', nativeGroup);
                nativeGroup.removeEventListener('input',validateNativeGroup);
            }
            else {
                nativeGroup.disabled = false;
                nativeGroup.style.opacity = 1;
                nativeGroup.value = saveNativeGroup || '';
                if (saveNativeGroup && saveNativeGroup.trim() !== '') {
                    nativeGroup.dispatchEvent(new Event('input'));
                };  // run once after enabling
            }
        })
    })
    nativeGroup.addEventListener('input', function(){
        validateNativeGroup();
    });
    if (birthDate) {
        birthDate.addEventListener('change', getAge);
    }
    if (psaNumber) {
        checkIfNumericInput(psaNumber);
        psaNumber.addEventListener('input', validatePSA);
    }
    if (lrn) {
        checkIfNumericInput(lrn);
        lrn.addEventListener('input', validateLRN);
    }
    radios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (radio.value === "0") {
                lrn.disabled = true;
                lrn.style.opacity = "0.2";
                lrn.value ="";
                ValidationUtils.clearError("em-LRN", lrn);
            } else {
                lrn.disabled = false;
                lrn.style.opacity = "1";
                lrn.value = localStorage.getItem('lrn');
            }
        });
    });

    const nameFields = [lname, fname, mname, guardianLname, guardianFname, guardianMname];
    nameFields.forEach(field => {
        if (field) {
            preventCharactersByRegex(field, nonNameRegex, (element, rejectedChars) => {
                console.log(`Rejected characters in ${element.id}: ${rejectedChars}`);
            });
        }
    });

    const numericFields = [psaNumber, lrn, startYear, endYear, lastYear, lschoolId, fschoolId, 
                        guardianCPnum, houseNumber];
    numericFields.forEach(field => {
        if (field) {
            preventCharactersByRegex(field, nonNumericRegex, (element, rejectedChars) => {
                console.log(`Prevented non-numeric characters: ${rejectedChars}`);
            });
        }
    });

    limitCharacters(lrn, numLimitLRN);
    limitCharacters(psaNumber, numLimitPSA);

    const schoolIdFields = [lschoolId, fschoolId];
    schoolIdFields.forEach(field => {
        if (field) {
            limitCharacters(field, numLimitSchoolID);
        }
    });

    const phoneNumbers = [guardianCPnum];
    phoneNumbers.forEach(field => {
        if (field) {
            limitCharacters(field, numLimitPhone);
        }
    });

    // === DISABLITY EVENTS (OLD - COMMENTED OUT FOR NEW SYSTEM) ===
    // Note: Old disability fields (boolsn, atdevice, sn, at) have been replaced
    // with new nested conditional flow (has-disability -> can-read-write -> details)
    /*
    function toggleField(radioChecked, field, savedValue,errorElement) {
        if (!radioChecked) {
            savedValue = field.value;
            field.disabled = true;
            field.style.opacity = 0.2;
            field.value = '';
            ValidationUtils.clearError(errorElement,field);
            field.removeEventListener('input',validateDisabilities);
        } else {
            field.disabled = false;
            field.style.opacity = 1;
            field.value = savedValue;
            if (savedValue && savedValue.trim() !== '') {
                field.dispatchEvent(new Event('input'));
            }
        }
        return savedValue;
    }
    const assistiveTechRadio = document.querySelectorAll('input[name="at"],radio');
    const disabilityRadio = document.querySelectorAll('input[name="sn"].radio');
    let saveDisability = '';
    let saveAssistiveTech = '';
    assistiveTechRadio.forEach(radio=>{
        radio.addEventListener('change',function(){
            saveAssistiveTech = toggleField(hasAssistiveTech.checked,assistiveTech,saveAssistiveTech,'em-atdevice');
        })
    })
    disabilityRadio.forEach(radio=>{
        radio.addEventListener('change',function(){
            saveDisability = toggleField(isDisabled.checked,disability,saveDisability,'em-boolsn');
        })
    })
    disability.addEventListener('input',function(){
        validateDisabilities(this,'em-boolsn');
    })
    assistiveTech.addEventListener('input',function(){
        validateDisabilities(this,'em-atdevice');
    })
    */
    // === ADDRESS EVENTS ===
    if (regions) {
        regions.addEventListener("change", async function() {
            if (isSubmittingForm) return;
            await getProvinceOptions();
            document.getElementById("region-name").value = regions.options[regions.selectedIndex].text;
            if (regionCode == "") {
                initialSelectValue(provinces, "Region");
            }
            validateAddress("em-region",this);
        });
        provinces.addEventListener("change", async function(){
            if (isSubmittingForm) return;
            await getCityOptions();
            document.getElementById("province-name").value = provinces.options[provinces.selectedIndex].text;
            if (provinceCode == "") {
                initialSelectValue(cityOrMunicipality, "Province");
            }
            validateAddress("em-province",this);
        });
        cityOrMunicipality.addEventListener("change", async function() {
            if (isSubmittingForm) return;
            // Check if city is a text input (manually entered) or a select dropdown
            if (this.tagName === "INPUT") {
                // City is manually entered, replace barangay with text input
                document.getElementById("city-municipality-name").value = this.value;
                const barangayElement = document.getElementById("barangay");
                if (barangayElement && barangayElement.tagName === "SELECT") {
                    await replaceTextBox(barangayElement, "barangay");
                }
                validateAddress('em-city', this);
            } else {
                // City is selected from dropdown
                await getBarangayOptions();
                document.getElementById("city-municipality-name").value = cityOrMunicipality.options[cityOrMunicipality.selectedIndex].text;
                if (cityCode == "") {
                    initialSelectValue(barangay, "City/Municipality");
                }
                validateAddress('em-city', this);
            }
        });
        
        // Also handle input event for manually entered city (when it's already a text input)
        cityOrMunicipality.addEventListener("input", async function() {
            if (isSubmittingForm) return;
            if (this.tagName === "INPUT" && this.value.trim() !== "") {
                document.getElementById("city-municipality-name").value = this.value;
                const barangayElement = document.getElementById("barangay");
                if (barangayElement && barangayElement.tagName === "SELECT") {
                    await replaceTextBox(barangayElement, "barangay");
                }
            }
        });
        
        barangay.addEventListener("change", function() {
            if (isSubmittingForm) return;
            if (this.tagName === "SELECT") {
                document.getElementById("barangay-name").value = barangay.options[barangay.selectedIndex].text;
            } else {
                document.getElementById("barangay-name").value = this.value;
            }
            validateAddress("em-barangay", this);
        });
    }
    // === PARENT INFO EVENTS ===
    const parentNameFields = [
        {element: guardianLname, error: "em-guardian-last-name"},
        {element: guardianFname, error: "em-guardian-first-name"}
    ];
    parentNameFields.forEach(({element, error}) => {
        if (element) {
            element.addEventListener('keyup', () => {
                ValidationUtils.validateEmpty(element, error);
            });
        }
    });
    const phoneFields = [
        {element: guardianCPnum, error: "em-g-number"}
    ];
    phoneFields.forEach(({element, error}) => {
        if (element) {
            checkIfNumericInput(element);
            element.addEventListener('input',() => validatePhoneNumber(element, error));
        }
    });
    // === OTHER EVENTS ===
    document.querySelectorAll('input[type="text"]').forEach(input => {
        capitalizeFirstLetter(input);
    });
    if (!localStorage.getItem('lrn')) {
        localStorage.setItem('lrn', 900000000145);
    }
    if (!localStorage.getItem('psa')) {
        localStorage.setItem('psa', 1000000000375);
    }
    lrn.value = localStorage.getItem('lrn');
    psaNumber.value = localStorage.getItem('psa');
    
    // |=====================================|
    // |===== REPORT CARD VALIDATION ========|
    // |=====================================|
    
    /**
     * Validate report card images via backend API
     */
    async function validateReportCard() {
        if (!reportCardFront.files[0] || !reportCardBack.files[0]) {
            return {
                success: false,
                status: 'missing_files',
                message: 'Both report card images are required'
            };
        }
        
        if (isValidatingReportCard) {
            return {
                success: false,
                status: 'validating',
                message: 'Validation already in progress'
            };
        }
        
        isValidatingReportCard = true;
        
        try {
            const validationData = new FormData();
            validationData.append('student_name', `${fname.value} ${mname.value ? mname.value + ' ' : ''}${lname.value}`);
            validationData.append('student_lrn', lrn.value || '000000000000');
            validationData.append('report-card-front', reportCardFront.files[0]);
            validationData.append('report-card-back', reportCardBack.files[0]);
            
            const response = await fetch('../../../BackEnd/api/user/validateReportCard.php', {
                method: 'POST',
                body: validationData
            });
            
            const result = await response.json();
            reportCardValidationStatus = result.data?.status || null;
            
            return {
                success: result.success,
                status: result.data?.status || 'unknown',
                message: result.message || 'Validation complete',
                data: result.data || {},
                flagReason: result.data?.flag_reason || null
            };
        }
        catch (error) {
            console.error('Report card validation error:', error);
            return {
                success: false,
                status: 'error',
                message: 'Network error during validation'
            };
        }
        finally {
            isValidatingReportCard = false;
        }
    }
    
    /**
     * Handle report card file changes - reset validation status
     */
    function handleReportCardChange() {
        reportCardValidationStatus = null;
        
        if (reportCardFront.files[0] && reportCardBack.files[0]) {
            console.log('Both report card images selected'); 
        }
    }
    
    // Add file change listeners
    if (reportCardFront) {
        reportCardFront.addEventListener('change', handleReportCardChange);
    }
    if (reportCardBack) {
        reportCardBack.addEventListener('change', handleReportCardChange);
    }
    // |============================|
    // |===== FORM SUBMISSION ======|
    // |============================|
    let isSubmitting = false;
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        if(isSubmitting) return;
        
        isSubmittingForm = true;
        
        const validateAllFields = () => {
            const allInputs = form.querySelectorAll(':is(input:not([type="radio"]), select, textarea):not(:disabled):not([data-optional="true"])');
            console.log(allInputs);
            allInputs.forEach(input => {
                const errorContainer = input.parentElement.querySelector('.error-msg') || 
                                    input.closest('div').querySelector('.error-msg') || 
                                    input.parentElement.parentElement.querySelector('.error-msg');
                if (errorContainer) {
                    const errorClass = errorContainer.querySelector('span')?.className;
                    if (errorClass) {
                        ValidationUtils.clearError(errorClass, input);
                    }
                }
            });
            const events = ['blur', 'change', 'input'];
            allInputs.forEach(input => {
                if (!input.disabled) {
                    events.forEach(eventType => {
                        input.dispatchEvent(new Event(eventType, { bubbles: true }));
                    });
                }
            });
            const studentInfoValid = validateStudentInfo();
            const parentInfoValid = validateParentInfo();
            const previousSchoolValid = validatePreviousSchoolInfo();
            const disabledInfo = validateDisabilityInfo();
            const addressInfoValid = validateAddressInfo();
            const errorMessages = document.querySelectorAll('.error-msg.show');   
            if (errorMessages.length > 0) {
                const firstError = errorMessages[0];
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });   
                const associatedInput = firstError.closest('div').querySelector('input, select, textarea');
                if (associatedInput) {
                    associatedInput.focus();
                }
                return false;
            }
            return studentInfoValid && parentInfoValid && previousSchoolValid && addressInfoValid && disabledInfo;
        };
        
        const successMessage = document.getElementById('success-message');
        const errorMessage = document.getElementById('error-message');
        
        const areFieldsValid = validateAllFields();
        const isFormValid = ValidationUtils.isFormValid();
        if (!areFieldsValid || !isFormValid) {
            if (errorMessage) {
                errorMessage.style.display = 'block';
                errorMessage.innerHTML = !areFieldsValid ? 
                    'Please fill in all required fields correctly.' : 
                    'Please ensure all sections are properly filled out.';
                setTimeout(() => {
                    errorMessage.style.display = 'none';
                }, 5000);
            }
            isSubmittingForm = false;
            return;
        }
        
        // VALIDATE REPORT CARD WITH NOTIFICATION MODAL
        errorMessage.style.display = 'block';
        errorMessage.style.backgroundColor = '#2196F3';
        errorMessage.innerHTML = 'Validating report card... Please wait.';
        
        const reportCardValidation = await validateReportCard();
        errorMessage.style.display = 'none';
        
        // Show notification modal based on validation result
        if (reportCardValidation.status === 'rejected') {
            Notification.show({
                type: 'error',
                title: 'Report Card Rejected',
                message: reportCardValidation.flagReason || reportCardValidation.message || 'Your report card images were rejected. Please re-upload valid images and try again.'
            });
            isSubmittingForm = false;
            return;
        }
        
        if (!reportCardValidation.success) {
            Notification.show({
                type: 'error',
                title: 'Validation Failed',
                message: reportCardValidation.message || 'Failed to validate report card. Please check your images and try again.'
            });
            isSubmittingForm = false;
            return;
        }
        
        if (reportCardValidation.status === 'flagged_for_review') {
            Notification.show({
                type: 'success',
                title: 'Report Card Accepted',
                message: 'Your report card has been accepted and will be manually reviewed. Submitting enrollment form...'
            });
        } else {
            Notification.show({
                type: 'success',
                title: 'Report Card Verified',
                message: 'Your report card has been verified successfully. Submitting enrollment form...'
            });
        }
        
        // Wait briefly for user to see notification before proceeding
        await new Promise(resolve => setTimeout(resolve, 2000));
        
        // Process address values
        try {
            const addressData = await changeAddressValues();
            if (!addressData) {
                throw new Error('Failed to process address data');
            }
        } catch (error) {
            console.error('Error processing address data:', error);
            Notification.show({
                type: 'error',
                title: 'Address Error',
                message: 'Error processing address information. Please try again.'
            });
            isSubmittingForm = false;
            return;
        }
        
        const formData = new FormData(form);
        
        submitButton.disabled = true;
        submitButton.style.backgroundColor = 'gray';
        isSubmitting = true;
        
        const result = await postEnrollmentForm(formData);
        
        if(!result.success) {
            Notification.show({
                type: 'error',
                title: 'Enrollment Failed',
                message: result.message || 'Failed to submit enrollment form. Please try again.'
            });
            isSubmitting = false;
            isSubmittingForm = false;
            submitButton.disabled = false;
            submitButton.style.backgroundColor = "#0FFCF6";
        }
        else {
            Notification.show({
                type: 'success',
                title: 'Enrollment Successful',
                message: result.message || 'Your enrollment has been submitted successfully!'
            });
            let lrnValue = localStorage.getItem('lrn');
            let psaValue = localStorage.getItem('psa');
            const currentLRN = parseInt(lrnValue);
            const currentPSA = parseInt(psaValue);
            localStorage.setItem('lrn', currentLRN + 1);
            localStorage.setItem('psa', currentPSA + 1);
            isSubmittingForm = false;
            setTimeout(() => {
                window.location.href = './user_enrollees.php';
            }, 2500);
        }
    });
});
const TIME_OUT = 60000;
async function postEnrollmentForm(formData) {
    const controller = new AbortController();
    const timeoutId = setTimeout(()=> controller.abort(),TIME_OUT);
    try {
        const response = await fetch(`../../../BackEnd/api/user/postEnrollmentFormData.php`,{
            signal: controller.signal,
            method: 'POST',
            body: formData
        });
        clearTimeout(timeoutId);
        let responseText = await response.text();
        let data;
        try {
            // Try to parse as JSON
            if (!responseText || !responseText.trim()) {
                throw new Error('Empty response from server');
            }
            data = JSON.parse(responseText);
        }
        catch(parseError) {
            console.error('JSON Parse Error:', parseError);
            console.error('Response text:', responseText);
            // Check if response is HTML (likely an error page)
            if (responseText.trim().startsWith('<!DOCTYPE') || responseText.trim().startsWith('<html')) {
                return {
                    success: false,
                    message: 'Server returned an error page. Please check server logs.',
                    data: null
                };
            }
            return {
                success: false,
                message: `Invalid response from server: ${responseText.substring(0, 200)}`,
                data: null
            };
        }
        if(!response.ok) {
            return {
                success: false,
                message: data.message || `HTTP error: ${response.status}`,
                data: null
            }
        };
        return data;
    }
    catch(error) {
        clearTimeout(timeoutId);
        if(error.name === "AbortError") {
            return {
                success: false,
                message: `Response timeout: Server took too long to response. Took ${TIME_OUT / 1000} seconds`,
                data: null
            };
        }
        return {
            success: false,
            message: error.message || `There was an unexpected problem`,
            data: null
        };
    }
}