import {modalHeader,loadingText,close} from '../utils.js';
//INITIALIZE THE POSSIBLE QUARTERS ARRAY
const selectValues = [1,2,3,4];
document.addEventListener('DOMContentLoaded',function(){
    const gradeButton = document.querySelectorAll('#grade-button');
    const modal = document.querySelector('.modal');
    const modalContent = document.querySelector('.modal-content');
    const form = document.createElement('form');
    form.id = 'grades-form';
    gradeButton.forEach(button=>{
        button.addEventListener('click', async function(e){
            modal.style.display = 'block';
            modalContent.innerHTML = modalHeader()
            modalContent.innerHTML += loadingText;
            try {
                const secSubId = e.target.getAttribute('data-id');
                const result = await fetchStudentsOfSectionSubject(secSubId,selectValues[0]);
                if(!result.success) {
                    modalContent.innerHTML = modalHeader();
                    modalContent.innerHTML += result.message;
                    close(modal);
                    return;
                }
                else {
                    modalContent.innerHTML = modalHeader();
                    createSelectElement(modalContent, result.data,secSubId);
                    close(modal);
                }
            }
            catch(error) {
                console.error(error);
                alert(error.message);
                modalContent.innerHTML = modalHeader()
                modalContent.innerHTML += error.message;
            } 
        })
    })
});
function createSelectElement(modalContent,students,secSubjId) {
    let currentIndex = 0
    const value = `<div class="slide-button-wrapper">
    <button class="back-button"> &lt; </button>
    <span class="display-value">${selectValues[currentIndex]}</span>
    <button class="forward-button"> &gt; </button></div>`;
    //INSERT BELOW FIRST CONTENT
    modalContent.insertAdjacentHTML('beforeend', value);
    //INITIALIZE GRADES TABLE
    const quarterTable = createQuarterTable(selectValues[currentIndex],students,secSubjId);
    modalContent.appendChild(quarterTable);
    //CREATE EVENT
    const slideButtonWrapper = modalContent.querySelector('.slide-button-wrapper');
    const displayValue = modalContent.querySelector('.display-value');
    if(slideButtonWrapper) {
        slideButtonWrapper.addEventListener('click',async function(e){
            //PREVENT RAPID CLICKS
            if(e.target.disabled) return;
            //DISABLE BUTTONS FIRST ONCLICK
            const buttons = this.querySelectorAll('button');
            buttons.forEach(btn => btn.disabled = true);
            if(e.target.classList.contains('back-button')) {
                currentIndex = (currentIndex - 1 + selectValues.length) %  selectValues.length;
                displayValue.textContent = selectValues[currentIndex];
            }
            if(e.target.classList.contains('forward-button')) {
                currentIndex = (currentIndex + 1) % selectValues.length;
                displayValue.textContent = selectValues[currentIndex];
            }
            const existingForm = modalContent.querySelector('#grades-form');
            if(existingForm) existingForm.innerHTML = loadingText;
            try {
                const newResult = await fetchStudentsOfSectionSubject(secSubjId,selectValues[currentIndex]);
                if(!newResult.success) {
                    if(existingForm) existingForm.innerHTML = newResult.message;
                }
                else {
                    const newQuarterTable = createQuarterTable(selectValues[currentIndex],newResult.data,secSubjId);
                    if(existingForm) existingForm.replaceWith(newQuarterTable);
                }
            }
            catch(error) {
                console.error('Error fetching quarter data:', error);
                if(existingForm) {
                    existingForm.innerHTML = `<p class="error">Error loading data</p>`;
                }
            }
            finally {
                //ENABLE BUTTON CLICKS AGAIN AFTER LOAD
                buttons.forEach(btn => btn.disabled = false);
            }
        })
    } 
}
function createQuarterTable(selectedQuarter, students,secSubId) {
    const labels = {
        1: 'first',
        2: 'second',
        3: 'third',
        4: 'fourth'
    }[selectedQuarter];
    const gradingForm = document.createElement('form');
    gradingForm.id = 'grades-form';
    gradingForm.setAttribute('data-quarter',selectedQuarter);
    //CREATE TABLE
    const table = document.createElement('table');
    table.className = 'grades-table';
    //CREATE TABLE HEADER
    const thead = document.createElement('thead');
    thead.innerHTML = `
        <tr>
            <th>Student Name</th>
            <th>${labels.charAt(0).toUpperCase() + labels.slice(1)} Quarter Grade</th>
        </tr>
    `;
    //CREATE TABLE BODY
    const tbody = document.createElement('tbody');
    //CREATE INPUTS FOREACH STUDENT
    students.forEach((student, index) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td><span>${index + 1}.</span> ${student.First_Name + ', '+ student.Last_Name}</td>
            <td>
                <input type="number" name="student-${labels}" data-student-id="${student.Student_Id}" min="0" max="100"  step="0.01" value="${student.existing_grade || ''}"placeholder="Enter grade">
            </td>
        `;
        tbody.appendChild(row);
    });
    tbody.innerHTML += `<input type="hidden" name="sec-sub-id" value="${secSubId}">`;
    //APPEND ALL CONTENT TO TABLE
    table.appendChild(thead);
    table.appendChild(tbody);
    gradingForm.appendChild(table);
    //CREATE A SUBMIT BUTTON
    const submitButton = document.createElement('button');
    submitButton.type = 'submit';
    submitButton.textContent = `Save Grades`;
    gradingForm.appendChild(submitButton);
    //BOOLEAN SUBMIT FLAG
    let isSubmitting = false;
    gradingForm.addEventListener('submit',async function(e){
        e.preventDefault();
        //DISCONTINUE AND RETURN IF FLAG TRUE BEFORE SUBMISSION
        if(isSubmitting) return;
        isSubmitting = true;//SET TO TRUE BEFORE STARTING THE PROCESS
        const submit = this.querySelector('button[type="submit"]'); 
        try {
            submit.disabled = true; //DISABLE BUTTON AS FALLBACK
            submit.style.backgroundColor = 'gray';
            const grades = convertToArray(this, selectedQuarter);
            const result = await postStudentGrades(grades);
            if(!result.success) {
                alert(result.details != null ? `${result.message}: ${result.details}` : result.message);
                isSubmitting = false;//RESET FLAG FOR RESUBMISSION
                gradingForm.reset();
                submit.disabled = false;
                submit.style.backgroundColor = 'green';
            }
            else {
                alert(result.details != null ? `${result.message}: ${result.details}` : result.message);
                setTimeout(()=>{window.location.reload();},1000);
            }
        }
        catch(error){
            isSubmitting = false;//RESET FLAG FOR RESUBMISSION
            console.error(error.message);
            gradingForm.reset();
            submit.disabled = false;//ENABLE AGAIN
            submit.style.backgroundColor = 'green';
        }
    })
    return gradingForm;
}
function convertToArray(formVal, currentQuarter){
    const gradesData = [];
    const rows = formVal.querySelectorAll('tbody tr');
    const secSubId = formVal.querySelector('input[name="sec-sub-id"]').value;
    rows.forEach(data=>{
        const input =  data.querySelector('input');
        const studId = input.getAttribute('data-student-id');

        gradesData.push({
            'student-id': studId,
            'quarter': currentQuarter,
            'grade-value': input.value || null,
            'sec-sub-id': secSubId
        });
    })
    return gradesData;
}
async function fetchStudentsOfSectionSubject(secSubId,quarter) {
    try {
        const response = await fetch(`../../../BackEnd/api/teacher/fetchSectionSubjectStudents.php?secSubId=${encodeURIComponent(secSubId)}&quarter=${quarter}`);
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
async function postStudentGrades(formData) {
    try {
        const response = await fetch(`../../../BackEnd/api/teacher/postStudentGrades.php`,{
            method: 'POST',
            headers:{
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        });
        let data;
        let detailsText ='';
        try {
            data = await response.json();
        }
        catch{
            throw new Error('Invalid Response');
        }
        if(data.details) {
            detailsText = `: ` + data.details;
        }

        if(!response.ok) {
            return {
                success: false,
                message: (data.message|| `HTTP ERROR: ${response.status}`) + detailsText,
                data: null
            };
        }
        return data;
    }
    catch(error) {
        return {
            success: false,
            message: error.message || 'Something went wrong',
            data: null
        };
    }
}