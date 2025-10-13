<?php
function renderNotification($type, $title, $message) {
    $icon = '';
    if ($type === 'success-notification') {
        $icon = '
            <svg aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" class="success-notification-svg">
                <path clip-rule="evenodd" fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z">
                </path>
            </svg>';
    }
    echo "
    <div class='notifications-container'>
        <div class='$type'>
            <div class='flex'>
                <div class='flex-shrink-0'>$icon</div>
                <div class='success-notification-prompt-wrap'>
                    <p class='success-notification-prompt-heading'>$title <span class='checkmark'>✓</span></p>
                    <div class='success-notification-prompt-prompt'><p>$message</p></div>
                    <div class='success-notification-button-container'>
                        <button class='success-notification-button-secondary' type='button'>Dismiss</button>
                    </div>
                </div>
            </div>
        </div>
    </div>";
}
?>