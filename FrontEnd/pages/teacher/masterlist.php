<?php

require('../../../fpdf/fpdf.php');
require_once __DIR__ . '/../../../BackEnd/teacher/models/teacherSectionAdvisersModel.php';

$pdf = new FPDF();
try {
    $pdf->AddPage();
    if(!isset($_GET['section'])) {
        $pdf->Cell(60,10, 'No Section found');
        $pdf->Output();
    }
    $sectionId = $_GET['section'];
    $sectionAdviserModel = new teacherSectionAdvisersModel();

    $sectionName = $sectionAdviserModel->getSectionName($sectionId);
    $adviserName = $sectionAdviserModel->getSectionAdviserName($sectionId);  

    $sectionMale = $sectionAdviserModel->getSectionMaleStudents($sectionId);
    $sectionFemale = $sectionAdviserModel->getSectionFemaleStudents($sectionId);
    //all students array
    $rows = [
        'male' => [],
        'female' => []
    ];
    //TODO: handle empty query
    foreach($sectionMale as $male) {
        $firstName = htmlspecialchars($male['Student_First_Name']);
        $lastName = htmlspecialchars($male['Student_Last_Name']);
        $middleName = (!empty($male['Student_Middle_Name'])) ? htmlspecialchars($male['Student_Middle_Name']) : '';
        $fullName = $lastName . ', ' . $firstName . ' ' . $middleName;

        $rows['male'][] = $fullName;
    }
    foreach($sectionFemale as $female) {
        $firstName = htmlspecialchars($female['Student_First_Name']);
        $lastName = htmlspecialchars($female['Student_Last_Name']);
        $middleName = (!empty($female['Student_Middle_Name'])) ? htmlspecialchars($female['Student_Middle_Name']) : '';
        $fullName = $lastName . ', ' . $firstName . ' ' . $middleName;

        $rows['female'][]   = $fullName;
    }

    $maleCount = count($sectionMale);
    $femaleCount = count($sectionFemale);

    //table cell decider
    $higherIndex = max($maleCount, $femaleCount);

    $adviserHasMiddleInitial = (!empty($adviserName['Staff_Middle_Name'])) ? htmlspecialchars($adviserName['Staff_Middle_Name']) : '';
    $currentAdviser = htmlspecialchars($adviserName['Staff_Last_Name']) . ', ' . htmlspecialchars($adviserName['Staff_First_Name']) .' ' . $adviserHasMiddleInitial;
    $currentSection = htmlspecialchars($sectionName['Grade_Level']) . ' - ' .htmlspecialchars($sectionName['Section_Name']);
    //title
    $pdf->setTitle( $currentSection . ' Masterlist');
    $pdf->ln();

    //school logo
    $pdf->Image('../../assets/imgs/logo.png',180, null, 20, 20);
    $pdf->ln(10);
    //section name
    $pdf->SetFont('Arial', 'B', 15);
    $pdf->Cell(210, 10, $currentSection, 0, 0, 'C');
    $pdf->ln();
    //advisername
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(210, 8, $currentAdviser,0,0, 'C');
    $pdf->ln();
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(210, 5, 'Class Adviser', 0,0,'C');
    $pdf->ln();
    
    //students table
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(95, 10, 'Male',1,0,'C');
    $pdf->Cell(95, 10, 'Female',1,0,'C');
    $pdf->ln();

    if($higherIndex > 0) {
        
        for ($i = 0; $i < $higherIndex; $i++) {
            $pdf->Cell(15,10, $i+1,1,0,'C');
            $pdf->Cell(80,10,isset($rows['male'][$i]) ? $rows['male'][$i] : '',1,0,'C');
            $pdf->Cell(15,10, $i+1,1,0,'C');
            $pdf->Cell(80,10,isset($rows['female'][$i]) ? $rows['female'][$i] : '',1,0,'C');
            $pdf->ln();
        }
    }
    $pdf->Output();

}
catch(Exeption $e) {
    $pdf->SetFont('Arial', 'B', 20);
    $pdf->Cell(210, 20, $e->getMessage());
    $pdf->Output();
}

