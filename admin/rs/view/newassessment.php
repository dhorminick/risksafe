<?php

include_once("../controller/auth.php");
include_once("../config.php");
include_once('../model/assessment.php');

if (isset($_REQUEST["response"]) and $_REQUEST["response"] == "true") {
	$msg = "New assessment created successfully.";
}

if (isset($_REQUEST["response"]) and $_REQUEST["response"] == "err") {
	$msg = "Error creating new risk assessment, please try again.";
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
				<h1 class="page-header">New Risk Assessment</h1>


				<div class="panel panel-default">
					<div class="panel-body">
						<div class="col-lg-8 col-md-12 col-sm-12"><!--col-lg-9 col-md-12-->

							<form role="form" id="form" action="../controller/assessment.php" method="post">
								<div class="alert alert-info" id="notify" <?php if (!isset($msg)) { ?>style="display: none;" <?php } ?>>
									<?php if (isset($msg)) echo $msg; ?>
								</div>
								<h3 class="subtitle">Choose a Type of Risk Assessment</h3>
								<div class="form-group">
									<label>Type of Risk Assessment</label>
									<select name="type" class="form-control" required>
										<option value="" selected>Please select type...</option>
										<?php
										$ass = new assessment;
										echo $ass->listTypes(-1);
										?>
									</select>
								</div>
								<h3 class="subtitle">Basic information</h3>
								<div class="form-group">
									<label>Team or Company</label>
									<input name="team" type="text" maxlength="100" class="form-control" placeholder="Enter company or a team name..." required>

								</div>
								<div class="form-group">
									<label>Task or Process Being Reviewed</label>
									<input name="task" type="text" maxlength="255" class="form-control" placeholder="Enter task being reviewed..." required>

								</div>
								<div class="form-group">
									<label>Description of Task or Process</label>
									<textarea name="description" rows="4" class="form-control" placeholder="Enter task description..." required></textarea>

								</div>
								<div class="form-group">
									<label>Business/Process Owner</label>
									<input name="owner" type="text" maxlength="100" class="form-control" placeholder="Enter assessment owner..." required>

								</div>
								<div class="form-group">
									<label>Assessor Name</label>
									<input name="assessor" type="text" maxlength="100" class="form-control" placeholder="Enter assessor name..." required>

								</div>
								<div class="form-group">
									<label>Next Assessment</label>
									<input name="date" id="date" type="text" maxlength="100" class="form-control readonly" placeholder="Select date..." required readonly style="cursor:pointer;" value="<?php echo date("m/d/Y"); ?>">

								</div>
								<div class="form-group">
									<label>Approval</label>
									<select name="approval" class="form-control" required>
										<option value="1">In progress</option>
										<option value="2">Approved</option>
										<option value="3">Closed</option>
									</select>

								</div>
								<div class="form-group">
									<button type="submit" class="btn btn-md btn-info" id="btn_save">Create a Risk Assessment</button>
									<button type="button" class="btn btn-md btn-warning" id="btn_cancel">Cancel</button>
									<input name="action" type="hidden" value="add" />
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

			$(function() {
				$("#date").datepicker();
			});

			$("#btn_cancel").click(function(e) {
				$(location).attr("href", "assessments.php");
			});

		});
	</script>
	</script>
</body>

</html>