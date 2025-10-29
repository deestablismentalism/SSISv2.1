<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ob_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../../admin/models/adminEditStaffInfoModel.php';

if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
    exit;
}

try {
    $EditInformation = new adminEditInformation();
    
    if (!isset($_POST['form_type'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Missing form identifier'
        ]);
        exit;
    }

    switch ($_POST['form_type']) {
        case 'update_address':
            $House_Number = $_POST['House_Number'];
            $Subd_Name = $_POST['Subd_Name'];
            $Brgy_Name = $_POST['Brgy_Name'];
            $Municipality_Name = $_POST['Municipality_Name'];
            $Province_Name = $_POST['Province_Name'];
            $Region = $_POST['Region'];

            $response = $EditInformation->Update_Address($House_Number, $Subd_Name, $Brgy_Name, $Municipality_Name, $Province_Name, $Region);
            echo json_encode($response);
            break;

        case 'update_identifiers':
            $Employee_Number = $_POST['Employee_Number'];
            $Philhealth_Number = $_POST['Philhealth_Number'];
            $TIN = $_POST['TIN'];
            
            $response = $EditInformation->Update_Identifiers($Employee_Number, $Philhealth_Number, $TIN);
            echo json_encode($response);
            break;
        
        case 'update_information':
            $Staff_First_Name = $_POST['Staff_First_Name'];
            $Staff_Middle_Name = $_POST['Staff_Middle_Name'];
            $Staff_Last_Name = $_POST['Staff_Last_Name'];
            $Staff_Email = $_POST['Staff_Email'];
            $Staff_Contact_Number = $_POST['Staff_Contact_Number'];

            $response =  $EditInformation->Update_Information($Staff_First_Name, $Staff_Middle_Name, $Staff_Last_Name, $Staff_Email, $Staff_Contact_Number);

            echo json_encode($response);
            break;

        case 'update_profile_picture':
            if (!isset($_FILES['Profile_Picture'])) {
                echo json_encode([
                    'success' => false,
                    'message' => 'No file uploaded'
                ]);
                break;
            }

            $response = $EditInformation->Update_Profile_Picture($_FILES['Profile_Picture']);
            echo json_encode($response);
            break;

        default:
            echo json_encode([
                'success' => false,
                'message' => 'Invalid form type'
            ]);
            break;
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

ob_end_flush();
?>