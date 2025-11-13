import {close, loadingText} from '../utils.js';
document.addEventListener('DOMContentLoaded', function() {
    const addSectionBtn = document.getElementById('add-section-btn');
    const modal = document.querySelector('.modal');
    const content = document.querySelector('.modal-content');

    //modal handler
    addSectionBtn.addEventListener('click', function() {
        modal.style.display = 'block';
        content.innerHTML = loadingText;
        fetch(`../../../BackEnd/templates/admin/fetchAddSectionForm.php`)
        .then(response => response.text())
        .then(data => {
            content.innerHTML = data;
            const form = document.getElementById('add-section-form');
            const button = document.querySelector('button[type="submit"]');
            console.log(button); 
            //submit handler
            form.addEventListener('submit', function(e){
                 e.preventDefault();
                 Loader.show();
                 button.disabled = true;

                 const formData = new FormData(form);
                 fetch(`../../../BackEnd/api/admin/postAddSection.php`, {
                    method: 'POST',
                    body: formData
                 })
                 .then(response=> response.json())
                 .then(data =>{
                    if(data.success == false) {
                        Notification.show({
                            type: data.success ? "error" : "error",
                            title: data.success ? "Success" : "Error",
                            message: data.message
                        });
                        form.reset();
                        button.disabled = false;
                        Loader.hide();  
                    }
                    else {
                        Notification.show({
                            type: data.success ? "success" : "error",
                            title: data.success ? "Success" : "Error",
                            message: data.message
                        });
                        form.reset();
                        button.disabled = false;
                        Loader.hide();
                        setTimeout(()=> {
                            Loader.hide();
                            window.location.reload();
                        }, 1000);
                    }
                 })
                 .catch(error=>{
                    console.log(error.message);
                 })
            })
            close(modal);
        })
    });
    //fetch section detals
    fetchSectionDetails().then(data=>{
        const template = document.getElementById('sections-list-template');
        const container = document.querySelector('.sections-list-container');
        if(data) {
            data.forEach(section => {
                const clone = template.content.cloneNode(true);
            
                const adviserName = section.Staff_Id == null
                    ? 'No Adviser yet'
                    : section.Staff_First_Name + ' ' + section.Staff_Last_Name;
            
                const students = section.Students == 0
                    ? 'No Students yet'
                    : section.Students;
            
                clone.querySelector('.section').textContent = section.Section_Name;
                clone.querySelector('.section-grade-level').textContent = section.Grade_Level;
                clone.querySelector('.adviser-value').textContent = adviserName;
                clone.querySelector('.students-value').textContent = students;
            
                clone.querySelector('.edit-section')
                    .setAttribute('href', `./admin_view_section.php?section_id=${section.Section_Id}`);
            
                container.appendChild(clone);
            });
            
            container.addEventListener('click', e => {
                const link = e.target.closest('.edit-section');
                if (!link) return;
                e.preventDefault();
                Loader.show();
                const target = link.href;
                setTimeout(() => window.location.href = target, 100);
            });
        }
    })
});

async function fetchSectionDetails() {
    try {
        let response = await fetch(`../../../BackEnd/api/admin/fetchSectionsListDetails.php`);
        let data = await response.json()
        if(!response.ok) {
            console.error(`Error ${data.htppcode}`);
            console.error(`There was a problem: ${data.message}`);
            return null;
        }
        if(!data.success) {
            Notification.show({
                type: data.success ? "error" : "error",
                title: data.success ? "Something Went Wrong" : "Error",
                message: data.message
            });
            return null;
        }
        Loader.hide();
        return data.data;
    }
    catch(err) {
        Notification.show({
            type: data.success ? "error" : "error",
            title: data.success ? "There was an unexpected problem" : "Error",
            message: data.message ? err.message : " " 
        });;
        console.error(error);
        return null;
    }
}