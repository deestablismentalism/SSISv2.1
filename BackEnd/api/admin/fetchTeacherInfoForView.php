<?php
require_once __DIR__ . '/../../core/dbconnection.php';
require_once __DIR__ . '/../../core/encryption_and_decryption.php';
require_once __DIR__ . '/../../admin/models/adminTeacherInfoModel.php';

header('Content-Type: application/json');

if (!isset($_GET['staff_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Staff ID is required'
    ]);
    exit();
}

$staffId = $_GET['staff_id'];

try {
    $teacherModel = new adminTeacherInformationModel();
    $teacherData = $teacherModel->getAllResults($staffId);
    
    if (!$teacherData) {
        echo json_encode([
            'success' => false,
            'message' => 'Teacher not found'
        ]);
        exit();
    }
    
    // Decrypt sensitive fields with proper null handling
    $encryption = new Encryption();
    
    if (!empty($teacherData['Employee_Number']) && $teacherData['Employee_Number'] !== null) {
        $decrypted = $encryption->passDecrypt($teacherData['Employee_Number']);
        $teacherData['Employee_Number'] = ($decrypted !== null) ? $decrypted : null;
    } else {
        $teacherData['Employee_Number'] = null;
    }
    
    if (!empty($teacherData['Philhealth_Number']) && $teacherData['Philhealth_Number'] !== null) {
        $decrypted = $encryption->passDecrypt($teacherData['Philhealth_Number']);
        $teacherData['Philhealth_Number'] = ($decrypted !== null) ? $decrypted : null;
    } else {
        $teacherData['Philhealth_Number'] = null;
    }
    
    if (!empty($teacherData['TIN']) && $teacherData['TIN'] !== null) {
        $decrypted = $encryption->passDecrypt($teacherData['TIN']);
        $teacherData['TIN'] = ($decrypted !== null) ? $decrypted : null;
    } else {
        $teacherData['TIN'] = null;
    }
    
    // Map status
    $statusMap = [
        1 => 'Active',
        2 => 'Retired',
        3 => 'Transferred Out'
    ];
    $teacherData['Status_Text'] = $statusMap[$teacherData['Staff_Status']] ?? 'Unknown';
    
    echo json_encode([
        'success' => true,
        'data' => $teacherData
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>
