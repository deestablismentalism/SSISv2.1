import { close, loadingText, modalHeader } from '../utils.js';

document.addEventListener('DOMContentLoaded', function(){
    const editAddressBtn = document.getElementById('edit-address');
    const editCredentialsBtn = document.getElementById('edit-credentials');
    const editPersonalInformationBtn = document.getElementById('edit-personal-information');
    const editProfilePictureBtn = document.getElementById('edit-profile-picture');
    const modal = document.getElementById('information-modal');
    const modalContent = document.getElementById('information-modal-content');

    const nameRegex = /^[A-Za-z\s.\-`'ñÑ]+$/;
    const phoneRegex = /^09\d{9}$/;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;
    const textRegex = /^[A-Za-z0-9\s.,'\-]{3,100}$/;
    const numericRegex = /^\d+$/;

    function sanitizeNameInput(element) {
        element.addEventListener('input', function(e) {
            const cursorPos = e.target.selectionStart;
            const oldValue = e.target.value;
            const newValue = oldValue.split('').filter(char => /[A-Za-z\s.\-`'ñÑ]/.test(char)).join('');
            if (oldValue !== newValue) {
                e.target.value = newValue;
                e.target.setSelectionRange(cursorPos - 1, cursorPos - 1);
            }
        });
    }

    function sanitizePhoneInput(element) {
        element.addEventListener('input', function(e) {
            const cursorPos = e.target.selectionStart;
            const oldValue = e.target.value;
            const newValue = oldValue.split('').filter(char => /[0-9]/.test(char)).join('').substring(0, 11);
            if (oldValue !== newValue) {
                e.target.value = newValue;
                e.target.setSelectionRange(cursorPos - (oldValue.length - newValue.length), cursorPos - (oldValue.length - newValue.length));
            }
        });
    }

    function sanitizeTextInput(element) {
        element.addEventListener('input', function(e) {
            const cursorPos = e.target.selectionStart;
            const oldValue = e.target.value;
            const newValue = oldValue.split('').filter(char => /[A-Za-z0-9\s.,'\-]/.test(char)).join('').substring(0, 100);
            if (oldValue !== newValue) {
                e.target.value = newValue;
                e.target.setSelectionRange(cursorPos - (oldValue.length - newValue.length), cursorPos - (oldValue.length - newValue.length));
            }
        });
    }

    function sanitizeNumericInput(element, maxLength) {
        element.addEventListener('input', function(e) {
            const cursorPos = e.target.selectionStart;
            const oldValue = e.target.value;
            const newValue = oldValue.split('').filter(char => /[0-9]/.test(char)).join('').substring(0, maxLength);
            if (oldValue !== newValue) {
                e.target.value = newValue;
                e.target.setSelectionRange(cursorPos - (oldValue.length - newValue.length), cursorPos - (oldValue.length - newValue.length));
            }
        });
    }

    function capitalizeWords(element) {
        element.addEventListener('blur', function(e) {
            const value = e.target.value;
            e.target.value = value.split(' ').map(word => {
                if (word.length === 0) return word;
                return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
            }).join(' ');
        });
    }

    function applyPersonalInfoSanitization(form) {
        const firstName = form.querySelector('#Staff_First_Name');
        const middleName = form.querySelector('#Staff_Middle_Name');
        const lastName = form.querySelector('#Staff_Last_Name');
        const email = form.querySelector('#Staff_Email');
        const phone = form.querySelector('#Staff_Contact_Number');

        if (firstName) {
            sanitizeNameInput(firstName);
            capitalizeWords(firstName);
        }
        if (middleName) {
            sanitizeNameInput(middleName);
            capitalizeWords(middleName);
        }
        if (lastName) {
            sanitizeNameInput(lastName);
            capitalizeWords(lastName);
        }
        if (phone) sanitizePhoneInput(phone);
    }

    function applyAddressSanitization(form) {
        const fields = [
            form.querySelector('#Brgy_Name'),
            form.querySelector('#Municipality_Name'),
            form.querySelector('#Province_Name'),
            form.querySelector('#Region')
        ];

        fields.forEach(field => {
            if (field) {
                sanitizeTextInput(field);
                capitalizeWords(field);
            }
        });
    }

    function applyIdentifiersSanitization(form) {
        const empNum = form.querySelector('#Employee_Number');
        const philhealth = form.querySelector('#Philhealth_Number');
        const tin = form.querySelector('#TIN');

        if (empNum) sanitizeNumericInput(empNum, 20);
        if (philhealth) sanitizeNumericInput(philhealth, 12);
        if (tin) sanitizeNumericInput(tin, 9);
    }

    function validatePersonalInfo(form) {
        const firstName = form.querySelector('#Staff_First_Name');
        const middleName = form.querySelector('#Staff_Middle_Name');
        const lastName = form.querySelector('#Staff_Last_Name');
        const email = form.querySelector('#Staff_Email');
        const phone = form.querySelector('#Staff_Contact_Number');

        if (!firstName.value.trim() || !nameRegex.test(firstName.value.trim())) {
            Notification.show({
                type: 'error',
                title: 'Validation Error',
                message: 'First name is invalid'
            });
            firstName.focus();
            return false;
        }

        if (middleName.value.trim() && !nameRegex.test(middleName.value.trim())) {
            Notification.show({
                type: 'error',
                title: 'Validation Error',
                message: 'Middle name is invalid'
            });
            middleName.focus();
            return false;
        }

        if (!lastName.value.trim() || !nameRegex.test(lastName.value.trim())) {
            Notification.show({
                type: 'error',
                title: 'Validation Error',
                message: 'Last name is invalid'
            });
            lastName.focus();
            return false;
        }

        if (!email.value.trim() || !emailRegex.test(email.value.trim())) {
            Notification.show({
                type: 'error',
                title: 'Validation Error',
                message: 'Email format is invalid'
            });
            email.focus();
            return false;
        }

        if (!phone.value.trim() || !phoneRegex.test(phone.value.trim())) {
            Notification.show({
                type: 'error',
                title: 'Validation Error',
                message: 'Contact number must be 09XXXXXXXXX'
            });
            phone.focus();
            return false;
        }

        return true;
    }

    function validateAddress(form) {
        const fields = [
            { el: form.querySelector('#Brgy_Name'), name: 'Barangay' },
            { el: form.querySelector('#Municipality_Name'), name: 'Municipality' },
            { el: form.querySelector('#Province_Name'), name: 'Province' },
            { el: form.querySelector('#Region'), name: 'Region' }
        ];

        for (const field of fields) {
            const value = field.el.value.trim();
            if (!value) {
                Notification.show({
                    type: 'error',
                    title: 'Validation Error',
                    message: `${field.name} is required`
                });
                field.el.focus();
                return false;
            }
            if (!textRegex.test(value)) {
                Notification.show({
                    type: 'error',
                    title: 'Validation Error',
                    message: `${field.name} contains invalid characters`
                });
                field.el.focus();
                return false;
            }
        }

        return true;
    }

    function validateIdentifiers(form) {
        const empNum = form.querySelector('#Employee_Number');
        const philhealth = form.querySelector('#Philhealth_Number');
        const tin = form.querySelector('#TIN');

        if (!empNum.value.trim() || !numericRegex.test(empNum.value.trim()) || empNum.value.length < 3) {
            Notification.show({
                type: 'error',
                title: 'Validation Error',
                message: 'Employee Number must be at least 3 digits'
            });
            empNum.focus();
            return false;
        }

        if (!philhealth.value.trim() || !numericRegex.test(philhealth.value.trim()) || philhealth.value.length !== 12) {
            Notification.show({
                type: 'error',
                title: 'Validation Error',
                message: 'PhilHealth Number must be 12 digits'
            });
            philhealth.focus();
            return false;
        }

        if (!tin.value.trim() || !numericRegex.test(tin.value.trim()) || tin.value.length !== 9) {
            Notification.show({
                type: 'error',
                title: 'Validation Error',
                message: 'TIN must be 9 digits'
            });
            tin.focus();
            return false;
        }

        return true;
    }

    function validateProfilePicture(form) {
        const fileInput = form.querySelector('#Profile_Picture');
        const file = fileInput.files[0];

        if (!file) {
            Notification.show({
                type: 'error',
                title: 'Validation Error',
                message: 'Please select an image file'
            });
            return false;
        }

        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!allowedTypes.includes(file.type)) {
            Notification.show({
                type: 'error',
                title: 'Validation Error',
                message: 'Only JPG, JPEG, and PNG files are allowed'
            });
            return false;
        }

        const maxSize = 5 * 1024 * 1024;
        if (file.size > maxSize) {
            Notification.show({
                type: 'error',
                title: 'Validation Error',
                message: 'File size must be less than 5MB'
            });
            return false;
        }

        return true;
    }

    function applyProfilePicturePreview(form) {
        const fileInput = form.querySelector('#Profile_Picture');
        const preview = form.querySelector('#profile-preview');

        if (fileInput && preview) {
            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                    if (allowedTypes.includes(file.type)) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            preview.src = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                }
            });
        }
    }

    function initializeFormHandler() {
        const form = modalContent.querySelector('form');
        if (!form) return;

        const formType = form.querySelector('input[name="form_type"]')?.value;

        if (formType === 'update_information') {
            applyPersonalInfoSanitization(form);
        } else if (formType === 'update_address') {
            applyAddressSanitization(form);
        } else if (formType === 'update_identifiers') {
            applyIdentifiersSanitization(form);
        } else if (formType === 'update_profile_picture') {
            applyProfilePicturePreview(form);
        }

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            let isValid = false;
            if (formType === 'update_information') {
                isValid = validatePersonalInfo(form);
            } else if (formType === 'update_address') {
                isValid = validateAddress(form);
            } else if (formType === 'update_identifiers') {
                isValid = validateIdentifiers(form);
            } else if (formType === 'update_profile_picture') {
                isValid = validateProfilePicture(form);
            }

            if (!isValid) return;

            Loader.show();

            try {
                const formData = new FormData(form);
                const response = await fetch('/BackEnd/api/admin/postEditStaffInformation.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                Loader.hide();

                if (result.success) {
                    Notification.show({
                        type: 'success',
                        title: 'Success',
                        message: result.message || 'Information updated successfully'
                    });
                    modal.style.display = 'none';
                } else {
                    Notification.show({
                        type: 'error',
                        title: 'Error',
                        message: result.message || 'Failed to update information'
                    });
                }
            } catch (error) {
                Loader.hide();
                Notification.show({
                    type: 'error',
                    title: 'Error',
                    message: error.message || 'An unexpected error occurred'
                });
            }
        });
    }
    
    console.log(modalContent);

    editAddressBtn.addEventListener('click', async function(){
        modal.style.display = 'block';
        try {
            const formResponse = await fetchEditAddress();
            modalContent.innerHTML = modalHeader();
            modalContent.innerHTML += formResponse;
            close(modal);
            initializeFormHandler();
            
        }
        catch(error) {
            Notification.show({
                type: "error",
                title: "Error",
                message: error.message
            });
            console.error(error);
            modalContent.innerHTML += `Failed to load`;
        }    
    })
    

    editCredentialsBtn.addEventListener('click', async function(){
        modal.style.display = 'block';
        try {
            const formResponse = await fetchEditIdentifiers();
            modalContent.innerHTML = modalHeader();
            modalContent.innerHTML += formResponse;
            close(modal);
            initializeFormHandler();
            
        }
        catch(error) {
            Notification.show({
                type: "error",
                title: "error",
                message: error.message
            });
        console.error(error);
            modalContent.innerHTML += `Failed to load`;
        }    
    })

    editPersonalInformationBtn.addEventListener('click', async function(){
        modal.style.display = 'block';
        try {
            const formResponse = await fetchEditPersonalInformation();
            modalContent.innerHTML = modalHeader();
            modalContent.innerHTML += formResponse;
            close(modal);
            initializeFormHandler();
            
        }
        catch(error) {
            Notification.show({
                type: "error",
                title: "error",
                message: error.message
            });
            console.error(error);
            modalContent.innerHTML += `Failed to load`;
        }    
    })

    editProfilePictureBtn.addEventListener('click', async function(){
        modal.style.display = 'block';
        try {
            const formResponse = await fetchEditProfilePicture();
            modalContent.innerHTML = modalHeader();
            modalContent.innerHTML += formResponse;
            close(modal);
            initializeFormHandler();
            
        }
        catch(error) {
            Notification.show({
                type: "error",
                title: "error",
                message: error.message
            });
            console.error(error);
            modalContent.innerHTML += `Failed to load`;
        }    
    })
});


