document.addEventListener('DOMContentLoaded', function (){ 
        const modal = document.getElementById('enrolleeModal');
        const modalContent = document.querySelector('.modal-content');

    document.addEventListener('click', function (e) { 
       if (e.target.classList.contains('view-button')) {
            const enrolleeId = e.target.getAttribute('data-id');
            console.log(enrolleeId);
            modal.style.display = 'block';
            modalContent.innerHTML = '<p> Wait for data to load... </p>'; // Show loader while fetching data
            fetch('../../../BackEnd/admin/adminEnrolleeInfoView.php?id=' + encodeURIComponent(enrolleeId)) 
            .then(response => response.text())
            .then(data => {
                modalContent.innerHTML = data;
                modalContent.innerHTML += `
                    <button class="accept-btn" data-action="accept"data-id="${enrolleeId}">Accept</button>
                    <button class="reject-btn" data-action="deny" data-id="${enrolleeId}">Deny</button>
                    <button class="toFollow-btn" data-action="toFollow" data-id="${enrolleeId}">To Follow</button>
                `;
                const close = document.querySelector('.close');
                close.addEventListener('click', function(){
                    modal.style.display = 'none';
                });
            })
            .catch(error => {
                console.error("Fetch error:", error);
                modalContent.innerHTML = `<span class="close">&times;</span><p> Error loading data. Please try again. </p>`;
                const close = document.querySelector('.close');
                close.addEventListener('click', function(){
                    modal.style.display = 'none';
                });
            });
            
       }
    })
    
    document.addEventListener('click', function(e){ 
        if (e.target.matches('.toFollow-btn') || e.target.matches('.reject-btn') || e.target.matches('.accept-btn')) {
            const enrolleeId = e.target.getAttribute('data-id');
            const action = e.target.getAttribute('data-action');
            let status = 0 
            if (action === "toFollow") {
                status = 4
            }
            else if (action === "deny") {
                status = 2
            }
            else {
                status = 1
            }
            modalContent.innerHTML = `
                <span class="close">&times;</span>
                <form id="deny-followup">
                        <input type="hidden" name="id" value="${enrolleeId}">
                        <input type="hidden" name="status" value="${status}">
                    <p> State Remarks </p>
                    <textarea id="description" class="description-box" name="remarks" rows="6" cols="40" placeholder="write here.."></textarea><br>
                    <button type="submit"> Submit followup </button>
                </form>
            `;
            const close = document.querySelector('.close');
                close.addEventListener('click', function(){
                    modal.style.display = 'none';
                });

            const form = document.getElementById('deny-followup');   
            const submitButton = document.querySelector('button[type=Submit]'); 
            console.log(submitButton);
            form.addEventListener('submit', function(e) {
                e.preventDefault(); 
                submitButton.disabled = true;
                submitButton.style.backgroundColor = 'gray';
                const formData = new FormData(form);
                for (const [key, value] of formData.entries()) {
                    console.log(`${key}: ${value}`);
                }
                fetch('../../../BackEnd/api/staff/postUpdateEnrolleeStatus.php', { //TODO: update this function to handle all enrollee status updates
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data =>{
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    }
                    else {
                        alert("ERROR: " + data.message);
                        submitButton.disabled = true;

                    }
                })
                .catch(error => {
                console.error("Fetch error:", error);
                    alert("Something went wrong. Please try again.");
                    submitButton.disabled = true;
                });
            });
        }
    });
    const backButton = document.getElementById('back-button');
    const staffType = document.querySelector('.user-type');
    backButton.addEventListener('click', function() {
        const staffValue = staffType.textContent.trim().toLowerCase();

        if(staffValue == 'admin') {
            window.location.href = '../admin/admin_dashboard.php';
        }
        else if (staffValue == 'teacher') {
            window.location.href = '../teacher/teacher_dashboard.php';
        }
        else {
            window.location.href = '../No_Page.php';
        }
    });
});