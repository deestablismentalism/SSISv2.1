import {close,modalHeader} from '../utils.js';
document.addEventListener('DOMContentLoaded',function(){
    const editBtn = document.querySelectorAll('.edit-section-btn');
    editBtn.forEach(button=>{
        button.addEventListener('click',async function(e){
            const secSubId = e.target.getAttribute('data-sec-sub-id');
            await displayModal(secSubId);
        })
    })
    async function displayModal(secSubId) {
        const modal = document.getElementById('add-sched-form');
        const modalContent = document.getElementById('add-sched-content');
        const result = await fetchSectionSchedulesGroupedByDay(secSubId);
        if (!result.success) {
            modalContent.innerHTML = `<div class="error-message">${result.message}</div>`;
            modal.style.display = 'block';
            return;
        }
        modalContent.innerHTML = modalHeader();
        const allDays = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
        const schedInputs = {};
        
        // Create schedule table container
        const scheduleTableContainer = document.createElement('div');
        scheduleTableContainer.classList.add('schedule-table-container');
        
        // Create the main table
        const table = document.createElement('table');
        table.classList.add('schedule-input-table');
        
        // Create table header
        const thead = document.createElement('thead');
        const headerRow = document.createElement('tr');
        
        const dayHeader = document.createElement('th');
        dayHeader.textContent = 'Day';
        dayHeader.classList.add('day-header');
        headerRow.appendChild(dayHeader);
        
        const startHeader = document.createElement('th');
        startHeader.textContent = 'Time Start';
        startHeader.classList.add('time-start-header');
        headerRow.appendChild(startHeader);
        
        const endHeader = document.createElement('th');
        endHeader.textContent = 'Time End';
        endHeader.classList.add('time-end-header');
        headerRow.appendChild(endHeader);
        
        thead.appendChild(headerRow);
        table.appendChild(thead);
        
        // Create table body
        const tbody = document.createElement('tbody');
        
        allDays.forEach(day => {
            const times = result.data[day] || {Time_Start: '', Time_End: ''};
            
            // Create row
            const row = document.createElement('tr');
            row.classList.add('schedule-row');
            
            // Day label cell
            const dayCell = document.createElement('td');
            dayCell.classList.add('day-cell');
            const dayLabel = document.createElement('label');
            dayLabel.textContent = day;
            dayLabel.classList.add('day-label');
            dayCell.appendChild(dayLabel);
            row.appendChild(dayCell);
            
            // Time start cell
            const startCell = document.createElement('td');
            startCell.classList.add('time-start-cell');
            const startInput = document.createElement('input');
            startInput.type = 'time';
            startInput.name = `time_start[${day}]`;
            startInput.classList.add('time-input', 'time-start-input');
            startInput.min = '07:00';
            startInput.max = '17:00';
            startInput.value = times.Time_Start;
            startCell.appendChild(startInput);
            row.appendChild(startCell);
            
            // Time end cell
            const endCell = document.createElement('td');
            endCell.classList.add('time-end-cell');
            const endInput = document.createElement('input');
            endInput.type = 'time';
            endInput.name = `time_end[${day}]`;
            endInput.classList.add('time-input', 'time-end-input');
            endInput.min = '07:00';
            endInput.max = '17:00';
            endInput.value = times.Time_End;
            endCell.appendChild(endInput);
            row.appendChild(endCell);
            
            // Store inputs
            schedInputs[day] = {startInput, endInput};
            
            tbody.appendChild(row);
        });
        
        table.appendChild(tbody);
        scheduleTableContainer.appendChild(table);
        modalContent.appendChild(scheduleTableContainer);
        //SAVE BTN
        const buttons = document.createElement('div');
        buttons.classList.add('modal-footer');
        
        //CANCEL BTN
        const cancelBtn = document.createElement('button');
        cancelBtn.textContent = 'Cancel';
        cancelBtn.type = 'button';
        cancelBtn.classList.add('btn-cancel');
        cancelBtn.addEventListener('click', () => { modal.style.display = 'none'; });
        
        const saveBtn = document.createElement('button');
        saveBtn.textContent = 'Save Schedule';
        saveBtn.type = 'button';
        saveBtn.classList.add('btn-submit');
        let isSubmitting = false;
        saveBtn.addEventListener('click', async () => {
            const schedules = [];
            for (const day of allDays) {
                schedules.push({
                    day,
                    timeStart: schedInputs[day].startInput.value,
                    timeEnd: schedInputs[day].endInput.value
                });
            }
            saveBtn.disabled = true;
            if(isSubmitting) return;
            isSubmitting = true;
            const result = await submitSchedules(secSubId, schedules);
            if (!result.success) {
                Notification.show({
                    type: "error",
                    title: "Error",
                    message: result.message
                });
                isSubmitting = false;
                saveBtn.disabled = false;
            } else {
                Notification.show({
                    type: result.success ? "success" : "error",
                    title: result.success ? "Success" : "Error",
                    message: result.message
                });
                setTimeout(() => window.location.reload(), 1000);
            }
        });

        buttons.appendChild(cancelBtn);
        buttons.appendChild(saveBtn);
        modalContent.appendChild(buttons);
        // Finally, show modal
        modal.style.display = 'block';
        close(modal);
    }
    async function submitSchedules(secSubId, schedules) {
        const formData = new FormData();
        formData.append('section-subject-id', secSubId);
        formData.append('schedules', JSON.stringify(schedules));
        return await postAddSectionSchedule(formData);
    }
});
const TIME_OUT = 30000;
async function postAddSectionSchedule(formData) {
    const controller = new AbortController();
    const timeoutId = setTimeout(()=>{controller.abort()},TIME_OUT);
    try {
        const response = await fetch(`../../../BackEnd/api/admin/postAddSectionSchedule.php`, {
            method: 'POST',
            body: formData
        });
        clearTimeout(timeoutId);
        let data;
        try {
            data = await response.json();
        }
        catch{
            throw new Error('Invalid response');
        }
        if(!response.ok) {
            return {
                success: false,
                message: data.message || `HTTP ERROR. Request returned with status ${response.status}`,
                data: null
            };
        }
        return data;
    }
    catch(error) {
        if(error.name === "AbortError") {
            return {
                success: false,
                message: `Request timeout. Server took too long to response: took ${TIME_OUT/1000} seconds`,
                data: null
            };
        }
        return {
            success: false,
            message: error.message || `Something went wrong`,
            data: null
        };
    }
}
async function fetchSectionSchedulesGroupedByDay(secSubId) {
    const controller = new AbortController();
    const timeoutId = setTimeout(()=>{controller.abort()},TIME_OUT);
    try {
        const response = await fetch(`../../../BackEnd/api/admin/fetchSectionSchedulesGroupedByDays.php?sec-sub-id=${encodeURIComponent(secSubId)}`,{
            signal: controller.signal
        });
        clearTimeout(timeoutId);
        let data;
        try {
            data = await response.json();
        }
        catch{
            throw new Error('Invalid Response');
        }
        if(!response.ok) {
            return {
                success: false,
                message: data.message || `HTTP ERROR. Request responsed with status ${response.status}`,
                data: null
            };
        }
        return data;
    }
    catch(error) {
        if(error.name = "AbortError") {
            return {
                success: false,
                message: `Request timeout: Server took too long to response. Took ${TIME_OUT /1000} seconds`,
                data: null
            }
        }
        return {
            success: false,
            message: error.message || `Something went wrong`,
            data: null
        };
    }
}