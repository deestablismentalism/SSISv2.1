# Translation System - Quick Setup Guide

## Prerequisites
- XAMPP or similar PHP environment
- Composer installed
- MySQL database
- Google Cloud account (for API features)

## Quick Setup (5 minutes)

### 1. Install Dependencies
```bash
cd c:\xampp\htdocs\SSISv2.1
composer install
```

### 2. Set Up Database
```bash
mysql -u root -p your_database < database/translation_system.sql
```

Or via phpMyAdmin:
- Import `database/translation_system.sql`

### 3. Create .env File
Create `.env` in project root:
```env
GOOGLE_CLOUD_PROJECT_ID=your-project-id
GOOGLE_CLOUD_CREDENTIALS_PATH=./credentials/google-cloud-key.json
DEFAULT_LANGUAGE=tl
TRANSLATION_CACHE_ENABLED=true
```

### 4. Google Cloud Setup (Optional but Recommended)

#### Option A: Full API Setup (Best Quality)
1. Go to https://console.cloud.google.com/
2. Create/select project
3. Enable "Cloud Translation API"
4. Create service account
5. Download JSON key → save as `credentials/google-cloud-key.json`
6. Update Project ID in `.env`

#### Option B: Glossary-Only Mode (Free)
- Skip Google Cloud setup
- System will use built-in glossary (150+ terms)
- No API calls, no costs
- Works offline

### 5. Test Installation

Visit: `http://localhost/SSISv2.1/FrontEnd/Login.php`

You should see:
- Language switcher in top-right
- Tagalog interface by default
- Dropdown with 10 language options

## Adding Translation to Your Pages

### Step 1: Include Required Files

```html
<head>
    <link rel="stylesheet" href="../../assets/css/language-switcher.css">
    <script src="../../assets/js/translation.js"></script>
</head>
```

### Step 2: Add Language Switcher

```php
<?php
session_start();
include './pages/user/language-switcher.php';
?>
```

### Step 3: Mark Translatable Content

```html
<!-- Text -->
<h1 data-translate="Welcome">Welcome</h1>

<!-- Placeholder -->
<input type="text" 
       placeholder="Name" 
       data-translate-placeholder="Name">

<!-- Title/Tooltip -->
<button data-translate="Save" 
        data-translate-title="Save changes"
        title="Save changes">
    Save
</button>
```

## Available Languages

- Tagalog (tl) - Default
- English (en)
- Cebuano (ceb)
- Ilocano (ilo)
- Hiligaynon (hil)
- Waray (war)
- Kapampangan (pam)
- Pangasinan (pan)
- Bikol (bik)
- Spanish (es)

## Testing

### Test Translation Status
Visit: `/BackEnd/api/user/translationStatus.php`

Expected response:
```json
{
  "success": true,
  "status": {
    "configured": true,
    "api_ready": true,
    "cache_enabled": true,
    "glossary_terms_count": 150
  }
}
```

### Test Language Switch
1. Open Login page
2. Click language dropdown
3. Select "English"
4. Page content should translate instantly

## Troubleshooting

### Issue: "Translation not working"
**Solution:**
- Check browser console for errors
- Verify `translation.js` is loaded
- Ensure `data-translate` attributes are present

### Issue: "Language switcher not showing"
**Solution:**
- Session must be started: `session_start();`
- Include path must be correct
- CSS file must be loaded

### Issue: "Database error"
**Solution:**
```sql
-- Check if tables exist
SHOW TABLES LIKE 'translation_%';

-- Recreate if needed
SOURCE database/translation_system.sql;
```

### Issue: "Google API not working"
**Solution:**
- System falls back to glossary mode automatically
- Check credentials path in `.env`
- Verify API is enabled in Google Cloud Console

## Common Pages to Update

Add translation to these pages:
- ✅ Login.php (Done)
- ✅ Registration.php (Done)
- ✅ user_header.php (Done)
- user_enrollment_form.php
- user_enrollment_status.php
- user_students_page.php
- view_grades.php

## Next Steps

1. Read full documentation: `TRANSLATION_SYSTEM_GUIDE.md`
2. Review example page: `pages/user/EXAMPLE_TRANSLATED_PAGE.php`
3. Add translation to remaining user pages
4. Customize glossary terms as needed
5. Monitor API usage in Google Cloud Console

## Support

For issues or questions:
- Check full guide: `TRANSLATION_SYSTEM_GUIDE.md`
- Review code comments in `app/Translation/`
- Test with example page template

---

**Time to implement per page:** ~5-10 minutes
**Total implementation time:** ~2-3 hours for all user pages
