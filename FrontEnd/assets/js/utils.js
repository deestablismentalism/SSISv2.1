export function close(modal) { // global close util
    const closeButton = modal.querySelector('.close');
    if(closeButton && !closeButton.hasAttribute('data-listener-added')) {
        closeButton.setAttribute('data-listener-added', 'true');
        closeButton.addEventListener('click', function(){
            modal.style.display = 'none';
            modal.classList.remove('show');
        });
    }
    
    // Also close on outside click
    if (!modal.hasAttribute('data-outside-listener-added')) {
        modal.setAttribute('data-outside-listener-added', 'true');
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.style.display = 'none';
                modal.classList.remove('show');
            }
        });
    }
}
export const loadingText = `
    <div class="loading-container" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 3rem; min-height: 200px;">
        <div class="spinner" style="width: 48px; height: 48px; border: 4px solid #f3f3f3; border-top: 4px solid #3e9ec4; border-radius: 50%; animation: spin 1s linear infinite;"></div>
        <p style="margin-top: 1rem; color: #666; font-size: 1rem;">Loading...</p>
    </div>
    <style>
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
`; //global loading pop up modal
export function modalHeader(backButton = false) {
    let hasBackButton = backButton === true ? '<a href="staff_pending_enrollments.php" class="modal-back-btn">Back to Pending Enrollments</a>' : '';
    const headerContent = `${hasBackButton}<span class="close">&times;</span><br>`;   
    return headerContent;
}
export function generateOptions(data,selectContainer) {
    if(data) {
        data.forEach(data=>{
            const createOption = document.createElement('option');
            createOption.value = data.code;
            createOption.textContent = data.name;
            selectContainer.append(createOption);
        })
    }
}
export async function fetchAddress(url) {
    const TIME_OUT = 10000;
    const controller = new AbortController();
    const timeoutId = setTimeout(()=> controller.abort(),TIME_OUT);
    try {
        const response = await fetch(url,{
            signal: controller.signal
        });
        clearTimeout(timeoutId);
        let data;
        try {
            data = await response.json();
        }
        catch {
            throw new Error('Invalid response');
        }
        if(!response.ok) throw new Error(`Failed to fetch: ${url}`);
        if (!data || !Array.isArray(data) || data.length === 0) throw new Error('Data recieved are either empty or non-array');
        return data;
    }
    catch(error) {
        clearTimeout(timeoutId);
        if(error.name === 'AbortError') {
            throw new Error(`Request timeout: Server took too long to respond! Exited after ${TIME_OUT / 1000} seconds`);
        }
        throw error;
    }
}
export async function getRegions() {
    return await fetchAddress(`https://psgc.gitlab.io/api/regions/`);
}
export async function getProvinces(regionCode) {
    return await fetchAddress(`https://psgc.gitlab.io/api/regions/${regionCode}/provinces`);
}
export async function getCities(provinceCode) {
    return await fetchAddress(`https://psgc.gitlab.io/api/provinces/${provinceCode}/cities-municipalities`);
}
export async function getBarangays(cityCode) {
    return await fetchAddress(`https://psgc.gitlab.io/api/cities-municipalities/${cityCode}/barangays`);
}
export function capitalizeFirstLetter(element) {
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
export const ValidationUtils = {
    emptyError: "This field is required",
    notNumber: "This field must be a number",
    isEmpty(element) {
        return !element.value.trim();
    },
    clearError(errorElement, childElement) {
        if (!childElement) {
            return;
        }
        let container = null;
        
        // Try multiple strategies to find the error container
        // Strategy 1: Check parent element
        if (childElement.parentElement) {
            container = childElement.parentElement.querySelector('.error-msg');
        }
        
        // Strategy 2: Use closest to find parent with error-msg
        if (!container) {
            const parentDiv = childElement.closest('div');
            if (parentDiv) {
                container = parentDiv.querySelector('.error-msg');
            }
        }
        
        // Strategy 3: Check parent's parent
        if (!container && childElement.parentElement?.parentElement) {
            container = childElement.parentElement.parentElement.querySelector('.error-msg');
        }
        
        // Strategy 4: Check parent's parent's parent
        if (!container && childElement.parentElement?.parentElement?.parentElement) {
            container = childElement.parentElement.parentElement.parentElement.querySelector('.error-msg');
        }
        
        // Strategy 5: Check parent's parent's parent's parent (for deeply nested elements)
        if (!container && childElement.parentElement?.parentElement?.parentElement?.parentElement) {
            container = childElement.parentElement.parentElement.parentElement.parentElement.querySelector('.error-msg');
        }
        
        // Strategy 6: Search up the DOM tree using closest
        if (!container) {
            let current = childElement.parentElement;
            let depth = 0;
            while (current && depth < 10) {
                container = current.querySelector('.error-msg');
                if (container) break;
                current = current.parentElement;
                depth++;
            }
        }
        
        if (!container) {
            // If no error container found, just reset the border and return
            if (childElement && childElement.style) {
                childElement.style.border = "1px solid #616161";
            }
            return;
        }
        
        const errorSpan = container.querySelector('.' + errorElement);

        if (container.classList) {
            container.classList.remove('show');
        }
        if (childElement && childElement.style) {
            childElement.style.border = "1px solid #616161";
        }
        if (errorSpan) {
            errorSpan.innerHTML = '';
        }
    },
    errorMessages(errorElement, message, childElement) {
        if (!childElement) {
            return false;
        }
        let container = null;
        
        // Try multiple strategies to find the error container
        // Strategy 1: Check parent element
        if (childElement.parentElement) {
            container = childElement.parentElement.querySelector('.error-msg');
        }
        
        // Strategy 2: Use closest to find parent with error-msg
        if (!container) {
            const parentDiv = childElement.closest('div');
            if (parentDiv) {
                container = parentDiv.querySelector('.error-msg');
            }
        }
        
        // Strategy 3: Check parent's parent
        if (!container && childElement.parentElement?.parentElement) {
            container = childElement.parentElement.parentElement.querySelector('.error-msg');
        }
        
        // Strategy 4: Check parent's parent's parent
        if (!container && childElement.parentElement?.parentElement?.parentElement) {
            container = childElement.parentElement.parentElement.parentElement.querySelector('.error-msg');
        }
        
        // Strategy 5: Check parent's parent's parent's parent
        if (!container && childElement.parentElement?.parentElement?.parentElement?.parentElement) {
            container = childElement.parentElement.parentElement.parentElement.parentElement.querySelector('.error-msg');
        }
        
        // Strategy 6: Search up the DOM tree using closest
        if (!container) {
            let current = childElement.parentElement;
            let depth = 0;
            while (current && depth < 10) {
                container = current.querySelector('.error-msg');
                if (container) break;
                current = current.parentElement;
                depth++;
            }
        }
        
        if (!container) {
            // If no error container found, just set the border and return
            if (childElement && childElement.style) {
                childElement.style.border = "1px solid red";
            }
            return false;
        }
        
        const errorSpan = container.querySelector('.' + errorElement);
        
        if (container.classList) {
            container.classList.add('show');
        }
        if (childElement && childElement.style) {
            childElement.style.border = "1px solid red";
        }
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
        disabledInfo: true,
        addressInfo: true,
        previousSchool: true
    },
    isFormValid() {
        return Object.values(this.validationState).every(state => state === true);
    }
};
export function preventInputByRegex(element, regex, errorCallback = null) {
    element.addEventListener('beforeinput', function(e) {
        if (e.inputType === 'deleteContentBackward' || 
            e.inputType === 'deleteContentForward' ||
            e.inputType === 'deleteByCut') {
            return;
        }

        if (e.data && regex.test(e.data)) {
            e.preventDefault();
            
            if (errorCallback && typeof errorCallback === 'function') {
                errorCallback(element, e.data);
            }
            return;
        }

        if (e.inputType === 'insertText' || e.inputType === 'insertFromPaste') {
            const currentValue = element.value;
            const start = element.selectionStart;
            const end = element.selectionEnd;
            const newValue = currentValue.substring(0, start) + (e.data || '') + currentValue.substring(end);
            
            if (regex.test(newValue)) {
                e.preventDefault();
                
                if (errorCallback && typeof errorCallback === 'function') {
                    errorCallback(element, e.data);
                }
            }
        }
    });

    element.addEventListener('paste', function(e) {
        const pastedText = (e.clipboardData || window.clipboardData).getData('text');
        
        if (regex.test(pastedText)) {
            e.preventDefault();
            
            if (errorCallback && typeof errorCallback === 'function') {
                errorCallback(element, pastedText);
            }
        }
    });
}

export function preventCharactersByRegex(element, regex, errorCallback = null) {
    const sanitizeValue = function(e) {
        const currentValue = element.value;
        const cursorPos = element.selectionStart;
        const cursorEnd = element.selectionEnd;
        
        const cleanedValue = currentValue.replace(regex, '');
        
        if (cleanedValue !== currentValue) {
            const removedChars = currentValue.replace(cleanedValue, '');
            
            element.value = cleanedValue;
            
            const charsRemovedBeforeCursor = currentValue.substring(0, cursorPos).length - 
                                            currentValue.substring(0, cursorPos).replace(regex, '').length;
            const newCursorPos = Math.max(0, cursorPos - charsRemovedBeforeCursor);
            
            element.setSelectionRange(newCursorPos, newCursorPos);
            
            if (errorCallback && typeof errorCallback === 'function') {
                errorCallback(element, removedChars);
            }
        }
    };

    element.addEventListener('input', sanitizeValue);

    element.addEventListener('paste', function(e) {
        setTimeout(() => sanitizeValue(e), 0);
    });

    const initialValue = element.value;
    if (initialValue && regex.test(initialValue)) {
        element.value = initialValue.replace(regex, '');
    }
}

export function limitCharacters(element, maxLength) {
    element.addEventListener('beforeinput', function(e) {
        if (e.inputType === 'deleteContentBackward' || 
            e.inputType === 'deleteContentForward' ||
            e.inputType === 'deleteByCut') {
            return;
        }

        const currentValue = element.value;
        const start = element.selectionStart;
        const end = element.selectionEnd;
        const selectedLength = end - start;
        const availableSpace = maxLength - (currentValue.length - selectedLength);

        if (e.data && availableSpace <= 0) {
            e.preventDefault();
            return;
        }

        if (e.inputType === 'insertText' || e.inputType === 'insertFromPaste') {
            const newValue = currentValue.substring(0, start) + (e.data || '') + currentValue.substring(end);
            
            if (newValue.length > maxLength) {
                e.preventDefault();
            }
        }
    });

    element.addEventListener('paste', function(e) {
        e.preventDefault();
        
        const pastedText = (e.clipboardData || window.clipboardData).getData('text');
        const currentValue = element.value;
        const start = element.selectionStart;
        const end = element.selectionEnd;
        const selectedLength = end - start;
        const availableSpace = maxLength - (currentValue.length - selectedLength);
        
        if (availableSpace > 0) {
            const textToInsert = pastedText.substring(0, availableSpace);
            const newValue = currentValue.substring(0, start) + textToInsert + currentValue.substring(end);
            element.value = newValue;
            
            const newCursorPos = start + textToInsert.length;
            element.setSelectionRange(newCursorPos, newCursorPos);
        }
    });

    const initialValue = element.value;
    if (initialValue.length > maxLength) {
        element.value = initialValue.substring(0, maxLength);
    }
}

