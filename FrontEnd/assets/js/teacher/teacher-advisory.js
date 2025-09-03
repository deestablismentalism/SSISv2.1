document.addEventListener('DOMContentLoaded', function() {
    const advisoryButton = document.querySelectorAll('.view-student-button');
   
    advisoryButton.forEach(button => {
        button.addEventListener('click', function() {
            const studentId = this.getAttribute('data-id');
            console.log(studentId);
        });
    });
});