<?php
    session_start();
    $file_dir = '../../';
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'login?r=/reports/control-reports');
    }
    $message = [];
    include '../../layout/db.php';
    include '../../layout/admin_config.php';
    include '../ajax/customs.php';
    include_once 'summary.php';
    
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\Writer\Xls;
    use PhpOffice\PhpSpreadsheet\Writer\Csv;
    
    
    $startDate = "";
    $endDate = "";
    
    $query="SELECT MAX( cus_date ) AS max FROM as_customcontrols WHERE c_id = '$company_id'";
	if ($result=$con->query($query)) {
	    $row=$result->fetch_assoc();
	    $largestNumber = $row['max'];
	}else{
	    $largestNumber = 0;
	}
	
	$query="SELECT MIN( cus_date ) AS max FROM as_customcontrols WHERE c_id = '$company_id'";
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
    
    if (isset($_POST["export-report"])) {
        $startDate = sanitizePlus($_POST['startDate']);
    	$endDate = sanitizePlus($_POST['endDate']);
    	$type = sanitizePlus($_POST['export_type']);
    	$file_ext_name = strtolower($type);
        
        if($startDate > $smallestNumber__1){
            array_push($message, 'Error : Earliest Recorded Control Is - '.$smallestNumber__1);
        }else{
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            $fileName = "Control Summary Data Export (From: ".$startDate." To: ".$endDate.") - RiskSAFE - Risk Assessment And Management - Exported On: " . date('d-m-Y');
            #define sheet row
            $r = 1;
            #set and merge risksafe header
            $spreadsheet->getActiveSheet()->mergeCells("A1:H1"); 
            $sheet->getStyle('A1:H1')->getAlignment()->setHorizontal('center');
            $sheet->setCellValue('A'.$r, "Control Summary Data Export - RiskSAFE - " . date('d-m-Y'));
            
            #add a space between header and data
            $r++;
            #start data on next line
            $r++;
            
            $query="SELECT * FROM as_customcontrols WHERE c_id = '$company_id' AND cus_date BETWEEN CAST('$startDate' as datetime) AND CAST('$endDate' as datetime) ORDER BY id DESC";
    		$result = mysqli_query($con, $query);
    		if ($result->num_rows > 0) {
    		    $export = true; #data to be exported exists
    		    $i = 0;
                    $sheet->getStyle('A'.$r.':H'.$r)->getFont()->setBold(true); #bold header values
                    #add assessment headers
                    $sheet->setCellValue('A'.$r, 'S/N');
                    $sheet->setCellValue('B'.$r, 'Control ID');
                    $sheet->setCellValue('C'.$r, 'Control Title');
                    $sheet->setCellValue('D'.$r, 'Control Description');
                    $sheet->setCellValue('E'.$r, 'Control Category');
                    $sheet->setCellValue('F'.$r, 'Effectiveness');
                    $sheet->setCellValue('G'.$r, 'Frequency Of Application');
                    $sheet->setCellValue('H'.$r, 'Date Created');
    		    #loop through each assesment
                foreach($result as $data) {
                    $i++;
                    
                    #add a space between header and data
                    $r++;
                    
                    $in_occured_date = get_date($data['cus_date']);

                    $sheet->setCellValue('A'.$r, $i);
                    $sheet->setCellValue('B'.$r, strtoupper($data['control_id']));
                    $sheet->setCellValue('C'.$r, $data['title']);
                    $sheet->setCellValue('D'.$r, $data['description']);
                    $sheet->setCellValue('E'.$r, con_category($data['category'], $con));
                    $sheet->setCellValue('F'.$r, con_eff($data['effectiveness']));
                    $sheet->setCellValue('G'.$r, con_freq($data['frequency']));
                    $sheet->setCellValue('H'.$r, $in_occured_date);
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
    
                #set values
                $spreadsheet->getProperties()->setCreator("RiskSafe")
                        ->setLastModifiedBy("RiskSafe")
                        ->setTitle("Controls Data Export - RiskSAFE")
                        ->setSubject("Controls Data Export - RiskSAFE");
    		}else{
    		    array_push($message, 'Error : No Controls To Be Exported');
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
  <title>Controls Reports | <?php echo $siteEndTitle ?></title>
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
                        <h3 class="d-inline">Control Reports</h3>
                        <a style='display:none;' class="btn btn-primary btn-icon icon-left header-a" href="../customs/new-control"><i class="fas fa-plus"></i> New Control</a>
                    </div>
                    <div class='card-body'>
                        <?php require '../../layout/alert.php' ?>
                        <form role="form" id="form" method="post">
        					<div class="form-group">
        					    <div class="card-bod">
                                    <?php 
                                        $list_one = listCustomControls(0, 10, $company_id, $con);
                                        $details_count = $list_one;
                                        if(count($details_count) <= 1){$details[] = $list_one;}else{$details = $list_one;}
                                        
                                        if($list_one !== false){
                                    ?>
                                    <table class="table table-striped table-bordered table-hover" id="table" style='margin:10px 0px;'>
                                        <tr>
                                            <th style="width: 5%;">S/N</th>
                                            <th>Title</th>
                                            <th>Description</th>
                                            <th>Effectiveness </th>
                                            <th>Frequency </th>
                                            <th>...</th>
                                        </tr>
                                    <?php if ($details === 'a:0:{}') { ?> 
                                    </table> 
                                    <div class="empty-table">
                                        No Controls Created Yet!!
                                        <div><a href='../customs/new-control' class='bb'><i class='fas fa-plus'></i> Create New Control</a></div>
                                    </div> 
                                    <?php }else{ $arrcount = count($details); $i = 0; foreach ($list_one as $item) { $i++;  ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $item['title']; ?></td>
                                            <td><?php echo $item['description']; ?></td>
                                            <td><?php echo getEffectiveness($item['effectiveness']); ?></td>
                                            <td><?php echo getFrequency($item['frequency']); ?></td>
                                            <td>...</td>
                                        </tr>
                                    <?php } ?> 
                                    </table>
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
                    		            <strong>NOTE:</strong> Earliest Registered Control - <?php echo $smallestNumber__1; ?> and Most Recent Registered Control - <?php echo $largestNumber__1; ?>
                    		        </div>
                    		        </div>
                                    <div style='width:100%;text-align:center;margin-top:10px;text-align:right;'>
                                        <button type="submit" class="btn btn-lg btn-primary btn-icon icon-left" name='export-report'><i class='fas fa-file-download'></i> Export Control Report</button>
                					</div>
                                    <?php } ?>
                                    <?php }else{ ?>
                                        <div class="empty-table"><h4>No Control Registered Yet!!</h4>
                                        <div style='margin-top:10px;'><a href='../customs/new-control' class='btn btn-icon btn-primary icon-left'><i class='fas fa-plus'></i> Create New Control</a></div></div>
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