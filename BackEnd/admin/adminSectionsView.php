<?php
declare(strict_types =1);

require_once __DIR__ . '/./models/adminSectionsModel.php';

class adminSectionsView {
    private $sectionsModel;

    public function __construct() {
        $this->sectionsModel = new adminSectionsModel();
    }

    public function displayAdminSections() {
        $sections = $this->sectionsModel->getSections();

        foreach($sections as $rows) {
            echo '<tr data-id="'.$rows['Section_Id'].'"  class="section-row">
                <td> ' .htmlspecialchars($rows['Section_Name']). '</td>
                <td> ' .htmlspecialchars($rows['Grade_Level']). '</td> 
            </tr>';
        }
    }
}