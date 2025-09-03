document.addEventListener('DOMContentLoaded', function() {
    const addSectionBtn = document.getElementById('add-section-btn');
    const modal = document.querySelector('.modal');
    const content = document.querySelector('.modal-content');

    function closeModal(modal) {
        modal.style.display = 'none';
    }

    //modal handler
    addSectionBtn.addEventListener('click', function() {
        modal.style.display = 'block';
        content.innerHTML = `Loading...`;
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
                 
                 button.disabled = true;
                 button.style.backgroundColor = 'gray';

                 const formData = new FormData(form);
                 fetch(`../../../BackEnd/api/admin/postAddSection.php`, {
                    method: 'POST',
                    body: formData
                 })
                 .then(response=> response.json())
                 .then(data =>{
                    if(data.success == false) {
                        alert(data.message);
                        form.reset();
                        button.disabled = false;
                        button.style.backgroundColor = '#007bff';
                    }
                    else {
                        alert('Section added successfully.');
                        console.log(data);
                        setInterval(()=> {
                            window.location.reload();
                        }, 1000);
                    }
                 })
                 .catch(error=>{
                    console.log(error.message);
                 })
            })
            const closeBtn = document.querySelector('.close-btn');
            closeBtn.addEventListener('click', function() {
                closeModal(modal);
            });
        })
    });
    const sectionsListContainer = document.querySelector('.sections-list-wrapper');
    //fetching sections lists
    fetch(`../../../BackEnd/api/admin/fetchSectionsListDetails.php`)
    .then(response=> response.json())
    .then(data=>{
       if (data.success == false) {
            sectionsListContainer.innerHTML = data.message || 'No sections found';
       }
       else {
            const template = document.getElementById('sections-list-template');
            const container = document.querySelector('.sections-list-container');
            data.forEach(section=> {
                const clone = template.content.cloneNode(true);

                const adviserName = section.Staff_Id == null ? 'No Adviser yet' : section.Staff_First_Name + ' ' + section.Staff_Last_Name;
                const students = section.Students == 0 ? 'No Students yet' : section.Students;
                clone.querySelector('.section').textContent = section.Section_Name;
                clone.querySelector('.section-grade-level').textContent = section.Grade_Level;
                clone.querySelector('.adviser-value').textContent = adviserName;
                clone.querySelector('.students-value').textContent = students;
                clone.querySelector('.edit-section').setAttribute('href' , `./admin_view_section.php?section_id=${section.Section_Id}`);
                container.appendChild(clone);

            });
       }
    })
    .catch(error=> {
        alert(error.message || 'Something went wrong. Please try again later');
    })

});