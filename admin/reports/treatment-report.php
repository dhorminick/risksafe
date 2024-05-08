<?php
    session_start();
    $file_dir = '../../';
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'login?r=/reports/control-reports');
        exit();
    }
    $message = [];
    include '../../layout/db.php';
    include '../../layout/admin__config.php';
    include '../ajax/customs.php';
    include_once 'summary.php';
    
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\Writer\Xls;
    use PhpOffice\PhpSpreadsheet\Writer\Csv;
    
    
    $startDate = "";
    $endDate = "";
    $export__ = false;
    $export = false;
    
    if(has_data('as_customtreatments', 'c_id', $company_id, $con) == true){
        $query="SELECT MAX( cus_date ) AS max FROM as_customtreatments WHERE c_id = '$company_id'";
    	if ($result=$con->query($query)) {
    	    $row=$result->fetch_assoc();
    	    $largestNumber = $row['max'];
    	}else{
    	    $largestNumber = 0;
    	}
    	
    	$query="SELECT MIN( cus_date ) AS max FROM as_customtreatments WHERE c_id = '$company_id'";
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
    
    if (isset($_POST["export-report"]) && isset($_POST["export_param"])) {
        if($_POST["export_param"] == 'date' || $_POST["export_param"] == 'all'){
            $param = sanitizePlus($_POST["export_param"]);
            
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            $fileName = "Treatment Summary Data Export (From: ".$startDate." To: ".$endDate.") - RiskSAFE - Risk Assessment And Management - Exported On: " . date('d-m-Y');
            #define sheet row
            $r = 1;
            #set and merge risksafe header
            $spreadsheet->getActiveSheet()->mergeCells("A1:H1"); 
            $sheet->getStyle('A1:F1')->getAlignment()->setHorizontal('center');
            $sheet->setCellValue('A'.$r, "Treatment Summary Data Export - RiskSAFE - " . date('d-m-Y'));
            
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
                    array_push($message, 'Error : Earliest Recorded Control Is - '.$smallestNumber__1);
                }else{
                    $export__ = true;
                    $fileName = "Treatment Data Export (From: ".$startDate." To: ".$endDate.") - RiskSAFE - Risk Assessment And Management - Exported On: " . date('d-m-Y');
                
                    $query="SELECT * FROM as_customtreatments WHERE c_id = '$company_id' AND cus_date BETWEEN CAST('$startDate' as datetime) AND CAST('$endDate' as datetime) ORDER BY cus_date DESC";
    		        $query_run = mysqli_query($con, $query);
                }
            }else{
                $type = sanitizePlus($_POST['export_type']);
                $file_ext_name = strtolower($type);
                $export__ = true;
                $fileName = "Treatment Summary Data Export - RiskSAFE - Risk Assessment And Management - Exported On: " . date('d-m-Y');
                
                $query = "SELECT * FROM as_customtreatments WHERE c_id = '$company_id'"; 
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
                    $sheet->setCellValue('B'.$r, 'Treatment ID');
                    $sheet->setCellValue('C'.$r, 'Treatment Title');
                    $sheet->setCellValue('D'.$r, 'Treatment Description');
                    $sheet->setCellValue('E'.$r, 'Treatment Status');
                    $sheet->setCellValue('F'.$r, 'Date Created');
    		    #loop through each assesment
                foreach($query_run as $data) {
                    $i++;
                    
                    #add a space between header and data
                    $r++;
                    
                    $in_occured_date = get_date($data['cus_date']);

                    $sheet->setCellValue('A'.$r, $i);
                    $sheet->setCellValue('B'.$r, strtoupper($data['treatment_id']));
                    $sheet->setCellValue('C'.$r, $data['title']);
                    $sheet->setCellValue('D'.$r, $data['description']);
                    $sheet->setCellValue('E'.$r, con_treat_status($data['status']));
                    $sheet->setCellValue('F'.$r, $in_occured_date);
                }
                
                #max width
                $sheet->getColumnDimension('A')->setAutoSize(true);
                $sheet->getColumnDimension('B')->setAutoSize(true);
                $sheet->getColumnDimension('C')->setAutoSize(true);
                $sheet->getColumnDimension('D')->setAutoSize(true);
                $sheet->getColumnDimension('E')->setAutoSize(true);
                $sheet->getColumnDimension('F')->setAutoSize(true);
    
                #set values
                $spreadsheet->getProperties()->setCreator("RiskSafe")
                        ->setLastModifiedBy("RiskSafe")
                        ->setTitle("Treatments Data Export - RiskSAFE")
                        ->setSubject("Treatments Data Export - RiskSAFE");
    		}else{
    		    array_push($message, 'Error : No Treatments To Be Exported');
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
  <title>Treatments Reports | <?php echo $siteEndTitle ?></title>
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
                <div class="card" style='margin-top:10px;'>
                    <div class="card-header" style="margin-top: 20px;display:flex;justify-content:space-between;">
                        <h3 class="d-inline">Treatment Reports</h3>
                        <div>
                            <?php if(has_data('as_customtreatments', 'c_id', $company_id, $con) == true){ ?>
                            <button class="btn btn-primary btn-icon icon-left header-a" data-toggle="modal" data-target="#exportAll"><i class="fas fa-file"></i> Export All</button>
                            <button class="btn btn-outline-primary btn-icon icon-left header-a" data-toggle="modal" data-target="#exportWithDate"><i class="fas fa-file"></i> Export By Date</button>
                            <?php }else{ ?>
                            <a class="btn btn-primary btn-icon icon-left header-a" href="../customs/new-treatment"><i class="fas fa-plus"></i> New Custom Treatment</a>
                            <?php } ?>
                        </div>
                    </div>
                    
                    <div class='card-body'>
                        <?php 
                            $query = "SELECT * FROM as_customtreatments WHERE c_id = '$company_id' ORDER BY id DESC LIMIT 5";
                            $result=$con->query($query);
		                          if ($result->num_rows > 0) { $i = 0;
		                              
                        ?>
                        <table class="table table-striped table-bordered table-hover hide-md" id="table">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">S/N</th>
                                    <th style='width: 30%'>Title</th>
                                    <th>Description</th>
                                    <thstyle='width: 10%'>Status</th>
                                    <th style='width: 10%'>...</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($item = $result->fetch_assoc()){ $i++; ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $item['title']; ?></td>
                                    <td><?php echo $item['description']; ?></td>
                                    <td><?php echo con_treat_status($item['status']); ?></td>
                                    <td>...</td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <?php }else{ ?>
                        <div class="empty-table" style='min-height:300px;display:flex;flex-direction:column;justify-content:center;align-items:center;'>
                            No No Risk Report Registered Yet!!
                            <div><a href='../customs/new-treatment' class='btn btn-primary' style='margin-top:10px;'><i class='fas fa-plus'></i> Register New Custom Treatmeny</a></div>
                        </div> 
                        <?php } ?>
                    </div>
                    <div class='card-footer'></div>
                </div>
                
            </div>
            </section>
        </div>
        
        <?php if(has_data('as_customtreatments', 'c_id', $company_id, $con) == true){ ?>
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
                        <button type="submit" class="btn btn-lg btn-primary btn-icon icon-left" name='export-report'><i class='fas fa-file-download'></i> Export Treatments Report</button>
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
                    		 <strong>NOTE:</strong> Earliest Registered Treatments - <?php echo $smallestNumber__1; ?> and Most Recent Registered Treatments - <?php echo $largestNumber__1; ?>
                    		</div>
                    	</div>
                    	<input type="hidden" name="export_param" value='date' required>
                        </div>
                    <div class="modal-footer bg-whitesmoke">
                        <button type="submit" class="btn btn-lg btn-primary btn-icon icon-left" name='export-report'><i class='fas fa-file-download'></i> Export Treatments Report</button>
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