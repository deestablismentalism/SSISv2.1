<?php
require_once __DIR__ .'/../../core/dbconnection.php';

class EnrollmentForm {
    protected $conn;
    
    //automatically run and connect database
    public function __construct() {
        $db = new Connect();
        $this->conn = $db->getConnection();
    }
    
    // Insert educational information function
    public function educational_information($School_Year_Start, $School_Year_End, $If_LRNN_Returning, $Enrolling_Grade_Level, $Last_Grade_Level, $Last_Year_Attended) {
        try {
            // Validate required fields
            if (empty($School_Year_Start) || empty($School_Year_End)) {
                throw new PDOException('School year start and end are required');
            }

            if (empty($Enrolling_Grade_Level)) {
                throw new PDOException('Enrolling grade level is required');
            }

            // Type casting and validation
            $School_Year_Start = filter_var($School_Year_Start, FILTER_VALIDATE_INT);
            $School_Year_End = filter_var($School_Year_End, FILTER_VALIDATE_INT);
            $Last_Year_Attended = !empty($Last_Year_Attended) ? filter_var($Last_Year_Attended, FILTER_VALIDATE_INT) : null;
            
            // Additional validation
            if ($School_Year_Start === false || $School_Year_End === false) {
                throw new PDOException('Invalid school year format');
            }

            if ($School_Year_Start >= $School_Year_End) {
                throw new PDOException('School year start must be less than school year end');
            }

            // Prepare and execute query with validated data
            $sql_educational_information = "INSERT INTO educational_information (
                School_Year_Start, 
                School_Year_End, 
                If_LRN_Returning, 
                Enrolling_Grade_Level, 
                Last_Grade_Level, 
                Last_Year_Attended
            ) VALUES (
                :School_Year_Start, 
                :School_Year_End, 
                :If_LRN_Returning, 
                :Enrolling_Grade_Level,
                :Last_Grade_Level, 
                :Last_Year_Attended
            )";

            $insert_educational_information = $this->conn->prepare($sql_educational_information);
            
            // Bind parameters with explicit types
            $insert_educational_information->bindValue(':School_Year_Start', $School_Year_Start, PDO::PARAM_INT);
            $insert_educational_information->bindValue(':School_Year_End', $School_Year_End, PDO::PARAM_INT);
            $insert_educational_information->bindValue(':If_LRN_Returning', $If_LRNN_Returning, PDO::PARAM_STR);
            $insert_educational_information->bindValue(':Enrolling_Grade_Level', $Enrolling_Grade_Level, PDO::PARAM_STR);
            $insert_educational_information->bindValue(':Last_Grade_Level', $Last_Grade_Level ?: null, PDO::PARAM_STR);
            $insert_educational_information->bindValue(':Last_Year_Attended', $Last_Year_Attended, $Last_Year_Attended === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
            
            // Execute and check for errors
            if (!$insert_educational_information->execute()) {
                $errorInfo = $insert_educational_information->errorInfo();
                error_log('SQL Error: ' . json_encode($errorInfo));
                throw new PDOException('Failed to insert educational information: ' . $errorInfo[2]);
            }
            
            $lastId = $this->conn->lastInsertId();
            if (!$lastId) {
                throw new PDOException('Failed to get last insert ID for educational information');
            }
            
            return $lastId;
        }
        catch (PDOException $e) {
            error_log('Educational information query failed: ' . $e->getMessage());
            throw new PDOException($e->getMessage());
        }
    }

