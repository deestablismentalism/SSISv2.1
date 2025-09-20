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
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                const submitButton = form.querySelector('.submit-button');
                const formData = new FormData(form);
                submitButton.disabled = true;
                submitButton.style.backgroundColor = 'gray';

                const result = await postAddSubject(formData);
                console.log(result);

                if(result === null) {
                    form.reset();
                    submitButton.disabled = false;
                    submitButton.style.backgroundColor = '#007bff';
                }
                else {
                    setTimeout(()=>{
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

            let data = await fetch(`../../../BackEnd/api/admin/fetchAllTeachers.php`)
            let teachers = await data.json();

            let teacherRadio =
            teachers.map(t=> `<label> <input type="radio" name="subject-teacher" value="${t.Staff_Id}"> 
                ${t.Staff_Last_Name}, ${t.Staff_First_Name} </label><br>`).join(``);

            modalContent.innerHTML = `<span class="close"> &times; </span><br>
            <form class="form popup" id="assign-subject-teacher-form">
                ${teacherRadio}
                <button type="submit" class="submit-button"> Save </button>
            </form>`;

            close(modal);
            
            const form = document.getElementById('assign-subject-teacher-form');
            const submitButton = modal.querySelector('button[type="submit"]');
            
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                console.log(submitButton);

                submitButton.disabled = true;
                submitButton.style.backgroundColor = 'gray';

                const formData = new FormData(form);    
                formData.append('subjectId', subjectId);

                postAssignTeacherForm(formData);
            })

        });
    })
});
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
    try {
        let response = await fetch(`../../../BackEnd/api/admin/postAddSubjects.php`, {
            method: 'POST',
            body: formData
        });
        let data = await response.json();
        if(!response.ok) {
            console.error(`Error: ${data.message}`);
            alert(data.message || 'Something went wrong');
            return null;
        }
        if(!data.success) {
            alert(data.message);
            return null;
        }
        return data;
    }
    catch(error) {
        alert('There was an unexpected problem');
        console.error(error);
        return null;
    }
}
async function postAssignTeacherForm(formData) {
    try {
        let response = await fetch(`../../../BackEnd/api/admin/postAssignSubjectTeacher.php`,{
            method: 'POST',
            body: formData,
        });
        let data = await response.json();
        if(!response.ok) {
            console.error(`Error: ${data.message}`);
            return null;
        }
        if(!data.success) {
            alert(data.message || 'Something went wrong');
            return null;
        }
        return data;
    }
    catch(error) {
        alert('There was an unexpected problem. Please try again later');
        console.error(error);
        return null;
    }
}