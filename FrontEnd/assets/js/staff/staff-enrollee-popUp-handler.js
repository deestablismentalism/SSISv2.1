import {close, modalHeader, loadingText} from '../utils.js';
document.addEventListener('DOMContentLoaded',async function (){ 
    const modal = document.getElementById('enrolleeModal');
    const modalContent = document.querySelector('.modal-content');
    let initialModalContent = '';
    document.addEventListener('click', async function (e) { 
        if (!e.target.classList.contains('view-button')) return;
        const enrolleeId = e.target.getAttribute('data-id');
        //MODAL DISPLAY
        modal.style.display = 'block';
        modalContent.innerHTML = loadingText; // Show loader while fetching data
        //FETCH
        const result = await fetchEnrolleeInfo(enrolleeId);
        if(!result.success) {
            modalContent.innerHTML = modalHeader();
            modalContent.innerHTML += result.message;
            close(modal);
        }
        else {
            modalContent.innerHTML = modalHeader();
            modalContent.innerHTML += result.data;
            modalContent.innerHTML += `
            <button class="accept-btn" data-action="accept" data-id="${enrolleeId}">✓ Enroll Student</button>
            <button class="reject-btn" data-action="deny" data-id="${enrolleeId}">✗ Deny Enrollment</button>
            <button class="toFollow-btn" data-action="toFollow" data-id="${enrolleeId}">⚠ Flag for Follow-up</button>
            `;
            initialModalContent = modalContent.innerHTML;
            close(modal);
        }     
    })
    //DELEGATED MODAL CLICK LISTENER
    modalContent.addEventListener('click', async function(e){
        if (e.target.matches('.toFollow-btn, .reject-btn, .accept-btn')) {
            const enrolleeId = e.target.getAttribute('data-id');
            const action = e.target.getAttribute('data-action');
            let status = {
                "toFollow" : 4,
                "deny" : 2,
                "accept" :1
            }[action];
            modalContent.innerHTML = modalHeader(true);
            modalContent.innerHTML += `
                <form id="enrollment-action-form">
                    <input type="hidden" name="id" value="${enrolleeId}">
                    <input type="hidden" name="status" value="${status}">
                    <p>State Remarks ${status === 1 ? '(Optional)' : ''}</p>
                    <textarea id="description" class="description-box" name="remarks" rows="6" cols="40" 
                        placeholder="${status === 1 ? 'Optional: Add notes about this enrollment...' : 'Explain reason for this action...'}" 
                        ${status !== 1 ? 'required' : ''}></textarea><br>
                    <button type="submit">Submit</button>
                </form>
            `;
            close(modal);
            const form = modalContent.querySelector('#enrollment-action-form');
            form.addEventListener('submit',submitForm);
        }
    });
    //HANDLE BACK BUTTON
    modalContent.addEventListener('click', async function(e){
        if(e.target.classList.contains('modal-back-btn')) {
            modalContent.innerHTML = initialModalContent;
            close(modal);
        }
    })
    //HANDLE FORM SUBMISSION  
    let isSubmitting = false;
     async function submitForm(e) {
        console.log(e.target.id);
        //EARLY RETURN
        e.preventDefault(); 
        //EARLY RETURN
        if(isSubmitting) return;
        isSubmitting = true;
        const form = e.target; 
        const submitButton = form.querySelector('button[type=submit]'); 
        //RETURN
        if(!submitButton) return;
        //DISABLE BUTTONS
        submitButton.disabled = true;   
        submitButton.style.backgroundColor = 'gray';
        //FORM VALUES AND POST
        const formData = new FormData(form);
        const result = await postUpdateEnrolleeStatus(formData);
        if(!result.success) {
            Notification.show({
                type: result.success ? "error" : "error",
                title: result.success ? "error" : "Error",
                message: result.message
            });
            form.reset();
            isSubmitting = false;
            submitButton.disabled = false;
            submitButton.style.backgroundColor = '#003366D5';
        }
        else {
            Notification.show({
                type: result.success ? "success" : "error",
                title: result.success ? "Success" : "Error",
                message: result.message
            });
            setTimeout(()=>{window.location.reload()}, 1000);
        }
    }
    //BACK TO ROLE'S PAGE
    const backButton = document.getElementById('back-button');
    const staffType = document.querySelector('.user-type');
    backButton.addEventListener('click', function() {
        const staffValue = staffType.textContent.trim().toLowerCase();
        if(staffValue == 'admin') {
            window.location.href = '../admin/admin_dashboard.php';
        }
        else if (staffValue == 'teacher') {
            window.location.href = '../teacher/teacher_dashboard.php';
        }
        else {
            window.location.href = '../No_Page.php';
        }
    });
});
const TIME_OUT = 20000;
async function fetchEnrolleeInfo(enrolleeId) {
    try {
        const controller = new AbortController();
        const timeoutId = setTimeout(()=>controller.abort(),TIME_OUT);
        const response = await fetch(`../../../BackEnd/templates/staff/fetchEnrolleeInfo.php?id=`+ encodeURIComponent(enrolleeId),{
            signal: controller.signal
        });
        clearTimeout(timeoutId);
        let data;
        try {
            data = await response.text();
        }
        catch {
            throw new Error('Invalid response');
        }
        return {
            success: true,
            message: `Fetch was successful`,
            data: data
        };
    }
    catch(error) {
        if(error.name === "AbortError") {
            return {
                success: false,
                message: `Request timeout: Server took too long to response`,
                data: null
            };
        }
        return {
            success: false,
            message: error.message || `Something went wrong`,
            data: null
        };
    }
}
async function postUpdateEnrolleeStatus(formData) {
    try {
        const controller = new AbortController();
        const timeoutId = setTimeout(()=>controller.abort(),TIME_OUT);
        const response = await fetch(`../../../BackEnd/api/staff/postUpdateEnrolleeStatus.php`, {
            signal: controller.signal,
            method: 'POST',
            body: formData
        });
        clearTimeout(timeoutId);
        let data;
        try {
            data = await response.json();
        }
        catch {
            throw new Error('Invalid response');
        }
        if(!response.ok) {
            return {
                success: false,
                message: data.message || `HTTP Error: ${response.status}`,
                data: null
            };
        }
        return data;
    }
    catch(error) {
        if(error.name === "AbortError") {
            return {
                success: false,
                message: `Request timeout: Server took too long to responsd`,
                data: null
            };
        }
        return {
            success: false,
            message: error.message || `Something went wrong`,
            data: null
        };
    }
}
async function fetchPendingStudents() {
    try {
        const controller = new AbortController();
        const timeoutId = setTimeout(()=> controller.abort(),TIME_OUT);
        const response = await fetch(`../../../BackEnd/api/staff/fetchPendingStudents.php`,{
            signal: controller.signal
        });
        clearTimeout(timeoutId);
        let data;
        try {
            data = await response.json();
        }
        catch{
            throw new Error('Invalid Response');
        }
        if(!response.ok) {
            return {
                success: false,
                message: data.messsage || `HTTP ERROR: ${response.status}`,
                data: null
            };
        }
        return data;
    }
    catch(error) {
        if(error.name === "AbortError") {
            return {
                success: false,
                message: `Request timeout: Server took too long to response`,
                data: null
            };
        }
        return {
            success: false,
            message: error.message || `Something went wrong`,
            data: null
        };
    }
}