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
    include '../../layout/admin_config.php';
   # include '../ajax/reports.php';
    include '../ajax/bia.php';
    require_once $file_dir.'classes/PHPExcel.php';
    
    $startDate = "";
    $endDate = "";
    
    if (isset($_POST["download"])) {
    	// Excel file name for download
    	$fileName = "Business Impact Analysis Report_" . date('Y-m-d') . ".xls";
    
    	// Column names
    	$fields = array('Critical Business Activity', 'Description', 'Priority', 'Impact of Loss', 'Recovery Time Objective', 'Preventative / Recovery Actions', 'Resource Requirements');
    
    	// Generate HTML table with column names as first row
    	$excelData = '<table>';
    
    	// Display column names as first row
    	$excelData .= '<tr>';
    	foreach ($fields as $field) {
    		$excelData .= '<th style="font-weight:bold;font-size:12pt">' . $field . '</th>';
    	}
    	$excelData .= '</tr>';
    
    	// Fetch records from the database
    	$list = listBIAForReport($company_id, $con);
    	//  print_r($list);
    	//  exit;
    	foreach ($list as $item) {
    		// Prepare line data for each item
    		$lineData = array(
    			$item['bia_activity'],
    			$item['bia_descript'],
    			$item['bia_priority'],
    			$item['bia_impact'],
    			$item['bia_time'],
    			$item['bia_action'],
    			$item['bia_resource']
    		);
    
    		// Filter the data for Excel
    		array_walk($lineData, 'filterData');
    
    		// Generate table row with data values
    		$excelData .= '<tr>';
    		foreach ($lineData as $value) {
    			$excelData .= '<td>' . $value . '</td>';
    		}
    		$excelData .= '</tr>';
    	}
    
    	$excelData .= '</table>';
    
    	// Headers for download
    	header("Content-Type: application/vnd.ms-excel");
    	header("Content-Disposition: attachment; filename=\"$fileName\"");
    
    	// Render excel data
    	echo $excelData;
    
    	exit;
    }
    
    // Filter the excel data
    function filterData(&$str)
    {
    	$str = preg_replace("/\t/", "\\t", $str);
    	$str = preg_replace("/\r?\n/", "\\n", $str);
    	if (strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
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
                        <h3 class="d-inline hide-md">Business Impact Analysis Report</h3>
                        <h3 class="d-inline show-md">BIA Report</h3>
                        <a class="btn btn-primary btn-icon icon-left header-a" href="../business/new-bia"><i class="fas fa-plus"></i> New BIA</a>
                    </div>
                    <div class='card-body'>
                        <form role="form" id="form" method="post">
        					<div class="form-group">
        					    <div class='bia-desc'>
        					        The BIA report documents the potential impacts resulting from the disruption of business functions and processes.
        					        Scenarios resulting in significant business interruption should be assessed in terms of financial impact,
        					        if possible. These costs should be compared with the costs for possible recovery strategies.
        					        <p>It consists of all business impact analysis created and modified on risksafe including:</p>
        					    </div>
        					    <div class="card-bod">
                                    <?php 
                                        $list_one = listBIAForReportCustom($company_id, $con, 5);
                                        $details = $list_one;
                                        foreach ($list_one as $item) {}
                                        if($list_one !== false){
                                    ?>
                                    <table class="payment-data">
                                        <tr>
                                            <th style="width: 5%;">S/N</th>
                                            <th>Business Activity</th>
                                            <th style="width: 30%;">BIA Description</th>
                                            <th>Priority </th>
                                            <th>...</th>
                                        </tr>
                                    <?php if ($details === 'a:0:{}') { ?> 
                                    </table> <div class="empty-table">No BIA Created Yet!!</div> 
                                    <?php }else{ $arrcount = count($details); for ($i=0; $i < $arrcount; $i++) {   ?>
                                        <tr>
                                            <td><?php echo $i+1; ?></td>
                                            <td><?php echo $item['bia_activity']; ?></td>
                                            <td><?php echo $item['bia_descript']; ?></td>
                                            <td><?php echo $item['bia_priority']; ?></td>
                                            <td>...</td>
                                        </tr>
                                    <?php } ?> 
                                    </table> 
                                    <div style='width:100%;text-align:center;margin-top:10px;'>
                					    <button type="submit" class="btn btn-lg btn-primary btn-icon icon-left" name='download'><i class='fas fa-file-download'></i> Export As Excel (.xls)</button>
                					</div>
                                    <?php } ?>
                                    <?php }else{ ?>
                                    <div class="empty-table">No BIA Created Yet!!</div>
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
    <style>
        .card{
            padding:10px;
        }
    </style>
</body>
</html>