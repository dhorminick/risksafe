<?php

include_once("../controller/auth.php");
include_once("../config.php");
include_once('../model/applicableProcedure.php');

require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';
$apply = new applicableProcedure();
if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "downloadxls") {


  $id = $_REQUEST['id'];

  // Filter the excel data
  function filterData(&$str)
  {
      $str = preg_replace("/\t/", "\\t", $str);
      $str = preg_replace("/\r?\n/", "\\n", $str);
      if (strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
  }

  // Excel file name for download
  $fileName = "Applicable Procedure Summary Report" . date('Y-m-d') . ".xls";

  // Column names
  $fields = array('Procedure Title', 'Procedure Number', 'Procedure Description', 'Procedure Effective Date', 'Procedure Review Date', 'Applicability','Compliance Requirements','Resources','Procedure Approval','Procedure Review','Procedure Acknowledgment');

  // Generate HTML table with column names as first row
  $excelData = '<table>';

  // Display column names as first row
  $excelData .= '<tr>';
  foreach ($fields as $field) {
      $excelData .= '<th style="font-weight:bold;font-size:36pt">' . $field . '</th>';
  }
  $excelData .= '</tr>';

  // Fetch records from the database
  $applypolicy = $apply->getApplicableProcedure($id);

  // Generate table row with data values
  $excelData .= '<tr>';
  $lineData = array($applypolicy['ProcedureTitle'], $applypolicy['ProcedureNumber'], $applypolicy['ProcedureDescription'], $applypolicy['ProcedureEffectiveDate'], $applypolicy['ProcedureReviewDate'], $applypolicy['Applicability'],$applypolicy['ComplianceRequirements'],$applypolicy['Resources'],$applypolicy['ProcedureApproval'],$applypolicy['ProcedureReview'],$applypolicy['ProcedureAcknowledgment']);
  array_walk($lineData, 'filterData');
  foreach ($lineData as $value) {
      $excelData .= '<td>' . $value . '</td>';
  }
  $excelData .= '</tr>';

  $excelData .= '</table>';

  // Headers for download
  header("Content-Type: application/vnd.ms-excel");
  header("Content-Disposition: attachment; filename=\"$fileName\"");

  // Render excel data
  echo $excelData;

  exit;

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
    #btn_add{
      border: none;
      outline: none;
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
    <h1 class="page-header">Applicable Procedure</h1>
  <div class="col-lg-12 col-md-12">
    <div class="panel panel-default">
      <div class="panel-body">
       	<div class="clearfix mb20 background">
          <div class="heading">
          <button type="button" class="btn btn-md btn-info pull-right" id="btn_add">+ New Applicable Procedure</button>
          </div>
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