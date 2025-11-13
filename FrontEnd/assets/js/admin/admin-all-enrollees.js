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
    
    // Get all view buttons
    const viewButtons = document.querySelectorAll('.view-button');
    const modal = document.getElementById('enrolleeModal');
    const modalContent = document.querySelector('.modal-content');
    
    // Add click event to each button
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const enrolleeId = this.getAttribute('data-id');
            // Fetch enrollee details via AJAX
            modal.style.display = 'block';
            modalContent.innerHTML = '<p>Loading enrollee details...</p>';
            fetch(`../../../BackEnd/admin/adminEnrolleeInfoView.php?id=${enrolleeId}`)
                .then(response => response.text())
                .then(data => {
                    modalContent.innerHTML = data;
                })
                .catch(error => console.error('Error:', error));
        });
    });
    
    // Close modal when X is clicked
    document.addEventListener('click', function(e) {
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