    // Insert educational background function
    public function educational_background($Last_School_Attended, $School_Id, $School_Address, $School_Type, $Initial_School_Choice, $Initial_School_Id, $Initial_School_Address) {
        try {
            $sql_educational_background = "INSERT INTO educational_background (Last_School_Attended, School_Id, School_Address, 
                                        School_Type, Initial_School_Choice, Initial_School_Id, Initial_School_Address)
                                        VALUES (:Last_School_Attended, :School_Id, :School_Address, :School_Type, :Initial_School_Choice,
                                         :Initial_School_Id, :Initial_School_Address)";
            $insert_educational_background = $this->conn->prepare($sql_educational_background);
            $insert_educational_background->bindParam(':Last_School_Attended', $Last_School_Attended);
            $insert_educational_background->bindParam(':School_Id', $School_Id);
            $insert_educational_background->bindParam(':School_Address', $School_Address);
            $insert_educational_background->bindParam(':School_Type', $School_Type);
            $insert_educational_background->bindParam(':Initial_School_Choice', $Initial_School_Choice);
            $insert_educational_background->bindParam(':Initial_School_Id', $Initial_School_Id);
            $insert_educational_background->bindParam(':Initial_School_Address', $Initial_School_Address);
            if ($insert_educational_background->execute()) {
                return $this->conn->lastInsertId();
            } else {
                return ['status'=> 'error', 'message' => 'failed to insert educational background'];
            }
        } 
        catch (PDOException $e) {
            return ['status'=> 'error', 'message' => 'education background query failed: ' .$e->getMessage()];
        }
    }

    // Insert student disabled student function
    public function disabled_student($Have_Special_Condition, $Have_Assistive_Tech, $Special_Condition, $Assistive_Tech) {
        try {
            $sql_disabled_student = "INSERT INTO disabled_student (Have_Special_Condition, Have_Assistive_Tech,
                                    Special_Condition, Assistive_Tech)
                                    VALUES (:Have_Special_Condition, :Have_Assistive_Tech, :Special_Condition, :Assistive_Tech)";
            $insert_disabled_student = $this->conn->prepare($sql_disabled_student);
            $insert_disabled_student->bindParam(':Have_Special_Condition', $Have_Special_Condition);
            $insert_disabled_student->bindParam(':Have_Assistive_Tech', $Have_Assistive_Tech);
            $insert_disabled_student->bindParam(':Special_Condition', $Special_Condition);
            $insert_disabled_student->bindParam(':Assistive_Tech', $Assistive_Tech);
            if ($insert_disabled_student->execute()) {
                return $this->conn->lastInsertId();
            } else {
                return ['status' => 'error' , 'message' => 'failed to insert disability information'];
            }
        } 
        catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'disability query failed: ' . $e->getMessage()];
        } 
    }

    // Insert enrollee address function
    public function enrollee_address($House_Number, $Subd_Name, $Brgy_Name, $Brgy_Code, $Municipality_Name, $Municipality_Code, 
    $Province_Name, $Province_Code, $Region, $Region_Code) {
        try {
            $sql_enrollee_address = "INSERT INTO enrollee_address (House_Number, Subd_Name, Brgy_Name, Brgy_Code, Municipality_Name, Municipality_Code, 
                                    Province_Name, Province_Code, Region, Region_Code)
                                    VALUES (:House_Number, :Subd_Name, :Brgy_Name, :Brgy_Code, :Municipality_Name, :Municipality_Code, 
                                    :Province_Name, :Province_Code, :Region, :Region_Code)";
            $insert_enrollee_address = $this->conn->prepare($sql_enrollee_address);
            $insert_enrollee_address->bindParam(':House_Number', $House_Number);
            $insert_enrollee_address->bindParam(':Subd_Name', $Subd_Name);
            $insert_enrollee_address->bindParam(':Brgy_Name', $Brgy_Name);
            $insert_enrollee_address->bindParam(':Brgy_Code', $Brgy_Code);
            $insert_enrollee_address->bindParam(':Municipality_Name', $Municipality_Name);
            $insert_enrollee_address->bindParam(':Municipality_Code', $Municipality_Code);
            $insert_enrollee_address->bindParam(':Province_Name', $Province_Name);
            $insert_enrollee_address->bindParam(':Province_Code', $Province_Code);
            $insert_enrollee_address->bindParam(':Region', $Region);
            $insert_enrollee_address->bindParam(':Region_Code', $Region_Code);

            if ($insert_enrollee_address->execute()) {
                return $this->conn->lastInsertId();
            } else {
                return ['status'=> 'error', 'message'=> 'failed to insert enrollee address'];
            }
        }
        catch (PDOException $e) {
            return ['status'=> 'error', 'message' => 'address query failed: ' . $e->getMessage()];
        }
    }
    
    // Insert father information function
    public function father_information($Father_First_Name, $Father_Last_Name, $Father_Middle_Name, $Father_Parent_Type,
    $Father_Educational_Attainment, $Father_Contact_Number,  $FIf_4Ps) {
        try {
            $sql_father_information  = "INSERT INTO parent_information (First_Name, Last_Name, Middle_Name, Parent_Type, 
                                        Educational_Attainment, Contact_Number, If_4Ps)
                                        VALUES (:First_Name, :Last_Name, :Middle_Name, :Parent_Type, :Educational_Attainment,
                                        :Contact_Number, :If_4Ps)";
            $insert_father_information = $this->conn->prepare($sql_father_information);
            $insert_father_information->bindParam(':First_Name', $Father_First_Name);
            $insert_father_information->bindParam(':Last_Name', $Father_Last_Name);
            $insert_father_information->bindParam(':Middle_Name', $Father_Middle_Name);
            $insert_father_information->bindParam(':Parent_Type', $Father_Parent_Type);
            $insert_father_information->bindParam(':Educational_Attainment', $Father_Educational_Attainment);
            $insert_father_information->bindParam(':Contact_Number', $Father_Contact_Number);
            $insert_father_information->bindParam(':If_4Ps', $FIf_4Ps);
            if ($insert_father_information->execute()) {
                return $this->conn->lastInsertId();
            } else {
                return ['status'=> 'error', 'message'=> 'failed to insert father information'];
            }                                        
        }
        catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'father information query failed: ' . $e->getMessage()];
        }
    }

    // Insert mother information function
    public function mother_information($Mother_First_Name, $Mother_Last_Name, $Mother_Middle_Name, $Parent_Type, 
    $Mother_Educational_Attainment, $Mother_Contact_Number, $MIf_4Ps) {
        try {
            $sql_mother_information  = "INSERT INTO parent_information (First_Name, Last_Name, Middle_Name, Parent_Type, 
                                        Educational_Attainment, Contact_Number, If_4Ps)
                                        VALUES (:First_Name, :Last_Name, :Middle_Name, :Parent_Type, :Educational_Attainment,
                                        :Contact_Number, :If_4Ps)";
            $insert_mother_information = $this->conn->prepare($sql_mother_information);
            $insert_mother_information->bindParam(':First_Name', $Mother_First_Name);
            $insert_mother_information->bindParam(':Last_Name', $Mother_Last_Name);
            $insert_mother_information->bindParam(':Middle_Name', $Mother_Middle_Name);
            $insert_mother_information->bindParam(':Parent_Type', $Parent_Type);
            $insert_mother_information->bindParam(':Educational_Attainment', $Mother_Educational_Attainment);
            $insert_mother_information->bindParam(':Contact_Number', $Mother_Contact_Number);
            $insert_mother_information->bindParam(':If_4Ps', $MIf_4Ps);
            if ($insert_mother_information->execute()) {
                return $this->conn->lastInsertId();
            } else {
                return ['status'=>'error', 'message' => 'failed to insert mother information'];
            }                                        
        }
        catch (PDOException $e) {
            return ['status'=> 'error', 'message' => 'mother information query failed: ' . $e->getMessage()];
        }
    }

    // Insert guardian information function
    public function guardian_information($Guardian_First_Name, $Guardian_Last_Name, $Guardian_Middle_Name, $Parent_Type, 
    $Guardian_Educational_Attainment, $Guardian_Contact_Number, $GIf_4Ps) {
        try {
            $sql_guardian_information  = "INSERT INTO parent_information (First_Name, Last_Name, Middle_Name, Parent_Type, 
                                        Educational_Attainment, Contact_Number, If_4Ps)
                                        VALUES (:First_Name, :Last_Name, :Middle_Name, :Parent_Type, :Educational_Attainment,
                                        :Contact_Number, :If_4Ps)";
            $insert_guardian_information = $this->conn->prepare($sql_guardian_information);
            $insert_guardian_information->bindParam(':First_Name', $Guardian_First_Name);
            $insert_guardian_information->bindParam(':Last_Name', $Guardian_Last_Name);
            $insert_guardian_information->bindParam(':Middle_Name', $Guardian_Middle_Name);
            $insert_guardian_information->bindParam(':Parent_Type', $Parent_Type);
            $insert_guardian_information->bindParam(':Educational_Attainment', $Guardian_Educational_Attainment);
            $insert_guardian_information->bindParam(':Contact_Number', $Guardian_Contact_Number);
            $insert_guardian_information->bindParam(':If_4Ps', $GIf_4Ps);
            if ($insert_guardian_information->execute()) {
                return $this->conn->lastInsertId();
            } else {
                return ['status' => 'error' , 'message' => 'failed to insert guardian information'];
            }                                        
        }
        catch (PDOException $e) {
            return ['status'=> 'error', 'message'=> 'guardian query failed: ' . $e->getMessage()];
        }
    }

    // Insert images function
    public function images($filename, $directory) {
            try {
                $sql_images = "INSERT INTO Psa_directory(filename, directory) 
                                VALUES (:filename, :directory)";
                $insert_images = $this->conn->prepare($sql_images);
                $insert_images->bindParam(':filename', $filename);
                $insert_images->bindParam(':directory', $directory);
                if ($insert_images->execute()) {
                    return $this->conn->lastInsertId();
                } else {
                    return ['status'=> 'error' , 'message'=> 'failed to insert image'];
                }
            } 
            catch (PDOException $e) {
                return ['status' => 'error', 'message' => 'image query failed: ' . $e->getMessage() ];
            }
    }   

    // Insert enrollee function MAIN FUNCTION!!!!
    public function Insert_Enrollee($User_Id, $School_Year_Start, $School_Year_End, $If_LRNN_Returning, $Enrolling_Grade_Level, $Last_Grade_Level, $Last_Year_Attended,
    $Last_School_Attended, $School_Id, $School_Address, $School_Type, $Initial_School_Choice, $Initial_School_Id, $Initial_School_Address,
    $Have_Special_Condition, $Have_Assistive_Tech, $Special_Condition, $Assistive_Tech,
    $House_Number, $Subd_Name, $Brgy_Name, $Brgy_Code, $Municipality_Name, $Municipality_Code, $Province_Name, $Province_Code, $Region, $Region_Code,
    $Father_First_Name, $Father_Last_Name, $Father_Middle_Name, $Father_Parent_Type, $Father_Educational_Attainment, $Father_Contact_Number, $FIf_4Ps,
    $Mother_First_Name, $Mother_Last_Name, $Mother_Middle_Name, $Mother_Parent_Type, $Mother_Educational_Attainment, $Mother_Contact_Number, $MIf_4Ps,
    $Guardian_First_Name, $Guardian_Last_Name, $Guardian_Middle_Name, $Guardian_Parent_Type, $Guardian_Educational_Attainment, $Guardian_Contact_Number, $GIf_4Ps,
    $Student_First_Name, $Student_Middle_Name, $Student_Last_Name, $Student_Extension, $Learner_Reference_Number, $Psa_Number, $Birth_Date, $Age, $Sex, $Religion, 
    $Native_Language, $If_Cultural, $Cultural_Group, $Student_Email, $Enrollment_Status, $filename, $directory) {
        try{
            // If even one of the queries fail, none of the queries will be executed
            $this->conn->beginTransaction();

            // Call the functions to insert data
            $Educational_Information_Id = $this->educational_information($School_Year_Start, $School_Year_End, $If_LRNN_Returning, $Enrolling_Grade_Level, $Last_Grade_Level, $Last_Year_Attended);
            $Educational_Background_Id = $this->educational_background($Last_School_Attended, $School_Id, $School_Address, $School_Type, $Initial_School_Choice, $Initial_School_Id, $Initial_School_Address);
            $Disabled_Student_Id = $this->disabled_student($Have_Special_Condition, $Have_Assistive_Tech, $Special_Condition, $Assistive_Tech);
            $Enrollee_Address_Id = $this->enrollee_address($House_Number, $Subd_Name, $Brgy_Name, $Brgy_Code, $Municipality_Name, $Municipality_Code, $Province_Name, $Province_Code, $Region, $Region_Code);
            $Father_Information_Id = $this->father_information($Father_First_Name, $Father_Last_Name, $Father_Middle_Name, $Father_Parent_Type, $Father_Educational_Attainment, $Father_Contact_Number, $FIf_4Ps);
            $Mother_Information_Id = $this->mother_information($Mother_First_Name, $Mother_Last_Name, $Mother_Middle_Name, $Mother_Parent_Type, $Mother_Educational_Attainment, $Mother_Contact_Number, $MIf_4Ps);
            $Guardian_Information_Id = $this->guardian_information($Guardian_First_Name, $Guardian_Last_Name, $Guardian_Middle_Name, $Guardian_Parent_Type, $Guardian_Educational_Attainment, $Guardian_Contact_Number, $GIf_4Ps);
            $Psa_Image_Id = $this->images($filename, $directory);
            $Enrollee_Id;

            
            if (!$Educational_Background_Id || !$Educational_Information_Id || !$Disabled_Student_Id || !$Enrollee_Address_Id
              || !$Father_Information_Id || !$Mother_Information_Id || !$Guardian_Information_Id || !$Psa_Image_Id) {
                throw new Exception("Error: Failed to insert enrollee.");
            }

            // Insert enrollee
            $sql_enrollee = "INSERT INTO enrollee (User_Id,Student_First_Name, Student_Middle_Name, Student_Last_Name, Student_Extension, Learner_Reference_Number, Psa_Number, Birth_Date, Age, Sex, Religion, 
                            Native_Language, If_Cultural, Cultural_Group, Student_Email, Enrollment_Status, Enrollee_Address_Id,
                            Educational_Information_Id, Educational_Background_Id, Disabled_Student_Id, Psa_Image_Id)
                            VALUES (:User_Id,:Student_First_Name, :Student_Middle_Name, :Student_Last_Name, :Student_Extension, :Learner_Reference_Number, :Psa_Number, :Birth_Date, :Age, :Sex, :Religion, :Native_Language, 
                            :If_Cultural, :Cultural_Group, :Student_Email, :Enrollment_Status, :Enrollee_Address_Id, :Educational_Information_Id, 
                            :Educational_Background_Id, :Disabled_Student_Id, :Psa_Image_Id);";

            // just binding parameters
            $insert_enrollee = $this->conn->prepare($sql_enrollee);
            $insert_enrollee->bindParam(':User_Id', $User_Id);
            $insert_enrollee->bindParam(':Student_First_Name', $Student_First_Name);
            $insert_enrollee->bindParam(':Student_Middle_Name', $Student_Middle_Name);
            $insert_enrollee->bindParam(':Student_Last_Name', $Student_Last_Name);
            $insert_enrollee->bindParam(':Student_Extension', $Student_Extension);
            $insert_enrollee->bindParam(':Learner_Reference_Number', $Learner_Reference_Number);
            $insert_enrollee->bindParam(':Psa_Number', $Psa_Number);
            $insert_enrollee->bindParam(':Birth_Date', $Birth_Date);
            $insert_enrollee->bindParam(':Age', $Age);
            $insert_enrollee->bindParam(':Sex', $Sex);
            $insert_enrollee->bindParam(':Religion', $Religion);
            $insert_enrollee->bindParam(':Native_Language', $Native_Language);
            $insert_enrollee->bindParam(':If_Cultural', $If_Cultural);
            $insert_enrollee->bindParam(':Cultural_Group', $Cultural_Group);
            $insert_enrollee->bindParam(':Student_Email', $Student_Email);
            $insert_enrollee->bindParam(':Enrollment_Status', $Enrollment_Status);
            $insert_enrollee->bindParam(':Enrollee_Address_Id', $Enrollee_Address_Id);
            $insert_enrollee->bindParam(':Educational_Information_Id', $Educational_Information_Id);
            $insert_enrollee->bindParam(':Educational_Background_Id', $Educational_Background_Id);
            $insert_enrollee->bindParam(':Disabled_Student_Id', $Disabled_Student_Id);
            $insert_enrollee->bindParam(':Psa_Image_Id', $Psa_Image_Id);
            if ($insert_enrollee->execute()) {
                // If the enrollee is successfully inserted, get the last inserted ID
                $Enrollee_Id = $this->conn->lastInsertId();
            } else {
                $this->conn->rollBack();
                throw new Exception("Error: Failed to insert enrollee.");
            }

            // Initialize Variables for parent type
            if ($Father_Information_Id) {
                $sql_enrollee_father = "SELECT Parent_Type FROM parent_information WHERE Parent_Id = :Father_Information_Id";
                $select_enrollee_father_type = $this->conn->prepare($sql_enrollee_father);
                $select_enrollee_father_type->bindParam(':Father_Information_Id', $Father_Information_Id);
                $select_enrollee_father_type->execute();
                $Father_Parent_Type = $select_enrollee_father_type->fetch(PDO::FETCH_ASSOC)['Parent_Type'];
            }

            if ($Mother_Information_Id) {
                $sql_enrollee_mother = "SELECT Parent_Type FROM parent_information WHERE Parent_Id = :Mother_Information_Id";
                $select_enrollee_mother_type = $this->conn->prepare($sql_enrollee_mother);
                $select_enrollee_mother_type->bindParam(':Mother_Information_Id', $Mother_Information_Id);
                $select_enrollee_mother_type->execute();
                $Mother_Parent_Type = $select_enrollee_mother_type->fetch(PDO::FETCH_ASSOC)['Parent_Type'];
            }
          
            if ($Guardian_Information_Id) {
                $sql_enrollee_guardian = "SELECT Parent_Type FROM parent_information WHERE Parent_Id = :Guardian_Information_Id";
                $select_enrollee_guardian_type = $this->conn->prepare($sql_enrollee_guardian);
                $select_enrollee_guardian_type->bindParam(':Guardian_Information_Id', $Guardian_Information_Id);
                $select_enrollee_guardian_type->execute();
                $Guardian_Parent_Type = $select_enrollee_guardian_type->fetch(PDO::FETCH_ASSOC)['Parent_Type'];
            }
          
            // Insert parent-student relationship in junction
            //father
            $sql_father_student_relationship = "INSERT INTO enrollee_parents (Enrollee_Id, Parent_Id, Relationship)
                                                VALUES (:Enrollee_Id, :Parent_Id, :Relationship)";
            $insert_father_student_relationship = $this->conn->prepare($sql_father_student_relationship);
            $insert_father_student_relationship->bindParam(':Enrollee_Id', $Enrollee_Id);
            $insert_father_student_relationship->bindParam(':Parent_Id', $Father_Information_Id);
            $insert_father_student_relationship->bindParam(':Relationship', $Father_Parent_Type);
            if(!$insert_father_student_relationship->execute()) {
                throw new PDOException("Error: Failed to insert father-student relationship.");
            } 
            //mother
            $sql_mother_student_relationship = "INSERT INTO enrollee_parents (Enrollee_Id, Parent_Id, Relationship)
                                                VALUES (:Enrollee_Id, :Parent_Id, :Relationship)";
            $insert_mother_student_relationship = $this->conn->prepare($sql_mother_student_relationship);
            $insert_mother_student_relationship->bindParam(':Enrollee_Id', $Enrollee_Id);
            $insert_mother_student_relationship->bindParam(':Parent_Id', $Mother_Information_Id);
            $insert_mother_student_relationship->bindParam(':Relationship', $Mother_Parent_Type);
            if(!$insert_mother_student_relationship->execute()) {
                throw new PDOException("Error: Failed to insert mother-student relationship.");
            } 
            //guardian
            $sql_guardian_student_relationship = "INSERT INTO enrollee_parents (Enrollee_Id, Parent_Id, Relationship)
                                                  VALUES (:Enrollee_Id, :Parent_Id, :Relationship)";
            $insert_guardian_student_relationship = $this->conn->prepare($sql_guardian_student_relationship);
            $insert_guardian_student_relationship->bindParam(':Enrollee_Id', $Enrollee_Id);
            $insert_guardian_student_relationship->bindParam(':Parent_Id', $Guardian_Information_Id);
            $insert_guardian_student_relationship->bindParam(':Relationship', $Guardian_Parent_Type);
            if(!$insert_guardian_student_relationship->execute()) {
               throw new PDOException("Error: Failed to insert guardian-student relationship.");
            } 
            $this->conn->commit();
            return ['success' => true , 'message' => 'Submission successful'];
        }
        catch(PDOException $e) {
            //rollback if something goes wrong
            $this->conn->rollBack();
            return ['success' => false , 'message' => 'Submission failed: ' .$e->getMessage()];
        }
    }

    //check matching numeric values in the database
    public function checkLRN($lrn) {

        $sql = 'SELECT Learner_Reference_Number FROM enrollee WHERE Learner_Reference_Number = :lrn';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':lrn', $lrn);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    public function checkPSA($psa) {

        $sql = 'SELECT Psa_Number FROM enrollee WHERE Psa_Number = :psa';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':psa', $psa);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
}
?>