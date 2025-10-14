<?php
declare(strict_types =1);

require_once __DIR__ . '/../core/dbconnection.php';
require_once __DIR__ . '/./models/userEnrolleesModel.php';

class displayEnrollmentStatus {
    protected $conn;
    protected $enrollee;
    public const ENROLLED = 1;
    public const DENIED = 2;
    public const PENDING = 3;
    public const FOR_FOLLOW_UP = 4;

    public function __construct() {
        $db = new Connect();
        $this->conn = $db->getConnection();
        $this->enrollee = new userEnrolleesModel();
    }

    public function stringEquivalent(int $status): string {
        return match ($status) {
            3 => 'pending',
            1 => 'enrolled',
            2 => 'denied',
            4 => 'follow-up',
            default => 'unknown',
        };
    }
public function displayStatus() {
    if (isset($_SESSION['User']['User-Id']) && isset($_GET['id'])) {
        $userId = $_SESSION['User']['User-Id'];
        $enrolleeId = $_GET['id'];

        $statusCode = $this->enrollee->getUserStatus($userId, $enrolleeId);

        if ($statusCode === null) {
            echo "<p>Status not found.</p>";
            return;
        }
        else if ($statusCode === 1) { //display status for enrolled
            $status = $this->stringEquivalent($statusCode);
            echo "<p class=status>" .strtoupper($status) . "</p>";
            echo "<p> SUCCESSFULLY ENROLLED! </p>";
        }
        else if ($statusCode === 2) { //display status for denied
            $status = $this->stringEquivalent($statusCode);
            echo "<p class=status>" .strtoupper($status) . "</p>";
            $transactions = $this->enrollee->sendTransactionStatus($enrolleeId);
                echo '<div>';
                echo '<p class="transaction-code"><strong>Transaction Code:</strong> ' . htmlspecialchars($transactions['Transaction_Code']) . '</p>';
                echo '<p class="transaction-description"><strong>Description:</strong> ' . htmlspecialchars($transactions['Remarks']) . '</p>';
                echo '</div>';
            echo "<p> Your enrollment form is DENIED. Please contact the school for more information. </p>";
        }
        else if ($statusCode === 3) { //display status for pending
            $status = $this->stringEquivalent($statusCode);
            echo "<p class=status>" .strtoupper($status) . "</p>";
            echo "<p> Your enrollment form is currently being processed. Please wait for 3-4 working days <p>";

        }
        else  { // display for follow up
            $transactions = $this->enrollee->sendTransactionStatus($enrolleeId);
            $status = $this->stringEquivalent($statusCode);
            echo "<p class=status>" .strtoupper($status) . "</p>";
        
                echo '<div class="reasons-container">';
                echo '<p  class="transaction-code"><strong>Transaction Code:</strong> ' . htmlspecialchars($transactions['Transaction_Code']) . '</p>';
                echo '<p><strong>Description:</strong> ' . htmlspecialchars($transactions['Remarks']) . '</p>';
                echo '</div>';
            if (isset($enrolleeId) && $transactions['Can_Resubmit'] == 1) { // allow editing of enrollment for if allowed by admin
                echo "<button class='edit-enrollment-form' data-id=" . $enrolleeId . "> Edit Enrollment Form</button>";
            }
            else if(isset($enrolleeId) && $transactions['Need_Consultation'] == 1) {
                echo "<p> Your enrollment form is in need of further discussion. Please wait for the school to contact you. </p>";
            }
            else {
                echo "";
            }
        }
    }    
}
}

