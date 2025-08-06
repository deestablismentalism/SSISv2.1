<?php
    require_once __DIR__ . '/./models/adminTeacherInfoModel.php';
    require_once __DIR__ . '/../core/encryption_and_decryption.php';


    class adminTeacherInformationView {
        protected $teacherInfoModel;
        protected $staff_id;
        protected $teacherInformation;
        protected $encryption;

        const ACTIVE = 1;
        const RETIRED = 2;
        const TRANSFERRED_OUT = 3; 

        public function __construct(){
            $this->teacherInfoModel = new adminTeacherInformationModel();
            $this->encryption = new Encryption();
            if (isset($_GET['staff_id'])) {
                $this->staff_id = $_GET['staff_id'];
                $this->teacherInformation = $this->teacherInfoModel->getAllResults($this->staff_id);
            
                echo '<script>
                    sessionStorage.setItem("staff_id", ' . json_encode($this->staff_id) . ');
                </script>';
            } else {
                echo "No teacher has been selected";
            }
        }

        public function displayFullName() {
            $First_Name = $this->teacherInformation['Staff_First_Name'];
            $Middle_Name = $this->teacherInformation['Staff_Middle_Name'];
            $Last_Name = $this->teacherInformation['Staff_Last_Name'];
            $Full_Name = $First_Name . " " . $Middle_Name . " " . $Last_Name;
            echo $Full_Name;
        }

        public function displayEmail() {
            $Email = $this->teacherInformation['Staff_Email'];
            echo $Email;
        }

        public function displayContact() {
            $Contact_Number = $this->teacherInformation['Staff_Contact_Number'];
            echo $Contact_Number;
        }

        public function displayAddress() {
            if (!isset($this->teacherInformation['staff_address_id'])) {
                echo "N/A";
            }
            else {

                $Region = $this->teacherInformation['Region'];
                $Province_Name = $this->teacherInformation['Province_Name'];
                $Municipality_Name = $this->teacherInformation['Municipality_Name'];
                $Barangay_Name = $this->teacherInformation['Brgy_Name'];
                $Subdivision = $this->teacherInformation['Subd_Name'];
                $House_Number = $this->teacherInformation['House_Number'];
                $Address = $Region . "" . ", " . $Province_Name . ", " . $Municipality_Name . ", " . $Barangay_Name . ", " . $Subdivision . ", " . $House_Number;
                echo $Address;
            }
        }

        public function displayStatus() {
            $status = $this->teacherInformation['Staff_Status'];
            switch ($status) {
                case self::ACTIVE:
                    echo "Active";
                    break;
                case self::TRANSFERRED_OUT:
                    echo "Transferred Out";
                    break;
                case self::RETIRED:
                    echo "Retired";
                    break;
                default:
                    echo "Unknown Status";
            }
        }

        public function displayPosition() {

            
            if(!isset($this->teacherInformation['Position'])) {
                echo "N/A";
            }
            else {
                $Position = $this->teacherInformation['Position'];
                echo $Position;
            }
            
            
        }

        public function displayEmployeeNumber() {
            if(!isset($this->teacherInformation['Employee_Number'])) {
                echo "N/A";
            }
            else {
                $Employee_Number = $this->teacherInformation['Employee_Number'];
                $Decrypted_Employee_Number = $this->encryption->passDecrypt($Employee_Number);
                echo $Decrypted_Employee_Number;
            }


        }

        public function displayPhilhealthNumber() {
            if(!isset($this->teacherInformation['Philhealth_Number'])) {
                echo "N/A";
            }
            else {
                $Philhealth_Number = $this->teacherInformation['Philhealth_Number'];
                $Decrypted_Philhealth_Number = $this->encryption->passDecrypt($Philhealth_Number);
                echo $Decrypted_Philhealth_Number;
            }
        }

        public function displayTIN() {
            if(!isset($this->teacherInformation['TIN'])) {
                echo "N/A";
            }
            else {
                $TIN = $this->teacherInformation['TIN'];
                $Decrypted_TIN = $this->encryption->passDecrypt($TIN);
                echo $Decrypted_TIN;
            }
        }
    }
?>