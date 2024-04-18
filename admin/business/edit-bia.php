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

    if (isset($_GET['id']) && isset($_GET['id']) !== "") {
        $toDisplay = true;   
        $id = sanitizePlus($_GET['id']);
        $CheckIfBIAExist = "SELECT * FROM as_bia WHERE bia_id = '$id' AND c_id = '$company_id'";
        $BIAExist = $con->query($CheckIfBIAExist);
        if ($BIAExist->num_rows > 0) {	
            $in_exist = true;
			$info = $BIAExist->fetch_assoc();

            if (isset($_POST['update-bia'])) {
                $activity = sanitizePlus($_POST["activity"]);
                $descript = sanitizePlus($_POST["descript"]);
                $priority = sanitizePlus($_POST["priority"]);
                $impact = sanitizePlus($_POST["impact"]);
                $time = sanitizePlus($_POST["time"]);
                $action = sanitizePlus($_POST["action"]);
                $resource = sanitizePlus($_POST["resource"]);
                
                $query = "UPDATE as_bia SET bia_activity = '$activity', bia_descript = '$descript', bia_priority = '$priority', bia_impact = '$impact', bia_time = '$time', bia_action = '$action', bia_resource = '$resource' WHERE c_id = '$company_id' AND bia_id = '$id'";
                $incidentUpdated = $con->query($query);
                if ($incidentUpdated) {
                    array_push($message, 'BIA Data Updated Successfully!!');
                }else{
                    array_push($message, 'Error 502: Error Updating BIA!!');
                }	
            }
        }else{
            $in_exist = false;
        }
    } else {
        $toDisplay = false;
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Edit Business Impact Analysis | <?php echo $siteEndTitle; ?></title>
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
                <?php if($toDisplay == true){ ?>
                <?php if ($in_exist == true) { ?>
                <div class="card">
                    <form method="post">
                        <div class="card-header"></div>
                        <div class="card-body">
                            <?php require '../../layout/alert.php' ?>
                            <div class="card-header">
                                <h3 class="d-inline hide-md">Edit Business Impact Analysis</h3>
                                <h3 class="d-inline show-md">Edit BIA</h3>
                                <a class="btn btn-primary btn-icon icon-left header-a" href="bia"><i class="fas fa-arrow-left"></i> View All</a>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Critical Business Activity:</label>
                                    <input value="<?php echo $info["bia_activity"];?>" name="activity" type="text" maxlength="255" class="form-control" placeholder="Enter critical business activity..." required>
                                
                                </div>
                                <div class="form-group">
                                    <label>Description:</label>
                                    <textarea rows="3" class="form-control" placeholder="Enter description..." class="form-control" name="descript"><?php echo $info["bia_descript"];?></textarea>                    
                                </div>
                                <div class="row custom-row">
                                    <div class="form-group col-lg-6 col-12">
                                        <label>Priority:</label>
                                        <select name="priority" class="form-control">
                                        <option value="High" <?php if($info["bia_priority"] == "High") echo "selected='selected'"; ?> >High</option>
                                        <option value="Medium" <?php if($info["bia_priority"] == "Medium") echo "selected='selected'"; ?> >Medium</option>
                                        <option value="Low" <?php if($info["bia_priority"] == "Low") echo "selected='selected'"; ?> >Low</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-6 col-12">
                                        <label>Impact of Loss:</label>
                                        <select name="impact" class="form-control">
                                        <option value="Financial" <?php if($info["bia_impact"] == "Financial") echo "selected='selected'"; ?> >Financial</option>
                                        <option value="Reputational" <?php if($info["bia_impact"] == "Reputational") echo "selected='selected'"; ?> >Reputational</option>
                                        <option value="Compliance" <?php if($info["bia_impact"] == "Compliance") echo "selected='selected'"; ?> >Compliance</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Recovery Time Objective:</label>
                                    <input value="<?php echo $info["bia_time"];?>"  name="time" type="text" maxlength="255" class="form-control" placeholder="e.g. 12 hours" required>
                                </div>
                                <div class="form-group">
                                    <label>Preventative / Recovery Actions:</label>
                                    <textarea rows="3" class="form-control" placeholder="Enter preventative or recovery actions..." class="form-control" name="action"><?php echo $info["bia_action"];?></textarea>	                    
                                </div>
                                <div class="form-group">
                                    <label>Resource Requirements:</label>
                                    <textarea rows="3" class="form-control" placeholder="Enter resource requirements..." class="form-control" name="resource"><?php echo $info["bia_resource"];?></textarea>                
                                </div>
                                
                            </div>
                            <div class="card-body">
                                <div class="form-group">
									<button type="submit" class="btn btn-md btn-primary" name="update-bia">Update Bia</button>
									<button type="button" class="btn btn-md btn-warning" id="btn_cancel">Cancel</button>
								</div>
                            </div>
                        </div>
                        <div class="card-footer"></div>
                    </form>
                </div>
                <?php }else{ ?>
                <div class="card">
                    <div class="card-body" style="display:flex;justify-content:center;align-items:center;min-height:500px;">
                        <div>
                            <h3 style="display:flex;justify-content:center;align-items:center;width:100%;">Error 402!!</h3>
                            <div style="display:flex;justify-content:center;align-items:center;width:100%;margin:10px 0px;">Business Impact Analysis Doesn't Exist!!</div>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <?php }else{ ?>
                <div class="card">
                    <div class="card-body" style="display:flex;justify-content:center;align-items:center;min-height:500px;">
                        <div>
                            <h3 style="display:flex;justify-content:center;align-items:center;width:100%;">Error 402!!</h3>
                            <div style="display:flex;justify-content:center;align-items:center;width:100%;margin:10px;">Missing Parameters!!</div>
                        </div>
                    </div>
                </div>
                <?php } ?>
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