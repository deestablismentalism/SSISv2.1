document.addEventListener('DOMContentLoaded', function() {
    const sortableHeaders = document.querySelectorAll('th.sortable');
    const tableBody = document.getElementById('query-table');
    
    sortableHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const columnIndex = parseInt(this.getAttribute('data-column'));
            const currentOrder = this.getAttribute('data-order');
            
            // Reset all other headers
            sortableHeaders.forEach(h => {
                if (h !== this) {
                    h.setAttribute('data-order', 'none');
                }
            });
            
            // Toggle order: none -> asc -> desc -> asc
            let newOrder;
            if (currentOrder === 'none' || currentOrder === 'desc') {
                newOrder = 'asc';
            } else {
                newOrder = 'desc';
            }
            
            this.setAttribute('data-order', newOrder);
            sortTable(columnIndex, newOrder);
        });
    });
    
    function sortTable(columnIndex, order) {
        const rows = Array.from(tableBody.getElementsByTagName('tr'));
        
        // Separate visible and hidden rows
        const visibleRows = rows.filter(row => row.style.display !== 'none');
        const hiddenRows = rows.filter(row => row.style.display === 'none');
        
        visibleRows.sort((a, b) => {
            const cellA = a.getElementsByTagName('td')[columnIndex];
            const cellB = b.getElementsByTagName('td')[columnIndex];
            
            if (!cellA || !cellB) return 0;
            
            let valueA = cellA.textContent.trim();
            let valueB = cellB.textContent.trim();
            
            // Handle Grade Level column (index 3) - numeric sorting
            if (columnIndex === 3) {
                // Extract numeric part from grade level (e.g., "Grade 7" -> 7)
                const gradeA = extractGradeNumber(valueA);
                const gradeB = extractGradeNumber(valueB);
                
                if (order === 'asc') {
                    return gradeA - gradeB;
                } else {
                    return gradeB - gradeA;
                }
            }
            
            // Handle Student Name column (index 1) - alphabetical sorting
            if (columnIndex === 1) {
                if (order === 'asc') {
                    return valueA.localeCompare(valueB);
                } else {
                    return valueB.localeCompare(valueA);
                }
            }
            
            return 0;
        });
        
        // Clear and re-append rows (visible sorted first, then hidden)
        tableBody.innerHTML = '';
        visibleRows.forEach(row => tableBody.appendChild(row));
        hiddenRows.forEach(row => tableBody.appendChild(row));
    }
    
    function extractGradeNumber(gradeString) {
        // Handle "N/A" or empty values
        if (!gradeString || gradeString === 'N/A') {
            return -1; // Put N/A at the beginning
        }
        
        // Extract number from strings like "Grade 7", "7", "Grade 11", etc.
        const match = gradeString.match(/\d+/);
        return match ? parseInt(match[0]) : -1;
    }
});
