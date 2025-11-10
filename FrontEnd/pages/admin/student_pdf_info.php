<?php
require('../../../fpdf/fpdf.php');
require_once __DIR__ . '/../../../BackEnd/admin/controllers/adminStudentsController.php';
require_once __DIR__ . '/../../../BackEnd/Exceptions/IdNotFoundException.php';
$pdf = new FPDF();
try {
    $studentId = isset($_GET['student-id']) ? (int)$_GET['student-id'] : null;
    if(is_null($studentId)) {
        throw new IdNotFoundException('Student ID not found');
    }
    $controller = new adminStudentsController();
    $response = $controller->viewStudentInformation($studentId);
    if(!$response['success']) {
        $notSuccessPdf = new FPDF();
        $notSuccessPdf->AddPage();
        $notSuccessPdf->SetFont('Arial','B',20);
        $notSuccessPdf->Cell(190,20,$response['message'],1,0,'C');
        $notSuccessPdf->Output();
    }
    //START A PAGE
    $pdf->AddPage();
    //CREATE TITLE
    $pdf->setTitle('Student Info PDF');
    //SET SCHOOL LOGO FIRST
    $pdf->Image('../../assets/imgs/logo.png',180, null, 20, 20);
    $pdf->ln();
    //HANDLE THE VALUES TO VIEW
    $student = $response['data'];
    $parents= $response['parent'];
    //LRN
    $lrn = !empty($student['LRN']) ? $student['LRN'] : 'No LRN yet';
    //STUDENT FULL NAME
    $firstName = !empty($student['First_Name']) ? $student['First_Name'] : 'No First name found';
    $lastName = !empty($student['Last_Name']) ? $student['Last_Name'] : 'No Last name found';
    $middleName = !empty($student['Middle_Name']) ? $student['Middle_Name'] . ', ': '';
    $suffix = !empty($student['Suffix']) ? $student['Suffix'] : '';
    $fullName =  $firstName . ' '. $middleName .  $lastName .' '.$suffix;
    //COMPLETE ADDRESS 
    $completeAddr = $student['House_Number'] .' ' .$student['Subd_Name']
                    . '. ' .$student['Brgy_Name']. ', ' .$student['Municipality_Name'] . ', '
                    . $student['Province_Name'] . ' ' . $student['Region'];
    //NULLABLE VALUES
    $email = !empty($student['Student_Email']) ? $student['Student_Email'] :  'No Email';
    //HIGHLIGHT STUDENT NAME AND LRN
    $pdf->SetFont('Arial','B',15);
    $pdf->Cell(70,8,'Student Name: ' .$fullName,0,0,'L');
    $pdf->ln();
    $pdf->Cell(70,8, 'Learner Reference Number: '.$lrn,0,0,'L');
    $pdf->ln(10);
    //CASCADE EACH PERSONAL INFO ADJACENT TO EACH OTHER E.G.(TITLE======VALUE)
    //SET PERSONAL INFO TITLE
    $pdf->SetFillColor(230,230,230);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(190,8,'PERSONAL INFORMATION',1,1,'C',true);
    //PREDEFINED WIDTH AND HEIGHT FOR EACH ROW
    $titleWidth = 70;
    $valueWidth = 120;
    $rowHeight = 5;
    //VALUES
    $pdf->SetFont('Arial','',13);
    //PSA
    infoRow($pdf,'PSA Number',$student['Psa_Number'],$titleWidth,$valueWidth,$rowHeight);
    //BIRTHDAY  
    infoRow($pdf,'Birth Date',$student['Readable_Birthday'],$titleWidth,$valueWidth,$rowHeight);
    //AGE
    infoRow($pdf,'Age',$student['Age'],$titleWidth,$valueWidth,$rowHeight);
    //SEX
    infoRow($pdf,'Biological Sex',ucfirst($student['Sex']),$titleWidth,$valueWidth,$rowHeight);
    //EMAIL
    infoRow($pdf,'Email',$student['Student_Email'],$titleWidth,$valueWidth,$rowHeight);
    //RELIGION
    infoRow($pdf,'Religion',$student['Religion'],$titleWidth,$valueWidth,$rowHeight);
    //NATIVE LANGUAGE
    infoRow($pdf,'Native Language',$student['Native_Language'],$titleWidth,$valueWidth,$rowHeight);
    //CULTURAL GROUP 
    infoRow($pdf,'Culutural Group',$student['Has_Cultural'],$titleWidth,$valueWidth,$rowHeight);
    //SPECIAL CONDITION
    infoRow($pdf,'Special Condition',$student['Has_Condition'],$titleWidth,$valueWidth,$rowHeight);
    //ASSISTIVE TECH
    infoRow($pdf,'Assistive  Technology',$student['Has_Tech'],$titleWidth,$valueWidth,$rowHeight);
    //ADDRESS
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell($titleWidth,$rowHeight,'Address',1,0,'L');
    $pdf->SetFont('Arial','',10);
    $pdf->MultiCell($valueWidth, $rowHeight, $completeAddr,1,'R');
    //DO THE SAME FOR PARENTS INFORMATION       
    foreach ($parents as $parentType => $parentInfo) {
        // Print section header (Father / Mother / Guardian)
        $pdf->SetFillColor(230,230,230);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(190, 8, strtoupper($parentType . ' Information'), 1, 1, 'C',true);
        // Then iterate through each key-value pair inside $parentInfo
        foreach ($parentInfo as $field => $value) {
            // Skip Parent_Type key since it's redundant
            if ($field === 'Parent_Type') continue;
            // Nicely format the field name (e.g., "First_Name" → "First Name")
            $formattedField = ucwords(str_replace('_', ' ', $field));
            infoRow($pdf, $formattedField, $value, $titleWidth, $valueWidth, $rowHeight);
        }
    }
    //OUTPUT PAGE
    $pdf->Output();
}
catch(IdNotFounException $e) {
    $errorPdf = new FPDF();
    $errorPdf->AddPage();
    $errorPdf->SetFont('Arial', 'B', 20);
    $errorPdf->Cell(190, 20, $e->getMessage(),0,0,'C');
    $errorPdf->Output();
}
catch(Throwable $t) {
    error_log("[".date('Y-m-d H:i:s')."]".$t."\n",3,__DIR__ . '/../../../BackEnd/errorLogs.txt');
    $syntaxPDF = new FPDF();
    $syntaxPDF->AddPage();
    $syntaxPDF->SetFont('Arial', 'B', 20);
    $syntaxPDF->Cell(190, 20,'There was a syntax problem. Please wait for us to fix it',0,0,'C');
    $syntaxPDF->Output();
}
function infoRow($pdf, $title, $value, $titleWidth, $valueWidth, $rowHeight) {
    // Title aligned left, bold
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell($titleWidth, $rowHeight, $title, 1, 0, 'L');
    // Value fills the rest, aligned right
    $pdf->SetFont('Arial','',10);
    $pdf->Cell($valueWidth, $rowHeight, $value, 1, 1, 'R');
}