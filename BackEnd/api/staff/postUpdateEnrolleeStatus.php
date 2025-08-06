<?php

declare(strict_types=1);
session_start();
require_once __DIR__ . '/../../staff/models/staffEnrollmentTransactionsModel.php';
require_once __DIR__ . '/../../admin/models/adminEnrolleesModel.php';
require_once __DIR__ . '/../../admin/models/adminStudentsModel.php';
require_once __DIR__ . '/../../staff/models/staffEnrolleesModel.php';
header("Content-Type: application/json");
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    //TODO: update this file to also handle the to follow and denied status
    try {

    $transactionsModel = new staffEnrollmentTransactionsModel();
    $enrolleesModel = new adminEnrolleesModel();
    $students = new adminStudentsModel();
    $staffEnrolleeModel = new staffEnrolleesModel();

    $enrolleeId = $_POST['id'] ?? null;
    $status = (int)$_POST['status'] ?? null;
    $remarks = $_POST['remarks'] ?? null;
    $statusCode = "";
    $staffId = 0;
    $isApproved = 0;
    //boolean flag to set if handling is successful
    $isHandled = 1;
    
    if($status == 1) {
        $statusCode = "E"; // if status = 1, then enrolled
    }
    else if($status == 4) {
        $statusCode = "F"; // if 4, then to follow
    }
    else {
        $statusCode = "D"; // denied if not any of 1 or 4
    }
    $date = date('Ymd');
    $time = time();
    $transactionCode = $statusCode . "-" . $date . "-" . $time; //generate unique transaction code
    $validStatuses = [1, 2, 3, 4];

    if(isset($_SESSION['Staff']['Staff-Id']) && $_SESSION['Staff']['Staff-Type'] == 1) {
        $staffId = $_SESSION['Staff']['Staff-Id'];

        if(isset($enrolleeId) && in_array($status, $validStatuses)) {
            $update = $enrolleesModel->updateEnrollee($enrolleeId, $status);
            //execute only if update successful
            if($update) {
                 // Only attempt to insert into students table if status is 1 (Enrolled)
                if ($status == 1) {
                    $isApproved = 1;
                    $insertToEnrolleeTransactions = $transactionsModel->insertEnrolleeTransaction($enrolleeId,$transactionCode, $status, $staffId, $remarks, $isApproved);
                    if($insertToEnrolleeTransactions) {
                        $insert = $students->insertEnrolleeToStudent($enrolleeId);
                        if($insert) {
                            $isHandledSuccess = $staffEnrolleeModel->setIsHandledStatus($enrolleeId, $isHandled);
                            if($isHandledSuccess) {
                                echo json_encode($insert);
                                exit();
                            }
                        }
                        else {
                            echo json_encode(['success' => false, 'message'=> 'inserting enrollee failed']);
                            exit();
                        }
                    }
                }
                else if ($status == 4 || $status == 3){
                    $insertToEnrolleeTransactions = $transactionsModel->insertEnrolleeTransaction($enrolleeId,$transactionCode, $status, $staffId, $remarks, $isApproved);
    
                    if($insertToEnrolleeTransactions) {
                        $isHandledSuccess = $staffEnrolleeModel->setIsHandledStatus($enrolleeId, $isHandled);
                        if($isHandledSuccess) {
                            echo json_encode($insert);
                            exit();
                        }
                    }
                    else {
                        echo json_encode(['success'=> false, 'message' => 'insert failed']);
                        exit();
                    }
                } 
                else {
                    echo json_encode(['success'=> false, 'message'=> 'invalid status']);
                    exit();
                }
            }
            echo json_encode(['success' => true, 'message'=> "Update successful"]);
            exit();
        }
        else {
            echo json_encode(['success' => false, 'message' => 'Invalid input: enrolleeId or status is invalid']);
            exit();
        }
    }
    //only insert the transaction if not an admin
    //no updates allowed for non admin
    else if(isset($_SESSION['Staff']['Staff-Id']) && $_SESSION['Staff']['Staff-Type'] == 2) {
        $staffId = $_SESSION['Staff']['Staff-Id'];
        if(isset($enrolleeId) && in_array($status, $validStatuses)) {
            $insert = $transactionsModel->insertEnrolleeTransaction($enrolleeId,$transactionCode, $status, $staffId, $remarks, $isApproved); //isApproved will be false if handled by teacher
            if(!$insert) {
                echo json_encode(['success' => false, 'message' => 'Insert failed']);
                exit();
            }
            $isHandledSuccess = $staffEnrolleeModel->setIsHandledStatus($enrolleeId, $isHandled);
            if($isHandledSuccess) {
                echo json_encode($insert);
                exit();
            }
        }
        else {
            echo json_encode(['success' => false, 'message' => 'Invalid input: enrollee Id or status is invalid']);
            exit();
        }
    }
    else {
        //do not accept if accessed without any valid session id
        echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
        exit();
    }
    }
    catch(Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit();
    }
}
else {
    echo json_encode(['success' => false, 'message'=> 'Invalid request method']);
    exit();
}