<?php
// Set PHP timezone
date_default_timezone_set('Asia/Manila');
// Database credentials
$servername = 'mysql-ssis-test.alwaysdata.net';
$dbname = 'ssis-test_database';
$username = 'ssis-test';
$pw = 'SSISdatabasetest123';
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $pw);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET time_zone = '+08:00'");
    //START TRANSACTION
    $pdo->beginTransaction();
    // EXPIRE WHEN DATE REACHES THE ENDING DATE
    $expiredRows = $pdo->exec("
        UPDATE school_year_details
        SET Is_Expired = 1
        WHERE Ending_Date < CURDATE()
          AND Is_Expired = 0
    ");
    echo "[" . date("Y-m-d H:i:s") . "] School years expired: $expiredRows\n";
    //ONLY EXECUTE THESE CONDITIONS IF THERE WAS AN EXPIRED ROW THAT DAY
    if ($expiredRows > 0) {
        //UPDATE THEM IN A SUSPENDED STATE UNTIL THEY REENROLL
        $suspendedRows = $pdo->exec("
            UPDATE students
            SET Student_Status = 0, Section_Id = NULL
            WHERE Student_Status NOT IN (2,3)
        ");
        echo "[" . date("Y-m-d H:i:s") . "] Students suspended for reenrollment: $suspendedRows\n";
        // ARCHIVE GRADUATED STUDENTS (FINAL GRADE LEVEL)
        $students = $pdo->query("SELECT Student_Id FROM students WHERE Grade_Level_Id = 8");
        $graduated = 0;
        $transferred = 0;
        foreach ($students as $student) {
            $studentId = (int)$student['Student_Id'];
            if($pdo->exec("INSERT INTO archive_students SELECT * FROM students WHERE Student_Id = $studentId"))  {
                $pdo->exec("UPDATE students SET Student_Status = 5 WHERE Student_Id = $studentId");
                $graduated++;
                if($pdo->exec("DELETE FROM students WHERE Student_Id = $studentId")) {
                    $transferred++;
                }
            }
        }
        echo "[" . date("Y-m-d H:i:s") . "] Students graduated: $graduated\n";
        echo "[" . date("Y-m-d H:i:s") . "] Graduated transferred to archive: $transferred\n";
    }
    // 5️⃣ Archive school-year-specific tables
    $archivedSchedules = $pdo->exec("INSERT INTO archive_section_schedules
        SELECT * FROM section_schedules
        WHERE School_Year_Details_Id IN (SELECT School_Year_Details_Id FROM school_year_details WHERE Is_Expired = 1)
    ");
    $archivedAdvisers = $pdo->exec("INSERT INTO archive_section_advisers
        SELECT * FROM section_advisers
        WHERE School_Year_Details_Id IN (SELECT School_Year_Details_Id FROM school_year_details WHERE Is_Expired = 1)
    ");
    $archivedSubjects = $pdo->exec("INSERT INTO archive_section_subject_teachers
        SELECT * FROM section_subject_teachers
        WHERE School_Year_Details_Id IN (SELECT School_Year_Details_Id FROM school_year_details WHERE Is_Expired = 1)
    ");
    // Delete archived section schedules for expired years
    $deletedSchedules = $pdo->exec("
        DELETE FROM section_schedules
        WHERE School_Year_Details_Id IN (
            SELECT School_Year_Details_Id FROM school_year_details WHERE Is_Expired = 1
        )
    ");
    // Delete  section advisers for expired years
    $deletedAdvisers = $pdo->exec("
        DELETE FROM section_advisers
        WHERE School_Year_Details_Id IN (
            SELECT School_Year_Details_Id FROM school_year_details WHERE Is_Expired = 1
        )
    ");
    // Delete  section subject teachers for expired years
    $deletedSubjectTeachers = $pdo->exec("
        DELETE FROM section_subject_teachers
        WHERE School_Year_Details_Id IN (
            SELECT School_Year_Details_Id FROM school_year_details WHERE Is_Expired = 1
        )
    ");
    //CHECK AFFECTED ROWS
    echo "[" . date("Y-m-d H:i:s") . "] Deleted section schedules: $deletedSchedules and Section schedules archived: $archivedSchedules\n";
    echo "[" . date("Y-m-d H:i:s") . "] Deleted section advisers: $deletedAdvisers advisers archived: $archivedAdvisers\n";
    echo "[" . date("Y-m-d H:i:s") . "] Section subject teachers archived: $deletedSubjectTeachers and archived $archivedSubjects\n";
    //COMMIT
    $pdo->commit();
} catch (PDOException $e) {
    if (isset($pdo) && $pdo !== null && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    if ($e->getCode() == 1045) {
        echo "[" . date("Y-m-d H:i:s") . "] Database access denied! Check username/password and host permissions.\n";
    } elseif ($e->getCode() == 2002) {
        echo "[" . date("Y-m-d H:i:s") . "] Database server not reachable! Check host or network.\n";
    } else {
        echo "[" . date("Y-m-d H:i:s") . "] Database error: " . $e->getMessage() . " (Code: " . $e->getCode() . ")\n";
    }
}
finally {$pdo = null;}//CLOSE CONNECTION

