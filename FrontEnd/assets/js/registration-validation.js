document.addEventListener('DOMContentLoaded', function(){
    const form = document.getElementById("registration-form");
    const contactNumber = document.getElementById("contact-number");
    const guardianLname = document.getElementById("guardian-last-name");
    const guardianMname = document.getElementById("guardian-middle-name");
    const guardianFname = document.getElementById("guardian-first-name");

    const charRegex = /^[A-Za-z0-9\s.,'-]{3,100}$/;
    const notNumber = "This field must be a number";

    const allTBox = [
        {element: guardianLname, error: "em-guardian-last-name"},
        {element: guardianMname, error: "em-guardian-middle-name"},
        {element: guardianFname, error: "em-guardian-first-name"}
    ];
    function isEmpty(element) {
        return !element.value.trim();
    }
    function errorMessages(errorElement, message, childElement) {
        document.querySelector("."+errorElement).classList.add("show");
        childElement.style.border = "1px solid red";
        document.querySelector("."+errorElement).innerHTML = message;
    }
      function clearError(errorElement, childElement) {
        const errorField = document.querySelector("." + errorElement);
        errorField.classList.remove("show");
        errorField.innerHTML = "";
        childElement.style.border = "1px solid #616161";
    }
      function checkEmptyFocus(element, errorElement) {
        element.addEventListener('blur', ()=> clearError(errorElement, element));
    }
    
    function validateTextBox(element, errorElement, e) {
        if(isEmpty(element)) {
            errorMessages(errorElement, emptyError, element);
            checkEmptyFocus(element, errorElement);
        }
        else if(element.value.length >= 20 && e.key !== "Backspace") {
            errorMessages(errorElement, "Must be at least 3 and no more than 50 characters", element);
            checkEmptyFocus(element, errorElement);
            e.preventDefault();
        }
        else if (!charRegex.test(element.value)) {
            errorMessages(errorElement, invalidChar, element);
        }
        else {
            clearError(errorElement, element);
        }
    }

    function validateContactNumber(e) {
        if(isNaN(e.key) && e.key !== "Backspace") {
            e.preventDefault();
            errorMessages("em-contact-number", notNumber, contactNumber);
            checkEmptyFocus(contactNumber, "em-contact-number");
        }
        else if(contactNumber.value.length >= 11 && e.key !== "Backspace") {
            e.preventDefault();
            errorMessages("em-contact-number", "Not a valid phone number", contactNumber);
            checkEmptyFocus(contactNumber, "em-contact-number");
        }
        else {
            clearError("em-contact-number", contactNumber);
        }
    }
    allTBox.forEach(({element,error}) =>{
        element.addEventListener('keydown', (e)=> validateTextBox(element, error, e));
    });
    contactNumber.addEventListener('keydown', (e)=> validateContactNumber(e));
});