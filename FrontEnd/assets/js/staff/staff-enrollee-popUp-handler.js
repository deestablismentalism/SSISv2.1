import {close, modalHeader, loadingText} from '../utils.js';
document.addEventListener('DOMContentLoaded',async function (){ 
    const modal = document.getElementById('enrolleeModal');
    const modalContent = document.querySelector('.modal-content');
    document.addEventListener('click', async function (e) { 
        let initialModalContent = '';
        const button = e.target.closest('.view-button');
        if (button) {
            const enrolleeId = button.getAttribute('data-id');
            modal.style.display = 'block';
            modalContent.innerHTML = loadingText; // Show loader while fetching data
            try {
                const result = await fetchEnrolleeInfo(enrolleeId);
                if(result) {
                    modalContent.innerHTML = modalHeader();
                    modalContent.innerHTML += result;
                    modalContent.innerHTML += `
                    <button class="accept-btn" data-action="accept"data-id="${enrolleeId}">Accept</button>
                    <button class="reject-btn" data-action="deny" data-id="${enrolleeId}">Deny</button>
                    <button class="toFollow-btn" data-action="toFollow" data-id="${enrolleeId}">To Follow</button>
                    `;
                    initialModalContent = modalContent.innerHTML;
                    close(modal);
                    createNextModalContent();
                }
            }
            catch(error) {
                console.error("Fetch error:", error);
                modalContent.innerHTML = `<span class="close">&times;</span><p>${error.message}</p>`;
                close(modal);
            }
            function createNextModalContent() {
                modalContent.addEventListener('click', async function(e){
                    if (e.target.matches('.toFollow-btn') || e.target.matches('.reject-btn') || e.target.matches('.accept-btn')) {
                        const enrolleeId = e.target.getAttribute('data-id');
                        const action = e.target.getAttribute('data-action');
                        let status = {
                            "toFollow" : 4,
                            "deny" : 2,
                            "accept" :1
                        }[action];
                        modalContent.innerHTML = modalHeader(true);
                        modalContent.innerHTML += `
                            <form id="deny-followup">
                                    <input type="hidden" name="id" value="${enrolleeId}">
                                    <input type="hidden" name="status" value="${status}">
                                <p> State Remarks </p>
                                <textarea id="description" class="description-box" name="remarks" rows="6" cols="40" placeholder="write here.."></textarea><br>
                                <button type="submit"> Submit followup </button>
                            </form>
                        `;
                        close(modal);
                        const modalBack = modalContent.querySelector('.back-button');
                        if(modalBack) {
                            modalBack.addEventListener('click',()=>{
                                modalContent.innerHTML = initialModalContent;
                                close(modal);
                                createNextModalContent();
                            })
                        }
                        const form = document.getElementById('deny-followup');   
                        const submitButton = document.querySelector('button[type=Submit]'); 
                        console.log(submitButton);
                        form.addEventListener('submit', async function(e) {
                            e.preventDefault(); 
                            submitButton.disabled = true;
                            submitButton.style.backgroundColor = 'gray';
                            try {
                                const formData = new FormData(form);
                                const result = await postUpdateEnrolleeStatus(formData);
                                if(!result.success) {
                                    alert(result.message);
                                    form.reset();
                                    submitButton.disabled = false;
                                }
                                else {
                                    alert(result.message);
                                    setTimeout(()=>{location.reload()}, 1000);
                                }
                            }
                            catch(error) {
                                alert(error.message);
                                form.reset();
                                submitButton.disabled = false;
                            }
                        });
                    }
                });
            }
       }
    })
    document.addEventListener('click', function(e){ 
        
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
async function fetchEnrolleeInfo(enrolleeId) {
    try {
        console.time('fetch');
        const response = await fetch(`../../../BackEnd/templates/staff/fetchEnrolleeInfo.php?id=`+ encodeURIComponent(enrolleeId));
        console.timeEnd('fetch');
        let data;
        try {
            data = await response.text();
        }
        catch {
            throw new Error('Invalid response');
        }
        return data;
    }
    catch(error) {
        return error.message || `There was an unexpected error`;
    }
}
async function postUpdateEnrolleeStatus(formData) {
    try {
        const response = await fetch(`../../../BackEnd/api/staff/postUpdateEnrolleeStatus.php`, {
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
                message: data.message || `HTTP Error: ${response.status}`,
                data: null
            };
        }
        return data;
    }
    catch(error) {
        return {
            success: false,
            message: error.message || `Something went wrong`,
            data: null
        };
    }
}
async function fetchPendingStudents() {
    try {
        console.time('fetch');
        const response = await fetch(`../../../BackEnd/api/staff/fetchPendingStudents.php`);
        console.timeEnd('fetch');
        let data;
        try {
            data = await response.json();
        }
        catch{
            throw new Error('Invalid Response');
        }
        if(!response.ok) {
            return {
                success: false,
                message: data.messsage || `HTTP ERROR: ${response.status}`,
                data: null
            };
        }
        return data;
    }
    catch(error) {
        return {
            success: false,
            message: error.message || `Something went wrong`,
            data: null
        };
    }
}