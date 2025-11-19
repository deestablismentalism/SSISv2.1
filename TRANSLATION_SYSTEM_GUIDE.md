# Translation System Documentation

## Overview

This translation system integrates Google Cloud Translation API with advanced glossary support for educational terms. It provides automatic translation of user-facing pages in the SSIS system, supporting multiple Philippine languages and intelligent caching for performance.

## Features

- ✅ **Google Cloud Translation API Integration** - Professional translation quality
- ✅ **Advanced Educational Glossary** - 150+ specialized educational terms
- ✅ **Multi-Language Support** - Tagalog, English, Cebuano, Ilocano, and more
- ✅ **Intelligent Caching** - Reduces API calls and improves performance
- ✅ **User Preferences** - Remembers language choice per user
- ✅ **Dynamic Content Translation** - Translates new content automatically
- ✅ **Batch Translation** - Efficient bulk translation support
- ✅ **Offline Glossary Fallback** - Works with glossary even without API

## Table of Contents

1. [Installation](#installation)
2. [Configuration](#configuration)
3. [Usage](#usage)
4. [API Endpoints](#api-endpoints)
5. [Frontend Integration](#frontend-integration)
6. [Advanced Features](#advanced-features)
7. [Troubleshooting](#troubleshooting)

---

## Installation

### Step 1: Install Composer Dependencies

Run the following command in your project root:

```bash
composer install
```

This will install:
- `google/cloud-translate` - Google Cloud Translation API client
- `vlucas/phpdotenv` - Environment configuration management

### Step 2: Set Up Google Cloud Translation API

1. **Create a Google Cloud Project**
   - Go to [Google Cloud Console](https://console.cloud.google.com/)
   - Create a new project or select existing one
   - Note your Project ID

2. **Enable Translation API**
   - Navigate to "APIs & Services" → "Library"
   - Search for "Cloud Translation API"
   - Click "Enable"

3. **Create Service Account**
   - Go to "IAM & Admin" → "Service Accounts"
   - Click "Create Service Account"
   - Name: `ssis-translation-service`
   - Grant role: "Cloud Translation API User"
   - Click "Create Key" → Choose "JSON"
   - Download the JSON key file

4. **Store Credentials**
   - Create folder: `c:\xampp\htdocs\SSISv2.1\credentials\`
   - Save the JSON key file as: `google-cloud-key.json`
   - **IMPORTANT**: Add `credentials/` to `.gitignore`

### Step 3: Configure Environment Variables

Create or update your `.env` file in the project root:

```env
# Google Cloud Translation API Configuration
GOOGLE_CLOUD_PROJECT_ID=your-project-id-here
GOOGLE_CLOUD_CREDENTIALS_PATH=./credentials/google-cloud-key.json
TRANSLATION_GLOSSARY_ID=ssis-education-glossary

# Default Language (ISO 639-1 code)
DEFAULT_LANGUAGE=tl

# Translation Cache Settings
TRANSLATION_CACHE_ENABLED=true
TRANSLATION_CACHE_DURATION=86400
```

**Replace** `your-project-id-here` with your actual Google Cloud Project ID.

### Step 4: Set Up Database Tables

Run the SQL migration to create required tables:

```bash
# Using MySQL command line
mysql -u your_username -p your_database < database/translation_system.sql
```

Or import through phpMyAdmin:
1. Open phpMyAdmin
2. Select your database
3. Go to "Import" tab
4. Choose `database/translation_system.sql`
5. Click "Go"

This creates:
- `translation_cache` - Stores translated text for faster retrieval
- `user_preferences` - Stores user language preferences

---

## Configuration

### Supported Languages

The system supports the following languages out of the box:

| Code | Language | Native Name |
|------|----------|-------------|
| `tl` | Tagalog | Filipino |
| `en` | English | English |
| `ceb` | Cebuano | Cebuano |
| `ilo` | Ilocano | Ilokano |
| `hil` | Hiligaynon | Hiligaynon |
| `war` | Waray | Waray |
| `pam` | Kapampangan | Kapampangan |
| `pan` | Pangasinan | Pangasinan |
| `bik` | Bikol | Bikol |
| `es` | Spanish | Español |

### Adding New Languages

Edit `app/Translation/TranslationConfig.php`:

```php
$this->supportedLanguages = [
    'tl' => 'Tagalog (Filipino)',
    'en' => 'English',
    'your_code' => 'Your Language Name',
    // ... add more
];
```

### Educational Glossary

The system includes 150+ educational terms in `app/Translation/GlossaryManager.php`:

- School system terminology
- Academic levels and grades
- Student records
- Enrollment processes
- Personnel titles
- Document names
- System actions

**Example terms:**
- "Student Information System" → "Sistema ng Impormasyon ng Mag-aaral"
- "Enrollment Form" → "Form ng Pagpaparehistro"
- "Grade Level" → "Baitang"
- "Teacher" → "Guro"

---

## Usage

### Frontend Integration

#### Basic Translation Markup

Add `data-translate` attributes to any HTML element:

```html
<!-- Text content translation -->
<h1 data-translate="Welcome">Welcome</h1>
<p data-translate="Please log in to your account">Please log in to your account</p>

<!-- Button text -->
<button data-translate="Submit">Submit</button>

<!-- Placeholder translation -->
<input type="text" 
       placeholder="Enter your name" 
       data-translate-placeholder="Enter your name">

<!-- Title/tooltip translation -->
<a href="#" 
   title="Click here for help" 
   data-translate-title="Click here for help">
   Help
</a>
```

#### Including Language Switcher

Add the language switcher to any page:

```php
<?php
session_start();
include './pages/user/language-switcher.php';
?>
```

#### Loading Translation Scripts

Add to your page `<head>`:

```html
<link rel="stylesheet" href="./assets/css/language-switcher.css">
<script src="./assets/js/translation.js"></script>
```

### Backend Usage

#### Translate Single Text

```php
<?php
require_once __DIR__ . '/app/Translation/TranslationService.php';
use app\Translation\TranslationService;

$translator = new TranslationService();

$result = $translator->translate(
    'Welcome to our school',  // Text to translate
    'tl',                     // Target language (Tagalog)
    'en',                     // Source language (English)
    true                      // Use glossary
);

if ($result['success']) {
    echo $result['translated']; // "Maligayang pagdating sa aming paaralan"
}
```

#### Batch Translation

```php
$texts = [
    'Student Information System',
    'Enrollment Form',
    'Grade Level',
    'Teacher'
];

$results = $translator->batchTranslate($texts, 'tl', 'en', true);

foreach ($results as $result) {
    echo $result['translated'] . "\n";
}
```

---

## API Endpoints

### POST `/BackEnd/api/user/translate.php`

Translate single text.

**Request:**
```json
{
  "text": "Welcome",
  "target_lang": "tl",
  "source_lang": "en",
  "use_glossary": true
}
```

**Response:**
```json
{
  "success": true,
  "original": "Welcome",
  "translated": "Maligayang Pagdating",
  "source_lang": "en",
  "target_lang": "tl",
  "from_cache": false
}
```

### POST `/BackEnd/api/user/batchTranslate.php`

Translate multiple texts at once.

**Request:**
```json
{
  "texts": ["Welcome", "Login", "Register"],
  "target_lang": "tl",
  "source_lang": "en",
  "use_glossary": true
}
```

**Response:**
```json
{
  "success": true,
  "translations": [
    {
      "success": true,
      "original": "Welcome",
      "translated": "Maligayang Pagdating",
      "target_lang": "tl"
    }
  ],
  "count": 3
}
```

### POST `/BackEnd/api/user/setLanguagePreference.php`

Save user's language preference.

**Request:**
```json
{
  "language": "tl"
}
```

**Response:**
```json
{
  "success": true,
  "language": "tl",
  "language_name": "Tagalog (Filipino)",
  "message": "Language preference updated successfully"
}
```

### GET `/BackEnd/api/user/translationStatus.php`

Check translation system status.

**Response:**
```json
{
  "success": true,
  "status": {
    "configured": true,
    "api_ready": true,
    "cache_enabled": true,
    "cache_ready": true,
    "supported_languages": {...},
    "glossary_terms_count": 150
  }
}
```

---

## Frontend Integration

### JavaScript API

The `translation.js` provides a global `translationHelper` object:

```javascript
// Get current language
const currentLang = translationHelper.getCurrentLanguage();

// Translate text programmatically
const translated = await translationHelper.translateText('Welcome', 'tl');

// Translate multiple texts
const results = await translationHelper.batchTranslate(['Hello', 'Goodbye'], 'tl');

// Change language
translationHelper.setLanguage('en');

// Translate specific element
const element = document.querySelector('#my-text');
await translationHelper.translateElement(element);

// Listen for language changes
translationHelper.addObserver((newLang) => {
    console.log('Language changed to:', newLang);
});

// Clear translation cache
translationHelper.clearCache();
```

### Automatic Translation

The system automatically translates:
- All elements with `data-translate` on page load
- Dynamically added content (via MutationObserver)
- Content when language is changed

---

## Advanced Features

### Custom Glossary Terms

Add custom terms to the glossary:

```php
$glossaryManager = new GlossaryManager();
$glossaryManager->addCustomTerm('Custom Term', 'Pasadyang Termino');
```

### Export Glossary

```php
// Export as JSON
$json = $glossaryManager->exportGlossaryAsJson();
file_put_contents('glossary.json', $json);

// Export as CSV
$csv = $glossaryManager->exportGlossaryAsCsv();
file_put_contents('glossary.csv', $csv);
```

### Cache Management

```php
// Clear old cache entries
$translator->clearCache();

// Disable cache temporarily
$_ENV['TRANSLATION_CACHE_ENABLED'] = 'false';
```

### Language Detection

```php
$detectedLang = $translator->detectLanguage('Kamusta ka?');
echo $detectedLang; // "tl"
```

---

## Troubleshooting

### Translation Not Working

1. **Check API Configuration**
   ```php
   $config = TranslationConfig::getInstance();
   if (!$config->isConfigured()) {
       $errors = $config->getConfigErrors();
       print_r($errors);
   }
   ```

2. **Verify Credentials File**
   - Ensure file exists at path specified in `.env`
   - Check file permissions (should be readable)

3. **Test API Status**
   - Visit: `/BackEnd/api/user/translationStatus.php`
   - Check `api_ready` is `true`

### Fallback Mode

If Google API is not configured, the system uses **offline glossary mode**:
- Only glossary terms are translated
- Unknown terms remain unchanged
- No API calls or costs

### Cache Issues

Clear cache in multiple ways:

1. **Via PHP:**
   ```php
   $translator->clearCache();
   ```

2. **Via SQL:**
   ```sql
   DELETE FROM translation_cache;
   ```

3. **Via JavaScript:**
   ```javascript
   translationHelper.clearCache();
   ```

### Language Not Showing

1. Check browser console for JavaScript errors
2. Verify `translation.js` is loaded
3. Ensure elements have `data-translate` attribute
4. Check network tab for API call responses

---

## Performance Tips

1. **Use Batch Translation** - More efficient than individual calls
2. **Enable Caching** - Reduces API calls significantly
3. **Preload Common Terms** - Cache frequently used phrases
4. **Use Glossary** - Falls back without API calls
5. **Optimize Selectors** - Limit `data-translate` to visible content

---

## Cost Estimation

Google Cloud Translation API pricing (as of 2024):
- **Standard Edition**: $20 per 1 million characters
- **Advanced Edition**: $25 per 1 million characters

**With caching:**
- First-time page load: ~500 characters
- Subsequent loads: 0 API calls
- Typical monthly cost: $0-5 for small school

---

## Security Notes

1. **Never commit credentials** - Add to `.gitignore`:
   ```
   credentials/
   .env
   ```

2. **Restrict API key** - In Google Cloud Console:
   - Set application restrictions
   - Limit to Translation API only
   - Add IP restrictions if possible

3. **User input sanitization** - System sanitizes all translated content

---

## Support & Maintenance

### Regular Maintenance

1. **Clean old cache** (runs automatically via SQL event):
   ```sql
   DELETE FROM translation_cache 
   WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY);
   ```

2. **Monitor API usage** - Check Google Cloud Console

3. **Update glossary** - Add new school-specific terms

### Extending the System

To add new features:
1. Modify `TranslationService.php` for backend logic
2. Update `translation.js` for frontend features
3. Add API endpoints in `BackEnd/api/user/`
4. Update this documentation

---

## Quick Start Checklist

- [ ] Run `composer install`
- [ ] Create Google Cloud project
- [ ] Enable Translation API
- [ ] Download credentials JSON
- [ ] Save to `credentials/google-cloud-key.json`
- [ ] Update `.env` with Project ID
- [ ] Import `database/translation_system.sql`
- [ ] Add language switcher to pages
- [ ] Add `data-translate` attributes
- [ ] Include translation.js and CSS
- [ ] Test translation functionality

---

## Additional Resources

- [Google Cloud Translation API Documentation](https://cloud.google.com/translate/docs)
- [ISO 639-1 Language Codes](https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes)
- [DepEd K-12 Curriculum Guide](https://www.deped.gov.ph/)

---

**Version:** 1.0  
**Last Updated:** November 2025  
**Author:** SSIS Development Team
