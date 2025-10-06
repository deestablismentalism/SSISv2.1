import{modalHeader, close, loadingText} from '../utils.js'; 
document.addEventListener('DOMContentLoaded', function() {

    const editBtn = document.getElementById('edit-section-btn');
    const modal = document.getElementById('admin-view-section-edit-modal');
    const modalContent = document.getElementById('admin-view-section-edit-content');

    const sectionId = new URLSearchParams(window.location.search).get('section_id');
    console.log(sectionId);
    editBtn.addEventListener('click', function() {

        modal.style.display = 'block';
        modalContent.innerHTML = loadingText;
        fetch(`../../../BackEnd/templates/admin/fetchEditSectionForm.php?section_id=${sectionId}`)
        .then(response => response.text())
        .then(data=> {
            modalContent.innerHTML = modalHeader();
            modalContent.innerHTML += data;

            close(modal);

            const form = document.getElementById('edit-section-details');
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formSectionId = parseInt(sectionId);
                const formData = new FormData(form);
                formData.append('section-id', formSectionId);

                const formButton = form.querySelector('button[type="submit"]');
                formButton.style.backgroundColor = 'gray';
                formButton.disabled = true;
                
                postEditSectionDetails(formData)
                .then(data=> {
                    if (data.success  && data.partialSuccess) {
                        let failMessage = data.message.join('\n- ');
                        alert(`Some updates failed \n ${failMessage}`);
                        setTimeout(()=>{
                            window.location.reload();
                        }, 1000);
                    }
                    else if(!data.success) {
                        alert(data.message);
                        form.reset();
                        formButton.style.backgroundColor = '#007bff';
                        formButton.disabled = false;
                    }
                    else {
                        alert(data.message);
                        setTimeout(()=>{
                            window.location.reload();
                        }, 1000);
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
async function postEditSectionDetails(formData) {
    const response = await fetch(`../../../BackEnd/api/admin/postEditSectionDetails.php`, {
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
        throw new Error(`HTTP error: ${response.status}`);
    }
    return data;
}