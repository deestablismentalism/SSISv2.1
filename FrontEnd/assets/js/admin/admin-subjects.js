import{close, loadingText} from '../utils.js';
document.addEventListener('DOMContentLoaded', function() {

    //popup for  subject's teachers
    const editButtons = document.querySelectorAll('.assign-teacher');
    const modal = document.getElementById('subjects-modal');
    const modalContent = document.getElementById('subjects-content');

    const formButton = document.getElementById('add-subject-button');
    //Add section event listener
    
    formButton.addEventListener('click', function(){
        modal.style.display = 'block';
        modalContent.innerHTML = '';
        modalContent.innerHTML = loadingText;
        fetchAddSubjectForm().then(data=>{
            modalContent.innerHTML = data;

            const radioInput = document.querySelectorAll('input[name="subject"]');
            const selectContainer = document.getElementById('select-container');
            const checkboxContainer = document.getElementById('checkbox-container');
            const checkbox = document.getElementById("checkboxes");
            const initialRadioInput = document.querySelector('input[name="subject"][value="Yes"]');

            // Default "Yes" checked if present
            if (initialRadioInput) {
                initialRadioInput.checked = true;
            }
            const toggleBtn = document.querySelector('.toggleCheckBox');
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function () {
                    checkbox.classList.toggle('show');
                });
            }
            updateDisplay(modal,selectContainer, checkboxContainer);       
            // Attach listeners properly
            radioInput.forEach(radio => {
                radio.addEventListener('change', function () {
                    updateDisplay(modal,selectContainer, checkboxContainer);
                });
            });
            //close button listener
            close(modal);
            
            const form = document.getElementById('add-subject-form');
            let isSubmitting = false;
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                if(isSubmitting) return;
                isSubmitting = true;

                const submitButton = form.querySelector('.submit-button');
                submitButton.disabled = true;
                submitButton.style.backgroundColor = 'gray';
                const formData = new FormData(form);
                const result = await postAddSubject(formData);

                if(result === null) {
                    Loader.hide();
                    form.reset();
                    submitButton.disabled = false;
                }
                else {
                    setTimeout(()=>{
                        Loader.hide();
                        alert(result.message);
                        window.location.reload();
                    },1000);
                }
            })
        });
    });

    editButtons.forEach(button=> {   
        button.addEventListener('click', async function() {
            const subjectId = this.getAttribute('data-id');
            modalContent.innerHTML = '';
            modal.style.display = 'block';
            modalContent.innerHTML = loadingText;

            try {
                let radioValues = ``;
                const response = await fetchAllTeachers(subjectId);
                const data = response.data;
                data.forEach(teacher=>{
                    const isChecked = !teacher.isChecked ? '' : 'checked';
                   radioValues += `<label> <input type="radio" name="subject-teacher" value="${teacher.Staff_Id}" ${isChecked}> 
                            ${teacher.Staff_Last_Name}, ${teacher.Staff_First_Name} </label><br><br>`;
                })
                
                modalContent.innerHTML = `<span class="close">&times;</span>
                    <form class="form popup" id="assign-subject-teacher-form">
                        ${radioValues}
                        <button type="submit" class="submit-button"> Save </button>
                    </form>`;
                close(modal);

                const form = document.getElementById('assign-subject-teacher-form');
                const submitButton = form.querySelector('.submit-button');
                console.log(submitButton)
                form.onsubmit = async (e)=>{
                    e.preventDefault();
                    Loader.show();
                    try {
                        submitButton.disabled = true;
                        submitButton.style.backgroundColor = 'gray';
                        const formData = new FormData(form);
                        formData.append('section-subject-id', subjectId);
                        postAssignTeacherForm(formData).then(data=>{
                            setTimeout(()=>{
                                Loader.hide();
                                alert(data.message);
                                window.location.reload();
                            },1000);
                        }).catch(error=>{
                            alert(error);
                            form.reset();
                            submitButton.disabled = false;
                            Loader.hide();
                        });
                    }
                    catch(error){
                        Loader.hide();
                        alert(error);
                    }
            }
            }catch(err){
                Loader.hide();
                alert(err);
            }

        });
    })
});
async function fetchAllTeachers(subjectId) {
    const response = await fetch(`../../../BackEnd/api/admin/fetchAllTeachers.php?sec-sub-id=${subjectId}`);

    let data;
    try {
        data = await response.json();
    }
    catch {
        throw new Error('Invalid response');
    }

    if(!response.ok) {
        throw new Error(data.message || `HTTP error: ${response.status}`);
    }
    if(!data.success) {
        throw new Error(data.message);
    }
    return data;
}
function updateDisplay(modal, selectContainer, checkboxContainer) {
  const currentSelected = modal.querySelector('input[name="subject"]:checked');

  // select the controls regardless of whether they have a name now
  const selectControls = selectContainer.querySelectorAll('select, input, textarea');
  const checkboxControls = checkboxContainer.querySelectorAll('input, select, textarea');

  if (currentSelected && currentSelected.value === "Yes") {
    // Show checkboxes, hide select
    selectContainer.style.display = "none";
    checkboxContainer.style.display = "block";

    // Remove name from select controls (but save original)
    selectControls.forEach(el => {
      if (el.hasAttribute('name')) {
        if (!el.hasAttribute('data-original-name')) {
          el.setAttribute('data-original-name', el.getAttribute('name'));
        }
        el.removeAttribute('name');
      }
    });

    // Restore names for checkboxes from data-original-name (if present)
    checkboxControls.forEach(el => {
      if (el.hasAttribute('data-original-name')) {
        el.setAttribute('name', el.getAttribute('data-original-name'));
      }
    });

  } else {
    // Show select, hide checkboxes
    selectContainer.style.display = "block";
    checkboxContainer.style.display = "none";

    // Remove names from checkboxes (save original first)
    checkboxControls.forEach(el => {
      if (el.hasAttribute('name')) {
        if (!el.hasAttribute('data-original-name')) {
          el.setAttribute('data-original-name', el.getAttribute('name'));
        }
        el.removeAttribute('name');
      }
    });

    // Restore names for select controls from data-original-name
    selectControls.forEach(el => {
      if (el.hasAttribute('data-original-name')) {
        el.setAttribute('name', el.getAttribute('data-original-name'));
      }
    });
  }
}

//form template
async function fetchAddSubjectForm() {
    try {
        let response = await fetch(`../../../BackEnd/templates/admin/fetchAddSubjectForm.php`);
        let data = await response.text();
        if(!response.ok) {
            console.error(`Error: ${data.message}`);
            return null;
        }
        return data;
    }
    catch(err) {
        console.error(err);
        return null;
    }
}
async function postAddSubject(formData) {
    const response = await fetch(`../../../BackEnd/api/admin/postAddSubjects.php`, {
        method: 'POST',
        body: formData
    });
    let data;
    try {
        data = await response.json();
    }
    catch{
        throw new Error("Invalid JSON response");
    }
    if (!response.ok){
        throw new Error(data.message || `HTTP error: ${response.status}`);
    }

    if(!data.success) {
        throw new Error(data.message || 'Something went wrong');
    }
    return data;
}
async function postAssignTeacherForm(formData) {
    const response = await fetch("../../../BackEnd/api/admin/postAssignSubjectTeacher.php", {
        method: "POST",
        body: formData,
    });

    // Try parsing JSON response
    let data;
    try {
        data = await response.json();
    } catch {
        throw new Error("Invalid JSON response from server");
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