document.addEventListener('DOMContentLoaded',function(){
    const restoreButton = document.querySelectorAll('.restore-teacher');
    restoreButton.forEach(button=>{
        button.addEventListener('click',async function(e){
            e.preventDefault();
            const teacherId = button.getAttribute('data-teacher');
            Restore(teacherId);
        });
    })
    const deleteButton = document.querySelectorAll('.delete-teacher');
    deleteButton.forEach(button=>{
        button.addEventListener('click',async function(e){
            e.preventDefault();
            const teacherId = button.getAttribute('data-teacher');
            deleteSubject(teacherId);
        });
    })
});
async function deleteSubject(teacherId) {
    if(confirm('Deleted records cannot be restored! Are you sure you want to delete this Teacher? All related records to this Teacher will be deleted as well')) {
        Loader.show();
        const result = await postDeleteTeacher(teacherId);
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
async function Restore(teacherId) {
    if (confirm('Are you sure you want to restore this Teacher?')) {
        Loader.show();
        const result = await postRestoreTeacher(teacherId);
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
async function postDeleteTeacher(teacherId) {
    const controller = new AbortController();
    const timeoutId = setTimeout(()=>controller.abort,TIME_OUT);
    try  {
        const response = await fetch(`../../../BackEnd/api/admin/postDeleteStaff.php`,{
            signal: controller.signal,
            method: 'POST',
            body: new URLSearchParams({'subject-id': teacherId})
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
async function postRestoreTeacher(teacherId) {
    const controller = new AbortController();
    const timeoutId = setTimeout(()=>controller.abort,TIME_OUT);
    try  {
        const response = await fetch(`../../../BackEnd/api/admin/postRestoreStaff.php`,{
            signal: controller.signal,
            method: 'POST',
            body: new URLSearchParams({'subject-id': teacherId})
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