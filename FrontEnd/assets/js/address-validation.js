document.addEventListener("DOMContentLoaded", function() {
  const form = document.getElementById("enrollment-form");
  const regions = document.getElementById("region");
  const provinces = document.getElementById("province");
  const cityOrMunicipality = document.getElementById("city-municipality");
  const barangay = document.getElementById("barangay");
  const subdivsion = document.getElementById("subdivision");
  const houseNumber = document.getElementById("house-number"); 
  let regionCode = "";
  let provinceCode = "";
  let cityCode = "";
  const emptyError = "This field is required";
  const notNumber = "This field must be a number";

  // Function to clear error messages on user interaction
  function clearAddressErrors(element) {
    const container = element.parentElement.querySelector('.error-msg');
    if (container) {
      container.classList.remove("show");
      const errorSpan = container.querySelector('span');
      if (errorSpan) {
        errorSpan.innerHTML = '';
      }
    }
    element.style.border = "1px solid #616161";
  }

  // Add error clearing to address fields
  [regions, provinces, cityOrMunicipality, barangay, subdivsion, houseNumber].forEach(element => {
    if (element) {
      element.addEventListener('change', () => clearAddressErrors(element));
      element.addEventListener('input', () => clearAddressErrors(element));
    }
  });

  function isEmpty(element) {
    return !element.value.trim();
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
  function errorMessages(errorElement, message, childElement) {
    const container = childElement.parentElement.querySelector('.error-msg');
    const errorSpan = container.querySelector('.' + errorElement);

    container.classList.add("show");
    childElement.style.border = "1px solid red";
    errorSpan.innerHTML = message;
  }
  function validateEmpty(element, errorElement) {
    if(isEmpty(element)) {
        errorMessages(errorElement, emptyError, element);
        checkEmptyFocus(element, errorElement);
    }
    else {
        clearError(errorElement, element);
    }
  } 
  function validateSubdivision() {
    if(isEmpty(subdivsion)) {
        errorMessages("em-subdivision", emptyError, subdivsion);
        checkEmptyFocus(subdivsion, "em-subdivision");
    }
    else {
        clearError("em-subdivision", subdivsion);
    }
  }
  function validateHouseNumber(e) {
    if(isEmpty(houseNumber)) {
        errorMessages("em-house-number", emptyError, houseNumber);
        checkEmptyFocus(houseNumber, "em-house-number");
        return;
    }
    if(isNaN(e.key) && e.key !== "Backspace") {
        errorMessages("em-house-number", notNumber, houseNumber);
        checkEmptyFocus(houseNumber, "em-house-number");
        e.preventDefault();
    }
    else {
        clearError("em-house-number", houseNumber);
    }
  }
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

async  function getBarangay() {
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
  subdivsion.addEventListener('keyup', validateSubdivision);
  houseNumber.addEventListener('keydown', validateHouseNumber);
  houseNumber.addEventListener('blur', function() {
    if(isEmpty(houseNumber)) {
      ValidationUtils.errorMessages("em-house-number", emptyError, houseNumber);           
    }
  });
  window.changeAddressValues = async function() {
    try {
        // Create an object to store both codes and text values
        const addressData = {
            region: { code: '', text: '' },
            province: { code: '', text: '' },
            city: { code: '', text: '' },
            barangay: { code: '', text: '' }
        };

        // Store both code and text for each field
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

        // Create hidden inputs to store both codes and text values
        Object.entries(addressData).forEach(([key, value]) => {
            // Create or update hidden input for code
            let codeInput = form.querySelector(`input[name="${key}_code"]`);
            if (!codeInput) {
                codeInput = document.createElement('input');
                codeInput.type = 'hidden';
                codeInput.name = `${key}_code`;
                form.appendChild(codeInput);
            }
            codeInput.value = value.code;

            // Create or update hidden input for text
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
    getRegions(); 
    if (regions.value) {
      getProvinces();
    }
    else{
      initialSelectValue(provinces, "Region");
    }
    if (provinces.value) {
      getCity();
    }
    else {
      initialSelectValue(cityOrMunicipality, "Province");
    }
    if (cityOrMunicipality.value) {
      getBarangay();
    }
    else {
      initialSelectValue(barangay, "City/Municipality");
    }
    function validateField(element) {
      if (!element) return;
    
      let isValid = true;
      let error = "";
      let label = "";
    
      // Determine field type and related info
      switch (element) {
        case regions:
          error = "em-region"; label = "Region"; break;
        case provinces:
          error = "em-province"; label = "Province"; break;
        case cityOrMunicipality:
          error = "em-city"; label = "City/Municipality"; break;
        case barangay:
          error = "em-barangay"; label = "Barangay"; break;
        case subdivsion:
          error = "em-subdivision"; label = "Subdivision/Street"; break;
        case houseNumber:
          error = "em-house-number"; label = "House Number"; break;
        default:
          return;
      }
    
      // Validate based on element type
      if (element.tagName === "SELECT") {
        if (!element.value) {
          ValidationUtils.errorMessages(error, `Please select a ${label}`, element);
          isValid = false;
        } else {
          ValidationUtils.clearError(error, element);
        }
      } else if (element.tagName === "INPUT") {
        if (isEmpty(element)) {
          ValidationUtils.errorMessages(error, emptyError, element);
          isValid = false;
        } else {
          ValidationUtils.clearError(error, element);
        }
    
        if (element === houseNumber && !isEmpty(element)) {
          if (isNaN(element.value)) {
            ValidationUtils.errorMessages(error, notNumber, element);
            isValid = false;
          }
        }
      }
      return isValid;
    }
    function validateAddressInfo() {
      let isValid = true;

      // Validate all required address fields
      const addressFields = [
        { element: regions, error: "em-region", label: "Region" },
        { element: provinces, error: "em-province", label: "Province" },
        { element: cityOrMunicipality, error: "em-city", label: "City/Municipality" },
        { element: barangay, error: "em-barangay", label: "Barangay" },
        { element: subdivsion, error: "em-subdivision", label: "Subdivision/Street" },
        { element: houseNumber, error: "em-house-number", label: "House Number" }
      ];

      addressFields.forEach(({ element, error, label }) => {
        if (!element) return; // Skip if element doesn't exist

        // For select elements (dropdown)
        if (element.tagName === "SELECT") {
          if (!element.value) {
            ValidationUtils.errorMessages(error, `Please select a ${label}`, element);
            isValid = false;
          } else {
            ValidationUtils.clearError(error, element);
          }
        }
        // For text inputs
        else if (element.tagName === "INPUT") {
          if (isEmpty(element)) {
            ValidationUtils.errorMessages(error, emptyError, element);
            isValid = false;
          } else {
            ValidationUtils.clearError(error, element);
          }

          // Additional validation for house number
          if (element === houseNumber && !isEmpty(element)) {
            if (isNaN(element.value)) {
              ValidationUtils.errorMessages(error, notNumber, element);
              isValid = false;
            }
          }
        }
      });

      // Update the global validation state
      ValidationUtils.validationState.addressInfo = isValid;
      return isValid;
    }

    // Expose the validation function to the global scope
    window.validateAddressInfo = validateAddressInfo;

    // Add validation triggers for address fields
    [regions, provinces, cityOrMunicipality, barangay].forEach(element => {
      if (element) {
        element.addEventListener('change', function(){ 
          validateField(element) 
        });
      }
    });
    // Add validation triggers for text inputs
    [subdivsion, houseNumber].forEach(element => {
      if (element) {
        element.addEventListener('input', function(){ 
          validateField(element) 
        });
        element.addEventListener('blur', function(){ 
          validateField(element) 
        });
      }
    });

  async function replaceTextBox(replaceElement, addressType) {
    let createTBox = document.createElement("input");
    createTBox.type = "text";
    createTBox.id = addressType;
    createTBox.placeholder = `Enter ${addressType} manually`;
    createTBox.className = "textbox";
    replaceElement.replaceWith(createTBox);

    // Add error clearing to the new textbox
    createTBox.addEventListener('input', () => clearAddressErrors(createTBox));
    createTBox.addEventListener('keyup', ()=> validateEmpty(createTBox, `em-${addressType}`));
  }
});