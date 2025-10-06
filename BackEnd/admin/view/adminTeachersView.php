<?php
    require_once __DIR__ . '/./models/adminTeachersModel.php';
    
    class adminTeachersView {
        protected $displayTeachers;
        private $User_Type;

        public function __construct() {
            $this->displayTeachers = new adminTeachersModel();
            $this->User_Type = $_SESSION['Staff']['Staff-Type'];
        }

        public function displayAllTeachers() {
            $teachers = $this->displayTeachers->selectAllTeachers();
            echo "<tr>
                    <th>Full Name</th>
                    <th>Contact Number</th>
                    <th>Position</th>
                    <th>Action</th>
                    </tr>";
            foreach ($teachers as $teacher) {
                echo"<tr>";
                echo "<td>" . $teacher['Staff_First_Name'] . " " . $teacher['Staff_Middle_Name'] . " " . $teacher['Staff_Last_Name']. "</td>";
                echo "<td>" . $teacher['Staff_Contact_Number'] . "</td>";
                echo "<td>" . $teacher['Position'] . "</td>";

                if ($this->User_Type == 1){
                    echo '<td> <a href="./admin_teacher_info.php?staff_id=' . $teacher['Staff_Id'] . '" class="btn btn-primary">Edit</a></td>';
                }
                else if ($this->User_Type == 2){
                    echo '<td> <a href="./admin_teacher_info.php?staff_id=' . $teacher['Staff_Id'] . '" class="btn btn-primary">View</a></td>';
                }
                echo "</tr>";
            }
        }
    }

?>