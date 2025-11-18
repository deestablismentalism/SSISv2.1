# Quick Start Checklist

After installing dependencies and updating the database, verify these items:

## ✅ Pre-Flight Checks

### 1. Database
- [ ] Run `report_card_submissions_table.sql` successfully
- [ ] Verify table exists: `SHOW TABLES LIKE 'report_card_submissions';`

### 2. Python Environment
- [ ] Tesseract OCR installed and accessible: `tesseract --version`
- [ ] Python dependencies installed: `pip install pytesseract pillow`
- [ ] Test Python script manually:
  ```bash
  python scripts/validate_card.py path/to/test_image.jpg
  ```

### 3. File Permissions
- [ ] `ImageUploads/` directory exists and is writable
- [ ] PHP can create `ImageUploads/report_cards/YYYY/` directories

### 4. PHP Configuration
- [ ] `upload_max_filesize` in php.ini allows image uploads (recommend 10M+)
- [ ] `post_max_size` is sufficient
- [ ] `shell_exec()` is enabled (needed for Python script execution)

## 🧪 Quick Test

### Test Upload Endpoint
```javascript
// Test via browser console or Postman
const formData = new FormData();
formData.append('student_name', 'Test Student');
formData.append('student_lrn', '123456789012');
formData.append('report_card', fileInput.files[0]);

fetch('/BackEnd/api/admin/postReportCardUpload.php', {
    method: 'POST',
    body: formData
}).then(r => r.json()).then(console.log);
```

### Expected Response (Success)
```json
{
    "httpcode": 201,
    "success": true,
    "message": "Report card processed successfully",
    "data": {
        "submission_id": 1,
        "status": "approved" or "flagged_for_review",
        "ocr_result": {...}
    }
}
```

### Check Database
```sql
SELECT * FROM report_card_submissions ORDER BY id DESC LIMIT 1;
```

## ⚠️ Common Issues

### Python script not found
- **Symptom**: Error "Python script not found"
- **Fix**: Verify `scripts/validate_card.py` exists at project root
- **Check**: File path in `reportCardController.php` line 93

### Tesseract not found
- **Symptom**: OCR returns errors or empty results
- **Fix Windows**: Add Tesseract to PATH or edit `validate_card.py`:
  ```python
  pytesseract.pytesseract.tesseract_cmd = r'C:\Program Files\Tesseract-OCR\tesseract.exe'
  ```
- **Fix Linux/Mac**: Ensure Tesseract is in PATH: `which tesseract`

### Permission denied
- **Symptom**: "Failed to create upload directory"
- **Fix**: `chmod 755 ImageUploads` or ensure PHP user has write access

### shell_exec disabled
- **Symptom**: OCR script execution fails silently
- **Fix**: Enable in php.ini: `disable_functions =` (remove shell_exec)

## 🎯 If Everything Works

1. Upload a test report card image
2. Check database for new submission
3. View OCR results in `ocr_json` column
4. Access dashboard: `/FrontEnd/pages/admin/admin_report_card_review.php`
5. Test approve/reject/reupload actions

## 📝 Notes

- First upload may be slow (OCR processing)
- Auto-approval requires: LRN match + 5+ grades + 50+ words
- All flagged submissions go to review dashboard
- Images stored in: `ImageUploads/report_cards/YYYY/`

