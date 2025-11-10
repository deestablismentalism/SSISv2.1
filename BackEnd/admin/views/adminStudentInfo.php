<?php
declare(strict_types=1);
require_once __DIR__ . '/../controllers/adminStudentsController.php';
require_once __DIR__ . '/../../core/tableDataTemplate.php';
class adminStudentInfo {
    protected $controller;
    protected $studId;
    protected $tableTemplate;
    //INFORMATION PROPERTIES
    protected $parentInfo;
    protected $studInfo;
    //GLOBAL ERROR 
    protected $isGlobal = false;
    protected $globalError = '';
    public function __construct() {
        $this->studId = isset($_GET['id']) ? (int)$_GET['id'] : null;
        $this->tableTemplate = new TableCreator();
        $this->controller = new adminStudentsController();
        $this->init();
    }
    private function init():void{
        try {
            $response = $this->controller->viewStudentInformation($this->studId);
            if(!$response['success']) {
                $this->isGlobal = true;
                $this->globalError = $response['message'];
                return;
            }
            $this->parentInfo = $response['parent'];
            $this->studInfo = $response['data'];
        }
        catch(IdNotFoundException $e) {
            $this->isGlobal = true;
            $this->globalError = $e->getMessage();
            return;
        }
    }
    public function displayGlobalError():void {
        if($this->isGlobal)  {
            echo '<div class="error-message">'.$this->globalError.'</div>';
        }
    }
    public function displayStudentInfo():void {
        try {
            if($this->globalError) {
                return;
            }
            $data = $this->studInfo;
            $lrn = !is_null($data['LRN']) ? $data['LRN']:"No LRN"  ; 
            $middleName = !is_null($data['Middle_Name'])  ? $data['Middle_Name'] : '';
            $sex = !empty($data['Sex']) ? $data['Sex'] : 'No Biological sex provided';
            $completeAddress = $data['House_Number'] .' ' .$data['Subd_Name']
                    . '. ' .$data['Brgy_Name']. ', ' .$data['Municipality_Name'] . ', '
                    . $data['Province_Name'] . ' ' . $data['Region'];
            echo '<table class="student-modal-table"><tbody>';
            echo $this->tableTemplate->returnVerticalTables([
               'Learner Reference Number', 'PSA Number', 'Last Name','First Name','Middle Name','Suffix','Birthday','Age',
                 'Sex','Email','Religion','Native Language','Cultural Group',
                'Special Condition','Assistive Technology','Address'
            ],
            [$lrn,$data['Psa_Number'],$data['Last_Name'],$data['First_Name'],$data['Middle_Name'], $data['Suffix']
            ,$data['Birthday'],$data['Age'],$data['Sex'],$data['Student_Email'],$data['Religion'],$data['Native_Language'],
            $data['Has_Cultural'],$data['Has_Condition'],$data['Has_Tech'],$completeAddress], 'student-info');
            echo '</tbody></table>';
        }
        catch(Throwable $t) {
            error_log("[".date('Y-m-d H:i:s')."]" .$t."\n",3, __DIR__ . '/../../errorLogs.txt');
            echo '<div class="error-message"> There was a syntax problem. Please wait for this to be fixed </div>';
        }
    }
    public function displayParentInfo():void {
        try {
            if($this->globalError) {
                return;
            }
            $data = $this->parentInfo;
            echo '<table class="modal-table"></tbody>';
            foreach($data as $rows) {
                echo $this->tableTemplate->returnVerticalTables(
                    ['Relasyon', 'Apleyido','Pangalan','Panggitna','Educational attainment','Numero ng telepono', 'Kabilang sa 4ps'],
                    [$rows['Parent_Type'],$rows['Last_Name'] ,$rows['First_Name'] 
                    ,$rows['Middle_Name'],$rows['Educational_Attainment'],$rows['Contact_Number'],$rows['Is_4Ps']],
                    'parent-info'
                );
            }
            echo '</tbody></table>';
        }
        catch(Throwable $t) {
            error_log("[".date('Y-m-d H:i:s')."]" .$t."\n",3, __DIR__ . '/../../errorLogs.txt');
            echo '<div class="error-message"> There was a syntax problem. Please wait for this to be fixed </div>';
        }
    }
}