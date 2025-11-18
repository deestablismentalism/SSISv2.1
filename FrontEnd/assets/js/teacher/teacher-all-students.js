document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const gradeFilter = document.getElementById('filter-grade');
    const statusFilter = document.getElementById('filter-status');
    const sectionFilter = document.getElementById('filter-section');

    function getRows() { return document.querySelectorAll('.student-rows'); }

    function getStatusTextFromNumber(n) {
        const numericValue = parseInt(n);
        switch(numericValue) {
            case 0: return 'Waiting';
            case 1: return 'Active';
            case 2: return 'Inactive';
            case 3: return 'Dropped';
            case 4: return 'Transferred';
            case 5: return 'Graduated';
            default: return String(n);
        }
    }
    function populateFilters() {
        if (!gradeFilter || !statusFilter || !sectionFilter) return;
        const grades = new Set();
        const statuses = new Set();
        const sections = new Set();
        getRows().forEach(row => {
            const g = row.getAttribute('data-grade'); if (g) grades.add(g);
            const s = row.getAttribute('data-status'); if (s) statuses.add(s);
            const sec = row.getAttribute('data-section'); if (sec) sections.add(sec);
        });
        const addOptions = (selectEl, values, labelFn = (v)=>v) => {
            const existing = new Set(Array.from(selectEl.options).map(o => o.value));
            Array.from(values).filter(v => v && !existing.has(v)).sort().forEach(v => {
                const opt = document.createElement('option');
                opt.value = v;
                opt.textContent = labelFn(v);
                selectEl.appendChild(opt);
            });
        };
        addOptions(gradeFilter, grades);
        addOptions(statusFilter, statuses, getStatusTextFromNumber);
        addOptions(sectionFilter, sections);
    }

    function applyFilters() {
        const searchTerm = (searchInput ? searchInput.value : '').toLowerCase();
        const gradeVal = gradeFilter ? gradeFilter.value.toLowerCase() : '';
        const statusVal = statusFilter ? statusFilter.value : '';
        const sectionVal = sectionFilter ? sectionFilter.value.toLowerCase() : '';

        getRows().forEach(row => {
            const rowGrade = (row.getAttribute('data-grade') || '').toLowerCase();
            const rowStatus = (row.getAttribute('data-status') || '').toString();
            const rowSection = (row.getAttribute('data-section') || '').toLowerCase();

            const studentName = row.querySelector('td:first-child').textContent.toLowerCase();
            const studentLRN = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            const studentEmail = row.querySelector('td:nth-child(5)').textContent.toLowerCase();

            const matchesSearch = !searchTerm || studentName.includes(searchTerm) || studentLRN.includes(searchTerm) || studentEmail.includes(searchTerm);
            const matchesGrade = !gradeVal || rowGrade === gradeVal;
            const matchesStatus = !statusVal || rowStatus === statusVal;
            const matchesSection = !sectionVal || rowSection === sectionVal;

            row.style.display = (matchesSearch && matchesGrade && matchesStatus && matchesSection) ? '' : 'none';
        });
    }

    if (searchInput) searchInput.addEventListener('input', applyFilters);
    if (gradeFilter) gradeFilter.addEventListener('change', applyFilters);
    if (statusFilter) statusFilter.addEventListener('change', applyFilters);
    if (sectionFilter) sectionFilter.addEventListener('change', applyFilters);

    populateFilters();
    applyFilters();

    // View Student button with loader
    const viewButtons = document.querySelectorAll('.view-student');
    viewButtons.forEach(button => {
        button.addEventListener('click', async function() {
            Loader.show();
            const studentId = this.getAttribute('data-student');
            const result = await viewStudentDetails(studentId);
            if(!result.success) {
                Loader.hide();
                createModal((result.message));
            }
            else {
                Loader.hide();
                createModal(result.data,studentId);
            }
        });
    });

    // Edit Student button with loader
    const editButtons = document.querySelectorAll('.edit-student');
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            Loader.show();
            const studentId = this.getAttribute('data-student');
            editStudentDetails(studentId);
        });
    });

    // Delete Student button with loader
    const deleteButtons = document.querySelectorAll('.delete-student');
    deleteButtons.forEach(button => {
        button.addEventListener('click', async function() {
            const studentId = this.getAttribute('data-student');
            deleteAndArchiveStudent(studentId);
        });
    });
});

