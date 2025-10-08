document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("change-password-form");
    
    form.addEventListener("submit", function(event) {
        event.preventDefault();
        Loader.show();

        const formData = new FormData(form);
        
        fetch("./../BackEnd/common/postChangePassword.php", {
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
                Notification.show({
                    type: data.success ? "success" : "error",
                    title: data.success ? "Success" : "error",
                    message: data.message
                });
                form.reset();
            } else if (!data.success){
                Notification.show({
                    type: data.success ? "error" : "error",
                    title: data.success ? "Error" : "Error",
                    message: data.message
                });
            }
        })
        .catch(error => {
            console.error("Fetch Error:", error);
            alert("Change Password failed. Please check the console for details.");
        })
        .finally(() => {
            Loader.hide();
        });
    });
});
