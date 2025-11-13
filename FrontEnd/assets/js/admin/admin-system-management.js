document.addEventListener('DOMContentLoaded',function(){
    const editBtn = document.getElementById('edit-btn');
    const editMode = document.getElementById('edit-mode');
    const viewMode = document.getElementById('view-mode');
    //EDIT BUTTON LISTENER
    editBtn.addEventListener('click',function(){
        toggleEditMode();
    });
    //FORM CANCEL EVENT LISTENER
    editMode.addEventListener('click',function(e){
       if(e.target.id === 'cancel-btn') {
            toggleViewMode();
       }
    });
    isSubmitting = false;
    editMode.addEventListener('submit', async function(e){
        e.preventDefault();
        const form = document.getElementById('school-year-details-form');
        if(!form) return;
        const button = editMode.querySelector('button[type="submit"]');
        //DISABLE DOUBLE CLICKS
        if(isSubmitting) return;
        isSubmitting = true;
        button.disabled = true
        button.style.backgroundColor = 'gray';
        //FORM SUBMISSION 
        const formData = new FormData(form);
        const result = await postSchoolYearDetails(formData);
        if(!result.success) {
            Notification.show({
                type: result.success ? "error" : "error",
                title: result.success ? "Error" : "Error",
                message: result.message
            });
            isSubmitting = false;
            button.disabled = false;
            button.style.backgroundColor = 'green';
        }
        else {
            Notification.show({
                type: result.success ? "success" : "error",
                title: result.success ? "Success" : "Error",
                message: result.message
            });
            setTimeout(()=> window.location.reload(),1000);
        }
    })
    function toggleEditMode() {
        viewMode.style.display = 'none';
        editMode.style.display = 'block';
    }
    function toggleViewMode() {
        editMode.style.display = 'none';
        viewMode.style.display = 'block';
    }
})
const TIME_OUT = 20000;
async function postSchoolYearDetails(formData) {
    const controller = new AbortController();
    const timeoutId = setTimeout(()=>controller.abort(),TIME_OUT);
    try {
        const response = await fetch(`../../../BackEnd/api/admin/postSchoolYearDetails.php`,{
            signal: controller.signal,
            method: 'POST',
            body: formData
        });
        clearTimeout(timeoutId);
        let data;
        try {
            data = await response.json();
        }
        catch {
            throw new Error('Invalid response')
        }
        if(!response.ok) {
            return {
                success: false,
                message: data.message || `HTTP ERROR: ${response.status}`,
                data: null
            };
        }
        return data;
    }
    catch(error) {
        if(error.name === "AbortError")  {
            return {
                success: false,
                message: `Response timeout. Server took too long to response: Took ${TIME_OUT/1000} seconds`,
                data: null
            };
        }
        return{
            success: false,
            message: `Something went wrong`,
            data: null
        };
    }
}