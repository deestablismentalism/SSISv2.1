<?php require_once __DIR__ . '/../../../BackEnd/common/getGradeLevels.php';?>
<span class="close"> &times; </span>
<form class="form" id="add-subject-form">
        <input type="text" placeholder="enter subject name..." name="subject-name">
        <div class="radio-container">
            <p> Is this subject being taught in many grade levels?</p>
            <div class="radio-options"> 
                <label for="multiLevelYes" class="radio-label">
                    <input type="radio" id="multiLevelYes" name="subject" value="Yes">
                    <span class="radio-text">Yes</span>
                </label>
                <label for="multiLevelNo" class="radio-label">
                    <input type="radio" id="multiLevelNo" name="subject" value="No">
                    <span class="radio-text">No</span>
                </label>
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
                <button type="button" class="toggleCheckBox"> Select Subjects applicable </option>
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