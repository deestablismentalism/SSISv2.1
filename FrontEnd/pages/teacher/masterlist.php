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
    if(empty($sectionMale) && empty($sectionFemale)) {
        throw new Exception('No Students Yet');
    }
    $currentSection = htmlspecialchars($sectionName['Grade_Level']) . ' - ' .htmlspecialchars($sectionName['Section_Name']);
    $adviserHasMiddleInitial = (!empty($adviserName['Staff_Middle_Name'])) ? htmlspecialchars($adviserName['Staff_Middle_Name']) : '';
    $currentAdviser = htmlspecialchars($adviserName['Staff_Last_Name']) . ', ' . htmlspecialchars($adviserName['Staff_First_Name']) .' ' . $adviserHasMiddleInitial;
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
    //students list array
    $rows = [
        'male' => [],
        'female' => []
    ];
    //TODO: handle empty query
    $maleCount = count($sectionMale);
    $femaleCount = count($sectionFemale);
    //table cell decider
    $higherIndex = max($maleCount, $femaleCount);

    foreach($sectionMale as $male) {
        $firstName = htmlspecialchars($male['First_Name']);
        $lastName = htmlspecialchars($male['Last_Name']);
        $middleName = (!empty($male['Middle_Name'])) ? htmlspecialchars($male['Middle_Name']) : '';
        $fullName = $lastName . ', ' . $firstName . ' ' . $middleName;

        $rows['male'][] = $fullName;
    }
    foreach($sectionFemale as $female) {
        $firstName = htmlspecialchars($female['First_Name']);
        $lastName = htmlspecialchars($female['Last_Name']);
        $middleName = (!empty($female['Middle_Name'])) ? htmlspecialchars($female['Middle_Name']) : '';
        $fullName = $lastName . ', ' . $firstName . ' ' . $middleName;

        $rows['female'][]   = $fullName;
    }
    if($higherIndex == 0) {
        $pdf->Cell(210,10,'No Students to display',1,0,'C');
    }
    else {
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
catch(Exception $e) {
    $errorPdf = new FPDF();
    $errorPdf->AddPage();
    $errorPdf->SetFont('Arial', 'B', 20);
    $errorPdf->Cell(190, 20, $e->getMessage(),1,0,'C');
    $errorPdf->Output();
}

