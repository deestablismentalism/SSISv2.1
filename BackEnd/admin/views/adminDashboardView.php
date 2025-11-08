<?php
require_once __DIR__ . '/../controllers/adminDashboardController.php';

class adminDashboardView {
    protected $controller;

    public function __construct() {
        $this->controller = new adminDashboardController();
    }

    public function displayEnrolleesCount() {
        $response = $this->controller->viewEnrolleesCount();
        if(!$response['success']) {
            echo '<span>'. htmlspecialchars($response['message']). '</span>';
        }

        $data = $response['data'];
        echo '<span>' .$data.'</span>';
    }

    public function displayStudentsCount() {
        $response = $this->controller->viewStudentsCount();
        if(!$response['success']) {
            echo '<span>'. htmlspecialchars($response['message']). '</span>';
        }

        $data = $response['data'];
        echo '<span>' .$data.'</span>';
    }

    public function displayDeniedAndToFollowUpCount() {
        $response = $this->controller->viewDeniedAndFollowedUpCount();
        if(!$response['success']) {
            echo '<span>'. htmlspecialchars($response['message']). '</span>';
        }
        $data = $response['data'];
        echo '<span>' .$data.'</span>';
    }
    public function displayPendingEnrolleesInformation() {
            $response = $this->controller->viewPendingEnrollees();        
            if(!$response['success']) {
                echo '<span>' .htmlspecialchars($response['message']). '</span>';
            }
            $data = $response['data'];

            foreach($data as $row) {
                $firstName = htmlspecialchars($row['Student_First_Name']);
                $lastName = htmlspecialchars($row['Student_Last_Name']);
                $middleName = !empty($row['Student_Middle_Name']) ? htmlspecialchars($row['Student_Middle_Name']) : '';
                $fullName = $lastName . ', ' . $firstName . ' ' . $middleName;

                echo '<tr> 
                    <td>'.$row['Learner_Reference_Number'].'</td>
                    <td>'. $fullName. '</td>
                    <td>'.htmlspecialchars($row['E_Grade_Level']).'</td>
                </tr>';
            }
    }
}

?>



/*====================================*/
/*   ADMIN SYSTEM MANAGEMENT STYLES   */
/*====================================*/

:root {
    --main-fonts:  "Poppins", "Segoe UI", sans-serif;
    --title-fonts: "Barlow", sans-serif;
    --button-fonts: 'Baloo-Thambi-2';
    --link-fonts: 'Roboto', sans-serif;
    --sidebar-width: 60px;
    --sidebar-expanded-width: 280px; /* Changed from 20dvw to fixed pixels */
    --title-font-size: 3em;
    --main-color: #1c4a72;
    --accent-color: #00b4d8;
    --secondary-color: #F1BD22;
    --wrapper-bg-color: #fffdfdf5;
    --title-main-color: #3e9ec4;
    --container-color: rgba(36, 186, 255, 0.28);
    --button-color: ;
    --button-hover-color: ;
    --button-click: scale(.9);
    --border-radius: 1rem;
    --title-h1-font-size: 1.6rem;
    --title-font-weight: 600;
    --title-letter-spacing: 1px;
    --card-border: rgba(255, 255, 255, 0.2);
    --warning: #ffcc00;
    --danger: #ff5f5f;
    --success: #44d07b;
}

/* Main Container */
.system-management-content {
    padding: 2rem;
    max-width: 1600px;
    margin: 0 auto;
    font-family: var(--main-fonts);
    min-height: calc(100vh - 3rem);
}

.system-management-content h1,
.system-management-content h2,
.system-management-content h3 {
    font-family: var(--title-fonts);
    color: var(--main-color);
    font-weight: var(--title-font-weight);
    letter-spacing: var(--title-letter-spacing);
}

.system-management-content > h3 {
    text-align: center;
    font-size: var(--title-h1-font-size);
    margin-bottom: 2rem;
    margin-top: 0;
    position: relative;
    padding: 1.5rem;
    background: var(--container-color);
    border-radius: 1.5rem;
}

.system-management-content > h3::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 80px;
    height: 3px;
}

/* ====================================
   TWO COLUMN GRID LAYOUT
   ==================================== */

.system-management-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    align-items: start;
}

