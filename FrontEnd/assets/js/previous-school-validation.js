document.addEventListener('DOMContentLoaded',function(){
    const form = document.getElementById("enrollment-form"); //form
    const startYear = document.getElementById("start-year"); //taong panuruan start textbox
    const endYear = document.getElementById("end-year"); //taong panuruan end textbox
    const lastYear = document.getElementById("last-year"); //huling natapos na taon textbox
    const lschool = document.getElementById("lschool"); //last school textbox
    const lschoolAddr = document.getElementById("lschoolAddress");//last shcool address
    const lschoolId = document.getElementById("lschoolID"); //last school ID
    const fschool = document.getElementById("fschool"); //nais paaralan
    const fschoolAddr = document.getElementById("fschoolAddress"); //nais paaralan address
    const fschoolId = document.getElementById("fschoolID"); //nais paaralan ID
    const enrollingGradeLevel = document.getElementById("grades-tbe");
    const  lastGradeLevel = document.getElementById("last-grade");    

    const year = new Date().getFullYear();
    const yearRegex = /^(1[0-9]{3}|2[0-9]{3}|3[0-9]{3})$/;
    const idRegex = /^([0-9]){6}$/;
    const charRegex = /^[A-Za-z0-9\s.,'-]{3,100}$/;
    const invalidYear = "Enter a valid year";
    const invalidSchoolId = "Not a valid school Id";
    const invalidChar = "Enter 3 or more characters";

    function replaceSelectValues(replaceElement, replaceId) {
        let createTBox = document.createElement("input");
        createTBox.type = "text";
        createTBox.id = replaceId;
        createTBox.placeholder = `eg. Grade 5`;
        createTBox.className = "textbox";
        replaceElement.replaceWith(createTBox);
    }
    if (lastGradeLevel &&  enrollingGradeLevel ) {
        if (lastGradeLevel.options.length === 0 && enrollingGradeLevel.options.length === 0) {
            enrollingGradeLevel.innerHTML = `
                <option value="1">Kinder I</option>
                <option value="2">Kinder II</option>
                <option value="3">Grade 1</option>       
                <option value="4">Grade 2</option>
                <option value="5">Grade 3</option>
                <option value="6">Grade 4</option>
                <option value="7">Grade 5</option>
                <option value="8">Grade 6</option>
           `;
           lastGradeLevel.innerHTML = `
                <option value="1">Kinder I</option>
                <option value="2">Kinder II</option>
                <option value="3">Grade 1</option>       
                <option value="4">Grade 2</option>
                <option value="5">Grade 3</option>
                <option value="6">Grade 4</option>
                <option value="7">Grade 5</option>
                <option value="8">Grade 6</option>
           `;
        }
        enrollingGradeLevel.selectedIndex = lastGradeLevel.selectedIndex + 1;
        lastGradeLevel.options[lastGradeLevel.options.length - 1].disabled = true;
        
        lastGradeLevel.addEventListener('change' ,function() {
        const nextIndex = this.selectedIndex + 1;
        if (nextIndex < enrollingGradeLevel.options.length) {
                enrollingGradeLevel.selectedIndex = nextIndex;
        }
        validatePreviousSchoolInfo();
        });
        enrollingGradeLevel.addEventListener('change' ,function() {
            const prevIndex = this.selectedIndex - 1;
            if (prevIndex >= 0) {
                lastGradeLevel.selectedIndex = prevIndex;
            }
            else if (enrollingGradeLevel.value = "1") {
                lastGradeLevel.disabled = true;
                lastGradeLevel.value = 0;
                lastGradeLevel.style.opacity = 0.5;
            }
            else {
                lastGradeLevel.disabled = false;
                lastGradeLevel.selectedIndex = enrollingGradeLevel.selectedIndex - 1;
                lastGradeLevel.opacity = 1;
            }
            validatePreviousSchoolInfo();
        });
        
    }
    else {
        replaceSelectValues(enrollingGradeLevel, 'grades-tbe');
        replaceSelectValues(lastGradeLevel, 'last-grade')
    }
   //set default academic year
    startYear.value = year;
    endYear.value = year + 1;
    //regex
    //error messages
    const emptyError = "This field is required";
    function validateStartYear() {
        let startYearVal = parseInt(startYear.value);
        let endYearVal = parseInt(endYear.value);
        
        if(ValidationUtils.isEmpty(startYear)) {
            return ValidationUtils.errorMessages("em-start-year", ValidationUtils.emptyError, startYear);
        }
        
        if (!yearRegex.test(startYear.value)) {
            return ValidationUtils.errorMessages("em-start-year", invalidYear, startYear);
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
            return ValidationUtils.errorMessages("em-start-year", invalidYear, endYear);
        }
        //ensure that the end year is not equal to the start year
        else if (endYearVal == startYearVal) {
            return ValidationUtils.errorMessages("em-start-year", "Academic year cannot be equal", endYear);
        }     
       //ensure that the end year is always greater than the start year 
        else if(endYearVal < startYearVal) {
            return ValidationUtils.errorMessages("em-start-year", "End year cannot be lower than the starting year", endYear);
        }
        else if(endYearVal != startYearVal + 1) {
            return ValidationUtils.errorMessages("em-start-year", "Academic year should be 1 year apart", endYear);
        }
        ValidationUtils.clearError("em-start-year", endYear);
        return true;
    }
    //check if huling natapos na taon is not greater than the current year
    function validateYearFinished(){
        const lastYearVal = parseInt(lastYear.value);

        if (ValidationUtils.isEmpty(lastYear)) {
            return ValidationUtils.errorMessages("em-last-year-finished", ValidationUtils.emptyError, lastYear);
        }
        else if (!yearRegex.test(lastYear.value)){
            return ValidationUtils.errorMessages("em-last-year-finished", invalidYear, lastYear);
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
    //validate school Id
    const idFields = [
        {element: lschoolId, error: "em-lschoolID"},
        {element: fschoolId, error: "em-fschoolID"}
    ];
    //validate empty character fields and school name validity
    const fields = [
        {element: lschool, error: "em-lschool"},
        {element: lschoolAddr, error: "em-lschoolAddress"},
        {element: fschool, error: "em-fschool"},
        {element: fschoolAddr, error: "em-fschoolAddress"}
    ];
    const yearFields = [
        {element: startYear, error: "em-start-year"},
        {element: endYear, error: "em-start-year"},
        {element: lastYear, error: "em-last-year-finished"}
    ]
    function validateSchoolId(element, errorElement) {
        if (ValidationUtils.isEmpty(element)) {
            return ValidationUtils.errorMessages(errorElement, ValidationUtils.emptyError, element);
        }
        else if(!idRegex.test(element.value)) {
            return ValidationUtils.errorMessages(errorElement, invalidSchoolId, element);
        }
        ValidationUtils.clearError(errorElement, element);
        return true;
    }
    function validateSchool(element, errorElement){
        if (ValidationUtils.isEmpty(element)) { 
            return ValidationUtils.errorMessages(errorElement, ValidationUtils.emptyError, element);
        }
        else if (!charRegex.test(element.value)) {
            return ValidationUtils.errorMessages(errorElement, invalidChar, element);
        }
        ValidationUtils.clearError(errorElement, element);
        return true;
    }
    //Event triggers
    fields.forEach(({element, error}) => {
        element.addEventListener('input', ()=> validateSchool(element, error));
    });
    yearFields.forEach(({element, error}) => {
        element.addEventListener('input', function(e) {
            const value = e.target.value;

            if (/\D/.test(value)) {
                const cursorPos = e.target.selectionStart;
                const sanitizedValue = value.replace(/\D/g, '');
                e.target.value = sanitizedValue;
                const posDiff = value.length - sanitizedValue.length;
                e.target.setSelectionRange(cursorPos - posDiff, cursorPos - posDiff);
                value = sanitizedValue;
            }
            if (value.length > 4) {
                e.target.value = value.slice(0, 4);
            }
        });
    });
    idFields.forEach(({element, error}) => {
        element.addEventListener('input', function(e) {
            let value = e.target.value;
            // Only sanitize if there are actual changes to sanitize
            if (/\D/.test(value)) {
                // Preserve cursor position
                const cursorPos = e.target.selectionStart;
                const sanitizedValue = value.replace(/\D/g, '');
                e.target.value = sanitizedValue;
                // Restore cursor position accounting for removed characters
                const posDiff = value.length - sanitizedValue.length;
                e.target.setSelectionRange(cursorPos - posDiff, cursorPos - posDiff);
                value = sanitizedValue;
            }
            
            // Limit to 6 digits
            if (value.length > 6) {
                e.target.value = value.slice(0, 6);
            }
            
            validateSchoolId(element, error);
        });
        // Add keydown handler for special keys
        element.addEventListener('keydown', function(e) {
            const allowedKeys = [46, 8, 9, 27, 13];
            // Allow: backspace, delete, tab, escape, enter
            if (allowedKeys.includes(e.key) !== -1 ||
                // Allow: Ctrl+A, Command+A
                (e.key === 'a' && (e.ctrlKey === true || e.metaKey === true)) ||
                // Allow: Ctrl+C, Command+C
                (e.key === 'c' && (e.ctrlKey === true || e.metaKey === true)) ||
                // Allow: Ctrl+V, Command+V
                (e.key === 'v' && (e.ctrlKey === true || e.metaKey === true)) ||
                // Allow: Ctrl+X, Command+X
                (e.key === 'x' && (e.ctrlKey === true || e.metaKey === true)) ||
                // Allow: home, end, left, right, up, down
                (e.key >= 35 && e.key <= 40)) {
                return;  // let it happen, don't do anything
            }
            
            // Prevent if not a number
            if (!/^\d$/.test(e.key) && !e.ctrlKey && !e.metaKey) {
                e.preventDefault();
            }
        });
        element.addEventListener('blur', () => validateSchoolId(element, error));
    });
    startYear.addEventListener('input',validateStartYear);
    endYear.addEventListener('input',validateAcademicYear);
    lastYear.addEventListener('input',validateYearFinished);

    function validatePreviousSchoolInfo() {
        let isValid = true;

        // Validate school fields
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

        // Validate school IDs
        const idFields = [
            {element: lschoolId, error: "em-lschoolID"},
            {element: fschoolId, error: "em-fschoolID"}
        ];

        idFields.forEach(({element, error}) => {
            if (!validateSchoolId(element, error)) {
                isValid = false;
            }
        });

        // Validate years
        if (!validateStartYear()) isValid = false;
        if (!validateAcademicYear()) isValid = false;
        if (!validateYearFinished()) isValid = false;

        ValidationUtils.validationState.previousSchool = isValid;
        return isValid;
    }
    // Expose the validation function to the global scope
    window.validatePreviousSchoolInfo = validatePreviousSchoolInfo;
});    
 