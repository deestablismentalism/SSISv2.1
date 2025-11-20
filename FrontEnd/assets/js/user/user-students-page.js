import {loadingText} from '../utils.js';
document.addEventListener('DOMContentLoaded',function(){
    const url = new URLSearchParams(window.location.search);
    const studentId = url.get('student-id');
    console.log(studentId);
    const dynamicContent = document.getElementById('student-dynamic-modal-content');

    const studentContentRadioButtons = document.querySelectorAll('input[name="student-content"]');
    let endpoint = '';
    async function loadContent(value) {
        dynamicContent.innerHTML = loadingText;
        switch(value) {
            case "historical-grades":
                endpoint = '../../../BackEnd/templates/student/fetchedHistoricalGrades.php';
                break;
            case "section": 
                endpoint = '../../../BackEnd/templates/student/fetchedClassSectionDetails.php';
                break;
            case "grades":
                endpoint = '../../../BackEnd/templates/student/fetchedStudentGrades.php';
                break;
            default:
                endpoint = '../../../BackEnd/templates/student/fetchedHistoricalGrades.php';
                break;
        }
        const finalURL = `${endpoint}?student-id=${parseInt(studentId)}`;
        const result = await fetchContent(finalURL);
        if(!result.success) {
            dynamicContent.innerHTML  = result.message;
        }
        else {
            dynamicContent.innerHTML = result.data;
            if(value="historical-grades") {
                loadScript("../../assets/js/student/student-historical-grades.js");
            }
        }
    }
    //DYNAMIC JAVASCRIPT LOADER FOR HISTORICAL GRADES
    function loadScript(src) {
        // Avoid loading the same script multiple times
        if(document.querySelector(`script[src="${src}"]`)) return;

        const script = document.createElement('script');
        script.src = src;
        script.defer = true;
        document.body.appendChild(script);
    }
    const currentChecked = document.querySelector('input[name="student-content"]:checked');
    loadContent(currentChecked);
    studentContentRadioButtons.forEach(button=>{
        button.addEventListener('click',function(){
            if(this.checked) {
                loadContent(this.value);
            }
        });
    })
})
    
const TIME_OUT = 15000;
async function fetchContent(url) {
    const controller = new AbortController();
    const timeoutID = setTimeout(()=>controller.abort(),TIME_OUT);
    try {
        const response = await fetch(url,{
            signal: controller.signal
        });
        clearTimeout(timeoutID);
        let data;
        try {
            data = await response.text();
        }
        catch{
            throw new Error('Invalid Response');
        }
        if(!response.ok) {
            return {
                success: false,
                message: `HTTP ERROR ${response.status}`,
                data: null
            };
        }
        return {
            success: true,
            message: `Content successfully fetched`,
            data: data
        };
    }
    catch(error) {
        if(error.name === "AbortError") {
            return {
                success: false,
                message: `Response timeout: Server took too long to response. Took ${TIME_OUT /1000} seconds`,
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