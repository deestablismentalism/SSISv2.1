document.addEventListener('DOMContentLoaded',function(){
    const restoreButton = document.querySelectorAll('.restore-section');
    restoreButton.forEach(button=>{
        button.addEventListener('click',async function(e){
            e.preventDefault();
            const sectionId = button.getAttribute('data-section');
            Restore(sectionId);
        });
    })
    const deleteButton = document.querySelectorAll('.delete-section');
    deleteButton.forEach(button=>{
        button.addEventListener('click',async function(e){
            e.preventDefault();
            const sectionId = button.getAttribute('data-section');
            deleteSection(sectionId);
        });
    })
});
async function deleteSection(sectionId) {
    if(confirm('Deleted records cannot be restored! Are you sure you want to delete this Section? All related records to this Section will be deleted as well')) {
        Loader.show();
        const result = await postDeleteSection(sectionId);
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
async function Restore(sectionId) {
    if (confirm('Are you sure you want to restore this Section?')) {
        Loader.show();
        const result = await postRestoreSection(sectionId);
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
async function postDeleteSection(sectionId) {
    const controller = new AbortController();
    const timeoutId = setTimeout(()=>controller.abort,TIME_OUT);
    try  {
        const response = await fetch(`../../../BackEnd/api/admin/postDeleteSection.php`,{
            signal: controller.signal,
            method: 'POST',
            body: new URLSearchParams({'section-id': sectionId})
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
async function postRestoreSection(sectionId) {
    const controller = new AbortController();
    const timeoutId = setTimeout(()=>controller.abort,TIME_OUT);
    try  {
        const response = await fetch(`../../../BackEnd/api/admin/postRestoreSection.php`,{
            signal: controller.signal,
            method: 'POST',
            body: new URLSearchParams({'section-id': sectionId})
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