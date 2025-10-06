<?php
if (session_status() === PHP_SESSION_NONE) {
    $session = session_start();
}