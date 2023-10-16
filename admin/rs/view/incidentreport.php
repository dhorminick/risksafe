<?php

include_once("../controller/auth.php");
include_once("../config.php");
include_once('../model/incidents.php');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';

$startDate = "";
$endDate ="";
$msg ='';
$msgClass = '';
$incident = new incidents();
if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "downloadxls") {
	$startDate = $_REQUEST['startDate'];
	$endDate = $_REQUEST['endDate'];
    // Excel file name for download
    $fileName = "Incident Report_" . date('Y-m-d') . ".xls";

    // Column names
    $fields = array('Incident Number', 'Case Title', 'Date Occurred', 'Reported By', 'Team or Department', 'Description', 'Impact', 'Priority','Status');

    // Generate HTML table with column names as first row
    $excelData = '<table>';
	$excelData .= '<tr style="background-color: #0074D9; color: white; font-size: 14pt; text-align: center;">';
$excelData .= '<th colspan="' . count($fields) . '">Incident Report From ' . $startDate . ' To ' . $endDate . '</th>';
    $excelData .= '</tr>';
    // Display column names as first row
    $excelData .= '<tr>';
    foreach ($fields as $field) {
        $excelData .= '<th style="font-weight:bold;font-size:12pt">' . $field . '</th>';
    }
    $excelData .= '</tr>';
    $startDate1 = DateTime::createFromFormat('m/d/Y', $startDate);
    $endDate1 = DateTime::createFromFormat('m/d/Y', $endDate);
 
       if($endDate1 > $startDate1){
      
        $list = $incident->listIncidentsForReport($startDate, $endDate);
     
        
    foreach ($list as $item) {
      // Prepare line data for each item
      $lineData = array(
          $item['idincident'],
          $item['in_title'],
          $item['in_date'],
          $item['in_reported'],
          $item['in_team'],
          $item['in_descript'],
          $item['in_impact'],
          $item['in_priority'],
          $item['in_status']
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

  header("Content-Type: application/vnd.ms-excel");
  header("Content-Disposition: attachment; filename=\"$fileName\"");


  // Render excel data
  echo $excelData;

  exit;
       }else{
        $msg='End date should not be earlier than start date';
        $msgClass = "alert-danger";
       }
    // Fetch records from the database
    // Headers for download
  
}
function filterData(&$str)
{
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if (strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
}
// if (isset($_POST["action"]) && $_POST["action"]=="downloadxls") {	
	
// 	$startDate = $_REQUEST['startDate'];
// 	$endDate = $_REQUEST['endDate'];
	
// 	$objPHPExcel = new PHPExcel();
// 	$objPHPExcel->getProperties()->setCreator("RiskSafe")
// 								 ->setLastModifiedBy("RiskSafe")
// 								 ->setTitle("Incident Report")
// 								 ->setSubject("Incident Report")
// 								 ->setDescription("")
// 								 ->setKeywords("")
// 								 ->setCategory("");
// 	$row = 1;						 
// 	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$row, 'Risk Safe')
// 	            ->setCellValue('B'.$row, 'Incident Report From '.$startDate.' To '.$endDate);	            								 
	
// 	$row = 2;		
// 	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$row, 'Incident Number')
// 	            ->setCellValue('B'.$row, 'Case Title')
// 	            ->setCellValue('C'.$row, 'Date Occurred')
// 	            ->setCellValue('D'.$row, 'Reported By')
// 	            ->setCellValue('E'.$row, 'Team or Department')
// 				->setCellValue('F'.$row, 'Description')
// 				->setCellValue('G'.$row, 'Impact')
// 				->setCellValue('H'.$row, 'Priority')
// 				->setCellValue('I'.$row, 'Status');
// 	$row++;		
	
// 	$list = $incident->listIncidentsForReport($startDate, $endDate);
// 	foreach ($list as $item) {
// 		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$row, $item['idincident'])
// 	            ->setCellValue('B'.$row, $item['in_title'])
// 	            ->setCellValue('C'.$row, $item['in_date'])
// 	            ->setCellValue('D'.$row, $item['in_reported'])
// 	            ->setCellValue('E'.$row, $item['in_team'])
// 				->setCellValue('F'.$row, $item['in_descript'])
// 				->setCellValue('G'.$row, $item['in_impact'])
// 				->setCellValue('H'.$row, $item['in_priority'])
// 				->setCellValue('I'.$row, $item['in_status']);
// 		$row++;	
// 	}

// 	$objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(true);
//     $objPHPExcel->getActiveSheet()->getStyle('A2:I2')->getFont()->setBold(true);
	
// 	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
//     $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
// 	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
//     $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
// 	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
//     $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(60);
// 	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
//     $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
// 	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);    

	
// 	$objPHPExcel->setActiveSheetIndex(0);
// 	$objPHPExcel->getActiveSheet()->setTitle('Incident Report');
// 	/*
// 	$objDrawing = new PHPExcel_Worksheet_Drawing();
// 	$objDrawing->setName('Logo');
// 	$objDrawing->setDescription('Logo');
// 	$logo = DIR_PATH. '/../img/logo.png'; // Provide path to your logo file
// 	$objDrawing->setPath($logo);
// 	$objDrawing->setOffsetX(8);    // setOffsetX works properly
// 	$objDrawing->setOffsetY(300);  //setOffsetY has no effect
// 	$objDrawing->setCoordinates('A1');
// 	$objDrawing->setHeight(75); // logo height
// 	$objDrawing->setWorksheet($objPHPExcel->getActiveSheet()); 
// 	*/
// 	// Redirect output to a client’s web browser (Excel2007)
// 	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
// 	header('Content-Disposition: attachment;filename="Incident Report'.$startDate.' - '.$endDate.'.xlsx"');
// 	header('Cache-Control: max-age=0');
// 	// If you're serving to IE 9, then the following may be needed
// 	header('Cache-Control: max-age=1');
	
// 	// If you're serving to IE over SSL, then the following may be needed
// 	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
// 	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
// 	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
// 	header ('Pragma: public'); // HTTP/1.0
	
// 	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
// 	$objWriter->save('php://output');
// 	exit;
	
// } else {
	
// }

?>

<!DOCTYPE html>
<html lang="en">
<head>
<?php include_once("header.php");?>
</head>
<body>
<!-- header -->
<div id="top-nav" class="navbar navbar-inverse navbar-static-top" style="background-color:#09F;border:0;">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
      <a class="navbar-brand" href="main.php" style="font-weight:900;color:#fff;"><?php echo APP_TITLE;?></a> </div>
    <div class="navbar-collapse collapse">
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown"> <a class="dropdown-toggle" role="button" data-toggle="dropdown" href="#" style="font-weight:900;color:#fff;"><i class="glyphicon glyphicon-user"></i> Account <span class="caret"></span></a>
          <ul id="g-account-menu" class="dropdown-menu" role="menu">
			<?php include_once("menu_top.php");?>
          </ul>
        </li>
      </ul>
    </div>
  </div>
  <!-- /container --> 
</div>
<!-- /Header --> 

<!-- Main -->
<div class="container-fluid">
<div class="row">
  <div class="col-lg-3 col-sm-12"> 
    <!-- Left column --> 
	<?php include_once("menu.php");?>
  <!-- /col-3 -->
  </div>
  <div class="col-lg-9 col-md-12">
    <h1 class="page-header">Incident Report</h1>
    
  		<div class="">
  	
		  	<div class="alert <?php if (isset($msgClass)) echo $msgClass;?>" id="notify" <?php if (!isset($msg)) { ?>style="display: none;"<?php } ?>>
		    	<?php if (isset($msg)) echo $msg;?>
		  	</div>
		  	
		    <div class="panel panel-default">
		      <div class="panel-body">
		        <form role="form" id="form" action="../view/incidentreport.php" method="post">
		        	<input type="hidden" name="action" value="downloadxls" />
		         <div class="form-group">
		            <label>Start Date</label>
		            <input value="<?php echo $startDate;?>" name="startDate" type="text" class="form-control datepicker" placeholder="Enter start date" required>        
		          </div>
		           <div class="form-group">
		            <label>End Date</label>
		            <input value="<?php  echo $endDate; ?>" name="endDate" type="text" class="form-control datepicker" placeholder="Enter end date" required>
		        
		          </div>
		          <div class="form-group">
		          	  <button type="submit" class="btn btn-md btn-info" id="btn_save">Download Excel</button>			                  			          			        
		          </div>
		        </form>
		      </div>
		    </div>
		  </div>
  </div>
  <!--/col-span-9--> 
</div>  
</div>

<!-- /Main -->

<?php include_once("footer.php");?>
<script>
$(document).ready(function(e) {
 	$( ".datepicker" ).datepicker(); 	
}); 
  </script>
</script>
</body>
</html>