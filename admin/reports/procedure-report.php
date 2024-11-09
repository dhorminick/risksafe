<?php
    session_start();
    $file_dir = '../../';
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'login?r=/reports/procedure-report');
    }
    $message = [];
    include '../../layout/db.php';
    include '../../layout/admin__config.php';
    // include '../ajax/customs.php';
    include_once 'summary.php';
    
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\Writer\Xls;
    use PhpOffice\PhpSpreadsheet\Writer\Csv;
    
    $ProcedureExist = false;
    
    $export = false;
    $export__ = false;
    
    $startDate = "";
    $endDate = "";
    
    if(has_data('as_procedures', 'c_id', $company_id, $con) == true){
        $query="SELECT MAX( ProcedureEffectiveDate ) AS max FROM as_procedures WHERE c_id = '$company_id'";
    	if ($result=$con->query($query)) {
    	    $row=$result->fetch_assoc();
    	    $largestNumber = $row['max'];
    	}else{
    	    $largestNumber = 0;
    	}
    	
    	$query="SELECT MIN( ProcedureEffectiveDate ) AS max FROM as_procedures WHERE c_id = '$company_id'";
    	if ($result=$con->query($query)) {	
    	    $row=$result->fetch_assoc();
    	    $smallestNumber = $row['max'];
    	}else{
    	    $smallestNumber = 0;
    	}
    	
    	$largestNumber_1 = DateTime::createFromFormat('Y-m-d', $largestNumber);
        $smallestNumber_1 = DateTime::createFromFormat('Y-m-d', $smallestNumber);
                
        $largestNumber__1 = date_format($largestNumber_1, "Y-m-d");
        $smallestNumber__1 = date_format($smallestNumber_1, "Y-m-d");
    }
    
    if (isset($_POST["export-report"])  && isset($_POST["export_param"])) {
        if($_POST["export_param"] == 'date' || $_POST["export_param"] == 'all'){
            
            $param = sanitizePlus($_POST["export_param"]);
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            $fileName = "Procedure Summary Data Export (From: ".$startDate." To: ".$endDate.") - RiskSAFE - Risk Assessment And Management - Exported On: " . date('d-m-Y');
            #define sheet row
            $r = 1;
            #set and merge risksafe header
            $spreadsheet->getActiveSheet()->mergeCells("A1:J1"); 
            $sheet->getStyle('A1:J1')->getAlignment()->setHorizontal('center');
            $sheet->setCellValue('A'.$r, "Procedure Summary Data Export - RiskSAFE - " . date('d-m-Y'));
            
            #add a space between header and data
            $r++;
            #start data on next line
            $r++;
            
            if($param == 'date'){
                $startDate = sanitizePlus($_POST['startDate']);
            	$endDate = sanitizePlus($_POST['endDate']);
            	$type = sanitizePlus($_POST['export_type']);
            	$file_ext_name = strtolower($type);
            	
            	if($startDate > $smallestNumber__1){
                    array_push($message, 'Error : Earliest Recorded Procedure Is - '.$smallestNumber__1);
                }else{
                    $export__ = true;
                    $fileName = "Procedures Summary Data Export (From: ".$startDate." To: ".$endDate.") - RiskSAFE - Risk Assessment And Management - Exported On: " . date('d-m-Y');
                
                    $query="SELECT * FROM as_procedures WHERE c_id = '$company_id' AND ProcedureEffectiveDate BETWEEN CAST('$startDate' as datetime) AND CAST('$endDate' as datetime) ORDER BY ProcedureEffectiveDate DESC";
    		        $query_run = mysqli_query($con, $query);
                }
            }else{
                $type = sanitizePlus($_POST['export_type']);
                $file_ext_name = strtolower($type);
                $export__ = true;
                $fileName = "Procedures Summary Data Export - RiskSAFE - Risk Assessment And Management - Exported On: " . date('d-m-Y');
                
                $query = "SELECT * FROM as_procedures WHERE c_id = '$company_id'"; 
                $query_run = mysqli_query($con, $query);
                #param is all
            }
            
            if($export__ == true){
                if ($query_run->num_rows > 0) {
        		    $export = true; #data to be exported exists
        		    $i = 0;
        		    
                    $sheet->getStyle('A'.$r.':L'.$r)->getFont()->setBold(true); #bold header values
                    #add assessment headers
                    $sheet->setCellValue('A'.$r, 'S/N');
                    $sheet->setCellValue('B'.$r, 'Procedure Title');
                    $sheet->setCellValue('C'.$r, 'Procedure Number');
                    $sheet->setCellValue('D'.$r, 'Procedure Description');
                    $sheet->setCellValue('E'.$r, 'Procedure Effective Date');
                    $sheet->setCellValue('F'.$r, 'Procedure Review Date');
                    $sheet->setCellValue('G'.$r, 'Procedure Applicability');
                    $sheet->setCellValue('H'.$r, 'Compliance Requirements');
                    $sheet->setCellValue('I'.$r, 'Resources');
                    $sheet->setCellValue('J'.$r, 'Procedure Approval');
                    $sheet->setCellValue('K'.$r, 'Procedure Review');
                    $sheet->setCellValue('L'.$r, 'Procedure Acknowledgment');
                    
        		    #loop through each assesment
                    foreach($query_run as $data) {
                        $i++;
                        
                        #add a space between header and data
                        $r++;
                        
                        $date_1 = get_date($data['ProcedureEffectiveDate']);
                        $date_2 = get_date($data['ProcedureReviewDate']);
                        $p_approval = ($data['ProcedureAcknowledgment'] == 1) ? 'True - Procedure Acknowledged' : 'False - Procedure Denied';
    
                        $sheet->setCellValue('A'.$r, $i);
                        $sheet->setCellValue('B'.$r, $data['ProcedureTitle']);
                        $sheet->setCellValue('C'.$r, $data['ProcedureNumber']);
                        $sheet->setCellValue('D'.$r, $data['ProcedureDescription']);
                        $sheet->setCellValue('E'.$r, $date_1);
                        $sheet->setCellValue('F'.$r, $date_2);
                        $sheet->setCellValue('G'.$r, $data['Applicability']);
                        $sheet->setCellValue('H'.$r, $data['ComplianceRequirements']);
                        $sheet->setCellValue('I'.$r, $data['Resources']);
                        $sheet->setCellValue('J'.$r, $data['ProcedureApproval']);
                        $sheet->setCellValue('K'.$r, $data['ProcedureReview']);
                        $sheet->setCellValue('L'.$r, $p_approval);
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
                            ->setTitle("Procedure Data Export - RiskSAFE")
                            ->setSubject("Procedure Data Export - RiskSAFE");
        		}else{
        		    array_push($message, 'Error : No Procedure To Be Exported');
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
                array_push($message, 'Error 402: Report Error');
            }
        }else{
            #invalid
            array_push($message, 'Error 402: Invalid Report Parameters');
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Procedure Reports | <?php echo $siteEndTitle ?></title>
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
                <?php require '../../layout/alert.php' ?>
                <div class='card' style='margin-top:10px;'>
                    <div class="card-header" style="margin-top: 20px;display:flex;justify-content:space-between;">
                        <h3 class="d-inline">Procedure Reports</h3>
                        <div>
                            <?php if(has_data('as_procedures', 'c_id', $company_id, $con) == true){ ?>
                            <button class="btn btn-primary btn-icon icon-left header-a" data-toggle="modal" data-target="#exportAll"><i class="fas fa-file"></i> Export All</button>
                            <button class="btn btn-outline-primary btn-icon icon-left header-a" data-toggle="modal" data-target="#exportWithDate"><i class="fas fa-file"></i> Export By Date</button>
                            <?php }else{ ?>
                            <a class="btn btn-primary btn-icon icon-left header-a" href="../compliances/new-procedure"><i class="fas fa-plus"></i> New Procedure</a>
                            <?php } ?>
                        </div>
                    </div>
        			<div class="card-body">
                                    
                        <?php 
                            $query = "SELECT * FROM as_procedures WHERE c_id = '$company_id' ORDER BY id DESC LIMIT 5";
                            $result=$con->query($query);
		                          if ($result->num_rows > 0) { $i = 0;$ProcedureExist = true;
                        ?>
                        <table class="table table-striped table-bordered table-hover" id="table" style='margin:10px 0px;'>
                            <tr>
                                <th style="width: 5%;">S/N</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Effective Date </th>
                                <th>Review Date</th>
                                <th>...</th>
                            </tr>
                            <?php while($item = $result->fetch_assoc()){ $i++; ?>
                            <tr> 
                                <td><?php echo $i; ?></td>
                                <td><?php echo ucwords($item['ProcedureTitle']); ?></td>
                                <td><?php echo ucwords($item['ProcedureDescription']); ?></td>
                                <td><?php echo ucwords($item["ProcedureEffectiveDate"]); ?></td>
                                <td><?php echo ucwords($item["ProcedureReviewDate"]); ?></td>
                                <td>...</td>
                            </tr>
                            <?php } ?>
                        </table>
                        <?php }else{ ?>
                        <div class="empty-table">
                            No Procedure Registered Yet!!
                            <div><a href='../compliances/new-procedure' class='bb'><i class='fas fa-plus'></i> Register New Procedure</a></div>
                        </div> 
                        <?php } ?>
                	</div>
                    <div class='card-footer'></div>
                </div>
                
            </div>
            </section>
            
            
            <?php if(has_data('as_procedures', 'c_id', $company_id, $con) == true){ ?>
            <div class="modal fade" id="exportAll" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <form method='post' action='' style='width:100%;'>
                  <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Confirm Action</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" style="font-weight: 400;">
                            <div class="form-group">
                        		    <label>Export Type:</label>
                            		<select class="form-control" name='export_type'>
                                        <option value='xls' selected>XLS</option>
                                        <option value='xlsx'>XLSX</option>
                                        <option value='csv'>CSV</option>
                                    </select>
                            </div>
                            <input type="hidden" name="export_param" value='all' required>
                        </div>
                        <div class="modal-footer bg-whitesmoke">
                            <button type="submit" class="btn btn-lg btn-primary btn-icon icon-left" name='export-report'><i class='fas fa-file-download'></i> Export Report</button>
                         </div>
                    </div>
                  </div>
                  </form>
            </div>
            <div class="modal fade bd-example-modal-lg" id="exportWithDate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <form method='post' action=''>
                  <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Select Timespan Of The Report To Be Exported:</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" style="font-weight: 400;">
                            <div class='row custom-row' style='margin-bottom:10px;'>
                                <div class="form-group col-lg-6 col-12">
                        		  <label>Start Date:</label>
                        		  <input name="startDate" type="text" class="form-control datepicker" placeholder="Enter start date" min='<?php echo $largestNumber__1; ?>' value='<?php echo $smallestNumber__1; ?>' max='<?php echo $smallestNumber__1; ?>' required>        
                        		 </div>
                        		<div class="form-group col-lg-6 col-12">
                        		        <label>End Date:</label>
                        		        <input name="endDate" type="text" class="form-control datepicker" placeholder="Enter end date" min='<?php echo $largestNumber__1; ?>' max='<?php echo $smallestNumber__1; ?>'  required>
                        		</div>
                        		<div class="form-group col-12">
                        		        <label>Export Type:</label>
                            		    <select class="form-control" name='export_type'>
                                        <option value='xls' selected>XLS</option>
                                        <option value='xlsx'>XLSX</option>
                                        <option value='csv'>CSV</option>
                                    </select>
                                </div>
                        		<div class='form-group col-lg-12 col-12' style='font-weight:400;margin-top:10px;'>
                        		 <strong>NOTE:</strong> Earliest Registered Report - <?php echo $smallestNumber__1; ?> and Most Recently Registered Report - <?php echo $largestNumber__1; ?>
                        		</div>
                        	</div>
                        	<input type="hidden" name="export_param" value='date' required>
                            </div>
                        <div class="modal-footer bg-whitesmoke">
                            <button type="submit" class="btn btn-lg btn-primary btn-icon icon-left" name='export-report'><i class='fas fa-file-download'></i> Export Report</button>
                        </div>
                    </div>
                  </div>
                  </form>
            </div>
            <?php } ?>
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