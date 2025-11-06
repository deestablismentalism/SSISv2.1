document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const gradeFilter = document.getElementById('filter-grade');
    const statusFilter = document.getElementById('filter-status');
    const sectionFilter = document.getElementById('filter-section');

    function getRows() { return document.querySelectorAll('.student-rows'); }

    function getStatusTextFromNumber(n) {
        switch(parseInt(n)) {
            case 1: return 'Active';
            case 2: return 'Inactive';
            case 3: return 'Dropped';
            default: return 'Unknown';
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
        button.addEventListener('click', function() {
            Loader.show();
            const studentId = this.getAttribute('data-enrollee');
            viewStudentDetails(studentId);
        });
    });

    // Edit Student button with loader
    const editButtons = document.querySelectorAll('.edit-student');
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            Loader.show();
            const studentId = this.getAttribute('data-enrollee');
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
function viewStudentDetails(studentId) {
    fetch(`../../../BackEnd/api/admin/fetchStudentDetails.php?id=${encodeURIComponent(studentId)}`) 
        .then(response => response.json())
        .then(data => {
            Loader.hide();
            if (data.success) {
                createStudentDetailsModal(data.student);
            } else {
                alert('Error fetching student details: ' + data.message);
            }
        })
        .catch(error => {
            Loader.hide();
            console.error('Error:', error);
            alert('An error occurred while fetching student details.');
        });
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

function editStudentDetails(studentId) {
    window.location.href = `../admin/admin_edit_student.php?id=${studentId}`;
}

async function deleteAndArchiveStudent(studentId) {
    if (confirm('Are you sure you want to delete this student? This action cannot be undone.')) {
        Loader.show();
        const result = await deleteAndArchive(studentId);
        if(!result.success) {
            alert(result.message);
            Loader.hide();
        }
        else {
            alert(result.message);
            setTimeout(()=>window.location.reload(), 1000);
        }
    }
}
const TIME_OUT = 20000;
async function deleteAndArchive(studentId) {
    const controller = new AbortController();
    const timeoutId = setTimeout(()=> controller.abort(),TIME_OUT);
    try {
        const response = await fetch(`../../../BackEnd/api/admin/postDeleteAndArchiveStudent.php`,{
            signal: controller.signal,
            method: 'POST',
            headers: {
                'Content-Type' : 'application/x-www-form-urlencoded',
            },
            body: `id=${studentId}`
        });
        clearTimeout(timeoutId);
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
                message: data.message || `HTTP ERROR ${response.status}`,
                data: null
            };
        }
        return data;
    }
    catch(error) {
        if(error.name === "AbortError") {
            return {
                success: false,
                message: `Response timeout. Server took too long to response: Took ${TIME_OUT/1000} seconds`,
                data: null
            };
        }
        return {
            success: false,
            message: error.message || `Something went wrong`,
            data: null
        };
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
            return 'Dropped';
        default:
            return 'Unknown';
    }
}