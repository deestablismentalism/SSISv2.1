<?php
    require_once __DIR__ . '/../../admin/view/adminEnrolleeInfo.php';

    $view = new adminEnrolleeInfo();
?>

                        
<!-- 🧍‍♂️ Student Info Section -->
<h1 class="enrollee-modal-title">I. Impormasyon ng Mag-aaral</h1>
<table class="modal-table">
    <tbody>
        <?php $view->displayEnrolleePersonalInfo(); ?>
    </tbody>
</table>

<!-- 🎓 School Level Info Section -->
<h1 class="enrollee-modal-title">II. Impormasyon sa Pagpapatalang Pang-Eskwela</h1>
<table class="modal-table">
    <tbody>
        <?php $view->displayEnrolleeEducationalInfo(); ?>
    </tbody>
</table>

<!-- ♿ Special Conditions Section -->
<h1 class="enrollee-modal-title">III. Espesyal na Kondisyon (kung mayroon)</h1>
<table class="modal-table">
    <tr>
        <td>
            <?php $view->displayDisabledInfo(); ?>
        </td>
    </tr>
</table>
<h1 class="enrollee-modal-title">IV. Impormasyon ng mga Magulang </h1>
<table class="modal-table">
    <tr>
        <td>
            <?php $view->displayParentInfo(); ?>
        </td>
    </tr>
</table>

<!-- 📄 PSA Image Section -->
<h1 class="enrollee-modal-title">V. PSA Birth Certificate</h1>
<table class="modal-table">
    <tr>
        <tbody>
            <?php $view->displayPsaImg(); ?>
        </tbody>
    </tr>
<table>