document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("login-form");
    const errorMessageContainer = document.querySelector('.error-msg');
    const errorMessage = document.getElementById('em-login');
    
    form.addEventListener("submit", function(event) {
        event.preventDefault();
        Loader.show();
        
        const formData = new FormData(form);
        
        fetch("../BackEnd/common/postLoginVerify.php", {
            method: "POST", 
            body: formData,
            headers: {
                'Accept' : 'application/json'
            }
        })
        .then(response => {
            return response.text().then(text => {
                console.log('Raw response:', text);
                try {
                    return JSON.parse(text);
                } catch (e) {
                    throw new Error('Invalid JSON response: ' + text);
                }
            });
        })
        .then(data => {
            if (data.success) {
                console.log(data);
                form.reset();
                const ifUser = data.session.User?.['User-Type'];
                const ifStaff = data.session.Staff?.['Staff-Type']; 

                if (ifUser && ifUser === "3") {
                    window.location.href =  './pages/user/user_enrollees.php';
                }
                else  {
                    if (ifStaff && ifStaff === "2") {
                        window.location.href = './pages/teacher/Teacher_Dashboard.php';
                    }
                    else if (ifStaff && ifStaff === "1"){
                        window.location.href = './pages/admin/admin_dashboard.php';
                    }
                    else {
                        window.location.href = '../pages/No_Page.php';
                    }
                }
            } 
            else if (!data.success){
                errorMessageContainer.classList.add('show');
                errorMessage.innerHTML = data.message;
            }
        })
        .catch(error => {
            console.error("Fetch Error:", error);
            alert("An error occured. Please try again.");
        })
        .finally(() => {
            Loader.hide();
        });
    });
});
