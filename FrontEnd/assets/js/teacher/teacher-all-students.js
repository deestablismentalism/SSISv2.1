document.addEventListener('DOMContentLoaded',function(){
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
});
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
    //APPEND TO MODAL CONTENTS
    modalContent.appendChild(closeBtn);
    modalContent.insertAdjacentHTML('beforeend', data);
    //APPEND TO MODAL AND APPEND TO BODY
    modalContainer.appendChild(modalContent);
    document.body.appendChild(modalContainer);
}