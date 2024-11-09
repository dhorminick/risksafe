<?php 
session_start();
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

$export = false; #data to be exported doesn't exist yet!!

if(isset($_POST["export_data"]) && isset($_POST["export-type"]) && isset($_POST["export-id"]) && isset($_POST["export-data"])){
        $company_id = $_SESSION["company_id"];
        $file_dir = '../../';
        $message = [];
        include $file_dir.'layout/db.php';
        
        $db = $con;
        
        require '../_external/v/vendor/autoload.php';



        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        function __getControl($s, $type, $con, $company_id){
        
            if($type == 'custom'){
                $query = "SELECT * FROM as_customcontrols WHERE c_id = '$company_id' AND id = '$s' LIMIT 1";
                $result=$con->query($query);
                if ($result->num_rows > 0) {
                    $info = $result->fetch_assoc();
                    $response = ucwords($info['title']);
                }else{
                    $response = 'Error 402: Control Not Found!!';	    
                }
            }else if($type == 'recommended'){
                $query = "SELECT * FROM as_controls WHERE id = '$s' LIMIT 1";
    		    $result = $conn->query($query);
                if ($result->num_rows > 0) {
                    $info = $result->fetch_assoc();
                    $response = ucwords($info['control_name']);
                }else{
                    $response = 'Error 402: Control Not Found!!';	    
                }
            }else{
                $response = 'Error 402: Control Not Found!!';
            }
    		
    		return $response;
    	}

        function sanitizePlus($data) {
          $data = trim($data);
          $data = stripslashes($data);
          $data = strip_tags($data);
          $data = htmlspecialchars($data);
          return $data;
        }
    
        $type = sanitizePlus($_POST["export-type"]);
        $id = sanitizePlus($_POST["export-id"]);
        
        $file_ext_name = strtolower($type);
    
        $fileName = "Audit Of Control (ID: ".strtoupper($id).") Data Export - RiskSAFE - " . date('d-m-Y');
    
        #set and merge risksafe header
        $spreadsheet->getActiveSheet()->mergeCells("A1:E1"); 
        $sheet->getStyle('A1:H1')->getAlignment()->setHorizontal('center');
        $sheet->setCellValue('A1', "Audit Of Control (ID: ".strtoupper($id).") Data Export - RiskSAFE - " . date('d-m-Y'));
        
        $query = "SELECT * FROM as_auditcontrols WHERE aud_id = '$id' AND c_id = '$company_id' LIMIT 1"; #get assessment details
        $query_run = mysqli_query($con, $query);
    
        $query_risks = "SELECT * FROM as_auditcriteria WHERE aud_id = '$id' AND c_id = '$company_id' ORDER BY idcriteria DESC"; #get risks
        $query_run_risks = mysqli_query($con, $query_risks);
    
        function switchOutcome($s) {
            switch ($s) {
                case 0:
                    $approval = 'N/A';
                    break;
    
                case 1:
                    $approval = 'Pass';
                    break;
    
                case 2:
                    $approval = 'Fail';
                    break;
                default:
                    $approval = 'N/A';
                    break;
            }
    
            return $approval;
        }

        function dateToDays($freq){

            if ($freq == 7) {
                $next = 'null';
            } else if ($freq == 1) {
                $next = 1;
            } else if ($freq == 2) {
                $next = 7;
            } else if ($freq == 3) {
                $next = 14;
            } else if ($freq == 4) {
                $next = 30;
            } else if ($freq == 5) {
                $next = 180;
            } else if ($freq == 6) {
                $next = 365;
            } else {
                $next = 'error';
            }
            
            return $next;
        }
        
        function nextDate($start_date,$interval_days,$output_format){
            
            $interval_days = dateToDays($interval_days);
            
            if($interval_days == 'error'){
                return 'Not Specified';
            }else if($interval_days == 'null'){
                return 'Audited As Required';
            }else{
                $start = strtotime($start_date);
                $end = strtotime(date('Y-m-d'));
                $days_ago = ($end - $start) / 24 / 60 / 60;
                if($days_ago < 0)return date($output_format,$start);
                $remainder_days = $days_ago % $interval_days;
                if($remainder_days > 0){
                    $new_date_string = "+" . ($interval_days - $remainder_days) . " days";
                } else {
                    $new_date_string = "today";
                }
                return date($output_format,strtotime($new_date_string));
            }
        }

        function get_Frequency($freq){

            if ($freq == 7) {
                return "As Required";
            } else if ($freq == 1) {
                return "Daily Controls";
            } else if ($freq == 2) {
                return "Weekly Controls";
            } else if ($freq == 3) {
                return "Fort-Nightly Controls";
            } else if ($freq == 4) {
                return "Monthly Controls";
            } else if ($freq == 5) {
                return "Semi-Annually Controls";
            } else if ($freq == 6) {
                return "Annually Controls";
            } else {
                return "Error!!";
            }
        }

        function getEffectiveness($effe){
            if ($effe == 0) {
                return "Not Assessed";
            } else if ($effe == 1) {
                return "Not Effective";
            } else if ($effe == 2) {
                return "Effective";
            } else{
                return "Error!!";
            }
        }
    
        if(mysqli_num_rows($query_run) > 0) {
            $export = true; #data to be exported exists
            $i = 0;
            $sheet->getStyle('A4:H4')->getFont()->setBold(true); #bold header values
            
            $sheet->setCellValue('A3', 'Audit Data:');
            $sheet->getStyle('A3')->getFont()->setBold(true); #bold header values

            $sheet->setCellValue('A4', 'Company');
            $sheet->setCellValue('B4', 'Industry Type');
            $sheet->setCellValue('C4', 'Business Unit or Team');
            $sheet->setCellValue('D4', 'Process, Task or Activity');
            $sheet->setCellValue('E4', 'Audit Assessor');
            $sheet->setCellValue('F4', 'Date of Audit');
            $sheet->setCellValue('G4', 'Time');
            $sheet->setCellValue('H4', 'Site');
            $sheet->setCellValue('I4', 'Street Address');
            $sheet->setCellValue('J4', 'Building');
            $sheet->setCellValue('K4', 'Country');
            $sheet->setCellValue('L4', 'State');
            $sheet->setCellValue('M4', 'Zip Code');
            $sheet->setCellValue('N4', 'Next Audit');
    
            $rowCount = 5; #cellValue about++
            foreach($query_run as $data) {
                $i++;
                $sheet->setCellValue('A'.$rowCount, $data['con_company']);
                $sheet->setCellValue('B'.$rowCount, $data['con_industry']);
                $sheet->setCellValue('C'.$rowCount, $data['con_team']);
                $sheet->setCellValue('D'.$rowCount, $data['con_task']);
                $sheet->setCellValue('E'.$rowCount, $data['con_assessor']);
                $sheet->setCellValue('F'.$rowCount, date("m-d-Y", strtotime($data['con_date'])));
                $sheet->setCellValue('G'.$rowCount, $data["con_time"]);
                $sheet->setCellValue('H'.$rowCount, $data['con_site']);
                $sheet->setCellValue('I'.$rowCount, $data['con_street']);
                $sheet->setCellValue('J'.$rowCount, $data['con_building']);
                $sheet->setCellValue('K'.$rowCount, $data['con_state']);
                $sheet->setCellValue('L'.$rowCount, $data["con_country"]);
                $sheet->setCellValue('M'.$rowCount, $data['con_zipcode']);
                $sheet->setCellValue('N'.$rowCount, nextDate($data['con_next'], $data['con_frequency'], 'm-d-Y'));
                $rowCount++;
            }
    
            $rowCount++; #increment row count to get a blank line as seperator
            
            $sheet->setCellValue('A'.$rowCount, 'Audited Control Details:');
            $sheet->getStyle('A'.$rowCount)->getFont()->setBold(true); #bold header values
            $rowCount++;

            $sheet->setCellValue('A'.$rowCount, 'Control');
            $sheet->setCellValue('B'.$rowCount, 'Control Rationale');
            $sheet->setCellValue('C'.$rowCount, 'Control Root Cause');
            $sheet->setCellValue('D'.$rowCount, 'Control Effectiveness');
            $sheet->setCellValue('E'.$rowCount, 'Frequency Of Application (FoA)');
    
            $sheet->getStyle('A'.$rowCount.':E'.$rowCount)->getFont()->setBold(true); #bold header values
            
            $control_type = $data['control_type'];
            if($control_type == 'null' || $control_type == null){
                $control_type = 'custom';
            }
            
            $c_control = __getControl($data['con_control'], $control_type, $con, $company_id);
            
            $rowCount++; #new line before inserting data
            $sheet->setCellValue('A'.$rowCount, $c_control);
            $sheet->setCellValue('B'.$rowCount, $data['con_observation']);
            $sheet->setCellValue('C'.$rowCount, $data['con_rootcause']);
            $sheet->setCellValue('D'.$rowCount, getEffectiveness($data['con_effect']));
            $sheet->setCellValue('E'.$rowCount, get_Frequency($data['con_frequency']));
            
            $rowCount++; #increment row count to get a blank line as seperator
            $rowCount++;
            
            $sheet->setCellValue('A'.$rowCount, 'Audit Criteria Questions:');
            $sheet->getStyle('A'.$rowCount)->getFont()->setBold(true); #bold header values
            $rowCount++;
            if(mysqli_num_rows($query_run_risks) > 0) {
                $sheet->setCellValue('A'.$rowCount, 'S/N');
                $sheet->setCellValue('B'.$rowCount, 'Criteria Question');
                $sheet->setCellValue('C'.$rowCount, 'Criteria Procedure');
                $sheet->setCellValue('D'.$rowCount, 'Expected Outcome');
                $sheet->setCellValue('E'.$rowCount, 'Outcome');
                $sheet->setCellValue('F'.$rowCount, 'Criteria Notes');
                $sheet->getStyle('A'.$rowCount.':F'.$rowCount)->getFont()->setBold(true); #bold header values
                $rowCount++;
                $j = 0;
                foreach($query_run_risks as $data) {
                    $j++;
                    $sheet->setCellValue('A'.$rowCount, $j);
                    $sheet->setCellValue('B'.$rowCount, $data['cri_question']);
                    $sheet->setCellValue('C'.$rowCount, $data['cri_procedure']);
                    $sheet->setCellValue('D'.$rowCount, $data['cri_expected']);
                    $sheet->setCellValue('E'.$rowCount, switchOutcome($data['cri_outcome']));
                    $sheet->setCellValue('F'.$rowCount, $data['cri_notes']);
                    $rowCount++;
                }
            }else{
                $sheet->setCellValue('A'.$rowCount, 'No Criteria Question Added To This Audit Yet!!');
                #empty data
            }
    
            #max width
            $sheet->getColumnDimension('A')->setAutoSize(true);
            $sheet->getColumnDimension('B')->setAutoSize(true);
            $sheet->getColumnDimension('C')->setAutoSize(true);
            $sheet->getColumnDimension('D')->setAutoSize(true);
            $sheet->getColumnDimension('E')->setAutoSize(true);
            $sheet->getColumnDimension('F')->setAutoSize(true);
            $sheet->getColumnDimension('G')->setAutoSize(true);
            $sheet->getColumnDimension('H')->setAutoSize(true);
            $sheet->getColumnDimension('I')->setAutoSize(true);
            $sheet->getColumnDimension('J')->setAutoSize(true);
            $sheet->getColumnDimension('K')->setAutoSize(true);
            $sheet->getColumnDimension('L')->setAutoSize(true);
            $sheet->getColumnDimension('M')->setAutoSize(true);
            $sheet->getColumnDimension('N')->setAutoSize(true);
    
            #set values
            $spreadsheet->getProperties()->setCreator("RiskSafe")
                        ->setLastModifiedBy("RiskSafe")
                        ->setTitle("Audit Of Control (ID: ".strtoupper($id).") Data Export - RiskSAFE")
                        ->setSubject("Audit Of Control (ID: ".strtoupper($id).") Data Export - RiskSAFE");
        } else {
            array_push($message, 'No Records Found!!');
        }
    
        
    

    if($export == true){
        #only export if data exist to avoid missing variable $fileName
        if($file_ext_name == 'xlsx') {
            $writer = new Xlsx($spreadsheet);
            $final_filename = $fileName.'.xlsx';
        } elseif($file_ext_name == 'xls') {
            $writer = new Xls($spreadsheet);
            $final_filename = $fileName.'.xls';
        } elseif($file_ext_name == 'csv') {
            $writer = new Csv($spreadsheet);
            $final_filename = $fileName.'.csv';
        }

        // $writer->save($final_filename);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attactment; filename="'.urlencode($final_filename).'"');
        $writer->save('php://output');
    }  
    
}else{

// require '../../layout/admin_config.php';
?>
<?php
    $file_dir = '../../';
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'login');
        exit();
    }
    $message = [];
    include $file_dir.'layout/db.php';
    include $file_dir.'layout/admin_config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Data | RiskSafe - Risk Assessment And Management</title>

    <?php require $file_dir.'layout/general_css.php' ?>
</head>
<body>
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <?php require $file_dir.'layout/header.php' ?>
        <?php require $file_dir.'layout/sidebar_admin.php' ?>
        <!-- Main Content -->
        <div class="main-content">
            <section class="section">
            <div class="section-body">
                <div class="card">
                    <div class="card-body">
                        <div style="width:100%;min-height:500px;display:flex;justify-content:center;align-items:center;">
                            <div style="text-align: center;"> 
                                <h3>Empty Data!!</h3>
                                No Data To Be Exported,
                                <p style="margin-top: 10px;"><a href="<?php echo $file_dir;?>admin/" class="btn btn-primary btn-icon icon-left mt-2"><i class="fas fa-arrow-left"></i> Back To Home Page</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </section>
        </div>
        <?php require $file_dir.'layout/footer.php' ?>
        </footer>
        </div>
    </div>
    <?php require $file_dir.'layout/general_js.php' ?>
</body>
</html>
<?php } ?>