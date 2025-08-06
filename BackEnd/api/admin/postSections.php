<?php
declare(strict_types = 1);
require_once __DIR__ . '/../../admin/models/adminSectionsModel.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    try {
        $sectionsModel = new adminSectionsModel();

    $sectionName = $_POST['section-name'];
    $sectionGradeId =$_POST['section-level'];

    if(!empty($sectionName)) {
        
        if($sectionsModel->insertSections($sectionName, $sectionGradeId)) {
            header('Location: ../../../FrontEnd/pages/admin/admin_sections.php?insert=success');
            exit();
        }
        else {
            header('Location: ../../../FrontEnd/pages/admin/admin_sections.php?insert=failed');
            exit();
        }
    }
    }
    catch(PDOException $e) {
        echo "error: " . $e->getMessage();
    }
}