<?php
    session_start();
    $file_dir = '../../';
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'login?r=/reports/incident-report');
    }
    $message = [];
    include '../../layout/db.php';
    include '../../layout/admin_config.php';
    include '../ajax/incidents.php';
    // require_once $file_dir.'classes/PHPExcel.php';
    include_once 'summary.php';
    // require '../_external/v/vendor/autoload.php';
    
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\Writer\Xls;
    use PhpOffice\PhpSpreadsheet\Writer\Csv;
    
    $startDate = "";
    $endDate = "";
    
    #params
    $export = false;
    
    $query="SELECT MAX( in_date ) AS max FROM as_incidents WHERE c_id = '$company_id'";
	if ($result=$con->query($query)) {
	    $row=$result->fetch_assoc();
	    $largestNumber = $row['max'];
	}else{
	    $largestNumber = 0;
	}
	
	$query="SELECT MIN( in_date ) AS max FROM as_incidents WHERE c_id = '$company_id'";
	if ($result=$con->query($query)) {	
	    $row=$result->fetch_assoc();
	    $smallestNumber = $row['max'];
	}else{
	    $smallestNumber = 0;
	}
	
	$largestNumber_1 = DateTime::createFromFormat('Y-m-d', $largestNumber);
    $smallestNumber_1 = DateTime::createFromFormat('Y-m-d', $smallestNumber);
            
    // $largestNumber_1 = date_format($largestNumber_1, "d-m-Y");
    // $smallestNumber_1 = date_format($smallestNumber_1, "d-m-Y");
    
    $largestNumber__1 = date_format($largestNumber_1, "Y-m-d");
    $smallestNumber__1 = date_format($smallestNumber_1, "Y-m-d");
    
    if (isset($_POST["export-report"])) {
        $startDate = sanitizePlus($_POST['startDate']);
    	$endDate = sanitizePlus($_POST['endDate']);
    	$type = sanitizePlus($_POST['export_type']);
    	$file_ext_name = strtolower($type);
        
        if($startDate > $smallestNumber__1){
            array_push($message, 'Error : Earliest Recorded Incident Is - '.$smallestNumber__1);
        }else{
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            $fileName = "Incident Summary Data Export (From: ".$startDate." To: ".$endDate.") - RiskSAFE - Risk Assessment And Management - Exported On: " . date('d-m-Y');
            #define sheet row
            $r = 1;
            #set and merge risksafe header
            $spreadsheet->getActiveSheet()->mergeCells("A1:J1"); 
            $sheet->getStyle('A1:J1')->getAlignment()->setHorizontal('center');
            $sheet->setCellValue('A'.$r, "Incident Summary Data Export - RiskSAFE - " . date('d-m-Y'));
            
            #add a space between header and data
            $r++;
            #start data on next line
            $r++;
            
            $query="SELECT * FROM as_incidents WHERE c_id = '$company_id' AND in_date BETWEEN CAST('$startDate' as datetime) AND CAST('$endDate' as datetime) ORDER BY idincident DESC";
    		$result = mysqli_query($con, $query);
    		if ($result->num_rows > 0) {
    		    $export = true; #data to be exported exists
    		    $i = 0;
                    $sheet->getStyle('A'.$r.':J'.$r)->getFont()->setBold(true); #bold header values
                    #add assessment headers
                    $sheet->setCellValue('A'.$r, 'S/N');
                    $sheet->setCellValue('B'.$r, 'Incident ID');
                    $sheet->setCellValue('C'.$r, 'Case Title');
                    $sheet->setCellValue('D'.$r, 'Date Occurred');
                    $sheet->setCellValue('E'.$r, 'Reported By');
                    $sheet->setCellValue('F'.$r, 'Team Or Department');
                    $sheet->setCellValue('G'.$r, 'Description');
                    $sheet->setCellValue('H'.$r, 'Impact');
                    $sheet->setCellValue('I'.$r, 'Priority');
                    $sheet->setCellValue('J'.$r, 'Status');
    		    #loop through each assesment
                foreach($result as $data) {
                    $i++;
                    
                    #add a space between header and data
                    $r++;
                    
                    $in_occured_date = get_date($data['in_date']);

                    $sheet->setCellValue('A'.$r, $i);
                    $sheet->setCellValue('B'.$r, strtoupper($data['in_id']));
                    $sheet->setCellValue('C'.$r, $data['in_title']);
                    $sheet->setCellValue('D'.$r, $in_occured_date);
                    $sheet->setCellValue('E'.$r, $data['in_reported']);
                    $sheet->setCellValue('F'.$r, $data['in_team']);
                    $sheet->setCellValue('G'.$r, $data['in_descript']);
                    $sheet->setCellValue('H'.$r, $data['in_impact']);
                    $sheet->setCellValue('I'.$r, $data['in_priority']);
                    $sheet->setCellValue('J'.$r, $data['in_status']);
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
                        ->setTitle("Incident Data Export - RiskSAFE")
                        ->setSubject("Incident Data Export - RiskSAFE");
    		}else{
    		    array_push($message, 'Error : No Incident To Be Exported');
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
            
        }
    	
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Incident Reports | <?php echo $siteEndTitle ?></title>
  <?php require '../../layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/admin.custom.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/bundles/bootstrap-timepicker/css/bootstrap-timepicker.min.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/bundles/bootstrap-daterangepicker/daterangepicker.css">
</head>

<body>
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <?php require '../../layout/header.php' ?>
        <?php require '../../layout/sidebar_admin.php' ?>
        <!-- Main Content -->
        <div class="main-content">
            <section class="section">
            <div class="section-body">
                <div class='card'>
                    <div class="card-header">
                        <h3 class="d-inline">Incident Reports</h3>
                        <a class="btn btn-primary btn-icon icon-left header-a" href="../business/new-incident"><i class="fas fa-plus"></i> New Incident</a>
                    </div>
                    <div class='card-body'>
                        <?php require '../../layout/alert.php' ?>
                        <form role="form" id="form" method="post">
        					<div class="form-group">
        					    <div class='bia-desc' style='text-align:left !important;'>
        					        It can include data such as who was involved, what happened, when it happened, where it happened, what caused it to happen, 
        					        and any other relevant details. This documentation helps organizations identify risks that need to 
        					        be addressed to prevent similar incidents from occurring in the future.

                                    <p>By recording incident data, organizations can use <strong>KEY RISK INDICATORS</strong> to gain insights that allow 
                                    for predictive analytics and proactive measures to prevent similar events from happening again. 
                                    It can also help streamline the process of incident reporting with accuracy and efficiency.</p>
                                    
                                    <p>Various incidents are reported, including workplace injuries, accidents and near-misses, 
                                    data breaches and security threats, medical emergencies, and customer complaints. 
                                    Each one needs to be properly documented so incidents can be tracked over time and patterns can be identified.</p>
                                    <p>Your registered incidents on RiskSafe includes:</p>
        					    </div>
        					    <div class="card-bod">
                                    <?php 
                                        $list_one = listIncidentsForReportCustom($company_id, $con, 5);
                                        $details_count = $list_one;
                                        if($list_one !== false){
                                        if(count($details_count) <= 1){$details[] = $list_one;}else{$details = $list_one;}
                                        
                                    ?>
                                    
                                    <?php if ($details === 'a:0:{}') { #empty data?> 
                                    <div style="width:100%;min-height:400px;display:flex;justify-content:center;align-items:center;">
                                          <div style="text-align: center;"> 
                                            <h3>Empty Data!!</h3>
                                            No Incident Recorded Yet,
                                            <p><a href="../business/new-incident" class="btn btn-primary btn-icon icon-left mt-2"><i class="fas fa-plus"></i> Record New Incident</a></p>
                                        </div>
                                    </div>
                                    <?php }else{ $arrcount = count($details); ?>
                                    <table class="payment-data" style='margin:10px 0px;'>
                                        <tr>
                                            <th style="width: 5%;">S/N</th>
                                            <th style="width: 30%;">Title</th>
                                            <th>Status</th>
                                            <th>Priority </th>
                                            <th>Date </th>
                                            <th>...</th>
                                        </tr>
                                        <?php $i = 0; foreach ($list_one as $item) { $i++; ?>
                                        <tr>
                                            <td><strong><?php echo $i; ?></strong></td>
                                            <td><?php echo ucwords($item['in_title']); ?></td>
                                            <td><?php echo ucwords($item['in_status']); ?></td>
                                            <td><?php echo ucwords($item['in_priority']); ?></td>
                                            <td><?php $d = date_create_from_format('Y-m-d', $item['in_date']); echo date_format($d, "m/d/Y"); ?></td>
                                            <td>...</td>
                                        </tr>
                                    <?php }} if ($details !== 'a:0:{}') { echo '</table>'; } #closing tag for table ?>
                                    
                                    
                                    
                                    
                                    <div class='row custom-row' style='margin-top:30px;margin-bottom:10px;'>
                                    <div class='col-12' style='margin:10px 0px;font-size:17px;'>
                                        Select Timespan Of The Report To Be Exported:
                                    </div>
                                    <div class="form-group col-lg-5 col-12">
                    		            <label>Start Date:</label>
                    		            <input name="startDate" type="text" class="form-control datepicker" placeholder="Enter start date" min='<?php echo $largestNumber__1; ?>' value='<?php echo $smallestNumber__1; ?>' max='<?php echo $smallestNumber__1; ?>' required>        
                    		        </div>
                    		        <div class="form-group col-lg-5 col-12">
                    		            <label>End Date:</label>
                    		            <input name="endDate" type="text" class="form-control datepicker" placeholder="Enter end date" min='<?php echo $largestNumber__1; ?>' max='<?php echo $smallestNumber__1; ?>' required>
                    		        </div>
                    		        <div class="form-group col-lg-2 col-12">
                    		            <label>Export Type:</label>
                        		        <select class="form-control" name='export_type'>
                                            <option value='xls' selected>XLS</option>
                                            <option value='xlsx'>XLSX</option>
                                            <option value='csv'>CSV</option>
                                        </select>
                                    </div>
                    		        <div class='form-group col-lg-12 col-12' style='font-weight:400;'>
                    		            <strong>NOTE:</strong> Earliest Registered Incident - <?php echo $smallestNumber__1; ?> and Most Recent Registered Incident - <?php echo $largestNumber__1; ?>
                    		        </div>
                    		        </div>
                                    <div style='width:100%;text-align:center;margin-top:10px;text-align:right;'>
                					    <button type="submit" class="btn btn-lg btn-primary btn-icon icon-left" name='export-report'><i class='fas fa-file-download'></i> Export Incident Report</button>
                					</div>
                                    <?php }else{ ?>
                                    <div class="empty-table">No Incident Registered Yet!!</div>
                                    <?php } ?>
                					</div>
        				</form>
                    </div>
                    <div class='card-footer'></div>
                </div>
                
            </div>
            </section>
        </div>
        <?php require '../../layout/footer.php' ?>
        </footer>
        </div>
    </div>
    <?php require '../../layout/general_js.php' ?>
    <script src="<?php echo $file_dir; ?>assets/bundles/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
    <script src="<?php echo $file_dir; ?>assets/bundles/bootstrap-daterangepicker/daterangepicker.js"></script>
    <style>
        .card{
            padding:10px;
        }
    </style>
</body>
</html>