<?php
    require_once __DIR__ . '/../../admin/adminEnrolleeInfo.php';

    $view = new adminEnrolleeInfo();
?>

    <span class="close">&times;</span>
                        
<!-- 🧍‍♂️ Student Info Section -->
<h1>I. Impormasyon ng Mag-aaral</h1>
<table class="modal-table">
    <tbody>
        <?php $view->enrolleeInfo(); ?>
    </tbody>
</table>

<!-- 🎓 School Level Info Section -->
<h1>II. Impormasyon sa Pagpapatalang Pang-Eskwela</h1>
<table class="modal-table">
    <tbody>
        <?php $view->schoolLevelInfo(); ?>
    </tbody>
</table>

<!-- ♿ Special Conditions Section -->
<h1>III. Espesyal na Kondisyon (kung mayroon)</h1>
<table class="modal-table">
    <tr>
        <td>
            <?php $view->ifDisabled(); ?>
        </td>
    </tr>
</table>
<h1>IV. Impormasyon ng mga Magulang </h1>
<table class="modal-table">
    <tr>
        <td>
            <?php $view->parentInfo(); ?>
        </td>
    </tr>
</table>

<!-- 📄 PSA Image Section -->
<h1>V. PSA Birth Certificate</h1>
<table class="modal-table">
    <tr>
        <tbody>
            <?php $view->displayPsaImg(); ?>
        </tbody>
    </tr>
<table>