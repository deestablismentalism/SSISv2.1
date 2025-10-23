document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("login-form");
    const button = form.querySelector("button[type=submit]");
    const phoneNumber = document.getElementById("phone_number");
    
    // Phone number validation
    phoneNumber.addEventListener('keydown', function(e) {
        const key = e.key;
        const currentLength = phoneNumber.value.length;
        
        // Allow control keys
        const allowedKeys = ['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab'];
        if (allowedKeys.includes(key)) {
            return;
        }
        
        // Check if key is a number
        if(isNaN(key) || key === ' ') {
            e.preventDefault();
            return;
        }
        
        // Check if length would exceed 11
        if(currentLength >= 11) {
            e.preventDefault();
            return;
        }
    });
    
    form.addEventListener("submit", async function(event) {
        event.preventDefault();

        button.disabled = true;
        button.style.backgroundColor = 'gray';
        Loader.show();
        
        const formData = new FormData(form);
        try {
            const result = await postLoginVerify(formData);

            if(!result.success) {
                Notification.show({
                    type: result.success ? "error" : "error",
                    title: result.success ? "Error" : "Error",
                    message: result.message
                });
                button.disabled = false;
                button.style.backgroundColor = '#E1A23C';
            }
            else {
                const ifUser = parseInt(result.session.User?.['User-Type']);
                const ifStaff = parseInt(result.session.Staff?.['Staff-Type']); 

                if (ifUser && ifUser === 3) {
                        window.location.href =  './pages/user/user_enrollees.php';
                }
                else  {
                    if (ifStaff && ifStaff === 2) {
                        window.location.href = './pages/teacher/Teacher_Dashboard.php';
                    }
                    else if (ifStaff && ifStaff === 1){
                        window.location.href = './pages/admin/admin_dashboard.php';
                    }
                    else {
                        window.location.href = '../pages/No_Page.php';
                    }
                }
            }
        }
        catch(err) {
            Notification.show({
                type: result.success ? "error" : "error",
                title: result.success ? "Error" : "Error",
                message: err.message
            });
        } 
        finally {
            Loader.hide();
        }
    });
});

async function postLoginVerify(formData) {
    try {
        const response = await fetch(`../BackEnd/api/postLoginVerify.php`, {
        method: 'POST',
        body: formData
        });
        let data;
        try {
            data = await response.json();
        }
        catch {
            throw new Error('Invalid response');
        }
        if (!response.ok) {
            return {
                success: false,
                message: data.message || `HTTP Error: ${response.status}`,
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
