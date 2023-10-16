<?php

include_once("../controller/auth.php");
include_once("../config.php");
include_once('../model/antimoney.php');
/** Include PHPExcel */
require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';

$as = new antimoney();
if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "downloadxls") {
    $id = $_REQUEST['id'];

    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getProperties()->setCreator("RiskSafe")
        ->setLastModifiedBy("RiskSafe")
        ->setTitle("Operational Risk Profile Report")
        ->setSubject("Operational Risk Profile Report")
        ->setDescription("")
        ->setKeywords("")
        ->setCategory("");
    $row = 1;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $row, 'Risk Safe')
        ->setCellValue('B' . $row, 'Operational Risk Profile Report');

    $row = 3;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $row, 'Type of Assessment')
        ->setCellValue('B' . $row, 'Team or Company')
        ->setCellValue('C' . $row, 'Task/Process')
        ->setCellValue('D' . $row, 'Business/Process Owner')
        ->setCellValue('E' . $row, 'AML#')
        ->setCellValue('F' . $row, 'Approval');
    $row++;
    $assessment = $as->getAssessment($id);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $row, $assessment['ty_name'])
        ->setCellValue('B' . $row, $assessment['as_team'])
        ->setCellValue('C' . $row, $assessment['as_task'])
        ->setCellValue('D' . $row, $assessment['as_owner'])
        ->setCellValue('E' . $row, $assessment['id'])
        ->setCellValue('F' . $row, $as->getApproval($assessment['as_approval']));

    $row = 6;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $row, 'Risk')
        ->setCellValue('B' . $row, 'Description')
        ->setCellValue('C' . $row, 'Risk Description')
        ->setCellValue('D' . $row, 'Likelihood')
        ->setCellValue('E' . $row, 'Consequence')
        ->setCellValue('F' . $row, 'Risk Rating')
        ->setCellValue('G' . $row, 'Controls')
        ->setCellValue('H' . $row, 'Control Effectiveness or Gaps')
        ->setCellValue('I' . $row, 'Action Type')
        ->setCellValue('J' . $row, 'Treatment Plan')
        ->setCellValue('K' . $row, 'Due Date')
        ->setCellValue('L' . $row, 'Action Owner');
    $row++;

    $list = $as->getAssessmentDetForReport($id);
    foreach ($list as $item) {
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $row, $item['aml_cat'])
            ->setCellValue('B' . $row, $item['aml_subcat'])
            ->setCellValue('C' . $row, $item['aml_descript'])
            ->setCellValue('D' . $row, $item['as_like'])
            ->setCellValue('E' . $row, $item['as_consequence'])
            ->setCellValue('F' . $row, $item['as_rating'])
            ->setCellValue('G' . $row, $as->listControlsForReport($item['iddetail']))
            ->setCellValue('H' . $row, $item['as_effect'])
            ->setCellValue('I' . $row, $item['as_action'])
            ->setCellValue('J' . $row, $as->listTreatmentsForReport($item['iddetail']))
            ->setCellValue('K' . $row, $item['as_duedate'])
            ->setCellValue('L' . $row, $item['as_owner']);
        $row++;
    }

    $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A3:I3')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A6:L6')->getFont()->setBold(true);

    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(60);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(60);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(40);
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(40);
    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(40);
    $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(12);
    $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);

    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->setTitle('Operational Risk Profile Report');

    try {
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Operational Risk Profile Report.xlsx"');
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
    } catch (Exception $e) {
        // Handle the exception
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<?php include_once("header.php");?>
</head>
<style>
	 .background{
    height: auto;
			background: rgb(0, 46, 76);
			background: linear-gradient(90deg, rgba(0, 46, 76, 1) 0%, rgba(0, 153, 255, 1) 35%, rgba(0, 107, 179, 1) 100%);
			display: flex;
      margin-bottom: 1rem;
			justify-content: flex-end;
			align-items: center;
			padding: 2rem 2rem;
			border-bottom-left-radius: 2rem;
			border-top-right-radius: 2rem;
			
  }
  .heading {
			display: flex;
			align-items: center;
		}
</style>
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
    <h1 class="page-header">My Anti Money Laundering</h1>
    
    <div class="">
    <div class="panel panel-default">
      <div class="panel-body">
          
          <div class="clearfix mb20 background">
			<div class="heading">
			<button type="button" style="border:none; outline:none;" class="btn btn-md btn-info pull-right" id="btn_add">+ New AML</button>
			</div>

          	
          </div>
          
         <table class="table table-striped table-bordered table-hover" id="table">
          <thead>
            <tr>
              <th>#</th>
              <th>Team or Organisation</th>
              <th>Task or Process</th>
              <th>Type of Assessment</th>
              <th>Date</th>
              <th>&nbsp;</th>
            </tr>
          </thead>
          <tbody>
          	<tr><td colspan="5">Loading...</td></tr>
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
   		{ "width": "20" },
		{},
		{ "width": "35%" },
		{ "width": "55" },
		{ "width": "100" }
		],
        "ajax": "../controller/antimoney.php?action=listassess"
    } );
	
	 $("#btn_add").click(function(e) {
	  $(location).attr("href","../view/newantimoney.php");
  });

	
}); 

function del(id) {
	
BootstrapDialog.show({
	title:"<i class='glyphicon glyphicon-trash'></i> Warning",
	type: BootstrapDialog.TYPE_DANGER,
	message: 'Are you sure you want to delete this antimoney?',
	buttons: [{
		label: 'Cancel',
		action: function(dialogItself){
			dialogItself.close();
		},
		
	},{
		label: 'Delete',
		cssClass:'btn-danger',
		action: function(dialogItself){
			res = $.ajax({type: "GET", url: "../controller/antimoney.php?action=deleteassess&id="+id, async: false})	
		    $('#table').DataTable().ajax.reload();
			dialogItself.close();
		}
	}]
});//end dialog	
}
</script>
</body>
</html>