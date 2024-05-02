<?php
    session_start();
    $file_dir = '../../';
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'login.php?r=/business/bia.php');
        exit();
    }
    $message = [];
    include '../../layout/db.php';
    include '../../layout/admin__config.php';

    if(isset($_POST["create-bia"])){

        $activity = sanitizePlus($_POST["activity"]);
        $descript = sanitizePlus($_POST["descript"]);
        $priority = sanitizePlus($_POST["priority"]);
        $impact = sanitizePlus($_POST["impact"]);
        $time = sanitizePlus($_POST["time"]);
        $action = sanitizePlus($_POST["action"]);
        $resource = sanitizePlus($_POST["resource"]);
        
		$bia_id = secure_random_string(10);
		$date_time = date("Y-m-d H:i:s");
        $query = "INSERT INTO as_bia (bia_user, bia_activity, bia_descript, bia_priority, bia_impact, bia_time, bia_action, bia_resource, c_id, bia_id, datee_time) 
        VALUES ('$userId', '$activity', '$descript', '$priority', '$impact', '$time', '$action','$resource', '$company_id', '$bia_id', '$date_time')";
        $biaCreated = $con->query($query);
        if ($biaCreated) {
            #send notification
            $datetime = date("Y-m-d H:i:s");
            $notification_message = 'New BIA Registered';
            $notifier = $userId;
            $link = "admin/business/bia?id=".$bia_id;
            $type = 'bia';
            $case = 'new';
            $id = $bia_id;
            $returnArray = createNotification($company_id, $notification_message, $datetime, $notifier, $link, $type, $case, $con, $sitee);
            
            header("Location: bia?id=".$bia_id);
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
  <title>Create New Business Impact Analysis | <?php echo $siteEndTitle; ?></title>
  <?php require '../../layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/footer.custom.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/admin.custom.css">
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
                                <h3 class="d-inline hide-md">Create New Business Impact Analysis</h3>
                                <h3 class="d-inline show-md">Create New BIA</h3>
                                <a class="btn btn-primary btn-icon icon-left header-a" href="bia"><i class="fas fa-arrow-left"></i> View All</a>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Critical Business Activity:</label>
                                    <input name="activity" type="text" maxlength="255" class="form-control" placeholder="Enter critical business activity..." required>
                                
                                </div>
                                <div class="form-group">
                                    <label>Description:</label>
                                    <textarea rows="3" class="form-control" placeholder="Enter description..." class="form-control" name="descript"></textarea>                    
                                </div>
                                <div class="row custom-row">
                                    <div class="form-group col-lg-6 col-12">
                                        <label>Priority:</label>
                                        <select name="priority" class="form-control">
                                        <option value="High">High</option>
                                        <option value="Medium">Medium</option>
                                        <option value="Low">Low</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-6 col-12">
                                        <label>Impact of Loss :</label>
                                        <select name="impact" class="form-control">
                                        <option value="Financial">Financial</option>
                                        <option value="Reputational">Reputational</option>
                                        <option value="Compliance">Compliance</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Recovery Time Objective:</label>
                                    <input name="time" type="text" maxlength="255" class="form-control" placeholder="e.g. 12 hours" required>
                                </div>
                                <div class="form-group">
                                    <label>Preventative/Recovery Actions:</label>
                                    <textarea rows="3" class="form-control" placeholder="Enter preventative or recovery actions..." class="form-control" name="action"></textarea>	                    
                                </div>
                                <div class="form-group">
                                    <label>Resource Requirements:</label>
                                    <textarea rows="3" class="form-control" placeholder="Enter resource requirements..." class="form-control" name="resource"></textarea>                
                                </div>
                                
                            </div>
                            <div class="card-body">
                                <div class="form-group">
									<button type="submit" class="btn btn-md btn-primary" name="create-bia">Create BIA</button>
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
    <style>
        textarea{
            min-height: 120px !important;
        }
    </style>
    <script>$("#date").datepicker();</script>
</body>
</html>