.left-column,
.right-column {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.section-wrapper {
    width: 100%;
}

.section-title {
    font-size: 1.3rem;
    color: var(--main-color);
    margin-bottom: 1.2rem;
    padding-bottom: 0.8rem;
    border-bottom: 2px solid var(--accent-color);
}

/* ====================================
   SCHOOL YEAR DETAILS SECTION
   ==================================== */

.school-year-details {
    background: white;
    border-radius: var(--border-radius);
    padding: 2rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    border-left: 4px solid var(--secondary-color);
}

/* View Mode */
.view-mode-container {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.view-mode-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1.5rem;
}

.dates {
    flex: 1;
    min-width: 250px;
}

.dates p {
    margin: 0.8rem 0;
    font-size: 1rem;
    color: #2c3e50;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.dates strong {
    color: var(--main-color);
    font-weight: 600;
    min-width: 120px;
}

.button-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.button-actions button {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 8px;
    font-family: var(--main-fonts);
    font-size: 0.95rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

#edit-btn {
    background: linear-gradient(135deg, var(--main-color), #3e9ec4);
    color: white;
    box-shadow: 0 4px 12px rgba(28, 74, 114, 0.3);
}

#edit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(28, 74, 114, 0.4);
}

#edit-btn:active {
    transform: var(--button-click);
}

/* Edit Mode */
.edit-mode-container {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

#school-year-details-form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

#school-year-details-form input[type="date"] {
    padding: 0.9rem 1.2rem;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-family: var(--main-fonts);
    font-size: 1rem;
    transition: all 0.3s ease;
    background: #f8f9fa;
    color: #2c3e50;
}

#school-year-details-form input[type="date"]:focus {
    outline: none;
    border-color: var(--main-color);
    background: white;
    box-shadow: 0 0 0 3px rgba(28, 74, 114, 0.1);
}

#school-year-details-form input[type="date"]:hover {
    border-color: #3e9ec4;
}

.actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 0.5rem;
}

.actions button {
    padding: 0.75rem 1.8rem;
    border: none;
    border-radius: 8px;
    font-family: var(--main-fonts);
    font-size: 0.95rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

#cancel-btn {
    background: #e0e0e0;
    color: #555;
}

#cancel-btn:hover {
    background: #bdbdbd;
    transform: translateY(-2px);
}

#save-btn {
    background: linear-gradient(135deg, var(--success), #3ac965);
    color: white;
    box-shadow: 0 4px 12px rgba(68, 208, 123, 0.3);
}

#save-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(68, 208, 123, 0.4);
}

#cancel-btn:active,
#save-btn:active {
    transform: var(--button-click);
}

/* ====================================
   ARCHIVES SECTION
   ==================================== */

.archives {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.student-archives,
.teacher-archives {
    background: white;
    border-radius: var(--border-radius);
    padding: 2rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.student-archives::before,
.teacher-archives::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--secondary-color), transparent);
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.3s ease;
}

.student-archives:hover::before,
.teacher-archives:hover::before {
    transform: scaleX(1);
}

.student-archives:hover,
.teacher-archives:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

.archives a {
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    color: var(--main-color);
    font-size: 1.1rem;
    font-weight: 600;
    padding: 1rem;
    transition: color 0.3s ease;
    position: relative;
}

.archives a::after {
    content: '→';
    margin-left: 0.8rem;
    font-size: 1.3rem;
    transition: transform 0.3s ease;
}

.archives a:hover {
    color: #3e9ec4;
}

.archives a:hover::after {
    transform: translateX(5px);
}

/* ====================================
   LOGIN ACTIVITY SECTION
   ==================================== */

.users-login-activity,
.teachers-login-activity {
    background: white;
    border-radius: var(--border-radius);
    padding: 2rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.teachers-login-activity {
  height: 60vh;
}
.users-login-activity .section-title,
.teachers-login-activity .section-title {
    margin-bottom: 1.5rem;
}

/* Table Styles */
.ul-table-container,
.tl-table-container {
    overflow-x: auto;
    border-radius: 8px;
    background: #f8f9fa;
    padding: 1rem;
    overflow-y: scroll;
    scrollbar-width: thin;
    scrollbar-color: #1a6e7e40 transparent;
}

.tl-table-container {
    margin-top: 1.5rem;
    page-break-inside: avoid;
    height: 47vh;
}

.ul-table-container table,
.tl-table-container table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.95rem;
}

.ul-table-container th,
.tl-table-container th {
    background: #006798b0;
    color: white;
    padding: 1rem;
    text-align: left;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
}

.ul-table-container td,
.tl-table-container td {
    padding: 1rem;
    border-bottom: 1px solid #e0e0e0;
    color: #2c3e50;
}

.ul-table-container tr:last-child td,
.tl-table-container tr:last-child td {
    border-bottom: none;
}

.ul-table-container tbody tr,
.tl-table-container tbody tr {
    transition: background-color 0.2s ease;
}

.ul-table-container tbody tr:hover,
.tl-table-container tbody tr:hover {
    background-color: rgba(62, 158, 196, 0.05);
}

/* Empty State */
.ul-table-container .no-data,
.tl-table-container .no-data {
    text-align: center;
    padding: 3rem 1rem;
    color: #999;
    font-style: italic;
}

/* ====================================
   RESPONSIVE DESIGN
   ==================================== */

