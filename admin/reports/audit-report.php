<?php
    session_start();
    $file_dir = '../../';
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'login?r=/reports/audit-report');
        exit();
    }
    $message = [];
    include '../../layout/db.php';
    include '../../layout/admin__config.php';
    include 'summary.php';
    
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\Writer\Xls;
    use PhpOffice\PhpSpreadsheet\Writer\Csv;
    
    
    #params
    $export = false;
    $export__ = false;
    
    if(has_data('as_auditcontrols', 'c_id', $company_id, $con) == true){
        $query="SELECT MAX( con_date ) AS max FROM as_auditcontrols WHERE c_id = '$company_id'";
    	if ($result=$con->query($query)) {
    	    $row=$result->fetch_assoc();
    	    $largestNumber = $row['max'];
    	}else{
    	    $largestNumber = 0;
    	}
    	
    	$query="SELECT MIN( con_date ) AS max FROM as_auditcontrols WHERE c_id = '$company_id'";
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
    }
    
    if (isset($_POST["export-report"]) && isset($_POST["export_param"])) {
        if($_POST["export_param"] == 'date' || $_POST["export_param"] == 'all'){
            $param = sanitizePlus($_POST["export_param"]);
            
            $r = 1;
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            #set and merge risksafe header
            $spreadsheet->getActiveSheet()->mergeCells("A1:E1"); 
            $sheet->getStyle('A1:L1')->getAlignment()->setHorizontal('center');
            $sheet->setCellValue('A'.$r, "Audit Summary Data Export - RiskSAFE - " . date('d-m-Y'));
            
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
                    array_push($message, 'Error : Earliest Recorded Audit Is - '.$smallestNumber__1);
                }else{
                    $export__ = true;
                    $fileName = "Audit Summary Data Export (From: ".$startDate." To: ".$endDate.") - RiskSAFE - Risk Assessment And Management - Exported On: " . date('d-m-Y');
                
                    $query="SELECT * FROM as_auditcontrols WHERE c_id = '$company_id' AND con_date BETWEEN CAST('$startDate' as datetime) AND CAST('$endDate' as datetime) ORDER BY con_date DESC";
    		        $query_run = mysqli_query($con, $query);
                }
            }else{
                $type = sanitizePlus($_POST['export_type']);
                $file_ext_name = strtolower($type);
                $export__ = true;
                $fileName = "Audit Summary Data Export - RiskSAFE - Risk Assessment And Management - Exported On: " . date('d-m-Y');
                
                $query = "SELECT * FROM as_auditcontrols WHERE c_id = '$company_id'"; 
                $query_run = mysqli_query($con, $query);
                #param is all
            }
            
            if($export__ == true){
                if(mysqli_num_rows($query_run) > 0) {
                    $ii = 0;
                    $export = true; #data to be exported exists
        
        
                    #loop through each assesment
                    foreach($query_run as $data) {
                        $ii++;
                        $as__id = $data['aud_id'];
                        
                        $spreadsheet->getActiveSheet()->mergeCells("A$r:I$r");
                        $sheet->getStyle("A$r:I$r")->getFont()->setBold(true); #bold header values
                        $sheet->setCellValue('A'.$r, "Audit Details:");
                                
                        #add a space between assessment and risks
                        $r++;
                            
                        $sheet->getStyle("A$r:I$r")->getFont()->setBold(true); #bold header values
                        #add assessment headers
                        $sheet->setCellValue('A'.$r, 'S/N');
                        $sheet->setCellValue('B'.$r, 'Company');
                        $sheet->setCellValue('C'.$r, 'Industry Type');
                        $sheet->setCellValue('D'.$r, 'Business Unit or Team');
                        $sheet->setCellValue('E'.$r, 'Process, Task or Activity');
                        $sheet->setCellValue('F'.$r, 'Auditor');
                        $sheet->setCellValue('G'.$r, 'Issued On');
                        $sheet->setCellValue('H'.$r, 'Site');
                        $sheet->setCellValue('I'.$r, 'Address');
                        
                        #add a space between header and data
                        $r++;

                        $as_date = get_date($data['con_date']);
                        $addrr = $data['con_building'].', '.$data['con_street'].', '.$data['con_state'].', '.$data['con_country'].', '.$data['con_zipcode'];
        
                        $sheet->setCellValue('A'.$r, $ii);
                        $sheet->setCellValue('B'.$r, $data['con_company']);
                        $sheet->setCellValue('C'.$r, $data['con_industry']);
                        $sheet->setCellValue('D'.$r, $data['con_team']);
                        $sheet->setCellValue('E'.$r, $data['con_task']);
                        $sheet->setCellValue('F'.$r, $data['con_assessor']);
                        $sheet->setCellValue('G'.$r, $as_date.' '.$data['con_time']);
                        $sheet->setCellValue('H'.$r, $data['con_site']);
                        $sheet->setCellValue('I'.$r, ucwords($addrr)); 
                        
                        #get control
                        $query_3 = "SELECT * FROM as_auditcontrols WHERE c_id = '$company_id' AND aud_id = '$as__id' ORDER BY idcontrol DESC"; 
                        $query_run_3 = mysqli_query($con, $query_3);

                        #if exist
                        if(mysqli_num_rows($query_run_3) > 0) {
                                $jj = 0;
                                #add a space between assessment and risks
                                $r++;
                                        
                                #begin on the next line
                                $r++;
                                
                                $spreadsheet->getActiveSheet()->mergeCells("A$r:E$r");
                                $sheet->getStyle("A$r:E$r")->getFont()->setBold(true); #bold header values
                                $sheet->setCellValue('A'.$r, "Audit Control Data:");
                                
                                #add a space between assessment and risks
                                $r++;
                                
                                $sheet->getStyle("A$r:E$r")->getFont()->setBold(true); #bold header values
                                #add risk headers
                                $sheet->setCellValue('A'.$r, 'Auditted Control');
                                $sheet->setCellValue('B'.$r, 'Control Rationale');
                                $sheet->setCellValue('C'.$r, 'Control Root Cause');
                                $sheet->setCellValue('D'.$r, 'Control Effectiveness');
                                $sheet->setCellValue('E'.$r, 'Frequency Of Application (FoA)');
                                $sheet->setCellValue('F'.$r, 'Next Audit');
                                
                                
                                #begin on the next line
                                $r++;
                        
                                foreach($query_run_3 as $data_3) {
                                    $jj++;
                                    $l_eff = getEffectiveness($data_3['con_effect']);
                                    $l_freq = get_Frequency($data_3['con_frequency']);
                                    $l_next = get_date($data_3['con_next']);
            
                                    $sheet->setCellValue('A'.$r, $data_3['con_control']);
                                    $sheet->setCellValue('B'.$r, $data_3['con_observation']);
                                    $sheet->setCellValue('C'.$r, $data_3['con_rootcause']);
                                    $sheet->setCellValue('D'.$r, $l_eff);
                                    $sheet->setCellValue('E'.$r, $l_freq);
                                    $sheet->setCellValue('F'.$r, $l_next);
                                    
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
                            $spreadsheet->getActiveSheet()->mergeCells("A$r:E$r"); 
                            $sheet->setCellValue('A'.$r, "No Control Registered For This Audit!!");
                        }
                        
                        #get criteria
                        $query_2 = "SELECT * FROM as_auditcriteria WHERE c_id = '$company_id' AND aud_id = '$as__id' ORDER BY idcriteria DESC"; 
                        $query_run_2 = mysqli_query($con, $query_2);

                        #if exist
                        if(mysqli_num_rows($query_run_2) > 0) {
                                $jj = 0;
                                #add a space between assessment and risks
                                $r++;
                                        
                                #begin on the next line
                                $r++;
                                
                                $spreadsheet->getActiveSheet()->mergeCells("A$r:F$r");
                                $sheet->getStyle("A$r:F$r")->getFont()->setBold(true); #bold header values
                                $sheet->setCellValue('A'.$r, "Audit Criteria:");
                                
                                #add a space between assessment and risks
                                $r++;
                                
                                $sheet->getStyle("A$r:I$r")->getFont()->setBold(true); #bold header values
                                #add risk headers
                                $sheet->setCellValue('A'.$r, 'S/N');
                                $sheet->setCellValue('B'.$r, 'Criteria Question');
                                $sheet->setCellValue('C'.$r, 'Procedure');
                                $sheet->setCellValue('D'.$r, 'Expected Outcome');
                                $sheet->setCellValue('E'.$r, 'Outcome');
                                $sheet->setCellValue('F'.$r, 'Notes');
                                
                                
                                #begin on the next line
                                $r++;
                        
                                foreach($query_run_2 as $data_2) {
                                    $jj++;
                                    $l_outcome = get_outcome($data_2['cri_outcome']);
            
                                    $sheet->setCellValue('A'.$r, $jj);
                                    $sheet->setCellValue('B'.$r, $data_2['cri_question']);
                                    $sheet->setCellValue('C'.$r, $data_2['cri_procedure']);
                                    $sheet->setCellValue('D'.$r, $data_2['cri_expected']);
                                    $sheet->setCellValue('E'.$r, $l_outcome);
                                    $sheet->setCellValue('F'.$r, $data_2['cri_notes']);
                                    
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
                            $spreadsheet->getActiveSheet()->mergeCells("A$r:F$r"); 
                            $sheet->setCellValue('A'.$r, "No Criteria Registered For This Audit Yet!!");
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
                    
                    $sheet->getRowDimension('10')->setRowHeight(-1);
        
                    #set values
                    $spreadsheet->getProperties()->setCreator("RiskSafe")
                                ->setLastModifiedBy("RiskSafe")
                                ->setTitle("Audit Data Export - RiskSAFE")
                                ->setSubject("Audit Data Export - RiskSAFE");
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
  <title>Audit Reports | <?php echo $siteEndTitle ?></title>
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
        <div class="main-content" style='min-height:0px;'>
            <section class="section">
            <div class="section-body">
                <?php require $file_dir.'layout/alert.php' ?>
                <div class="card" style='margin-top:10px;'>
                    <div class="card-header" style="margin-top: 20px;display:flex;justify-content:space-between;">
                        <h3 class="d-inline">Export Audit Reports</h3>
                        <div>
                            <?php if(has_data('as_auditcontrols', 'c_id', $company_id, $con) == true){ ?>
                            <button class="btn btn-primary btn-icon icon-left header-a" data-toggle="modal" data-target="#exportAll"><i class="fas fa-file"></i> Export All</button>
                            <button class="btn btn-outline-primary btn-icon icon-left header-a" data-toggle="modal" data-target="#exportWithDate"><i class="fas fa-file"></i> Export By Date</button>
                            <?php }else{ ?>
                            <a class="btn btn-primary btn-icon icon-left header-a" href="../monitoring/new-audit"><i class="fas fa-plus"></i> New Audit</a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class='card-body'>
                        <?php 
                            $query = "SELECT * FROM as_auditcontrols WHERE c_id = '$company_id' ORDER BY idcontrol DESC LIMIT 5";
                            $result=$con->query($query);
		                          if ($result->num_rows > 0) { $i = 0;
		                              
                        ?>
                        <table class="table table-striped table-bordered table-hover hide-md" id="table">
                            <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Audit Control</th>
                                    <th>Issued On</th>
                                    <th>Effectiveness</th>
                                    <th>Frequency</th>
                                    <th>...</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($item = $result->fetch_assoc()){ $i++; ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo ucwords($item['con_control']); ?></td>
                                    <td><?php echo get_date($item['con_date']); ?></td>
                                    <td><?php echo getEffectiveness($item['con_effect']); ?></td>
                                    <td><?php echo get_Frequency($item['con_frequency']); ?></td>
                                    <td>
                                        <a href="../monitoring/audit-details?id=<?php echo $item["aud_id"]; ?>" target="_blank" class="action-icons btn btn-primary btn-action mr-1"><i class="fas fa-eye"></i> View Audit</a>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <?php }else{ ?>
                        <div class="empty-table" style='min-height:300px;display:flex;flex-direction:column;justify-content:center;align-items:center;'>
                            No Audit Registered Yet!!
                            <div><a href='../monitoring/new-audit' class='btn btn-primary' style='margin-top:10px;'><i class='fas fa-plus'></i> Register New Audit</a></div>
                        </div> 
                        <?php } ?>
                    </div>
                </div>
            </div>
            </section>
        </div>
        
        <?php if(has_data('as_auditcontrols', 'c_id', $company_id, $con) == true){ ?>
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
                        <button type="submit" class="btn btn-lg btn-primary btn-icon icon-left" name='export-report'><i class='fas fa-file-download'></i> Export Audit Report</button>
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
                    		 <strong>NOTE:</strong> Earliest Registered Audit - <?php echo $smallestNumber__1; ?> and Most Recently Registered Audit - <?php echo $largestNumber__1; ?>
                    		</div>
                    	</div>
                    	<input type="hidden" name="export_param" value='date' required>
                        </div>
                    <div class="modal-footer bg-whitesmoke">
                        <button type="submit" class="btn btn-lg btn-primary btn-icon icon-left" name='export-report'><i class='fas fa-file-download'></i> Export Audit Report</button>
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
        textarea{
            min-height: 120px !important;
        }
        .risk-desc {
        width: 100%;
        border-collapse: separate !important;
        }
        .risk-desc td {
        text-align: left;
        border: 2px solid #e4e6fc;
        border-radius: 0px 5px 5px 0px !important;
        padding: 10px 15px !important;
        width: 60%;
        font-weight: 400;
        }
        .risk-desc th {
        text-align: right;
        border: 2px solid #e4e6fc;
        border-radius: 5px 0px 0px 5px !important;
        padding: 10px 15px !important;
        width: 40%;
        }
        td{
            font-weight: 400;
        }
    </style>
</body>
</html>