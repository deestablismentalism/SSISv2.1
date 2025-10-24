// Unified Enrollment Form Validation Handler
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('enrollment-form');
    
    // ===== VALIDATION UTILITIES =====
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
        
        validationState: {
            studentInfo: true,
            parentInfo: true,
            addressInfo: true,
            previousSchool: true
        },

        isFormValid() {
            return Object.values(this.validationState).every(state => state === true);
        }
    };

    // ===== ELEMENT SELECTORS =====
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

    const fatherLname = document.getElementById("Father-Last-Name");
    const fatherFname = document.getElementById("Father-First-Name");
    const motherLname = document.getElementById("Mother-Last-Name");
    const motherFname = document.getElementById("Mother-First-Name");
    const guardianLname = document.getElementById("Guardian-Last-Name");
    const guardianFname = document.getElementById("Guardian-First-Name");
    const fatherCPnum = document.getElementById("F-number");
    const motherCPnum = document.getElementById("M-number");
    const guardianCPnum = document.getElementById("G-number");

    const regions = document.getElementById("region");
    const provinces = document.getElementById("province");
    const cityOrMunicipality = document.getElementById("city-municipality");
    const barangay = document.getElementById("barangay");
    const subdivsion = document.getElementById("subdivision");
    const houseNumber = document.getElementById("house-number");

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

    // ===== CONSTANTS =====
    const today = new Date();
    const year = today.getFullYear();
    const minDate = new Date();
    minDate.setFullYear(today.getFullYear() - 25);
    const maxDate = new Date();
    maxDate.setFullYear(today.getFullYear() - 3);

    const lrnRegex = /^([0-9]){12}$/;
    const bCertRegex = /^([0-9]){13}$/;
    const yearRegex = /^(1[0-9]{3}|2[0-9]{3}|3[0-9]{3})$/;
    const idRegex = /^([0-9]){6}$/;
    const charRegex = /^[A-Za-z0-9\s.,'-]{3,100}$/;
    const nameRegex = /^[A-Za-z\s.\-`'ñÑ]+$/;
    const numericOnlyRegex = /^[0-9]+$/;
    const phoneRegex = /^[0-9]{11}$/;

    // ===== HELPER FUNCTIONS =====
    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    function sanitizeNumericInput(element, maxLength) {
        element.addEventListener('input', function(e) {
            const value = e.target.value;
            if (/\D/.test(value)) {
                const cursorPos = e.target.selectionStart;
                const sanitizedValue = value.replace(/\D/g, '');
                e.target.value = sanitizedValue.slice(0, maxLength);
                const posDiff = value.length - sanitizedValue.length;
                e.target.setSelectionRange(cursorPos - posDiff, cursorPos - posDiff);
            } else if (value.length > maxLength) {
                e.target.value = value.slice(0, maxLength);
                e.target.setSelectionRange(maxLength, maxLength);
            }
        });
        
        // Prevent non-numeric keys and enforce max length on keydown
        element.addEventListener('keydown', function(e) {
            const currentLength = e.target.value.length;
            const allowedKeys = ['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab', 'Home', 'End'];
            
            // Allow Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
            if (e.ctrlKey && ['a', 'c', 'v', 'x'].includes(e.key.toLowerCase())) {
                return;
            }
            
            // Block if max length reached and not a control key
            if (currentLength >= maxLength && !allowedKeys.includes(e.key)) {
                e.preventDefault();
                return;
            }
            
            // Block non-numeric keys
            if (!allowedKeys.includes(e.key) && (e.key < '0' || e.key > '9')) {
                e.preventDefault();
            }
        });
        
        // Handle paste events
        element.addEventListener('paste', function(e) {
            e.preventDefault();
            const pasteData = e.clipboardData.getData('text');
            const numericOnly = pasteData.replace(/\D/g, '').slice(0, maxLength);
            
            const currentValue = e.target.value;
            const start = e.target.selectionStart;
            const end = e.target.selectionEnd;
            
            const newValue = currentValue.substring(0, start) + numericOnly + currentValue.substring(end);
            e.target.value = newValue.slice(0, maxLength);
            
            const newCursorPos = Math.min(start + numericOnly.length, maxLength);
            e.target.setSelectionRange(newCursorPos, newCursorPos);
        });
    }

    function sanitizePhoneInput(element) {
        element.addEventListener('input', function(e) {
            const value = e.target.value;
            const cursorPos = e.target.selectionStart;
            
            // Remove any non-numeric characters
            const sanitizedValue = value.replace(/\D/g, '');
            
            if (sanitizedValue.length > 11) {
                e.target.value = sanitizedValue.slice(0, 11);
                e.target.setSelectionRange(11, 11);
            } else if (value !== sanitizedValue) {
                e.target.value = sanitizedValue;
                const newPos = Math.max(0, cursorPos - (value.length - sanitizedValue.length));
                e.target.setSelectionRange(newPos, newPos);
            }
        });
        
        // Prevent non-numeric keys
        element.addEventListener('keydown', function(e) {
            const currentLength = e.target.value.length;
            const allowedKeys = ['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab', 'Home', 'End'];
            
            // Allow Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
            if (e.ctrlKey && ['a', 'c', 'v', 'x'].includes(e.key.toLowerCase())) {
                return;
            }
            
            // Block if max length (11) reached
            if (currentLength >= 11 && !allowedKeys.includes(e.key)) {
                e.preventDefault();
                return;
            }
            
            // Block non-numeric keys
            if (!allowedKeys.includes(e.key) && (e.key < '0' || e.key > '9')) {
                e.preventDefault();
            }
        });
        
        // Handle paste events
        element.addEventListener('paste', function(e) {
            e.preventDefault();
            const pasteData = e.clipboardData.getData('text');
            const numericOnly = pasteData.replace(/\D/g, '').slice(0, 11);
            
            const currentValue = e.target.value;
            const start = e.target.selectionStart;
            const end = e.target.selectionEnd;
            
            const newValue = currentValue.substring(0, start) + numericOnly + currentValue.substring(end);
            e.target.value = newValue.slice(0, 11);
            
            const newCursorPos = Math.min(start + numericOnly.length, 11);
            e.target.setSelectionRange(newCursorPos, newCursorPos);
        });
    }

    function sanitizeNameInput(element) {
        element.addEventListener('input', function(e) {
            const value = e.target.value;
            const cursorPos = e.target.selectionStart;
            
            // Remove any characters that are not letters, spaces, periods, hyphens, or apostrophes
            const sanitizedValue = value.replace(/[^A-Za-zñÑ\s.\-'']/g, '');
            
            if (value !== sanitizedValue) {
                e.target.value = sanitizedValue;
                e.target.setSelectionRange(cursorPos - 1, cursorPos - 1);
            }
        });
    }

    function capitalizeFirstLetter(element) {
        element.addEventListener('input', function(e) {
            const value = e.target.value;
            const cursorPos = e.target.selectionStart;
            if(value.length > 0) {
                const firstChar = value.charAt(0);
                if (firstChar === firstChar.toLowerCase() && firstChar !== firstChar.toUpperCase()) {
                    e.target.value = firstChar.toUpperCase() + value.slice(1);
                    e.target.setSelectionRange(cursorPos, cursorPos);
                }
            }
        });
    }

    function validateName(element, errorElement) {
        const value = element.value.trim();
        
        if (!value) {
            return ValidationUtils.errorMessages(errorElement, ValidationUtils.emptyError, element);
        }
        
        if (!nameRegex.test(value)) {
            return ValidationUtils.errorMessages(errorElement, "Name can only contain letters, spaces, periods, hyphens and apostrophes", element);
        }
        
        if (value.length < 2) {
            return ValidationUtils.errorMessages(errorElement, "Name must be at least 2 characters long", element);
        }
        
        ValidationUtils.clearError(errorElement, element);
        return true;
    }

    function validatePhoneNumber(element, errorElement) {
        const value = element.value.trim();
        
        if (!value) {
            return ValidationUtils.errorMessages(errorElement, ValidationUtils.emptyError, element);
        }
        
        if (!numericOnlyRegex.test(value)) {
            return ValidationUtils.errorMessages(errorElement, "Phone number must contain only numbers", element);
        }
        
        if (!phoneRegex.test(value)) {
            if (value.length < 11) {
                return ValidationUtils.errorMessages(errorElement, "Phone number must be 11 digits", element);
            } else {
                return ValidationUtils.errorMessages(errorElement, "Phone number must be exactly 11 digits", element);
            }
        }
        
        ValidationUtils.clearError(errorElement, element);
        return true;
    }

    // ===== STUDENT INFO VALIDATION =====
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

        if (!validateAge(parseInt(age.value)) || !birthDate.value || !getAge()) {
            isValid = false;
        }

        ValidationUtils.validationState.studentInfo = isValid;
        return isValid;
    }

    // ===== PARENT INFO VALIDATION =====
    function validateParentInfo() {
        let isValid = true;

        const allInfo = [
            {element: fatherLname, error: "em-father-last-name"},
            {element: fatherFname, error: "em-father-first-name"},
            {element: motherLname, error: "em-mother-last-name"},
            {element: motherFname, error: "em-mother-first-name"},
            {element: guardianLname, error: "em-guardian-last-name"},
            {element: guardianFname, error: "em-guardian-first-name"}
        ];

        allInfo.forEach(({element, error}) => {
            if (!ValidationUtils.validateEmpty(element, error)) {
                isValid = false;
            }
        });

        const phoneInfo = [
            {element: fatherCPnum, error: "em-f-number"},
            {element: motherCPnum, error: "em-m-number"},   
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

    // ===== ADDRESS VALIDATION =====
    let regionCode = "";
    let provinceCode = "";
    let cityCode = "";

    function initialSelectValue(selectElement, parentElement) {
        selectElement.innerHTML = `<option value=""> Select a ${parentElement} first </option>`;
    }

    async function getRegions() {
        try {
            const controller = new AbortController();
            const signal = controller.signal;
            const timeOut = setTimeout(() => {
                controller.abort();
                console.error("Request timed out");
                replaceTextBox(regions, "region");
            }, 10000);
            const response = await fetch("https://psgc.gitlab.io/api/regions", {signal});
                            
            if (!response.ok) {
                throw new Error(`HTTP error! ${response.status}`);
            }
            clearTimeout(timeOut);
            const data = await response.json();    
            if (!data || !Array.isArray(data) || data.length === 0) {
                throw new Error('No regions data available');
            }
            regions.innerHTML = `<option value=""> Select a Region</option>`;
            data.forEach(region=>{
                let option = document.createElement("option");
                option.value = region.code;
                option.textContent = region.name;
                regions.appendChild(option);
            });
        }
        catch (error) {
            console.error("Error fetching regions:", error);
            replaceTextBox(regions, "region");
        }
    }

    async function getProvinces() {
        try {
            regionCode = regions.value;
            if (!regionCode) {
                initialSelectValue(provinces, "Region");
                return;
            }
            const response = await fetch(`https://psgc.gitlab.io/api/regions/${regionCode}/provinces`);     
            if (!response.ok) {
                throw new Error(`HTTP error! ${response.status}`);
            }
            const data = await response.json();
            if (!data || !Array.isArray(data) || data.length === 0) {
                throw new Error('No provinces data available');
            }
            
            provinces.innerHTML = `<option value=""> Select a Province</option>`;
            data.forEach(province=>{
                let option = document.createElement("option");
                option.value = province.code;
                option.textContent = province.name;
                provinces.appendChild(option);
            });
        }
        catch (error) {
            console.error("Error fetching provinces:", error);
            if (regions.value !== "") {
                replaceTextBox(provinces, "province");
            }
        }
    }

    async function getCity() {
        try {
            provinceCode = provinces.value;
            if (!provinceCode) {
                initialSelectValue(cityOrMunicipality, "Province");
                return;
            }
            const response = await fetch(`https://psgc.gitlab.io/api/provinces/${provinceCode}/cities-municipalities`);
            if (!response.ok) {
                throw new Error(`HTTP error! ${response.status}`);
            }
            const data = await response.json();
            if (!data || !Array.isArray(data) || data.length === 0) {
                throw new Error('No cities/municipalities data available');
            }
            cityOrMunicipality.innerHTML = `<option value=""> Select a City/Municipality</option>`;
            data.forEach(city=> {
                let option = document.createElement("option");
                option.value = city.code;
                option.textContent = city.name;
                cityOrMunicipality.appendChild(option);
            });
        }
        catch (error) {
            console.error("Error fetching cities:", error);
            if (provinces.value !== "") {
                replaceTextBox(cityOrMunicipality, "city");
            } 
        }
    }

    async function getBarangay() {
        try {
            cityCode = cityOrMunicipality.value;
            if (!cityCode) {
                initialSelectValue(barangay, "City/Municipality");
                return;
            }
            const response = await fetch(`https://psgc.gitlab.io/api/cities-municipalities/${cityCode}/barangays`);
            if (!response.ok) {
                throw new Error(`HTTP error! ${response.status}`);
            }
            const data = await response.json();
            if (!data || !Array.isArray(data) || data.length === 0) {
                throw new Error('No barangays data available');
            }
            barangay.innerHTML = `<option value=""> Select a Barangay</option>`;
            data.forEach(barangays=> {
                let option = document.createElement("option");
                option.value = barangays.code;
                option.textContent = barangays.name;
                barangay.appendChild(option);
            });
        }
        catch(error) {
            console.error("Error fetching barangays:", error);
            if (cityOrMunicipality.value !== "") {
                replaceTextBox(barangay, "barangay");
            }
        }
    }

    async function replaceTextBox(replaceElement, addressType) {
        let createTBox = document.createElement("input");
        createTBox.type = "text";
        createTBox.id = addressType;
        createTBox.placeholder = `Enter ${addressType} manually`;
        createTBox.className = "textbox";
        replaceElement.replaceWith(createTBox);
    }

    async function changeAddressValues() {
        try {
            const addressData = {
                region: { code: '', text: '' },
                province: { code: '', text: '' },
                city: { code: '', text: '' },
                barangay: { code: '', text: '' }
            };

            if(regions.value && regions.selectedIndex !== -1) {
                addressData.region.code = regions.value;
                addressData.region.text = regions.options[regions.selectedIndex].text;
            }
            
            if(provinces.value && provinces.selectedIndex !== -1) {
                addressData.province.code = provinces.value;
                addressData.province.text = provinces.options[provinces.selectedIndex].text;
            }
            
            if(cityOrMunicipality.value && cityOrMunicipality.selectedIndex !== -1) {
                addressData.city.code = cityOrMunicipality.value;
                addressData.city.text = cityOrMunicipality.options[cityOrMunicipality.selectedIndex].text;
            }
            
            if(barangay.value && barangay.selectedIndex !== -1) {
                addressData.barangay.code = barangay.value;
                addressData.barangay.text = barangay.options[barangay.selectedIndex].text;
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

            return addressData;
        } catch(error) {
            console.error('Error in changeAddressValues:', error);
            return null;
        }
    }

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

    // ===== PREVIOUS SCHOOL VALIDATION =====
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
        if (!validateYearFinished()) isValid = false;

        ValidationUtils.validationState.previousSchool = isValid;
        return isValid;
    }
    if (!localStorage.getItem('lrn')) {
        localStorage.setItem('lrn', 900000000000);
    }

    if (!localStorage.getItem('psa')) {
        localStorage.setItem('psa', 1000000000000);
    }

    //set value for lrn and psa texbox
    lrn.value = localStorage.getItem('lrn');
    psaNumber.value = localStorage.getItem('psa');
    

    // ===== FORM SUBMISSION HANDLER =====
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const validateAllFields = () => {
            const allInputs = form.querySelectorAll('input:not([type="radio"]), select, textarea');
            
            // Clear existing error states
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
            
            // Trigger validation events
            const events = ['blur', 'change', 'input'];
            allInputs.forEach(input => {
                if (!input.disabled) {
                    events.forEach(eventType => {
                        input.dispatchEvent(new Event(eventType, { bubbles: true }));
                    });
                }
            });

            // Call validation functions
            const studentInfoValid = validateStudentInfo();
            const parentInfoValid = validateParentInfo();
            const previousSchoolValid = validatePreviousSchoolInfo();
            const addressInfoValid = validateAddressInfo();

            // Check for visible errors
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
            
            return studentInfoValid && parentInfoValid && previousSchoolValid && addressInfoValid;
        };

        const areFieldsValid = validateAllFields();
        const isFormValid = ValidationUtils.isFormValid();

        if (!areFieldsValid || !isFormValid) {
            const errorMessage = document.getElementById('error-message');
            if (errorMessage) {
                errorMessage.style.display = 'block';
                errorMessage.innerHTML = !areFieldsValid ? 
                    'Please fill in all required fields correctly.' : 
                    'Please ensure all sections are properly filled out.';
                setTimeout(() => {
                    errorMessage.style.display = 'none';
                }, 5000);
            }
            return;
        }

        // Process address values
        try {
            const addressData = await changeAddressValues();
            if (!addressData) {
                throw new Error('Failed to process address data');
            }
        } catch (error) {
            console.error('Error processing address data:', error);
            const errorMessage = document.getElementById('error-message');
            if (errorMessage) {
                errorMessage.style.display = 'block';
                errorMessage.innerHTML = 'Error processing address information. Please try again.';
                setTimeout(() => {
                    errorMessage.style.display = 'none';
                }, 5000);
            }
            return;
        }

        const formData = new FormData(form);

        // Show loading state
        const submitButton = form.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.textContent;
        submitButton.disabled = true;
        submitButton.style.backgroundColor = 'gray';
        submitButton.textContent = 'Submitting...';

        fetch('../../../BackEnd/api/user/postEnrollmentFormData.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if(data.success) {
                const successMessage = document.getElementById('success-message');
                successMessage.style.display = 'block';
                successMessage.innerHTML = data.message || 'Enrollment form submitted successfully!';
                //get psa and lrn value upon submission
                let lrnValue = localStorage.getItem('lrn');
                let psaValue = localStorage.getItem('psa');

                const currentLRN = parseInt(lrnValue);
                const currentPSA = parseInt(psaValue);
                //incerment if submission is successful
                localStorage.setItem('lrn', currentLRN + 1);
                localStorage.setItem('psa', currentPSA + 1);
                setTimeout(() => {
                    window.location.href = './user_enrollees.php';
                }, 2000);
            } else {
                const errorMessage = document.getElementById('error-message');
                errorMessage.style.display = 'block';
                errorMessage.innerHTML = data.message || 'Failed to submit enrollment form. Please try again.';
                setTimeout(() => {
                    errorMessage.style.display = 'none';
                }, 5000);
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            
            const errorMessage = document.getElementById('error-message');
            errorMessage.style.display = 'block';
            errorMessage.textContent = 'An error occurred while submitting the form. Please check all input fields and try again.';
            setTimeout(() => {
                errorMessage.style.display = 'none';
            }, 5000);
        })
        .finally(() => {
            submitButton.disabled = false;
            submitButton.textContent = originalButtonText;
        });
    });

    // ===== INITIALIZATION =====
    
    // Set date constraints
    if (birthDate) {
        birthDate.max = formatDate(maxDate);
        birthDate.min = formatDate(minDate);
    }

    // Set default academic year and make readonly
    if (startYear && endYear) {
        startYear.value = year;
        endYear.value = year + 1;
        startYear.readOnly = true;
        endYear.readOnly = true;
        startYear.style.cursor = 'not-allowed';
        endYear.style.cursor = 'not-allowed';
        startYear.style.backgroundColor = '#e9ecef';
        endYear.style.backgroundColor = '#e9ecef';
    }

    // Initialize grade level dropdowns
    if (lastGradeLevel && enrollingGradeLevel) {
        if (lastGradeLevel.options.length === 0 && enrollingGradeLevel.options.length === 0) {
            const gradeOptions = `
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
        
        if (enrollingGradeLevel.selectedIndex !== -1) {
            enrollingGradeLevel.selectedIndex = lastGradeLevel.selectedIndex + 1;
        }
        lastGradeLevel.options[lastGradeLevel.options.length - 1].disabled = true;

        lastGradeLevel.addEventListener('change', function() {
            const nextIndex = this.selectedIndex + 1;
            if (nextIndex < enrollingGradeLevel.options.length) {
                enrollingGradeLevel.selectedIndex = nextIndex;
            }
            validatePreviousSchoolInfo();
        });
        
        enrollingGradeLevel.addEventListener('change', function() {
            const prevIndex = this.selectedIndex - 1;
            if (prevIndex >= 0) {
                lastGradeLevel.selectedIndex = prevIndex;
            }
            validatePreviousSchoolInfo();
        });
    }

    // Initialize address API
    if (regions) {
        getRegions();
        
        regions.addEventListener("change", async function() {
            await getProvinces();
            document.getElementById("region-name").value = regions.options[regions.selectedIndex].text;
            if (regionCode == "") {
                initialSelectValue(provinces, "Region");
            }
        });
        
        provinces.addEventListener("change", async function(){
            await getCity();
            document.getElementById("province-name").value = provinces.options[provinces.selectedIndex].text;
            if (provinceCode == "") {
                initialSelectValue(cityOrMunicipality, "Province");
            }
        });
        
        cityOrMunicipality.addEventListener("change", async function() {
            await getBarangay();
            document.getElementById("city-municipality-name").value = cityOrMunicipality.options[cityOrMunicipality.selectedIndex].text;
            if (cityCode == "") {
                initialSelectValue(barangay, "City/Municipality");
            }
        });
        
        barangay.addEventListener("change", function() {
            document.getElementById("barangay-name").value = barangay.options[barangay.selectedIndex].text;
        });
    }

    // Initialize existing values
    if (regions && regions.value) {
        getProvinces();
    }
    if (provinces && provinces.value) {
        getCity();
    }
    if (cityOrMunicipality && cityOrMunicipality.value) {
        getBarangay();
    }

    // ===== EVENT LISTENERS =====

    // Student Info Events
   
    if (birthDate) {
        birthDate.addEventListener('change', getAge);
    }

    if (psaNumber) {
        sanitizeNumericInput(psaNumber, 13);
        psaNumber.addEventListener('blur', validatePSA);
        // Set maxlength attribute as fallback
        psaNumber.setAttribute('maxlength', '13');
    }

    if (lrn) {
        sanitizeNumericInput(lrn, 12);
        lrn.addEventListener('blur', validateLRN);
        // Set maxlength attribute as fallback
        lrn.setAttribute('maxlength', '12');
    }

    // LRN radio buttons
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
                lrn.value = localStorage.getItem('lrn');
            }
        });
    });

    // Indigenous/Disability radio groups
    const radioGroups = [
        {textBoxElement: "community", nameValue: "group", error: "em-community"},
        {textBoxElement: "boolsn", nameValue: "sn", error: "em-boolsn"},
        {textBoxElement: "atdevice", nameValue: "at", error: "em-atdevice"}
    ];

    radioGroups.forEach(({textBoxElement, nameValue, error}) => {
        document.querySelectorAll(`input[name="${nameValue}"]`).forEach(radio => {
            radio.addEventListener('change', () => checkIndigenous(textBoxElement, nameValue, error));
        });
    });

    // Apply name sanitization and capitalization to all name fields
    const nameFields = [
        lname, fname,
        fatherLname, fatherFname,
        motherLname, motherFname,
        guardianLname, guardianFname
    ];

    nameFields.forEach(field => {
        if (field) {
            sanitizeNameInput(field);
            capitalizeFirstLetter(field);
        }
    });

    // Apply sanitization to middle name and extension (optional fields)
    const optionalNameFields = [
        document.getElementById('mname'),
        document.getElementById('extension'),
        document.getElementById('Father-Middle-Name'),
        document.getElementById('Mother-Middle-Name'),
        document.getElementById('Guardian-Middle-Name')
    ];

    optionalNameFields.forEach(field => {
        if (field) {
            sanitizeNameInput(field);
            capitalizeFirstLetter(field);
        }
    });

    // Parent Info Events
    const parentNameFields = [
        {element: fatherLname, error: "em-father-last-name"},
        {element: fatherFname, error: "em-father-first-name"},
        {element: motherLname, error: "em-mother-last-name"},
        {element: motherFname, error: "em-mother-first-name"},
        {element: guardianLname, error: "em-guardian-last-name"},
        {element: guardianFname, error: "em-guardian-first-name"}
    ];

    parentNameFields.forEach(({element, error}) => {
        if (element) {
            element.addEventListener('keyup', () => {
                validateName(element, error);
                validateParentInfo();
            });
            element.addEventListener('blur', () => {
                validateName(element, error);
            });
        }
    });

    // Student name validation
    const studentNameFields = [
        {element: lname, error: "em-lname"},
        {element: fname, error: "em-fname"}
    ];

    studentNameFields.forEach(({element, error}) => {
        if (element) {
            element.addEventListener('keyup', () => {
                validateName(element, error);
            });
            element.addEventListener('blur', () => {
                validateName(element, error);
            });
        }
    });

    // Phone number fields - apply sanitization and validation
    const phoneFields = [
        {element: fatherCPnum, error: "em-f-number"},
        {element: motherCPnum, error: "em-m-number"},   
        {element: guardianCPnum, error: "em-g-number"}
    ];

    phoneFields.forEach(({element, error}) => {
        if (element) {
            sanitizePhoneInput(element);
            element.setAttribute('maxlength', '11');
            element.setAttribute('inputmode', 'numeric');
            element.setAttribute('pattern', '[0-9]{11}');
            
            element.addEventListener('blur', function() {
                validatePhoneNumber(element, error);
                validateParentInfo();
            });
            
            element.addEventListener('input', function() {
                if (element.value.length === 11) {
                    validatePhoneNumber(element, error);
                }
            });
        }
    });

    // Address Events
    const addressFields = [regions, provinces, cityOrMunicipality, barangay, subdivsion, houseNumber];
    addressFields.forEach(element => {
        if (element) {
            element.addEventListener('change', () => validateAddressInfo());
            element.addEventListener('input', () => validateAddressInfo());
        }
    });

    // Previous School Events
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

    const yearFields = [startYear, endYear, lastYear];
    yearFields.forEach(element => {
        if (element) {
            sanitizeNumericInput(element, 4);
        }
    });

    const idFields = [
        {element: lschoolId, error: "em-lschoolID"},
        {element: fschoolId, error: "em-fschoolID"}
    ];

    idFields.forEach(({element, error}) => {
        if (element) {
            sanitizeNumericInput(element, 6);
            element.addEventListener('blur', () => validateSchoolId(element, error));
        }
    });

    if (startYear) startYear.addEventListener('input', validateStartYear);
    if (endYear) endYear.addEventListener('input', validateAcademicYear);
    if (lastYear) lastYear.addEventListener('input', validateYearFinished);
});