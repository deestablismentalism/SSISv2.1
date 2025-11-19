# Translation System Setup Commands
# Run these commands in PowerShell to set up the translation system

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "SSIS Translation System Setup" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Step 1: Check current directory
Write-Host "[Step 1/7] Checking current directory..." -ForegroundColor Yellow
$currentDir = Get-Location
Write-Host "Current directory: $currentDir" -ForegroundColor Green

# Step 2: Install Composer dependencies
Write-Host ""
Write-Host "[Step 2/7] Installing Composer dependencies..." -ForegroundColor Yellow
if (Test-Path "composer.json") {
    composer install
    Write-Host "✓ Composer dependencies installed" -ForegroundColor Green
} else {
    Write-Host "✗ composer.json not found!" -ForegroundColor Red
    exit 1
}

# Step 3: Check if .env exists
Write-Host ""
Write-Host "[Step 3/7] Checking .env configuration..." -ForegroundColor Yellow
if (Test-Path ".env") {
    Write-Host "✓ .env file exists" -ForegroundColor Green
} else {
    Write-Host "! .env file not found" -ForegroundColor Yellow
    if (Test-Path ".env.example") {
        Copy-Item ".env.example" ".env"
        Write-Host "✓ Created .env from .env.example" -ForegroundColor Green
        Write-Host "! IMPORTANT: Please edit .env with your Google Cloud Project ID" -ForegroundColor Cyan
    } else {
        Write-Host "✗ .env.example not found!" -ForegroundColor Red
    }
}

# Step 4: Create credentials directory
Write-Host ""
Write-Host "[Step 4/7] Creating credentials directory..." -ForegroundColor Yellow
if (!(Test-Path "credentials")) {
    New-Item -ItemType Directory -Path "credentials" | Out-Null
    Write-Host "✓ Created credentials directory" -ForegroundColor Green
} else {
    Write-Host "✓ Credentials directory exists" -ForegroundColor Green
}

# Step 5: Check database connection
Write-Host ""
Write-Host "[Step 5/7] Database setup..." -ForegroundColor Yellow
Write-Host "Please run the following SQL file in your MySQL database:" -ForegroundColor Cyan
Write-Host "  File: database/translation_system.sql" -ForegroundColor White
Write-Host ""
Write-Host "Option 1: MySQL Command Line" -ForegroundColor Cyan
Write-Host '  mysql -u root -p your_database < database/translation_system.sql' -ForegroundColor White
Write-Host ""
Write-Host "Option 2: phpMyAdmin" -ForegroundColor Cyan
Write-Host "  1. Open phpMyAdmin" -ForegroundColor White
Write-Host "  2. Select your database" -ForegroundColor White
Write-Host "  3. Click 'Import' tab" -ForegroundColor White
Write-Host "  4. Choose database/translation_system.sql" -ForegroundColor White
Write-Host "  5. Click 'Go'" -ForegroundColor White

# Step 6: Verify file structure
Write-Host ""
Write-Host "[Step 6/7] Verifying file structure..." -ForegroundColor Yellow

$requiredFiles = @(
    "app/Translation/TranslationConfig.php",
    "app/Translation/GlossaryManager.php",
    "app/Translation/TranslationService.php",
    "BackEnd/api/user/translate.php",
    "BackEnd/api/user/batchTranslate.php",
    "BackEnd/api/user/setLanguagePreference.php",
    "BackEnd/api/user/translationStatus.php",
    "FrontEnd/assets/js/translation.js",
    "FrontEnd/assets/css/language-switcher.css",
    "FrontEnd/pages/user/language-switcher.php",
    "database/translation_system.sql"
)

$allFilesExist = $true
foreach ($file in $requiredFiles) {
    if (Test-Path $file) {
        Write-Host "  ✓ $file" -ForegroundColor Green
    } else {
        Write-Host "  ✗ $file (MISSING)" -ForegroundColor Red
        $allFilesExist = $false
    }
}

if ($allFilesExist) {
    Write-Host "✓ All required files are present" -ForegroundColor Green
} else {
    Write-Host "✗ Some files are missing!" -ForegroundColor Red
}

# Step 7: Summary and Next Steps
Write-Host ""
Write-Host "[Step 7/7] Setup Summary" -ForegroundColor Yellow
Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "NEXT STEPS" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "1. Configure .env file:" -ForegroundColor Yellow
Write-Host "   - Update GOOGLE_CLOUD_PROJECT_ID" -ForegroundColor White
Write-Host "   - Verify GOOGLE_CLOUD_CREDENTIALS_PATH" -ForegroundColor White
Write-Host ""
Write-Host "2. Google Cloud Setup (Optional):" -ForegroundColor Yellow
Write-Host "   - Create Google Cloud project" -ForegroundColor White
Write-Host "   - Enable Translation API" -ForegroundColor White
Write-Host "   - Create service account" -ForegroundColor White
Write-Host "   - Download JSON key to credentials/" -ForegroundColor White
Write-Host ""
Write-Host "3. Import database/translation_system.sql" -ForegroundColor Yellow
Write-Host ""
Write-Host "4. Test the system:" -ForegroundColor Yellow
Write-Host "   Visit: http://localhost/SSISv2.1/FrontEnd/Login.php" -ForegroundColor White
Write-Host "   Look for language switcher dropdown" -ForegroundColor White
Write-Host ""
Write-Host "5. Read documentation:" -ForegroundColor Yellow
Write-Host "   - TRANSLATION_QUICK_START.md (5-min guide)" -ForegroundColor White
Write-Host "   - TRANSLATION_SYSTEM_GUIDE.md (full docs)" -ForegroundColor White
Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Setup script completed!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Cyan
