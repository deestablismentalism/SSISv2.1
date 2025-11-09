document.addEventListener('DOMContentLoaded',function(){
    document.getElementById('grade_level').addEventListener('change', async function() {
        const errorMessage = document.querySelector('.error');
        const gradeLevelId = this.value;
        // Make an AJAX request to get sections for the selected grade level
        const result = await fetchSectionsListByGradeLevel(gradeLevelId);
        if(!result.success) {
            errorMessage.style.display = 'block';
            errorMessage.innerHTML = (result.message) + ': Unable to  populate section dropdown';
            setTimeout(()=>{errorMessage.style.display="none";},3000);
        }
        else {
            const sectionDropdown = document.getElementById('section');
            // Clear existing options
            sectionDropdown.innerHTML = '<option value="">-- No Section --</option>';
            // Add new options
            if (result.data.length > 0) {
                result.data.forEach(section => {
                    const option = document.createElement('option');
                    option.value = section.Section_Id;
                    option.textContent = section.Section_Name;
                    sectionDropdown.appendChild(option);
                });
            }
        }
    });
    // Calculate age based on birthdate
    document.getElementById('birthdate').addEventListener('change', function() {
        const birthdate = new Date(this.value);
        const today = new Date();
        let age = today.getFullYear() - birthdate.getFullYear();
        const monthDiff = today.getMonth() - birthdate.getMonth();
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthdate.getDate())) {
            age--;
        }
        document.getElementById('age').value = age;
    });
});
async function fetchSectionsListByGradeLevel(gradeLevelId) {
    try {
        const response = await fetch(`../../../BackEnd/api/admin/fetchSectionsListByGradeLevel.php?grade_level_id=${gradeLevelId}`);
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
                message: data.message || `HTTP ERROR. Request returned with response ${response.status}`,
                data: null
            };
        }
        return data;
    }
    catch(error) {
        return {
            success: false,
            message: error.message,
            data: null
        };
    }
}