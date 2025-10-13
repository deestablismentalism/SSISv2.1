import{close,loadingText, modalHeader} from '../utils.js';
document.addEventListener('DOMContentLoaded', function(){
    const getAddSchedFormBtn = document.getElementById('get-add-sched-form');
    const modal = document.getElementById('schedule-modal');
    const modalContent = document.getElementById('schedule-modal-content');

    getAddSchedFormBtn.addEventListener('click', async function(){
        modal.style.display = 'block';
        modalContent.innerHTML = loadingText;

        let initialModalContent = '';
        try {
            const response = await fetchSectionSubjects();
            let subjectValues = ``;
            response.data.forEach(subject=>{
                subjectValues += `<div class="modal-subjects"> <p class="subject-name">${subject.Subject_Name} - ${subject.Section_Name}</p> <button data-id="${subject.Section_Subjects_Id}"> create schedule</button> </div>`;
            })
            modalContent.innerHTML = modalHeader();           
            modalContent.innerHTML += `<p>Which section do you want to create a schedule for?</p><br>` + subjectValues;
            initialModalContent = modalContent.innerHTML;
            close(modal);
            attachSectionButtonListener();
            //handler for create schedule buttons
            function attachSectionButtonListener() {
                const sectionButtons = modalContent.querySelectorAll('button');
                sectionButtons.forEach(button=>{
                button.addEventListener('click', async function(e){
                    const sectionSubject = e.target.parentElement.querySelector('.subject-name');//get text content
                    modalContent.innerHTML = '';
                    modalContent.innerHTML = loadingText;
                    const sectionSubjectId = parseInt(e.target.getAttribute('data-id')); //to store section subject id in db
                    try {
                        const formResponse = await fetchAddScheduleForm();
                        modalContent.innerHTML = modalHeader(true);
                        modalContent.innerHTML += `<p>${sectionSubject.textContent}</p>`;
                        modalContent.innerHTML += formResponse;
                        close(modal);

                        const backButton = modalContent.querySelector('.back-button');
                        if(backButton) {
                            backButton.addEventListener('click',()=>{
                                modalContent.innerHTML = initialModalContent;
                                close(modal);
                                attachSectionButtonListener();
                            });
                        }
                    }
                    catch(error) {
                        alert(error.message);
                        console.error(error);
                        modalContent.innerHTML = modalHeader();
                        modalContent.innerHTML += `Failed to load`;
                        close(modal);
                    }
                    const form = document.getElementById('add-schedule-form');
                    const button = modal.querySelector('button[type="submit"]');
                    let isSubmitting = false;
                    form.addEventListener('submit', async function(e) {
                        e.preventDefault();
                        if(isSubmitting) return;
                        isSubmitting = true;
                        button.disabled = true;
                        button.style.backgroundColor = 'gray';
                        const formData = new FormData(form);
                        formData.append('section-subject-id', sectionSubjectId);
                        try {
                            const result = await postAddSectionSchedule(formData);

                            if(!result.success) {
                                alert(result.message);
                                form.reset();
                                button.disabled = false;
                            }
                            else {
                                alert(result.message);
                                setTimeout(()=>{
                                    window.location.reload()
                                }, 1000);
                            }
                        }
                        catch(error) {
                            alert(`Error: ${error.message}`);
                        }
                    });
                })
            });
            }    
        }
        catch(error) {
            alert(error.message);
            console.error(error);
            modalContent.innerHTML = modalHeader();
            modalContent.innerHTML += `Failed to load`;
            close(modal);
        }
    });
});
async function fetchSectionSubjects() {
    try {
        const response = await fetch(`../../../BackEnd/api/admin/fetchAllSubjects.php`);

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
                message: data.message || `HTTP ERROR: ${response.status}`,
                data: null
            };
        }
        if(!data.success) {
            return {
                success: false,
                message: data.message || `Something went wrong`,
                data: null
            };
        }
        return data;
    }
    catch(error) {
        return {
            success: false,
            message: error.message || `There was an error`,
            data: null
        };
    }
}
async function fetchAddScheduleForm() {
    const response = await fetch(`../../../BackEnd/templates/admin/fetchAddScheduleForm.php`);

    let data;
    try {
        data = await response.text();
    }
    catch{
        throw new Error('Cannot proccess response');
    }
    if(!response.ok) {
        throw new Error(`HTTP error: ${response.status}`);
    }
    return data;
}
async function postAddSectionSchedule(formData) {
    try {
        const response = await fetch(`../../../BackEnd/api/admin/postAddSectionSchedule.php`, {
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
                message: data.message || `HTTP ERROR: ${response.status}`,
                data: null
            };
        }
        if(!data.success) {
            return {
                success: false,
                message: data.message || `Something went wrong`,
                data: null
            };
        }
        return data;
    }
    catch(error) {
        return {
            success: false,
            message: error.message || `There was unexpected error`,
            data: null
        };
    }
}

