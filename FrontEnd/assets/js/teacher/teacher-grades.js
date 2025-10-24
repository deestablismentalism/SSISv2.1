import {modalHeader,loadingText,close} from '../utils.js';
document.addEventListener('DOMContentLoaded',function(){
    const gradeButton = document.querySelectorAll('#grade-button');
    const modal = document.querySelector('.modal');
    const modalContent = document.querySelector('.modal-content');
    gradeButton.forEach(button=>{
        button.addEventListener('click', async function(e){
            modal.style.display = 'block';
            modalContent.innerHTML = modalHeader()
            modalContent.innerHTML += loadingText;
            try {
                const secSubId = e.target.getAttribute('data-id');
                const result = await fetchStudentsOfSectionSubject(secSubId);

                if(!result.success) {
                    alert(result.message);
                }
                else {
                    const table = document.createElement('table');
                    table.className = 'grade-table'; // Optional: add a class for styling
                    // Create table header
                    const thead = document.createElement('thead');
                    const headerRow = document.createElement('tr');
                    const nameHeader = document.createElement('th');
                    nameHeader.textContent = 'Student Name';
                    //4 quarters of headers
                    const firstQuarter = document.createElement('th');
                    firstQuarter.textContent = '1st Quarter';
                    const secondQuarter = document.createElement('th');
                    secondQuarter.textContent = '2nd Quarter';
                    const thirdQuarter = document.createElement('th');
                    thirdQuarter.textContent = '3rd Quarter';
                    const fourthQuarter = document.createElement('th');
                    fourthQuarter.textContent = '4th Quarter';
                    
                    headerRow.appendChild(nameHeader);
                    headerRow.appendChild(firstQuarter);
                    headerRow.appendChild(secondQuarter);
                    headerRow.appendChild(thirdQuarter);
                    headerRow.appendChild(fourthQuarter);
                    thead.appendChild(headerRow);
                    table.appendChild(thead);
                    
                    // Create table body
                    const tbody = document.createElement('tbody');
                    const students = result.data;
                    students.forEach(student => {
                        const row = document.createElement('tr');
                        
                        // Student name cell
                        const nameCell = document.createElement('td');
                        nameCell.textContent = student.Last_Name + ', ' + student.First_Name; 
                        // 4 quarter input cell
                        const firstCell = document.createElement('td');
                        const firstInput = document.createElement('input');
                        firstInput.type = 'number';
                        firstInput.name = 'student-first';
                        firstInput.value = student.grade || ''; // Pre-fill existing first if available
                        firstInput.min = 0;
                        firstInput.step = 0.01;
                        firstInput.max = 100;
                        firstInput.setAttribute('data-student-id', student.Student_Id); // Store student ID for submission
                        firstCell.appendChild(firstInput);
                        
                        const secondCell = document.createElement('td');
                        const secondInput = document.createElement('input');
                        secondInput.type = 'number';
                        secondInput.name = 'student-second';
                        secondInput.value = student.grade || ''; // Pre-fill existing second if available
                        secondInput.min = 0;
                        secondInput.step = 0.01;
                        secondInput.max = 100;
                        secondInput.setAttribute('data-student-id', student.Student_Id); // Store student ID for submission
                        secondCell.appendChild(secondInput);

                        const thirdCell = document.createElement('td');
                        const thirdInput = document.createElement('input');
                        thirdInput.type = 'number';
                        thirdInput.name = 'student-third';
                        thirdInput.value = student.grade || ''; // Pre-fill existing third if available
                        thirdInput.min = 0;
                        thirdInput.step = 0.01;
                        thirdInput.max = 100;
                        thirdInput.setAttribute('data-student-id', student.Student_Id); // Store student ID for submission
                        thirdCell.appendChild(thirdInput);

                        const fourthCell = document.createElement('td');
                        const fourthInput = document.createElement('input');
                        fourthInput.type = 'number';
                        fourthInput.name = 'student-fourth';
                        fourthInput.value = student.grade || ''; // Pre-fill existing fourth if available
                        fourthInput.min = 0;
                        fourthInput.step = 0.01;
                        fourthInput.max = 100;
                        fourthInput.setAttribute('data-student-id', student.Student_Id); // Store student ID for submission
                        fourthCell.appendChild(fourthInput);
                        // Append cells to row
                        row.appendChild(nameCell);
                        row.appendChild(firstCell);
                        row.appendChild(secondCell);
                        row.appendChild(thirdCell);
                        row.appendChild(fourthCell);
                        
                        // Append row to tbody
                        tbody.appendChild(row);
                    });
                    
                    table.appendChild(tbody);
                    
                    // Clear loading text and append table
                    modalContent.innerHTML = modalHeader();
                    modalContent.appendChild(table);
                    
                    // Optional: Add submit button
                    const submitButton = document.createElement('button');
                    submitButton.textContent = 'Save Grades';
                    submitButton.type = 'submit';
                    modalContent.appendChild(submitButton);
                }
            }
            catch(error) {
                console.error(error);
                alert(error.message);
                modalContent.innerHTML = modalHeader()
                modalContent.innerHTML += error.message;
            }
            close(modal);
        })
    })
});
async function fetchStudentsOfSectionSubject(secSubId) {
    try {
        const response = await fetch(`../../../BackEnd/api/teacher/fetchSectionSubjectStudents.php?secSubId=${encodeURIComponent(secSubId)}`);

        let data;
        try {
            data = await response.json();
        }
        catch{
            throw new Error(`Invalid response`);
        }
        if(!response.ok) {
            return {
                success: false,
                message: data.message || `HTTP ERROR ${response.status}`,
                data: null
            };
        }
        return data;
    }
    catch(error) {
        return {
            success: false,
            message: error.message || `There was a problem with the fetch`,
            data: null
        };
    }
}