async function fetchEditPersonalInformation() {
    const response = await fetch(`../../../BackEnd/templates/admin/fetchEditPersonalInformation.php`);

    let data;
    try {
        data = await response.text();
    }
    catch{
        throw new Error('Cannot proccess response');
    }
    if(!response.ok) {
        throw new Error(`HTTP error: ${response.status}`);
    }
    return data;
}

async function fetchEditProfilePicture() {
    const response = await fetch(`../../../BackEnd/templates/admin/fetchEditProfilePicture.php`);

    let data;
    try {
        data = await response.text();
    }
    catch{
        throw new Error('Cannot proccess response');
    }
    if(!response.ok) {
        throw new Error(`HTTP error: ${response.status}`);
    }
    return data;
}

async function fetchEditAddress() {
    const response = await fetch(`../../../BackEnd/templates/admin/fetchEditAddress.php`);

    let data;
    try {
        data = await response.text();
    }
    catch{
        throw new Error('Cannot proccess response');
    }
    if(!response.ok) {
        throw new Error(`HTTP error: ${response.status}`);
    }
    return data;
}

async function fetchEditIdentifiers() {
    const response = await fetch(`../../../BackEnd/templates/admin/fetchEditIdentifiers.php`);

    let data;
    try {
        data = await response.text();
    }
    catch{
        throw new Error('Cannot proccess response');
    }
    if(!response.ok) {
        throw new Error(`HTTP error: ${response.status}`);
    }
    return data;
}