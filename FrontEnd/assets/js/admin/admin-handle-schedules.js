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
        allDays.forEach(day => {
            const times = result.data[day] || {Time_Start: '', Time_End: ''};
            // Create row wrapper
            const row = document.createElement('div');
            row.classList.add('day-time-row');
            // Label
            const label = document.createElement('label');
            label.textContent = day;
            row.appendChild(label);
            // Time start input
            const startInput = document.createElement('input');
            startInput.type = 'time';
            startInput.name = `time_start[${day}]`
            startInput.min = '07:00';
            startInput.max = '17:00';
            startInput.value = times.Time_Start;
            row.appendChild(startInput);
            // Time end input
            const endInput = document.createElement('input');
            endInput.type = 'time';
            endInput.name = `time_end[${day}]`;
            endInput.min = '07:00';
            endInput.max = '17:00';
            endInput.value = times.Time_End;
            row.appendChild(endInput);
            //APPEND ALL TO ARRAY
            schedInputs[day] = {startInput, endInput};
            modalContent.appendChild(row);
        });
        //SAVE BTN
        const buttons = document.createElement('div');
        buttons.classList.add('action-buttons');
        const saveBtn = document.createElement('button');
        saveBtn.textContent = 'Save Schedule';
        saveBtn.type = 'button';
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
        //CANCEL BTN
        const cancelBtn = document.createElement('button');
        cancelBtn.textContent = 'Cancel';
        cancelBtn.type = 'button';
        cancelBtn.addEventListener('click', () => { modal.style.display = 'none'; });

        buttons.appendChild(saveBtn);
        buttons.appendChild(cancelBtn);
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