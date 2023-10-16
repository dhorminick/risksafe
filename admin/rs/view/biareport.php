<?php

include_once("../controller/auth.php");
include_once("../config.php");
include_once('../model/bia.php');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';

$startDate = "";
$endDate = "";

$bia = new bia();
if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "downloadxls") {
	// Excel file name for download
	$fileName = "Business Impact Analysis Report_" . date('Y-m-d') . ".xls";

	// Column names
	$fields = array('Critical Business Activity', 'Description', 'Priority', 'Impact of Loss', 'Recovery Time Objective', 'Preventative/Recovery Actions', 'Resource Requirements');

	// Generate HTML table with column names as first row
	$excelData = '<table>';

	// Display column names as first row
	$excelData .= '<tr>';
	foreach ($fields as $field) {
		$excelData .= '<th style="font-weight:bold;font-size:12pt">' . $field . '</th>';
	}
	$excelData .= '</tr>';

	// Fetch records from the database
	$list = $bia->listBIAForReport();
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
	<?php include_once("header.php"); ?>
</head>

<body>
	<!-- header -->
	<div id="top-nav" class="navbar navbar-inverse navbar-static-top" style="background-color:#09F;border:0;">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
				<a class="navbar-brand" href="main.php" style="font-weight:900;color:#fff;"><?php echo APP_TITLE; ?></a>
			</div>
			<div class="navbar-collapse collapse">
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown"> <a class="dropdown-toggle" role="button" data-toggle="dropdown" href="#" style="font-weight:900;color:#fff;"><i class="glyphicon glyphicon-user"></i> Account <span class="caret"></span></a>
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
				<h1 class="page-header">Business Impact Analysis Report</h1>

				<div class="">

					<div class="alert <?php if (isset($msgClass)) echo $msgClass; ?>" id="notify" <?php if (!isset($msg)) { ?>style="display: none;" <?php } ?>>
						<?php if (isset($msg)) echo $msg; ?>
					</div>

					<div class="panel panel-default">
						<div class="panel-body">
							<form role="form" id="form" action="../view/biareport.php" method="post">
								<input type="hidden" name="action" value="downloadxls" />
								<!--
		         <div class="form-group">
		            <label>Start Date</label>
		            <input value="<?php echo $startDate; ?>" name="startDate" type="text" class="form-control datepicker" placeholder="Enter start date" required>        
		          </div>
		           <div class="form-group">
		            <label>End Date</label>
		            <input value="<?php echo $endDate; ?>" name="endDate" type="text" class="form-control datepicker" placeholder="Enter end date" required>
		        
		          </div>-->
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

	<?php include_once("footer.php"); ?>
	<script>
		$(document).ready(function(e) {
			$(".datepicker").datepicker();
		});
	</script>
	</script>
</body>

</html>