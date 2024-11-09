<?php
    session_start();
    $file_dir = '../../';
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'login?r=/assessments/new-assessment');
        exit();
    }
    $message = [];
    include '../../layout/db.php';
    include '../../layout/admin__config.php';
    
    function getIndustryTitle($id, $con){
        if($id == ''){
           $response = 'None Selected'; 
        }else{
            $query="SELECT * FROM as_newrisk_industry WHERE industry_id = '$id'";
    		$result=$con->query($query);
    		if ($result->num_rows > 0) {	
    			$row=$result->fetch_assoc();
    			$response = $row['title'];
    		}else{
    			$response = 'Error!!';
    		}
        }
		return $response;
    }
    
    $risk__industry = $_SESSION['risk_industry'];

    function calculateRating($like, $consequence, $conn) {
	
		$result=$conn->query("SELECT * FROM as_consequence WHERE idconsequence='$consequence'");
		$con=$result->fetch_assoc();
		$result=$conn->query("SELECT * FROM as_like WHERE idlike='$like'");
		$li=$result->fetch_assoc();
		
		$sum=$li["li_value"]+$con["con_value"];
		if ($li["li_value"]==$con["con_value"]) $sum++; //when both 3 calse says medium but chart says high, so this is a hack
		switch ($sum) {
		
			case ($sum<=4):
				$response=1;
				break;
				
			case ($sum>4 and $sum<7):
				$response=2;
				break;
				
			case ($sum==7):
				$response=3;
				break;
				
			case ($sum>7):
				$response=4;
				break;
		}
		return $response;
	}

    if(isset($_POST["create-assessment"])){
        // $insert_userid = $_SESSION["userid"];
        $type = ucwords(getIndustryTitle($risk__industry, $con));
        $team = sanitizePlus($_POST["team"]);
        $task = sanitizePlus($_POST["task"]);
        $descript = sanitizePlus($_POST["description"]);
        $owner = sanitizePlus($_POST["owner"]);
        $next = sanitizePlus($_POST["date"]);
        $assessor = sanitizePlus($_POST["assessor"]);
        $approval = sanitizePlus($_POST["approval"]); 

        $number = rand();
	
		$date = date("Y-m-d");
		$next = date("Y-m-d", strtotime($next));
        $as_id = secure_random_string(10);

        $query = "INSERT INTO as_assessment (industry, as_user, as_type, as_team, as_task, as_descript, as_number, as_owner, as_next, as_assessor, as_approval, as_completed, as_date, as_id, has_values, c_id, export_date) 
        VALUES ('$risk__industry', '$userId', '$type', '$team', '$task', '$descript', '$number', '$owner', '$next', '$assessor', '$approval', '0', '$next', '$as_id', 'false', '$company_id', '$date')";
        $assessmentCreated = $con->query($query);
        if ($assessmentCreated) {
                # code...
                $_SESSION["assessment"] = $as_id;
                #send notification
                #include_once '../ajax/ajax.php';
                $datetime = date("Y-m-d H:i:s");
                if(isset($_GET['type']) && $_GET['type'] == 'aml'){
                    $notification_message = 'New AML Assessment Created';
                    $type = 'aml';
                }else{
                    $notification_message = 'New Risk Assessment Created';
                    $type = 'risk';
                }
                $notifier = $userId;
                $link = "admin/assessments/assessment-details?id=".$as_id;
                $case = 'new';
                #$case_type = 'new-risk';
                $id = $as_id;
                $returnArray = createNotification($company_id, $notification_message, $datetime, $notifier, $link, $type, $case, $con, $sitee);
                
                header("Location: add-risks?id=".$as_id);
                exit();
        }else{
            array_push($message, 'Error 502: Error!!');
        }	
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Create New <?php if(isset($_GET['type']) && $_GET['type'] == 'aml'){echo 'AML ';} ?>Assessment | <?php echo $siteEndTitle; ?></title>
  <?php require '../../layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/footer.custom.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/bundles/bootstrap-daterangepicker/daterangepicker.css">
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
                    <form method="post">
                        <div class="card-header"></div>
                        <div class="card-body">
                            <?php require '../../layout/alert.php' ?>
                            <div class="card-header"><h3 class="subtitle">Select Assessment Type</h3></div>
                            <div class="card-body">
                                <div class="form-group">
									<label>Selected Risk Industry: </label>
									<div class='form-control' style='border:none !important;padding:10px 0px !important;font-size:16px;font-weight:400;'><?php echo $getIndustry = ucwords(getIndustryTitle($risk__industry, $con)); ?></div>
									
									<div style='margin-top:20px;'>
									<?php if($getIndustry == 'None Selected'){ ?>
									<a href='industry' class='btn btn-primary btn-icon icon-left'>Select Industry</a>
									<?php }else{ ?>
									<a href='industry' class='btn btn-primary btn-icon icon-left'>Update Industry</a>
									<?php } ?>
									</div>
								</div>
                            </div>
                            <div class="card-body"></div>
                            <div class="card-header"><h3 class="subtitle">Assessment Information</h3></div>
                            <div class="card-body">
                                <div class="form-group">
									<label>Team or Company: </label>
									<input name="team" type="text" maxlength="100" class="form-control" placeholder="Enter company or a team name..." required>

								</div>
								<div class="form-group">
									<label>Task or Process Being Reviewed: </label>
									<input name="task" type="text" maxlength="255" class="form-control" placeholder="Enter task being reviewed..." required>

								</div>
								<div class="form-group">
									<label>Description of Task or Process: </label>
									<textarea name="description" rows="6" class="form-control" placeholder="Enter task description..." required></textarea>

								</div>
								<div class="form-group">
									<label>Business/Process Owner: </label>
									<input name="owner" type="text" maxlength="100" class="form-control" placeholder="Enter assessment owner..." required>

								</div>
								<div class="form-group">
									<label>Assessor Name: </label>
									<input name="assessor" type="text" maxlength="100" class="form-control" placeholder="Enter assessor name..." value='<?php #echo $name; ?>' required>

								</div>
								<div class='row custom-row'>
								<div class="form-group col-lg-6 col-12">
									<label>Next Assessment: </label>
									<input name="date" id="date" type="text" maxlength="100" class="form-control datepicker" placeholder="Select date..." required style="cursor:pointer;" value="<?php echo date("Y-m-d"); ?>">

								</div>
								<div class="form-group col-lg-6 col-12">
									<label>Approval: </label>
									<select name="approval" class="form-control" required>
										<option value="1">In progress</option>
										<option value="2">Approved</option>
										<option value="3">Closed</option>
									</select>

								</div>
								</div>
							</div>
                            <div class="card-body" style="margin-top: -45px !important;">
                                <div class="form-group">
									<button type="submit" class="btn btn-md btn-primary" name="create-assessment">Create Assessment</button>
									<button type="button" class="btn btn-md btn-warning" id="btn_cancel">Cancel</button>
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
    <script src="<?php echo $file_dir; ?>assets/bundles/bootstrap-daterangepicker/daterangepicker.js"></script>
    <style>
        textarea{
            min-height: 120px !important;
        }
    </style>
    <script>$("#date").datepicker();</script>
</body>
</html>