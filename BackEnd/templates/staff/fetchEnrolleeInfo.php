<?php
    require_once __DIR__ . '/../../admin/views/adminEnrolleeInfo.php';
    $view = new adminEnrolleeInfo();
?>                      
<!-- 🧍‍♂️ Student Info Section -->
<?php $view->displayGlobalError();?>
<h1>I. Impormasyon ng Mag-aaral</h1>
<?php $view->displayEnrolleePersonalInfo(); ?>
<!-- 🎓 School Level Info Section -->
<h1>II. Impormasyon sa Pagpapatalang Pang-Eskwela</h1>
<?php $view->displayEnrolleeEducationalInfo(); ?>
<h1>III. Impormasyon sa Eskwelang Huli at Nais Pagpatalaan</h1>
<?php $view->displayEnrolleeEducationalBackground(); ?>
<!-- ♿ Special Conditions Section -->
<h1>IV. Espesyal na Kondisyon (kung mayroon)</h1>
<?php $view->displayDisabledInfo(); ?>
<h1>V. Impormasyon ng mga Magulang </h1>
<?php $view->displayParentInfo(); ?>          
<!-- 📄 Report Card Section -->
<h1>VI. Report Card</h1>
<?php $view->displayReportCard(); ?>