/* Large Desktop (1440px - 1919px) */
@media screen and (min-width: 1440px) and (max-width: 1919px) {
    .system-management-grid {
        gap: 1.8rem;
    }
}

/* Desktop (1024px - 1439px) */
@media screen and (max-width: 1439px) {
    .system-management-content {
        padding: 1.8rem;
    }

    .system-management-grid {
        gap: 1.5rem;
    }

    .section-title {
        font-size: 1.2rem;
    }
}

/* Tablet Landscape (992px - 1023px) */
@media screen and (max-width: 1023px) {
    .system-management-content {
        padding: 1.5rem;
    }

    .system-management-content > h3 {
        font-size: 1.4rem;
    }

    .system-management-grid {
        grid-template-columns: 1fr;
        gap: 2rem;
    }

    .school-year-details {
        padding: 1.5rem;
    }

    .view-mode-content {
        flex-direction: column;
        align-items: stretch;
    }

    .button-actions {
        justify-content: flex-end;
    }
}

/* Tablet Portrait (768px - 991px) */
@media screen and (max-width: 991px) {
    .system-management-grid {
        gap: 1.5rem;
    }

    .left-column,
    .right-column {
        gap: 1.5rem;
    }
}

/* Mobile (max-width: 767px) */
@media screen and (max-width: 767px) {
    .system-management-content {
        padding: 1rem;
    }

    .system-management-content > h3 {
        font-size: 1.2rem;
        margin-bottom: 1.5rem;
    }

    .system-management-grid {
        gap: 1.5rem;
    }

    .left-column,
    .right-column {
        gap: 1.5rem;
    }

    .section-title {
        font-size: 1.1rem;
        margin-bottom: 1rem;
        padding-bottom: 0.6rem;
    }

    .school-year-details {
        padding: 1.2rem;
    }

    .dates p {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.3rem;
    }

    .dates strong {
        min-width: auto;
    }

    .button-actions {
        width: 100%;
    }

    .button-actions button {
        flex: 1;
        padding: 0.7rem 1rem;
        font-size: 0.9rem;
    }

    .actions {
        flex-direction: column-reverse;
    }

    .actions button {
        width: 100%;
        padding: 0.8rem 1.2rem;
    }

    .student-archives,
    .teacher-archives {
        padding: 1.5rem;
    }

    .archives a {
        font-size: 1rem;
    }

    .users-login-activity,
    .teachers-login-activity {
        padding: 1.2rem;
    }

    .ul-table-container,
    .tl-table-container {
        padding: 0.5rem;
    }

    .ul-table-container th,
    .tl-table-container th,
    .ul-table-container td,
    .tl-table-container td {
        padding: 0.7rem 0.5rem;
        font-size: 0.85rem;
    }
}

/* Small Mobile (max-width: 479px) */
@media screen and (max-width: 479px) {
    .system-management-content {
        padding: 0.8rem;
    }

    .system-management-content > h3 {
        font-size: 1.1rem;
    }

    .system-management-grid {
        gap: 1.2rem;
    }

    .left-column,
    .right-column {
        gap: 1.2rem;
    }

    .section-title {
        font-size: 1rem;
    }

    .school-year-details {
        padding: 1rem;
    }

    #school-year-details-form input[type="date"] {
        padding: 0.7rem 1rem;
        font-size: 0.9rem;
    }

    .archives a {
        font-size: 0.95rem;
        padding: 0.8rem;
    }

    .ul-table-container th,
    .tl-table-container th {
        font-size: 0.75rem;
        padding: 0.6rem 0.4rem;
    }

    .ul-table-container td,
    .tl-table-container td {
        font-size: 0.8rem;
        padding: 0.6rem 0.4rem;
    }
}

/* Extra Small Mobile (max-width: 374px) */
@media screen and (max-width: 374px) {
    .system-management-content {
        padding: 0.5rem;
    }

    .system-management-content > h3 {
        font-size: 1rem;
    }

    .system-management-grid {
        gap: 1rem;
    }

    .left-column,
    .right-column {
        gap: 1rem;
    }

    .section-title {
        font-size: 0.95rem;
        margin-bottom: 0.8rem;
    }

    .button-actions button {
        padding: 0.6rem 0.8rem;
        font-size: 0.85rem;
    }

    .archives a {
        font-size: 0.9rem;
    }
}

/* Print Styles */
@media print {
    .system-management-content {
        padding: 0;
    }

    .system-management-grid {
        grid-template-columns: 1fr;
    }

    .button-actions,
    .actions,
    .edit-mode-container {
        display: none !important;
    }

    .school-year-details,
    .student-archives,
    .teacher-archives,
    .users-login-activity,
    .teachers-login-activity {
        box-shadow: none;
        border: 1px solid #ddd;
        page-break-inside: avoid;
    }
}