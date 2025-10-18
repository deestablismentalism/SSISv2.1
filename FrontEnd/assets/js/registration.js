document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("registration-form");
    
    form.addEventListener("submit", async function(e) {
        e.preventDefault();        
        Loader.show();
        
        const formData = new FormData(form);
        const result = await postRegistrationForm(formData);

        if(result.success) {
            Notification.show({
                type: result.success ? "success" : "error",
                title: result.success ? "Success" : "error",
                message: result.message
            });

            setTimeout(()=>{
                window.location.href = './Login.php';
            },1000);
        }
        else {
            //TODO: switch to error message container
            Notification.show({
                type: result.success ? "Error" : "error",
                title: result.success ? "Error" : "Error",
                message: result.message
            });
            Loader.hide();
        }
        
    });
});
async function postRegistrationForm(formData) {
    try {
            const response = await fetch(`/SSISV2.1/BackEnd/api/postRegistrationForm.php`,{
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
        if(!response.ok) {
            return {
                success: false,
                httpcode: response.status,
                message: data.message || `HTTP ERROR: ${response.status}`,
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
