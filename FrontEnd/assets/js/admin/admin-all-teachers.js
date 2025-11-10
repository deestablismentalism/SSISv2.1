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
        
    });

    // Edit button handlers
    document.addEventListener('click', async function(e) {
        // View button handler
        if (e.target.classList.contains('view-teacher-btn') || e.target.closest('.view-teacher-btn')) {
            const button = e.target.classList.contains('view-teacher-btn') ? e.target : e.target.closest('.view-teacher-btn');
            const staffId = button.dataset.staffId;
            
            if (!staffId) return;
            
            modal.style.display = 'block';
            modalContent.innerHTML = loadingText;
            
            try {
                const data = await fetchViewTeacherInfo(staffId);
                modalContent.innerHTML = data;
                close(modal);
            } catch(err) {
                alert(`Error: ${err.message}`);
                modal.style.display = 'none';
            }
        }
        
        if (e.target.classList.contains('edit-teacher-btn') || e.target.closest('.edit-teacher-btn')) {
            const button = e.target.classList.contains('edit-teacher-btn') ? e.target : e.target.closest('.edit-teacher-btn');
            const staffId = button.dataset.staffId;
            
            if (!staffId) return;
            
            modal.style.display = 'block';
            modalContent.innerHTML = loadingText;
            
            try {
                const data = await fetchEditTeacherForm(staffId);
                modalContent.innerHTML = data;
                close(modal);
                
                const form = document.getElementById('edit-teacher-form');
                const submitButton = form.querySelector('button[type="submit"]');
                
                form.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    submitButton.disabled = true;
                    submitButton.style.backgroundColor = 'gray';
                    
                    const formData = new FormData(form);
                    const result = await postEditTeacherInfo(formData);
                    
                    if (!result.success) {
                        alert(result.message);
                        submitButton.disabled = false;
                        submitButton.style.backgroundColor = '';
                    } else {
                        alert(result.message);
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    }
                });
            } catch(err) {
                alert(`Error: ${err.message}`);
                modal.style.display = 'none';
            }
        }
    });
});

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

async function fetchEditTeacherForm(staffId) {
    const response = await fetch(`../../../BackEnd/templates/admin/fetchEditTeacherForm.php?staff_id=${staffId}`);
    
    if (!response.ok) {
        throw new Error('Failed to load edit form');
    }
    
    let data;
    try {
        data = await response.text();
    } catch {
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

async function postEditTeacherInfo(formData) {
    try {
        const response = await fetch(`../../../BackEnd/api/admin/postEditTeacherInfo.php`, {
            method: 'POST',
            body: formData
        });

        let data;
        try {
            data = await response.json();
        } catch {
            throw new Error('Invalid json response');
        }
        
        if (!response.ok) {
            return {
                success: false,
                message: data.message || `HTTP error: ${response.status}`,
                data: null
            };
        }
        return data;
    } catch(error) {
        return {
            success: false,
            message: error.message || `There was an unexpected error`,
            data: null
        };
    }
}

async function fetchViewTeacherInfo(staffId) {
    const response = await fetch(`../../../BackEnd/templates/admin/fetchViewTeacherInfoModal.php?staff_id=${staffId}`);
    
    if (!response.ok) {
        throw new Error('Failed to load teacher information');
    }
    
    let data;
    try {
        data = await response.text();
    } catch {
        throw new Error('Invalid response');
    }
    return data;
}