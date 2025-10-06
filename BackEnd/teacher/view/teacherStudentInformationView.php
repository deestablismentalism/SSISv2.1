<?php

declare(strict_types=1);
require_once __DIR__ . '/./models/teacherStudentInformationModel.php';
class teacherStudentInformationView {
   
        protected $studentInformationModel;
        protected $studentId;
        public function __construct() {
          try {
            $this->studentInformationModel = new teacherStudentInformationModel();
            if(!isset($_GET['student_id'])) {throw new Exception('Student Not Found');}
            $this->studentId = $_GET['student_id'];
        }
          catch(Exception $e) {
             echo '<div><h1>'. $e->getMessage(). '</h1></div>';
          }
        }
    
        public function displayStudentInformation() {
            try {
                $studentInfo = $this->studentInformationModel->getStudentInformation($this->studentId);
                if(empty($studentInfo)) {
                    throw new Exception('No Student Information found');
                } 
                $studentInfoArray = [];
                $data = $studentInfo;
    
                $firstName = htmlspecialchars($data['First_Name']);
                $lastName = htmlspecialchars($data['Last_Name']);
                $hasMiddleInitial = !empty($data['Middle_Name']) ? htmlspecialchars($data['Middle_Name']) : '';
                $hasSuffix = !empty($data['Suffix']) ? ', ' . htmlspecialchars($data['Suffix']) : '';
    
                $fullName = $lastName . ', ' . $firstName . ' ' . $hasMiddleInitial . $hasSuffix;
                $studentInfoArray = [
                    'Buong Pangalan' => $fullName,
                    'Petsa ng Kapanganakan' => htmlspecialchars($data['Birthday']),
                    'Edad' => $data['Age'],
                    'LRN' => $data['LRN'],
                    'Kasarian' => htmlspecialchars($data['Sex']),
                    'Baitang' => htmlspecialchars($data['Grade_Level']),
                    'Section' => htmlspecialchars($data['Section_Name'])
                ];
            
                if(empty($studentInfoArray)) {
                    throw new Exception('No Student Information yet');
                }
                foreach($studentInfoArray as $label => $value) {
                    echo '<tr>
                        <td>'. $label .' </td>
                        <td>'. $value .'</td>
                    </tr>';
            }
            }
            catch(Exception $e) {
                echo '<tr> <td>'.$e->getMessage().'<td></tr>';
            }
        }
        public function displayAddress() {
            try {
                $studentAddress = $this->studentInformationModel->getStudentAddress($this->studentId);
                if(empty($studentAddress)) {
                    throw new Exception('No Address found');
                }
                $data =$studentAddress;
    
                $region = htmlspecialchars($data['Region']);
                $province = htmlspecialchars($data['Province_Name']);
                $municipality = htmlspecialchars($data['Municipality_Name']);
                $brgy = htmlspecialchars($data['Brgy_Name']);
                $subd = htmlspecialchars($data['Subd_Name']);
                $houseNumber = !empty($data['House_Number']) ? $data['House_Number'] .'.' : '';
    
                $completeAddress = $houseNumber . ', ' . $subd . ' ' . $brgy . ', ' . $municipality . ', ' . $province . ' ' . $region;
                
                if(empty($completeAddress)) {
                    throw new Exception('No Address set');
                }
                echo '<tr> 
                        <td> Address </td>
                        <td>' .$completeAddress.'</td>
                </tr>';
            }
            catch(Exception $e) {
                echo '<tr><td>'.$e->getMessage(). '</td></tr>';
            }
        }
    
        public function displayStudentParents() {
            try {
                $studentParents = $this->studentInformationModel->getStudentParents($this->studentId);

                if(empty($studentParents)) {
                    throw new Exception('Parents not found.');
                }
                foreach($studentParents as $rows) {
                    $firstName = htmlspecialchars($rows['First_Name']);
                    $lastName = htmlspecialchars($rows['Last_Name']);
                    $hasMiddleName = !empty($rows['Middle_Name']) ? htmlspecialchars($rows['Middle_Name']) : '';
                    $fullName = $lastName . ', ' . $firstName . ' ' . $hasMiddleName;
                    $hasContactNumber = !empty($rows['Contact_Number']) ? '('.$rows['Contact_Number'].')' : '';

                    echo '<tr>
                        <td>'.htmlspecialchars($rows['Parent_Type']).'</td> 
                        <td>'. $fullName . $hasContactNumber .'</td>
                            </tr>';
                }
            }
            catch(Exception $e) {
                echo '<tr> <td>'.$e->getMessage().'<td></tr>';
            }
        }
}
