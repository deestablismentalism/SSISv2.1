<?php
declare(strict_types=1);

class components {

    public function modalComponent(string $modalId, string $modalContentId) : string {
        return'<div class="modal" id="'.$modalId.'"> 
            <div class="modal-content" id="'.$modalContentId.'"></div>
        </div>';
    }
}