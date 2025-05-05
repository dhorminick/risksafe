<?php 
session_start();
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

$export = false; #data to be exported doesn't exist yet!!

function get_date($date){
        return date("D\. jS \of F Y", strtotime($date));
    }

    function list_out_control_com($c_id, $id, $con){
        
        $query="SELECT * FROM as_compliancestandard WHERE c_id = '$c_id' AND compli_id = '$id' LIMIT 1";
		$result=$con->query($query);
        if($result->num_rows > 0){
            $row=$result->fetch_assoc();
            $rc = $row["existing_ct"];
            $sc = $row["saved_control"];
            $cc = $row["custom_control"];
            
            $response = '';
            
            if($rc !== 'null' && $rc != '' && $rc != null){
                $response .= ucwords(get_recommended_control($rc, $con))." \r\n";
            }
            if($sc !== 'null' && $sc != '' && $sc != null){
                $response .= ucwords(get_custom_control($sc, $con))." \r\n";
            }
            if($cc !== 'null' && $cc != '' && $cc != null){
                $cc = unserialize($cc);
                foreach ($cc as $arr) {
                    #$response .= $arr.'\n';
                    $response .= ucwords($arr)." \r\n";
                }
            }
            $response .= '';
            
        }else{
            $response = 'Error!';
        }
		return $response;
    }
    
    function list_out_treat_com($c_id, $id, $con){
        
        $query="SELECT * FROM as_compliancestandard WHERE c_id = '$c_id' AND compli_id = '$id' LIMIT 1";
		$result=$con->query($query);
        if($result->num_rows > 0){
            $row=$result->fetch_assoc();
            $sc = $row["saved_treatment"];
            $cc = $row["custom_treatment"];
            
            $response = '';
            
            if($sc !== 'null' && $sc != '' && $sc != null){
                $response .= ucwords(get_custom_treatment($sc, $con))." \r\n";
            }
            if($cc !== 'null' && $cc != '' && $cc != null){
                $cc = unserialize($cc);
                foreach ($cc as $arr) {
                    #$response .= $arr.'\n';
                    $response .= ucwords($arr)." \r\n";
                }
            }
            $response .= '';
            
        }else{
            $response = 'Error!';
        }
		return $response;
    }

