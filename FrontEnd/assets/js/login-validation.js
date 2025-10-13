document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("login-form");
    const errorMessageContainer = document.querySelector('.error-msg');
    const errorMessage = document.getElementById('em-login');
    const button = form.querySelector("button[type=submit]");
    
    form.addEventListener("submit", async function(event) {
        event.preventDefault();

        button.disabled = true;
        button.style.backgroundColor = 'gray';
        Loader.show();
        
        const formData = new FormData(form);
        try {
            const result = await postLoginVerify(formData);

            if(!result.success) {
                errorMessageContainer.classList.add('show');
                errorMessage.innerHTML = result.message;
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
            errorMessageContainer.classList.add('show');
            errorMessage.innerHTML = err.message;
            console.error(err);
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
            //throw when invalid response
            throw new Error('Invalid response');
        }
        // HTTP-level error
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
