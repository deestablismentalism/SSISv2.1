document.addEventListener('DOMContentLoaded', function() {
    const tbody = document.querySelector('tbody');

    if (tbody.rows.length == 0) {
        const row = document.createElement('tr');
        const cell = document.createElement('td');
        cell.style.textAlign = 'center';
        const enrolleeLink = document.createElement('a');
        enrolleeLink.href = '../userPages/Enrollment_Form.php';
        enrolleeLink.textContent = 'Fill out an enrollment form';
        enrolleeLink.className = 'enrollee-link';

        cell.appendChild(enrolleeLink);
        row.appendChild(cell);
        tbody.appendChild(row);
    }
});