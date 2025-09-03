document.addEventListener('DOMContentLoaded', function() {

    const editBtn = document.getElementById('edit-section-btn');
    const modal = document.getElementById('admin-view-section-edit-modal');
    const modalContent = document.getElementById('admin-view-section-edit-content');

    const sectionId = new URLSearchParams(window.location.search).get('section_id');
    console.log(sectionId);
    editBtn.addEventListener('click', function() {

        modal.style.display = 'block';
        fetch(`../../../BackEnd/templates/admin/fetchEditSectionForm.php?section_id=${sectionId}`)
        .then(response => response.text())
        .then(data=> {
            modalContent.innerHTML = data;

            const close = document.querySelector('.close');
            close.addEventListener('click', function(){
                modal.style.display = 'none';
            });

            const form = document.getElementById('edit-section-details');
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(form);

                const formButton = form.querySelector('button[type="submit"]');
                formButton.style.backgroundColor = 'gray';
                formButton.disabled = true;
                
                fetch(`../../../BackEnd/api/admin/postEditSectionDetails.php`, {
                    method: 'POST',
                    body: formData
                })
                .then(response=> response.json())
                .then(data=> {

                    if (data.success) {
                        alert(data.message);

                        setTimeout(()=>{
                            window.location.reload();
                        }, 1000);
                    }
                    else {
                        if(data.section && !data.section.success) {
                            alert(data.section.message);
                            form.reset();
                        }
                        else if(data.adviser && !data.adviser.success) {
                            alert(data.adviser.message);
                            form.reset()
                        }
                        else if(data.student && !data.student.success) {
                            alert(data.student.message);
                            form.reset();
                        }
                    }
                })
                .catch(error=>{
                    alert(error);
                    console.log(error);
                })
            });

        });
    });
    
});