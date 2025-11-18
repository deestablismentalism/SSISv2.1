import {close, loadingText} from '../utils.js';

document.addEventListener('DOMContentLoaded', function() {
    const addSubjectBtn = document.getElementById('add-subject-button');
    const modal = document.getElementById('subjects-modal');
    const modalContent = document.getElementById('subjects-content');
    const subjectsList = document.getElementById('subjects-list');
    const loadingState = document.getElementById('loading-state');
    const emptyState = document.getElementById('empty-state');
    //INITIALIZE SUBJECTS LOAD
    loadSubjects();
    // Modal handler for adding subject
    addSubjectBtn.addEventListener('click', function() {
        modal.style.display = 'block';
        modalContent.innerHTML = loadingText;
        
        fetchAddSubjectForm().then(data => {
            if(!data) {
                modal.style.display = 'none';
                return;
            }
            modalContent.innerHTML = data;
            const radioInput = document.querySelectorAll('input[name="subject"]');
            const selectContainer = document.getElementById('select-container');
            const checkboxContainer = document.getElementById('checkbox-container');
            const checkbox = document.getElementById("checkboxes");
            const initialRadioInput = document.querySelector('input[name="subject"][value="Yes"]');
            if (initialRadioInput) {
                initialRadioInput.checked = true;
            }  
            const toggleBtn = document.querySelector('.toggleCheckBox');
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function () {
                    checkbox.classList.toggle('show');
                });
            }
            updateDisplay(modal, selectContainer, checkboxContainer);
            radioInput.forEach(radio => {
                radio.addEventListener('change', function () {
                    updateDisplay(modal, selectContainer, checkboxContainer);
                });
            });
            close(modal);
            // Add cancel button event listener
            const cancelBtn = modalContent.querySelector('.btn-cancel');
            if (cancelBtn) {
                cancelBtn.addEventListener('click', function() {
                    window.location.href = 'admin_subjects.php';
                });
            }
            //ADD SUBJECT FORM HANDLER
            const form = document.getElementById('add-subject-form');
            let isSubmitting = false;
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                if(isSubmitting) return;
                isSubmitting = true;
                const submitButton = form.querySelector('.submit-button');
                submitButton.disabled = true;
                submitButton.style.backgroundColor = 'gray';
                Loader.show();
                const formData = new FormData(form);
                try {
                    const result = await postAddSubject(formData);
                    Notification.show({
                        type: 'success',
                        title: 'Success',
                        message: result.message
                    });
                    setTimeout(() => {
                        Loader.hide();
                        modal.style.display = 'none';
                        loadSubjects();
                        isSubmitting = false;
                    }, 1000);
                } catch(error) {
                    Loader.hide();
                    Notification.show({
                        type: 'error',
                        title: 'Error',
                        message: error.message
                    });
                    form.reset();
                    submitButton.disabled = false;
                    submitButton.style.backgroundColor = '';
                    isSubmitting = false;
                }
            });
        });
    });
    // Load subjects data
    async function loadSubjects() {
        loadingState.style.display = 'flex';
        subjectsList.style.display = 'none';
        emptyState.style.display = 'none';
        try {
            const response = await fetch('../../../BackEnd/api/admin/fetchSubjectsGrouped.php');
            const result = await response.json();

            if (result.success && result.data && result.data.length > 0) {
                displaySubjects(result.data);
            } else {
                emptyState.style.display = 'block';
                subjectsList.style.display = 'none';
            }
        } catch (error) {
            console.error('Error loading subjects:', error);
            Notification.show({
                type: 'error',
                title: 'Error',
                message: 'Failed to load subjects'
            });
            emptyState.style.display = 'block';
            subjectsList.style.display = 'none';
        } finally {
            loadingState.style.display = 'none';
        }
    }
    // Display subjects with expandable sections
    function displaySubjects(subjects) {
        subjectsList.style.display = 'block';
        emptyState.style.display = 'none';

        subjectsList.innerHTML = subjects.map(subject => {
            const sectionsCount = subject.Section_Count || 0;
            return `
                <div class="subject-item" data-subject-id="${subject.Subject_Id}">
                    <div class="subject-header">
                        <div class="subject-title-section">
                            <span class="subject-name">${escapeHtml(subject.Subject_Name)}</span>
                            <span class="sections-count">${sectionsCount} section${sectionsCount !== 1 ? 's' : ''}</span>
                        </div>

                        <div>
                            <button class="archive-btn" data-subject="${subject.Subject_Id}">
                                <img src="../../assets/imgs/box-archive-solid-full.svg" alt="archive" style="width:25px;height:25px;">
                            </button>
                            <button class="toggle-btn" aria-label="Toggle sections">
                                <svg class="chevron" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="6 9 12 15 18 9"></polyline>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="subject-content">
                        <div class="loading-container">
                            <div class="spinner"></div>
                            <p>Loading sections...</p>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
        // Attach toggle event listeners
        document.querySelectorAll('.subject-header').forEach(header => {
            header.addEventListener('click', async function() {
                const subjectItem = this.closest('.subject-item');
                const content = subjectItem.querySelector('.subject-content');
                const chevron = this.querySelector('.chevron');
                const isExpanded = content.classList.contains('expanded');
                
                if (isExpanded) {
                    content.classList.remove('expanded');
                    chevron.classList.remove('expanded');
                } else {
                    const subjectId = subjectItem.dataset.subjectId;
                    content.classList.add('expanded');
                    chevron.classList.add('expanded');
                    
                    if (!content.dataset.loaded) {
                        await loadSectionsForSubject(subjectId, content);
                        content.dataset.loaded = 'true';
                    }
                }
            });
        });
    }
    // Load sections for a specific subject
    async function loadSectionsForSubject(subjectId, contentElement) {
        try {
            const response = await fetch(`../../../BackEnd/api/admin/fetchSectionsBySubject.php?subject_id=${subjectId}`);
            const result = await response.json();

            if (result.success && result.data && result.data.length > 0) {
                displaySections(result.data, contentElement);
            } else {
                contentElement.innerHTML = '<div class="no-sections">No sections found for this subject</div>';
            }
        } catch (error) {
            console.error('Error loading sections:', error);
            contentElement.innerHTML = '<div class="no-sections">Error loading sections</div>';
        }
    }
    // Display sections table with teacher assignment
    function displaySections(sections, contentElement) {
        const sectionsHtml = `
            <div class="subject-actions">
                <div class="select-all-container">
                    <input type="checkbox" id="select-all-${sections[0].Section_Subjects_Id}" class="select-all-checkbox">
                    <label for="select-all-${sections[0].Section_Subjects_Id}">Select All</label>
                </div>
                <button class="batch-assign-btn" disabled>Assign Teacher to Selected</button>
            </div>
            <div class="sections-table-container">
                <table class="sections-table">
                    <thead>
                        <tr>
                            <th>Select</th>
                            <th>Grade Level</th>
                            <th>Section</th>
                            <th>Teacher</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${sections.map(section => {
                            const teacherName = section.Staff_First_Name && section.Staff_Last_Name
                                ? `${escapeHtml(section.Staff_Last_Name)}, ${escapeHtml(section.Staff_First_Name)} ${section.Staff_Middle_Name ? escapeHtml(section.Staff_Middle_Name) : ''}`
                                : '<span class="no-teacher">No teacher assigned</span>';
                            
                            return `
                                <tr>
                                    <td>
                                        <input type="checkbox" class="section-checkbox" 
                                               data-section-subject-id="${section.Section_Subjects_Id}">
                                    </td>
                                    <td>${escapeHtml(section.Grade_Level)}</td>
                                    <td>${escapeHtml(section.Section_Name)}</td>
                                    <td>${teacherName}</td>
                                </tr>
                            `;
                        }).join('')}
                    </tbody>
                </table>
            </div>
        `;
        
        contentElement.innerHTML = sectionsHtml;
        
        const selectAllCheckbox = contentElement.querySelector('.select-all-checkbox');
        const sectionCheckboxes = contentElement.querySelectorAll('.section-checkbox');
        const batchAssignBtn = contentElement.querySelector('.batch-assign-btn');
        
        selectAllCheckbox.addEventListener('change', function() {
            sectionCheckboxes.forEach(cb => {
                cb.checked = this.checked;
            });
            updateBatchAssignButton();
        });
        
        sectionCheckboxes.forEach(cb => {
            cb.addEventListener('change', updateBatchAssignButton);
        });
        
        function updateBatchAssignButton() {
            const checkedCount = Array.from(sectionCheckboxes).filter(cb => cb.checked).length;
            batchAssignBtn.disabled = checkedCount === 0;
            batchAssignBtn.textContent = checkedCount > 0 
                ? `Assign Teacher to ${checkedCount} Selected` 
                : 'Assign Teacher to Selected';
        }
        
        batchAssignBtn.addEventListener('click', function() {
            const selectedIds = Array.from(sectionCheckboxes)
                .filter(cb => cb.checked)
                .map(cb => parseInt(cb.dataset.sectionSubjectId));
            
            if (selectedIds.length > 0) {
                openBatchAssignModal(selectedIds);
            }
        });
    }

    // Open modal for single teacher assignment
    async function openSingleAssignModal(sectionSubjectId) {
        modal.style.display = 'block';
        modalContent.innerHTML = loadingText;
        
        try {
            const response = await fetchAllTeachers(sectionSubjectId);
            const teachers = response.data;
            
            const teachersHtml = teachers.map(teacher => {
                const isChecked = teacher.isChecked ? 'checked' : '';
                return `
                    <div class="teacher-option">
                        <input type="radio" name="subject-teacher" value="${teacher.Staff_Id}" ${isChecked}>
                        <label>${escapeHtml(teacher.Staff_Last_Name)}, ${escapeHtml(teacher.Staff_First_Name)}</label>
                    </div>
                `;
            }).join('');
            
            modalContent.innerHTML = `
                <div class="modal-header">
                    <h2 class="modal-title">Assign Teacher</h2>
                    <button class="close">&times;</button>
                </div>
                <form id="assign-teacher-form">
                    <div class="teacher-select-list">
                        ${teachersHtml}
                    </div>
                    <div class="modal-actions">
                        <button type="button" class="btn-cancel">Cancel</button>
                        <button type="submit" class="submit-button">Save</button>
                    </div>
                </form>
            `;
            
            close(modal);
            
            // Add cancel button event listener
            const cancelBtn = modalContent.querySelector('.btn-cancel');
            if (cancelBtn) {
                cancelBtn.addEventListener('click', function() {
                    window.location.href = 'admin_subjects.php';
                });
            }
            
            const form = document.getElementById('assign-teacher-form');
            const submitButton = form.querySelector('.submit-button');
            
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                Loader.show();
                
                try {
                    submitButton.disabled = true;
                    
                    const formData = new FormData(form);
                    formData.append('section-subject-id', sectionSubjectId);
                    
                    const result = await postAssignTeacherForm(formData);
                    
                    Notification.show({
                        type: 'success',
                        title: 'Success',
                        message: result.message
                    });
                    
                    setTimeout(() => {
                        Loader.hide();
                        modal.style.display = 'none';
                        loadSubjects();
                    }, 1000);
                } catch(error) {
                    Loader.hide();
                    Notification.show({
                        type: 'error',
                        title: 'Error',
                        message: error.message
                    });
                    submitButton.disabled = false;
                }
            });
        } catch(error) {
            Loader.hide();
            modal.style.display = 'none';
            Notification.show({
                type: 'error',
                title: 'Error',
                message: error.message
            });
        }
    }
    // Open modal for batch teacher assignment
    async function openBatchAssignModal(sectionSubjectIds) {
        modal.style.display = 'block';
        modalContent.innerHTML = loadingText;
        
        try {
            const response = await fetchAllTeachers(sectionSubjectIds[0]);
            const teachers = response.data;
            
            const teachersHtml = teachers.map(teacher => {
                return `
                    <div class="teacher-option">
                        <input type="radio" name="subject-teacher" value="${teacher.Staff_Id}">
                        <label>${escapeHtml(teacher.Staff_Last_Name)}, ${escapeHtml(teacher.Staff_First_Name)}</label>
                    </div>
                `;
            }).join('');
            
            modalContent.innerHTML = `
                <div class="modal-header">
                    <h2 class="modal-title">Assign Teacher to ${sectionSubjectIds.length} Sections</h2>
                    <button class="close">&times;</button>
                </div>
                <form id="batch-assign-teacher-form">
                    <div class="teacher-select-list">
                        ${teachersHtml}
                    </div>
                    <div class="modal-actions">
                        <button type="button" class="btn-cancel">Cancel</button>
                        <button type="submit" class="submit-button">Assign to All Selected</button>
                    </div>
                </form>
            `;
            
            close(modal);
            
            // Add cancel button event listener
            const cancelBtn = modalContent.querySelector('.btn-cancel');
            if (cancelBtn) {
                cancelBtn.addEventListener('click', function() {
                    window.location.href = 'admin_subjects.php';
                });
            }
            const form = document.getElementById('batch-assign-teacher-form');
            const submitButton = form.querySelector('.submit-button');
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                Loader.show();
                
                try {
                    submitButton.disabled = true;
                    
                    const selectedTeacherId = form.querySelector('input[name="subject-teacher"]:checked');
                    
                    if (!selectedTeacherId) {
                        throw new Error('Please select a teacher');
                    }
                    
                    const teacherId = parseInt(selectedTeacherId.value);
                    
                    const assignments = sectionSubjectIds.map(id => ({
                        section_subject_id: id,
                        staff_id: teacherId
                    }));
                    
                    const result = await postBatchAssignTeachers(assignments);
                    
                    Notification.show({
                        type: 'success',
                        title: 'Success',
                        message: result.message
                    });
                    
                    setTimeout(() => {
                        Loader.hide();
                        modal.style.display = 'none';
                        loadSubjects();
                    }, 1000);
                } catch(error) {
                    Loader.hide();
                    Notification.show({
                        type: 'error',
                        title: 'Error',
                        message: error.message
                    });
                    submitButton.disabled = false;
                }
            });
        } catch(error) {
            Loader.hide();
            modal.style.display = 'none';
            Notification.show({
                type: 'error',
                title: 'Error',
                message: error.message
            });
        }
    }
    function updateDisplay(modal, selectContainer, checkboxContainer) {
        const currentSelected = modal.querySelector('input[name="subject"]:checked');

        const selectControls = selectContainer.querySelectorAll('select, input, textarea');
        const checkboxControls = checkboxContainer.querySelectorAll('input, select, textarea');

        if (currentSelected && currentSelected.value === "Yes") {
            selectContainer.style.display = "none";
            checkboxContainer.style.display = "block";

            selectControls.forEach(el => {
                if (el.hasAttribute('name')) {
                    if (!el.hasAttribute('data-original-name')) {
                        el.setAttribute('data-original-name', el.getAttribute('name'));
                    }
                    el.removeAttribute('name');
                }
            });

            checkboxControls.forEach(el => {
                if (el.hasAttribute('data-original-name')) {
                    el.setAttribute('name', el.getAttribute('data-original-name'));
                }
            });
        } else {
            selectContainer.style.display = "block";
            checkboxContainer.style.display = "none";

            checkboxControls.forEach(el => {
                if (el.hasAttribute('name')) {
                    if (!el.hasAttribute('data-original-name')) {
                        el.setAttribute('data-original-name', el.getAttribute('name'));
                    }
                    el.removeAttribute('name');
                }
            });

            selectControls.forEach(el => {
                if (el.hasAttribute('data-original-name')) {
                    el.setAttribute('name', el.getAttribute('data-original-name'));
                }
            });
        }
    }
    //ARCHIVE SUBJECTS 
    subjectsList.addEventListener('click',async function(e){
        const archiveBtn = e.target.closest('.archive-btn');
        if(!archiveBtn) return;

        const subjectId = archiveBtn.getAttribute('data-subject');
        await archiveSubject(subjectId);
    });
    // Helper function to escape HTML
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    async function archiveSubject(subjectId) {
        if(confirm('Are you sure you want to archive this subject?')) {
            Loader.show();
            const result = await postArchiveSubject(subjectId);
            if(!result.success) {
                Loader.hide();
                Notification.show({
                    type: 'error',
                    title: 'Error',
                    message: result.message
                });
            }
            else {
                Notification.show({
                    type: 'success',
                    title: 'Success',
                    message: result.message
                }); 
                setTimeout(() => {
                    Loader.hide();
                    loadSubjects();
                }, 1000);
            }
        }
    }
});
// API Functions
const TIME_OUT = 30000;
async function postArchiveSubject(subjectId)  {
    const controller = new AbortController();
    const timeoutId = setTimeout(()=> controller.abort(),TIME_OUT);
    try {
        const response = await fetch(`../../../BackEnd/api/admin/postArchiveSubject.php`,{
            signal: controller.signal,
            method: 'POST',
            body: new URLSearchParams({ "subject-id": subjectId})
        });
        clearTimeout(timeoutId);
        let data;
        try {
            data = await response.json();
        }
        catch{
            throw new Error('Invalid response');
        }
        if(!response.ok) {
            return {
                success: false,
                message: data.message || `HTTP ERROR: Request responded with status ${response.status}`,
                data: null
            };
        }
        return data;
    }
    catch(error) {
        if(error.name === "AbortError") {
            return {
                success: false,
                message: `Request timeout. Server took too long to response: took ${TIME_OUT/1000} seconds`,
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
async function fetchAllTeachers(subjectId) {
    const response = await fetch(`../../../BackEnd/api/admin/fetchAllTeachers.php?sec-sub-id=${subjectId}`);

    let data;
    try {
        data = await response.json();
    } catch {
        throw new Error('Invalid response');
    }

    if(!response.ok) {
        throw new Error(data.message || `HTTP error: ${response.status}`);
    }
    
    if(!data.success) {
        throw new Error(data.message);
    }
    
    return data;
}
async function fetchAddSubjectForm() {
    try {
        let response = await fetch(`../../../BackEnd/templates/admin/fetchAddSubjectForm.php`);
        let data = await response.text();
        
        if(!response.ok) {
            console.error(`Error: ${data.message}`);
            return null;
        }
        
        return data;
    } catch(err) {
        console.error(err);
        return null;
    }
}
async function postAddSubject(formData) {
    const response = await fetch(`../../../BackEnd/api/admin/postAddSubjects.php`, {
        method: 'POST',
        body: formData
    });
    
    let data;
    try {
        data = await response.json();
    } catch {
        throw new Error("Invalid JSON response");
    }
    
    if (!response.ok){
        throw new Error(data.message || `HTTP error: ${response.status}`);
    }

    if(!data.success) {
        throw new Error(data.message || 'Something went wrong');
    }
    
    return data;
}

async function postAssignTeacherForm(formData) {
    const response = await fetch("../../../BackEnd/api/admin/postAssignSubjectTeacher.php", {
        method: "POST",
        body: formData,
    });

    let data;
    try {
        data = await response.json();
    } catch {
        throw new Error("Invalid JSON response from server");
    }

    if (!response.ok) {
        throw new Error(data.message || `HTTP error: ${response.status}`);
    }

    if (!data.success) {
        throw new Error(data.message || "Something went wrong");
    }

    return data;
}

async function postBatchAssignTeachers(assignments) {
    const response = await fetch("../../../BackEnd/api/admin/postBatchAssignTeachers.php", {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ assignments }),
    });

    let data;
    try {
        data = await response.json();
    } catch {
        throw new Error("Invalid JSON response from server");
    }

    if (!response.ok) {
        throw new Error(data.message || `HTTP error: ${response.status}`);
    }

    if (!data.success) {
        throw new Error(data.message || "Something went wrong");
    }

    return data;
}
