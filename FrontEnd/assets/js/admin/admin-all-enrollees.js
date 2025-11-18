document.addEventListener('DOMContentLoaded', function() {
    // Apply status colors
    const rows = document.querySelectorAll('.enrollee-row');
    rows.forEach(row => {
        const statusCell = row.querySelector('td:nth-child(4)');
        if (statusCell) {
            const status = statusCell.textContent.trim().toLowerCase();
            statusCell.innerHTML = `<span class="status-cell status-${status}">${status.toUpperCase()}</span>`;
        }
    });
    
    // Get modal elements
    const modal = document.getElementById('enrolleeModal');
    const modalContent = document.querySelector('.modal-content');
    
    // Event delegation for view enrollee buttons
    document.addEventListener('click', async function(e) {
        if (e.target.classList.contains('view-enrollee')) {
            const enrolleeId = e.target.getAttribute('data-id');
            
            if (!enrolleeId) {
                console.error('No enrollee ID found');
                return;
            }
            
            // Show modal with loading state
            modal.style.display = 'block';
            modalContent.innerHTML = '<div class="loading-container"><p>Loading enrollee details...</p></div>';
            
            try {
                const response = await fetch(`../../../BackEnd/templates/admin/fetchEnrolleeInfo.php?id=${encodeURIComponent(enrolleeId)}`);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.text();
                modalContent.innerHTML = `
                    <span class="close">&times;</span>
                    <div class="enrollee-details">
                        ${data}
                    </div>
                `;
            } catch (error) {
                console.error('Error fetching enrollee details:', error);
                modalContent.innerHTML = `
                    <span class="close">&times;</span>
                    <div class="error-message">Failed to load enrollee details. Please try again.</div>
                `;
            }
        }
        
        // Close modal when X is clicked
        if (e.target.classList.contains('close')) {
            modal.style.display = 'none';
        }
    });
    
    // Close modal when clicking outside
    window.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
    
    // Enhanced Search functionality
    const searchInput = document.getElementById('search');
    searchInput.addEventListener('keyup', function() {
        const query = this.value.toLowerCase();
        const rows = document.querySelectorAll('.enrollee-row');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if (query === '' || text.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
    
    // Filtering functionality
    const searchBox = document.getElementById('search');
    const statusFilter = document.getElementById('status-filter');
    const sourceFilter = document.getElementById('source-filter');
    const table = document.querySelector('.enrollees-table tbody');
    
    function filterTable() {
        const searchTerm = searchBox.value.toLowerCase();
        const statusValue = statusFilter.value;
        const sourceValue = sourceFilter.value;
        const rows = table.querySelectorAll('tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const statusBadge = row.querySelector('.status-badge');
            const sourceBadge = row.querySelector('.source-badge');
            
            let matchesSearch = searchTerm === '' || text.includes(searchTerm);
            let matchesStatus = statusValue === '' || 
                (statusBadge && statusBadge.textContent.toLowerCase() === getStatusText(statusValue));
            let matchesSource = sourceValue === '' || 
                (sourceBadge && sourceBadge.textContent.toLowerCase() === sourceValue);
            
            row.style.display = (matchesSearch && matchesStatus && matchesSource) ? '' : 'none';
        });
        
        updateCount();
    }
    
    function getStatusText(value) {
        const map = { '1': 'enrolled', '2': 'rejected', '3': 'pending', '4': 'archived' };
        return map[value] || '';
    }
    
    function updateCount() {
        const visibleRows = table.querySelectorAll('tr:not([style*="display: none"])').length;
        const totalRows = table.querySelectorAll('tr').length;
        const countElement = document.querySelector('.count-number');
        if (countElement) {
            countElement.textContent = `${visibleRows} / ${totalRows}`;
        }
    }
    
    if (searchBox) searchBox.addEventListener('input', filterTable);
    if (statusFilter) statusFilter.addEventListener('change', filterTable);
    if (sourceFilter) sourceFilter.addEventListener('change', filterTable);
});
