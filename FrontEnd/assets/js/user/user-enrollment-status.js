document.addEventListener('DOMContentLoaded', function() {
    const status = document.querySelector('.status');
    const statusInfo = document.querySelector('#status-info');
    const modal = document.getElementById('editModal');
    const closeModal = document.querySelector('.close-modal');
    const formFields = document.querySelector('.form-fields');
    const editForm = document.getElementById('editEnrollmentForm');
    const cancelBtn = document.querySelector('.cancel-btn');

    // Add status color class
    if(status) {
        const statusText = status.textContent.trim().toLowerCase();
        statusInfo.classList.add(statusText);
    }

    // Modal control functions
    function openModal() {
        modal.style.display = 'block';
    }

    function closeModalHandler() {
        modal.style.display = 'none';
        formFields.innerHTML = ''; // Clear form fields
    }

    // Close modal with X button or cancel button
    closeModal.addEventListener('click', closeModalHandler);
    cancelBtn.addEventListener('click', closeModalHandler);

    function createFormField(label, data) {
        const fieldContainer = document.createElement('div');
        fieldContainer.className = 'form-field';

        const labelElement = document.createElement('label');
        labelElement.textContent = label;
        
        let inputElement;
        
        // Handle image type
        if (data.type === 'image') {
            const imageContainer = document.createElement('div');
            imageContainer.className = 'image-container';
            
            // Display current image if exists
            if (data.value && data.value.path) {
                const currentImage = document.createElement('img');
                currentImage.src = data.value.path;
                currentImage.alt = 'PSA Image';
                currentImage.className = 'current-psa-image';
                imageContainer.appendChild(currentImage);
            }
            
            // Add file input for new image
            inputElement = document.createElement('input');
            inputElement.type = 'file';
            inputElement.accept = 'image/*';
            inputElement.name = label.replace(/\s+/g, '_').toLowerCase();
            inputElement.id = inputElement.name;
            
            // Add preview functionality
            inputElement.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        // Remove existing preview if any
                        const existingPreview = imageContainer.querySelector('.image-preview');
                        if (existingPreview) {
                            existingPreview.remove();
                        }
                        
                        // Create new preview
                        const preview = document.createElement('img');
                        preview.src = e.target.result;
                        preview.alt = 'Image Preview';
                        preview.className = 'image-preview';
                        imageContainer.appendChild(preview);
                    };
                    reader.readAsDataURL(file);
                }
            });
            
            imageContainer.appendChild(inputElement);
            fieldContainer.appendChild(labelElement);
            fieldContainer.appendChild(imageContainer);
            return fieldContainer;
        }
        
        if (data.type === 'radio') {
            const radioContainer = document.createElement('div');
            radioContainer.className = 'radio-group';
            
            // Define radio options based on field name
            let options;
            if (label === 'School Type') {
                options = [
                    { label: 'Private', value: 'private' },
                    { label: 'Public', value: 'public' }
                ];
            } 
            else if (label === 'Sex') {
                options = [
                    { label: 'Male', value: 'male' },
                    { label: 'Female', value: 'female' }
                ];
            }
            else if (label.includes('4Ps Member')) {
                options = [
                    { label: 'Yes', value: '1' },
                    { label: 'No', value: '0' }
                ];
            }
            else {
                options = [
                    { label: 'Yes', value: '1' },
                    { label: 'No', value: '0' }
                ];
            }
            
            options.forEach(option => {
                const radioWrapper = document.createElement('div');
                inputElement = document.createElement('input');
                inputElement.type = 'radio';
                inputElement.name = label.replace(/\s+/g, '_').toLowerCase();
                inputElement.value = option.value;
                inputElement.id = `${inputElement.name}_${option.value}`;
                inputElement.checked = String(data.value).toLowerCase() === String(option.value).toLowerCase();
                
                const radioLabel = document.createElement('label');
                radioLabel.textContent = option.label;
                radioLabel.htmlFor = inputElement.id;

                radioWrapper.appendChild(inputElement);
                radioWrapper.appendChild(radioLabel);
                radioContainer.appendChild(radioWrapper);
            });

            fieldContainer.appendChild(labelElement);
            fieldContainer.appendChild(radioContainer);
            return fieldContainer;
        }
        
        if (data.type === 'select') {
            inputElement = document.createElement('select');
            inputElement.name = label.replace(/\s+/g, '_').toLowerCase();
            inputElement.id = inputElement.name;
            // Handle address fields
            if (label === 'Region' || label === 'Province' || label === 'City/Municipality' || label === 'Barangay') {
                // Convert label to match expected IDs
                const addressId = label === 'Region' ? 'region' :
                                 label === 'Province' ? 'province' :
                                 label === 'City/Municipality' ? 'city-municipality' :
                                 'barangay';
                inputElement.name = addressId;
                inputElement.id = addressId;
                
                // Add default option
                const defaultOption = document.createElement('option');
                defaultOption.value = "";
                defaultOption.textContent = `Select ${label}`;
                inputElement.appendChild(defaultOption);

                // Add the current value as an option
                if (data.value && data.code) {
                    const currentOption = document.createElement('option');
                    currentOption.value = data.code;
                    currentOption.textContent = data.value;
                    currentOption.selected = true;
                    // Set the hidden name field with the current value
                    const nameField = document.getElementById(`${addressId}-name`);
                    if (nameField) {
                        nameField.value = data.value;
                    }
                    inputElement.appendChild(currentOption);
                }

                // Add change event listeners for cascading dropdowns
                if (addressId === 'region') {
                    inputElement.addEventListener('change', function() {
                        const regionCode = this.value;
                        const selectedText = this.options[this.selectedIndex].text;
                        document.getElementById(`${addressId}-name`).value = selectedText;
                        const provinceSelect = document.getElementById('province');
                        const citySelect = document.getElementById('city-municipality');
                        const barangaySelect = document.getElementById('barangay');
                        
                        // Store the pre-selected values
                        const preSelectedProvince = provinceSelect ? provinceSelect.getAttribute('data-preselected') : null;
                        
                        // Reset dependent dropdowns
                        if (provinceSelect) {
                            provinceSelect.innerHTML = '<option value="">Select Province</option>';
                            if (citySelect) citySelect.innerHTML = '<option value="">Select City/Municipality</option>';
                            if (barangaySelect) barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                        }

                        // Fetch provinces for selected region
                        if (regionCode) {
                            fetch(`https://psgc.gitlab.io/api/regions/${regionCode}/provinces`)
                                .then(response => response.json())
                                .then(provinces => {
                                    provinces.forEach(province => {
                                        const option = document.createElement('option');
                                        option.value = province.code;
                                        option.textContent = province.name;
                                        // Check if this is the pre-selected province
                                        if (preSelectedProvince === province.code) {
                                            option.selected = true;
                                        }
                                        provinceSelect.appendChild(option);
                                    });
                                    // If we had a pre-selected province, trigger its change event
                                    if (preSelectedProvince) {
                                        provinceSelect.dispatchEvent(new Event('change'));
                                    }
                                })
                                .catch(error => console.error('Error fetching provinces:', error));
                        }
                    });
                } else if (addressId === 'province') {
                    // Store the pre-selected value as a data attribute
                    if (data.code) {
                        inputElement.setAttribute('data-preselected', data.code);
                    }
                    
                    inputElement.addEventListener('change', function() {
                        const provinceCode = this.value;
                        const selectedText = this.options[this.selectedIndex].text;
                        document.getElementById(`${addressId}-name`).value = selectedText;
                        const citySelect = document.getElementById('city-municipality');
                        const barangaySelect = document.getElementById('barangay');
                        
                        // Store the pre-selected values
                        const preSelectedCity = citySelect ? citySelect.getAttribute('data-preselected') : null;
                        
                        // Reset dependent dropdowns
                        if (citySelect) {
                            citySelect.innerHTML = '<option value="">Select City/Municipality</option>';
                            if (barangaySelect) barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                        }

                        // Fetch cities/municipalities for selected province
                        if (provinceCode) {
                            fetch(`https://psgc.gitlab.io/api/provinces/${provinceCode}/cities-municipalities`)
                                .then(response => response.json())
                                .then(cities => {
                                    cities.forEach(city => {
                                        const option = document.createElement('option');
                                        option.value = city.code;
                                        option.textContent = city.name;
                                        // Check if this is the pre-selected city
                                        if (preSelectedCity === city.code) {
                                            option.selected = true;
                                        }
                                        citySelect.appendChild(option);
                                    });
                                    // If we had a pre-selected city, trigger its change event
                                    if (preSelectedCity) {
                                        citySelect.dispatchEvent(new Event('change'));
                                    }
                                })
                                .catch(error => console.error('Error fetching cities:', error));
                        }
                    });
                } else if (addressId === 'city-municipality') {
                    // Store the pre-selected value as a data attribute
                    if (data.code) {
                        inputElement.setAttribute('data-preselected', data.code);
                    }
                    
                    inputElement.addEventListener('change', function() {
                        const cityCode = this.value;
                        const selectedText = this.options[this.selectedIndex].text;
                        document.getElementById(`${addressId}-name`).value = selectedText;
                        const barangaySelect = document.getElementById('barangay');
                        
                        // Store the pre-selected value
                        const preSelectedBarangay = barangaySelect ? barangaySelect.getAttribute('data-preselected') : null;
                        
                        // Reset barangay dropdown
                        if (barangaySelect) {
                            barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                        }

                        // Fetch barangays for selected city/municipality
                        if (cityCode) {
                            fetch(`https://psgc.gitlab.io/api/cities-municipalities/${cityCode}/barangays`)
                                .then(response => response.json())
                                .then(barangays => {
                                    barangays.forEach(barangay => {
                                        const option = document.createElement('option');
                                        option.value = barangay.code;
                                        option.textContent = barangay.name;
                                        // Check if this is the pre-selected barangay
                                        if (preSelectedBarangay === barangay.code) {
                                            option.selected = true;
                                        }
                                        barangaySelect.appendChild(option);
                                    });
                                })
                                .catch(error => console.error('Error fetching barangays:', error));
                        }
                    });
                } else if (addressId === 'barangay') {
                    // Store the pre-selected value as a data attribute
                    if (data.code) {
                        inputElement.setAttribute('data-preselected', data.code);
                    }
                    inputElement.addEventListener('change', function() {
                        const selectedText = this.options[this.selectedIndex].text;
                        document.getElementById(`${addressId}-name`).value = selectedText;
                    });
                }

                // Initial load of all regions
                if (addressId === 'region') {
                    fetch('https://psgc.gitlab.io/api/regions/')
                        .then(response => response.json())
                        .then(regions => {
                            regions.forEach(region => {
                                const option = document.createElement('option');
                                option.value = region.code;
                                option.textContent = region.name;
                                // Check if this is the pre-selected region
                                if (data.code === region.code) {
                                    option.selected = true;
                                }
                                inputElement.appendChild(option);
                            });
                            // If we have a pre-selected region, trigger its change event
                            if (data.code) {
                                inputElement.dispatchEvent(new Event('change'));
                            }
                        })
                        .catch(error => console.error('Error fetching regions:', error));
                }

                // If we have initial values, trigger the cascade
                if (data.code) {
                    if (addressId === 'region') {
                        setTimeout(() => inputElement.dispatchEvent(new Event('change')), 500);
                    } else if (addressId === 'province') {
                        setTimeout(() => inputElement.dispatchEvent(new Event('change')), 1000);
                    } else if (addressId === 'city-municipality') {
                        setTimeout(() => inputElement.dispatchEvent(new Event('change')), 1500);
                    }
                }
            }
            // Handle educational attainment
            else if (label.includes('Educational Attainment')) {
                const educationOptions = [
                    'Hindi Nakapag-aral',
                    'Hindi Nakapag-aral pero marunong magbasa at magsulat',
                    'Nakatuntong ng Elementarya',
                    'Nakapagtapos ng Elementarya',
                    'Nakatuntong ng Sekundarya',
                    'Nakapagtapos ng Sekundarya',
                    'Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal/Bokasyonal'
                ];
                
                inputElement.innerHTML = '<option value="">Select Educational Attainment</option>';
                educationOptions.forEach(opt => {
                    const option = document.createElement('option');
                    option.value = opt;
                    option.textContent = opt;
                    option.selected = opt === data.value;
                    inputElement.appendChild(option);
                });
            }
            // Handle grade levels
            else if (label.includes('Grade Level')) {
                const gradeLevels = [
                    {label:'Kinder I', value: '1'},
                    {label:'Kinder II', value: '2'},
                    {label:'Grade 1', value: '3'},
                    {label:'Grade 2', value: '4'},
                    {label:'Grade 3', value: '5'},
                    {label:'Grade 4', value: '6'},
                    {label:'Grade 5', value: '7'},
                    {label:'Grade 6', value: '8'},

                ];
                
                inputElement.innerHTML = '<option value="">Select Grade Level</option>';
                gradeLevels.forEach(grade => {
                    const option = document.createElement('option');
                    option.value = grade.value;
                    option.textContent = grade.label;
                    option.selected = grade.value == data.value;
                    inputElement.appendChild(option);
                });
            }
        } else {
            inputElement = document.createElement('input');
            inputElement.type = data.type;
            inputElement.value = data.value;
            inputElement.name = label.replace(/\s+/g, '_').toLowerCase();
            inputElement.id = inputElement.name;
        }
        
        labelElement.htmlFor = inputElement.id;
        
        fieldContainer.appendChild(labelElement);
        if (data.type !== 'radio') {
            fieldContainer.appendChild(inputElement);
        }
        return fieldContainer;
    }

    // Handle edit button click
    statusInfo.addEventListener('click', async function(e) {
        if (e.target.classList.contains('edit-enrollment-form')) {
            const enrolleeId = e.target.getAttribute('data-id');
            try {
                const response = await fetch(`../../../BackEnd/api/user/fetchUserEditForm.php?editId=${enrolleeId}`);
                const data = await response.json();
                
                if (data.success) {
                    formFields.innerHTML = ''; // Clear existing fields
                    console.log(data);
                    // Add hidden enrollee ID field
                    const hiddenField = document.createElement('input');
                    hiddenField.type = 'hidden';
                    hiddenField.name = 'enrolleeId';
                    hiddenField.value = enrolleeId;
                    formFields.appendChild(hiddenField);
                    
                    // Create form fields for each data item
                    Object.entries(data).forEach(([key, value]) => {
                        if (key !== 'success') {
                            const fieldElement = createFormField(key, value);
                            formFields.appendChild(fieldElement);
                        }
                    });
                    
                    openModal();
                } else {
                    alert(data.error || 'Failed to load enrollment data');
                }
            } catch (error) {
                alert('Error fetching enrollment form: ' + error);
            }
        }
    });

    // Handle form submission
    editForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Create FormData object
        const formData = new FormData();
        
        // Add basic form fields
        const formElements = formFields.querySelectorAll('input:not([type="file"]), select');
        formElements.forEach(element => {
            if (element.type === 'radio') {
                if (element.checked) {
                    formData.append(element.name, element.value);
                }
            } else {
                formData.append(element.name, element.value);
            }
        });

        // Add hidden address name fields
        const hiddenAddressFields = document.querySelectorAll('input[type="hidden"][name$="_name"]');
        hiddenAddressFields.forEach(field => {
            formData.append(field.name, field.value);
        });

        // Handle file upload
        const fileInput = formFields.querySelector('input[type="file"]');
        if (fileInput && fileInput.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Remove the data URL prefix
                const base64String = e.target.result.split(',')[1];
                
                // Create the final data object
                const finalData = {
                    ...Object.fromEntries(formData),
                    psa_image: base64String
                };

                // Send data to server
                submitFormData(finalData);
            };
            reader.readAsDataURL(fileInput.files[0]);
        } else {
            // If no new image, submit without image data
            const finalData = Object.fromEntries(formData);
            submitFormData(finalData);
        }
    });

    function submitFormData(formData) {
        // Debug: Log the form data being sent
        console.log('Form data being sent:', formData);
        
        fetch('../../../BackEnd/api/user/updateUserForm.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            // Debug: Log the response
            console.log('Server response:', data);
            
            if (data.success) {
                alert('Enrollment data updated successfully');
                closeModalHandler();
                // Reload the page to show updated data
                window.location.reload();
            } else {
                alert(data.error || 'Failed to update enrollment data');
            }
        })
        .catch(error => {
            alert('Error updating enrollment data: ' + error);
            console.log(error);
        });
    }
});