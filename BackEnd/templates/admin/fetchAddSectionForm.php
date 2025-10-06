<?php 
require_once __DIR__ . '/../../common/getGradeLevels.php';
$getGradeLevels = new getGradeLevels();

?>
<form id="add-section-form" class="add-section-form"> 
    <span class="close"> &times; </span><br>
    <input type="text" name="section-name">
    <select name="section-grade-level"> 
        <?php $getGradeLevels->createSelectValues(); ?>
    </select>
    <button type="submit"> Add Section </button>
</form>
