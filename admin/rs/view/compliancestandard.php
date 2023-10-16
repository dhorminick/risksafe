<?php

include_once("../controller/auth.php");
include_once("../config.php");
include_once('../model/compliancestandard.php');
include_once('../model/assessment.php');

if (isset($_REQUEST["response"]) and $_REQUEST["response"] == "true") {
	$msg = "Compliance Standard created successfully.";
}

if (isset($_REQUEST["response"]) and $_REQUEST["response"] == "err") {
	$msg = "Error creating compliance standard, please try again.";
}
$compliance = new compliancestandard();

if ($_REQUEST["action"] == "edit") {
	$edit = true;
	$info = $compliance->getCompliance($_REQUEST["id"]);
	$controldata = $compliance->getControlData($info['existing_ct']);
	$treatmentdata = $compliance->getTreatmentData($info['existing_tr']);

	//   print_r($controldata);
	//     exit;

} else {
	$edit = false;
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
				<h1 class="page-header">Compliance Standard</h1>


				<div class="panel panel-default">
					<div class="panel-body">
						<div class="col-lg-8 col-md-12 col-sm-12"><!--col-lg-9 col-md-12-->

							<form role="form" id="form" action="../controller/compliancestandard.php" method="post" enctype="multipart/form-data">
								<div class="alert alert-info" id="notify" <?php if (!isset($msg)) { ?>style="display: none;" <?php } ?>>
									<?php if (isset($msg)) echo $msg; ?>
								</div>
								<div class="form-group">
									<label>Compliance Standard</label>
									<input name="compliancestandard" type="text" maxlength="100" class="form-control" value="<?php if ($edit) echo $info['com_compliancestandard']; ?>" placeholder="Enter your Compliance Standard..." required>
								</div>
								<div class="form-group">
									<label>Legislation</label>
									<input name="legislation" type="text" maxlength="100" class="form-control" value="<?php if ($edit) echo $info['com_legislation']; ?>" placeholder="" required>
								</div>
								<div class="form-group">
									<label>Controls<a href="#" data-tooltip="You add controls to your risk by selecting existing control from your controls library or you can create your own custom control by typing it into the text field below and clicking on '+Add' button."></a></label>
									<select name="existing_ct" id="existing_ct" class="form-control" required>
										<?php if ($edit) {
											echo  $controldata;
										} else {

										?> <option value="-1" selected>Select and add an existing control</option> <?php
																												}
																													?>
										<?php
										if ($edit) {
											echo $compliance->listControl($_SESSION["userid"], $controldata);
										} else {
											echo $compliance->listControl($_SESSION["userid"]);
										}

										?>
									</select>

								</div>
								<div class="form-group">
									<input style="width:84%;float:left;" name="control" id="control" type="text" maxlength="255" class="form-control" placeholder="Enter custom control description...">
									<button style="width:15%;float:right;margin-top:0px;" type="button" class="btn btn-sm btn-info" id="btn_addcontrol">+ Add</button>
								</div>
								<div class="clearfix" id="controls">
									&nbsp;
								</div>
								<div class="form-group">
									<label>Control Requirements</label>
									<input name="control" type="text" maxlength="255" class="form-control" value="<?php if ($edit) echo $info['com_controls']; ?>" placeholder="Enter control requirements" required>

								</div>
								<div class="form-group">
									<label>Treatments<a href="#" data-tooltip="You add treatments to your risk by selecting existing treatment from your treatments library or you can create your own custom treatment by typing it into the text field below and clicking on '+Add' button."></a></label>
									<select name="existing_tr" id="existing_tr" class="form-control" required>
										<?php if ($edit) {
											echo  $treatmentdata;
										} else {
										?><option value="-1" selected>Select and add an existing treatment</option> <?php } ?>
										<?php
										if ($edit) {
											echo $compliance->listTreatmentsLib($_SESSION["userid"], $treatmentdata);
										} else {
											echo $compliance->listTreatmentsLib($_SESSION["userid"]);
										}

										?>
									</select>

								</div>
								<div class="form-group">
									<input style="width:84%;float:left;" name="treatment" id="treatment" type="text" maxlength="255" class="form-control" placeholder="Enter custom treatment description...">
									<button style="width:15%;float:right;margin-top:0px;" type="button" class="btn btn-sm btn-info" id="btn_addtreatment">+ Add</button>
								</div>
								<div class="clearfix" id="treatments">
									&nbsp;
								</div>
								<div class="form-group">
									<label>Training & Awareness </label>
									<input name="training" type="text" maxlength="100" class="form-control" value="<?php if ($edit) echo $info['com_training']; ?>" placeholder="" required>
								</div>
								<div class="form-group">
									<label>Compliance Status</label>
									<select class="form-control" name="compliancestatus">
										<option value="Effective" <?php if ($edit && $info["co_status"] == "Effective") echo "selected='selected'"; ?>>Effective</option>
										<option value="Ineffective" <?php if ($edit && $info["co_status"] == "Ineffective") echo "selected='selected'"; ?>>Ineffective</option>
									</select>
								</div>
								<div class="form-group">
									<label>Compliance Officer </label>
									<input name="officer" type="text" maxlength="100" class="form-control" value="<?php if ($edit) echo $info['com_officer']; ?>" placeholder="" required>
								</div>
								<div class="form-group">
									<label>Documentation & evidence</label>
									<?php if (empty($info['com_documentation'])) : ?>
										<input name="documentation" type="file" class="form-control">
									<?php else : ?>
										<input name="documentation" type="file" class="form-control">
									<?php endif; ?>

								</div>
								<div class="form-group">
									<button type="submit" class="btn btn-md btn-info" id="btn_save">Create</button>
									<button type="button" class="btn btn-md btn-warning" id="btn_cancel">Cancel</button>
									<input name="action" type="hidden" value="<?php echo $_REQUEST["action"]; ?>" />
									<input name="id" type="hidden" value="<?php if ($edit) echo $info["idcompliance"]; ?>" />
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
			<?php
			//picks up data from the db and asigns values to JS variables
			if ($edit) {
				echo 'detid=' . $_REQUEST["id"] . ';';
			} else {
				echo 'detid=-1;';
			}

			?>
			$(function() {
				$("#date").datepicker();
			});

			$("#btn_cancel").click(function(e) {
				$(location).attr("href", "compliances.php");
			});

			// control section 
			$("#btn_addcontrol").click(function(e) {
				if ($("#control").val() !== '') {
					$.ajax({
						type: "GET",
						url: "../controller/assessment.php?action=addcontrol&descript=" + $("#control").val() + '&id=' + detid,
						async: false
					})
					$("#controls").load('../controller/assessment.php?action=listcontrols&id=' + detid);
					$("#control").val("");
				}
			});
			$("#btn_addtreatment").click(function(e) {
				if ($("#treatment").val() !== '') {
					$.ajax({
						type: "GET",
						url: "../controller/assessment.php?action=addtreat&descript=" + $("#treatment").val() + '&id=' + detid,
						async: false
					})
					$("#treatments").load('../controller/assessment.php?action=listtreat&id=' + detid);
					$("#treatment").val("");
				}
			});
		});

		function del(what, id) {

			if (what == "treatment") {
				$.ajax({
					type: "GET",
					url: "../controller/assessment.php?action=deletetreat&id=" + id,
					async: false
				})
				$("#treatments").load('../controller/assessment.php?action=listtreat&id=' + detid);
			}

			if (what == "control") {
				$.ajax({
					type: "GET",
					url: "../controller/assessment.php?action=deletecontrol&id=" + id,
					async: false
				})
				$("#controls").load('../controller/assessment.php?action=listcontrols&id=' + detid);
			}

		}
	</script>
	</script>
</body>

</html>