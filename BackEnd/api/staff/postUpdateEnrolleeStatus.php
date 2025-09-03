<?php

declare(strict_types=1);
session_start();
require_once __DIR__ . '/../../staff/models/staffEnrollmentTransactionsModel.php';
require_once __DIR__ . '/../../admin/models/adminEnrolleesModel.php';
require_once __DIR__ . '/../../admin/models/adminStudentsModel.php';
require_once __DIR__ . '/../../staff/models/staffEnrolleesModel.php';
header("Content-Type: application/json");

    if($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message'=> 'Invalid request method']);
        exit();
    }
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

    if(!isset($_SESSION['Staff']) || !in_array($_SESSION['Staff']['Staff-Type'], [1,2])) {
        echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
        exit();
    }

        $staffId = $_SESSION['Staff']['Staff-Id'];
        $staffType = $_SESSION['Staff']['Staff-Type'];
        if(!isset($enrolleeId) || !in_array($status, $validStatuses)) {
            echo json_encode(['success'=> false, 'message'=> 'Invalid Id!']);
            exit();
        }
        if($staffType == 1) {
            $update = $enrolleesModel->updateEnrollee($enrolleeId, $status);
            //execute only if update successful
            if(!$update) {
                echo json_encode(['success'=> false, 'message'=> 'Update failed']);
                exit();
            }
            // Only attempt to insert into students table if status is 1 (Enrolled)
            if ($status == 1) {
                $isApproved = 1;

                $insertToEnrolleeTransactions = $transactionsModel->insertEnrolleeTransaction($enrolleeId,$transactionCode, $status, $staffId, $remarks, $isApproved);
                if(!$insertToEnrolleeTransactions) {
                    echo json_encode(['success' => false, 'message'=> 'Transaction insertion failed']);
                    exit();
                }
                $insert = $students->insertEnrolleeToStudent($enrolleeId);
                if(!$insert) {
                    echo json_encode(['success'=> false, 'message'=> 'There was a problem with inserting student information']);
                    exit();
                }
                $isHandledSuccess = $staffEnrolleeModel->setIsHandledStatus($enrolleeId, $isHandled);
                if(!$isHandledSuccess) {
                    echo json_encode(['success'=> false, 'message'=> 'Handling did not update']);
                    exit();
                }
                echo json_encode($insert);
                exit();
            }
            else if ($status == 4 || $status == 3){
                $insertToEnrolleeTransactions = $transactionsModel->insertEnrolleeTransaction($enrolleeId,$transactionCode, $status, $staffId, $remarks, $isApproved);

                if(!$insertToEnrolleeTransactions) {
                    echo json_encode(['success'=> false, 'message' => 'Transaction insertion failed']);
                    exit();
                }
                $isHandledSuccess = $staffEnrolleeModel->setIsHandledStatus($enrolleeId, $isHandled);
                if(!$isHandledSuccess) {
                    echo json_encode(['success'=> false, 'message'=> 'Handling did not update']);
                    exit();
                }
                echo json_encode($insertToEnrolleeTransactions);
                exit();
            }  
        }
    //only insert the transaction if not an admin
    //no updates allowed for non admin
    else if($staffType== 2) {
        if(empty($enrolleeId) || !in_array($status, $validStatuses)) {
            echo json_encode(['success'=> false, 'message'=> 'Invalid ID!']);
            exit();
        }
        $insert = $transactionsModel->insertEnrolleeTransaction($enrolleeId,$transactionCode, $status, $staffId, $remarks, $isApproved); //isApproved will be false if handled by teacher
        if(!$insert) {
            echo json_encode(['success' => false, 'message' => 'Insert failed']);
            exit();
        }
        $isHandledSuccess = $staffEnrolleeModel->setIsHandledStatus($enrolleeId, $isHandled);
        if(!$isHandledSuccess) {
            echo json_encode(['success'=> false, 'message'=> 'Handling did not update']);
            exit();
        }
        echo json_encode($insert);
        exit();
    }
    else {
        echo json_encode(['success'=> false, 'message'=> 'Unknown Staff type']);
        exit();
    }
}
catch(Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    exit();
}
