import {ValidationUtils, capitalizeFirstLetter, generateOptions, getRegions, getProvinces, getCities, getBarangays, preventCharactersByRegex, limitCharacters} from "../utils.js";

document.addEventListener('DOMContentLoaded', function() {
    const addStudentBtn = document.querySelector('.add-student-btn');
    const modal = document.getElementById('admin-enrollment-modal');
    const closeModalBtn = document.querySelector('.close-modal');
    const cancelBtn = document.querySelector('.btn-cancel');
    const form = document.getElementById('admin-enrollment-form');
    const submitButton = form.querySelector('.btn-submit');
    
    // Open modal
    if (addStudentBtn) {
        addStudentBtn.addEventListener('click', function() {
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
            setTimeout(() => initializeForm(), 100);
        });
    }
    
    // Close modal
    function closeEnrollmentModal() {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
        form.reset();
        clearAllErrors();
        // Reset disabled fields
        const adminLRN = document.getElementById('admin-LRN');
        const adminBoolsn = document.getElementById('admin-boolsn');
        const adminAtdevice = document.getElementById('admin-atdevice');
        const adminCommunity = document.getElementById('admin-community');
        const adminLastGrade = document.getElementById('admin-last-grade');
        const adminLastYear = document.getElementById('admin-last-year');
        
        if (adminLRN) {
            adminLRN.disabled = false;
            adminLRN.style.opacity = '1';
        }
        if (adminBoolsn) {
            adminBoolsn.disabled = false;
            adminBoolsn.style.opacity = '1';
        }
        if (adminAtdevice) {
            adminAtdevice.disabled = false;
            adminAtdevice.style.opacity = '1';
        }
        if (adminCommunity) {
            adminCommunity.disabled = false;
            adminCommunity.style.opacity = '1';
        }
        if (adminLastGrade) {
            adminLastGrade.disabled = false;
            adminLastGrade.style.opacity = '1';
        }
        if (adminLastYear) {
            adminLastYear.disabled = false;
            adminLastYear.style.opacity = '1';
        }
    }
    
    if (closeModalBtn) closeModalBtn.addEventListener('click', closeEnrollmentModal);
    if (cancelBtn) cancelBtn.addEventListener('click', closeEnrollmentModal);
    
    // Get all form elements with admin- prefix
    const startYear = document.getElementById("admin-start-year");
    const endYear = document.getElementById("admin-end-year");
    const lastYear = document.getElementById("admin-last-year");
    const lschool = document.getElementById("admin-lschool");
    const lschoolAddr = document.getElementById("admin-lschoolAddress");
    const lschoolId = document.getElementById("admin-lschoolID");
    const fschool = document.getElementById("admin-fschool");
    const fschoolAddr = document.getElementById("admin-fschoolAddress");
    const fschoolId = document.getElementById("admin-fschoolID");
    const enrollingGradeLevel = document.getElementById("admin-grades-tbe");
    const lastGradeLevel = document.getElementById("admin-last-grade");
    
    const lrn = document.getElementById("admin-LRN");
    const lname = document.getElementById("admin-lname");
    const fname = document.getElementById("admin-fname");
    const birthDate = document.getElementById("admin-bday");
    const age = document.getElementById("admin-age");
    const nativeGroup = document.getElementById("admin-community");
    const language = document.getElementById("admin-language");
    const religion = document.getElementById("admin-religion");
    
    const disability = document.getElementById("admin-boolsn");
    const assistiveTech = document.getElementById("admin-atdevice");
    
    const regions = document.getElementById("admin-region");
    const provinces = document.getElementById("admin-province");
    const cityOrMunicipality = document.getElementById("admin-city-municipality");
    const barangay = document.getElementById("admin-barangay");
    const subdivision = document.getElementById("admin-subdivision");
    const houseNumber = document.getElementById("admin-house-number");
    
    const fatherLname = document.getElementById("admin-Father-Last-Name");
    const fatherFname = document.getElementById("admin-Father-First-Name");
    const motherLname = document.getElementById("admin-Mother-Last-Name");
    const motherFname = document.getElementById("admin-Mother-First-Name");
    const guardianLname = document.getElementById("admin-Guardian-Last-Name");
    const guardianFname = document.getElementById("admin-Guardian-First-Name");
    const fatherCPnum = document.getElementById("admin-F-number");
    const motherCPnum = document.getElementById("admin-M-number");
    const guardianCPnum = document.getElementById("admin-G-number");
    
    // Constants
    const today = new Date();
    const year = today.getFullYear();
    const minDate = new Date();
    minDate.setFullYear(today.getFullYear() - 25);
    const maxDate = new Date();
    maxDate.setFullYear(today.getFullYear() - 3);
    
    // Regex patterns
    const lrnRegex = /^([0-9]){12}$/;
    const yearRegex = /^(1[0-9]{3}|2[0-9]{3}|3[0-9]{3})$/;
    const idRegex = /^([0-9]){6}$/;
    const nonAlphaRegex = /[^A-Za-z\s]/g;
    const nonNumericRegex = /[^0-9]/g;
    
    const numLimitLRN = 12;
    const numLimitSchoolID = 6;
    const numLimitPhone = 11;
    
    let saveLRN = '';
    let saveDisability = '';
    let saveAssistiveTech = '';
    let saveNativeGroup = '';
    let saveYear = '';
    
    // Initialize form
    function initializeForm() {
        if (startYear && endYear) {
            startYear.value = year;
            endYear.value = year + 1;
        }
        
        if (birthDate) {
            birthDate.max = formatDate(maxDate);
            birthDate.min = formatDate(minDate);
        }
        
        // Initialize LRN radio to "with LRN"
        const withLrnRadio = document.getElementById('admin-with-lrn');
        if (withLrnRadio) {
            withLrnRadio.checked = true;
        }
        
        // Initialize public school radio
        const publicRadio = document.getElementById('admin-public');
        if (publicRadio) {
            publicRadio.checked = true;
        }
        
        // Initialize gender to female
        const femaleRadio = document.getElementById('admin-female');
        if (femaleRadio) {
            femaleRadio.checked = true;
        }
        
        // Initialize ethnic group to yes
        const ethnicRadio = document.getElementById('admin-is-ethnic');
        if (ethnicRadio) {
            ethnicRadio.checked = true;
        }
        
        // Initialize disability to "has disability"
        const disabledRadio = document.getElementById('admin-is-disabled');
        if (disabledRadio) {
            disabledRadio.checked = true;
        }
        
        // Initialize assistive tech to "has tech"
        const hasTechRadio = document.getElementById('admin-has-assistive-tech');
        if (hasTechRadio) {
            hasTechRadio.checked = true;
        }
        
        // Initialize 4PS to "not 4ps"
        const not4psRadio = document.getElementById('admin-not-4ps');
        if (not4psRadio) {
            not4psRadio.checked = true;
        }
        
        loadRegions();
        setupValidations();
        setupEventListeners();
    }
    
    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }
    
    function clearAllErrors() {
        document.querySelectorAll('.error-msg').forEach(el => el.classList.remove('show'));
        document.querySelectorAll('.form-input, .form-select').forEach(el => {
            el.style.border = '';
        });
    }
    
    // Address loading functions
    async function loadRegions() {
        try {
            const response = await getRegions();
            regions.innerHTML = `<option value="">Select a Region</option>`;
            generateOptions(response, regions);
        } catch (error) {
            console.error("Error fetching regions:", error);
        }
        
        provinces.innerHTML = `<option value="">Select a Region first</option>`;
        cityOrMunicipality.innerHTML = `<option value="">Select a Province first</option>`;
        barangay.innerHTML = `<option value="">Select a City/Municipality first</option>`;
    }
    
    async function getProvinceOptions() {
        try {
            const regionCode = regions.value;
            if (!regionCode) {
                provinces.innerHTML = `<option value="">Select a Region first</option>`;
                return;
            }
            const data = await getProvinces(regionCode);
            provinces.innerHTML = `<option value="">Select a Province</option>`;
            generateOptions(data, provinces);
        } catch (error) {
            console.error("Error fetching provinces:", error);
        }
    }
    
    async function getCityOptions() {
        try {
            const provinceCode = provinces.value;
            if (!provinceCode) {
                cityOrMunicipality.innerHTML = `<option value="">Select a Province first</option>`;
                return;
            }
            const data = await getCities(provinceCode);
            cityOrMunicipality.innerHTML = `<option value="">Select a City/Municipality</option>`;
            generateOptions(data, cityOrMunicipality);
        } catch (error) {
            console.error("Error fetching cities:", error);
        }
    }
    
    async function getBarangayOptions() {
        try {
            const cityCode = cityOrMunicipality.value;
            if (!cityCode) {
                barangay.innerHTML = `<option value="">Select a City/Municipality first</option>`;
                return;
            }
            const data = await getBarangays(cityCode);
            barangay.innerHTML = `<option value="">Select a Barangay</option>`;
            generateOptions(data, barangay);
        } catch (error) {
            console.error("Error fetching barangays:", error);
        }
    }
    
    // Validation functions (same logic as user form)
    function validateLRN() {
        const value = lrn.value.trim();
        if (lrn.disabled) {
            ValidationUtils.clearError("em-LRN", lrn);
            return true;
        }
        if (!value) return ValidationUtils.errorMessages("em-LRN", ValidationUtils.emptyError, lrn);
        if (!/^\d*$/.test(value)) return ValidationUtils.errorMessages("em-LRN", ValidationUtils.notNumber, lrn);
        if (!lrnRegex.test(value)) {
            return ValidationUtils.errorMessages("em-LRN", 
                value.length > 12 ? "Only 12 digits are allowed" : "Enter a valid LRN", lrn);
        }
        ValidationUtils.clearError("em-LRN", lrn);
        return true;
    }
    
    function getAge() {
        let currentYear = today.getFullYear();
        let currentMonth = today.getMonth() + 1;
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
        let birthMonth = getDate.getMonth() + 1;
        let birthDay = getDate.getDate();
        let ageValue = currentYear - birthYear;
        
        if (currentMonth < birthMonth || (currentMonth === birthMonth && currentDay < birthDay)) {
            ageValue--;
        }
        
        if (ageValue < 3 || ageValue > 25) {
            ValidationUtils.errorMessages("em-age", "Student must be between 3 and 25 years old", age);
            age.value = "";
            return false;
        }
        
        age.value = ageValue;
        ValidationUtils.clearError("em-bday", birthDate);
        ValidationUtils.clearError("em-age", age);
        return true;
    }
    
    function validatePhoneNumber(element, errorElement) {
        if (ValidationUtils.isEmpty(element)) {
            ValidationUtils.errorMessages(errorElement, ValidationUtils.emptyError, element);
            return false;
        }
        if (element.value.length !== 11 || element.value.charAt(0) !== '0') {
            ValidationUtils.errorMessages(errorElement, "Not a valid phone number", element);
            return false;
        }
        ValidationUtils.clearError(errorElement, element);
        return true;
    }
    
    function syncSelects(currentSelect, otherSelect) {
        const selectedIndex = currentSelect.selectedIndex;
        if (currentSelect.id === 'admin-grades-tbe') {
            const prevIndex = selectedIndex - 1;
            if (selectedIndex === 0) {
                otherSelect.selectedIndex = 0;
            } else if (prevIndex >= 0) {
                otherSelect.selectedIndex = prevIndex;
            }
        } else {
            if (selectedIndex === 0) {
                otherSelect.selectedIndex = 0;
            } else {
                const nextIndex = selectedIndex + 1;
                if (nextIndex < otherSelect.options.length) {
                    otherSelect.selectedIndex = nextIndex;
                }
            }
        }
    }
    
    function toggleEnrollingGradeLevelRelatedDisables() {
        const selectedIndex = enrollingGradeLevel.selectedIndex;
        if (selectedIndex === 1) {
            lastGradeLevel.disabled = true;
            lastGradeLevel.style.opacity = '0.5';
            lastGradeLevel.value = '';
            ValidationUtils.clearError('em-last-grade-level', lastGradeLevel);
            
            lastYear.disabled = true;
            lastYear.style.opacity = '0.5';
            lastYear.value = '';
            ValidationUtils.clearError('em-last-year-finished', lastYear);
        } else {
            lastGradeLevel.disabled = false;
            lastGradeLevel.style.opacity = '1';
            
            lastYear.disabled = false;
            lastYear.style.opacity = '1';
            if (saveYear) {
                lastYear.value = saveYear;
            }
        }
    }
    
    // Setup validations
    function setupValidations() {
        const nameFields = [lname, fname, fatherLname, fatherFname, motherLname, motherFname, guardianLname, guardianFname];
        nameFields.forEach(field => {
            if (field) {
                preventCharactersByRegex(field, nonAlphaRegex);
                capitalizeFirstLetter(field);
            }
        });
        
        const numericFields = [lrn, startYear, endYear, lastYear, lschoolId, fschoolId, 
                              fatherCPnum, motherCPnum, guardianCPnum, houseNumber];
        numericFields.forEach(field => {
            if (field) preventCharactersByRegex(field, nonNumericRegex);
        });
        
        limitCharacters(lrn, numLimitLRN);
        limitCharacters(lschoolId, numLimitSchoolID);
        limitCharacters(fschoolId, numLimitSchoolID);
        limitCharacters(fatherCPnum, numLimitPhone);
        limitCharacters(motherCPnum, numLimitPhone);
        limitCharacters(guardianCPnum, numLimitPhone);
    }
    
    // Setup event listeners
    function setupEventListeners() {
        // Birth date and age
        if (birthDate) birthDate.addEventListener('change', getAge);
        if (lrn) lrn.addEventListener('input', validateLRN);
        
        // Phone validation
        const phoneFields = [
            {element: fatherCPnum, error: "em-f-number"},
            {element: motherCPnum, error: "em-m-number"},   
            {element: guardianCPnum, error: "em-g-number"}
        ];
        phoneFields.forEach(({element, error}) => {
            if (element) element.addEventListener('input', () => validatePhoneNumber(element, error));
        });
        
        // LRN radio buttons
        const lrnRadios = document.querySelectorAll('input[name="bool-LRN"]');
        lrnRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === "0") {
                    saveLRN = lrn.value;
                    lrn.disabled = true;
                    lrn.style.opacity = "0.5";
                    lrn.value = "";
                    ValidationUtils.clearError("em-LRN", lrn);
                } else {
                    lrn.disabled = false;
                    lrn.style.opacity = "1";
                    lrn.value = saveLRN;
                }
            });
        });
        
        // Ethnic group radio buttons
        const ethnicRadios = document.querySelectorAll('input[name="group"]');
        ethnicRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                const isEthnic = document.getElementById('admin-is-ethnic');
                if (!isEthnic.checked) {
                    saveNativeGroup = nativeGroup.value;
                    nativeGroup.disabled = true;
                    nativeGroup.style.opacity = '0.5';
                    nativeGroup.value = '';
                    ValidationUtils.clearError('em-community', nativeGroup);
                } else {
                    nativeGroup.disabled = false;
                    nativeGroup.style.opacity = '1';
                    nativeGroup.value = saveNativeGroup;
                }
            });
        });
        
        // Disability radio buttons
        const disabilityRadios = document.querySelectorAll('input[name="sn"]');
        disabilityRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                const isDisabled = document.getElementById('admin-is-disabled');
                if (!isDisabled.checked) {
                    saveDisability = disability.value;
                    disability.disabled = true;
                    disability.style.opacity = '0.5';
                    disability.value = '';
                    ValidationUtils.clearError('em-boolsn', disability);
                } else {
                    disability.disabled = false;
                    disability.style.opacity = '1';
                    disability.value = saveDisability;
                }
            });
        });
        
        // Assistive tech radio buttons
        const assistiveTechRadios = document.querySelectorAll('input[name="at"]');
        assistiveTechRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                const hasTech = document.getElementById('admin-has-assistive-tech');
                if (!hasTech.checked) {
                    saveAssistiveTech = assistiveTech.value;
                    assistiveTech.disabled = true;
                    assistiveTech.style.opacity = '0.5';
                    assistiveTech.value = '';
                    ValidationUtils.clearError('em-atdevice', assistiveTech);
                } else {
                    assistiveTech.disabled = false;
                    assistiveTech.style.opacity = '1';
                    assistiveTech.value = saveAssistiveTech;
                }
            });
        });
        
        // Grade level changes
        if (enrollingGradeLevel) {
            enrollingGradeLevel.addEventListener('change', function() {
                toggleEnrollingGradeLevelRelatedDisables();
                if (!lastGradeLevel.disabled) {
                    syncSelects(this, lastGradeLevel);
                }
            });
        }
        
        if (lastGradeLevel) {
            lastGradeLevel.addEventListener('change', function() {
                if (!this.disabled) {
                    syncSelects(this, enrollingGradeLevel);
                }
            });
        }
        
        // Last year input
        if (lastYear) {
            lastYear.addEventListener('input', function() {
                if (!this.disabled) {
                    saveYear = this.value;
                }
            });
        }
        
        // Address events
        if (regions) {
            regions.addEventListener("change", async function() {
                await getProvinceOptions();
                document.getElementById("admin-region-name").value = regions.options[regions.selectedIndex]?.text || '';
            });
        }
        
        if (provinces) {
            provinces.addEventListener("change", async function() {
                await getCityOptions();
                document.getElementById("admin-province-name").value = provinces.options[provinces.selectedIndex]?.text || '';
            });
        }
        
        if (cityOrMunicipality) {
            cityOrMunicipality.addEventListener("change", async function() {
                await getBarangayOptions();
                document.getElementById("admin-city-municipality-name").value = cityOrMunicipality.options[cityOrMunicipality.selectedIndex]?.text || '';
            });
        }
        
        if (barangay) {
            barangay.addEventListener("change", function() {
                document.getElementById("admin-barangay-name").value = barangay.options[barangay.selectedIndex]?.text || '';
            });
        }
    }
    
    // Form submission
    let isSubmitting = false;
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        if (isSubmitting) return;
        
        const formData = new FormData(form);
        isSubmitting = true;
        submitButton.disabled = true;
        submitButton.textContent = 'Enrolling...';
        
        try {
            // Use absolute path from web root for nginx compatibility
            const apiUrl = '/BackEnd/api/admin/postAdminEnrollmentFormData.php';
            
            console.log('Submitting to:', apiUrl);
            console.log('Full URL:', window.location.origin + apiUrl);
            console.log('Form data entries:', Array.from(formData.entries()));
            
            const response = await fetch(apiUrl, {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            });
            
            console.log('Response status:', response.status);
            console.log('Response headers:', Object.fromEntries(response.headers.entries()));
            
            const responseText = await response.text();
            console.log('Response text (first 500 chars):', responseText.substring(0, 500));
            
            let result;
            try {
                result = JSON.parse(responseText);
            } catch (parseError) {
                console.error('JSON parse error:', parseError);
                console.error('Full response:', responseText);
                
                if (responseText.includes('<!DOCTYPE') || responseText.includes('<html')) {
                    const debugWindow = window.open('', 'Debug', 'width=800,height=600');
                    if (debugWindow) {
                        debugWindow.document.write(responseText);
                    }
                }
                
                throw new Error('Server returned invalid JSON. Check console for details.');
            }
            
            console.log('Parsed result:', result);
            
            if (result.success) {
                showToast('Student enrolled successfully!', 'success');
                setTimeout(() => {
                    closeEnrollmentModal();
                    window.location.reload();
                }, 2000);
            } else {
                showToast(result.message || 'Failed to enroll student', 'error');
            }
        } catch (error) {
            console.error('Submission error:', error);
            showToast('Error: ' + error.message, 'error');
        } finally {
            isSubmitting = false;
            submitButton.disabled = false;
            submitButton.textContent = 'Enroll Student';
        }
    });
    
    function showToast(message, type) {
        const toast = document.getElementById('toast-message');
        toast.textContent = message;
        toast.className = `toast-message ${type}`;
        toast.style.display = 'block';
        
        setTimeout(() => {
            toast.style.display = 'none';
        }, 5000);
    }
});
