<?php

include_once("../controller/auth.php");
include_once("../config.php");
include_once('../model/audit.php');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
/** Include PHPExcel */

require_once dirname(__FILE__) . '/../Classes/PHPExcel/IOFactory.php';
require '../../vendor/autoload.php';
$startDate = "";
$endDate ="";

$au = new audit();
if (isset($_POST["action"]) && $_POST["action"]=="downloadxls") {
	
	$startDate = $_REQUEST['startDate'];
	$endDate = $_REQUEST['endDate'];
	
	$objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
	$objPHPExcel->getProperties()->setCreator("RiskSafe")
								 ->setLastModifiedBy("RiskSafe")
								 ->setTitle("Controls Dashboard Report")
								 ->setSubject("Controls Dashboard Report")
								 ->setDescription("")
								 ->setKeywords("")
								 ->setCategory("");
	$row = 1;						 
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$row, 'Risk Safe')
	            ->setCellValue('B'.$row, 'Controls Dashboard Report From '.$startDate.' To '.$endDate);	            								 
	
	$row = 2;		
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$row, 'Control Name')
	            ->setCellValue('B'.$row, 'Control ID')
	            ->setCellValue('C'.$row, 'Test Date')
	            ->setCellValue('D'.$row, 'Effectiveness')
	            ->setCellValue('E'.$row, 'Observation')
				->setCellValue('F'.$row, 'Frequency')
				->setCellValue('G'.$row, 'Next Test Date');
				
	$row++;		
	$startDate1 = DateTime::createFromFormat('m/d/Y',$startDate);
	$endDate1 = DateTime::createFromFormat('m/d/Y',$endDate);
	if($startDate1 < $endDate1){
		$list = $au->listAuditControlsForReport($startDate, $endDate);
		foreach ($list as $item) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$row, $item['con_control'])
					->setCellValue('B'.$row, $item['idcontrol'])
					->setCellValue('C'.$row, $item['con_date'])
					->setCellValue('D'.$row, $au->getEffectiveness($item['con_effect']))
					->setCellValue('E'.$row, $item['con_observation'])
					->setCellValue('F'.$row, $au->getFrequency($item['con_frequency']))
					->setCellValue('G'.$row, $au->getNext($item['con_date'], $item['con_frequency']));				
			$row++;	
		}
	
		$objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getFont()->setBold(true);
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
		
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setTitle('Controls Dashboard Report');
		
		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="Controls Dashboard Report'.$startDate.' - '.$endDate.'.xlsx"');
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
	}else{		
		$msg='End date should not be earlier than start date';
        $msgClass = "alert-danger";
	}
	
	
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
    <h1 class="page-header">Controls Dashboard Report</h1>
    
  		<div class="">
  	
		  	<div class="alert <?php if (isset($msgClass)) echo $msgClass;?>" id="notify" <?php if (!isset($msg)) { ?>style="display: none;"<?php } ?>>
		    	<?php if (isset($msg)) echo $msg;?>
		  	</div>
		  	
		    <div class="panel panel-default">
		      <div class="panel-body">
		        <form role="form" id="form" action="../view/controlreport.php" method="post">
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