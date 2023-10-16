<?php

include_once("../controller/auth.php");
include_once("../config.php");
include_once('../model/assessment.php');

// not needed anymore
if (isset($_REQUEST["id"])) {
	$_SESSION["assessment"] = $_REQUEST["id"];
}
if (isset($_REQUEST['response']) && $_REQUEST['response'] == true) {
	$msg = "Information saved successfully.";
}

$assess = new assessment();
$info = $assess->getAssessment($_REQUEST["id"]);

//approval
switch ($info["as_approval"]) {

	case 1:
		$approval = 'In progress';
		break;

	case 2:
		$approval = 'Approved';
		break;

	case 3:
		$approval = 'Closed';
		break;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<?php include_once("header.php"); ?>
	<style>
		.form-group {
			height: auto;
			background: rgb(0, 46, 76);
			background: linear-gradient(90deg, rgba(0, 46, 76, 1) 0%, rgba(0, 153, 255, 1) 35%, rgba(0, 107, 179, 1) 100%);
			display: flex;
			justify-content: space-between;
			align-items: center;
			padding: 0rem 2rem;
			border-bottom-left-radius: 2rem;
			border-top-right-radius: 2rem;
		

		}

		.heading {
			display: flex;
			align-items: center;
		}

		.buton {
			display: flex;
			gap: 1rem;
		}

		.btn:hover {
			transform: scale(1.1);
			/* Increase the size by 10% (1.1 times) */
		}
	</style>

</head>

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
				<h1 class="page-header">Risk Assessment Details</h1>

				<div class="alert alert-success" id="notify" <?php if (!isset($msg)) { ?>style="display: none;" <?php } ?>>
					<?php if (isset($msg))
						echo $msg; ?>
				</div>

				<div class="">
					<div class="panel panel-default">
						<div class="panel-body">

							<div class="form-group view-table">
								<div class="heading">
									<h3 class="subtitle" style="text-align:center;  color:white" ;>Details</h3>

								</div>
								<div class="buton">
									<button type="button" class="btn btn-md btn-info pull-right" id="btn_back">&lt;&lt;
										Back</button>
									<button type="button" class="btn btn-md btn-info pull-right"
										id="btn_edit">Edit</button>
								</div>

							</div>

							<div class="table-responsive">
								<table class="table table-bordered">
									<tr>
										<td class="title_text">Type of Assessment</td>
										<td class="content_text">
											<?php echo $assess->getBusinessType($info["as_type"]); ?>
										</td>
										<td class="title_text">Team or Company</td>
										<td class="content_text">
											<?php echo $info["as_team"]; ?>
										</td>
									</tr>
									<tr>
										<td class="title_text">Task/Process Reviewed</td>
										<td class="content_text">
											<?php echo $info["as_task"]; ?>
										</td>
										<td class="title_text">Description of Task/Process</td>
										<td class="content_text">
											<?php echo $info["as_descript"]; ?>
										</td>
									</tr>
									<tr>
										<td class="title_text">Business/Process Owner</td>
										<td class="content_text">
											<?php echo $info["as_owner"]; ?>
										</td>
										<td class="title_text">Assessor Name</td>
										<td class="content_text">
											<?php echo $info["as_assessor"]; ?>
										</td>
									</tr>
									<tr>
										<td class="title_text">Date of Assessment</td>
										<td class="content_text">
											<?php echo date("m/d/Y", strtotime($info["as_date"])); ?>
										</td>
										<td class="title_text">Next Assessment</td>
										<td class="content_text">
											<?php echo date("m/d/Y", strtotime($info["as_date"])); ?>
										</td>
									</tr>
									<tr>
										<td class="title_text">Status</td>
										<td class="content_text">
											<?php echo $approval; ?>
										</td>
										<td class="title_text">Assessment #</td>
										<td class="content_text">
											<?php echo $info["idassessment"]; ?>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>

					<div class="panel panel-default">
						<div class="panel-body">
							<div class="form-group">
								<div class="heading">
									<h3 class="subtitle" style="text-align:center; color:white">Covered risks</h3>
								</div>
								<div class="buton">
									<button type="button" class="btn btn-md btn-info pull-right" id="btn_add">+ Add a
										risk</button>
								</div>

							</div>

							<table class="table table-striped table-bordered table-hover" id="table"
								style="width:100%;">
								<thead>
									<tr>
										<th>Risk</th>
										<th>Risk Sub Category</th>
										<th>&nbsp;</th>
									</tr>
								</thead>
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
					{},
					{ "width": "40%" },
					{ "width": "70" }
				],
				"ajax": "../controller/assessment.php?action=listdetails&id=<?php echo $_REQUEST["id"]; ?>"
			});

			$("#btn_add").click(function (e) {
				$(location).attr("href", "../view/assessdetails.php?action=adddetail&assessmentId=<?php echo $_REQUEST["id"]; ?>");
			});


			$("#btn_back").click(function (e) {
				$(location).attr("href", "assessments.php");
			});

			$("#btn_edit").click(function (e) {
				$(location).attr("href", "editassessment.php?return=details&id=<?php echo $_REQUEST["id"]; ?>");
			});


		});

		function del(id) {
			BootstrapDialog.show({
				message: 'Are you sure you want to delete this entry?',
				buttons: [{
					label: 'No, go back',
					action: function (dialogItself) {
						dialogItself.close();
					},

				}, {
					label: 'Yes, delete',
					action: function (dialogItself) {
						//kod za booking
						res = $.ajax({ type: "GET", url: "../controller/assessment.php?action=deletedetail&id=" + id, async: false })
						$('#table').DataTable().ajax.reload();
						dialogItself.close();
					}
				}]
			});//end dialog	
		}
	</script>
</body>

</html>