<?php
declare(strict_types=1);
require_once __DIR__ . '/../../core/dbconnection.php';
require_once __DIR__ . '/../../Exceptions/DatabaseException.php';

class reportCardModel {
    protected $conn;
    
    public function __construct() {
        $db = new Connect();
        $this->conn = $db->getConnection();
    }
    
    public function insertSubmission(string $studentName, string $studentLrn, string $reportCardFrontPath, ?string $reportCardBackPath, ?string $ocrJson, string $status, ?int $enrolleeId = null, ?string $flagReason = null, ?int $userId = null, ?string $sessionId = null, int $validationOnly = 0, ?string $formDataJson = null): int {
        try {
            $sql = "INSERT INTO report_card_submissions (student_name, student_lrn, user_id, session_id, report_card_front_path, report_card_back_path, ocr_json, form_data_json, status, flag_reason, enrollee_id, validation_only) 
                    VALUES (:student_name, :student_lrn, :user_id, :session_id, :report_card_front_path, :report_card_back_path, :ocr_json, :form_data_json, :status, :flag_reason, :enrollee_id, :validation_only)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':student_name', $studentName);
            $stmt->bindParam(':student_lrn', $studentLrn);
            $stmt->bindParam(':user_id', $userId, $userId === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
            $stmt->bindParam(':session_id', $sessionId);
            $stmt->bindParam(':report_card_front_path', $reportCardFrontPath);
            $stmt->bindParam(':report_card_back_path', $reportCardBackPath);
            $stmt->bindParam(':ocr_json', $ocrJson);
            $stmt->bindParam(':form_data_json', $formDataJson);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':flag_reason', $flagReason);
            $stmt->bindParam(':enrollee_id', $enrolleeId, $enrolleeId === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
            $stmt->bindParam(':validation_only', $validationOnly, PDO::PARAM_INT);
            
            if (!$stmt->execute()) {
                throw new PDOException('Failed to insert report card submission');
            }
            
            return (int)$this->conn->lastInsertId();
        }
        catch (PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]" . $e->getMessage() . "\n", 3, __DIR__ . '/../../../errorLogs.txt');
            throw new DatabaseException('Failed to insert report card submission', 0, $e);
        }
    }
    
    public function updateSubmissionStatus(int $id, string $status): bool {
        try {
            $sql = "UPDATE report_card_submissions SET status = :status WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':id', $id);
            
            if (!$stmt->execute()) {
                throw new PDOException('Failed to update submission status');
            }
            
            return $stmt->rowCount() > 0;
        }
        catch (PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]" . $e->getMessage() . "\n", 3, __DIR__ . '/../../../errorLogs.txt');
            throw new DatabaseException('Failed to update submission status', 0, $e);
        }
    }
    
    public function updateFlagReason(int $id, ?string $flagReason): bool {
        try {
            $sql = "UPDATE report_card_submissions SET flag_reason = :flag_reason WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':flag_reason', $flagReason);
            $stmt->bindParam(':id', $id);
            
            if (!$stmt->execute()) {
                throw new PDOException('Failed to update flag reason');
            }
            
            return $stmt->rowCount() > 0;
        }
        catch (PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]" . $e->getMessage() . "\n", 3, __DIR__ . '/../../../errorLogs.txt');
            throw new DatabaseException('Failed to update flag reason', 0, $e);
        }
    }
    
    public function getSubmissionById(int $id): ?array {
        try {
            $sql = "SELECT * FROM report_card_submissions WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        }
        catch (PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]" . $e->getMessage() . "\n", 3, __DIR__ . '/../../../errorLogs.txt');
            throw new DatabaseException('Failed to fetch submission', 0, $e);
        }
    }
    
    public function getAllSubmissions(?string $status = null): array {
        try {
            $sql = "SELECT * FROM report_card_submissions";
            if ($status !== null) {
                $sql .= " WHERE status = :status";
            }
            $sql .= " ORDER BY created_at DESC";
            
            $stmt = $this->conn->prepare($sql);
            if ($status !== null) {
                $stmt->bindParam(':status', $status);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]" . $e->getMessage() . "\n", 3, __DIR__ . '/../../../errorLogs.txt');
            throw new DatabaseException('Failed to fetch submissions', 0, $e);
        }
    }
    
    public function getFlaggedSubmissions(): array {
        try {
            $sql = "SELECT * FROM report_card_submissions WHERE (status = 'flagged_for_review' OR status = 'pending_review') AND validation_only = 0 ORDER BY created_at DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]" . $e->getMessage() . "\n", 3, __DIR__ . '/../../../errorLogs.txt');
            throw new DatabaseException('Failed to fetch flagged submissions', 0, $e);
        }
    }
    
    public function getSubmissionBySessionId(string $sessionId): ?array {
        try {
            $sql = "SELECT * FROM report_card_submissions WHERE session_id = :session_id ORDER BY created_at DESC LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':session_id', $sessionId);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        }
        catch (PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]" . $e->getMessage() . "\n", 3, __DIR__ . '/../../../errorLogs.txt');
            throw new DatabaseException('Failed to fetch submission by session', 0, $e);
        }
    }
    
    public function getSubmissionByEnrolleeId(int $enrolleeId): ?array {
        try {
            $sql = "SELECT * FROM report_card_submissions WHERE enrollee_id = :enrollee_id AND validation_only = 0 ORDER BY created_at DESC LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':enrollee_id', $enrolleeId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        }
        catch (PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]" . $e->getMessage() . "\n", 3, __DIR__ . '/../../../errorLogs.txt');
            throw new DatabaseException('Failed to fetch submission by enrollee', 0, $e);
        }
    }
    
    public function deleteValidationSubmissions(string $sessionId): bool {
        try {
            $sql = "DELETE FROM report_card_submissions WHERE session_id = :session_id AND validation_only = 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':session_id', $sessionId);
            return $stmt->execute();
        }
        catch (PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]" . $e->getMessage() . "\n", 3, __DIR__ . '/../../../errorLogs.txt');
            throw new DatabaseException('Failed to delete validation submissions', 0, $e);
        }
    }
}


