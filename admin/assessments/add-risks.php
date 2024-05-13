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
    $toEdit = false;
    
    if (isset($_GET['id']) && isset($_GET['id']) !== "") {
        $ass_Id = sanitizePlus($_GET['id']);
        $toDisplay = true;
        $CheckIfAssessmentExist = "SELECT * FROM as_assessment WHERE as_id = '$ass_Id'";
        $AssessmentExist = $con->query($CheckIfAssessmentExist);
        if ($AssessmentExist->num_rows > 0) {	
            $ass_exist = true;	

			$assessment_details = $AssessmentExist->fetch_assoc();
			$hasValue = $assessment_details['has_values'];
            $riskType = $assessment_details["as_type"];
            $assType = $assessment_details['as_type'];
			
			$toEdit = true;
            $selrisk = -1;
                    
            $query="SELECT * FROM as_types WHERE idtype = '$assType'";
            $result=$con->query($query);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $assType = $row['ty_name'];
            }else{
                $assType = 'Error!';
            }

        }else{
            $ass_exist = false;
        }

        if (isset($_POST['update-assessment-details'])) {
            
            $assessmentId = sanitizePlus($_POST["assessment"]);

            if ($assessmentId == $ass_Id) {
                $risk = sanitizePlus($_POST["risk"]);
                $hazard = sanitizePlus($_POST["hazard"]);
                $descript = sanitizePlus($_POST["descript"]);
                $likelihood = sanitizePlus($_POST["likelihood"]);
                $consequence = sanitizePlus($_POST["consequence"]);
                $effectiveness = sanitizePlus($_POST["effectiveness"]);
                $actiontake = sanitizePlus($_POST["actiontake"]);
                $date = sanitizePlus($_POST["date"]);
                $owner = sanitizePlus($_POST["owner"]);
                
                
                if (isset($_POST["custom-control"]) && $_POST["custom-control"] != null) {
                    $custom_control = serialize($_POST["custom-control"]);
                }else{
                    $custom_control = 'null'; #empty array
                }
                
                
                if (isset($_POST["custom-treatment"]) && $_POST["custom-treatment"] != null) {
                    $custom_treatment = serialize($_POST["custom-treatment"]);
                }else{
                    $custom_treatment = 'null'; #empty array
                }
                
                
                if (isset($_POST["existing_ct"]) && $_POST["existing_ct"] == 0) {
                    $existing_ct = 'null'; #empty array
                }else{
                    $existing_ct = sanitizePlus($_POST["existing_ct"]); 
                }
                
                if (isset($_POST["saved-control"]) && $_POST["saved-control"] == 'null') {
                    $saved_control = 'null'; #empty array
                }else{
                    $saved_control = sanitizePlus($_POST["saved-control"]);
                }
                
                if (isset($_POST["saved-treatment"]) && $_POST["saved-treatment"] == 'null') {
                    $saved_treatment = 'null'; #empty array
                }else{
                    $saved_treatment = sanitizePlus($_POST["saved-treatment"]);
                }
                
                // $custom_control_main = sanitizePlus($_POST["custom-control-main"]);
                // $custom_treatment_main = sanitizePlus($_POST["custom-treatment-main"]);
                
                
                if (!$risk || $risk == null || !$hazard || $hazard == null || !$descript || $descript == null || !$likelihood || $likelihood == null || !$consequence || $consequence == null || !$effectiveness || $effectiveness == null || !$actiontake || $actiontake == null || !$date || $date == null || !$owner || $owner == null) {
                    array_push($message, 'Error 402: Incomplete Parameters');
                } else {
                    # code...
                    $ri_id = secure_random_string(10);
                    $date = date("Y-m-d", strtotime($date));
                    $rating = calculateRating($likelihood, $consequence, $con);
                    $InsertRisk = "INSERT INTO as_details (recommended_control, saved_control, saved_treatment, custom_control, custom_treatment, as_risk, as_hazard, as_descript, as_like, as_consequence, as_rating, as_effect, as_action, as_duedate, as_owner, as_details_has_value, c_id, as_id, as_assessment, ri_id) VALUES ('$existing_ct', '$saved_control', '$saved_treatment', '$custom_control', '$custom_treatment', '$risk', '$hazard', '$descript', '$likelihood', '$consequence', '$rating', '$effectiveness', '$actiontake', '$date', '$owner', 'true', '$company_id', '$ass_Id', '$ass_Id', '$ri_id')";
                    #$InsertRisk = "INSERT INTO as_details (custom_treatment_main, custom_control_main, recommended_control, saved_control, saved_treatment, custom_control, custom_treatment, as_risk, as_hazard, as_descript, as_like, as_consequence, as_rating, as_effect, as_action, as_duedate, as_owner, as_details_has_value, c_id, as_id, as_assessment, ri_id) VALUES ('$custom_treatment_main', '$custom_control_main', '$existing_ct', '$saved_control', '$saved_treatment', '$custom_control', '$custom_treatment', '$risk', '$hazard', '$descript', '$likelihood', '$consequence', '$rating', '$effectiveness', '$actiontake', '$date', '$owner', 'true', '$company_id', '$ass_Id', '$ass_Id', '$ri_id')";
                    $RiskInserted = $con->query($InsertRisk);  
                    if ($RiskInserted) {
                        if ($hasValue == 'true') {
                        }else{
                            $con->query("UPDATE as_assessment SET has_values = 'true' WHERE as_id = '$assessmentId' AND c_id = '$company_id'");
                        }
                        #create notification and send notifier email
                        $notification_message = "New Risk Created Successfully";
                        $datetime = date("Y-m-d H:i:s");
                        $notify_link = "admin/assessments/risks?id=".$ri_id;
                        $notifier = $userId;
                        $type = 'risk';
                        $case = 'new';
                        $notificationResult = createNotification($company_id, $notification_message, $datetime, $notifier, $notify_link, $type, $case, $con, $sitee);
                        
                        header("Location: risks?id=".$ri_id);
                    } else {
                        array_push($message, 'Error 502: Error 01!!');
                    }
                }
            } else {
                array_push($message, 'Error 402: Error!!');
            }
            
        }
    } else {
        $toDisplay = false;
    }
    
    if(isset($_GET['load']) && isset($_GET['load']) !== "pre-saved"){
        if(isset($_SESSION["presave"]) !== '' || isset($_SESSION["presave"]) !== null){
            var_dump( $_SESSION["presave"]);
        }else{
            echo 'error';
        }
        
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Add Risk | <?php echo $siteEndTitle; ?></title>
  <?php require '../../layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/footer.custom.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/admin.custom.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/bundles/prism/prism.css">
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
                <div class="card toEdit">
                    <div class="card-body">
                        <form method="post" id='fno'>
                        <!-- Identification -->
                        <div class="card-header">
                            <h3 class="d-inline">Risk Identification</h3>
                            <a href='assessment-details?id=<?php echo $ass_Id;?>' class="btn btn-primary btn-icon icon-left header-a">Assessment Details <i class="fas fa-arrow-right"></i></a>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Assessment Type</label>
                                <div id="risk_type_div" style='font-weight:400;text-transform:capitalize;'>
                                    <?php echo $assType; ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Risk</label>
                                <div id="risk_div">
                                    <?php echo listRisks($riskType, $selrisk, $con); ?>
                                </div>
                            </div>
                            <div class="form-group hazard">
                                <label>Risk Sub Category</label>
                                <div class='hazard_empty' style='font-weight:400;margin-top:5px;'>Select A Risk Above...</div>
                                <div id="hazard_div">
                                    <?php #echo listHazards($cathazard, $selhazard, $con) ;?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Risk Description</label>
                                <textarea name="descript" rows="4" class="form-control" placeholder="Enter risk description..." required><?php if(isset($_POST['descript'])){echo $_POST['descript'];} ?></textarea>
                            </div>
                        </div>
                        
                        <div class='div_divider'></div>
                        
                        <!-- Evaluation -->
                        <div class="card-header hh">
                            <h3 class="d-inline">Risk Evaluation</h3>
                            <i class="fas fa-question btn btn-primary btn-icon btn-small btn-help header-a" id="likeli_conseq"></i>
                        </div>
                        <div class="card-body">
                            <div class='row custom-row'>
                            <div class="form-group col-lg-4 col-12">
                                <label>Likelihood</label>
                                <?php echo listLikelihood(-1 , $con);?>
                            </div>
                            <div class="form-group col-lg-4 col-12">
                                <label>Consequence</label>
                                <?php echo listConsequence(-1 , $con); ?>
                            </div>
                            <div class="form-group risk-rating col-lg-4 col-12">
                                <label>Risk Rating</label>
                                <div id="rating"><span style='font-weight:400;'>Select Likelihood & Consequence</span></div>
                            </div>
                            </div>
                        </div>
                        
                        <div class='div_divider'></div>

                        <!-- Controls -->
                        <div class="card-header hh">
                            <h3 class="d-inline">Control Actions</h3>
                            <i class="fas fa-question btn btn-primary btn-icon btn-small btn-help header-a" id="swal-custom-2"></i>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="help-label">
                                    RiskSafe Recommended Controls
                                </label>
                                <select name="existing_ct" id="existing_ct" class="form-control" required>
                                    <option value="null" selected>None Selected</option>
                                    <?php echo listControl($company_id, $con); ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="help-label">
                                    Saved Custom Controls
                                </label>
                                <div class="add-customs">
                                    <div style='width:100%;margin-right:5px;' id='fh4nfve'>
                                    <select name="saved-control" class="form-control" required>
                                        <!-- add none selected -->
                                        <?php echo listCompanyControl($company_id, $con); ?>
                                    </select>
                                    </div>
                                    <a href='../customs/new-control?redirect=true' target='_blank' class="btn btn-sm btn-primary" id='fn4h9nf' style='width: 15%;display:flex;justify-content:center;align-items:center;'>+ Create New</a>
                                    <buttton id='f93nfo0' class="btn btn-sm btn-primary" type='button' data-toggle="tooltip" title="Refresh Customs List" data-placement="left" style='margin-left:5px;display:flex;justify-content:center;align-items:center;font-size:20px;padding:0 10px;'><i class='fas fa-spinner'></i></buttton>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="help-label">
                                    Assessment Specific Controls
                                </label>
                                <div class="add-customs">
                                    <input type="text" class="form-control" placeholder="Enter custom control description..." name='custom-treatment[]'>
                                    <button type="button" class="btn btn-sm btn-primary" id="btn-append-custom-control">+ Add</button>
                                </div>
                                <div id='add-customs-control'></div>
                                <div class="custom-controls"></div>
                            </div>
                            <div class="form-group">
                                <label>Control Effectiveness</label>
                                <textarea name="effectiveness" rows="4" class="form-control" placeholder="Enter control effectiveness..." required><?php if(isset($_POST['effectiveness'])){echo $_POST['effectiveness'];} ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Action Type</label>
                                <?php echo listActions(-1, $con); ?>
    
                            </div>
                        </div>
                        
                        <div class='div_divider'></div>

                        <!-- Treatment -->
                        <div class="card-header hh">
                            <h3 class="d-inline">Treatment Plans</h3>
                            <i class="fas fa-question btn btn-primary btn-icon btn-small btn-help header-a" id="swal-custom-1"></i>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="help-label">
                                    Saved Custom Treatments
                                </label>
                                <div class="add-customs">
                                    <div style='width:100%;margin-right:5px;' id='fh4nfvf'>
                                    <select name="saved-treatment" class="form-control" required style='margin-right:5px;'>
                                        <?php echo listCompanyTreatment($company_id, $con); ?>
                                    </select>
                                    </div>
                                    <a href='../customs/new-treatment?redirect=true' target='_blank' class="btn btn-sm btn-primary" style='width: 15%;display:flex;justify-content:center;align-items:center;'>+ Create New</a>
                                    <buttton id='f93nfo1' class="btn btn-sm btn-primary" type='button' data-toggle="tooltip" title="Refresh Customs List" data-placement="left" style='margin-left:5px;display:flex;justify-content:center;align-items:center;font-size:20px;padding:0 10px;'><i class='fas fa-spinner'></i></buttton>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="help-label">
                                    Assessment Specific Treatments
                                </label>
                                <div class="add-customs">
                                    <input type="text" class="form-control" placeholder="Enter custom control description..." name='custom-treatment[]'>
                                    <button type="button" class="btn btn-sm btn-primary" id="btn-append-custom-treatment">+ Add</button>
                                </div>
                                <div id='add-customs-treatment'></div>
                            </div>
                            <div class='row custom-row'>
                            <div class="form-group col-lg-8 col-12">
                                <label>Action Owner</label>
                                <input name="owner" id="owner" type="text" maxlength="100" class="form-control" placeholder="Enter action owner..." required value="<?php if(isset($_POST['owner'])){echo $_POST['owner']; } ?>">
                            </div>
                            <div class="form-group col-lg-4 col-12">
                                <label>Due Date</label>
                                <input name="date" id="date" type="text" maxlength="100" class="form-control datepicker" placeholder="Select date..." required style="cursor:pointer;" value="<?php echo date("Y-m-d"); ?>">
                            </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <button type="submit" class="btn btn-md btn-primary" name="update-assessment-details">Create Risk</button>
                                <button type="button" class="btn btn-md btn-warning" id="btn_cancel">Cancel</button>
                                <input type="hidden" name="assessment" value="<?php echo $ass_Id; ?>">
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
                <?php }else{ ?>
                <div class="card">
                    <div class="card-body" style="display:flex;justify-content:center;align-items:center;min-height:500px;">
                        <div>
                            <h3 style="display:flex;justify-content:center;align-items:center;width:100%;">Error 402!!</h3>
                            <div style="display:flex;justify-content:center;align-items:center;width:100%;">Assessment Parameters Does Not Exist!!</div>
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
            
            <form id="getRisk" class="ajax-form">
                <input type="hidden" name="category" id="get_risk_category">
                <input type="hidden" name="selected" id="get_risk_selected">
            </form>
            <form id="getRating" class="ajax-form">
                <input type="hidden" name="consequence" id="get_risk_consequence">
                <input type="hidden" name="likelihood" id="get_risk_likelihood">
            </form>
        </div>
        <div id="testId"></div>
        <?php require '../../layout/footer.php' ?>
        </footer>
        </div>
    </div>
    <?php require '../../layout/general_js.php' ?>
    <script src="<?php echo $file_dir; ?>assets/bundles/prism/prism.js"></script>
    <script src="<?php echo $file_dir; ?>assets/bundles/sweetalert/sweetalert.min.js"></script>
    <script src="<?php echo $file_dir; ?>assets/js/page/sweetalert.js"></script>
    <script src="<?php echo $file_dir; ?>assets/js/admin.custom.js"></script>
    <script src="<?php echo $file_dir; ?>assets/bundles/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src='../../assets/js/admin/assessment.js'></script>
    <script>
        // if (typeof(Storage) !== "undefined") {
        //     alert('allows ls');
        // } else {
        //     alert('does not allows ls');
        // }
        
        // $("#fno").submit(function (event) {
        //   // alert('first first stop!');
        //   event.preventDefault();
        
        //   var formValues = $(this).serialize();
        
        //   $.post("../ajax/presave", {
        //     preSaveData: formValues,
        //   }).done(function (data) {
            
        //     alert('sent!');
        //   });
        // });
    </script>
    <style>
        textarea{
            min-height: 120px !important;
        }
        
        .assessment-hr{
            margin: 20px 30px 40px 30px;
        }
        .card{
            margin: 10px 0px;
        }
        .main-footer{
            margin-top: 10px !important;
        }
        .div_divider{
            margin: 20px 0px;
        }
        .card-header.hh{
            display:flex;
            justify-content:space-between;
        }
    </style>
    
</body>

</html>