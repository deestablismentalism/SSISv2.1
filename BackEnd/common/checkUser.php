<?php
if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
class checkuser {

    public function directory() {
        $directory = '';
        if (isset($_SESSION['Admin']['User-Id'])) {
            $directory = '/../adminPages/admin_base_designs.php';
            return $directory;
        }
        else if (isset($_SESSION['Staff']['User-Id'])){
            $directory = '/../teacherPages/teacher_base_designs.php';
            return $directory;
        }
        else {
            return '/../no_user.php';
        }
    }
}

