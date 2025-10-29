<?php
    require_once __DIR__ . '/../../core/dbconnection.php';
    require_once __DIR__ . '/../../core/encryption_and_decryption.php';

    class adminEditInformation {
        protected $conn;
        private $Staff_Id;
        protected $Encryption;
    
        //automatically run and connect database
        public function __construct() {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $db = new Connect();
            $this->conn = $db->getConnection();
            $this->Staff_Id = $_SESSION['Staff']['Staff-Id'];
            $this->Encryption = new Encryption();
        }

        public function Has_Address_Id() {
            //1. Check if the teacher already has Address_Id
            $sql_check_address = "SELECT Staff_Address_Id FROM `staffs` WHERE Staff_Id = :Staff_Id AND Staff_Id IS NOT NULL";
            $check_address = $this->conn->prepare($sql_check_address);
            $check_address->bindparam(':Staff_Id', $this->Staff_Id);
            $check_address->execute();
            $result = $check_address->fetch(PDO::FETCH_ASSOC);

            //2. If the teacher has Address_Id, return true, else return false
            return $result ? true : false;
        }

        public function Has_Identifiers_Id() {
            //1. Check if the teacher already has Identifiers_Id
            $sql_check_identifiers = "SELECT Staff_Identifier_Id FROM `staffs` WHERE Staff_Id = :Staff_Id AND Staff_Identifier_Id IS NOT NULL";
            $check_identifiers = $this->conn->prepare($sql_check_identifiers);
            $check_identifiers->bindparam(':Staff_Id', $this->Staff_Id);
            $check_identifiers->execute();
            $result = $check_identifiers->fetch(PDO::FETCH_ASSOC);

            //2. If the teacher has Identifiers_Id, return true, else return false
            return $result ? true : false;
        }

        public function Update_Address($House_Number, $Subd_Name, $Brgy_Name, $Municipality_Name, $Province_Name, $Region) {
            //1. Check if the teacher already has Address_Id
            //2. If the teacher has Address_Id, update the address
            if($this->Has_Address_Id()) {
                $sql_get_address_id = "SELECT Staff_Address_Id FROM `staffs` WHERE Staff_Id = :Staff_Id";
                $get_address_id = $this->conn->prepare($sql_get_address_id);
                $get_address_id->bindparam(':Staff_Id', $this->Staff_Id);
                $get_address_id->execute();
                $result = $get_address_id->fetch(PDO::FETCH_ASSOC);
                $address_id = $result['Staff_Address_Id'];

                $sql_update_address = "UPDATE staff_address
                                        SET House_Number = :House_Number,
                                            Subd_Name = :Subd_Name,
                                            Brgy_Name = :Brgy_Name,
                                            Municipality_Name = :Municipality_Name,
                                            Province_Name = :Province_Name,
                                            Region = :Region
                                        WHERE Staff_Address_Id = :Staff_Address_Id";
                                        
                $update_address = $this->conn->prepare($sql_update_address);
                $update_address->bindparam(':House_Number', $House_Number);
                $update_address->bindparam(':Subd_Name', $Subd_Name);
                $update_address->bindparam(':Brgy_Name', $Brgy_Name);
                $update_address->bindparam(':Municipality_Name', $Municipality_Name);
                $update_address->bindparam(':Province_Name', $Province_Name);
                $update_address->bindparam(':Region', $Region);
                $update_address->bindparam(':Staff_Address_Id', $address_id);
                if($update_address->execute()) {
                    return [
                        'success' => true,
                        'message' => 'Address updated successfully',
                        'Address_Id' => 'Successfully updated',
                        'Staff_Address_Id' => $address_id
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => 'Error updating address',
                        'error' => 'Error updating address'
                    ];
                }
            }

            //3. If the teacher doesn't have Address_Id, insert the address and get the Address_Id
            else {
                $sql_insert_address = "INSERT INTO staff_address(House_Number, Subd_Name, Brgy_Name, Municipality_Name, Province_Name, Region) 
                                        VALUES (:House_Number, :Subd_Name, :Brgy_Name, :Municipality_Name, :Province_Name, :Region)";
                $insert_address = $this->conn->prepare($sql_insert_address);
                $insert_address->bindparam(':House_Number', $House_Number);
                $insert_address->bindparam(':Subd_Name', $Subd_Name);
                $insert_address->bindparam(':Brgy_Name', $Brgy_Name);
                $insert_address->bindparam(':Municipality_Name', $Municipality_Name);
                $insert_address->bindparam(':Province_Name', $Province_Name);
                $insert_address->bindparam(':Region', $Region);

                if($insert_address->execute()) {
                    return [
                        'success' => true,
                        'message' => 'Address inserted successfully',
                        'Address_Id' => $this->conn->lastInsertId()
                    ];
                } else {
                    return [
                        'success' => false,
                        'error' => 'Address'
                    ];
                }
            }
        }

        public function Update_Identifiers($Employee_Number, $Philhealth_Number, $TIN){
            //1. Check if the teacher already has Identifiers_Id
            //2 If the teacher has Identifiers_Id, update the identifiers

            $Encrypted_Employee_Number = $this->Encryption->passEncrypt($Employee_Number);
            $Encrypted_Philhealth_Number = $this->Encryption->passEncrypt($Philhealth_Number);
            $Encrypted_TIN = $this->Encryption->passEncrypt($TIN);
            if($this->Has_Identifiers_Id()) {
                $sql_get_identifiers_id = "SELECT Staff_Identifier_Id FROM `staffs` WHERE Staff_Id = :Staff_Id";
                $get_identifiers_id = $this->conn->prepare($sql_get_identifiers_id);
                $get_identifiers_id->bindparam(':Staff_Id', $this->Staff_Id);
                $get_identifiers_id->execute();
                $result = $get_identifiers_id->fetch(PDO::FETCH_ASSOC);
                $Identifier_Id = $result['Staff_Identifier_Id'];

                $sql_update_identifiers_id = "UPDATE staff_Identifiers SET 
                                            Employee_Number= :Employee_Number,
                                            Philhealth_Number=:Philhealth_Number,
                                            TIN= :TIN 
                                            WHERE Staff_Identifier_Id = :Staff_Identifier_Id";
                $update_identifiers = $this->conn->prepare($sql_update_identifiers_id);
                $update_identifiers->bindparam(':Employee_Number', $Encrypted_Employee_Number);
                $update_identifiers->bindparam(':Philhealth_Number', $Encrypted_Philhealth_Number);
                $update_identifiers->bindparam(':TIN', $Encrypted_TIN);
                $update_identifiers->bindparam(':Staff_Identifier_Id', $Identifier_Id);
                if($update_identifiers->execute()) {
                    return [
                        'success' => true,
                        'message' => 'Credentials updated successfully',
                        'Identifier_Id' => $Identifier_Id
                    ];
                } else {
                    return [
                        'success' => false,
                        'error' => 'Identifiers'
                    ];
                }
            }

            //3. If the teacher doesn't have Identifiers_Id, insert the identifiers and get the Identifiers_Id
            else {
                $sql_insert_identifiers = "INSERT INTO staff_Identifiers
                                            (Employee_Number, Philhealth_Number, TIN) 
                                            VALUES (:Employee_Number, :Philhealth_Number, :TIN)";
                $insert_identifiers = $this->conn->prepare($sql_insert_identifiers);
                $insert_identifiers->bindparam(':Employee_Number', $Encrypted_Employee_Number);
                $insert_identifiers->bindparam(':Philhealth_Number', $Encrypted_Philhealth_Number);
                $insert_identifiers->bindparam(':TIN', $Encrypted_TIN);

                if($insert_identifiers->execute()) {
                    $Identifier_Id_Insert = $this->conn->lastInsertId();
                    $sql_update_staff = "UPDATE staffs SET Staff_Identifier_Id = :Staff_Identifier_Id WHERE Staff_Id = :Staff_Id";
                    $update_staff = $this->conn->prepare($sql_update_staff);
                    $update_staff->bindparam(':Staff_Identifier_Id', $Identifier_Id_Insert);
                    $update_staff->bindparam(':Staff_Id', $this->Staff_Id);
                    if($update_staff->execute()) {
                        return [
                            'success' => true,
                            'message' => 'Credentials added successfully',
                            'Identifier_Id' => $Identifier_Id_Insert
                        ];
                    } else {
                        return [
                            'success' => false,
                            'message' => 'Error updating staff with identifier id'
                        ];
                    }
                } else {
                    return [
                        'success' => false,
                        'message' => 'Error inserting identifiers',
                        'error' => 'Identifiers'
                    ];
                }
            }
            //4. Return last inserted id
        }

        //MAIN FUNCTION!!!!
        public function Update_Information($Staff_First_Name, $Staff_Middle_Name, $Staff_Last_Name, $Staff_Email, $Staff_Contact_Number){
            try {
                if (!preg_match('/^09\d{9}$/', $Staff_Contact_Number)) {
                    return [
                        'success' => false,
                        'message' => 'Invalid phone number format. Please use 09XXXXXXXXX.',
                        'error' => 'invalid_phone_number'
                    ];
                }

                $sql_update_information = "UPDATE staffs SET
                                            Staff_First_Name = :Staff_First_Name ,
                                            Staff_Middle_Name = :Staff_Middle_Name,
                                            Staff_Last_Name = :Staff_Last_Name ,
                                            Staff_Email = :Staff_Email,
                                            Staff_Contact_Number = :Staff_Contact_Number
                                            WHERE Staff_Id = :Staff_Id";
                $update_information = $this->conn->prepare($sql_update_information);
                $update_information->bindparam(':Staff_Id', $this->Staff_Id);
                $update_information->bindparam(':Staff_First_Name', $Staff_First_Name);
                $update_information->bindparam(':Staff_Middle_Name', $Staff_Middle_Name);
                $update_information->bindparam(':Staff_Last_Name', $Staff_Last_Name);
                $update_information->bindparam(':Staff_Email', $Staff_Email);
                $update_information->bindparam(':Staff_Contact_Number', $Staff_Contact_Number);
                if($update_information->execute()) {
                    return ["success" => true, "message" => "Information updated successfully"];
                } else {
                    return ["success" => false, "message" => "Error updating information"];
                }
            } catch (PDOException $e) {
                if ($e->errorInfo[1] === 1062) {
                    return [
                        'success' => false,
                        'message' => 'Update failed: The number you entered is already registered.',
                        'error' => 'duplicate_entry'
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => 'Database error: ' . $e->getMessage(),
                        'error' => 'database'
                    ];
                }
            }
        }

        public function Update_Profile_Picture($uploaded_file) {
            try {
                $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
                $max_size = 5 * 1024 * 1024;

                if (!isset($uploaded_file['tmp_name']) || !is_uploaded_file($uploaded_file['tmp_name'])) {
                    return [
                        'success' => false,
                        'message' => 'No file uploaded'
                    ];
                }

                if (!in_array($uploaded_file['type'], $allowed_types)) {
                    return [
                        'success' => false,
                        'message' => 'Invalid file type. Only JPG, JPEG, and PNG are allowed'
                    ];
                }

                if ($uploaded_file['size'] > $max_size) {
                    return [
                        'success' => false,
                        'message' => 'File size exceeds 5MB limit'
                    ];
                }

                $extension = pathinfo($uploaded_file['name'], PATHINFO_EXTENSION);
                $filename = 'staff_' . $this->Staff_Id . '_' . time() . '.' . $extension;
                $upload_dir = __DIR__ . '/../../../ImageUploads/profile_pictures/';
                $relative_dir = '/ImageUploads/profile_pictures/';

                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                $upload_path = $upload_dir . $filename;

                if (!move_uploaded_file($uploaded_file['tmp_name'], $upload_path)) {
                    return [
                        'success' => false,
                        'message' => 'Failed to upload file'
                    ];
                }

                $sql_get_user = "SELECT User_Id, Profile_Picture_Id FROM users WHERE Staff_Id = :Staff_Id";
                $get_user = $this->conn->prepare($sql_get_user);
                $get_user->bindparam(':Staff_Id', $this->Staff_Id);
                $get_user->execute();
                $user = $get_user->fetch(PDO::FETCH_ASSOC);

                if (!$user) {
                    unlink($upload_path);
                    return [
                        'success' => false,
                        'message' => 'User not found'
                    ];
                }

                if ($user['Profile_Picture_Id']) {
                    $sql_get_old_pic = "SELECT File_Name, Directory FROM profile_directory WHERE Profile_Picture_Id = :Profile_Picture_Id";
                    $get_old_pic = $this->conn->prepare($sql_get_old_pic);
                    $get_old_pic->bindparam(':Profile_Picture_Id', $user['Profile_Picture_Id']);
                    $get_old_pic->execute();
                    $old_pic = $get_old_pic->fetch(PDO::FETCH_ASSOC);

                    if ($old_pic && $old_pic['File_Name'] !== 'default-avatar.png') {
                        $old_file = __DIR__ . '/../../../' . $old_pic['Directory'] . $old_pic['File_Name'];
                        if (file_exists($old_file)) {
                            unlink($old_file);
                        }
                    }

                    $sql_update_pic = "UPDATE profile_directory SET File_Name = :filename, Directory = :directory WHERE Profile_Picture_Id = :Profile_Picture_Id";
                    $update_pic = $this->conn->prepare($sql_update_pic);
                    $update_pic->bindparam(':filename', $filename);
                    $update_pic->bindparam(':directory', $relative_dir);
                    $update_pic->bindparam(':Profile_Picture_Id', $user['Profile_Picture_Id']);
                    $update_pic->execute();

                    return [
                        'success' => true,
                        'message' => 'Profile picture updated successfully',
                        'filename' => $filename,
                        'path' => $relative_dir . $filename
                    ];
                } else {
                    $sql_insert_pic = "INSERT INTO profile_directory (File_Name, Directory) VALUES (:filename, :directory)";
                    $insert_pic = $this->conn->prepare($sql_insert_pic);
                    $insert_pic->bindparam(':filename', $filename);
                    $insert_pic->bindparam(':directory', $relative_dir);
                    $insert_pic->execute();
                    $pic_id = $this->conn->lastInsertId();

                    $sql_update_user = "UPDATE users SET Profile_Picture_Id = :Profile_Picture_Id WHERE User_Id = :User_Id";
                    $update_user = $this->conn->prepare($sql_update_user);
                    $update_user->bindparam(':Profile_Picture_Id', $pic_id);
                    $update_user->bindparam(':User_Id', $user['User_Id']);
                    $update_user->execute();

                    return [
                        'success' => true,
                        'message' => 'Profile picture uploaded successfully',
                        'filename' => $filename,
                        'path' => $relative_dir . $filename
                    ];
                }
            } catch (Exception $e) {
                if (isset($upload_path) && file_exists($upload_path)) {
                    unlink($upload_path);
                }
                return [
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage()
                ];
            }
        }
    }
?>