// Function to view student details
async function viewStudentDetails(studentId) {
    try {
        const response = await fetch(`../../../BackEnd/templates/admin/fetchStudentDetails.php?id=${encodeURIComponent(studentId)}`);
        let data;
        try {
            data = await response.text();
        }
        catch{
            throw new Error('Invalid Response');
        }
        if(!response.ok) {
            return {
                success: false,
                message: data.message || `HTTP ERROR. Request returned with response code: ${response.status}`,
                data: null
            };
        }
        return {
            success: true,
            message: `Success`,
            data: data
        };
    } 
    catch(error) {
         return {
            success: false,
            message: error.message || `Something went wrong`,
            data: null
        };
    }
}
function createModal(data, studentId) {
    // Create main modal wrapper
    const modal = document.createElement('div');
    modal.className = 'modal';
    modal.style.display = 'flex';
    
    // Create modal content
    const modalContent = document.createElement('div');
    modalContent.className = 'modal-content';
    
    // Create modal header
    const modalHeader = document.createElement('div');
    modalHeader.className = 'modal-header';
    
    const headerTitle = document.createElement('h2');
    headerTitle.textContent = 'Student Information';
    
    const closeBtn = document.createElement('span');
    closeBtn.className = 'close';
    closeBtn.innerHTML = '&times;';
    closeBtn.onclick = function() {
        document.body.removeChild(modal);
    };
    //CREATE HYPERLINK TO PDF GENERATED
    const pdfButton  =  document.createElement('a');
    pdfButton.href = `./student_pdf_info.php?student-id=${encodeURIComponent(studentId)}`;
    pdfButton.target = '_blank';
    pdfButton.rel = 'noopener noreferrer';
    pdfButton.textContent = 'Generate PDF';
    //APPEND TO MODAL CONTENTS
    modalContent.appendChild(closeBtn);
    modalContent.insertAdjacentHTML('beforeend', data);
    modalContent.appendChild(pdfButton);
    //APPEND TO MODAL AND APPEND TO BODY
    modalContainer.appendChild(modalContent);
    document.body.appendChild(modalContainer);
}
// Function to create and display the student details modal
function createStudentDetailsModal(student) {
    const modalContainer = document.createElement('div');
    modalContainer.className = 'modal-container';
    
    // Create modal content
    const modalContent = document.createElement('div');
    modalContent.className = 'modal-content';
    
    const closeBtn = document.createElement('span');
    closeBtn.className = 'close';
    closeBtn.innerHTML = '&times;';
    closeBtn.onclick = function() {
        document.body.removeChild(modalContainer);
    };
    
    const studentDetails = document.createElement('div');
    studentDetails.className = 'student-details';
    
    // Helper function to format disability info
    const getSpecialCondition = () => {
        if (!student.Have_Special_Condition || student.Have_Special_Condition === 0 || student.Have_Special_Condition === '0') {
            return 'None';
        }
        return student.Special_Condition || 'Yes (No details provided)';
    };
    
    const getAssistiveTech = () => {
        if (!student.Have_Assistive_Tech || student.Have_Assistive_Tech === 0 || student.Have_Assistive_Tech === '0') {
            return 'None';
        }
        return student.Assistive_Tech || 'Yes (No details provided)';
    };
    
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
                <label>Special Condition:</label>
                <span>${getSpecialCondition()}</span>
            </div>
            <div class="detail-item">
                <label>Assistive Technology:</label>
                <span>${getAssistiveTech()}</span>
            </div>
            <div class="detail-item">
                <label>Status:</label>
                <span>${getStatusText(student.Student_Status)}</span>
            </div>
        </div>
    `;
    
    modalContent.appendChild(closeBtn);
    modalContent.appendChild(studentDetails);
    modalContainer.appendChild(modalContent);
    
    document.body.appendChild(modalContainer);
    
    window.onclick = function(event) {
        if (event.target === modalContainer) {
            document.body.removeChild(modalContainer);
        }
    };
}
