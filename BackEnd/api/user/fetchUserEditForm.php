<?php
declare(strict_types=1);
require_once __DIR__ . '/../models/userEditFormModel.php';

header('Content-Type: application/json');

$model = new userEditFormModel();
if (!$model) {
    echo json_encode(['success' => false, 'error' => 'Query failed']);
    exit();
}

if (!isset($_GET['editId'])) {
    echo json_encode(['success' => false, 'error' => 'Enrollee not found']);
    exit();
}

$enrolleeId = $_GET['editId'];
$data = $model->getEnrolleeData($enrolleeId);

if (!$data) {
    echo json_encode(['success' => false, 'error' => 'Data not found']);
    exit();
}

$result = [
    'success' => true,
    'First Name' => ['value' => $data['Student_First_Name'], 'type' => 'text'],
    'Last Name' => ['value' => $data['Student_Last_Name'], 'type' => 'text'],
    'Middle Name' => ['value' => $data['Student_Middle_Name'], 'type' => 'text'],
    'Extension' => ['value' => $data['Student_Extension'], 'type' => 'text'],
    'LRN' => ['value' => $data['Learner_Reference_Number'], 'type' => 'number'],
    'PSA' => ['value' => $data['Psa_Number'], 'type' => 'number'],
    'Age' => ['value' => $data['Age'], 'type' => 'number'],
    'Birthdate' => ['value' => $data['Birth_Date'], 'type' => 'date'],
    'Sex' => ['value' => $data['Sex'], 'type' => 'radio'],
    'Religion' => ['value' => $data['Religion'], 'type' => 'text'],
    'Native Language' => ['value' => $data['Native_Language'], 'type' => 'text'],
    'Belongs in Cultural Group' => ['value'=> $data['If_Cultural'], 'type' => 'radio'],
    'Cultural Group' => ['value' => $data['Cultural_Group'], 'type' => 'text'],
    'Email Address' => ['value' => $data['Student_Email'], 'type' => 'email'],
    'Enrolling Grade Level' => ['value' => $data['Enrolling_Grade_Level'], 'type' => 'select'],
    'Last Grade Level' => ['value' => $data['Last_Grade_Level'], 'type' => 'select'],
    'Last Year Attended' => ['value' => $data['Last_Year_Attended'], 'type' => 'number'],
    'Last School Attended' => ['value' => $data['Last_School_Attended'], 'type' => 'text'],
    'School ID' => ['value' => $data['School_Id'], 'type' => 'number'],
    'School Address' => ['value' => $data['School_Address'], 'type' => 'text'],
    'School Type' => ['value' => $data['School_Type'], 'type' => 'radio'],
    'Region' => ['value' => $data['Region'], 'code' => $data['Region_Code'], 'type' => 'select'],
    'Province' => ['value' => $data['Province_Name'], 'code' => $data['Province_Code'], 'type' => 'select'],
    'City/Municipality' => ['value' => $data['Municipality_Name'], 'code' => $data['Municipality_Code'], 'type' => 'select'],
    'Barangay' => ['value' => $data['Brgy_Name'], 'code' => $data['Brgy_Code'], 'type' => 'select'],
    'Subdivision' => ['value' => $data['Subd_Name'], 'type' => 'text'],
    'House Number' => ['value' => $data['House_Number'], 'type' => 'number'],
    'Has a Special Condition' => ['value' => $data['Have_Special_Condition'], 'type' => 'radio'],
    'Special Condition' => ['value' => $data['Special_Condition'], 'type' => 'text'],
    'Has Assistive Technology' => ['value' => $data['Have_Assistive_Tech'], 'type' => 'radio'],
    'Assistive Technology' => ['value' => $data['Assistive_Tech'], 'type' => 'text']
];
// Add parent information
if (isset($data['parent_information'])) {
    // Father's Information
    if (isset($data['parent_information']['father'])) {
        $father = $data['parent_information']['father'];
        $result['Father First Name'] = ['value' => $father['first_name'], 'type' => 'text'];
        $result['Father Middle Name'] = ['value' => $father['middle_name'], 'type' => 'text'];
        $result['Father Last Name'] = ['value' => $father['last_name'], 'type' => 'text'];
        $result['Father Educational Attainment'] = ['value' => $father['educational_attainment'], 'type' => 'select'];
        $result['Father Contact Number'] = ['value' => $father['contact_number'], 'type' => 'text'];
        $result['Father 4Ps Member'] = ['value' => $father['if_4ps'], 'type' => 'radio'];
    }

    // Mother's Information
    if (isset($data['parent_information']['mother'])) {
        $mother = $data['parent_information']['mother'];
        $result['Mother First Name'] = ['value' => $mother['first_name'], 'type' => 'text'];
        $result['Mother Middle Name'] = ['value' => $mother['middle_name'], 'type' => 'text'];
        $result['Mother Last Name'] = ['value' => $mother['last_name'], 'type' => 'text'];
        $result['Mother Educational Attainment'] = ['value' => $mother['educational_attainment'], 'type' => 'select'];
        $result['Mother Contact Number'] = ['value' => $mother['contact_number'], 'type' => 'text'];
        $result['Mother 4Ps Member'] = ['value' => $mother['if_4ps'], 'type' => 'radio'];
    }

    // Guardian's Information
    if (isset($data['parent_information']['guardian'])) {
        $guardian = $data['parent_information']['guardian'];
        $result['Guardian First Name'] = ['value' => $guardian['first_name'], 'type' => 'text'];
        $result['Guardian Middle Name'] = ['value' => $guardian['middle_name'], 'type' => 'text'];
        $result['Guardian Last Name'] = ['value' => $guardian['last_name'], 'type' => 'text'];
        $result['Guardian Educational Attainment'] = ['value' => $guardian['educational_attainment'], 'type' => 'select'];
        $result['Guardian Contact Number'] = ['value' => $guardian['contact_number'], 'type' => 'text'];
        $result['Guardian 4Ps Member'] = ['value' => $guardian['if_4ps'], 'type' => 'radio'];
    }
}
// Add PSA image information if available
if (isset($data['psa_image_filename']) && isset($data['psa_image_path'])) {
    $result['PSA Image'] = [
        'value' => [
            'filename' => $data['psa_image_filename'],
            'path' => $data['psa_image_path']
        ],
        'type' => 'image'
    ];
}

echo json_encode($result);
exit();

