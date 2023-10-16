<?php

// include_once("../controller/auth.php");
// include_once("../config.php");
include_once('../model/assessment.php');

/** Include PHPExcel */
$as = new assessment();
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
	$fileName = "Risk Assessments Summary Report" . date('Y-m-d') . ".xls";
  
	// Column names
	$fields = array('Type of Assessment', 'Team or Company','Description of Task or Process', 'Task/Process', 'Business/Process Owner', 'Assessment #', 'Approval');

	$fields2 = array('Risk','Description','Risk Description','Likelihood','Consequence','Risk Rating','Controls','Control Effectiveness or Gaps','Action Type','Treatment Plan','Due Date','Action Owner');
  
	// Generate HTML table with column names as first row
	$excelData = '<table>';
  
	// Display column names as first row
	$excelData .= '<tr>';
	foreach ($fields as $field) {
		$excelData .= '<th style="font-weight:bold;font-size:36pt">' . $field . '</th>';
	}
	$excelData .= '</tr>';
	$excelData2 = '<table>';
  
	// Display column names as first row
	$excelData2 .= '<tr>';
	foreach ($fields2 as $field) {
		$excelData2 .= '<th style="font-weight:bold;font-size:36pt">' . $field . '</th>';
	}
	$excelData2 .= '</tr>';
  
	// Fetch records from the database
	$assessment = $as->getAssessment($id);
  
	// Generate table row with data values
	$excelData .= '<tr>';
	$lineData = array($assessment['ty_name'], $assessment['as_team'],$assessment['as_descript'], $assessment['as_task'], $assessment['as_owner'], $assessment['idassessment'], $as->getApproval($assessment['as_approval']));
	array_walk($lineData, 'filterData');
	foreach ($lineData as $value) {
		$excelData .= '<td>' . $value . '</td>';
	}
	$excelData .= '</tr>';
  
	$excelData .= '</table>';

	$list = $as->getAssessmentDetForReport($id);
	$excelData2 .= '<tr>';
	$lineData2 = array();
	foreach ($list as $item) {
		$lineData2 = array($item['ri_name'], $item['cat_name'], $item['as_descript'], $item['li_like'], $item['con_consequence'], $as->getRating($item['as_rating']),$as->listControlsForReport($item['iddetail']),$item['as_effect'],$item['ac_type'],$as->listTreatmentsForReport($item['iddetail']),$item['as_duedate'],$item['as_owner']);
	}
	
	
	array_walk($lineData2, 'filterData');
	foreach ($lineData2 as $value) {
		$excelData2 .= '<td>' . $value . '</td>';
	}
	$excelData2 .= '</tr>';
  
	$excelData2 .= '</table>';
	// Headers for download
	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=\"$fileName\"");
  
	// Render excel data
	echo $excelData;
	echo'<br>';
	echo $excelData2;
  
	exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<?php include_once("header.php"); ?>
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
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span
						class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
				<a class="navbar-brand" href="main.php" style="font-weight:900;color:#fff;">
					<?php echo APP_TITLE; ?>
				</a>
			</div>
			<div class="navbar-collapse collapse">
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown"> <a class="dropdown-toggle" role="button" data-toggle="dropdown" href="#"
							style="font-weight:900;color:#fff;"><i class="glyphicon glyphicon-user"></i> Account <span
								class="caret"></span></a>
						<ul id="g-account-menu" class="dropdown-menu" role="menu">
							<?php include_once("menu_top.php"); ?>
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
				<?php include_once("menu.php"); ?>
				<!-- /col-3 -->
			</div>
			<div class="col-lg-9 col-md-12">
				<h1 class="page-header">My Risk Assessments</h1>

				<div class="">
					<div class="panel panel-default">
						<div class="panel-body">

							<div class="clearfix mb20 background">
								<div class="heading">
								<button type="button" style="border:none; outline:none;" class="btn btn-md btn-info pull-right" id="btn_add">+ New Risk
									Assessment</button>

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
									<tr>
										<td colspan="6">Loading...</td>
									</tr>
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

	<?php include_once("footer.php"); ?>
	<script>
		$(document).ready(function (e) {

			var table = $('#table').dataTable({
				"processing": true,
				"serverSide": true,
				"stateSave": true,
				"bFilter": false,
				"ordering": false,
				"columns": [
					{ "width": "20" },
					{},
					{ "width": "35%" },
					{ "width": "25" },
					{ "width": "55" },
					{ "width": "100" }
				],
				"ajax": "../controller/assessment.php?action=listassess"
			});

			$("#btn_add").click(function (e) {
				$(location).attr("href", "../view/newassessment.php");
			});


		});

		function del(id) {

			BootstrapDialog.show({
				title: "<i class='glyphicon glyphicon-trash'></i> Warning",
				type: BootstrapDialog.TYPE_DANGER,
				message: 'Are you sure you want to delete this assessment?',
				buttons: [{
					label: 'Cancel',
					action: function (dialogItself) {
						dialogItself.close();
					},

				}, {
					label: 'Delete',
					cssClass: 'btn-danger',
					action: function (dialogItself) {
						res = $.ajax({ type: "GET", url: "../controller/assessment.php?action=deleteassess&id=" + id, async: false })
						$('#table').DataTable().ajax.reload();
						dialogItself.close();
					}
				}]
			});//end dialog	
		}
	</script>
</body>

</html>