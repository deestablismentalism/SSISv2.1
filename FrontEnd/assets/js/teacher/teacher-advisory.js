import{close,loadingText,modalHeader} from '../utils.js';

document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('student-view-modal');
    const modalContent = document.getElementById('student-modal-content');
    const params = new URLSearchParams(window.location.search);
    const advisoryId = params.get('adv_id');
    // Use event delegation for dynamically added buttons
    document.addEventListener('click', async function(e) {
        if (e.target.classList.contains('view-student-button')) {
            const studentId = e.target.getAttribute('data-id');
            if (!studentId) return;
            
            // Show modal with flex
            modal.classList.add('show');
            modal.style.display = 'flex';
            modalContent.innerHTML = loadingText;
            
            try {
                const response = await 
                fetch(`../../../BackEnd/templates/teacher/fetchStudentInformationModal.php?student_id=${encodeURIComponent(studentId)}&adv_id=${advisoryId}`);
                
                if (!response.ok) {
                    throw new Error(`HTTP error: ${response.status}`);
                }
                
                const data = await response.text();
                modalContent.innerHTML = modalHeader();
                modalContent.innerHTML += data;
                close(modal);
            }
            catch(error) {
                console.error('Error fetching student information:', error);
                modalContent.innerHTML = modalHeader();
                modalContent.innerHTML += '<div class="error-message" style="padding: 1.5rem; color: #c33; background: #fee; border-radius: 8px; margin: 1rem; text-align: center; font-weight: 600;">Error: ' + error.message + '</div>';
                close(modal);
            }
        }
    });
    
    // Add search functionality
    const studentsWrapper = document.querySelector('.students-list-wrapper');
    if (studentsWrapper) {
        const table = studentsWrapper.querySelector('.students-list');
        if (table) {
            // Create search and sort controls container
            const controlsContainer = document.createElement('div');
            controlsContainer.style.cssText = 'margin-bottom: 1rem; display: flex; gap: 0.75rem; align-items: center;';
            controlsContainer.innerHTML = `
                <input type="text" id="student-search" placeholder="Search students by name..." 
                    style="flex: 1; padding: 0.75rem 1rem; border: 2px solid #3e9ec4; border-radius: 8px; font-size: 0.95rem; outline: none; transition: border-color 0.3s;">
                <button id="sort-students-btn" data-sort="asc" 
                    style="padding: 0.75rem 1.5rem; background: #3e9ec4; color: white; border: none; border-radius: 8px; font-size: 0.95rem; font-weight: 600; cursor: pointer; transition: all 0.3s; white-space: nowrap; display: flex; align-items: center; gap: 0.5rem;">
                    <span>Sort A-Z</span>
                    <span style="font-size: 1.1rem;">↓</span>
                </button>
            `;
            studentsWrapper.insertBefore(controlsContainer, table);
            
            const searchInput = document.getElementById('student-search');
            const sortButton = document.getElementById('sort-students-btn');
            const tbody = table.querySelector('tbody');
            const rows = table.querySelectorAll('tbody tr');
            
            // Sort functionality
            sortButton.addEventListener('click', function() {
                const currentSort = this.getAttribute('data-sort');
                const newSort = currentSort === 'asc' ? 'desc' : 'asc';
                
                // Get all rows as array
                const rowsArray = Array.from(tbody.querySelectorAll('tr'));
                
                // Sort rows by student name
                rowsArray.sort((rowA, rowB) => {
                    const nameA = rowA.querySelector('td:first-child')?.textContent.trim() || '';
                    const nameB = rowB.querySelector('td:first-child')?.textContent.trim() || '';
                    
                    // Extract name without numbering (remove "1. ", "2. " etc.)
                    const cleanNameA = nameA.replace(/^\d+\.\s*/, '').toLowerCase();
                    const cleanNameB = nameB.replace(/^\d+\.\s*/, '').toLowerCase();
                    
                    if (newSort === 'asc') {
                        return cleanNameA.localeCompare(cleanNameB);
                    } else {
                        return cleanNameB.localeCompare(cleanNameA);
                    }
                });
                
                // Re-append sorted rows and update numbering
                rowsArray.forEach((row, index) => {
                    tbody.appendChild(row);
                    
                    // Update numbering only in the span element
                    const nameCell = row.querySelector('td:first-child');
                    if (nameCell) {
                        const numberSpan = nameCell.querySelector('span');
                        if (numberSpan) {
                            numberSpan.textContent = `${index + 1}. `;
                        }
                    }
                });
                
                // Update button state
                this.setAttribute('data-sort', newSort);
                const buttonText = this.querySelector('span:first-child');
                const buttonIcon = this.querySelector('span:last-child');
                
                if (newSort === 'asc') {
                    buttonText.textContent = 'Sort A-Z';
                    buttonIcon.textContent = '↓';
                    this.style.background = '#3e9ec4';
                } else {
                    buttonText.textContent = 'Sort Z-A';
                    buttonIcon.textContent = '↑';
                    this.style.background = '#2d7a9a';
                }
            });
            
            // Hover effect for sort button
            sortButton.addEventListener('mouseenter', function() {
                this.style.background = '#2d7a9a';
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 4px 12px rgba(62, 158, 196, 0.3)';
            });
            
            sortButton.addEventListener('mouseleave', function() {
                const currentSort = this.getAttribute('data-sort');
                this.style.background = currentSort === 'asc' ? '#3e9ec4' : '#2d7a9a';
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'none';
            });
            
            // Search functionality
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                let visibleCount = 0;
                
                const allRows = tbody.querySelectorAll('tr');
                allRows.forEach(row => {
                    const nameCell = row.querySelector('td:first-child');
                    if (nameCell) {
                        const name = nameCell.textContent.toLowerCase();
                        if (name.includes(searchTerm)) {
                            row.style.display = '';
                            visibleCount++;
                        } else {
                            row.style.display = 'none';
                        }
                    }
                });
                
                // Show "no results" message if needed
                let noResultsMsg = studentsWrapper.querySelector('.no-results');
                if (visibleCount === 0 && searchTerm !== '') {
                    if (!noResultsMsg) {
                        noResultsMsg = document.createElement('div');
                        noResultsMsg.className = 'no-results';
                        noResultsMsg.style.cssText = 'text-align: center; padding: 2rem; color: #666; font-style: italic;';
                        noResultsMsg.textContent = 'No students found matching your search.';
                        table.parentNode.insertBefore(noResultsMsg, table.nextSibling);
                    }
                } else if (noResultsMsg) {
                    noResultsMsg.remove();
                }
            });
            
            // Add focus styles
            searchInput.addEventListener('focus', function() {
                this.style.borderColor = '#2d7a9a';
                this.style.boxShadow = '0 0 0 3px rgba(62, 158, 196, 0.1)';
            });
            
            searchInput.addEventListener('blur', function() {
                this.style.borderColor = '#3e9ec4';
                this.style.boxShadow = 'none';
            });
        }
    }
});