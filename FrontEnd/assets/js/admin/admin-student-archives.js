document.addEventListener('DOMContentLoaded',function(){
    const restoreButton = document.querySelectorAll('.restore-student');
    restoreButton.forEach(button=>{
        button.addEventListener('click',async function(e){
            e.preventDefault();
            const studentId = button.getAttribute('data-student');
            transferAndRestore(studentId);
        });
    })
    const deleteButton = document.querySelectorAll('.delete-student');
    deleteButton.forEach(button=>{
        button.addEventListener('click',async function(e){
            e.preventDefault();
            const studentId = button.getAttribute('data-student');
            deleteStudent(studentId);
        });
    })
});
async function deleteStudent(studentId) {
    if(confirm('Are you sure you want to delete this student? Make sure there are no related records in the system before you delete')) {
        Loader.show();
        const result = await studentDelete(studentId);
        if(!result.success) {
            alert(result.message);
            Loader.hide();
        }
        else {
            alert(result.message);
            setTimeout(()=>window.location.reload(), 1000);
        }
    }
}
async function Restore(studentId) {
    if (confirm('Are you sure you want to restore this student?')) {
        Loader.show();
        const result = await restoreStudent(studentId);
        if(!result.success) {
            alert(result.message);
            Loader.hide();
        }
        else {
            alert(result.message);
            setTimeout(()=>window.location.reload(), 1000);
        }
    }
}
const TIME_OUT = 30000;
async function restoreStudent(studentId) {
    const controller = new AbortController();
    const timeoutId = setTimeout(()=>controller.abort,TIME_OUT);
    try {
        const response = await fetch(`../../../BackEnd/api/admin/postRestoreStudent.php`,{
            signal: controller.signal,
            method: 'POST',
            headers: {
                'Content-Type' : 'application/x-www-form-urlencoded',
            },
            body: `id=${studentId}`
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
                message: data.message || `HTTP ERROR. Request returned with response${response.status}`,
                data: null
            };
        }
        return data;
    }
    catch(error) {
        if(error.name === "AbortError") {
            return {
                success: false,
                message: `Request timeout. Server took too long to respond: Took ${TIME_OUT/1000} seconds`,
                data: null
            }
        }
        else {
            return {
                success: false,
                message: error.message || `Something went wrong`,
                data: null
            }
        }
    }
}
async function studentDelete(studentId) {
    const controller = new AbortController();
    const timeoutId = setTimeout(()=>controller.abort,TIME_OUT);
    try {
        const response = await fetch(`../../../BackEnd/api/admin/postDeleteStudent.php`,{
            signal: controller.signal,
            method: 'POST',
            headers: {
                'Content-Type' : 'application/x-www-form-urlencoded',
            },
            body: `id=${studentId}`
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
                message: data.message || `HTTP ERROR. Request returned with response${response.status}`,
                data: null
            };
        }
        return data;
    }
    catch(error) {
        if(error.name === "AbortError") {
            return {
                success: false,
                message: `Request timeout. Server took too long to respond: Took ${TIME_OUT/1000} seconds`,
                data: null
            }
        }
        else {
            return {
                success: false,
                message: error.message || `Something went wrong`,
                data: null
            }
        }
    }
}