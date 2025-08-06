document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('search');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const studentRows = document.querySelectorAll('.student-rows');
            
            studentRows.forEach(row => {
                const studentName = row.querySelector('td:first-child').textContent.toLowerCase();
                const studentLRN = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const studentEmail = row.querySelector('td:nth-child(5)').textContent.toLowerCase();
                
                if (studentName.includes(searchTerm) || 
                    studentLRN.includes(searchTerm) || 
                    studentEmail.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    // View Student button
    const viewButtons = document.querySelectorAll('.view-student');
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const studentId = this.getAttribute('data-id');
            viewStudentDetails(studentId);
        });
    });

    // Edit Student button
    const editButtons = document.querySelectorAll('.edit-student');
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const studentId = this.getAttribute('data-id');
            editStudentDetails(studentId);
        });
    });

    // Delete Student button
    const deleteButtons = document.querySelectorAll('.delete-student');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const studentId = this.getAttribute('data-id');
            deleteStudent(studentId);
        });
    });
});

// Function to view student details
function viewStudentDetails(studentId) {
    // Create a modal to display student details
    //TODO: create the php script for fetchStudentDetails.php
    fetch(`../server_side/students/fetchStudentDetails.php?id=${studentId}`) //non existent API endpoint
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Create modal with student details
                createStudentDetailsModal(data.student);
            } else {
                alert('Error fetching student details: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while fetching student details.');
        });
}

// Function to create and display the student details modal
function createStudentDetailsModal(student) {
    // Create modal container
    const modalContainer = document.createElement('div');
    modalContainer.className = 'modal-container';
    
    // Create modal content
    const modalContent = document.createElement('div');
    modalContent.className = 'modal-content';
    
    // Add close button
    const closeBtn = document.createElement('span');
    closeBtn.className = 'close-btn';
    closeBtn.innerHTML = '&times;';
    closeBtn.onclick = function() {
        document.body.removeChild(modalContainer);
    };
    
    // Add student details
    const studentDetails = document.createElement('div');
    studentDetails.className = 'student-details';
    
    // Format student information
    studentDetails.innerHTML = `
        <h2>${student.Student_First_Name} ${student.Student_Middle_Name ? student.Student_Middle_Name + ' ' : ''}${student.Student_Last_Name}</h2>
        <div class="details-grid">
            <div class="detail-item">
                <label>LRN:</label>
                <span>${student.Learner_Reference_Number}</span>
            </div>
            <div class="detail-item">
                <label>Grade Level:</label>
                <span>${student.Grade_Level}</span>
            </div>
            <div class="detail-item">
                <label>Section:</label>
                <span>${student.Section_Name || 'Not Assigned'}</span>
            </div>
            <div class="detail-item">
                <label>Email:</label>
                <span>${student.Student_Email}</span>
            </div>
            <div class="detail-item">
                <label>Status:</label>
                <span>${getStatusText(student.Student_Status)}</span>
            </div>
        </div>
    `;
    
    // Append elements to modal
    modalContent.appendChild(closeBtn);
    modalContent.appendChild(studentDetails);
    modalContainer.appendChild(modalContent);
    
    // Add modal to the body
    document.body.appendChild(modalContainer);
    
    // Add event listener to close modal when clicking outside
    window.onclick = function(event) {
        if (event.target === modalContainer) {
            document.body.removeChild(modalContainer);
        }
    };
}

// Function to edit student details
function editStudentDetails(studentId) {
    window.location.href = `../admin/admin_edit_student.php?id=${studentId}`;
}

// Function to delete student
function deleteStudent(studentId) {
    if (confirm('Are you sure you want to delete this student? This action cannot be undone.')) {
        fetch('../server_side/deleteStudent.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${studentId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Student deleted successfully');
                // Refresh the page to update the student list
                location.reload();
            } else {
                alert('Error deleting student: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the student.');
        });
    }
}

// Helper function to convert status number to text
function getStatusText(status) {
    switch(parseInt(status)) {
        case 1:
            return 'Active';
        case 2:
            return 'Inactive';
        case 3:
            return 'On Leave';
        default:
            return 'Unknown';
    }
} 