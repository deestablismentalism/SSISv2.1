import{close, modalHeader, loadingText} from '../utils.js';
document.addEventListener('DOMContentLoaded',function() {
    const registerButton = document.getElementById('register-teacher-btn');
    const modal = document.getElementById('all-teachers-modal');
    const modalContent = document.getElementById('all-teachers-modal-content');
    
    registerButton.addEventListener('click', async function() {
        modal.style.display = 'block';
        modalContent.innerHTML = loadingText;

        try {
            const data = await fetchRegisterTeacherForm();
            modalContent.innerHTML = modalHeader() + data;
            close(modal);

            const form = document.getElementById('teacher-registration-form');
            const button = modal.querySelector("button[type=submit]");

            form.addEventListener('submit',async function(e){
                e.preventDefault();
                button.disabled = true;
                button.style.backgroundColor = 'gray';

                const formData = new FormData(form);

                const result = await postRegisterTeacher(formData);

                if(!result.success) {
                    alert(result.message);
                }
                else {
                    alert(result.message);
                    setTimeout(()=>{
                        window.location.reload();
                    },1000);
                }
            })
        }
        catch(err) {
            alert(`Error: ${err.message}`);
        }
        
    })
})
async function fetchRegisterTeacherForm() {
    const response = await fetch(`../../../BackEnd/templates/admin/fetchRegisterTeacherForm.php`);

    let data;
    try {
        data = await response.text();
    }
    catch {
        throw new Error('Invalid response');
    }
    return data;
}
async function postRegisterTeacher(formData) {
    try {
        const response = await fetch(`../../../BackEnd/api/admin/postStaffRegistration.php`, {
            method: 'POST',
            body: formData
        });

        let data;
        try {
            data = await response.json();
        }
        catch {
            throw new Error('Invalid json response');
        }
        if(!response.ok) {
            return {
                success: false,
                message: data.message || `HTTP error: ${response.status}`,
                data: null
            };
        }
        return data;
    }
    catch(error) {
        return {
            success: false,
            message: error.message || `There was an unexpected error`,
            data: null
        };
    }
}