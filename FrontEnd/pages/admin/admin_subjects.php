<?php 
ob_start();
$pageJs = '<script src="../../assets/js/admin/admin-subjects-validation.js" defer></script>';
$pageTitle = "Admin Subjects";
$pageCss = '<link rel="stylesheet" href="../../assets/css/admin/admin-subjects.css">';
    require_once __DIR__ . '/../../../BackEnd/common/getGradeLevels.php';
?>
<div class="admin-subjects-content">
    <div class="add-subject">      
        <form action="../server_side/post_subjects.php" method="post" class="form">
            <input type="text" placeholder="enter subject name..." name="subject-name">
            <div class="radio-container">
                <p> Is this subject being taught in many grade levels?</p>
                <div> 
                    <input type="radio" id="multiLevelYes" name="subject" value="Yes">
                    <label for="multiLevelYes">  Yes </label>
                    <input type="radio" id="multiLevelNo" name="subject" value="No">
                    <label for="multiLevelNo"> No </label>
                </div>
            </div>
            <div class="select-container" id="select-container">
                <select name="subject-level" id="subject-level">
                    <?php 
                        $view = new getGradeLevels();
                        $view->createSelectValues();
                    ?>
                </select>
            </div>
            <div class="checkbox-container" id="checkbox-container">
                <div class="drop-down">
                    <button type="button" id="toggleCheckBox"> Select Subjects applicable </option>
                </div>
                <div class="checkboxes" id="checkboxes">
                    <?php 
                    $view = new getGradeLevels();
                    $view->createCheckBoxes();
                    ?>
                </div>
            </div>
            <button type="submit" class="submit-button"> Add subject </button>
        </form>
    </div>
    <div class="subjects-list">
        <h2> Subjects List </h2>
        <table class="subjects-table">
            <thead> 
                <th> Subject Name </th>
                <th> Grade Level </th>
                <th> Teacher Assigned </th>
                <th> Assign </th>
            </thead>
            <tbody> 
                <?php 
                    require_once __DIR__ . '/../../../BackEnd/admin/adminSubjectsView.php';
                    $view = new adminSubjectsView();
                    $view->displaySubjects();
                ?>
            </tbody>
        </table>
    </div>
</div>
<div class="success-message"></div>
<div class="error-message"></div>

<?php 
    $pageContent = ob_get_clean();
    require_once __DIR__ . '/./admin_base_designs.php';
?>