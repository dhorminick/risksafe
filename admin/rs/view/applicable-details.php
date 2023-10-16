<?php

include_once("../controller/auth.php");
include_once("../config.php");
include_once("../model/applicable.php");

// not needed anymore
if (isset($_REQUEST["id"])) {
	$_SESSION["applicable"] = $_REQUEST["id"];
}
if (isset($_REQUEST['response']) && $_REQUEST['response'] == true) {
	$msg = "Information saved successfully.";
}

$apply = new applicable();
$info = $apply->getApplicable($_REQUEST["id"]);


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
			"

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
				<h1 class="page-header">Applicable Policy Details</h1>

				<div class="alert alert-success" id="notify" <?php if (!isset($msg)) { ?>style="display: none;" <?php } ?>>
					<?php if (isset($msg))
						echo $msg; ?>
				</div>

				<div class="">
					<div class="panel panel-default">

						<div class="panel-body">
							<div class="form-group">

								<div class="heading">
									<h3 class="subtitle" style="text-align:center;  color:white">Details</h3>
								</div>
								<div class="buton">
									<button type="button" class="btn btn-md btn-info pull-right" style="outline:none;"
										id="btn_back">&lt;&lt; Back</button>
									<button type="button" class="btn btn-md btn-info pull-right" style="outline:none;"
										id="btn_edit">Edit</button>

								</div>


							</div>
							<div class="table-responsive">
								<table class="table table-bordered">
									<tr>
										<td class="title_text">Policy Title</td>
										<td class="content_text">
											<?php echo $info["PolicyTitle"]; ?>
										</td>
										<td class="title_text">Policy Number</td>
										<td class="content_text">
											<?php echo $info["PolicyNumber"]; ?>
										</td>
									</tr>
									<tr>
										<td class="title_text">Policy Description</td>
										<td class="content_text">
											<?php echo $info["PolicyDescription"]; ?>
										</td>
										<td class="title_text">Policy Effective Date</td>
										<td class="content_text">
											<?php echo date("m/d/Y", strtotime($info["PolicyEffectiveDate"])); ?>
										</td>
									</tr>
									<tr>
										<td class="title_text">Policy Review Date</td>
										<td class="content_text">
											<?php echo date("m/d/Y", strtotime($info["PolicyReviewDate"])); ?>
										</td>
										<td class="title_text">Applicability</td>
										<td class="content_text">
											<?php echo $info["Applicability"]; ?>
										</td>
									</tr>
									<tr>
										<td class="title_text">Policy Requirements</td>
										<td class="content_text">
											<?php echo $info["PolicyRequirements"]; ?>
										</td>
										<td class="title_text">ComplianceResponsibility</td>
										<td class="content_text">
											<?php echo $info["ComplianceResponsibility"]; ?>
										</td>
									</tr>
									<tr>
										<td class="title_text">Related Documents</td>
										<td class="content_text">
											<?php echo $info["RelatedDocuments"]; ?>
										</td>
										<td class="title_text">Policy Approval</td>
										<td class="content_text">
											<?php echo $info["PolicyApproval"]; ?>
										</td>

									</tr>
									<tr>
										<td class="title_text">Policy Review Revision History</td>
										<td class="content_text">
											<?php echo $info["PolicyReviewRevisionHistory"]; ?>
										</td>
										<td class="title_text">Policy Acknowledgment</td>
										<td class="content_text">
											<?php echo $info["PolicyAcknowledgment"]; ?>
										</td>

									</tr>
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
				"ajax": "../controller/applicable.php?action=list&id=<?php echo $_REQUEST["id"]; ?>"
			});




			$("#btn_back").click(function (e) {
				$(location).attr("href", "applicables.php");
			});

			$("#btn_edit").click(function (e) {
				$(location).attr("href", "applicable.php?action=edit&id=<?php echo $_REQUEST["id"]; ?>");
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