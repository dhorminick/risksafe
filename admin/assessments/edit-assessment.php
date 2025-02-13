<?php
    session_start();
    $file_dir = '../../';
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'login?r=/assessments/all');
        exit();
    }
    $message = [];
    include '../../layout/db.php';
    include '../../layout/admin__config.php';
    include '../ajax/assessment.php';

    $ass_exist = false;
    $noValue = true;
    #$toEdit = false;
    $risk__industry = $_SESSION['risk_industry'];

    if (isset($_GET['id']) && isset($_GET['id']) !== "") {
        $assess_id = sanitizePlus($_GET['id']);
        $toDisplay = true;
        
        if(isset($_POST["update-assessment"])){
                // $type = sanitizePlus($_POST["type"]);
                $team = sanitizePlus($_POST["team"]);
                $task = sanitizePlus($_POST["task"]);
                $descript = sanitizePlus($_POST["description"]);
                $owner = sanitizePlus($_POST["owner"]);
                $next = sanitizePlus($_POST["date"]);
                $assessor = sanitizePlus($_POST["assessor"]);
                $approval = sanitizePlus($_POST["approval"]); 

                $next = date("Y-m-d", strtotime($next));

                #as_type = '$type',
                $query = "UPDATE as_assessment SET as_team = '$team', as_task = '$task', as_descript = '$descript', as_owner = '$owner', as_next = '$next', as_assessor = '$assessor', as_approval = '$approval', as_date = '$next' WHERE as_id = '$assess_id' AND c_id = '$company_id'"; 
                $assessmentCreated = $con->query($query);
                if ($assessmentCreated) {
                    #notify
                    array_push($message, 'Assessment Updated Successfully!!');
                    #header('Location: assessment-details?id='.$assess_id);
                    header('refresh:2;url= assessment-details?id='.$assess_id);
                }else{
                    array_push($message, 'Error 502: Error!!');
                }
        }
            
        $CheckIfAssessmentDetailsExist = "SELECT * FROM as_assessment WHERE as_id = '$assess_id' AND c_id = '$company_id'";
        $AssessmentDetailsExist = $con->query($CheckIfAssessmentDetailsExist);
        if ($AssessmentDetailsExist->num_rows > 0) {	
            $ass_exist = true;	

			$info = $AssessmentDetailsExist->fetch_assoc();
                // $riskType = $info["as_type"];

                // $query="SELECT * FROM as_types WHERE idtype = '$riskType'";
                // $result=$con->query($query);
                // if ($result->num_rows > 0) {
                //     $row = $result->fetch_assoc();
                //     $riskType = $row['ty_name'];
                // }else{
                //     $riskType = 'Error!';
                // }

        }else{
            $ass_exist = false;
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
  <title>Edit Assessment | <?php echo $siteEndTitle; ?></title>
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
                <?php if ($toDisplay == true) { ?>
                <?php if ($ass_exist == true) { ?>
                <form method="post">
                <div class="card toEdit" style='padding:10px;'>
                    <div class="card-header">
                        <h3 class="d-inline">Edit Assessment</h3>
                        <a class="btn btn-primary btn-icon icon-left header-a" href='all'><i class="fas fa-arrow-left"></i> All Assessments</a>
                    </div>
                    <div class="card-body">
                        <?php include '../../layout/alert.php'; ?>
                        <div class="form-group">
							<label>Selected Risk Industry: </label>
							<div class='form-control' style='font-weight:400;border:none !important;padding:10px 0px !important;'><?php echo ucwords(getIndustryTitle($risk__industry, $con)); ?></div>
						    <div style='margin-top:20px;'>NOTE: Industry type cannot be modified after risk have been created!!</div>
						</div>
                    </div>
                    
                    <div class="card-header" style='margin-top:20px;'><h3 class="subtitle">Assessment Information</h3></div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Team or Company: </label>
                            <input name="team" type="text" value='<?php echo $info['as_team'] ;?>' class="form-control" placeholder="Enter company or a team name..." required>
    
                        </div>
                        <div class="form-group">
                            <label>Task or Process Being Reviewed: </label>
                            <input name="task" type="text" value='<?php echo $info['as_task'] ;?>' class="form-control" placeholder="Enter task being reviewed..." required>
    
                        </div>
                        <div class="form-group">
                            <label>Description of Task or Process: </label>
                            <textarea name="description" rows="6" class="form-control" placeholder="Enter task description..." required><?php echo $info['as_descript'] ;?> </textarea>
    
                        </div>
                        <div class="form-group">
                            <label>Business/Process Owner: </label>
                            <input name="owner" type="text" value='<?php echo $info['as_owner'] ;?>' class="form-control" placeholder="Enter assessment owner..." required>
    
                        </div>
                        <div class='row custom-row'>
                        <div class="form-group col-lg-6 col-12">
                            <label>Assessor Name: </label>
                            <input name="assessor" type="text" value='<?php echo ucwords($info['as_assessor']) ;?>' class="form-control" placeholder="Enter assessor name..." required>
    
                        </div>
                        <div class="form-group col-lg-3 col-12">
                            <label>Next Assessment: </label>
                            <input name="date" id="date" type="text" class="form-control datepicker" placeholder="Select date..." required style="cursor:pointer;" value="<?php echo date("Y-m-d", strtotime($info['as_date'])); ?>">
    
                        </div>
                        <div class="form-group col-lg-3 col-12">
                            <label>Approval: </label>
                            <select name="approval" class="form-control" required>
                                <option value="1" <?php if($info['as_approval'] == 1) echo 'selected'; ?>>In progress</option>
                                <option value="2" <?php if($info['as_approval'] == 2) echo 'selected'; ?>>Approved</option>
                                <option value="3" <?php if($info['as_approval'] == 3) echo 'selected'; ?>>Closed</option>
                            </select>
    
                        </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <button type="submit" class="btn btn-md btn-primary" name="update-assessment">Update Assessment</button>
                            <button type="button" class="btn btn-md btn-warning" id="btn_cancel">Cancel</button>
                        </div>
                    </div>
                </div>


                </form>
                <?php }else{ ?>
                <div class="card">
                    <div class="card-body" style="display:flex;justify-content:center;align-items:center;min-height:500px;">
                        <div>
                            <h3 style="display:flex;justify-content:center;align-items:center;width:100%;">Error 402!!</h3>
                            <div style="display:flex;justify-content:center;align-items:center;width:100%;">Risk Does Not Exist!!</div>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <?php }else{ ?>
                <div class="card">
                    <div class="card-body" style="display:flex;justify-content:center;align-items:center;min-height:500px;">
                        <div>
                            <h3 style="display:flex;justify-content:center;align-items:center;width:100%;">Error 402!!</h3>
                            <div style="display:flex;justify-content:center;align-items:center;width:100%;">Missing Parameters!!</div>
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

    <script src="<?php echo $file_dir; ?>assets/js/admin.custom.js"></script>
    <script src="<?php echo $file_dir; ?>assets/bundles/bootstrap-daterangepicker/daterangepicker.js"></script>
    <style>
        textarea{
            min-height: 120px !important;
        }
        .card{
            margin: 10px 0px;
        }
        .main-footer{
            margin-top: 10px !important;
        }
    </style>
    
</body>

</html>