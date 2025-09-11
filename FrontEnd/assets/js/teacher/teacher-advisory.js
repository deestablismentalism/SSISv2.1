document.addEventListener('DOMContentLoaded', function() {
    const advisoryButton = document.querySelectorAll('.view-student-button');
    const modal = document.getElementById('student-view-modal');
    const modalContent = document.getElementById('student-modal-content');
    advisoryButton.forEach(button => {
        button.addEventListener('click', function() {
            const studentId = this.getAttribute('data-id');

            modal.style.display = 'block';
            modalContent.innerHTML = "...Loading";
            fetch(`../../../BackEnd/templates/teacher/fetchStudentInformationModal.php?student_id=` + encodeURIComponent(studentId))
            .then(response => response.text())
            .then(data=> {
                modalContent.innerHTML = data;

                const close = modal.querySelector('.close');
                close.addEventListener('click', function() {
                    modal.style.display = 'none';
                })
            })
            .catch(error=>{
                modalContent.innerHTML = error;
            })
        });
    });
});