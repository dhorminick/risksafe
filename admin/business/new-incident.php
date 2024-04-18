<?php
    session_start();
    $file_dir = '../../';
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'login?r=/business/incidents');
        exit();
    }
    $message = [];
    include '../../layout/db.php';
    include '../../layout/admin__config.php';

    if(isset($_POST["create-incident"])){

        $title = sanitizePlus($_POST["title"]);
        $date = sanitizePlus($_POST["date"]);
        $reported = sanitizePlus($_POST["reported"]);
        $team = sanitizePlus($_POST["team"]);
        $financial = sanitizePlus($_POST["financial"]);
        $injuries = sanitizePlus($_POST["injuries"]);
        $complaints = sanitizePlus($_POST["complaints"]);
        $compliance = sanitizePlus($_POST["compliance"]);
        $descript = sanitizePlus($_POST["descript"]);
        $impact = sanitizePlus($_POST["impact"]);
        $priority = sanitizePlus($_POST["priority"]);
        $status = sanitizePlus($_POST["status"]);
        
		$in_id = secure_random_string(10);
        $query = "INSERT INTO as_incidents (in_user, in_title, in_date, in_reported, in_team, in_financial, in_injuries, in_complaints, in_compliance, in_descript, in_impact, in_priority, in_status, c_id, in_id) 
        VALUES ('$userId', '$title', '$date', '$reported', '$team', '$financial', '$injuries','$complaints', '$compliance','$descript', '$impact', '$priority', '$status', '$company_id', '$in_id')";
        $incidentCreated = $con->query($query);
        if ($incidentCreated) {
            #send notification
            $datetime = date("Y-m-d H:i:s");
            $notification_message = 'New Incident Registered';
            $notifier = $userId;
            $link = "admin/business/incidents?id=".$in_id;
            $type = 'incident';
            $case = 'new';
            $id = $in_id;
            $returnArray = createNotification($company_id, $notification_message, $datetime, $notifier, $link, $type, $case, $id, $con, $sitee);
            header("Location: incidents?id=".$in_id);
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
  <title>Create New Incident | <?php echo $siteEndTitle; ?></title>
  <?php require '../../layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/footer.custom.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/admin.custom.css">
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
                            <div class="card-header">
                                <h3 class="d-inline">Create New Incident</h3>
                                <a class="btn btn-primary btn-icon icon-left header-a" href="incidents"><i class="fas fa-arrow-left"></i> View All</a>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Case Title:</label>
                                    <input name="title" type="text" maxlength="255" class="form-control" placeholder="Enter case title" required>        
                                </div>
                                <div class="row custom-row">
                                    <div class="form-group col-lg-8 col-12">
                                        <label>Reported By:</label>
                                        <input name="reported" type="text" maxlength="255" class="form-control" placeholder="Enter person who reported the incident..." required>
                                    </div>
                                    <div class="form-group col-lg-4 col-12">
                                        <label>Date Occured:</label>
                                        <input name="date" type="date" maxlength="255" class="form-control datepicker" placeholder="Enter incident date occured..." value='<?php echo date('Y-m-d'); ?>' required>
                                    </div>
                                
                                    <div class="form-group col-lg-8 col-lg-12">
                                        <label>Team or Department:</label>
                                        <input name="team" type="text" maxlength="255" class="form-control" placeholder="Enter team or department..." required>
                                    </div>
                                    <div class="form-group col-lg-4 col-lg-12">
                                        <label>Financial Loss:</label>
                                        <input name="financial" type="text" maxlength="255" class="form-control" placeholder="" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Injuries:</label>
                                    <input name="injuries" type="text" maxlength="255" class="form-control" placeholder="" required>
                                </div>
                                <div class="form-group">
                                    <label>Complaints:</label>
                                    <input name="complaints" type="text" maxlength="255" class="form-control" placeholder="" required>
                                </div>
                                <div class="form-group">
                                    <label>Compliance breach:</label>
                                    <input name="compliance" type="text" maxlength="255" class="form-control" placeholder="" required>
                                </div>
                                <div class="form-group">
                                    <label>Description:</label>
                                    <textarea rows="3" class="form-control" placeholder="Enter description..." class="form-control" name="descript"></textarea>            
                                </div>
                                <div class="form-group">
                                    <label>Impact:</label>
                                    <textarea rows="3" class="form-control" placeholder="Enter impact..." class="form-control" name="impact"></textarea>            
                                </div>
                                <div class="row custom-row">
                                    <div class="form-group col-lg-6 col-12">
                                        <label>Priority:</label>
                                        <select class="form-control" name="priority" >
                                            <option value="High">High</option>
                                            <option value="Medium">Medium</option>
                                            <option value="Low">Low</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-6 col-12">
                                        <label>Status:</label>
                                        <select class="form-control" name="status" >
                                            <option value="Open">Open</option>
                                            <option value="Closed">Closed</option>
                                            <option value="In Progress">In Progress</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
									<button type="submit" class="btn btn-md btn-primary" name="create-incident">Create Incident</button>
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