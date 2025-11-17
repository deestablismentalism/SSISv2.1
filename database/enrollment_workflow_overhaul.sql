-- =====================================================
-- Enrollment Workflow Overhaul Migration
-- Date: 2025-11-18
-- Purpose: Remove admin bottleneck, allow teachers to directly enroll students
-- =====================================================

-- Update Is_Approved column comment to reflect new semantics
ALTER TABLE enrollment_transactions 
MODIFY COLUMN Is_Approved TINYINT(1) DEFAULT 0 
COMMENT '0=Pending/Open Transaction, 1=Finalized by Staff';

-- Update existing records: Set Is_Approved=1 for all completed enrollments
UPDATE enrollment_transactions 
SET Is_Approved = 1 
WHERE Enrollment_Status = 1 AND Is_Approved = 0;

-- Update existing records: Set Is_Approved=1 for all completed denials
UPDATE enrollment_transactions 
SET Is_Approved = 1 
WHERE Enrollment_Status = 2 AND Is_Approved = 0;

-- Follow-up transactions remain with Is_Approved=0 (requires action)

-- =====================================================
-- New Workflow Documentation
-- =====================================================
-- OLD FLOW: 
--   User Submits → Teacher Reviews → Admin Approves → Student Table
--
-- NEW FLOW:
--   User Submits → Teacher Reviews → Student Table (Direct)
--
-- Enrollment_Status meanings:
--   1 = Enrolled (finalized, in students table)
--   2 = Denied (finalized, rejected)
--   3 = Pending (awaiting teacher review)
--   4 = Follow-up (requires additional documents/info)
--
-- Is_Approved meanings:
--   0 = Transaction open/pending
--   1 = Transaction finalized by staff
-- =====================================================

-- Verify the migration
SELECT 
    Enrollment_Status,
    Is_Approved,
    COUNT(*) as transaction_count
FROM enrollment_transactions
GROUP BY Enrollment_Status, Is_Approved
ORDER BY Enrollment_Status, Is_Approved;
