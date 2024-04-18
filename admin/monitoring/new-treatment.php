<?php
    session_start();
    $file_dir = '../../';
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'login?r=/monitoring/new-treatment');
        exit();
    }
    $message = [];
    include '../../layout/db.php';
    include '../../layout/admin_config.php';
    #include '../../layout/user_details.php';

    if(isset($_POST["create-treat"])){
        $existing = sanitizePlus($_POST["existing"]);
        
        $team = sanitizePlus($_POST["team"]); 
        $assessor = sanitizePlus($_POST["assessor"]); 
        $treatment = sanitizePlus($_POST["treatment"]); 
        $cost_ben = sanitizePlus($_POST["cost_ben"]); 
        $progress = sanitizePlus($_POST["progress"]);
        $owner = sanitizePlus($_POST["owner"]);
        $start = sanitizePlus($_POST["start"]); 
        $due = sanitizePlus($_POST["due"]);
        $status = sanitizePlus($_POST["status"]);
        $tre_id = secure_random_string(10);
        
        $query = "INSERT INTO as_treatments (tre_user, tre_team, tre_assessor, tre_treatment, tre_cost_ben, tre_progress, tre_owner, tre_start, tre_due, tre_status, c_id, t_id) VALUES ('$userId', '$team', '$assessor', '$treatment', '$cost_ben', '$progress', '$owner','$start', '$due', '$status', '$company_id', '$tre_id')";
        $treCreated = $con->query($query);
        if ($treCreated) {
            header("Location: treatments?id=".$tre_id);
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
  <title>Create New Treatment | <?php echo $siteEndTitle; ?></title>
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
                    <form method="post" action=''>
                        <div class="card-header"></div>
                        <div class="card-body">
                            <?php require '../../layout/alert.php' ?>
                            <div class="card-header">
                                <h3 class="d-inline">Create New Treatment</h3>
                                <a class="btn btn-primary btn-icon icon-left header-a" href="treatments"><i class="fas fa-arrow-left"></i> View All</a>
                            </div>
                            <div class="card-body">
                                
                                <div class="form-group">
                                    <label>Create Treatment :</label>
                                    <input name="treatment" type="text" id="createCustomControl" maxlength="255" class="form-control" placeholder="Enter treatment..." required>
                
                                </div>
                                <div class="form-group">
                                    <label>Cost / benefits :</label>
                                    <input name="cost_ben" type="text" maxlength="255" class="form-control" placeholder="Enter cost/benefits..." required>
                
                                </div>
                                <div class="form-group">
                                    <label>Progress update :</label>
                                    <textarea name="progress" rows="4" class="form-control" placeholder="Enter progress update..." required></textarea>
                
                                </div>
                                <div class="form-group">
                                    <label>Owner :</label>
                                    <input id="owner" name="owner" type="text" maxlength="100" class="form-control" placeholder="Enter owner..." required>
                
                                </div>
                                <div class="row custom-row">
                                <div class="form-group col-lg-4 col-12">
                                    <label>Start date :</label>
                                    <input name="start" id="start" type="text" maxlength="20" class="form-control datepicker" placeholder="Select start date..." required style="cursor:pointer;">
                
                                </div>
                                <div class="form-group col-lg-4 col-12">
                                    <label>Due date :</label>
                                    <input name="due" id="due" type="text" maxlength="20" class="form-control datepicker" placeholder="Select due date..." required style="cursor:pointer;">
                
                                </div>
                                <div class="form-group col-lg-4 col-12">
                                    <label>Status :</label>
                                    <select name="status" class="form-control" required>
                                    <option value="1" selected>In progress</option>
                                    <option value="2" >Completed</option>
                                    <option value="3" >Cancelled</option>
                                    </select>
                
                                </div>
                                </div>
                                <hr>
                                <h3 class="subtitle" style="margin-bottom: 20px;">Business details</h3>
                                <div class="form-group">
                                    <label>Team/business unit :</label>
                                    <input id="team" name="team" type="text" maxlength="255" class="form-control" placeholder="Enter team/business unit..." required>
                
                                </div>
                                <div class="form-group">
                                    <label>Assessor name :</label>
                                    <input id="assessor" name="assessor" type="text" maxlength="100" class="form-control" placeholder="Enter assessor name..." required>
                
                                </div>
                                
                            </div>
                            <div class="card-body">
                                <div class="form-group">
									<button class="btn btn-md btn-primary" name="create-treat">Create Treatment</button>
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
</body>
</html>