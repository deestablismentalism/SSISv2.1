<?php
    $session = '';
if (session_status() === PHP_SESSION_NONE) {
    $session = session_start();
}
else {
    $session = '';
}
echo $session;