<form action="/BackEnd/api/admin/postEditStaffInformation.php" method="POST" enctype="multipart/form-data" id="profile-picture-form">
    <p class="title">Update Profile Picture</p>
    <input type="hidden" name="form_type" value="update_profile_picture">

    <div class="profile-picture-container">
        <div class="current-picture">
            <img id="profile-preview" src="/FrontEnd/assets/imgs/default-avatar.png" alt="Profile Picture">
        </div>
    </div>

    <label for="Profile_Picture">Choose New Picture:</label>
    <input type="file" id="Profile_Picture" name="Profile_Picture" accept="image/jpeg,image/jpg,image/png" required>
    
    <div class="file-info">
        <small>Accepted formats: JPG, JPEG, PNG (Max 5MB)</small>
    </div>

    <div class="form-buttons">
        <input type="submit" value="Upload">
    </div>
</form>

<style>
.profile-picture-container {
    display: flex;
    justify-content: center;
    margin: 1.5rem 0;
}

.current-picture {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid #e5e7eb;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.current-picture img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.file-info {
    margin-top: 0.5rem;
    text-align: center;
}

.file-info small {
    color: #6b7280;
    font-size: 0.875rem;
}

input[type="file"] {
    padding: 0.75rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.375rem;
    font-size: 1rem;
    width: 100%;
    cursor: pointer;
}

input[type="file"]:focus {
    outline: none;
    border-color: var(--primary-color);
}
</style>
