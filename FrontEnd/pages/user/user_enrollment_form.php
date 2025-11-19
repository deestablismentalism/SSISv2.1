<?php
ob_start();

$pageTitle = 'SSIS-Enrollment Form';
$pageCss = '<link rel="stylesheet" href="../../assets/css/user/user-enrollment-form.css" media="all">';
$pageCss2 = '<link rel="stylesheet" href="../../assets/css/user/user-enrollment-form-errors.css" media="all">';
$pageCss3 = '<link rel="stylesheet" href="../../assets/css/user/user-enrollment-form-mq.css" media="all">';
$pageCss4 = '<link rel="stylesheet" href="../../assets/css/notifications.css" media="all">';
$pageJs = '<script type="module" src="../../assets/js/user/user-enrollment-form.js" defer></script>';
$pageJs2 = '<script src="../../assets/js/notifications.js" defer></script>';
require_once __DIR__ .'/../../../BackEnd/common/getGradeLevels.php';
require_once __DIR__ . '/../../../BackEnd/common/isAcademicYearSet.php';
$set = new isAcademicYearSet();
$view = new getGradeLevels();
?>
<div class="enrollment-form-content">
    <div class="content-title">
        <p>Learner's Enrollment Form</p>
    </div>

    
    <!-- DISABILITY MODAL - Shows on page load -->
    <div id="disability-modal" class="disability-modal-overlay">
        <div class="disability-modal-content">
            <div class="disability-modal-header">
                <h2>PARA SA MGA MAG-AARAL NA MAY KAPANSANAN</h2>
            </div>
            <div class="disability-modal-body">
                <!-- Step 1: Has Disability -->
                <div class="has-disability">
                    <p class="dfont">Mayroon bang kapansanan ang mag-aaral? <span class="required">*</span></p>
                    <div>
                        <div class="radio-option">
                            <input type="radio" name="has-disability" id="has-disability-yes" value="Yes" class="radio" required>
                            <label for="has-disability-yes">Mayroon</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="has-disability" id="has-disability-no" value="No" class="radio" required>
                            <label for="has-disability-no">Wala</label>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Can Read/Write (appears if Yes to disability) -->
                <div class="can-read-write" style="display: none;">
                    <p class="dfont">Nakakabasa at nakakasulat ba ang mag-aaral? <span class="required">*</span></p>
                    <div>
                        <div class="radio-option">
                            <input type="radio" name="can-read-write" id="can-read-write-yes" value="Yes" class="radio">
                            <label for="can-read-write-yes">Oo</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="can-read-write" id="can-read-write-no" value="No" class="radio">
                            <label for="can-read-write-no">Hindi</label>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Disability Details (appears if Yes to read/write) -->
                <div class="disability-details" style="display: none;">
                    <div class="disability-description">
                        <div class="error-msg">
                            <span class="em-disability-desc"></span>
                        </div>
                        <p class="dfont">Ano ang kapansanan ng mag-aaral? <span class="required">*</span></p>
                        <input type="text" name="disability-description" id="disability-description" class="textbox" placeholder="Halimbawa: Visual Impairment, Hearing Impairment, Physical Disability, etc.">
                    </div>
                    <div class="assistive-tech">
                        <div class="error-msg">
                            <span class="em-assistive-tech"></span>
                        </div>
                        <p class="dfont">Gumagamit ba ng assistive technology? (Isulat "N/A" kung wala) <span class="required">*</span></p>
                        <input type="text" name="assistive-technology" id="assistive-technology" class="textbox" placeholder="Halimbawa: Braille, Wheelchair, Hearing Aid, o N/A">
                    </div>
                </div>
            </div>
            <div class="disability-modal-footer">
                <button type="button" class="disability-modal-btn" id="disability-modal-continue" disabled>Magpatuloy</button>
            </div>
        </div>
    </div>

    <!-- Popup Modal for Template (appears if No to read/write) -->
    <div id="disability-template-popup" class="popup-overlay" style="display: none;">
        <div class="popup-content">
            <div class="popup-header">
                <h2>Paalala para sa mga Magulang/Tagapagalaga</h2>
            </div>
            <div class="popup-body">
                <p><strong>Sa mga magulang o tagapagalaga ng mga mag-aaral na may kapansanan at hindi nakakabasa o nakakasulat:</strong></p>
                <p>Mangyaring makipag-ugnayan sa aming tanggapan upang matulungan kayo sa proseso ng enrollment. Mayroon kaming espesyal na tulong at gabay para sa inyong sitwasyon.</p>
                <p><strong>Mga Contact Details:</strong></p>
                <ul>
                    <li>Telepono: [School Contact Number]</li>
                    <li>Email: [School Email]</li>
                    <li>Oras ng Tanggapan: Lunes - Biyernes, 8:00 AM - 5:00 PM</li>
                </ul>
                <p>Salamat sa inyong pag-unawa.</p>
            </div>
            <div class="popup-footer">
                <button type="button" class="popup-btn" id="confirm-disability-popup">Naiintindihan Ko</button>
            </div>
        </div>
    </div>

    <div class="content-wrapper">
        <form id="enrollment-form" class="form-main" enctype="multipart/form-data">
            <!-- Hidden fields for disability data mapping -->
            <input type="hidden" name="sn" id="sn-hidden" value="0">
            <input type="hidden" name="boolsn" id="boolsn-hidden" value="">
            <input type="hidden" name="at" id="at-hidden" value="0">
            <input type="hidden" name="atdevice" id="atdevice-hidden" value="">
            
            <!--ANTAS AT IMPORMASYON NG PAARALAN-->
            <div class="previous-school border-75">
                <div class="previous-school-title">
                    <span class="title">ANTAS NG IMPORMASYON NG PAARALAN</span>
                </div>
                <div class="previous-school-row-1">
                <div class="school-year">
                        <p class="dfont-acad-year">Taong Panuruan <span class="required">*</span></p>
                        <!--ERROR MESSAGES DIVS FOR WRONG INPUTS -DAVID -->
                        <div class="error-msg">
                            <span class="em-start-year"> Error Message here.</span>
                        </div>
                        <div class="academic-year-input">
                            <div class="acad-year-tbox">
                                <input type="number" name="start-year" id="start-year" class="textbox">
                                <p> - </p>
                                <input type="number" name="end-year" id="end-year" class="textbox">
                            </div>
                        </div>
                    </div>
                    <div class="learner-radio">
                        <p class="dfont">I-check lamang naaangkop <span class="required">*</span></p>
                        <div class="lrn-radio-buttons-selections">
                            <div class="radio-option">
                                <input type="radio" id="no-lrn" name="bool-LRN" value="0" class="radio">
                                <label for="no-lrn">Walang LRN</label>
                            </div>
                            <div class="radio-option">
                                <input type="radio" id="with-lrn" name="bool-LRN" value="1" class="radio">
                                <label for="with-lrn">Mayroong LRN</label>
                            </div>
                            <div class="radio-option">
                                <input type="radio" id="returning" name="bool-LRN" value="0" class="radio">
                                <label for="returning">Returning (Balik Aral)</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="previous-school-wrapper">
                    
                    <div class="grade">
                        <div class="enrolling-grade-level-container">
                            <div class="error-msg">
                                <span class="em-enrolling-grade-level"> Error Message Here. </span>
                            </div>
                            <p class="dfont">Baitang na nais ipatala <span class="required">*</span></p>
                            <select name="grades-tbe" id="grades-tbe" class="select">
                                <option value=""> Select a grade level </option>
                                <?php
                                    $view->createSelectValues();
                                ?>
                            </select>
                        </div>
                        <div class="last-grade-level-container">
                            <div class="error-msg">
                                <span class="em-last-grade-level"> Error Message Here. </span>
                            </div>
                            <p class="dfont">Huling baitang na natapos <span class="required">*</span></p>
                            <select name="last-grade" id="last-grade" class="select">
                                <option value=""> Select a grade level </option>
                                <?php 
                                    $view->createSelectValues();
                                ?>
                            </select>
                        </div>
                        <div class="last-year-finished">
                            <div class="error-msg">
                                <span class="em-last-year-finished"> Error Message Here. </span>
                            </div>
                            <p class="dfont">Huling natapos na taon <span class="required">*</span></p>
                            <input type="number" name="last-year" id="last-year" class="textbox">
                        </div>
                    </div>
                    <div class="heducation">
                        <div class="lschool-wrapper">
                            <div class="last-school">
                                <div class="error-msg">
                                    <span class="em-lschool"> Error Message Here. </span>
                                </div>
                                <p class="dfont">Huling paaralang pinasukan <span class="required">*</span></p>
                                <input type="text" name="lschool" id="lschool" class="textbox" placeholder="South II Elementary School">
                            </div>
                                <div class="lschoolID">
                                    <div class="error-msg">
                                        <span class="em-lschoolID"> Error Message Here. </span>
                                    </div>
                                <p class="dfont">ID ng paaralan</p>
                                <input type="number" name="lschoolID" id="lschoolID" class="textbox">
                            </div>
                        </div>
                        <div class="last-school-address">
                            <div class="error-msg">
                                <span class="em-lschoolAddress"> Error Message Here.</span>
                            </div>
                            <p class="dfont">Address ng paaralan <span class="required">*</span></p>
                            <input type="text" name="lschoolAddress" id="lschoolAddress" class="textbox"> 
                        </div> 
                        <p class="dfont">Anong klase ng paaralan</p>
                        <div> 
                            <div class="radio-option">
                                <input type="radio" name="school-type" id="private" class="radio" value="Private">
                                <label for="private">Pribado</label>
                            </div>
                            <div class="radio-option">
                                <input type="radio" name="school-type" id="public" class="radio" value="Public">
                                <label for="public">Pampubliko</label>
                            </div>
                        </div>
                    </div>
                    <div class="nais-paaralan">
                        <div class="fschool-wrapper">
                            <div class="fschool">
                                <div class="error-msg">
                                    <span class="em-fschool"> Error Message Here. </span>
                                </div>
                                <p class="dfont">Nais na paaralan <span class="required">*</span></p>
                                <input type="text" name="fschool" id="fschool" class="textbox" placeholder="South II Elementary School">
                            </div>
                            <div class="fschoolID">
                                <div class="error-msg">
                                    <span class="em-fschoolID"> Error Message Here. </span>
                                </div>
                                <p class="dfont">ID ng paaralan</p>
                                <input type="number" name="fschoolID" id="fschoolID" class="textbox">
                            </div>
                        </div>
                        <div>
                            <div class="error-msg">
                                <span class="em-fschoolAddress"> Error Message Here. </span>
                            </div>
                            <p class="dfont">Address ng paaralan <span class="required">*</span></p>
                        <input type="text" name="fschoolAddress" id="fschoolAddress" class="textbox">
                        </div>
                    </div>
                </div>
            </div>
            <!--IMPORMASYON NG STUDYANTE-->
            <div class="student-information border-75">
                <div class="student-information-title">
                    <span class="title">IMPORMASYON NG ESTUDYANTE</span>
                </div>
                <!--ROW 1-->
                <div class="student-info-row-1">
                    <div class="LRN">
                        <div class="error-msg">
                            <span class="em-LRN"></span>
                        </div>
                        <p class="dfont">Learner's Reference Number (LRN) kung mayroon <span class="required">*</span></p>
                        <input type="number" name="LRN" id="LRN" class="textbox">
                    </div>
                </div>
                <div class="student-information-wrapper">
                    <!--ROW 2-->
                    <div class="student-info-row-2">
                        <div class="lname">
                            <div class="error-msg">
                                <span class="em-lname"></span>
                            </div>
                            <p class="dfont">Apelyido <span class="required">*</span></p>
                            <input type="text" name="lname" id="lname" class="textbox" placeholder="Dela Cruz">
                        </div>
                        <div class="fname">
                            <div class="error-msg">
                                <span class="em-fname"></span>
                            </div>
                            <p class="dfont">Pangalan <span class="required">*</span></p>
                            <input type="text" name="fname" id="fname" class="textbox" placeholder="John Mark">
                        </div>
                        <div class="mname">
                            <div class="error-msg">
                                <span class="em-mname"></span>
                            </div>
                            <p class="dfont">Gitnang Pangalan</p>
                            <input type="text" name="mname" id="mname" class="textbox" placeholder="Jimenez" data-optional="true">
                        </div>
                        <div class="extension">
                            <div class="error-msg">
                                <span class="em-extension"></span>
                            </div>
                            <p class="dfont">Extensyon(Jr., Sr.)</p>
                            <input type="text" name="extension" id="extension" class="textbox" placeholder="III" data-optional="true">
                        </div>
                    </div>
                    <!--ROW 3-->
                    <div class="student-info-row-3">
                        <div class="bday">
                            <div class="error-msg">
                                <span class="em-bday"></span>
                            </div>
                            <p class="dfont">Petsa ng Kapanganakan <span class="required">*</span></p>
                            <input type="date" name="bday" id="bday" class="textbox">
                        </div>
                        <div class="age">
                            <div class="error-msg">
                                <span class="em-age"></span>
                            </div>
                            <p class="dfont">Edad <span class="required">*</span></p>
                            <input type="text" name="age" id="age" class="textbox" readonly>
                        </div>
                        <div class="gender-group-wrapper">
                            <div class="gender">
                                <p class="dfont">Kasarian <span class="required">*</span></p>
                                <div> 
                                    <div class="radio-option">
                                        <input type="radio" name="gender" id="male" class="radio" value="Male">
                                        <label for="male">Lalake</label>
                                    </div>
                                    <div class="radio-option">
                                        <input type="radio" name="gender" id="female" class="radio" value="Female">
                                        <label for="female">Babae</label>
                                    </div>
                                </div>
                            </div>
                            <div class="community">
                                <p class="dfont">Nabibilang sa katutubong grupo/ <br class="responsive-text-break">
                                            Komunidad ng Katutubong Kultural <span class="required">*</span></p>
                                <div>
                                    <div class="radio-option">
                                        <input type="radio" name="group" id="is-ethnic" class="radio" value="1">
                                        <label for="is-ethnic">Oo</label>
                                    </div>
                                    <div class="radio-option">
                                        <input type="radio" name="group" id="not-ethnic" class="radio" value="0">
                                        <label for="not-ethnic">Hindi</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="true-community">
                            <div class="error-msg">
                                <span class="em-community"></span>
                            </div>
                            <p class="dfont">Kung oo, saang grupo nabilang</p>
                            <input type="text" name="community" id="community" class="textbox" placeholder="sama-badjau">
                        </div>
                    </div>
                    <!--ROW 4-->
                    <div class="student-info-row-4">
                        <div class="native-language">
                            <div class="language">
                                <div class="error-msg">
                                    <span class="em-language"></span>
                                </div>
                                <p class="dfont">Kinagisnang wika <span class="required">*</span></p>
                                <input type="text" name="language" id="language" class="textbox" placeholder="Tagalog">
                            </div>
                        </div>
                        <div class="religion">
                            <div class="error-msg">
                                <span class="em-religion"></span>
                            </div>
                            <p class="dfont">Relihiyon <span class="required">*</span></p>
                            <input type="text" name="religion" id="religion" class="textbox" placeholder="Catholic">
                        </div>
                        <div class="email">
                            <div class="error-msg">
                                <span class="em-email"></span>
                            </div>
                            <p class="dfont">Email Address</p>
                            <input type="email" name="email" id="email" class="textbox" placeholder="sampleemail@gmail.com" data-optional="true">
                        </div>
                    </div>
                </div>
            </div>
            <!--TIRAHAN-->
            <div class="address border-75">
                <div class="address-title">
                    <span class="title">TIRAHAN</span>
                </div>
                <div class="address-wrapper">
                    <div class="region">
                        <div class="error-msg">
                            <span class="em-region"></span>
                        </div>
                        <p class="dfont">Rehiyon <span class="required">*</span></p>
                        <input type="hidden" name="region-name" id="region-name">
                        <select name="region" id="region" class="textbox" >
                            
                        </select>
                    </div>
                    <div class="province">
                        <div class="error-msg">
                            <span class="em-province"></span>
                        </div>
                        <p class="dfont">Probinsya/lalawigan <span class="required">*</span></p>
                        <input type="hidden" name="province-name" id="province-name">
                        <select name="province" id="province" class="textbox">
                            
                        </select>
                    </div>
                    <div class="city">
                        <div class="error-msg">
                            <span class="em-city"></span>
                        </div>
                        <p class="dfont">Lungsod/Munisipalidad <span class="required">*</span></p>
                        <input type="hidden" name="city-municipality-name" id="city-municipality-name">
                        <select name="city-municipality" id="city-municipality" class="textbox">
                            
                        </select>
                    </div>
                    <div class="barangay">
                        <div class="error-msg">
                            <span class="em-barangay"></span>
                        </div>
                        <p class="dfont">Barangay <span class="required">*</span></p>
                        <input type="hidden" name="barangay-name" id="barangay-name">
                        <select name="barangay" id="barangay" class="textbox">
                            
                        </select>
                    </div>
                    <div class="subdivision">
                        <div class="error-msg">
                            <span class="em-subdivision"></span>
                        </div>
                        <p class="dfont">Subdivision/ baryo/ purok/ sitio</p>
                        <input type="text" name="subdivision" id="subdivision" class="textbox" placeholder="Talipan">
                    </div>
                    <div class="house-number">
                        <div class="error-msg">
                            <span class="em-house-number"></span>
                        </div>
                        <p class="dfont">Numero ng Bahay at kalye</p>
                        <input type="text" name="house-number" id="house-number" class="textbox" placeholder="32">
                    </div>
                </div>
            </div>

            <!--IMPORMASYON NG MAGULANG/TAGAPAGALAGA-->
            <div class="parents-guardian-information border-75">
                <div class="parents-guardian-information-title">
                    <span class="title">IMPORMASYON NG TAGAPAGALAGA</span>
                </div>
                <!-- Nagdagdag ako ng seperate names dito dabid (Kinit)-->
                <div class="parents-guardian-information-wrapper">
                    <!-- Nagdagdag ako ng seperate names dito dabid (Kinit)-->
                    <div class="G-fullname">
                        <div class="error-msg">
                            <span class="em-guardian-last-name"></span>
                        </div>
                        <p class="dfont">Apilyedo <span class="required">*</span></p>
                        <input type="text" class="textbox" name="Guardian-Last-Name" id="Guardian-Last-Name" placeholder="Dela Cruz">
                    </div>
                    <div class="Guardian-Middle-Name">
                        <div class="error-msg">
                            <span class="em-guardian-middle-name"></span>
                        </div>
                        <p class="dfont">Gitnang Pangalan</p>
                        <input type="text" class="textbox" name="Guardian-Middle-Name" id="Guardian-Middle-Name" placeholder="Jimenez">
                    </div>
                    <div class="Guardian-First-Name">
                        <div class="error-msg">
                            <span class="em-guardian-first-name"></span>
                        </div>
                        <p class="dfont">Pangalan <span class="required">*</span></p>
                        <input type="text" class="textbox" name="Guardian-First-Name" id="Guardian-First-Name" placeholder="Maria">
                    </div>
                    <div class="G-highest-education">
                        <label for="G-highest-education">Pinakamataas na antas na natapos sa pag-aaral <span class="required">*</span></label><br>
                        <select name="G-highest-education" id="G-highest-education" class="select">
                            <option value="Hindi Nakapag-aral">Hindi Nakapag-aral</option>
                            <option value="Hindi Nakapag-aral pero marunong magbasa at magsulat">Hindi Nakapag-aral pero marunong magbasa at magsulat</option>
                            <option value="Nakatuntong ng Elementarya">Nakatuntong ng Elementarya</option>
                            <option value="Nakapagtapos ng Elementarya">Nakapagtapos ng Elementarya</option>
                            <option value="Nakatuntong ng Sekundarya">Nakatuntong ng Sekundarya</option>
                            <option value="Nakapagtapos ng Sekundarya">Nakapagtapos ng Sekundarya</option>
                            <option value="Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal /Bokasyonal">Nakapag-aral Pagkatapos ng Sekundarya o ng Teknikal/Bokasyonal</option>
                        </select>
                    </div>
                    <div class="G-Relationship">
                        <label for="G-Relationship">Relasyon sa Mag-aaral <span class="required">*</span></label><br>
                        <select name="G-Relationship" id="G-Relationship" class="select">
                            <option value="Magulang">Magulang</option>
                            <option value="Lola">Lola</option>
                            <option value="Lolo">Lolo</option>
                            <option value="Tiya">Tiya</option>
                            <option value="Kapatid">Kapatid</option>
                            <option value="Ninang">Ninang</option>
                            <option value="Ninong">Ninong</option>
                            <option value="Iba pa">Iba pa</option>
                        </select>
                    </div>
                    <div class="G-number">
                        <div class="error-msg">
                            <span class="em-g-number"></span>
                        </div>
                        <p class="dfont">Numero sa telepono (cellphone/ telephone/) <span class="required">*</span></p>
                        <input type="text" name="G-Number" id="G-number" class="textbox" placeholder="09123456789">
                    </div> 
                </div>
            </div>
            <!--4PS, IMAGES AND SUBMIT BUTTON-->            
            <div class="confirmation border-75">
                <div class="fourPS">
                    <p class="dfont">Kabilang ba ang inyong pamilya sa 4Ps ng DSWD? <span class="required">*</span></p>
                    <div>
                        <div class="radio-option">
                            <input type="radio" name="fourPS" id="is-4ps" class="radio" value="yes">
                            <label for="is-4ps">Oo</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="fourPS" id="not-4ps" class="radio" value="no">
                            <label for="not-4ps">Hinde</label>
                        </div>
                    </div>
                </div>
                <div class="image-confirm">
                    <p class="dfont">Ipasa ang malinaw na larawan ng <b>REPORT CARD <span class="required">*</span></b></p>
                    <label for="report-card-front" style="display: block; margin-bottom: 8px;">Front Side <span class="required">*</span></label>
                    <input type="file" id="report-card-front" name="report-card-front" accept="image/jpeg,image/jpg,image/png" required style="margin-bottom: 15px;"> 
                    <label for="report-card-back" style="display: block; margin-bottom: 8px;">Back Side <span class="required">*</span></label>
                    <input type="file" id="report-card-back" name="report-card-back" accept="image/jpeg,image/jpg,image/png" required> 
                </div>
                <?php if($set->isSet()): ?>
                    <button type="submit" class="submit-button" >Submit</button>
                <?php else: ?>
                    <button class="submit-button" style="opacity:0.5; background-color: gray; pointer-events:none;" disabled>Submit</button>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>
<div class="error-message" id="error-message"></div>
<div class="success-message" id="success-message"></div>
<?php
$pageContent = ob_get_clean();
require_once __DIR__ . '/./user_base_designs.php';
?>
