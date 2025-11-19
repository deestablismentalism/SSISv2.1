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
                    Notification.show({
                        type: result.success ? "success" : "error",
                        title: result.success ? "Success" : "Error",
                        message: result.message
                    });
                }
                else {
                    Notification.show({
                        type: result.success ? "success" : "error",
                        title: result.success ? "Success" : "Error",
                        message: result.message
                    });
                    setTimeout(()=>{
                        window.location.reload();
                    },1000);
                }
            })
        }
        catch(err) {
            Notification.show({
                type: "error",
                title: "Error",
                message: `Error: ${err.message}`
            });
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
                Notification.show({
                    type: data.success ? "success" : "error",
                    title: data.success ? "Success" : "error",
                    message: `Error: ${err.message}`
                });
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
                            Notification.show({
                                type: result.success ? "success" : "error",
                                title: result.success ? "Success" : "error",
                                message: result.message
                            });
                        submitButton.disabled = false;
                        submitButton.style.backgroundColor = '';
                    } else {
                        Notification.show({
                            type: result.success ? "success" : "error",
                            title: result.success ? "Success" : "Error",
                            message: result.message
                        });
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    }
                });
            } catch(err) {
                Notification.show({
                    type: "error",
                    title: "Error",
                    message: `Error: ${err.message}`
                });
                modal.style.display = 'none';
            }
        }
        
        // Archive button handler
        if (e.target.classList.contains('archive-staff-btn') || e.target.closest('.archive-staff-btn')) {
            const button = e.target.classList.contains('archive-staff-btn') ? e.target : e.target.closest('.archive-staff-btn');
            const staffId = button.dataset.staffId;
            
            if (!staffId) return;
            
            if (confirm('Are you sure you want to archive this staff member?')) {
                try {
                    const result = await archiveStaff(staffId);
                    
                    Notification.show({
                        type: result.success ? "success" : "error",
                        title: result.success ? "Success" : "Error",
                        message: result.message
                    });
                    
                    if (result.success) {
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    }
                } catch(err) {
                    Notification.show({
                        type: "error",
                        title: "Error",
                        message: `Error: ${err.message}`
                    });
                }
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

async function fetchEditTeacherForm(staffId) {
    const response = await fetch(`../../../BackEnd/templates/admin/fetchEditTeacherForm.php?staff_id=${staffId}`);
    
    if (!response.ok) {
        throw new Error('Failed to load edit teacher form');
    }
    
    let data;
    try {
        data = await response.text();
    } catch {
        throw new Error('Invalid response');
    }
    return data;
}

async function archiveStaff(staffId) {
    try {
        const formData = new FormData();
        formData.append('id', staffId);
        
        const response = await fetch(`../../../BackEnd/api/admin/postArchiveStaff.php`, {
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