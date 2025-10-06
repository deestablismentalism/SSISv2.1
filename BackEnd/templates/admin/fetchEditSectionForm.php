<?php

require_once __DIR__ . '/../../admin/controller/adminSectionDetailsController.php';

$controller = new adminSectionDetailsController();
?>
<form id="edit-section-details" class="edit-section-details"> 
    <?php 
        if(isset($_GET['section_id'])) $id = $_GET['section_id'];
        $teachersResponse = $controller->viewEditSectionFormTeachers($id);
        $sectionNameResponse = $controller->viewEditSectionFormSectionName($id);
        $studentsResponse = $controller->viewEditSectionFormStudents($id);
        //section name value
        $sectionName = $sectionNameResponse['success'] ? htmlspecialchars($sectionNameResponse['data']['Section_Name']) : '';
    ?>
    <span> Section Name </span>
    <input type="text" name="section-name" value="<?php echo $sectionName; ?>">
    <span> Please Select an Adviser </span>
    <select name="select-adviser">
        <option value=""> Select an Adviser </option>
        <?php 
            if(isset($_GET['section_id'])) {
                $id = $_GET['section_id'];
                if(!$teachersResponse['success']) {
                    echo '<option value="">'.htmlspecialchars($teachersResponse['message']). '</option>';
                }
            }
            foreach($teachersResponse['data'] as $options) {
                $name = htmlspecialchars($options['Staff_Last_Name']) . ', '. htmlspecialchars($options['Staff_First_Name']) 
                        .' '. htmlspecialchars($options['Staff_Middle_Name']);
                $flagSelected = $options['isSelected'] ? 'selected' : '';
                echo '<option value="'. htmlspecialchars($options['Staff_Id']).'" '. $flagSelected.'> 
                    '.  $name .'
                    </option>';
            }
        ?>
    </select>
    <span> Grade level students list </span>
    <?php 
        if(!$studentsResponse['success']) {
            echo '<p>'.htmlspecialchars($studentsResponse['message']).'</p>';
        }
        foreach($studentsResponse['data'] as $checkboxes) {
            $isChecked = $checkboxes['isChecked'] ? 'checked' : '';
            
            echo '<div class="checkbox-container"> <input type="checkbox" name="students[]" value="'.$checkboxes['Student_Id'].'" '.$isChecked.'>    
                    '. htmlspecialchars($checkboxes['Last_Name']) .','. htmlspecialchars($checkboxes['First_Name']) .' '.
                        htmlspecialchars($checkboxes['Middle_Name']).
                '</div>';
        }
    
    ?>
    <button type="submit"> Save </button>
</form>