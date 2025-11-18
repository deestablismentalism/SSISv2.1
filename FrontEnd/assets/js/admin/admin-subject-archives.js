document.addEventListener('DOMContentLoaded',function(){
    const restoreButton = document.querySelectorAll('.restore-subject');
    restoreButton.forEach(button=>{
        button.addEventListener('click',async function(e){
            e.preventDefault();
            const studentId = button.getAttribute('data-subject');
            Restore(studentId);
        });
    })
    const deleteButton = document.querySelectorAll('.delete-subject');
    deleteButton.forEach(button=>{
        button.addEventListener('click',async function(e){
            e.preventDefault();
            const studentId = button.getAttribute('data-subject');
            deleteSubject(studentId);
        });
    })
});
async function deleteSubject(studentId) {
    if(confirm('Are you sure you want to delete this subject? All related records to this subject will be deleted as well')) {
        Loader.show();
        const result = await postDeleteSubject(studentId);
        if(!result.success) {
            Notification.show({
                type: "error",
                title: "Error",
                message: result.message
            });
            Loader.hide();
        }
        else {
            Notification.show({
                type: 'success',
                title: 'success',
                message: result.message
            });
            setTimeout(()=>window.location.reload(), 1000);
        }
    }
}
async function Restore(subjectId) {
    if (confirm('Are you sure you want to restore this subject?')) {
        Loader.show();
        const result = await postRestoreSubject(subjectId);
        if(!result.success) {
            Notification.show({
                type: 'error',
                title: 'error',
                message: result.message
            });
            Loader.hide();
        }
        else {
            Notification.show({
                type: 'success',
                title: 'success',
                message: result.message
            });
            setTimeout(()=>window.location.reload(), 1000);
        }
    }
}
const TIME_OUT = 30000;
async function postDeleteSubject(subjectId) {
    const controller = new AbortController();
    const timeoutId = setTimeout(()=>controller.abort,TIME_OUT);
    try  {
        const response = await fetch(`../../../BackEnd/api/admin/postDeleteSubject.php`,{
            signal: controller.signal,
            method: 'POST',
            body: new URLSearchParams({'subject-id': subjectId})
        });
        clearTimeout(timeoutId);
        let data;
        try {
            data = await response.json();
        }
        catch {
            throw new Error('Invalid response');
        }
        if(!response.ok) {
            return {
                success: false,
                message: data.message || `HTTP Error: Request responded with status ${response.status}`,
                data: null
            };
        }
        return data;
    }
    catch(error) {
        if(error.name === "AbortError") {
            return {
                success: false,
                message: `Request timeout. Server took too long to respond: took ${TIME_OUT/1000} seconds`,
                data: null
            };
        }
        return {
            success: false,
            message: error.message || `Something went wrong`,
            data: null
        }
    }
}
async function postRestoreSubject(subjectId) {
    const controller = new AbortController();
    const timeoutId = setTimeout(()=>controller.abort,TIME_OUT);
    try  {
        const response = await fetch(`../../../BackEnd/api/admin/postRestoreSubject.php`,{
            signal: controller.signal,
            method: 'POST',
            body: new URLSearchParams({'subject-id': subjectId})
        });
        clearTimeout(timeoutId);
        let data;
        try {
            data = await response.json();
        }
        catch {
            throw new Error('Invalid response');
        }
        if(!response.ok) {
            return {
                success: false,
                message: data.message || `HTTP Error: Request responded with status ${response.status}`,
                data: null
            };
        }
        return data;
    }
    catch(error) {
        if(error.name === "AbortError") {
            return {
                success: false,
                message: `Request timeout. Server took too long to respond: took ${TIME_OUT/1000} seconds`,
                data: null
            };
        }
        return {
            success: false,
            message: error.message || `Something went wrong`,
            data: null
        }
    }
}