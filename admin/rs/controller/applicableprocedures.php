<?php

include_once("../controller/auth.php");
include_once("../config.php");
include_once('../model/applicableProcedure.php');

require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';
$apply = new applicableProcedure();
if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "downloadxls") {

  $id = $_REQUEST['id'];

  $objPHPExcel = new PHPExcel();
  $objPHPExcel->getProperties()->setCreator("RiskSafe")
    ->setLastModifiedBy("RiskSafe")
    ->setTitle("Applicable Procedure Summary Report")
    ->setSubject("Applicable Procedure Summary Report")
    ->setDescription("")
    ->setKeywords("")
    ->setCategory("");
  $row = 1;
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $row, 'Risk Safe')
    ->setCellValue('B' . $row, 'Applicable procedure Summary Report');

  $row = 3;
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $row, 'Procedure Title')
    ->setCellValue('B' . $row, 'Procedure Number')
 
    ->setCellValue('C' . $row, 'Procedure Description')
    ->setCellValue('D' . $row, 'Procedure Effective Date')
    ->setCellValue('E' . $row, 'Procedure Review Date')
    ->setCellValue('F' . $row, 'Applicability')
    ->setCellValue('G' . $row, 'Compliance Requirements')
    ->setCellValue('H' . $row, 'Resources')
    ->setCellValue('I' . $row, 'Procedure Approval')
    ->setCellValue('J' . $row, 'Procedure Review')
    ->setCellValue('K' . $row, 'Procedure Acknowledgment');
  $row++;
  $applypolicy = $apply->getApplicableProcedure($id);
  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $row, $applypolicy['ProcedureTitle'])
    ->setCellValue('B' . $row, $applypolicy['ProcedureNumber'])
    ->setCellValue('C' . $row, $applypolicy['ProcedureDescription'])
    ->setCellValue('D' . $row, $applypolicy['ProcedureEffectiveDate'])
    ->setCellValue('E' . $row, $applypolicy['ProcedureReviewDate'])
    ->setCellValue('F' . $row, $applypolicy['Applicability'])
    ->setCellValue('G' . $row, $applypolicy['ComplianceRequirements'])
    ->setCellValue('H' . $row, $applypolicy['Resources'])
    ->setCellValue('I' . $row, $applypolicy['ProcedureApproval'])
    ->setCellValue('J' . $row, $applypolicy['ProcedureReview'])
    ->setCellValue('K' . $row, $applypolicy['ProcedureAcknowledgment']);

  $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(true);
  $objPHPExcel->getActiveSheet()->getStyle('A3:I3')->getFont()->setBold(true);
  $objPHPExcel->getActiveSheet()->getStyle('A6:G6')->getFont()->setBold(true);

  $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(60);
  $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(60);
  $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
  $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
  $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
  $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
  $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(35);
  $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(35);
  $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(35);
  $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(35);
  $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(35);
 


  $objPHPExcel->setActiveSheetIndex(0);
  $objPHPExcel->getActiveSheet()->setTitle('Test Summary Report');

  // Redirect output to a client’s web browser (Excel2007)
  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename="Applicable Procedure Summary Report.xlsx"');
  header('Cache-Control: max-age=0');
  // If you're serving to IE 9, then the following may be needed
  header('Cache-Control: max-age=1');

  // If you're serving to IE over SSL, then the following may be needed
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
  header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
  header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
  header('Pragma: public'); // HTTP/1.0

  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
  $objWriter->save('php://output');
  exit;

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
    <h1 class="page-header">Applicable Procedure</h1>
  <div class="col-lg-12 col-md-12">
    <div class="panel panel-default">
      <div class="panel-body">
       	<div class="clearfix mb20">
          <button type="button" class="btn btn-md btn-info pull-right" id="btn_add">+ New Applicable Procedure</button>
          </div>
         <table class="table table-striped table-bordered table-hover" id="table" style="width:100%;">
          <thead>
            <tr>
              <th>#</th>
              <th>Procedure Title</th>
              <th>Procedure Number</th>
              <th>Procedure Description</th>
              <th>Procedure Effective Date</th>
              <th>Procedure Review Date</th>
              <th>&nbsp;</th>
            </tr>
          </thead>
          <tbody>
          	<tr><td colspan="7">Loading...</td></tr>
          </tbody>
         </table> 

      </div>
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
 
   var table = $('#table').dataTable( {
        "processing": true,
        "serverSide": true,
		"stateSave": true,
		"bFilter": false,
		"ordering": false,
		"columns": [
		{ "width": "25" },
		{ },
   		{ "width": "65" },
		{ "width": "25" },
		{ "width": "20" },
		{ "width": "25" },
		{ "width": "110" }
		],
        "ajax": "../controller/applicableprocedure.php?action=list"
    } );

  $("#btn_add").click(function(e) {
	  $(location).attr("href","../view/applicableprocedure.php?action=add");
  });

	
}); 

function del(id) {
	BootstrapDialog.show({
						message: 'Are you sure you want to delete this entry?',
						buttons: [{
							label: 'No, go back',
							action: function(dialogItself){
								dialogItself.close();
							},
							
						},{
							label: 'Yes, delete',
							action: function(dialogItself){
								//kod za booking
								res = $.ajax({type: "GET", url: "../controller/applicableprocedure.php?action=delete&id="+id, async: false})	
							    $('#table').DataTable().ajax.reload();
								dialogItself.close();
							}
						}]
					});//end dialog	
}

</script>
</body>
</html>