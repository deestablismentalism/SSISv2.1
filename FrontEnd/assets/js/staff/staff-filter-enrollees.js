document.addEventListener('DOMContentLoaded', function() {
    const filterGrade = document.getElementById('filter-grade');
    const filterSex = document.getElementById('filter-sex');
    const clearFiltersBtn = document.getElementById('clear-filters');
    const tableBody = document.getElementById('query-table');
    const searchBox = document.getElementById('search');
    
    // Populate filter dropdowns with unique values from table
    function populateFilters() {
        const rows = tableBody.getElementsByTagName('tr');
        const grades = new Set();
        const sexes = new Set();
        
        Array.from(rows).forEach(row => {
            const cells = row.getElementsByTagName('td');
            if (cells.length >= 5) {
                // Column 3 is Grade Level
                const grade = cells[3].textContent.trim();
                if (grade && grade !== 'N/A') {
                    grades.add(grade);
                }
                
                // Column 4 is Biological Sex
                const sex = cells[4].textContent.trim();
                if (sex && sex !== 'No Biological Sex provided') {
                    sexes.add(sex);
                }
            }
        });
        
        // Sort grades numerically
        const sortedGrades = Array.from(grades).sort((a, b) => {
            const numA = parseInt(a.match(/\d+/)?.[0] || 0);
            const numB = parseInt(b.match(/\d+/)?.[0] || 0);
            return numA - numB;
        });
        
        // Populate grade filter
        sortedGrades.forEach(grade => {
            const option = document.createElement('option');
            option.value = grade;
            option.textContent = grade;
            filterGrade.appendChild(option);
        });
        
        // Populate sex filter
        const sortedSexes = Array.from(sexes).sort();
        sortedSexes.forEach(sex => {
            const option = document.createElement('option');
            option.value = sex;
            option.textContent = sex;
            filterSex.appendChild(option);
        });
    }
    
    // Apply all filters (search + grade + sex)
    function applyFilters() {
        const searchQuery = searchBox.value.trim().toLowerCase();
        const selectedGrade = filterGrade.value;
        const selectedSex = filterSex.value;
        const rows = Array.from(tableBody.getElementsByTagName('tr'));
        
        rows.forEach(row => {
            const cells = row.getElementsByTagName('td');
            if (cells.length < 5) {
                row.style.display = '';
                return;
            }
            
            const lrn = cells[0].textContent.toLowerCase();
            const name = cells[1].textContent.toLowerCase();
            const grade = cells[3].textContent.trim();
            const sex = cells[4].textContent.trim();
            
            // Check search filter (LRN or Name)
            const matchesSearch = searchQuery === '' || 
                                 lrn.includes(searchQuery) || 
                                 name.includes(searchQuery);
            
            // Check grade filter
            const matchesGrade = selectedGrade === 'all' || grade === selectedGrade;
            
            // Check sex filter
            const matchesSex = selectedSex === 'all' || sex === selectedSex;
            
            // Show row only if all filters match
            if (matchesSearch && matchesGrade && matchesSex) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
    
    // Clear all filters
    function clearFilters() {
        filterGrade.value = 'all';
        filterSex.value = 'all';
        searchBox.value = '';
        applyFilters();
    }
    
    // Event listeners
    filterGrade.addEventListener('change', applyFilters);
    filterSex.addEventListener('change', applyFilters);
    clearFiltersBtn.addEventListener('click', clearFilters);
    
    // Override search box to work with filters
    searchBox.addEventListener('input', applyFilters);
    
    // Initialize filters on page load
    populateFilters();
});
