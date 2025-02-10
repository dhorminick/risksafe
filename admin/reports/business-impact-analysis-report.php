<?php
    session_start();
    $file_dir = '../../';
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'login?r=/reports/business-impact-analysis-report');
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
    
    $policyExist = false;
    $export = false;
    $export__ = false;
    
    $startDate = "";
    $endDate = "";
    
    if(has_data('as_bia', 'c_id', $company_id, $con) == true){
        $query="SELECT MAX( datee_time ) AS max FROM as_bia WHERE c_id = '$company_id'";
        $result=$con->query($query);
    	if ($result->num_rows > 0) {
    	    $row=$result->fetch_assoc();
    	    $largestNumber = $row['max'];
    	}else{
    	    $largestNumber = 0;
    	}
    	
    	$query="SELECT MIN( datee_time ) AS max FROM as_bia WHERE c_id = '$company_id'";
    	$result=$con->query($query);
    	if ($result->num_rows > 0) {	
    	    $row=$result->fetch_assoc();
    	    $smallestNumber = $row['max'];
    	}else{
    	    $smallestNumber = 0;
    	}
    	
    // 	echo $largestNumber.' - '.$smallestNumber;exit();
    	
    	$largestNumber_1 = DateTime::createFromFormat('Y-m-d H:i:s', $largestNumber);
        $smallestNumber_1 = DateTime::createFromFormat('Y-m-d H:i:s', $smallestNumber);
                
        $largestNumber__1 = date_format($largestNumber_1, "Y-m-d");
        $smallestNumber__1 = date_format($smallestNumber_1, "Y-m-d");
    }
    
    if (isset($_POST["export-report"]) && isset($_POST["export_param"])) {
        if($_POST["export_param"] == 'date' || $_POST["export_param"] == 'all'){
            $param = sanitizePlus($_POST["export_param"]);
            
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            $fileName = "BIA Summary Data Export (From: ".$startDate." To: ".$endDate.") - RiskSAFE - Risk Assessment And Management - Exported On: " . date('d-m-Y');
            #define sheet row
            $r = 1;
            #set and merge risksafe header
            $spreadsheet->getActiveSheet()->mergeCells("A1:H1"); 
            $sheet->getStyle('A1:I1')->getAlignment()->setHorizontal('center');
            $sheet->setCellValue('A'.$r, "BIA Summary Data Export - RiskSAFE - " . date('d-m-Y'));
            
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
                    array_push($message, 'Error : Earliest Recorded Incident Is - '.$smallestNumber__1);
                }else{
                    $export__ = true;
                    $fileName = "BIA Summary Data Export (From: ".$startDate." To: ".$endDate.") - RiskSAFE - Risk Assessment And Management - Exported On: " . date('d-m-Y');
                
                    $query="SELECT * FROM as_bia WHERE c_id = '$company_id' AND datee_time BETWEEN CAST('$startDate' as datetime) AND CAST('$endDate' as datetime) ORDER BY datee_time DESC";
    		        $query_run = mysqli_query($con, $query);
                }
            }else{
                $type = sanitizePlus($_POST['export_type']);
                $file_ext_name = strtolower($type);
                $export__ = true;
                $fileName = "BIA Summary Data Export - RiskSAFE - Risk Assessment And Management - Exported On: " . date('d-m-Y');
                
                $query = "SELECT * FROM as_bia WHERE c_id = '$company_id'"; 
                $query_run = mysqli_query($con, $query);
                #param is all
            }
                
            if($export__ == true){
                if ($query_run->num_rows > 0) {
        		    $export = true; #data to be exported exists
        		    $i = 0;
                        $sheet->getStyle('A'.$r.':H'.$r)->getFont()->setBold(true); #bold header values
                        #add assessment headers
                        $sheet->setCellValue('A'.$r, 'S/N');
                        $sheet->setCellValue('B'.$r, 'BIA ID');
                        $sheet->setCellValue('C'.$r, 'Critical Business Activity');
                        $sheet->setCellValue('D'.$r, 'BIA Description');
                        $sheet->setCellValue('E'.$r, 'BIA Priority');
                        $sheet->setCellValue('F'.$r, 'Impact of Loss');
                        $sheet->setCellValue('G'.$r, 'Recovery Time Objective');
                        $sheet->setCellValue('H'.$r, 'Preventative / Recovery Actions');
                        $sheet->setCellValue('I'.$r, 'Resource Requirements');
        		    #loop through each assesment
                    foreach($query_run as $data) {
                        $i++;
                        
                        #add a space between header and data
                        $r++;
                        
                        $date_1 = get_date($data['datee_time']);
    
                        $sheet->setCellValue('A'.$r, $i);
                        $sheet->setCellValue('B'.$r, strtoupper($data['bia_id']));
                        $sheet->setCellValue('C'.$r, $data['bia_activity']);
                        $sheet->setCellValue('D'.$r, $data['bia_descript']);
                        $sheet->setCellValue('E'.$r, $data['bia_priority']);
                        $sheet->setCellValue('F'.$r, $data['bia_impact']);
                        $sheet->setCellValue('G'.$r, $data['bia_time']);
                        $sheet->setCellValue('H'.$r, $data['bia_action']);
                        $sheet->setCellValue('I'.$r, $data['bia_resource']);
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
        
                    #set values
                    $spreadsheet->getProperties()->setCreator("RiskSafe")
                            ->setLastModifiedBy("RiskSafe")
                            ->setTitle("BIA Data Export - RiskSAFE")
                            ->setSubject("BIA Data Export - RiskSAFE");
        		}else{
        		    array_push($message, 'Error : No BIA To Be Exported');
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
                    }else{
                        $writer = new Xls($spreadsheet);
                        $final_filename = $fileName.'.xls';
                    }
            
                    // $writer->save($final_filename);
                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                    header('Content-Disposition: attactment; filename="'.urlencode($final_filename).'"');
                    $writer->save('php://output');
                } 
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
  <title>Business Impact Analysis Reports | <?php echo $siteEndTitle ?></title>
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
                <?php require $file_dir.'layout/alert.php' ?>
                <div class='card' style='margin-top:10px;'>
                    <div class="card-header" style="margin-top: 20px;display:flex;justify-content:space-between;">
                        <h3 class="d-inline hide-md">Business Impact Analysis Report</h3>
                        <h3 class="d-inline show-md">BIA Report</h3>
                        <div>
                            <?php if(has_data('as_bia', 'c_id', $company_id, $con) == true){ ?>
                            <button class="btn btn-primary btn-icon icon-left header-a" data-toggle="modal" data-target="#exportAll"><i class="fas fa-file"></i> Export All</button>
                            <button class="btn btn-outline-primary btn-icon icon-left header-a" data-toggle="modal" data-target="#exportWithDate"><i class="fas fa-file"></i> Export By Date</button>
                            <?php }else{ ?>
                            <a class="btn btn-primary btn-icon icon-left header-a" href="../business/new-bia"><i class="fas fa-plus"></i> New BIA</a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class='card-body'>
                        
                        <div class="form-group">
        					    <div class='bia-desc' style='text-align:left;'>
        					        The BIA report documents the potential impacts resulting from the disruption of business functions and processes.
        					        Scenarios resulting in significant business interruption should be assessed in terms of financial impact,
        					        if possible. These costs should be compared with the costs for possible recovery strategies.
        					        <p>It consists of all business impact analysis created and modified on risksafe including:</p>
        					    </div>
        					    <div class="card-bod">
                                    <?php 
                                        $query = "SELECT * FROM as_bia WHERE c_id = '$company_id' ORDER BY idbia DESC LIMIT 5";
                                        $result=$con->query($query);
		                                if ($result->num_rows > 0) { $i = 0; $policyExist = true;
		                              
                                    ?>
                                    <table  class="table table-striped table-bordered table-hover hide-md" id="table">
                                        <thead>
                                        <tr>
                                            <th style="width: 5%;">S/N</th>
                                            <th>Business Activity</th>
                                            <th style="width: 30%;">BIA Description</th>
                                            <th>Priority </th>
                                            <th>...</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php while($item = $result->fetch_assoc()){ $i++; ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $item['bia_activity']; ?></td>
                                            <td><?php echo $item['bia_descript']; ?></td>
                                            <td><?php echo $item['bia_priority']; ?></td>
                                            <td>...</td>
                                        </tr>
                                        <?php } ?> 
                                        </tbody>
                                    </table> 
                                    <?php }else{ ?>
                                    <div class="empty-table">
                                        No BIA Registered Yet!!
                                        <div><a href='../business/new-bia' class='btn btn-primary' style='margin-top:10px;'><i class='fas fa-plus'></i> Register New BIA</a></div>
                                    </div> 
                                    <?php } ?>
                                    
                				</div>
                			</div>
                    </div>
                    <div class='card-footer'></div>
                </div>
                
            </div>
            </section>
        </div>
        
        <?php if(has_data('as_bia', 'c_id', $company_id, $con) == true){ ?>
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
                        <button type="submit" class="btn btn-lg btn-primary btn-icon icon-left" name='export-report'><i class='fas fa-file-download'></i> Export BIA Report</button>
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
                    		 <strong>NOTE:</strong> Earliest Registered Incident - <?php echo $smallestNumber__1; ?> and Most Recent Registered Incident - <?php echo $largestNumber__1; ?>
                    		</div>
                    	</div>
                    	<input type="hidden" name="export_param" value='date' required>
                        </div>
                    <div class="modal-footer bg-whitesmoke">
                        <button type="submit" class="btn btn-lg btn-primary btn-icon icon-left" name='export-report'><i class='fas fa-file-download'></i> Export BIA Report</button>
                    </div>
                </div>
              </div>
              </form>
        </div>
        <?php } ?>
        
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