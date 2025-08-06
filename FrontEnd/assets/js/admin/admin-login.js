document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("admin-login-form");
    
    form.addEventListener("submit", function(event) {
        event.preventDefault();
        
        const formData = new FormData(form);
        
        fetch("../server_side/admin/post_admin_login.php", {
            method: "POST", 
            body: formData,
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
                form.reset();
                window.location.href = " ../pages/adminPages/Admin_Dashboard.php";
            } else if (!data.success){
                alert(data.message);
            }
        })
        .catch(error => {
            console.error("Fetch Error:", error);
            alert("An error occured. Please try again.");
        });
    });
});
