<?php
require_once __DIR__ . '/../../core/dbconnection.php';

if (!isset($_GET['staff_id'])) {
    http_response_code(400);
    echo '<p>Invalid request</p>';
    exit();
}

$staffId = intval($_GET['staff_id']);

$db = new Connect();
$conn = $db->getConnection();

$sql = "SELECT Position, Staff_Status FROM staffs WHERE Staff_Id = :staff_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':staff_id', $staffId);
$stmt->execute();
$teacher = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$teacher) {
    http_response_code(404);
    echo '<p>Teacher not found</p>';
    exit();
}

$statusMap = [
    1 => 'Active',
    2 => 'Retired',
    3 => 'Transferred Out'
];
$currentStatus = $statusMap[$teacher['Staff_Status']] ?? 'Active';
$currentPosition = $teacher['Position'];
?>

<div class="modal-header">
    <h2 class="modal-title">Edit Teacher Information</h2>
    <button class="close">&times;</button>
</div>
<form id="edit-teacher-form">
    <input type="hidden" name="staff_id" value="<?= htmlspecialchars($staffId) ?>">
    
    <div class="form-group">
        <label for="status">Status</label>
        <select id="status" name="status" required>
            <option value="Active" <?= $currentStatus === 'Active' ? 'selected' : '' ?>>Active</option>
            <option value="Retired" <?= $currentStatus === 'Retired' ? 'selected' : '' ?>>Retired</option>
            <option value="Transferred Out" <?= $currentStatus === 'Transferred Out' ? 'selected' : '' ?>>Transferred Out</option>
        </select>
    </div>
    
    <div class="form-group">
        <label for="position">Position</label>
        <select id="position" name="position" required>
            <option value="Teacher 1" <?= $currentPosition === 'Teacher 1' ? 'selected' : '' ?>>Teacher 1</option>
            <option value="Teacher 2" <?= $currentPosition === 'Teacher 2' ? 'selected' : '' ?>>Teacher 2</option>
            <option value="Teacher 3" <?= $currentPosition === 'Teacher 3' ? 'selected' : '' ?>>Teacher 3</option>
            <option value="Teacher 4" <?= $currentPosition === 'Teacher 4' ? 'selected' : '' ?>>Teacher 4</option>
            <option value="Teacher 5" <?= $currentPosition === 'Teacher 5' ? 'selected' : '' ?>>Teacher 5</option>
            <option value="Teacher 6" <?= $currentPosition === 'Teacher 6' ? 'selected' : '' ?>>Teacher 6</option>
            <option value="Teacher 7" <?= $currentPosition === 'Teacher 7' ? 'selected' : '' ?>>Teacher 7</option>
            <option value="Master Teacher 1" <?= $currentPosition === 'Master Teacher 1' ? 'selected' : '' ?>>Master Teacher 1</option>
            <option value="Master Teacher 2" <?= $currentPosition === 'Master Teacher 2' ? 'selected' : '' ?>>Master Teacher 2</option>
            <option value="Master Teacher 3" <?= $currentPosition === 'Master Teacher 3' ? 'selected' : '' ?>>Master Teacher 3</option>
            <option value="Master Teacher 4" <?= $currentPosition === 'Master Teacher 4' ? 'selected' : '' ?>>Master Teacher 4</option>
            <option value="Master Teacher 5" <?= $currentPosition === 'Master Teacher 5' ? 'selected' : '' ?>>Master Teacher 5</option>
            <option value="Principal 1" <?= $currentPosition === 'Principal 1' ? 'selected' : '' ?>>Principal 1</option>
            <option value="Principal 2" <?= $currentPosition === 'Principal 2' ? 'selected' : '' ?>>Principal 2</option>
            <option value="Principal 3" <?= $currentPosition === 'Principal 3' ? 'selected' : '' ?>>Principal 3</option>
            <option value="Principal 4" <?= $currentPosition === 'Principal 4' ? 'selected' : '' ?>>Principal 4</option>
            <option value="Principal 5" <?= $currentPosition === 'Principal 5' ? 'selected' : '' ?>>Principal 5</option>
        </select>
    </div>
    
    <div class="modal-actions">
        <button type="button" class="btn-cancel">Cancel</button>
        <button type="submit" class="submit-button">Save Changes</button>
    </div>
</form>
