document.addEventListener('DOMContentLoaded', function() {
    // Create enrollee modal
    const modal = document.createElement('div');
    modal.id = 'enrolleeModal';
    modal.className = 'modal';
    modal.innerHTML = `
        <div class="modal-content">
            <span class="close">&times;</span>
            <div id="modal-body"></div>
        </div>
    `;
    document.body.appendChild(modal);

    document.body.appendChild(reasonModal);
     
    // Style status cells
    const rows = document.querySelectorAll('.denied-followup-row');
    rows.forEach(row => {
        const status = row.querySelector('td:nth-child(5)');
        if (status) {
            const statusValue = status.textContent.trim().toLowerCase();
            status.innerHTML = `<span class="status-cell status-${statusValue}"> ${statusValue.toUpperCase()} </span>`;
        }
    });

    // Function to close modals
    function closeModal(modalElement) {
        if (modalElement) {
            modalElement.style.display = 'none';
        }
    }

    // Function to reattach close button listeners
    function attachCloseButtonListeners() {
        document.querySelectorAll('.modal .close').forEach(closeBtn => {
            // Remove existing listeners
            closeBtn.replaceWith(closeBtn.cloneNode(true));
            // Add new listener
            closeBtn.onclick = function() {
                const parentModal = this.closest('.modal');
                if (parentModal) {
                    closeModal(parentModal);
                }
            };
        });
    }

    // Initial attachment of close button listeners
    attachCloseButtonListeners();

    // Handle view resubmission and view reason buttons
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('view-resubmission')) {
            const enrolleeId = e.target.getAttribute('data-id');
            const modalBody = document.getElementById('modal-body');
            
            modal.style.display = 'block';
            modalBody.innerHTML = '<p>Loading...</p>';

            fetch('../../../BackEnd/admin/adminEnrolleeInfoView.php?id=' + encodeURIComponent(enrolleeId))
                .then(response => response.text())
                .then(data => {
                    const modalContent = document.querySelector('#enrolleeModal .modal-content');
                    modalContent.innerHTML = `
                        <span class="close">&times;</span>
                        <div id="modal-body">
                            ${data}
                            <div class="action-buttons">
                                <button data-id="${enrolleeId}" class="resubmission accept-btn" data-id="${enrolleeId}">Accept</button>
                                <button data-id="${enrolleeId}" class="resubmission deny-btn" data-id="${enrolleeId}">Deny</button>
                            </div>
                        </div>
                    `;
                    attachCloseButtonListeners();
                })
                .catch(error => {
                    console.error('Error:', error);
                    modalBody.innerHTML = '<p>Error loading data. Please try again.</p>';
                    attachCloseButtonListeners();
                });
        } 
         
        else if (e.target.classList.contains('view-reason')) {
            const enrolleeId = e.target.getAttribute('data-id');
            const content = document.getElementById('reason-modal-content');
            const reasonModal = document.getElementById('reasonModal');
            let enrollmentStatus = '';
            
            // Show modal and loading message
            reasonModal.style.display = 'block';
            content.innerHTML = '<p>Loading reasons...</p>';

            fetch('../../../BackEnd/api/admin/fetchEnrolleeTransactions.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'id=' + enrolleeId
            })
            .then(response => response.json())
            .then(response => {
                console.log('Response:', response); // Debug log
                
                content.innerHTML = response.data[0].Remarks;
                enrollmentStatus = parseInt(response.data[0].Enrollment_Status);
                const transactionId = parseInt(response.data[0].Enrollment_Transaction_Id);

                if (enrollmentStatus == 1) {
                    content.innerHTML+= `<div class="unhandled-buttons">
                                        <button data-id="${transactionId}" data-enrollee="${enrolleeId}" data-action="enroll" class="remarks accept-btn">Finalize Enrollment</button>
                                        <button data-id="${transactionId}" data-enrollee="${enrolleeId}" data-action="deny" class="remarks deny-btn">Deny</button>
                                        <button data-id="${transactionId}" data-enrollee="${enrolleeId}" data-action="toFollow" class="remarks to-follow-btn">To Follow</button></div>`;    
                }
                else if (enrollmentStatus == 4) {
                    const isResubmitted = parseInt(response.data[0].Can_Resubmit);
                    const isNeedConsult = parseInt(response.data[0].Need_Consultation);
                    const isConsultationDisabled = isNeedConsult == 1? 'disabled' : '';
                    const isResubmissionDisabled = isResubmitted == 1 ? 'disabled' : '';
                    content.innerHTML+= `<div><button data-id="${transactionId}" data-enrollee="${enrolleeId}" data-action="enroll" class="remarks accept-btn ${isResubmissionDisabled}" ${isResubmissionDisabled}>Enroll</button>
                                        <button data-id="${transactionId}" data-enrollee="${enrolleeId}" data-action="resubmit" class="remarks resubmission-btn ${isResubmissionDisabled}" ${isResubmissionDisabled}>Allow Resubmission</button>
                                        <button data-id="${transactionId}" data-enrollee="${enrolleeId}" data-action="consult" class="remarks consultation-btn ${isConsultationDisabled}"${isConsultationDisabled}> Needs Consultation </button>
                                        </div>`;    
                }
                else if (enrollmentStatus == 2) {
                    content.innerHTML+= `<div><button data-id="${transactionId}" data-enrollee="${enrolleeId}" data-action="deny" class="remarks deny-btn">Finalize Denial</button>
                                        <button data-id="${transactionId}" data-enrollee="${enrolleeId}" data-action="toFollow" class="remarks to-follow-btn">To Follow</button></div>`;    
                }
            })
            .catch(error => {
                console.error('Error:', error);
                content.innerHTML = '<p>Error fetching data. Please try again later.</p>';
            });
        }
    // Close modals when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                closeModal(event.target);
            }
        }
    });
    reasonModal.addEventListener('click', function(e) {
        /*NOTE: unhandled enrollments are still not reflected to the users, it needs to be approved or handled by the admin 
          before reflecting to the user*/ 
      
          //TODO:  Add post handlers for all action buttons for remarks and update the enrollee table accordingly
          //TODO: Handle enrolled status whether accepted, denied or to follow
          //TODO: Handle resubmission checks to avoid double resubmission, and allow enrollment and  
          //TODO: handle denied status, finalize denial or update into to follow
      if(e.target.classList.contains('accept-btn') || e.target.classList.contains('consultation-btn') 
    || e.target.classList.contains('resubmission-btn') || e.target.classList.contains('deny-btn') || e.target.classList.contains('to-follow-btn') ) {
        const enrolleeId = e.target.getAttribute('data-enrollee');
        const transactionId = e.target.getAttribute('data-id');  
        const action = e.target.getAttribute('data-action');
          let actionNumber = 0;

          if(action == "enroll") {
            actionNumber = 1; 
          }
          else if(action == "deny") {
            actionNumber = 2;
          }
          else if(action == "toFollow") {
            actionNumber = 4
          }
          else if(action == "resubmit") {
            actionNumber = 5;
          }
          else if(action == "consult") {
            actionNumber = 6;
          }  

          if (actionNumber == 1 || actionNumber == 2 || actionNumber == 4) {
            console.log(actionNumber);
            console.log(transactionId);
            
            fetch(`../../../BackEnd/api/admin/postUpdateEnrollee.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    'status': actionNumber,
                    'id' : enrolleeId
                })
            })
            .then(response => {
                if(!response.ok) {
                    throw new Error(`HTTP ERROR! status ${response.status}`)
                }
                return response.json()
            })
            .then(data=>{
                if(data.success) {
                    alert('Update success');

                    window.location = '/admin_unhandled_enrollments.php';
                }
                else {
                    alert(data.message);
                }
            })
            .catch(error=>{
                alert(error);
            })
          }
          else {
            //initialize boolean flags if 5 = isResubmit, if 6 = isConsult
            let isResubmit = 0;
            let isConsult = 0;
            const status = 4;
            if(actionNumber == 5) {
                isResubmit = 1;
            }
            else if(actionNumber == 6) {
                isConsult = 1;
            }
            console.log(isResubmit);
            console.log(isConsult);
            console.log(transactionId);
            fetch(`../../../BackEnd/api/admin/postUpdateEnrollmentTransaction.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    'isResubmit': isResubmit,
                    'isConsult' : isConsult,
                    'id' : transactionId,
                    'status' : status,
                    'enrolleeId' : enrolleeId 
                })
            })
            .then(response => {
                if(!response.ok) {
                    throw new Error(`HTTP ERROR! status ${response.status}`)
                }
                return response.json()
            })
            .then(data=>{
                if(data.success) {
                    alert('Update success');

                    window.location = '../admin_unhandled_enrollments.php';
                }
                else {
                    alert(data.message);
                }
            })
            .catch(error=>{
                alert(error);
            })
          }
      } 
    });
});