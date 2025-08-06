function enableEdit() {
    // Status
    document.getElementById("statusDisplay").style.display = "none";
    document.getElementById("statusSelect").style.display = "inline-block";

    // Position
    document.getElementById("positionDisplay").style.display = "none";
    document.getElementById("positionSelect").style.display = "inline-block";

    // Optionally show a save button
    const saveButton = document.getElementById("saveButton");
    if (saveButton) saveButton.style.display = "inline-block";

    document.getElementById("editButton").style.display = "none";
}

document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("editForm");
    
    form.addEventListener("submit", function(event) {
        event.preventDefault();
        
        const formData = new FormData(form);
        const staffId = sessionStorage.getItem('staff_id');

        if (staffId) {
            formData.append('staff_id', staffId);
        }
        
        fetch("../server_side/post_edit_teacher_info.php", {
            method: "POST",
            body: formData,
        })
        .then(response => {
            return response.text().then(text => {
                console.log('Raw response:', text);
                try {
                    return JSON.parse(text);
                } catch (e) {
                    throw new Error('Invalid JSON response: ' + text);
                }
            });
        })
        .then(data => {
            if (data.success) {
                alert(data.message);
                
                const statusValue = document.getElementById("statusSelect").value;
                const positionValue = document.getElementById("positionSelect").value;
            
                document.getElementById("statusDisplay").textContent = statusValue;
                document.getElementById("positionDisplay").textContent = positionValue;
            
                document.getElementById("statusDisplay").style.display = "inline-block";
                document.getElementById("positionDisplay").style.display = "inline-block";
            
                document.getElementById("statusSelect").style.display = "none";
                document.getElementById("positionSelect").style.display = "none";
            
                document.getElementById("editButton").style.display = "inline-block";
                const saveButton = document.getElementById("saveButton");
                if (saveButton) saveButton.style.display = "none";
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error("Fetch Error:", error);
            alert("An error occurred while processing your request.");
        });
    });
});
