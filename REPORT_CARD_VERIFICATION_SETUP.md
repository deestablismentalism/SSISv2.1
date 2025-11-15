# Report Card Verification System - Setup Instructions

## Overview
This system automatically verifies report card uploads using OCR (Tesseract) and flags suspicious submissions for manual review.

## Prerequisites

### 1. Install Tesseract OCR

**Windows:**
- Download and install from: https://github.com/UB-Mannheim/tesseract/wiki
- Add Tesseract to PATH or note the installation path (default: `C:\Program Files\Tesseract-OCR\tesseract.exe`)
- If not in PATH, you may need to set `TESSDATA_PREFIX` environment variable

**Linux (Ubuntu/Debian):**
```bash
sudo apt-get update
sudo apt-get install tesseract-ocr
```

**macOS:**
```bash
brew install tesseract
```

### 2. Install Python Dependencies

```bash
pip install pytesseract pillow
```

### 3. Database Setup

Run the SQL file to create the required table:

```sql
-- Execute report_card_submissions_table.sql
mysql -u your_username -p your_database < report_card_submissions_table.sql
```

Or import it through phpMyAdmin/your database management tool.

## File Structure

```
SSISv2.1/
├── scripts/
│   └── validate_card.py          # Python OCR script
├── BackEnd/
│   ├── admin/
│   │   ├── models/
│   │   │   └── reportCardModel.php
│   │   ├── controllers/
│   │   │   ├── reportCardController.php
│   │   │   └── reportCardReviewController.php
│   │   └── views/
│   │       └── reportCardReviewView.php
│   └── api/
│       └── admin/
│           ├── postReportCardUpload.php
│           ├── getReportCardSubmissions.php
│           ├── getReportCardSubmission.php
│           └── postUpdateReportCardStatus.php
├── FrontEnd/
│   ├── pages/
│   │   └── admin/
│   │       └── admin_report_card_review.php
│   └── assets/
│       ├── css/
│       │   └── admin/
│       │       └── admin-report-card-review.css
│       └── js/
│           └── admin/
│               └── admin-report-card-review.js
└── ImageUploads/
    └── report_cards/              # Auto-created directory
        └── YYYY/                  # Year-based subdirectories
```

## Configuration

### Python Script Path
The PHP controller calls the Python script at:
```
__DIR__ . '/../../../scripts/validate_card.py'
```

### Tesseract Path (if not in PATH)
If Tesseract is not in your system PATH, modify `validate_card.py`:

```python
import pytesseract
pytesseract.pytesseract.tesseract_cmd = r'C:\Program Files\Tesseract-OCR\tesseract.exe'  # Windows
# or
pytesseract.pytesseract.tesseract_cmd = '/usr/bin/tesseract'  # Linux
```

## Usage

### 1. Upload Report Card via API

**Endpoint:** `POST /BackEnd/api/admin/postReportCardUpload.php`

**Parameters:**
- `student_name` (required): Student's full name
- `student_lrn` (required): 12-digit Learner Reference Number
- `report_card` (required): Image file (jpg, jpeg, png)
- `enrollee_id` (optional): Related enrollee ID

**Example (JavaScript):**
```javascript
const formData = new FormData();
formData.append('student_name', 'Juan Dela Cruz');
formData.append('student_lrn', '123456789012');
formData.append('report_card', fileInput.files[0]);

const response = await fetch('/BackEnd/api/admin/postReportCardUpload.php', {
    method: 'POST',
    body: formData
});
```

### 2. Access Teacher Review Dashboard

Navigate to: `/FrontEnd/pages/admin/admin_report_card_review.php`

The dashboard shows:
- All report card submissions
- Status (Approved, Flagged, Pending, Reupload Needed)
- OCR results
- Actions: Approve, Reject, Request Re-upload

## Auto-Approval Logic

A submission is **auto-approved** if ALL of the following are true:
- OCR finds an LRN
- OCR LRN matches submitted LRN
- At least 5 grade-like numbers (75-100) detected
- Word count ≥ 50
- No critical flags (no_lrn, no_grades, low_text, file_not_found, processing_error, ocr_error)

Otherwise, it is **flagged for review**.

## OCR Output Format

The Python script returns JSON:

```json
{
    "lrn": "123456789012",
    "grades_found": 8,
    "word_count": 120,
    "flags": []
}
```

Or if suspicious:

```json
{
    "lrn": null,
    "grades_found": 2,
    "word_count": 15,
    "flags": ["no_lrn", "no_grades", "low_text"]
}
```

## Troubleshooting

### Python script not found
- Check file path in `reportCardController.php` (line ~80)
- Ensure script has execute permissions: `chmod +x scripts/validate_card.py`

### Tesseract not found
- Verify Tesseract installation: `tesseract --version`
- Check PATH or set `pytesseract.pytesseract.tesseract_cmd` in Python script

### OCR returns empty results
- Ensure image is clear and readable
- Check image format (jpg, jpeg, png)
- Verify Tesseract language data is installed

### Permission errors
- Ensure `ImageUploads/report_cards/` directory is writable
- Check PHP upload_max_filesize and post_max_size in php.ini

## Testing

1. Upload a test report card image
2. Check database `report_card_submissions` table
3. Verify OCR JSON in `ocr_json` column
4. Test auto-approval with valid LRN and grades
5. Test flagging with invalid/mismatched data

## Notes

- This is an MVP implementation focused on core functionality
- OCR accuracy depends on image quality
- Manual review is recommended for all flagged submissions
- The system does not perform advanced document analysis or rotation correction

