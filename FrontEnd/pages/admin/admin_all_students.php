<?php
ob_start(); 
    $pageTitle = "Admin Students";
    $pageCss = '<link rel="stylesheet" href="../../assets/css/admin/admin-all-students.css">
                <link rel="stylesheet" href="../../assets/css/admin/admin-enrollment-modal.css">';
    $pageJs = '<script src="../../assets/js/admin/admin-all-students.js" defer></script>
                <script type="module" src="../../assets/js/admin/admin-enrollment-modal.js" defer></script>';
    require_once __DIR__ .'/../../../BackEnd/common/getGradeLevels.php';
    $gradeView = new getGradeLevels();
?>
   <div class="admin-all-students-content">
        <div class="table-title">
            <div class="table-title-left">
                <h2>Students</h2>
            </div>
            <div class="table-title-right">                
                <button class="add-student-btn" aria-label="Add new student">
                    <img src="../../assets/imgs/plus-solid.svg" alt="Add">
                </button>
                <select id="filter-grade" class="filter-select" aria-label="Filter by grade">
                    <option value="">All Grades</option>
                </select>
                <select id="filter-status" class="filter-select" aria-label="Filter by status">
                    <option value="">All Statuses</option>
                </select>
                <select id="filter-section" class="filter-select" aria-label="Filter by section">
                    <option value="">All Sections</option>
                </select>
                <input type="text" id="search" class="search-box" placeholder="Search student..." aria-label="Search students">
            </div>
        </div>

        <div class="table-container">
            <table class="students">
                <thead> 
                    <tr>
                        <th>Student Name</th>
                        <th>Student LRN</th>
                        <th>Grade Level</th>
                        <th>Section</th>
                        <th>Student Birthdate</th>
                        <th>Student Status</th>
                        <th>Student Actions</th>
                    </tr>
                </thead>
                <tbody class="student-info">
                    <?php 
                        require_once __DIR__ . '/../../../BackEnd/admin/views/adminStudentsView.php';
                        $view = new adminStudentsView();
                        $view->displayStudents();
                    ?>
                </tbody>
            </table>
        </div>
   </div>

   <!-- Admin Manual Enrollment Modal -->
   <div id="admin-enrollment-modal" class="modal-overlay" style="display: none;">
       <div class="modal-container">
           <div class="modal-header">
               <h2>Manual Student Enrollment</h2>
               <span class="close-modal">&times;</span>
           </div>
           <div class="modal-body">
               <form id="admin-enrollment-form" method="POST" enctype="multipart/form-data">
                   <!-- Previous School Section -->
                   <div class="form-section">
                       <h3 class="section-title">ANTAS NG IMPORMASYON NG PAARALAN</h3>
                       <div class="form-row">
                           <div class="form-group">
                               <label>Taong Panuruan <span class="required">*</span></label>
                               <div class="error-msg"><span class="em-start-year"></span></div>
                               <div class="year-inputs">
                                   <input type="number" name="start-year" id="admin-start-year" class="form-input">
                                   <span>-</span>
                                   <input type="number" name="end-year" id="admin-end-year" class="form-input">
                               </div>
                           </div>
                           <div class="form-group">
                               <label>I-check lamang naaangkop <span class="required">*</span></label>
                               <div class="radio-group">
                                   <div class="radio-option">
                                       <input type="radio" id="admin-no-lrn" name="bool-LRN" value="0" class="radio">
                                       <label for="admin-no-lrn">Walang LRN</label>
                                   </div>
                                   <div class="radio-option">
                                       <input type="radio" id="admin-with-lrn" name="bool-LRN" value="1" class="radio" checked>
                                       <label for="admin-with-lrn">Mayroong LRN</label>
                                   </div>
                                   <div class="radio-option">
                                       <input type="radio" id="admin-returning" name="bool-LRN" value="0" class="radio">
                                       <label for="admin-returning">Returning (Balik Aral)</label>
                                   </div>
                               </div>
                           </div>
                       </div>
                       <div class="form-row">
                           <div class="form-group">
                               <label>Baitang na nais ipatala <span class="required">*</span></label>
                               <div class="error-msg"><span class="em-enrolling-grade-level"></span></div>
                               <select name="grades-tbe" id="admin-grades-tbe" class="form-select">
                                   <option value="">Select a grade level</option>
                                   <?php $gradeView->createSelectValues(); ?>
                               </select>
                           </div>
                           <div class="form-group">
                               <label>Huling baitang na natapos <span class="required">*</span></label>
                               <div class="error-msg"><span class="em-last-grade-level"></span></div>
                               <select name="last-grade" id="admin-last-grade" class="form-select">
                                   <option value="">Select a grade level</option>
                                   <?php $gradeView->createSelectValues(); ?>
                               </select>
                           </div>
                           <div class="form-group">
                               <label>Huling natapos na taon <span class="required">*</span></label>
                               <div class="error-msg"><span class="em-last-year-finished"></span></div>
                               <input type="number" name="last-year" id="admin-last-year" class="form-input">
                           </div>
                       </div>
                       <div class="form-row">
                           <div class="form-group">
                               <label>Huling paaralang pinasukan <span class="required">*</span></label>
                               <div class="error-msg"><span class="em-lschool"></span></div>
                               <input type="text" name="lschool" id="admin-lschool" class="form-input">
                           </div>
                           <div class="form-group">
                               <label>ID ng paaralan</label>
                               <div class="error-msg"><span class="em-lschoolID"></span></div>
                               <input type="number" name="lschoolID" id="admin-lschoolID" class="form-input">
                           </div>
                       </div>
                       <div class="form-group">
                           <label>Address ng paaralan <span class="required">*</span></label>
                           <div class="error-msg"><span class="em-lschoolAddress"></span></div>
                           <input type="text" name="lschoolAddress" id="admin-lschoolAddress" class="form-input">
                       </div>
                       <div class="form-group">
                           <label>Anong klase ng paaralan <span class="required">*</span></label>
                           <div class="radio-group">
                               <div class="radio-option">
                                   <input type="radio" name="school-type" id="admin-private" class="radio" value="Private">
                                   <label for="admin-private">Pribado</label>
                               </div>
                               <div class="radio-option">
                                   <input type="radio" name="school-type" id="admin-public" class="radio" value="Public" checked>
                                   <label for="admin-public">Pampubliko</label>
                               </div>
                           </div>
                       </div>
                       <div class="form-row">
                           <div class="form-group">
                               <label>Nais na paaralan <span class="required">*</span></label>
                               <div class="error-msg"><span class="em-fschool"></span></div>
                               <input type="text" name="fschool" id="admin-fschool" class="form-input">
                           </div>
                           <div class="form-group">
                               <label>ID ng paaralan</label>
                               <div class="error-msg"><span class="em-fschoolID"></span></div>
                               <input type="number" name="fschoolID" id="admin-fschoolID" class="form-input">
                           </div>
                       </div>
                       <div class="form-group">
                           <label>Address ng paaralan <span class="required">*</span></label>
                           <div class="error-msg"><span class="em-fschoolAddress"></span></div>
                           <input type="text" name="fschoolAddress" id="admin-fschoolAddress" class="form-input">
                       </div>
                   </div>

                   <!-- Student Information Section -->
                   <div class="form-section">
                       <h3 class="section-title">IMPORMASYON NG ESTUDYANTE</h3>
                       <div class="form-row">
                           <div class="form-group">
                               <label>Learner Reference Number (LRN) <span class="required">*</span></label>
                               <div class="error-msg"><span class="em-LRN"></span></div>
                               <input type="number" name="LRN" id="admin-LRN" class="form-input">
                           </div>
                       </div>
                       <div class="form-row">
                           <div class="form-group">
                               <label>Apelyido <span class="required">*</span></label>
                               <div class="error-msg"><span class="em-lname"></span></div>
                               <input type="text" name="lname" id="admin-lname" class="form-input">
                           </div>
                           <div class="form-group">
                               <label>Pangalan <span class="required">*</span></label>
                               <div class="error-msg"><span class="em-fname"></span></div>
                               <input type="text" name="fname" id="admin-fname" class="form-input">
                           </div>
                           <div class="form-group">
                               <label>Gitnang Pangalan</label>
                               <div class="error-msg"><span class="em-mname"></span></div>
                               <input type="text" name="mname" id="admin-mname" class="form-input" data-optional="true">
                           </div>
                           <div class="form-group">
                               <label>Extensyon (Jr., Sr.)</label>
                               <div class="error-msg"><span class="em-extension"></span></div>
                               <input type="text" name="extension" id="admin-extension" class="form-input" data-optional="true">
                           </div>
                       </div>
                       <div class="form-row">
                           <div class="form-group">
                               <label>Petsa ng Kapanganakan <span class="required">*</span></label>
                               <div class="error-msg"><span class="em-bday"></span></div>
                               <input type="date" name="bday" id="admin-bday" class="form-input">
                           </div>
                           <div class="form-group">
                               <label>Edad <span class="required">*</span></label>
                               <div class="error-msg"><span class="em-age"></span></div>
                               <input type="text" name="age" id="admin-age" class="form-input" readonly>
                           </div>
                       </div>
                       <div class="form-row">
                           <div class="form-group">
                               <label>Kasarian <span class="required">*</span></label>
                               <div class="radio-group">
                                   <div class="radio-option">
                                       <input type="radio" name="gender" id="admin-male" class="radio" value="Male">
                                       <label for="admin-male">Lalake</label>
                                   </div>
                                   <div class="radio-option">
                                       <input type="radio" name="gender" id="admin-female" class="radio" value="Female" checked>
                                       <label for="admin-female">Babae</label>
                                   </div>
                               </div>
                           </div>
                           <div class="form-group">
                               <label>Nabibilang sa katutubong grupo <span class="required">*</span></label>
                               <div class="radio-group">
                                   <div class="radio-option">
                                       <input type="radio" name="group" id="admin-is-ethnic" class="radio" value="1" checked>
                                       <label for="admin-is-ethnic">Oo</label>
                                   </div>
                                   <div class="radio-option">
                                       <input type="radio" name="group" id="admin-not-ethnic" class="radio" value="0">
                                       <label for="admin-not-ethnic">Hindi</label>
                                   </div>
                               </div>
                           </div>
                       </div>
                       <div class="form-group">
                           <label>Kung oo, saang grupo nabilang</label>
                           <div class="error-msg"><span class="em-community"></span></div>
                           <input type="text" name="community" id="admin-community" class="form-input">
                       </div>
                       <div class="form-row">
                           <div class="form-group">
                               <label>Kinagisnang wika <span class="required">*</span></label>
                               <div class="error-msg"><span class="em-language"></span></div>
                               <input type="text" name="language" id="admin-language" class="form-input">
                           </div>
                           <div class="form-group">
                               <label>Relihiyon <span class="required">*</span></label>
                               <div class="error-msg"><span class="em-religion"></span></div>
                               <input type="text" name="religion" id="admin-religion" class="form-input">
                           </div>
                           <div class="form-group">
                               <label>Email Address</label>
                               <div class="error-msg"><span class="em-email"></span></div>
                               <input type="email" name="email" id="admin-email" class="form-input" data-optional="true">
                           </div>
                       </div>
                   </div>

                   <!-- Disability Section -->
                   <div class="form-section">
                       <h3 class="section-title">PARA SA MGA MAG-AARAL NA MAY KAPANSANAN</h3>
                       <div class="form-group">
                           <label>Nangangailangan ng espesyal na tulong? <span class="required">*</span></label>
                           <div class="radio-group">
                               <div class="radio-option">
                                   <input type="radio" name="sn" id="admin-is-disabled" class="radio" value="1" checked>
                                   <label for="admin-is-disabled">Mayroon</label>
                               </div>
                               <div class="radio-option">
                                   <input type="radio" name="sn" id="admin-not-disabled" class="radio" value="0">
                                   <label for="admin-not-disabled">Wala</label>
                               </div>
                           </div>
                       </div>
                       <div class="form-group">
                           <label>Kung MAYROON, isulat kung ano</label>
                           <div class="error-msg"><span class="em-boolsn"></span></div>
                           <input type="text" name="boolsn" id="admin-boolsn" class="form-input">
                       </div>
                       <div class="form-group">
                           <label>May assistive technology devices? <span class="required">*</span></label>
                           <div class="radio-group">
                               <div class="radio-option">
                                   <input type="radio" name="at" id="admin-has-assistive-tech" class="radio" value="1" checked>
                                   <label for="admin-has-assistive-tech">Oo</label>
                               </div>
                               <div class="radio-option">
                                   <input type="radio" name="at" id="admin-no-assistive-tech" class="radio" value="0">
                                   <label for="admin-no-assistive-tech">Hindi</label>
                               </div>
                           </div>
                       </div>
                       <div class="form-group">
                           <label>Kung MAYROON, isulat kung ano</label>
                           <div class="error-msg"><span class="em-atdevice"></span></div>
                           <input type="text" name="atdevice" id="admin-atdevice" class="form-input">
                       </div>
                   </div>

                   <!-- Address Section -->
                   <div class="form-section">
                       <h3 class="section-title">TIRAHAN</h3>
                       <div class="form-row">
                           <div class="form-group">
                               <label>Rehiyon <span class="required">*</span></label>
                               <div class="error-msg"><span class="em-region"></span></div>
                               <input type="hidden" name="region-name" id="admin-region-name">
                               <select name="region" id="admin-region" class="form-select"></select>
                           </div>
                           <div class="form-group">
                               <label>Probinsya <span class="required">*</span></label>
                               <div class="error-msg"><span class="em-province"></span></div>
                               <input type="hidden" name="province-name" id="admin-province-name">
                               <select name="province" id="admin-province" class="form-select"></select>
                           </div>
                       </div>
                       <div class="form-row">
                           <div class="form-group">
                               <label>Lungsod/Munisipalidad <span class="required">*</span></label>
                               <div class="error-msg"><span class="em-city"></span></div>
                               <input type="hidden" name="city-municipality-name" id="admin-city-municipality-name">
                               <select name="city-municipality" id="admin-city-municipality" class="form-select"></select>
                           </div>
                           <div class="form-group">
                               <label>Barangay <span class="required">*</span></label>
                               <div class="error-msg"><span class="em-barangay"></span></div>
                               <input type="hidden" name="barangay-name" id="admin-barangay-name">
                               <select name="barangay" id="admin-barangay" class="form-select"></select>
                           </div>
                       </div>
                       <div class="form-row">
                           <div class="form-group">
                               <label>Subdivision/Baryo/Purok <span class="required">*</span></label>
                               <div class="error-msg"><span class="em-subdivision"></span></div>
                               <input type="text" name="subdivision" id="admin-subdivision" class="form-input">
                           </div>
                           <div class="form-group">
                               <label>Numero ng Bahay at kalye <span class="required">*</span></label>
                               <div class="error-msg"><span class="em-house-number"></span></div>
                               <input type="text" name="house-number" id="admin-house-number" class="form-input">
                           </div>
                       </div>
                   </div>

                   <!-- Parent Information Section -->
                   <div class="form-section">
                       <h3 class="section-title">IMPORMASYON NG MAGULANG/TAGAPAGALAGA</h3>
                       <div class="form-row">
                           <div class="form-group-parent">
                               <h4>AMA</h4>
                               <label>Apelyido <span class="required">*</span></label>
                               <div class="error-msg"><span class="em-father-last-name"></span></div>
                               <input type="text" name="Father-Last-Name" id="admin-Father-Last-Name" class="form-input">
                               <label>Gitnang Pangalan</label>
                               <div class="error-msg"><span class="em-father-middle-name"></span></div>
                               <input type="text" name="Father-Middle-Name" id="admin-Father-Middle-Name" class="form-input" data-optional="true">
                               <label>Pangalan <span class="required">*</span></label>
                               <div class="error-msg"><span class="em-father-first-name"></span></div>
                               <input type="text" name="Father-First-Name" id="admin-Father-First-Name" class="form-input">
                               <label>Pinakamataas na antas <span class="required">*</span></label>
                               <select name="F-highest-education" id="admin-F-highest-education" class="form-select">
                                   <option value="Hindi Nakapag-aral">Hindi Nakapag-aral</option>
                                   <option value="Hindi Nakapag-aral pero marunong magbasa at magsulat">Hindi Nakapag-aral pero marunong magbasa at magsulat</option>
                                   <option value="Nakatuntong ng Elementarya">Nakatuntong ng Elementarya</option>
                                   <option value="Nakapagtapos ng Elementarya">Nakapagtapos ng Elementarya</option>
                                   <option value="Nakatuntong ng Sekundarya">Nakatuntong ng Sekundarya</option>
                                   <option value="Nakapagtapos ng Sekundarya">Nakapagtapos ng Sekundarya</option>
                                   <option value="Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal">Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal/Bokasyonal</option>
                               </select>
                               <label>Contact Number <span class="required">*</span></label>
                               <div class="error-msg"><span class="em-f-number"></span></div>
                               <input type="text" name="F-Number" id="admin-F-number" class="form-input">
                           </div>
                           <div class="form-group-parent">
                               <h4>INA</h4>
                               <label>Apelyido <span class="required">*</span></label>
                               <div class="error-msg"><span class="em-mother-last-name"></span></div>
                               <input type="text" name="Mother-Last-Name" id="admin-Mother-Last-Name" class="form-input">
                               <label>Gitnang Pangalan</label>
                               <div class="error-msg"><span class="em-mother-middle-name"></span></div>
                               <input type="text" name="Mother-Middle-Name" id="admin-Mother-Middle-Name" class="form-input" data-optional="true">
                               <label>Pangalan <span class="required">*</span></label>
                               <div class="error-msg"><span class="em-mother-first-name"></span></div>
                               <input type="text" name="Mother-First-Name" id="admin-Mother-First-Name" class="form-input">
                               <label>Pinakamataas na antas <span class="required">*</span></label>
                               <select name="M-highest-education" id="admin-M-highest-education" class="form-select">
                                   <option value="Hindi Nakapag-aral">Hindi Nakapag-aral</option>
                                   <option value="Hindi Nakapag-aral pero marunong magbasa at magsulat">Hindi Nakapag-aral pero marunong magbasa at magsulat</option>
                                   <option value="Nakatuntong ng Elementarya">Nakatuntong ng Elementarya</option>
                                   <option value="Nakapagtapos ng Elementarya">Nakapagtapos ng Elementarya</option>
                                   <option value="Nakatuntong ng Sekundarya">Nakatuntong ng Sekundarya</option>
                                   <option value="Nakapagtapos ng Sekundarya">Nakapagtapos ng Sekundarya</option>
                                   <option value="Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal">Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal/Bokasyonal</option>
                               </select>
                               <label>Contact Number <span class="required">*</span></label>
                               <div class="error-msg"><span class="em-m-number"></span></div>
                               <input type="text" name="M-Number" id="admin-M-number" class="form-input">
                           </div>
                           <div class="form-group-parent">
                               <h4>TAGAPAGALAGA</h4>
                               <label>Apelyido <span class="required">*</span></label>
                               <div class="error-msg"><span class="em-guardian-last-name"></span></div>
                               <input type="text" name="Guardian-Last-Name" id="admin-Guardian-Last-Name" class="form-input">
                               <label>Gitnang Pangalan</label>
                               <div class="error-msg"><span class="em-guardian-middle-name"></span></div>
                               <input type="text" name="Guardian-Middle-Name" id="admin-Guardian-Middle-Name" class="form-input" data-optional="true">
                               <label>Pangalan <span class="required">*</span></label>
                               <div class="error-msg"><span class="em-guardian-first-name"></span></div>
                               <input type="text" name="Guardian-First-Name" id="admin-Guardian-First-Name" class="form-input">
                               <label>Pinakamataas na antas <span class="required">*</span></label>
                               <select name="G-highest-education" id="admin-G-highest-education" class="form-select">
                                   <option value="Hindi Nakapag-aral">Hindi Nakapag-aral</option>
                                   <option value="Hindi Nakapag-aral pero marunong magbasa at magsulat">Hindi Nakapag-aral pero marunong magbasa at magsulat</option>
                                   <option value="Nakatuntong ng Elementarya">Nakatuntong ng Elementarya</option>
                                   <option value="Nakapagtapos ng Elementarya">Nakapagtapos ng Elementarya</option>
                                   <option value="Nakatuntong ng Sekundarya">Nakatuntong ng Sekundarya</option>
                                   <option value="Nakapagtapos ng Sekundarya">Nakapagtapos ng Sekundarya</option>
                                   <option value="Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal">Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal/Bokasyonal</option>
                               </select>
                               <label>Contact Number <span class="required">*</span></label>
                               <div class="error-msg"><span class="em-g-number"></span></div>
                               <input type="text" name="G-Number" id="admin-G-number" class="form-input">
                           </div>
                       </div>
                   </div>

                   <!-- Confirmation Section -->
                   <div class="form-section">
                       <h3 class="section-title">CONFIRMATION</h3>
                       <div class="form-group">
                           <label>Kabilang ba ang pamilya sa 4Ps? <span class="required">*</span></label>
                           <div class="radio-group">
                               <div class="radio-option">
                                   <input type="radio" name="fourPS" id="admin-is-4ps" class="radio" value="yes">
                                   <label for="admin-is-4ps">Oo</label>
                               </div>
                               <div class="radio-option">
                                   <input type="radio" name="fourPS" id="admin-not-4ps" class="radio" value="no" checked>
                                   <label for="admin-not-4ps">Hindi</label>
                               </div>
                           </div>
                       </div>
                   </div>

                   <div class="modal-footer">
                       <button type="button" class="btn-cancel">Cancel</button>
                       <button type="submit" class="btn-submit">Enroll Student</button>
                   </div>
               </form>
           </div>
       </div>
   </div>
   <div class="toast-message" id="toast-message" style="display: none;"></div>

<?php 
$pageContent = ob_get_clean();
require_once __DIR__ . '/./admin_base_designs.php';
?>