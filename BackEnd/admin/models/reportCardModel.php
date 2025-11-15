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
    
    public function insertSubmission(string $studentName, string $studentLrn, string $reportCardFrontPath, ?string $reportCardBackPath, ?string $ocrJson, string $status, ?int $enrolleeId = null, ?string $flagReason = null): int {
        try {
            $sql = "INSERT INTO report_card_submissions (student_name, student_lrn, report_card_front_path, report_card_back_path, ocr_json, status, flag_reason, enrollee_id) 
                    VALUES (:student_name, :student_lrn, :report_card_front_path, :report_card_back_path, :ocr_json, :status, :flag_reason, :enrollee_id)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':student_name', $studentName);
            $stmt->bindParam(':student_lrn', $studentLrn);
            $stmt->bindParam(':report_card_front_path', $reportCardFrontPath);
            $stmt->bindParam(':report_card_back_path', $reportCardBackPath);
            $stmt->bindParam(':ocr_json', $ocrJson);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':flag_reason', $flagReason);
            $stmt->bindParam(':enrollee_id', $enrolleeId, $enrolleeId === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
            
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
            $sql = "SELECT * FROM report_card_submissions WHERE status = 'flagged_for_review' OR status = 'pending_review' ORDER BY created_at DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e) {
            error_log("[".date('Y-m-d H:i:s')."]" . $e->getMessage() . "\n", 3, __DIR__ . '/../../../errorLogs.txt');
            throw new DatabaseException('Failed to fetch flagged submissions', 0, $e);
        }
    }
}

