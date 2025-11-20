<?php
declare(strict_types=1);
require_once __DIR__ . '/../models/userPostEnrollmentFormModel.php';
require_once __DIR__ . '/../models/userEnrolleesModel.php';
require_once __DIR__ . '/../../Exceptions/DatabaseException.php';
require_once __DIR__ . '/../../Exceptions/IdNotFoundException.php';
require_once __DIR__ . '/../../core/normalizeName.php';

class userEnrollmentFormController {
    protected $postFormModel;
    protected $enrolleesModel;

    public function __construct() {
        $this->postFormModel = new userPostEnrollmentFormModel();
        $this->enrolleesModel = new userEnrolleesModel();
    }
    //API
    public function apiPostAddEnrollee(?int $uId,int $schoolYStart,int $schoolYEnd,int $hasLRN, int $enrollGLevel,?int $lastGLevel,?int $lastYAttended, 
    string $lastSAttended,?int $sId,string $sAddress,string $sType, string $initalSChoice, ?int $initialSId,string $initialSAddrress
    ,int $hasSpecialCondition,int $hasAssistiveTech,?string $specialCondition,?string $assistiveTech,
    ?int $hNumber,?string $subdName,?string $bName,?string $bCode,?string $mName,?string $mCode,?string $pName,?string $pCode,?string $rName,?string $rCode,
    string $gFName,string $gLName,?string $gMName,string $gParentType,string $gEduAttainment,string $gCpNum, int $gIs4Ps,
    string $stuFName,string $stuLName,?string $stuMName,?string $stuSuffix,?int $lrn, string $birthDate,
    int $age,string $sex,string $religion,string $natLang,int $isCultural,?string $culturalG, string $studentEmail, int $enrollStat,
    ?array $reportCardFront, ?array $reportCardBack) : array {
        try {
            // Remove the strict null check - allow admin enrollment with null userId
            // if(is_null($uId)) {
            //     throw new IdNotFoundException("Current User's ID not found");
            // }
            
            if($hasLRN === 1 && empty($lrn)) {
                return [
                    'httpcode'=>400,
                    'success'=> false,
                    'message'=> 'LRN cannot be empty if not returning or a new student',
                    'data'=> []
                ];
            }
            // Only check LRN if it's provided (not null)
            if($lrn !== null) {
                $isMatchingLrn = $this->postFormModel->checkLRN($lrn);
                if($isMatchingLrn) {
                    return [
                        'httpcode'=> 400,
                        'success'=> false,
                        'message'=> 'LRN provided already exists. Cannot input an existing Learner Reference Number',
                        'data'=> []
                    ];
                }
            }
            $currentYear = (int)date('Y');
            if (($schoolYStart < $currentYear || $schoolYStart > ($currentYear + 1)) || ($schoolYEnd <= $schoolYStart || $schoolYEnd > ($currentYear + 2)) ) {
                return [
                    'httpcode'=>400,
                    'success'=> false,
                    'message'=> 'Invalid academic year format',
                    'data'=> []
                ];
            }
            if ($lastYAttended > $currentYear) {
                return [
                    'httpcode'=>400,
                    'success'=> false,
                    'message'=> 'Last year attended is greater than current year. Please change it',
                    'data'=> []
                ];
            }
            if($hasSpecialCondition === 1 && empty($specialCondition)) {
                return [
                    'httpcode'=>400,
                    'success'=> false,
                    'message'=> 'Special condition not specified',
                    'data'=> []
                ];
            }
            if($hasAssistiveTech === 1 && empty($assistiveTech)) {
                return [
                    'httpcode'=> 400,
                    'success'=> false,
                    'message'=> 'Assistive technology not specified',
                    'data'=> []
                ];
            }
            if(empty($stuFName) || empty($stuLName)) {
                $isFirstName = empty($stuFName) ? 'first name' : 'last name';
                return [
                    'httpcode'=> 400,
                    'success'=> false,
                    'message'=> 'Enrollee'.$isFirstName.'cannnot be empty. Please try again',
                    'data'=> []
                ];
            }
            
            // Check report card requirement - skip for Kinder 1 (grade level 1)
            $isKinder1 = ($enrollGLevel === 1);
            
            if(!$isKinder1 && empty($reportCardFront)) {
                return [
                    'httpcode'=> 400,
                    'success'=> false,
                    'message'=> 'Report card front image is required',
                    'data'=> []
                ];
            }
            
            if(!$isKinder1 && empty($reportCardBack)) {
                return [
                    'httpcode'=> 400,
                    'success'=> false,
                    'message'=> 'Report card back image is required',
                    'data'=> []
                ];
            }
            
            //===NORMALIZE NAMES===
            $normalize = fn($n)=>(new normalizeName($n))->validatedNormalize();
            $gFName = $normalize($gFName);
            $gLName = $normalize($gLName);
            $stuFName = $normalize($stuFName);
            $stuLName = $normalize($stuLName);
            //===NORMALIZE MIDDLE NAMES IF NOT EMPTY===
            $gMName = !empty($gMName) ? $normalize($gMName) : null;
            $stuMName = !empty($stuMName) ? $normalize($stuMName) : null;
            
            //attempt enrollee insert - userId can be null for admin enrollment
            $enrolleeId = $this->postFormModel->insert_enrollee($uId, $schoolYStart,$schoolYEnd,$hasLRN,$enrollGLevel,$lastGLevel,$lastYAttended,
            $lastSAttended,$sId,$sAddress,$sType,$initalSChoice,$initialSId,$initialSAddrress,
            $hasSpecialCondition,$hasAssistiveTech,$specialCondition,$assistiveTech,
            $hNumber,$subdName,$bName,$bCode,$mName,$mCode,$pName,$pCode,$rName,$rCode,
            $gFName,$gLName,$gMName,$gParentType,$gEduAttainment,$gCpNum,$gIs4Ps,
            $stuFName,$stuLName,$stuMName,$stuSuffix,$lrn,$birthDate,$age,$sex,$religion,
            $natLang,$isCultural,$culturalG,$studentEmail,$enrollStat);
            if($enrolleeId > 0) {
                // Process report card with OCR verification (skip for Kinder 1)
                if (!$isKinder1) {
                    require_once __DIR__ . '/../../admin/controllers/reportCardController.php';
                    $reportCardController = new reportCardController();
                    
                    $studentFullName = trim($stuFName . ' ' . ($stuMName ? $stuMName . ' ' : '') . $stuLName);
                    $studentLrnStr = $lrn !== null ? str_pad((string)$lrn, 12, '0', STR_PAD_LEFT) : '000000000000';
                    
                    // Generate session ID for tracking
                    $sessionId = session_id();
                    
                    // Delete any previous validation-only submissions from this session
                    require_once __DIR__ . '/../../admin/models/reportCardModel.php';
                    $reportCardModel = new reportCardModel();
                    $reportCardModel->deleteValidationSubmissions($sessionId);
                    
                    // Process final report card submission (not validation_only)
                    $reportCardResult = $reportCardController->processReportCardUpload(
                        $uId, 
                        $studentFullName, 
                        $studentLrnStr, 
                        $reportCardFront, 
                        $reportCardBack, 
                        $enrolleeId,
                        $sessionId,
                        0  // validation_only = 0 (this is final submission)
                    );
                    
                    // Update Report_Card_Id in enrollee table if submission was created
                    if (isset($reportCardResult['data']['submission_id']) && $reportCardResult['data']['submission_id'] > 0) {
                        $reportCardSubmissionId = (int)$reportCardResult['data']['submission_id'];
                        $updateResult = $this->postFormModel->updateReportCardId($enrolleeId, $reportCardSubmissionId);
                        
                        if (!$updateResult) {
                            error_log("[".date('Y-m-d H:i:s')."] Warning: Failed to update Report_Card_Id for Enrollee_Id: {$enrolleeId}\n", 3, __DIR__ . '/../../../errorLogs.txt');
                        }
                    }
                } else {
                    // Kinder 1 - skip report card processing
                    error_log("[".date('Y-m-d H:i:s')."] Info: Kinder 1 enrollment - skipping report card validation for Enrollee_Id: {$enrolleeId}\n", 3, __DIR__ . '/../../../errorLogs.txt');
                    $reportCardResult = [
                        'data' => [
                            'status' => 'exempt',
                            'submission_id' => null
                        ]
                    ];
                }
                
                // Even if OCR fails, enrollment is still created (just flagged for review)
                $reportCardStatus = $reportCardResult['data']['status'] ?? 'pending_review';
                $statusMessage = $isKinder1 ? 'Kinder 1 - no report card required' : 
                                 ($reportCardStatus === 'approved' ? 'auto-approved' : 'flagged for review');
                
                return [
                    'httpcode'=> 201,
                    'success'=> true,
                    'message'=> 'Enrollment form submitted successfully. Report card ' . $statusMessage,
                    'data'=> [
                        'enrollee_id' => $enrolleeId,
                        'report_card_status' => $reportCardStatus,
                        'report_card_submission_id' => $reportCardResult['data']['submission_id'] ?? null
                    ]
                ];
            }
            else {
                return [
                    'httpcode'=>500,
                    'success'=> false,
                    'message'=> 'Enrollment form failed to submit',
                    'data'=> []
                ];
            }
        }
        catch(InvalidArgumentException $e) {
            return [
                'httpcode'=>500,
                'success'=> false,
                'message'=> $e->getMessage(),
                'data'=> []
            ];
        }
        catch(DatabaseException $e) {
            return [
                'httpcode'=>500,
                'success'=> false,
                'message'=> 'There was a problem on our side: ' .$e->getMessage(),
                'error_code'=> $e->getCode(),
                'error_message'=> $e->getPrevious()?->getMessage(),
                'data'=> []
            ];
        }
        catch(Exception $e) {
            return [
                'httpcode'=> 500,
                'success'=> false,
                'message'=> 'There was an unexpected problem: ' .$e->getMessage(),
                'data'=> []
            ];
        }
    }
    public function apiUpdateEnrolleeInfo(int $userId, ?array $formData) : array { //F 3.5.2
        try {
            $enrolleeId = !empty($formData['enrolleeId']) ? (int) $formData['enrolleeId'] : null;
            if(is_null($formData['enrolleeId'])) {
                return [
                    'httpcode'=> 400,
                    'success'=> false,
                    'message'=> 'Enrollee ID is invalid',
                    'data'=> []
                ];
            }
            if(empty($formData)) {
                return [
                    'httpcode'=> 500,
                    'success'=> false,
                    'message'=> 'The form recieved is empty',
                    'data'=> []
                ];
            }
            $allData = $this->associateFormData($formData);
            if(empty($allData)) {
                return [
                    'httpcode'=> 500,
                    'success'=> false,
                    'message'=> 'Failed to process form data',
                    'data'=> []
                ];
            }
            $requiredFields = ['first_name', 'last_name', 'lrn', 'birthdate', 'sex'];
            $missingFields = [];
            foreach ($requiredFields as $field) {
                if (is_null($allData[$field])) {
                    $missingFields[] = $field;
                }
            }
            if(!empty($missingFields)) {
                return [
                    'httpcode'=> 409,
                    'success'=> false,
                    'message'=> 'Required fields are misssing',
                    'data'=> []
                ];
            }
            $isMatchingLrn = $this->postFormModel->checkLRN($allData['lrn'],$enrolleeId);
            $psaImage = $formData['psa_image'] ?? null;
            $psaData = $this->updateImage($userId, $enrolleeId, $psaImage);
            if(!$psaData['success']) {
                return [
                    'httpcode'=> 500,
                    'success'=> false,
                    'message'=> $psaData['message']. 'is this directory modifiable: ' .$psaData['isWritable'],
                    'data'=> []
                ];
            }
            if($psaData['isUpload'] ?? true) {
                $allData['psa_image'] = [
                    'filename'=> $psaData['filename'],
                    'filepath'=> $psaData['filepath']
                ];   
            }
            $insertData = $this->enrolleesModel->updateEnrolleeInformation($enrolleeId, $allData);
            if(!$insertData) {
                return [
                    'httpcode'=> 500,
                    'success'=> false,
                    'message'=> 'Failed to execute enrollee update',
                    'data'=> []
                ];
            }
            $setTransactionStatus = $this->enrolleesModel->setResubmitStatus($enrolleeId);
            return [
                'httpcode'=> 201,
                'success'=> true,
                'message'=> 'Successfully updated enrollee information',
                'data'=> $insertData
            ];
        }
        catch(InvalidArgumentException $e) {
            return [
                'httpcode'=>400,
                'success'=> false,
                'message'=> $e->getMessage(),
                'data'=> []
            ];
        }
        catch(DatabaseException $e) {
            return [
                'httpcode'=>500,
                'success'=> false,
                'message'=> 'There was a problem on our side: ' .$e->getMessage(),
                'error_code'=> $e->getCode(),
                'error_message'=> $e->getPrevious()?->getMessage(),
                'data'=> []
            ];
        }
        catch(Exception $e) {
            return [
                'httpcode'=> 500,
                'success'=> false,
                'message'=> 'There was an unexpected problem: ' .$e->getMessage(),
                'data'=> []
            ];
        }
    }
    //HELPERS
    private function associateFormData(?array $postData) : array { //F 3.6.1
        try {
            $lrn = isset($postData['lrn']) ? (int) trim($postData['lrn']) : null;
            $formData = [
                'first_name' => $postData['first_name'] ?? null,
                'last_name' => $postData['last_name'] ?? null,
                'middle_name' => $postData['middle_name'] ?? null,
                'extension' => $postData['extension'] ?? null,
                'lrn' => $lrn,  // Add trim to remove any whitespace
                'age' => $postData['age'] ?? null,
                'birthdate' => $postData['birthdate'] ?? null,
                'sex' => $postData['sex'] ?? null,
                'religion' => $postData['religion'] ?? null,
                'native_language' => $postData['native_language'] ?? null,
                'belongs_in_cultural_group' => $postData['belongs_in_cultural_group'] ?? 0,
                'cultural_group' => $postData['cultural_group'] ?? null,
                'email_address' => $postData['email_address'] ?? null,
                'enrolling_grade_level' => $postData['enrolling_grade_level'] ?? null,
                'last_grade_level' => $postData['last_grade_level'] ?? null,
                'last_year_attended' => $postData['last_year_attended'] ?? null,
                'last_school_attended' => $postData['last_school_attended'] ?? null,
                'school_id' => $postData['school_id'] ?? null,
                'school_address' => $postData['school_address'] ?? null,
                'school_type' => $postData['school_type'] ?? null,
                'region' => $postData['region'] ?? null,
                'region_name' => $postData['region_name'] ?? null,
                'province' => $postData['province'] ?? null,
                'province_name' => $postData['province_name'] ?? null,
                'city-municipality' => $postData['city-municipality'] ?? null,
                'city_municipality_name' => $postData['city_municipality_name'] ?? null,
                'barangay' => $postData['barangay'] ?? null,
                'barangay_name' => $postData['barangay_name'] ?? null,
                'subdivision' => $postData['subdivision'] ?? null,
                'house_number' => $postData['house_number'] ?? null,
                'has_a_special_condition' => $postData['has_a_special_condition'] ?? 0,
                'special_condition' => $postData['special_condition'] ?? null,
                'has_assistive_technology' => $postData['has_assistive_technology'] ?? 0,
                'assistive_technology' => $postData['assistive_technology'] ?? null
            ];
            // Add parent information
            $formData['parent_information'] = [
                'Guardian' => [
                    'first_name' => $postData['guardian_first_name'] ?? null,
                    'middle_name' => $postData['guardian_middle_name'] ?? null,
                    'last_name' => $postData['guardian_last_name'] ?? null,
                    'educational_attainment' => $postData['guardian_educational_attainment'] ?? null,
                    'contact_number' => $postData['guardian_contact_number'] ?? null,
                    'if_4ps' => $postData['guardian_4ps_member'] ?? 0
                ]
            ];
            return $formData;
        }
        catch(Exception $e) {
            return [
                'success'=> false,
                'message'=> 'There was an unexpected problem'
            ];
        }
    }
    private function updateImage(int $userId, int $enrolleeId, ?string $filename) : array { //F 3.6.2
        try {
            if(empty($filename)) {
                return [
                    'success'=> true,
                    'message'=> 'No image sent; will not replace old one',
                    'filename'=> null,
                    'filepath'=> null,
                    'isUpload'=> false
                ];
            }
            $relPath = '../../../ImageUploads/'.date('Y').'/';
            $uploadDirectory = __DIR__ . '/'. $relPath;
            if(!is_dir($uploadDirectory)) {
                if(!mkdir($uploadDirectory,0777,true)) {
                    return [
                        'success'=> false,
                        'message'=> 'Failed to submit image. Image folder storage not found'
                    ];
                }
            }
            $base64 = preg_replace('#^data:image/\w+;base64,#i', '', $filename);
            $base64 = str_replace(' ', '+', $base64);
            $imageData = base64_decode($base64, true);
            $imageInfo = getimagesizefromstring($imageData);
            $allowedFileTypes = ['image/jpeg','image/png'];
            if(!$imageInfo) {
                return [
                    'success'=> false,
                    'message'=> 'Not an image'
                ];
            }
            if(!in_array( $imageInfo['mime'],$allowedFileTypes)) {
                return [
                    'success'=> false,
                    'message'=> 'File type not allowed. Must be jpg, jpeg, or png'
                ];
            }
            $extension = ($imageInfo['mime'] === 'image/png') ? 'png' :'jpg';
            $time = time();
            $imageRandomString = bin2hex(random_bytes(5));
            $uniqName = "{$userId}-{$time}-{$imageRandomString}.{$extension}"; //filename
            $filepath = $relPath . $uniqName;
            if(!file_put_contents($filepath, $imageData)) {
                return [
                    'success'=> false,
                    'message'=> 'Failed to send image to file'
                ];
            }
            $oldImage = $this->enrolleesModel->getPSAImageData($enrolleeId); //get old image data
            if(!empty($oldImage) && isset($oldImage['directory']) && file_exists($oldImage['directory'])) {
                if(!@unlink($oldImage['directory'])) {
                    error_log("Failed to delete filename: " .$oldImage['directory'] . "\n",3, __DIR__ . '/../../errorLogs.txt');
                }
            }
            return [
                'success'=> true,
                'message'=> 'Image file successfully saved',
                'filename'=> $uniqName,
                'filepath'=> $filepath,
                'isUpload'=> true
            ];
        }
        catch(DatabaseException $e) {
            return [
                'success'=> false,
                'message'=> 'There was a problem during image data fetch: ' .$e->getMessage(),
                'error_code'=> $e->getCode(),
                'error_message'=> $e->getPrevious()->getMessage()
            ];
        }
        catch(Exception $e) {
            return [
                'success'=> false,
                'message'=> 'There was an unexpected problem in updating image' 
            ];
        }
    }
    private function storeImage(?int $userId, ?array $fileName) : array { //F 3.6.3
        try {
            if(empty($fileName)) {
                return [
                    'success'=> false,
                    'message'=> 'PSA image file empty'
                ];
            }
            if(!isset($fileName['name']) || !isset($fileName['tmp_name']) || !isset($fileName['type'])) {
                return [
                    'success'=> false,
                    'message'=> 'file sent is not an image'
                ];
            }
            if (!isset($fileName['error']) || $fileName['error'] !== UPLOAD_ERR_OK) {
                return [
                    'success'=> false,
                    'message'=> 'Error during file upload'
                ];
            }
            //handle directory
            $relPath = '../../../ImageUploads/'.date('Y').'/'; // use relative path for database for now
            $uploadDirectory = __DIR__ . '/'. $relPath;
            if(!is_dir($uploadDirectory)) {
                if(!mkdir($uploadDirectory,0777,true)) {
                    return [
                        'success'=> false,
                        'message'=> 'Failed to submit image. Image folder storage not found'
                    ];
                }
            }     
            //extract file name
            $image = $fileName['name'];
            $imageTmpName = $fileName['tmp_name'];
            //check valid file types
            $extractImageExtension = explode('.',$image);
            $imageExtension = strtolower(end($extractImageExtension));
            $allowedFileTypes = ['jpg','jpeg','png'];
            if(!in_array($imageExtension,$allowedFileTypes)) {
                return [
                    'success'=> false,
                    'message'=> 'File type not allowed. Must be jpg, jpeg, or png'
                ];
            }
            $imageTime = time();
            $imageRandString = bin2hex(random_bytes(5));
            // Handle null userId for admin enrollment
            $userIdentifier = $userId ?? 'admin_' . time();
            $imageCustomFileName = $userIdentifier .'-'. $imageTime.'-'.$imageRandString;
            //db stored values
            $imageFileName = $imageCustomFileName .'.'. $imageExtension;
            $imageFilePath = $relPath . $imageFileName;
            if(!move_uploaded_file($imageTmpName, $imageFilePath)) {
                return [
                    'success'=> false,
                    'message'=> 'Failed to store the image to directory'
                ];
            }
            return [
                'success'=> true,
                'message'=> 'Image stored successfully',
                'filename'=> $imageFileName,
                'filepath'=> $imageFilePath
            ];
        }
        catch(Exception $e) {
            return [
                'success'=> false,
                'message'=> 'There was an unexpected problem in storing the image'
            ];
        }
    }
    //VIEW
}