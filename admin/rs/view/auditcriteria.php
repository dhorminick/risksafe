<?php

include_once("../controller/auth.php");
include_once("../config.php");
include_once('../model/audit.php');

if (isset($_REQUEST["response"]) and $_REQUEST["response"] == "true") {
	$msg = "Control effectivness updated successfully.";
}

if (isset($_REQUEST["response"]) and $_REQUEST["response"] == "err") {
	$msg = "Error updating control effectivness, please try again.";
}

$audit = new audit();
$info = $audit->getAudit($_REQUEST["id"]);

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<?php include_once("header.php"); ?>
	<style>
		.form-group1 {
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

		.panel-heading {
			background: rgb(0, 46, 76);
			background: linear-gradient(90deg, rgba(0, 46, 76, 1) 0%, rgba(0, 153, 255, 1) 35%, rgba(0, 107, 179, 1) 100%);
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
				<h1 class="page-header">Audit Review</h1>
				<div class="clearfix">
					<div class="panel panel-default">

						<div class="panel-body">
							<div class="form-group1">
								<div class="heading">
									<h3 class="subtitle" style="text-align:center;  color:white">Audit Details</h3>
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
										<td class="title_text">Company Name</td>
										<td class="content_text">
											<?php echo $info["con_company"]; ?>
										</td>
										<td class="title_text">Site</td>
										<td class="content_text">
											<?php echo $info["con_site"]; ?>
										</td>
									</tr>
									<tr>
										<td class="title_text">Industry Type</td>
										<td class="content_text">
											<?php echo $info["con_industry"]; ?>
										</td>
										<td class="title_text">Street</td>
										<td class="content_text">
											<?php echo $info["con_street"]; ?>
										</td>
									</tr>
									<tr>
										<td class="title_text">Business Unit / Team</td>
										<td class="content_text">
											<?php echo $info["con_team"]; ?>
										</td>
										<td class="title_text">Building</td>
										<td class="content_text">
											<?php echo $info["con_building"]; ?>
										</td>
									</tr>
									<tr>
										<td class="title_text">Process/task/activity</td>
										<td class="content_text">
											<?php echo $info["con_task"]; ?>
										</td>
										<td class="title_text">Zip Code</td>
										<td class="content_text">
											<?php echo $info["con_zipcode"]; ?>
										</td>
									</tr>
									<tr>
										<td class="title_text">Assessor name</td>
										<td class="content_text">
											<?php echo $info["con_assessor"]; ?>
										</td>
										<td class="title_text">State</td>
										<td class="content_text">
											<?php echo $info["con_state"]; ?>
										</td>
									</tr>
									<tr>
										<td class="title_text">Date</td>
										<td class="content_text">
											<?php echo date("m/d/Y", strtotime($info["con_date"])); ?>
										</td>
										<td class="title_text">Country</td>
										<td class="content_text">
											<?php echo $info["con_country"]; ?>
										</td>
									</tr>
									<tr>
										<td class="title_text">Time</td>
										<td class="content_text">
											<?php echo $info["con_time"]; ?>
										</td>
										<td class="title_text">Control #</td>
										<td class="content_text">
											<?php echo $info["idcontrol"]; ?>
										</td>
									</tr>
									<tr>
										<td class="title_text">Control Name</td>
										<td class="content_text">
											<?php echo $info["con_control"]; ?>
										</td>
										<td class="title_text">Sub Control</td>
										<td class="content_text">
											<?php echo $info["subControl"]; ?>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
					<div class="panel panel-default">
						<div class="panel-body">

							<div class="form-group1" style="margin-bottom:2%;">
								<div class="heading">
									<h3 class="subtitle" style="text-align:center;  color:white">Audit Criteria
										Questions</h3>
								</div>
								<div class="buton">
									<button type="button" class="btn btn-md btn-info pull-right" id="btn_add">+ Test
										question</button>

								</div>
							</div>
							<table class="table table-striped table-bordered table-hover" id="table"
								style="width:100%;">
								<thead>
									<tr>
										<th>Question</th>
										<th>Expected Outcome</th>
										<th>Outcome</th>
										<th>&nbsp;</th>
									</tr>
								</thead>
							</table>
						</div>

					</div>

					<div class="alert alert-info" id="notify" <?php if (!isset($msg)) { ?>style="display: none;" <?php } ?>>
						<?php if (isset($msg))
							echo $msg; ?>
					</div>
					<div class="panel panel-warning">
						<div class="panel-heading">
							<h3 class="panel-title" style="color:white">Control Effectivness</h3>
						</div>
						<div class="panel-body">
							<form role="form" id="form" action="../controller/audit.php" method="post"
								class="form-horizontal">
								<div class="form-group">
									<label class="col-md-3 control-label">Effectiveness:</label>
									<div class="col-md-6">
										<select name="effect" class="form-control">
											<option value="0" <?php if ($info["con_effect"] == 0)
												echo ' selected'; ?>>Not
												selected</option>
											<option value="1" <?php if ($info["con_effect"] == 1)
												echo ' selected'; ?>>
												Ineffective</option>
											<option value="2" <?php if ($info["con_effect"] == 2)
												echo ' selected'; ?>>
												Effective</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Rationale:</label>
									<div class="col-md-6">
										<input name="observation" id="observation" type="text" maxlength="255"
											class="form-control" placeholder="Enter Rationale..." required
											value="<?php echo $info["con_observation"]; ?>">
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-3 control-label">Root Cause:</label>
									<div class="col-md-6">
										<input name="rootcause" id="rootcause" type="text" maxlength="255"
											class="form-control" placeholder="Enter Root ..." required
											value="<?php echo $info["con_rootcause"]; ?>">
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-3 control-label">Treatment:</label>
									<div class="col-md-6">
										<input name="treatment" id="treatment" type="text" maxlength="255"
											class="form-control" placeholder="Enter treatment..." required
											value="<?php echo $info["con_treatment"]; ?>">
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Frequency:</label>
									<div class="col-md-6">
										<select name="frequency" class="form-control">
											<option value="1" <?php if ($info["con_frequency"] == 1)
												echo ' selected'; ?>>
												Daily</option>
											<option value="7" <?php if ($info["con_frequency"] == 7)
												echo ' selected'; ?>>
												Weekly</option>
											<option value="30" <?php if ($info["con_frequency"] == 30)
												echo ' selected'; ?>>Monthly</option>
											<option value="182" <?php if ($info["con_frequency"] == 182)
												echo ' selected'; ?>>6 monthly</option>
											<option value="365" <?php if ($info["con_frequency"] == 365)
												echo ' selected'; ?>>Yearly</option>
										</select>
									</div>

								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Next Assessment:</label>
									<div class="col-md-6">
										<input name="next" id="next" type="text" maxlength="255" class="form-control"
											placeholder="Enter treatment..." readonly
											value="<?php echo $audit->getNext($info["con_date"], $info["con_frequency"]); ?>">
									</div>


								</div>
								<div class="form-group">
									<div class="col-md-6 col-md-offset-3">
										<button type="submit" class="btn btn-md btn-info"
											id="btn_saveeffect">Save</button>
									</div>
									<input name="id" type="hidden" value="<?php echo $info["idcontrol"]; ?>" />
									<input name="action" type="hidden" value="updateeffect" />
								</div>

							</form>
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
					{ "width": "30%" },
					{ "width": "35%" },
					{ "width": "15%" },
					{ "width": "20%" }
				],
				"ajax": "../controller/audit.php?action=listcriteria&id=<?php echo $_REQUEST["id"]; ?>"
			});


			$("#btn_add").click(function (e) {
				$(location).attr("href", "../view/auditcriteriafrm.php?action=addcriteria&control=<?php echo $_REQUEST["id"]; ?>");
			});

			$("#btn_edit").click(function (e) {
				$(location).attr("href", "../view/audit.php?action=edit&return=auditcriteria&id=<?php echo $_REQUEST["id"]; ?>");
			});

			$("#btn_back").click(function (e) {
				$(location).attr("href", "audits.php");
			});


		});

		function del(id) {
			BootstrapDialog.show({
				title: 'Warning',
				message: 'Are you sure you want to delete this entry?',
				buttons: [{
					label: 'Cancel',
					action: function (dialogItself) {
						dialogItself.close();
					},
				}, {
					label: 'Delete',
					action: function (dialogItself) {
						res = $.ajax({ type: "GET", url: "../controller/audit.php?action=deletecriteria&id=" + id, async: false })
						$('#table').DataTable().ajax.reload();
						dialogItself.close();
					}
				}]
			});//end dialog	
		}

	</script>
</body>

</html>