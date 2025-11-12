import{close,loadingText, modalHeader} from '../utils.js';
document.addEventListener('DOMContentLoaded', function(){
    const gradeLevelsList = document.getElementById('grade-levels-list');
    const loadingState = document.getElementById('loading-state');
    const emptyState = document.getElementById('empty-state');
    async function loadGradeLevels() {
        loadingState.style.display = 'flex';
        gradeLevelsList.style.display = 'none';
        emptyState.style.display = 'none';
        try {
            const response = await fetch('../../../BackEnd/api/admin/fetchSectionScheduleSummary.php');
            const result = await response.json();

            if (result.success && result.data && result.data.length > 0) {
                displayGradeLevels(result.data);
            } else {
                emptyState.style.display = 'block';
                gradeLevelsList.style.display = 'none';
            }
        } catch (error) {
            console.error('Error loading grade levels:', error);
            Notification.show({
                type: 'error',
                title: 'Error',
                message: 'Failed to load grade levels'
            });
            emptyState.style.display = 'block';
            gradeLevelsList.style.display = 'none';
        } finally {
            loadingState.style.display = 'none';
        }
    }
    // Display grade levels with expandable sections
    function displayGradeLevels(gradeLevels) {
        gradeLevelsList.style.display = 'block';
        emptyState.style.display = 'none';

        gradeLevelsList.innerHTML = gradeLevels.map(gradeLevel => {
            const sectionsCount = gradeLevel.Sections ? gradeLevel.Sections.length : 0;
            const sectionsHtml = gradeLevel.Sections && gradeLevel.Sections.length > 0
                ? `
                    <div class="sections-table-container">
                        <table class="sections-table">
                            <thead>
                                <tr>
                                    <th>Section</th>
                                    <th>Subjects</th>
                                    <th>Schdeuled Subjects</th>
                                    <th> Handle Schedules</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${gradeLevel.Sections.map(section => `
                                    <tr>
                                        <td>${escapeHtml(section.Section_Name)}</td>
                                        <td>${section.Total_Subjects}</td>
                                        <td>${section.Scheduled_Subjects}</td>
                                        <td>
                                            <a href="./admin_handle_schedules.php?section_id=${section.Section_Id}" class="view-btn">
                                                <img src="../../assets/imgs/eye-regular.svg" alt="View">
                                            </a>
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                `
                : '<div class="no-sections">No sections in this grade level</div>';
            return `
                <div class="grade-level-item">
                    <div class="grade-level-header" data-grade-id="${gradeLevel.Grade_Level_Id}">
                        <div class="grade-level-title">
                            <span class="grade-level-name">${escapeHtml(gradeLevel.Grade_Level)}</span>
                            <span class="sections-count">${sectionsCount} section${sectionsCount !== 1 ? 's' : ''}</span>
                        </div>
                        <button class="toggle-btn" aria-label="Toggle sections">
                            <svg class="chevron" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                    </div>
                    <div class="grade-level-content" style="display: none;">
                        ${sectionsHtml}
                    </div>
                </div>
            `;
        }).join('');
        // Attach toggle event listeners
        document.querySelectorAll('.grade-level-header').forEach(header => {
            header.addEventListener('click', function() {
                const content = this.nextElementSibling;
                const chevron = this.querySelector('.chevron');
                const isExpanded = content.style.display !== 'none';
                
                content.style.display = isExpanded ? 'none' : 'block';
                chevron.style.transform = isExpanded ? 'rotate(0deg)' : 'rotate(180deg)';
            });
        });
        // Attach view button handlers
        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                Loader.show();
                const target = this.href;
                setTimeout(() => window.location.href = target, 100);
            });
        });
    }
    // Helper function to escape HTML
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    loadGradeLevels();
});
async function fetchSectionSubjects() {
    try {
        const response = await fetch(`../../../BackEnd/api/admin/fetchAllSubjects.php`);

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
                message: data.message || `HTTP ERROR: ${response.status}`,
                data: null
            };
        }
        if(!data.success) {
            return {
                success: false,
                message: data.message || `Something went wrong`,
                data: null
            };
        }
        return data;
    }
    catch(error) {
        return {
            success: false,
            message: error.message || `There was an error`,
            data: null
        };
    }
}
async function fetchAddScheduleForm() {
    const response = await fetch(`../../../BackEnd/templates/admin/fetchAddScheduleForm.php`);

    let data;
    try {
        data = await response.text();
    }
    catch{
        throw new Error('Cannot proccess response');
    }
    if(!response.ok) {
        throw new Error(`HTTP error: ${response.status}`);
    }
    return data;
}
async function postAddSectionSchedule(formData) {
    try {
        const response = await fetch(`../../../BackEnd/api/admin/postAddSectionSchedule.php`, {
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
                message: data.message || `HTTP ERROR: ${response.status}`,
                data: null
            };
        }
        if(!data.success) {
            return {
                success: false,
                message: data.message || `Something went wrong`,
                data: null
            };
        }
        return data;
    }
    catch(error) {
        return {
            success: false,
            message: error.message || `There was unexpected error`,
            data: null
        };
    }
}

