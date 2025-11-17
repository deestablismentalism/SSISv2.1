# Report Card Pre-Validation Implementation

## Overview
Modified enrollment flow to validate report card images BEFORE creating enrollee records. Users can now resubmit rejected images without creating duplicate enrollees.

## Database Changes

### File: `add_report_card_columns.sql`
Added columns to `report_card_submissions`:
- `user_id` - Track who submitted (for cleanup/tracking)
- `session_id` - Track temporary submissions during validation
- `form_data_json` - Store form data temporarily (future use)
- `validation_only` - Flag to distinguish pre-validation from final submissions

**Action Required:** Run this migration on your database.

## Backend Changes

### 1. Models - `reportCardModel.php`
**Modified:**
- `insertSubmission()` - Added parameters: `userId`, `sessionId`, `validationOnly`, `formDataJson`
- `getFlaggedSubmissions()` - Filter out `validation_only=1` records from admin view

**New Methods:**
- `getSubmissionBySessionId()` - Retrieve validation result by session
- `deleteValidationSubmissions()` - Clean up temporary validation records

### 2. Controllers - `reportCardController.php`
**Modified:**
- `processReportCardUpload()` - Added parameters: `sessionId`, `validationOnly`
  - Supports both validation-only and final submission modes
  - Properly tags submissions based on mode

### 3. Controllers - `userEnrollmentFormController.php`
**Modified:**
- `apiPostAddEnrollee()` - Now cleans up validation submissions before final submission

### 4. New API Endpoint - `BackEnd/api/user/validateReportCard.php`
**Purpose:** Pre-validate report card images before enrollment creation
**Parameters:**
- `student_name` - Student full name
- `student_lrn` - LRN number
- `report-card-front` - Front image file
- `report-card-back` - Back image file

**Response:**
```json
{
  "success": true/false,
  "message": "...",
  "data": {
    "submission_id": 123,
    "status": "approved|flagged_for_review|rejected",
    "flag_reason": "...",
    "ocr_result": {...}
  }
}
```

### 5. Modified API - `postEnrollmentFormData.php`
**Added:** Check for rejected submissions before enrollment creation
- Reads session validation result
- Blocks submission if status is 'rejected'
- Returns error message prompting resubmission

## Frontend Changes

### 1. Main Form JS - `user-enrollment-form.js`
**Added:**
- `reportCardValidationStatus` - Global validation state
- `isValidatingReportCard` - Prevent duplicate validations
- `validateReportCard()` - Async function to validate images via API
- `handleReportCardChange()` - Reset validation when files change

**Modified:**
- Form submission handler now:
  1. Validates all fields
  2. **Validates report card images** ← NEW STEP
  3. If rejected → Show error, allow resubmit
  4. If approved/flagged → Proceed with enrollment
  5. Submit full form data

### 2. New Module - `reportCardValidator.js`
Standalone validation module (currently not imported, but available for future use)

## User Flow

### Before (Problem):
1. User fills form + submits images
2. Enrollee created in database
3. Images processed → Rejected
4. User stuck with rejected enrollee record

### After (Solution):
1. User fills form + selects images
2. User clicks Submit
3. **Images validated first**
4. **If rejected:**
   - Show error message with reason
   - Allow user to re-select different images
   - Submit button enabled for retry
   - No enrollee record created yet
5. **If approved/flagged:**
   - Proceed with full enrollment
   - Create enrollee record with status=3 (pending)
   - Link report card submission to enrollee

## Status Flow

### Report Card Statuses:
- `approved` - Auto-approved (LRN match + sufficient grades + text)
- `flagged_for_review` - Needs manual review but acceptable
- `rejected` - Not acceptable, requires resubmission
- `pending_review` - Initial state
- `reupload_needed` - Admin requests reupload

### Validation Logic:
**REJECTED if:**
- Both images identical
- Both images have no readable text
- Both images missing keywords AND no grades
- Images don't appear to be report cards

**FLAGGED if:**
- Insufficient grades (< 5)
- Low text content (< 50 words combined)
- Missing keywords
- One image has issues but other is valid

**APPROVED if:**
- LRN detected + >= 5 grades + >= 50 words

## Admin View Impact
- Only submissions with `validation_only=0` appear in admin panel
- Temporary validation submissions filtered out
- Cleaner submission list for review

## Testing Checklist

1. **Run database migration** (`add_report_card_columns.sql`)
2. **Test rejected submission:**
   - Upload blank images or same image twice
   - Verify error message appears
   - Verify can resubmit different images
   - Verify no enrollee created until success
3. **Test flagged submission:**
   - Upload valid but low-quality report card
   - Verify submission proceeds
   - Verify enrollee created with pending status
4. **Test approved submission:**
   - Upload clear report card with visible grades
   - Verify auto-approval
   - Verify enrollee created
5. **Verify admin panel:**
   - Check only final submissions appear
   - No validation-only records visible

## Cleanup Notes

Temporary validation submissions (validation_only=1) can be cleaned up:
- Manually via SQL: `DELETE FROM report_card_submissions WHERE validation_only=1 AND created_at < DATE_SUB(NOW(), INTERVAL 1 DAY)`
- Future: Add cron job for automatic cleanup

## Files Changed
1. `add_report_card_columns.sql` (NEW)
2. `BackEnd/admin/models/reportCardModel.php`
3. `BackEnd/admin/controllers/reportCardController.php`
4. `BackEnd/user/controllers/userEnrollmentFormController.php`
5. `BackEnd/api/user/validateReportCard.php` (NEW)
6. `BackEnd/api/user/postEnrollmentFormData.php`
7. `FrontEnd/assets/js/user/user-enrollment-form.js`
8. `FrontEnd/assets/js/user/reportCardValidator.js` (NEW - not imported yet)
