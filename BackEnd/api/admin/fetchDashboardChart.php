<?php

declare(strict_types =1);
header('Content-Type: application/json');
require_once __DIR__ . '/../../admin/models/adminDashboardModel.php';

$data = new adminDashboardModel();

//enrollments data
$enrollees = $data->EnrolleeStatuses();
$enrollee_grade_levels = $data->EnrolleeGradeLevels();
$enrollee_biological_sex = $data->EnrolleeBiologicalSex();
$students = $data->StudentStatuses();
$student_grade_levels = $data->StudentGradeLevels();
$student_biological_sex = $data->StudentsBiologicalSex();


if(!$enrollees) {
    echo json_encode(['success' => false, 'message'=> 'cannot fetch total enrollees']);
    exit();
}
if(!$enrollee_grade_levels) {
    echo json_encode(['success' => false, 'message'=> 'cannot fetch enrollee grade levels']);
    exit();
}
if(!$enrollee_biological_sex) {
    echo json_encode(['success' => false, 'message'=> 'cannot fetch enrollee biological sex']);
    exit();
}
if(!$students) {
    echo json_encode(['success' => false, 'message'=> 'cannot fetch student statuses']);
    exit();
}
if(!$student_grade_levels) {
    echo json_encode(['success' => false, 'message'=> 'cannot fetch student grade levels']);
    exit();
}
if(!$student_biological_sex) {      
    echo json_encode(['success' => false, 'message'=> 'cannot fetch student biological sex']);
    exit();
}
$result = [
    'chart1'=> [
        ['label' => 'Enrolled', 'value' => $enrollees['enrolled_count']],
        ['label' => 'Denied', 'value' => $enrollees['denied_count']],
        ['label' => 'Pending', 'value' => $enrollees['pending_count']],
        ['label' => 'To Follow up', 'value' => $enrollees['follow_up_count']]
    ],
    'chart2' => [
        ['label' => 'Kinder 1', 'value' => $enrollee_grade_levels['Kinder1']],
        ['label' => 'Kinder 2', 'value' => $enrollee_grade_levels['Kinder2']],
        ['label' => 'Grade 1', 'value' => $enrollee_grade_levels['Grade1']],
        ['label' => 'Grade 2', 'value' => $enrollee_grade_levels['Grade2']],
        ['label' => 'Grade 3', 'value' => $enrollee_grade_levels['Grade3']],
        ['label' => 'Grade 4', 'value' => $enrollee_grade_levels['Grade4']],
        ['label' => 'Grade 5', 'value' => $enrollee_grade_levels['Grade5']],
        ['label' => 'Grade 6', 'value' => $enrollee_grade_levels['Grade6']]
    ],
    'chart3' => [
        ['label' => 'Male', 'value' => $enrollee_biological_sex['Male']],
        ['label' => 'Female', 'value' => $enrollee_biological_sex['Female']]
    ],
    'chart4' => [
        ['label' => 'Active', 'value' => $students['ActiveStudents']],
        ['label' => 'Inactive', 'value' => $students['InactiveStudents']],
        ['label' => 'Dropped', 'value' => $students['DroppedStudents']]
    ],
    'chart5' => [   
        ['label' => 'Kinder 1', 'value' => $student_grade_levels['Kinder1']],
        ['label' => 'Kinder 2', 'value' => $student_grade_levels['Kinder2']],
        ['label' => 'Grade 1', 'value' => $student_grade_levels['Grade1']],
        ['label' => 'Grade 2', 'value' => $student_grade_levels['Grade2']],
        ['label' => 'Grade 3', 'value' => $student_grade_levels['Grade3']],
        ['label' => 'Grade 4', 'value' => $student_grade_levels['Grade4']],
        ['label' => 'Grade 5', 'value' => $student_grade_levels['Grade5']],
        ['label' => 'Grade 6', 'value' => $student_grade_levels['Grade6']]
    ],
    'chart6' => [
        ['label' => 'Male', 'value' => $student_biological_sex['Male']],
        ['label' => 'Female', 'value' => $student_biological_sex['Female']]
    ]
];
if(!$result) {
    echo json_encode(['success' => false, 'message'=> 'cannot fetch dashboard data']);
    exit();
}
echo json_encode($result);
exit();
    


