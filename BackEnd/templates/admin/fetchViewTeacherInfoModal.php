<?php
require_once __DIR__ . '/../../core/dbconnection.php';
require_once __DIR__ . '/../../core/encryption_and_decryption.php';
require_once __DIR__ . '/../../admin/models/adminTeacherInfoModel.php';

if (!isset($_GET['staff_id'])) {
    echo '<p>Error: Staff ID is required</p>';
    exit();
}

$staffId = $_GET['staff_id'];

try {
    $teacherModel = new adminTeacherInformationModel();
    $teacherData = $teacherModel->getAllResults($staffId);
    
    if (!$teacherData) {
        echo '<p>Teacher not found</p>';
        exit();
    }
    
    // Decrypt sensitive fields
    $encryption = new Encryption();
    
    // Decrypt only if the value is not null or empty
    $employeeNumber = 'No Employee Number';
    if (!empty($teacherData['Employee_Number'])) {
        try {
            $decrypted = $encryption->passDecrypt($teacherData['Employee_Number']);
            $employeeNumber = $decrypted ?? 'No Employee Number';
        } catch (Exception $e) {
            $employeeNumber = 'No Employee Number';
        }
    }
    
    $philhealthNumber = 'No Philhealth';
    if (!empty($teacherData['Philhealth_Number'])) {
        try {
            $decrypted = $encryption->passDecrypt($teacherData['Philhealth_Number']);
            $philhealthNumber = $decrypted ?? 'No Philhealth';
        } catch (Exception $e) {
            $philhealthNumber = 'No Philhealth';
        }
    }
    
    $tin = 'No TIN';
    if (!empty($teacherData['TIN'])) {
        try {
            $decrypted = $encryption->passDecrypt($teacherData['TIN']);
            $tin = $decrypted ?? 'No TIN';
        } catch (Exception $e) {
            $tin = 'No TIN';
        }
    }
    
    // Map status
    $statusMap = [
        1 => 'Active',
        2 => 'Retired',
        3 => 'Transferred Out'
    ];
    $statusText = $statusMap[$teacherData['Staff_Status']] ?? 'Unknown';
    
    // Build full name
    $fullName = trim($teacherData['Staff_First_Name'] . ' ' . 
                     ($teacherData['Staff_Middle_Name'] ?? '') . ' ' . 
                     $teacherData['Staff_Last_Name']);
    
    // Build address
    $address = trim(($teacherData['House_Number'] ?? '') . ' ' . 
                    ($teacherData['Street'] ?? '') . ' ' . 
                    ($teacherData['Barangay'] ?? '') . ' ' . 
                    ($teacherData['City'] ?? ''));
    
?>
<div class="modal-header">
    <h2 class="modal-title">Teacher Information</h2>
    <button class="close">&times;</button>
</div>

<div class="view-teacher-content">
    <div class="view-header">
        <div class="view-status-container">
            <p class="view-status-label">Status</p>
            <p class="view-status-value status-<?php echo strtolower(str_replace(' ', '-', $statusText)); ?>">
                <?php echo htmlspecialchars($statusText); ?>
            </p>
        </div>
    </div>
    
    <div class="view-body">
        <div class="view-profile-section">
            <div class="view-profile-info">
                <div class="view-info-item">
                    <p class="view-info-label">Teacher Name</p>
                    <p class="view-info-value"><?php echo htmlspecialchars($fullName); ?></p>
                </div>
                
                <div class="view-info-item">
                    <p class="view-info-label">Email</p>
                    <p class="view-info-value"><?php echo htmlspecialchars($teacherData['Staff_Email'] ?? 'N/A'); ?></p>
                </div>
                
                <div class="view-info-item">
                    <p class="view-info-label">Contact Number</p>
                    <p class="view-info-value"><?php echo htmlspecialchars($teacherData['Staff_Contact_Number'] ?? 'N/A'); ?></p>
                </div>
                
                <div class="view-info-item">
                    <p class="view-info-label">Address</p>
                    <p class="view-info-value"><?php echo htmlspecialchars($address ?: 'N/A'); ?></p>
                </div>
            </div>
        </div>
        
        <div class="view-details-section">
            <div class="view-section">
                <p class="view-section-title">Current Position</p>
                <p class="view-section-content"><?php echo htmlspecialchars($teacherData['Position'] ?? 'N/A'); ?></p>
            </div>
            
            <div class="view-section">
                <p class="view-section-title">Government ID(s)</p>
                <table class="view-info-table">
                    <thead>
                        <tr>
                            <th>Employee Number</th>
                            <th>Philhealth Number</th>
                            <th>TIN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo htmlspecialchars($employeeNumber); ?></td>
                            <td><?php echo htmlspecialchars($philhealthNumber); ?></td>
                            <td><?php echo htmlspecialchars($tin); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
} catch (Exception $e) {
    echo '<div class="error-message">Error loading teacher information: ' . htmlspecialchars($e->getMessage()) . '</div>';
}
?>
