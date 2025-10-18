<?php
require_once 'enrollment_form.php';

//create object from selectAll class

// //call selectAll functions 
// $enrollees = $selectAll->selectAllEnrollees();
// $registrations = $selectAll->selectAllRegistrations();
// $users = $selectAll->selectAllUsers();


// if ($enrollees) {
//     print_r($enrollees);
//     echo "Data found Successfully!";
// }

// if ($registrations) {
//     print_r($registrations);
//     echo "Data found Successfully!";
// }

// if ($users) {
//     print_r($users);
//     echo "Data found Successfully!";
// }


// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $enrollment_form = new EnrollmentForm();
//     // Collect form data
//     $School_Year_Start = $_POST['School_Year_Start'];
//     $School_Year_End = $_POST['School_Year_End'];
//     $If_LRNN_Returning = $_POST['If_LRNN_Returning'];
//     $Enrolling_Grade_Level = $_POST['Enrolling_Grade_Level'];
//     $Last_Grade_Level = $_POST['Last_Grade_Level'];
//     $Last_Year_Attended = $_POST['Last_Year_Attended'];

//     // Call function to insert data
//     $response = $enrollment_form->educational_information($School_Year_Start, $School_Year_End, $If_LRNN_Returning, $Enrolling_Grade_Level, $Last_Grade_Level, $Last_Year_Attended);

//     echo $response;
// }

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $enrollment_form = new EnrollmentForm();
//     // Collect form data
//     $Last_School_Attended = $_POST['Last_School_Attended'];
//     $School_Id = $_POST['School_Id'];
//     $School_Address = $_POST['School_Address'];
//     $School_Type = $_POST['School_Type'];
//     $Initial_School_Choice = $_POST['Initial_School_Choice'];
//     $Initial_School_Id = $_POST['Initial_School_Id'];
//     $Initial_School_Address = $_POST['Initial_School_Address'];
//     // Call function to insert data
//     $response = $enrollment_form->educational_background($Last_School_Attended, $School_Id, $School_Address, $School_Type, $Initial_School_Choice, $Initial_School_Id, $Initial_School_Address);

//     echo $response;
// }

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $enrollment_form = new EnrollmentForm();


//     // Collect form data
//     $Have_Special_Condition = $_POST['Have_Special_Condition'];
//     $Have_Assistive_Tech = $_POST['Have_Assistive_Tech'];
//     $Special_Condition = $_POST['Special_Condition'];
//     $Assistive_Tech = $_POST['Assistive_Tech'];
//     // Call function to insert data
//     $response = $enrollment_form->disabled_student($Have_Special_Condition, $Have_Assistive_Tech, $Special_Condition, $Assistive_Tech);

//     echo $response;
// }

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $enrollment_form = new EnrollmentForm();

//     // Collect form data
//     $House_Number = $_POST['House_Number'];
//     $Subd_Name = $_POST['Subdivision_Name'];
//     $Brgy_Name = $_POST['Barangay_Name'];
//     $Municipality_Name = $_POST['Municipality_Name'];
//     $Province_Name = $_POST['Province_Name'];
//     $Region = $_POST['Region'];
//     // Call function to insert data
//     $response = $enrollment_form->enrollee_address($House_Number, $Subd_Name, $Brgy_Name, $Municipality_Name, $Province_Name, $Region);

//     echo $response;
// }


// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $enrollment_form = new EnrollmentForm();

//     // Collect form data
//     $Father_First_Name = $_POST['Father_First_Name'];
//     $Father_Last_Name = $_POST['Father_Last_Name'];
//     $Father_Middle_Name = $_POST['Father_Middle_Name'];
//     $Parent_Type = "Father";
//     $Father_Educational_Attainment = $_POST['Father_Educational_Attainment'];
//     $Father_Contact_Number = $_POST['Father_Contact_Number'];
//     $Father_Email = $_POST['Father_Email'];
//     $If_4Ps = $_POST['If_4Ps'];

//     // Call function to insert data
//     $response = $enrollment_form->father_information($Father_First_Name, $Father_Last_Name, $Father_Middle_Name, $Parent_Type, 
//     $Father_Educational_Attainment, $Father_Contact_Number, $Father_Email, $If_4Ps);

//     echo $response;
// }

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $enrollment_form = new EnrollmentForm();

//     // Collect form data
//     $Mother_First_Name = $_POST['Mother_First_Name'];
//     $Mother_Last_Name = $_POST['Mother_Last_Name'];
//     $Mother_Middle_Name = $_POST['Mother_Middle_Name'];
//     $Parent_Type = "Mother";
//     $Mother_Educational_Attainment = $_POST['Mother_Educational_Attainment'];
//     $Mother_Contact_Number = $_POST['Mother_Contact_Number'];
//     $Mother_Email = $_POST['Mother_Email'];
//     $If_4Ps = $_POST['If_4Ps'];

//     // Call function to insert data
//     $response = $enrollment_form->mother_information($Mother_First_Name, $Mother_Last_Name, $Mother_Middle_Name, $Parent_Type, 
//     $Mother_Educational_Attainment, $Mother_Contact_Number, $Mother_Email, $If_4Ps);

//     echo $response;
// }
//class User {
//    private $fName;
//    private $lName;
//    private $mName;
 //   private $parentType;
   // private $educationalAttainment;
    //private $contactNumber;
 //   private $email;
  //  private $if4Ps;

   // public function _construct(){

   // }
//}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $enrollment_form = new EnrollmentForm();

    // Collect form data
    $Guardian_First_Name = $_POST['Guardian_First_Name'];
    $Guardian_Last_Name = $_POST['Guardian_Last_Name'];
    $Guardian_Middle_Name = $_POST['Guardian_Middle_Name'];
    $Parent_Type = "Guardian";
    $Guardian_Educational_Attainment = $_POST['Guardian_Educational_Attainment'];
    $Guardian_Contact_Number = $_POST['Guardian_Contact_Number'];
    $Guardian_Email = $_POST['Guardian_Email'];
    $If_4Ps = $_POST['If_4Ps'];

    // Call function to insert data
    $enrollment_form->guardian_information($Guardian_First_Name, $Guardian_Last_Name, $Guardian_Middle_Name, $Parent_Type, 
    $Guardian_Educational_Attainment, $Guardian_Contact_Number, $Guardian_Email, $If_4Ps);

    echo ("All data successfully inserted!");

    
}
?>