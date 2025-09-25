document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("login-form");
    
    const errorMessageContainer = document.querySelector('.error-msg');
    const errorMessage = document.getElementById('em-login');
    form.addEventListener("submit", function(event) {
        event.preventDefault();
        
        const formData = new FormData(form);

        //TODO: use the async function below 
        postLoginVerify(formData).then(data=>{
                const ifUser = data.session.User?.['User-Type'];
                const ifStaff = data.session.Staff?.['Staff-Type']; 

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
        }).catch(err=>{
            errorMessageContainer.classList.add('show');
            errorMessage.innerHTML = err.message;
            console.error(err);
        })
    });
});

async function postLoginVerify(formData) {

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
    // HTTP-level error
    if (!response.ok) {
        throw new Error(data.message || `HTTP error: ${response.status}`);
    }

    // API-level error
    if (!data.success) {
        throw new Error(data.message || "Something went wrong");
    }

    return data;
}
