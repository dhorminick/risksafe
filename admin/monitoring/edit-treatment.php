<?php
    session_start();
    $file_dir = '../../';

    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'auth/sign-in?r=/monitoring/treatments');
        exit();
    }
  
    $message = [];
    include $file_dir.'layout/db.php';
    // include '../ajax/audits.php';
    include $file_dir.'layout/admin__config.php';

    
    if(isset($_POST["update-treat"]) && isset($_POST['c__id'])){
        $id = sanitizePlus($_POST["c__id"]);
                $team = sanitizePlus($_POST["team"]); 
                $assessor = sanitizePlus($_POST["assessor"]); 
                $treatment = sanitizePlus($_POST["treatment"]); 
                $cost_ben = sanitizePlus($_POST["cost_ben"]); 
                $progress = sanitizePlus($_POST["progress"]);
                $owner = sanitizePlus($_POST["owner"]);
                $start = sanitizePlus($_POST["start"]); 
                $due = sanitizePlus($_POST["due"]);
                $status = sanitizePlus($_POST["status"]);

                if (!$team || $team == null || $team == '' || !$status || $status == null || $status == '' || !$start || $start == null || $start == '' || !$due || $due == null || $due == '' || !$treatment || $treatment == null || $treatment == '') {
                    array_push($message, 'Error 402: Incomplete Data Parameters!!');
                } else {
                    # code...
                    $query = "UPDATE as_treatments SET tre_team = '$team', tre_assessor = '$assessor', tre_treatment = '$treatment', tre_cost_ben = '$cost_ben', tre_progress = '$progress', tre_owner = '$owner', tre_start = '$start', tre_due = '$due', tre_status = '$status' WHERE t_id = '$id' AND c_id = '$company_id'";
                    if ($con->query($query)) {
                        array_push($message, 'Treatment Updated Successfully!!');
                    } else {
                        array_push($message, 'Error 502: Error Updating Treatment Data!!');
                    }
                }		
            }
            
    if (isset($_GET['id']) && isset($_GET['id']) !== "") {
        $toDisplay = true;   
        $id = sanitizePlus($_GET['id']);
        $CheckIfAuditExist = "SELECT * FROM as_treatments WHERE t_id = '$id' AND c_id = '$company_id'";
        $AuditExist = $con->query($CheckIfAuditExist);
        if ($AuditExist->num_rows > 0) {	
            $aud_exist = true;
            
            $info = $AuditExist->fetch_assoc();
        
        }else{
            $aud_exist = false;
        }
    } else {
        $toDisplay = false;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta
   content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Update Treatment | <?php echo $siteEndTitle; ?></title>
  <?php require $file_dir.'layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/footer.custom.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/admin.custom.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/bundles/bootstrap-daterangepicker/daterangepicker.css">
</head>

<body>
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <?php require $file_dir.'layout/header.php' ?>
        <?php require $file_dir.'layout/sidebar_admin.php' ?>
        <!-- Main
         Content -->
        <div class="main-content">
            <section class="section">
            <div class="section-body">
                <?php if($toDisplay == true){ ?>
                <?php if ($aud_exist == true) { ?>
                <div class="card">
                  <!--<div class="card-body">-->
                    <form method="post" action=''>
                    <input type='hidden' name='c__id' value='<?php echo $info['t_id']; ?>' />
                        <div class="card-body">
                            <?php require $file_dir.'layout/alert.php' ?>
                            <div class="card-header">
                                <h3 class="d-inline">Update Treatment</h3>
                                <a class="btn btn-primary btn-icon icon-left header-a" href="treatments?id=<?php echo $info['t_id']; ?>"><i class="fas fa-arrow-left"></i> Back</a>
                            </div>
                            <div class="card-body">
                                
                                <div class="form-group">
                                    <label>Treatment :</label>
                                    <input name="treatment" value='<?php echo $info['tre_treatment']; ?>' type="text" id="createCustomControl" maxlength="255" class="form-control" placeholder="Enter treatment..." required>
                
                                </div>
                                <div class="form-group">
                                    <label>Cost / benefits :</label>
                                    <input name="cost_ben" value='<?php echo $info['tre_cost_ben']; ?>' type="text" maxlength="255" class="form-control" placeholder="Enter cost/benefits..." required>
                
                                </div>
                                <div class="form-group">
                                    <label>Progress update :</label>
                                    <textarea name="progress" rows="4" class="form-control" placeholder="Enter progress update..." required><?php echo $info['tre_progress']; ?></textarea>
                
                                </div>
                                <div class="form-group">
                                    <label>Owner :</label>
                                    <input id="owner" value='<?php echo $info['tre_owner']; ?>' name="owner" type="text" maxlength="100" class="form-control" placeholder="Enter owner..." required>
                
                                </div>
                                <div class="row custom-row">
                                <div class="form-group col-lg-4 col-12">
                                    <label>Start date :</label>
                                    <input name="start" value='<?php echo date("Y-m-d", strtotime($info["tre_start"])); ?>' id="start" type="text" maxlength="20" class="form-control datepicker" placeholder="Select start date..." required style="cursor:pointer;">
                
                                </div>
                                <div class="form-group col-lg-4 col-12">
                                    <label>Due date :</label>
                                    <input name="due" value='<?php echo date("Y-m-d", strtotime($info["tre_due"])); ?>' id="due" type="text" maxlength="20" class="form-control datepicker" placeholder="Select due date..." required style="cursor:pointer;">
                
                                </div>
                                <div class="form-group col-lg-4 col-12">
                                    <label>Status :</label>
                                    <select name="status" class="form-control" required>
                                    <option value="1" <?php if ($info["tre_status"] == 1) echo "selected"; ?>>In progress</option>
                                    <option value="2" <?php if ($info["tre_status"] == 2) echo "selected"; ?>>Completed</option>
                                    <option value="3" <?php if ($info["tre_status"] == 3) echo "selected"; ?>>Cancelled</option>
                                    </select>
                
                                </div>
                                </div>
                                <hr>
                                <h3 class="subtitle" style="margin-bottom: 20px;">Business details</h3>
                                <div class="form-group">
                                    <label>Team / Business unit :</label>
                                    <input id="team" value='<?php echo $info['tre_team']; ?>' name="team" type="text" maxlength="255" class="form-control" placeholder="Enter team/business unit..." required>
                
                                </div>
                                <div class="form-group">
                                    <label>Assessor Name :</label>
                                    <input id="assessor" value='<?php echo $info['tre_assessor']; ?>' name="assessor" type="text" maxlength="100" class="form-control" placeholder="Enter assessor name..." required>
                
                                </div>
                                
                            </div>
                            <div class="card-body">
                                <div class="form-group text-right">
									<button class="btn btn-md btn-primary btn-icon icol-left" name="update-treat"><i class='fas fa-check'></i> Update Treatment</button>
									<button type="button" class="btn btn-md btn-warning" id="btn_cancel">Cancel</button>
								</div>
                            </div>
                        </div>
                        <div class="card-footer"></div>
                    </form>
                  <!--</div>-->
                </div>
                <style>.main-footer{margin-top:0px;}</style>
                <?php }else{ ?>
                <div class="card">
                    <div class="card-body" style="display:flex;justify-content:center;align-items:center;min-height:500px;">
                        <div style="width:100%;min-height:400px;display:flex;justify-content:center;align-items:center;">
                            <div style="text-align: center;"> 
                                 <h3>Empty Data!!</h3>
                                 Treatment Doesn't Exist!!
                                 <p><a href="/help#data-error" class="btn btn-primary btn-icon icon-left mt-2"><i class="fas fa-arrow-left"></i> Help</a></p>
                             </div>
                         </div>
                    </div>
                </div>
                <style>.main-footer{margin-top:0px;}</style>
                <?php } ?>
                <?php }else{ ?>
                <div class="card">
                    <div class="card-body" style="display:flex;justify-content:center;align-items:center;min-height:500px;">
                        <div style="width:100%;min-height:400px;display:flex;justify-content:center;align-items:center;">
                            <div style="text-align: center;"> 
                                 <h3>Empty Data!!</h3>
                                 Missing Parameters,
                                 <p><a href="treatments" class="btn btn-primary btn-icon icon-left mt-2"><i class="fas fa-arrow-left"></i> Back</a></p>
                             </div>
                         </div>
                    </div>
                </div>
                <style>.main-footer{margin-top:0px;}</style>
                <?php } ?>
            </div>
            </section>
        </div>
        <?php require $file_dir.'layout/footer.php' ?>
        </footer>
        </div>
    </div>
    <?php require $file_dir.'layout/general_js.php' ?>
    <script src="<?php echo $file_dir; ?>assets/bundles/bootstrap-daterangepicker/daterangepicker.js"></script>
    <style>
        textarea{
            min-height: 120px !important;
        }
        .card{
          padding: 10px;
        }
    </style>
</body>
</html>