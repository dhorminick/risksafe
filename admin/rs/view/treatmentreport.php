<?php

include_once("../controller/auth.php");
include_once("../config.php");
include_once('../model/treatment.php');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';
require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';
require_once dirname(__FILE__) . '/../Classes/PHPExcel/IOFactory.php';
require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

$startDate = "";
$endDate ="";

function getTreStatus($status){
	if($status == 1){
		return "In Progress";
	} else if($status == 2){
		return "Completed";
	} else if($status == 3){
		return "Cancelled";
	}
}


$treatment = new treatment();
if (isset($_POST["action"]) && $_POST["action"]=="downloadxls") {
	
	//$startDate = $_REQUEST['startDate'];
	//$endDate = $_REQUEST['endDate'];
	
	$objPHPExcel = new Spreadsheet();
	$objPHPExcel->getProperties()->setCreator("RiskSafe")
								 ->setLastModifiedBy("RiskSafe")
								 ->setTitle("Treatment Status Report")
								 ->setSubject("Treatment Status Report")
								 ->setDescription("")
								 ->setKeywords("")
								 ->setCategory("");
	$row = 1;						 
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$row, 'Risk Safe')
	            ->setCellValue('B'.$row, 'Treatment Status Report ');	            								 
	
	$row = 2;		
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$row, 'Number ID')
	            ->setCellValue('B'.$row, 'Treatment')
	            ->setCellValue('C'.$row, 'Cost/Benefits')
	            ->setCellValue('D'.$row, 'Progress Update ')
	            ->setCellValue('E'.$row, 'Owner')
				->setCellValue('F'.$row, 'Start Date')
				->setCellValue('G'.$row, 'Due Date')
				->setCellValue('H'.$row, 'Status');
	$row++;		
	
	$list = $treatment->listTreatmentsForReport();
	foreach ($list as $item) {
		
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$row, $item['idtreatment'])
	            ->setCellValue('B'.$row, $item['tre_treatment'])
	            ->setCellValue('C'.$row, $item['tre_cost_ben'])
	            ->setCellValue('D'.$row, $item['tre_progress'])
	            ->setCellValue('E'.$row, $item['tre_owner'])
				->setCellValue('F'.$row, $item['tre_start'])
				->setCellValue('G'.$row, $item['tre_due'])
				->setCellValue('H'.$row, getTreStatus($item['tre_status']));
		$row++;	
	}

	$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A2:H2')->getFont()->setBold(true);
	
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);	

	
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->setTitle('Treatment Status Report');
	
	// Redirect output to a client’s web browser (Excel2007)
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="Treatment Status Report.xlsx"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');
	
	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0
	
	$writer = new Xlsx($objPHPExcel);
    $writer->save('php://output');
	exit;
	
} else {
	
}

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
    <h1 class="page-header">Treatment Status Report</h1>
    
  		<div class="">
  	
		  	<div class="alert <?php if (isset($msgClass)) echo $msgClass;?>" id="notify" <?php if (!isset($msg)) { ?>style="display: none;"<?php } ?>>
		    	<?php if (isset($msg)) echo $msg;?>
		  	</div>
		  	
		    <div class="panel panel-default">
		      <div class="panel-body">
		        <form role="form" id="form" action="../view/treatmentreport.php" method="post">
		        	<input type="hidden" name="action" value="downloadxls" />
		         
		         <!--
			         <div class="form-group">
			            <label>Start Date</label>
			            <input value="<?php echo $startDate;?>" name="startDate" type="text" class="form-control datepicker" placeholder="Enter start date" required>        
			          </div>
		           <div class="form-group">
			            <label>End Date</label>
			            <input value="<?php  echo $endDate; ?>" name="endDate" type="text" class="form-control datepicker" placeholder="Enter end date" required>
			        
			          </div>
		         -->
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