if(isset($_POST["export_data"]) && isset($_POST["export-type"]) && isset($_POST["export-id"]) && isset($_POST["export-data"])){
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
        $data = sanitizePlus($_POST["export-data"]);
    
        $file_ext_name = strtolower($type);
    
        if($data == 'policy'){
            $fileName = "Applicable Policy Data Export - RiskSAFE - " . date('d-m-Y');
    
            #set and merge risksafe header
            $spreadsheet->getActiveSheet()->mergeCells("A1:E1"); 
            $sheet->getStyle('A1:L1')->getAlignment()->setHorizontal('center');
            $sheet->setCellValue('A1', "Applicable Policy Data Export - RiskSAFE - " . date('d-m-Y'));
            
            $query = "SELECT * FROM policyfields WHERE p_id = '$id' AND c_id = '$company_id' LIMIT 1"; 
            $query_run = mysqli_query($con, $query);
    
            if(mysqli_num_rows($query_run) > 0) {
                $export = true; #data to be exported exists
                $i = 0;
                $sheet->getStyle('A3:L3')->getFont()->setBold(true); #bold header values
    
                $sheet->setCellValue('A3', 'Policy ID');
                $sheet->setCellValue('B3', 'Policy Title');
                $sheet->setCellValue('C3', 'Policy Number');
                $sheet->setCellValue('D3', 'Policy Description');
                $sheet->setCellValue('E3', 'Policy Requirements');
                $sheet->setCellValue('F3', 'Compliance Responsibility');
                $sheet->setCellValue('G3', 'Related Documents');
                $sheet->setCellValue('H3', 'Policy Approval');
                $sheet->setCellValue('I3', 'Policy Review Revision History');
                $sheet->setCellValue('J3', 'Policy Acknowledgment');
                $sheet->setCellValue('K3', 'Policy Effective Date');
                $sheet->setCellValue('L3', 'Policy Review Date');
    
                $rowCount = 4; #cellValue about++
                foreach($query_run as $data) {
                    $i++;
                    $status = ($data['PolicyAcknowledgment'] == 1)?'True - Policy Acknowledged':'False - Policy Denied';
                    $sheet->setCellValue('A'.$rowCount, $i);
                    $sheet->setCellValue('B'.$rowCount, $data['PolicyTitle']);
                    $sheet->setCellValue('C'.$rowCount, $data['PolicyNumber']);
                    $sheet->setCellValue('D'.$rowCount, $data['PolicyDescription']);
                    $sheet->setCellValue('E'.$rowCount, $data['PolicyRequirements']);
                    $sheet->setCellValue('F'.$rowCount, $data['ComplianceResponsibility']);
                    $sheet->setCellValue('G'.$rowCount, $data['RelatedDocuments']);
                    $sheet->setCellValue('H'.$rowCount, $data['PolicyApproval']);
                    $sheet->setCellValue('I'.$rowCount, $data['PolicyReviewRevisionHistory']);
                    $sheet->setCellValue('J'.$rowCount, $status);
                    $sheet->setCellValue('K'.$rowCount, date("m/d/Y", strtotime($data["PolicyEffectiveDate"])));
                    $sheet->setCellValue('L'.$rowCount, date("m/d/Y", strtotime($data["PolicyReviewDate"])));
                    $rowCount++;
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
    
                #set values
                $spreadsheet->getProperties()->setCreator("RiskSafe")
                            ->setLastModifiedBy("RiskSafe")
                            ->setTitle("Applicable Policy Data Export - RiskSAFE")
                            ->setSubject("Applicable Policy Data Export - RiskSAFE");
            } else {
                echo "No Record Found";
                // header('Location: index.php');
                exit(0);
            }
        }else if($data == 'procedure'){
            $fileName = "Applicable Procedure Data Export - RiskSAFE - " . date('d-m-Y'); 
            
            #set and merge risksafe header
            $spreadsheet->getActiveSheet()->mergeCells("A1:L1"); 
            $sheet->getStyle('A1:L1')->getAlignment()->setHorizontal('center');
            $sheet->setCellValue('A1', "Applicable Procedure Data Export - RiskSAFE - " . date('d-m-Y'));
            
            $query = "SELECT * FROM as_procedures WHERE p_id = '$id' AND c_id = '$company_id' LIMIT 1"; 
            $query_run = mysqli_query($con, $query);
    
            if(mysqli_num_rows($query_run) > 0) {
                $export = true; #data to be exported exists
    
                $i = 0;
                $sheet->getStyle('A3:L3')->getFont()->setBold(true); #bold header values
    
                $sheet->setCellValue('A3', 'ID');
                $sheet->setCellValue('B3', 'Procedure Title');
                $sheet->setCellValue('C3', 'Procedure Number');
                $sheet->setCellValue('D3', 'Procedure Description');
                $sheet->setCellValue('E3', 'Procedure Effective Date');
                $sheet->setCellValue('F3', 'Procedure Review Date');
                $sheet->setCellValue('G3', 'Applicability');
                $sheet->setCellValue('H3', 'Compliance Requirements');
                $sheet->setCellValue('I3', 'Resources');
                $sheet->setCellValue('J3', 'Procedure Approval');
                $sheet->setCellValue('K3', 'Procedure Review');
                $sheet->setCellValue('L3', 'Procedure Acknowledgment');
    
                $rowCount = 4; #cellValue about++
                foreach($query_run as $data) {
                    $i++;
                    $status = ($data['ProcedureAcknowledgment'] == 1)?'True - Procedure Acknowledged':'False - Procedure Denied';
    
                    $sheet->setCellValue('A'.$rowCount, $i);
                    $sheet->setCellValue('B'.$rowCount, $data['ProcedureTitle']);
                    $sheet->setCellValue('C'.$rowCount, $data['ProcedureNumber']);
                    $sheet->setCellValue('D'.$rowCount, $data['ProcedureDescription']);
                    $sheet->setCellValue('E'.$rowCount, $data['com_training']);
                    $sheet->setCellValue('F'.$rowCount, $data['com_training']);
                    $sheet->setCellValue('G'.$rowCount, $data['Applicability']);
                    $sheet->setCellValue('H'.$rowCount, $data['ComplianceRequirements']);
                    $sheet->setCellValue('I'.$rowCount, $data['Resources']);
                    $sheet->setCellValue('J'.$rowCount, $data['ProcedureApproval']);
                    $sheet->setCellValue('K'.$rowCount, $data['ProcedureReview']);
                    $sheet->setCellValue('L'.$rowCount, $status);
                    $rowCount++;
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
    
                #set values
                $spreadsheet->getProperties()->setCreator("RiskSafe")
                            ->setLastModifiedBy("RiskSafe")
                            ->setTitle("Applicable Procedure Data Export - RiskSAFE")
                            ->setSubject("Applicable Procedure Data Export - RiskSAFE");
            } else {
                echo "No Record Found";
                // header('Location: index.php');
                exit(0);
            }
        }else if($data == 'compliance'){
            $fileName = "Compliance Standard Data Export - RiskSAFE - " . date('d-m-Y');    
    
            #set and merge risksafe header
            $spreadsheet->getActiveSheet()->mergeCells("A1:E1"); 
            $sheet->getStyle('A1:E1')->getAlignment()->setHorizontal('center');
            $sheet->setCellValue('A1', "Compliance Standard Data Export - RiskSAFE - " . date('d-m-Y'));
            
            $query = "SELECT * FROM as_compliancestandard WHERE compli_id = '$id' AND c_id = '$company_id' LIMIT 1"; 
            $query_run = mysqli_query($con, $query);
    
            if(mysqli_num_rows($query_run) > 0) {
                $export = true; #data to be exported exists
                // $sheet->getStyle('A3:E3')->getFont()->setBold(true); #bold header values
    
                // $sheet->setCellValue('A3', 'ID');
                // $sheet->setCellValue('B3', 'Full Name');
                // $sheet->setCellValue('C3', 'Email');
                // $sheet->setCellValue('D3', 'Phone');
                // $sheet->setCellValue('E3', 'Course');
                $r = 3;

                $sheet->getStyle('A'.$r.':L'.$r)->getFont()->setBold(true); #bold header values
                #add assessment headers
                $sheet->setCellValue('A'.$r, 'S/N');
                $sheet->setCellValue('B'.$r, 'Compliance Task or Obligation');
                $sheet->setCellValue('C'.$r, 'Reference / Legislation');
                $sheet->setCellValue('D'.$r, 'Compliance Requirements');
                $sheet->setCellValue('E'.$r, 'Compliance Officer');
                $sheet->setCellValue('F'.$r, 'Compliance Status');
                $sheet->setCellValue('G'.$r, 'Compliance Frequency');
                $sheet->setCellValue('H'.$r, 'Documentation & Evidence');
                $sheet->setCellValue('I'.$r, 'Compliance Controls');
                $sheet->setCellValue('J'.$r, 'Control Requirements');
                $sheet->setCellValue('K'.$r, 'Compliance Treatments');
                $sheet->setCellValue('L'.$r, 'Date Created');
    
                // $rowCount = 4; #cellValue about++
                // foreach($query_run as $data) {
                //     $sheet->setCellValue('A'.$rowCount, $data['com_user_id']);
                //     $sheet->setCellValue('B'.$rowCount, $data['com_compliancestandard']);
                //     $sheet->setCellValue('C'.$rowCount, $data['com_legislation']);
                //     $sheet->setCellValue('D'.$rowCount, $data['com_controls']);
                //     $sheet->setCellValue('E'.$rowCount, $data['com_training']);
                //     $rowCount++;
                // }

                $r = 4;
                $date_1 = get_date($data['date_added']);
                $evidence = $data['com_documentation'];
                $controls = list_out_control_com($company_id, $data['compli_id'], $con);
                $treatments = list_out_treat_com($company_id, $data['compli_id'], $con);
    

                $sheet->setCellValue('A'.$r, 1);
                $sheet->setCellValue('B'.$r, ucwords($data['com_compliancestandard']));
                $sheet->setCellValue('C'.$r, nl2br($data['com_legislation']));
                $sheet->setCellValue('D'.$r, nl2br($data['com_training']));
                $sheet->setCellValue('E'.$r, ucwords($data['com_officer']));
                $sheet->setCellValue('F'.$r, ucwords($data['com_controls']));
                $sheet->setCellValue('G'.$r, get_Frequency_com($data['frequency']));
                
                if($evidence == 'null'){
                    $sheet->setCellValue('H'.$r, 'None Uploaded');
                }else{
                    $___link = 'https://risksafe.co/admin/compliances/evidence/'.$evidence;
                    $sheet->getCell('H'.$r) ->setValueExplicit("Click Here To View Documentation");
                    $sheet->getCell('H'.$r) ->getHyperlink() ->setUrl($___link); 
                    $sheet->getStyle('H'.$r) ->applyFromArray(array( 'font' => array( 'color' => ['rgb' => '0000FF'], 'underline' => 'single' ) ));
                }
                
                $sheet->setCellValue('I'.$r, $controls);
                $sheet->setCellValue('J'.$r, $data['com_controls']);
                $sheet->setCellValue('K'.$r, $treatments);
                $sheet->setCellValue('L'.$r, $date_1);
    
                #max width
                $sheet->getColumnDimension('A')->setAutoSize(true);
                $sheet->getColumnDimension('B')->setAutoSize(true);
                $sheet->getColumnDimension('C')->setAutoSize(true);
                $sheet->getColumnDimension('D')->setAutoSize(true);
                $sheet->getColumnDimension('E')->setAutoSize(true);
                $sheet->getColumnDimension('F')->setAutoSize(true);
                $sheet->getColumnDimension('G')->setAutoSize(true);
                $sheet->getColumnDimension('H')->setAutoSize(true);
    
                #set values
                $spreadsheet->getProperties()->setCreator("RiskSafe")
                            ->setLastModifiedBy("RiskSafe")
                            ->setTitle("Compliance Standard Data Export - RiskSAFE")
                            ->setSubject("Compliance Standard Data Export - RiskSAFE");
            } else {
                echo "No Record Found";
                // header('Location: index.php');
                exit(0);
            }
        }else{
            #error 
            exit();
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