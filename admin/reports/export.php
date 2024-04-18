<?php 
session_start();
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'login');
        exit();
    }
    $file_dir = '../../';
    $message = [];
    
    include $file_dir.'layout/db.php';
    
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

$export = false; #data to be exported doesn't exist yet!!
$i = 0;

if(isset($_POST["export_data"]) && isset($_POST["export-type"])){
        $company_id = $_SESSION["company_id"];
        
        $db = $con;
        
        include_once 'summary.php';
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
    
        $file_ext_name = strtolower($type);
    
        $fileName = "Risk Assessment Summary Data Export - RiskSAFE - " . date('d-m-Y');
        #define sheet row
        $r = 1;
        #set and merge risksafe header
        $spreadsheet->getActiveSheet()->mergeCells("A1:E1"); 
        $sheet->getStyle('A1:L1')->getAlignment()->setHorizontal('center');
        $sheet->setCellValue('A'.$r, "Risk Assessment Summary Data Export - RiskSAFE - " . date('d-m-Y'));
        
        #add a space between header and data
        $r++;
        #start data on next line
        $r++;

        $query = "SELECT * FROM as_assessment WHERE c_id = '$company_id'"; 
        $query_run = mysqli_query($con, $query);

        if(mysqli_num_rows($query_run) > 0) {
            $ii = 0;
            $export = true; #data to be exported exists


            #loop through each assesment
            foreach($query_run as $data) {
                $ii++;
            $as__id = $data['as_id'];
                
            $sheet->getStyle('A'.$r.':J'.$r)->getFont()->setBold(true); #bold header values
            #add assessment headers
            $sheet->setCellValue('A'.$r, 'S/N');
            $sheet->setCellValue('B'.$r, 'Assessment Type');
            $sheet->setCellValue('C'.$r, 'Assessment Team');
            $sheet->setCellValue('D'.$r, 'Assessment Task');
            $sheet->setCellValue('E'.$r, 'Assessment Description');
            $sheet->setCellValue('F'.$r, 'Assessment Owner');
            $sheet->setCellValue('G'.$r, 'Assessor');
            $sheet->setCellValue('H'.$r, 'Assessment Approval');
            $sheet->setCellValue('I'.$r, 'Assessment Date');
            $sheet->setCellValue('J'.$r, 'Next Assessment');
            
            #add a space between header and data
            $r++;

                $as_approval = ($data['as_approval'] == 1) ? 'True - Assessment Acknowledged' : 'False - Assessment Denied';
                $as__type = as_type($data['as_type'], $con);
                $as_date = get_date($data['as_date']);
                $as_next_date = get_date($data['as_next']);

                $sheet->setCellValue('A'.$r, $ii);
                $sheet->setCellValue('B'.$r, $as__type);
                $sheet->setCellValue('C'.$r, $data['as_team']);
                $sheet->setCellValue('D'.$r, $data['as_task']);
                $sheet->setCellValue('E'.$r, $data['as_descript']);
                $sheet->setCellValue('F'.$r, $data['as_owner']);
                $sheet->setCellValue('G'.$r, $data['as_assessor']);
                $sheet->setCellValue('H'.$r, $as_approval);
                $sheet->setCellValue('I'.$r, $as_date);
                $sheet->setCellValue('J'.$r, $as_next_date);

                #get risks
                $query_2 = "SELECT * FROM as_details WHERE c_id = '$company_id' AND as_id = '$as__id' ORDER BY iddetail DESC"; 
                $query_run_2 = mysqli_query($con, $query_2);

                #if exist
                if(mysqli_num_rows($query_run_2) > 0) {
                        $jj = 0;
                        #add a space between assessment and risks
                        $r++;
                                
                        #begin on the next line
                        $r++;
                        
                        $sheet->getStyle('A'.$r.':J'.$r)->getFont()->setBold(true); #bold header values
                        #add risk headers
                        $sheet->setCellValue('A'.$r, 'S/N');
                        $sheet->setCellValue('B'.$r, 'Risk');
                        $sheet->setCellValue('C'.$r, 'Hazard');
                        $sheet->setCellValue('D'.$r, 'Description');
                        #skipped likelihood and consequence
                        $sheet->setCellValue('E'.$r, 'Risk Rating');
                        $sheet->setCellValue('F'.$r, 'Effectiveness');
                        $sheet->setCellValue('G'.$r, 'Action');
                        $sheet->setCellValue('H'.$r, 'Due Date');
                        $sheet->setCellValue('I'.$r, 'Controls');
                        $sheet->setCellValue('J'.$r, 'Treatments');
                        
                        
                        #begin on the next line
                        $r++;
                        
                    foreach($query_run_2 as $data_2) {
                        $jj++;
                        $as__risk = as_risk($data_2['as_risk'], $con);
                        $as__hazard = as_hazard($data_2['as_hazard'], $con);
                        $as__rating = as_rating($data_2['as_rating']);
                        $as__action = as_action($data_2['as_action'], $con);
                        $as__date = get_date($data_2['as_duedate']);
                        $controls = list_out($data_2['custom_controls']);
                        $treatments = list_out($data_2['custom_treatments']);

                        $sheet->setCellValue('A'.$r, $jj);
                        $sheet->setCellValue('B'.$r, $as__risk);
                        $sheet->setCellValue('C'.$r, $as__hazard);
                        $sheet->setCellValue('D'.$r, $data_2['as_descript']);
                        $sheet->setCellValue('E'.$r, $as__rating);
                        $sheet->setCellValue('F'.$r, $data_2['as_effect']);
                        $sheet->setCellValue('G'.$r, $as__action);
                        $sheet->setCellValue('H'.$r, $as__date);
                        $sheet->setCellValue('I'.$r, $controls);
                        $sheet->setCellValue('J'.$r, $treatments);
                        
                        #begin on the next line
                        $r++;
                    }

                    #add a space between this and next assessment
                    $r++;

                    #begin on next line
                    $r++;
                }else{
                    #add a space between assessment and risks
                    $r++;

                    #begin on the next line
                    $r++;

                    #empty row - no assessment addedd for this risk
                    $spreadsheet->getActiveSheet()->mergeCells("A'.$r.':J".$r); 
                    $sheet->setCellValue('A'.$r, "No Risk Registered For This Assessment Yet!!");
                }

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
                        ->setTitle("Applicable Policy Data Export - RiskSAFE")
                        ->setSubject("Applicable Policy Data Export - RiskSAFE");
        } else {
            #no risk to be exported
            echo "No Record Found";
            // header('Location: index.php');
            exit(0);
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