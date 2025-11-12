<?php
declare(strict_types=1);
require_once __DIR__ . '/../controllers/adminUnprocessedEnrollmentsController.php';
require_once __DIR__ . '/../../core/tableDataTemplate.php';
require_once __DIR__ . '/../../core/safeHTML.php';

class adminUnhandledEnrollmentsView {
    protected $tableTemplate;
    protected $transactionsController;
    // ENROLLMENT STATUSES
    private const ENROLLED = 1;
    private const DENIED = 2;
    private const FOLLOWUP = 4;
    // TRANSACTION STATUSES
    private const UNPROCESSED = 0;
    private const FOR_RESUBMISSION = 1;
    private const FOR_CONSULTATION = 2;

    public function __construct() {
        $this->tableTemplate = new tableCreator();
        $this->transactionsController = new adminUnprocessedEnrollmentsController();
    }
    //Returns a readable transaction value (used in default text cases)
    private function transactionValue(int $value): string {
        $statuses = [
            self::UNPROCESSED => 'Unprocessed',
            self::FOR_RESUBMISSION => 'For resubmission',
            self::FOR_CONSULTATION => 'For consultation',
        ];
        return $statuses[$value] ?? 'Unknown status';
    }
    //Returns string version of enrollment status (used for upper labels)
    private function stringEquivalent(int $value): string {
        return match($value) {
            self::ENROLLED => 'enrolled',
            self::DENIED => 'denied',
            self::FOLLOWUP => 'to-follow',
            default => 'Unknown',
        };
    }
    //Generates a dynamic action button based on transaction status
    private function generateTransactionButton(int $status, int $enrolleeId, ?string $contactNumber = null): safeHTML|string {
        switch ($status) {
            case self::FOR_RESUBMISSION:
                return new safeHTML(
                    '<button id="'.$enrolleeId.'" data-enrollee="'.$enrolleeId.'" class="view-resubmission">View Resubmission</button>'
                );
            case self::FOR_CONSULTATION:
                $showCp = ($status === 2 && !empty($contactNumber)) ? 'Number: '.htmlspecialchars($contactNumber) : '';
                return new safeHTML(
                    '<button id="'.$enrolleeId.'" data-enrollee="'.$enrolleeId.'" class="start-consultation">Consultation: '.$showCp.'</button>'
                );
            default:
                return strtoupper($this->transactionValue($status));
        }
    }
    //Shared rendering logic for any transaction table
    private function renderTransactionTable(array $data, string $tableClass): void {
        echo '<table class="enrollments">';
        echo $this->tableTemplate->returnHorizontalTitles(
            ['LRN', 'Enrollee Name', 'Handled By', 'Transaction Code', 'Enrollment Status Given', 'Handled At', 'Transaction Status', 'Remarks'],
            $tableClass . '-titles'
        );
        foreach ($data as $row) {
            $transactionNum = (int)$row['Transaction_Status'];
            $lrn = !empty($row['Learner_Reference_Number']) ? $row['Learner_Reference_Number'] : 'No LRN';
            $status = strtoupper($this->stringEquivalent((int)$row['Enrollment_Status']));
            // Student full name
            $studentMiddleInitial = !empty($row['Student_Middle_Name'])
                ? substr(htmlspecialchars($row['Student_Middle_Name']), 0, 1) . '.'
                : '';
            $fullName = $row['Student_Last_Name'] . ', ' .
                        $row['Student_First_Name'] . ' ' .
                        $studentMiddleInitial;
            // Staff name
            $staffMiddleInitial = !empty($row['Staff_Middle_Name'])
                ? substr($row['Staff_Middle_Name'], 0, 1) . '.'
                : '';
            $staffName = htmlspecialchars($row['Staff_Last_Name']) . ', ' .
                         htmlspecialchars($row['Staff_First_Name']) . ' ' .
                         $staffMiddleInitial;
            // Dynamic transaction status button/text
            $transactionStatus = $this->generateTransactionButton(
                $transactionNum,
                (int)$row['Enrollee_Id'],
                $row['Contact_Number'] ?? null
            );
            // Remarks button
            $button = new safeHTML(
                '<button id="'.$row['Enrollee_Id'].'" data-enrollee="'.$row['Enrollee_Id'].'" class="view-reason">View Remarks</button>'
            );
            echo $this->tableTemplate->returnHorizontalRows(
                [
                    $lrn,
                    $fullName,
                    $staffName,
                    $row['Transaction_Code'],
                    $status,
                    $row['Date'],
                    $transactionStatus,
                    $button
                ],
                $tableClass . '-row'
            );
        }
        echo '</tbody></table>';
    }
    public function displayEnrolledTransactions(): void {
        try {
            $data = $this->transactionsController->viewMarkedEnrolledTransactions();
            if (!$data['success']) {
                echo '<div class="error-message"><span>' . htmlspecialchars($data['message']) . '</span></div>';
            } else {
                $this->renderTransactionTable($data['data'], 'enrolled-transaction');
            }
        } catch (Throwable $t) {
            echo '<div class="error-message">There was a syntax error</div>';
        }
    }
    public function displayFollowUpTransactions(): void {
        try {
            $data = $this->transactionsController->viewMarkedFollowedUpTransactions();
            if (!$data['success']) {
                echo '<div class="error-message"><span>' . htmlspecialchars($data['message']) . '</span></div>';
            } else {
                $this->renderTransactionTable($data['data'], 'followedup-transaction');
            }
        } catch (Throwable $t) {
            echo '<div class="error-message">There was a syntax error</div>';
        }
    }
    public function displayDeniedTransactions(): void {
        try {
            $data = $this->transactionsController->viewMarkedDeniedTransactions();
            if (!$data['success']) {
                echo '<div class="error-message"><span>' . htmlspecialchars($data['message']) . '</span></div>';
            } else {
                $this->renderTransactionTable($data['data'], 'denied-transaction');
            }
        } catch (Throwable $t) {
            echo '<div class="error-message">There was a syntax error</div>';
        }
    }
}
