function initHistoricalGrades() {
    const loadingState = document.getElementById('loading-state');
    const emptyState = document.getElementById('empty-state');
    const yearContainer = document.getElementById('by-year-grades-list');
    const params = new URLSearchParams(window.location.search);
    const studentId = params.get('student-id');
    async function loadAcademicYears(studentId) {
        
        if (!yearContainer) return; // exit if container not found

        loadingState.style.display = 'flex';
        yearContainer.style.display = 'none';
        emptyState.style.display = 'none';
        yearContainer.innerHTML = '<p>Loading...</p>';
        try {
            const response = await fetch('../../../BackEnd/api/student/fetchGradesGroupedByYear.php?student-id=' + studentId);
            const result = await response.json();

            if (!result.success || !result.data || result.data.length === 0) {
                emptyState.style.display = 'block';
                yearContainer.style.display = 'none';
                return;
            }

            displayAcademicYears(result.data);
        } catch (error) {
            console.error('Error loading academic years:', error);
            emptyState.style.display = 'block';
            yearContainer.style.display = 'none';
        } finally {
            loadingState.style.display = 'none';
        }
    }

    function displayAcademicYears(years) {
        yearContainer.style.display = 'block';
        emptyState.style.display = 'none';

        yearContainer.innerHTML = years.map(year => {
            const subjectsHtml = year.grades && year.grades.length > 0
                ? `
                    <div class="subjects-table-container">
                        <table class="subjects-table">
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Q1</th>
                                    <th>Q2</th>
                                    <th>Q3</th>
                                    <th>Q4</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${year.grades.map(grade => `
                                    <tr>
                                        <td>${escapeHtml(grade.Subject_Name)}</td>
                                        <td>${grade.Q1 ?? ''}</td>
                                        <td>${grade.Q2 ?? ''}</td>
                                        <td>${grade.Q3 ?? ''}</td>
                                        <td>${grade.Q4 ?? ''}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                `
                : '<div class="no-subjects">No grades available for this year</div>';

            return `
                <div class="academic-year-item">
                    <div class="academic-year-header" data-year-id="${year.school_year_id}">
                        <div class="year-title">
                            ${year.start_year ?? 'Unknown'} - ${year.end_year ?? 'Unknown'}
                        </div>
                        <button class="toggle-btn" aria-label="Toggle subjects">
                            <svg class="chevron" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                    </div>
                    <div class="academic-year-content" style="display: none;">
                        ${subjectsHtml}
                    </div>
                </div>
            `;
        }).join('');
    // Attach toggle events
    document.querySelectorAll('.academic-year-header').forEach(header => {
        header.addEventListener('click', function() {
        const content = this.nextElementSibling;
        const chevron = this.querySelector('.chevron');
        const isExpanded = content.style.display !== 'none';

            content.style.display = isExpanded ? 'none' : 'block';
            chevron.style.transform = isExpanded ? 'rotate(0deg)' : 'rotate(180deg)';
            });
        });
    }
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    // populate grades
    loadAcademicYears(studentId);
}
// call it after inserting template
initHistoricalGrades();