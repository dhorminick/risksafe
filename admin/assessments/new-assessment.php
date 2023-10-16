<?php
    $file_dir = '../../';
    $message = [];
    include '../../layout/db.php';
    include '../../layout/admin_config.php';

    if(isset($_POST["create-assessment"])){
        // $insert_userid = $_SESSION["userid"];
        $type = sanitizePlus($_POST["type"]);
        $team = sanitizePlus($_POST["team"]);
        $task = sanitizePlus($_POST["task"]);
        $descript = sanitizePlus($_POST["description"]);
        $owner = sanitizePlus($_POST["owner"]);
        $insert_date = sanitizePlus($_POST["date"]);
        $assessor = sanitizePlus($_POST["assessor"]);
        $approval = sanitizePlus($_POST["approval"]); 

        
		//generate number of assessment
		$query = "SELECT * FROM as_assessment WHERE as_user=" . $userId . " ORDER BY as_number DESC LIMIT 0,1";
		$result = $conn->query($query);
		if ($row = $result->fetch_assoc()) {
			$number = $row["as_number"] + 1;
		} else {
			$number = 1;	
		}
	
		$date = date("Y-m-d");
		$next = date("Y-m-d", strtotime($next));
        $as_id = secure_random_string(20);

        $query = "INSERT INTO as_assessment (as_user, as_type, as_team, as_task, as_descript, as_number, as_owner, as_next, as_assessor, as_approval, as_completed, as_date, as_id) 
        VALUES ('$userId', '$type', '$team', '$task', '$descript', '$number', '$owner', '$next', '$assessor', '$approval', '0', '$next', '$as_id')";
        $assessmentCreated = $con->query($query);
        if ($assessmentCreated) {
            $_SESSION["assessment"] = $con->insert_id;
            header("Location: ../view/assessdetails.php?action=adddetail&assessmentId=" . $as_id);
            header("Location: assessment-details.php?action=adddetail&id=" . $as_id);
        }else{

        }	
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Create New Assessment | </title>
  <?php require '../../layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/footer.custom.css">
</head>

<body>
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <?php require '../../layout/header.php' ?>
        <?php require '../../layout/sidebar_admin.php' ?>
        <!-- Main Content -->
        <div class="main-content">
            <section class="section">
            <div class="section-body">
                <div class="card">
                    <form role="form" class="form" id="form" action="" method="post">
                        <div class="card-header"></div>
                        <div class="card-body">
                            <div class="card-header"><h3 class="subtitle">Select Risk Assessment Type</h3></div>
                            <div class="card-body">
                                <div class="form-group">
									<label>Type of Risk Assessment</label>
									<select name="type" class="form-control" required>
										<option selected>Please select type...</option>
                                        <?php
                                            $selected = -1;
                                            $query="SELECT * FROM as_types ORDER BY idtype";
                                            $result=$con->query($query);
                                            $response="";
                                            while ($row=$result->fetch_assoc()) {
                                                $response.='<option value="' . $row["idtype"] . '"';
                                                if ($selected==$row["idtype"]) $response.=' selected';
                                                $response.='>' . $row["ty_name"] . '</option>';
                                            }
									
                                            echo $response;
										?>
									</select>
								</div>
                            </div>
                            <div class="card-body"></div>
                            <div class="card-header"><h3 class="subtitle">Basic Information</h3></div>
                            <div class="card-body">
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
									<textarea name="description" rows="6" class="form-control" placeholder="Enter task description..." required></textarea>

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
									<input name="date" id="date" type="date" maxlength="100" class="form-control datepicker" placeholder="Select date..." required style="cursor:pointer;" value="<?php echo date("m/d/Y"); ?>">

								</div>
								<div class="form-group">
									<label>Approval</label>
									<select name="approval" class="form-control" required>
										<option value="1">In progress</option>
										<option value="2">Approved</option>
										<option value="3">Closed</option>
									</select>

								</div>
							</div>
                            <div class="card-body" style="margin-top: -45px !important;">
                                <div class="form-group">
									<button type="submit" class="btn btn-md btn-primary" name="create-assessment">Create a Risk Assessment</button>
									<button type="button" class="btn btn-md btn-warning" id="btn_cancel">Cancel</button>
									<input name="action" type="hidden" value="add" />
								</div>
                            </div>
                        </div>
                        <div class="card-footer"></div>
                    </form>
                </div>
            </div>
            </section>
        </div>
        <?php require '../../layout/footer.php' ?>
        </footer>
        </div>
    </div>
    <?php require '../../layout/general_js.php' ?>
    <style>
        textarea{
            min-height: 120px !important;
        }
    </style>
    <script>$("#date").datepicker();</script>
</body>
</html>