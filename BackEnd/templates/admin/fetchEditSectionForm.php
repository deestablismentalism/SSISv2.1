<?php

require_once __DIR__ . '/../../admin/models/adminSectionsModel.php';

$sectionsModel = new adminSectionsModel();
?>
<span class="close"> &times; </span>
<form id="edit-section-details" class="edit-section-details"> 
    <?php 
        $teachers = $sectionsModel->getAllTeachers();
        if(isset($_GET['section_id'])) $id = $_GET['section_id'];
        $getSection = $sectionsModel->getSectionName($id);
        $sectionName = htmlspecialchars($getSection['Section_Name']);
    ?>
    <span> Section Name </span>
    <input type="hidden" name="section-id" value="<?php echo $id ?>">
    <input type="text" name="section-name" value="<?php echo $sectionName; ?>">
    <span> Please Select an Adviser </span>
    <select name="select-adviser">
        <?php 
            if(isset($_GET['section_id'])) {
                $id = $_GET['section_id'];
                $currentAdviser = $sectionsModel->checkCurrentAdviser($id);
                $students = $sectionsModel->getAvailableStudents($id);
                $checkStudents = $sectionsModel->getCheckedStudents($id);
            }
            foreach($teachers as $options) {
                $name = htmlspecialchars($options['Staff_Last_Name']) . ', '. htmlspecialchars($options['Staff_First_Name']) 
                        .' '. htmlspecialchars($options['Staff_Middle_Name']);
                $flagSelected = ($currentAdviser == $options['Staff_Id'] && !empty($currentAdviser)) ? 'selected' : '';
                echo '<option value="'. htmlspecialchars($options['Staff_Id']).'" '. $flagSelected.'> 
                    '.  $name .'
                    </option>';
            }
        ?>
    </select>
    <span> Grade level students list </span>
    <?php 
        if(!empty($students)) {
            $checkedIds = array_column($checkStudents, 'Student_Id');
            foreach($students as $checkboxes) {
                $isChecked = (in_array($checkboxes['Student_Id'], $checkedIds)) ? 'checked' : '';
                
                echo '<div class="checkbox-container"> <input type="checkbox" name="students[]" value="'.$checkboxes['Student_Id'].'" '.$isChecked.'>    
                        '. htmlspecialchars($checkboxes['Last_Name']) .','. htmlspecialchars($checkboxes['First_Name']) .' '.
                            htmlspecialchars($checkboxes['Middle_Name']).
                    '</div>';
            }
        }
        else {
            echo '<p> No students available yet. </p>';
        }
    ?>
    <button type="submit"> Save </button>
</form>
    <button class="cancel-btn"> Cancel </button>