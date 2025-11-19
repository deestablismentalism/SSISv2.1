<?php
/**
 * Example User Page Template with Translation Support
 * Copy this template for new user pages
 */
session_start();

// Page configuration
$pageTitle = 'SSIS - Example Page';
$pageCss = '<link rel="stylesheet" href="../../assets/css/user/example-page.css">';
$pageJs = '<script src="../../assets/js/user/example-page.js" defer></script>';

// Include base template
require_once __DIR__ . '/user_base_designs.php';
?>

<!-- Include translation assets -->
<link rel="stylesheet" href="../../assets/css/language-switcher.css">
<script src="../../assets/js/translation.js"></script>

<div class="page-content">
    <!-- Page Header -->
    <div class="content-header">
        <h1 data-translate="Welcome to Example Page">Welcome to Example Page</h1>
        <p data-translate="This is a demonstration of the translation system">
            This is a demonstration of the translation system
        </p>
    </div>

    <!-- Example Form -->
    <form class="example-form">
        <div class="form-group">
            <label for="student-name" data-translate="Student Name">Student Name</label>
            <input type="text" 
                   id="student-name" 
                   name="student-name" 
                   data-translate-placeholder="Enter student name"
                   placeholder="Enter student name">
        </div>

        <div class="form-group">
            <label for="grade-level" data-translate="Grade Level">Grade Level</label>
            <select id="grade-level" name="grade-level">
                <option value="" data-translate="Select Grade">Select Grade</option>
                <option value="1" data-translate="Grade 1">Grade 1</option>
                <option value="2" data-translate="Grade 2">Grade 2</option>
                <option value="3" data-translate="Grade 3">Grade 3</option>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" 
                    class="btn btn-primary" 
                    data-translate="Submit">
                Submit
            </button>
            <button type="button" 
                    class="btn btn-secondary" 
                    data-translate="Cancel">
                Cancel
            </button>
        </div>
    </form>

    <!-- Example Table -->
    <div class="data-table">
        <h2 data-translate="Student List">Student List</h2>
        <table>
            <thead>
                <tr>
                    <th data-translate="Student Name">Student Name</th>
                    <th data-translate="Grade Level">Grade Level</th>
                    <th data-translate="Status">Status</th>
                    <th data-translate="Actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Juan Dela Cruz</td>
                    <td data-translate="Grade 1">Grade 1</td>
                    <td data-translate="Active">Active</td>
                    <td>
                        <button class="btn-action" 
                                data-translate="Edit"
                                data-translate-title="Edit student information"
                                title="Edit student information">
                            Edit
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Example Alert/Notification -->
    <div class="alert alert-info">
        <strong data-translate="Information">Information:</strong>
        <span data-translate="This page demonstrates translation features">
            This page demonstrates translation features
        </span>
    </div>
</div>

<!-- JavaScript for dynamic content translation -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Example: Dynamically add translated content
    function addDynamicContent() {
        const container = document.createElement('div');
        container.innerHTML = '<p data-translate="This content was added dynamically">This content was added dynamically</p>';
        document.querySelector('.page-content').appendChild(container);
        
        // The translation system will automatically detect and translate this
    }
    
    // Example: Translate text programmatically
    async function translateCustomText() {
        const text = 'Custom message to translate';
        const translated = await translationHelper.translateText(text, 'tl', 'en');
        console.log('Translated:', translated);
    }
    
    // Example: Listen for language changes
    translationHelper.addObserver((newLang) => {
        console.log('User changed language to:', newLang);
        // Perform any language-specific actions here
    });
});
</script>

<?php
// Include footer if needed
// include __DIR__ . '/footer.php';
?>
