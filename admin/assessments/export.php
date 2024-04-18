<?php 
session_start();
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

$export = false; #data to be exported doesn't exist yet!!

if(isset($_POST["export_data"]) && isset($_POST["export-type"]) && isset($_POST["export-id"])){
        
        $company_id = $_SESSION["company_id"];
        $file_dir = '../../';
        $message = [];
        include $file_dir.'layout/db.php';
        
        $db = $con;
        
        require '../_external/v/vendor/autoload.php';



        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

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
    
        $fileName = "Risk Assessments (ID: ".strtoupper($id).") Data Export - RiskSAFE - " . date('d-m-Y');
    
        #set and merge risksafe header
        $spreadsheet->getActiveSheet()->mergeCells("A1:E1"); 
        $sheet->getStyle('A1:H1')->getAlignment()->setHorizontal('center');
        $sheet->setCellValue('A1', "Risk Assessments (ID: ".strtoupper($id).") Data Export - RiskSAFE - " . date('d-m-Y'));
        
        $query = "SELECT * FROM as_assessment WHERE as_id = '$id' AND c_id = '$company_id' LIMIT 1"; #get assessment details
        $query_run = mysqli_query($con, $query);
    
        $query_risks = "SELECT * FROM as_details WHERE as_id = '$id' AND c_id = '$company_id' ORDER BY iddetail DESC"; #get risks
        $query_run_risks = mysqli_query($con, $query_risks);
    
        function switchProgress($s) {
            switch ($s) {
                case 1:
                    $approval = 'In progress';
                    break;
    
                case 2:
                    $approval = 'Approved';
                    break;
    
                case 3:
                    $approval = 'Closed';
                    break;
            }
    
            return $approval;
        }
    
        function switchApproval($s) {
            switch ($s) {
                case 1:
                    $a = 'Risk Acknowledged';
                    break;
    
                case 0:
                    $a = 'Risk Not Acknowledged';
                    break;
    
                default:
                    $a = 'Risk Not Acknowledged';
                    break;
            }
    
            return $a;
        }
    
        function switchType($type, $con) {
            $query="SELECT * FROM as_types WHERE idtype = '$type'";
            $result = $con->query($query);
            if ($row = $result->fetch_assoc()) {
                $type = $row["ty_name"];
            }else{
                $type = 'Error';
            }
    
            return $type;
        }
    
        function getLikelihood($id, $con){
    		$query="SELECT * FROM as_like WHERE idlike = '$id' LIMIT 1";
    		$result=$con->query($query);
    		if ($result->num_rows > 0) {	
                $row=$result->fetch_assoc();
    			$response = $row["li_like"];
    		}else{
    			$response = 'Error!!';
    		}
    		return $response;
        }
    
        function getConsequence($id, $con){
    		$query="SELECT * FROM as_consequence WHERE idconsequence = '$id' LIMIT 1";
    		$result=$con->query($query);
    		if ($result->num_rows > 0) {	
                $row=$result->fetch_assoc();
    			$response = $row["con_consequence"];
    		}else{
    			$response = 'Error!!';
    		}
    		return $response;
        }
    
        function getRating($rating){
    		switch ($rating) {
    				case 1:
    					return 'Low';
    					break;  
    				case 2:
    					return 'Medium';
    					break;  
    				case 3:
    					return 'High';
    					break;
    				case 4:
    					return 'Extreme';
    					break; 
                    default:
                        return 'Error';
                        break;
    		  }
    	}
    
        function getRisks($id, $con){
    		$query="SELECT * FROM as_risks WHERE idrisk = '$id'";
    		$result=$con->query($query);
    		if ($result->num_rows > 0) {	
    			$row=$result->fetch_assoc();
    			$response = $row['ri_name'];
    		}else{
    			$response = 'Error!!';
    		}
    		return $response;
        }
        
        function getHazards($id, $con){
    		$query="SELECT * FROM as_cat WHERE idcat = '$id'";
    		$result=$con->query($query);
    		if ($result->num_rows > 0) {	
    			$row=$result->fetch_assoc();
    			$response = $row['cat_name'];
    		}else{
    			$response = 'Error!!';
    		}
    		return $response;
        }
    
        function getlistActions($id, $con) {
    	
    		$query="SELECT * FROM as_actiontype WHERE idaction = '$id' LIMIT 1";
    		$result=$con->query($query);
            if($result->num_rows > 0){
                $row=$result->fetch_assoc();
                $response = $row["ac_type"];
            }else{
                $response = 'Error!';
            }
    		return $response;
    	}
    
        if(mysqli_num_rows($query_run) > 0) {
            $export = true; #data to be exported exists
            $i = 0;
            $sheet->getStyle('A3:H3')->getFont()->setBold(true); #bold header values
    
            $sheet->setCellValue('A3', 'Assessment Type');
            $sheet->setCellValue('B3', 'Assessment Task');
            $sheet->setCellValue('C3', 'Assessment Description');
            $sheet->setCellValue('D3', 'Assessment Team');
            $sheet->setCellValue('E3', 'Process Owner');
            $sheet->setCellValue('F3', 'Assessor');
            $sheet->setCellValue('G3', 'Issued On');
            $sheet->setCellValue('H3', 'Assessment Approval');
            // $sheet->setCellValue('I3', 'Policy Review Revision History');
            // $sheet->setCellValue('J3', 'Policy Acknowledgment');
    
            $rowCount = 4; #cellValue about++
            foreach($query_run as $data) {
                $i++;
                $sheet->setCellValue('A'.$rowCount, switchType($data['as_type'], $con));
                $sheet->setCellValue('B'.$rowCount, $data['as_task']);
                $sheet->setCellValue('C'.$rowCount, $data['as_descript']);
                $sheet->setCellValue('D'.$rowCount, $data['as_team']);
                $sheet->setCellValue('E'.$rowCount, $data['as_owner']);
                $sheet->setCellValue('F'.$rowCount, $data['as_assessor']);
                $sheet->setCellValue('G'.$rowCount, date("m/d/Y", strtotime($data["as_date"])));
                $sheet->setCellValue('H'.$rowCount, switchApproval($data['as_approval']));
                $rowCount++;
            }
    
            $rowCount++; #increment row count to get a blank line as seperator
    
            $sheet->setCellValue('A'.$rowCount, 'Risk');
            $sheet->setCellValue('B'.$rowCount, 'Risk Hazard');
            $sheet->setCellValue('C'.$rowCount, 'Risk Description');
            $sheet->setCellValue('D'.$rowCount, 'Risk Likelihood');
            $sheet->setCellValue('E'.$rowCount, 'Risk Consequence');
            $sheet->setCellValue('F'.$rowCount, 'Risk Rating');
            $sheet->setCellValue('G'.$rowCount, 'Risk Effectiveness');
            $sheet->setCellValue('H'.$rowCount, 'Action Taken');
            $sheet->setCellValue('I'.$rowCount, 'Risk Owner');
            $sheet->setCellValue('J'.$rowCount, 'Due Date');
    
            $sheet->getStyle('A'.$rowCount.':J'.$rowCount)->getFont()->setBold(true); #bold header values
    
            if(mysqli_num_rows($query_run_risks) > 0) {
                $rowCount++;
                $j = 0;
                foreach($query_run_risks as $data) {
                    $j++;
                    $sheet->setCellValue('A'.$rowCount, getRisks($data['as_risk'], $con));
                    $sheet->setCellValue('B'.$rowCount, getHazards($data['as_hazard'], $con));
                    $sheet->setCellValue('C'.$rowCount, $data['as_descript']);
                    $sheet->setCellValue('D'.$rowCount, getLikelihood($data['as_like'], $con));
                    $sheet->setCellValue('E'.$rowCount, getConsequence($data['as_consequence'], $con));
                    $sheet->setCellValue('F'.$rowCount, getRating($data['as_rating']));
                    $sheet->setCellValue('G'.$rowCount, $data["as_effect"]);
                    $sheet->setCellValue('H'.$rowCount, getlistActions($data['as_action'], $con));
                    $sheet->setCellValue('I'.$rowCount, $data['as_owner']);
                    $sheet->setCellValue('J'.$rowCount, $data["as_duedate"]);
                    $rowCount++;
                }
            }else{
                $rowCount++;
                $sheet->setCellValue('A'.$rowCount, 'No Risk Added To This Assessment Yet!!');
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
    
            #set values
            $spreadsheet->getProperties()->setCreator("RiskSafe")
                        ->setLastModifiedBy("RiskSafe")
                        ->setTitle("Risk Assessments Data Export - RiskSAFE")
                        ->setSubject("Risk Assessments Data Export - RiskSAFE");
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