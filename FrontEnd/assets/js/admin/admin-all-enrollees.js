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
});
