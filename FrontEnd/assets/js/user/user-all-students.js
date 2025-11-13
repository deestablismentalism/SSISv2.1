document.addEventListener('DOMContentLoaded',function(){
    const reenrollBtn = document.querySelector('.reenroll-btn');
    const TO_ACTIVE = 1;
    if(reenrollBtn) {
        let isSubmitting = false;
        reenrollBtn.addEventListener('click',function(e){
            e.preventDefault();
            const studentId = e.target.getAttribute('data-student');
            const formData = new FormData();
            formData.append('student-id',studentId);
            formData.append('active-status',TO_ACTIVE);
            if(isSubmitting) return;
            isSubmitting = true;
            reenrollBtn.disabled = true;
            fetch(`../../../BackEnd/api/user/postReenrollStudent.php`,{
                method: 'POST',
                body: formData
            })
            .then(response=> {
                if(!response.ok) {
                    return new Error(`HTTP ERROR: Request responded with status ${response.status}`);
                }
                return response.json();
            })
            .then(data=>{
                if(!data.success) {
                    alert(data.message || `Failed to re-enroll student`);
                    isSubmitting = false;
                    reenrollBtn.disabled = false;
                }
                else {
                    alert(data.message || `Student successfully re-enrolled`);
                    setTimeout(()=>window.location.reload(),1000);
                }
            })
            .catch(error=>{
                alert(error.message || `Something went wrong`);
                isSubmitting = false;
                reenrollBtn.disabled = false;
            })
        });
    }
})