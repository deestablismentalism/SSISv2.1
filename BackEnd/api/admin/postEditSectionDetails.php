<?php

declare(strict_types=1);
require_once __DIR__ . '/../../admin/models/adminStudentsModel.php';
require_once __DIR__ . '/../../admin/models/adminSectionsModel.php';

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success'=> false, 'message'=> 'Invalid request method']);
    exit();
}
$sectionName = $_POST['section-name'] ?? null;
$adviserId = $_POST['select-adviser'] ?? null;

$sectionId = (int)$_POST['section-id'] ?? null;

if(empty($sectionId)) {
    echo json_encode(['success'=> false, 'message'=> 'Section not found']);
    exit();
}
if(empty($sectionName)) {
    echo json_encode(['success'=> false, 'message'=> 'Section name cannot be empty']);
    exit();
}
$sectionsModel = new adminSectionsModel();
$studentsModel = new adminStudentsModel();

$response = [
    'success' => true,
    'section' => ['success'=> true, 'message'=> ''],
    'adviser' => ['success'=> true, 'message'=> ''],
    'student' => ['success'=> true, 'message' => '', 'failedId' => []],
    'message' => []
];

$updateSectionName = $sectionsModel->updateSectionName($sectionId, $sectionName);
$updateAdviser = $sectionsModel->updateAdviser($sectionId, $adviserId);
$isExistingSectionName = $sectionsModel->checkIfSectionNameExists($sectionName, $sectionId);

if($isExistingSectionName) {
    $response['success'] = false;
    $response['section'] = ['success'=> false, 'message'=> 'This section name already exists. Please choose another one'];
}
if(!$updateSectionName) {
    $response['success'] = false;
    $response['section'] = ['success' => false, 'message' => 'The Section name did not update'];
}
if(!$updateAdviser) {
    $response['success'] = false;
    $response['adviser'] = ['success'=> false, 'message' => 'The Adviser did not update'];
}

$failedCount = [];
$students = $sectionsModel->getAvailableStudents($sectionId);

$student = isset($_POST['students']) ? $_POST['students'] : [];
foreach($students as $studentCount) {
    $studentIds = $studentCount['Student_Id'];

    $isChecked = in_array($studentIds, $student) ? 1 : 0;
    
    if($isChecked) {
        $updated = $studentsModel->updateStudentSection($studentIds, $sectionId);
    }
    else {
        $updated = $studentsModel->updateUncheckedStudents($studentIds);
    }
    if(!$updated) {
        $failedCount[] = $studentIds;
    }
}
if(!empty($failedCount)) {
    $response['success'] = false;
    $response['student'] = ['success' => false, 'message' => 'Some Students failed to update', 'failedId'=> $failedCount];
}
if(empty($response['message'])) {
    $response['message'][] = 'All changes were saved';
}
echo json_encode($response);
exit();
