document.addEventListener('DOMContentLoaded', function() {
    const radioInput = document.querySelectorAll('input[name="subject"]');
    const selectContainer = document.getElementById('select-container');
    const checkboxContainer = document.getElementById('checkbox-container');
    const checkbox = document.getElementById("checkboxes");
    
    function updateDisplay() {
        
        const selectedRadio = document.querySelector('input[name="subject"]:checked');
        console.log(selectedRadio);
        if (selectedRadio && selectedRadio.value == "Yes") {
            selectContainer.style.display = "none";
            checkboxContainer.style.display = "block";
        }
        else {
            selectContainer.style.display = "block";
            checkboxContainer.style.display = "none";
        }
    }

    document.getElementById('toggleCheckBox').addEventListener('click', function() {
        checkbox.classList.toggle('show');
    }) 

    const initialRadioInput = document.querySelector('input[name="subject"][value="Yes"]');
    if(initialRadioInput) {
        initialRadioInput.checked = true;
    }

    updateDisplay();

    radioInput.forEach(radio=> {
        radio.addEventListener('change', updateDisplay);
    })
});