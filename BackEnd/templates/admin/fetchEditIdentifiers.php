<form action="/BackEnd/api/admin/postEditStaffInformation.php" method="POST">
    <p class="title">Edit Government IDs</p>
    <!--DO NOT REMOVE!! For Switch case statement-->
    <input type="hidden" name="form_type" value="update_identifiers">
    
    <label for="Employee_Number">Employee Number:</label>
    <input type="text" id="Employee_Number" name="Employee_Number" required>

    <label for="Philhealth_Number">Philhealth Number:</label>
    <input type="text" id="Philhealth_Number" name="Philhealth_Number" required>

    <label for="TIN">TIN:</label>
    <input type="text" id="TIN" name="TIN" required>

    <div class="form-buttons">
        <input type="submit" value="Update">  
    </div>
</form>