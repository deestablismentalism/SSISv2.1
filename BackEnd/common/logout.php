<?php
session_start();
session_unset();
session_destroy();
header("Location: ../../FrontEnd/Login.php");
exit();
?>