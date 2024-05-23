<?php
    session_start();
    $file_dir = '../../';
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'auth/sign-in?r=/assessments/all');
        exit();
    }
    $message = [];
    include '../../layout/db.php';
    include '../../layout/admin__config.php';
    include '../ajax/assessment.php';

    $ass_exist = false;
    $noValue = true;
    #$toEdit = false;
    

    if (isset($_GET['id']) && isset($_GET['id']) !== "") {
        $assess_id = sanitizePlus($_GET['id']);
        $toDisplay = true;
        
        if (isset($_POST['update-risk'])) {
            
            $assessmentId = sanitizePlus($_POST["assessment"]);

            if ($assessmentId == $assess_id) {
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
                
                if (!$risk || $risk == null || !$hazard || $hazard == null || !$descript || $descript == null || !$likelihood || $likelihood == null || !$consequence || $consequence == null || !$effectiveness || $effectiveness == null || !$actiontake || $actiontake == null || !$date || $date == null || !$owner || $owner == null) {
                    array_push($message, 'Error 402: Incomplete Parameters');
                } else {
                    # code...
                    $date = date("Y-m-d", strtotime($date));
                    $rating = calculateRating($likelihood, $consequence, $con);
                    $UpdateRisk = "UPDATE as_details SET recommended_control = '$existing_ct', saved_control = '$saved_control', saved_treatment = '$saved_treatment', custom_control = '$custom_control', custom_treatment = '$custom_treatment', as_risk = '$risk', as_hazard = '$hazard', as_descript = '$descript', as_like = '$likelihood', as_consequence = '$consequence', as_rating = '$rating', as_effect = '$effectiveness', as_action = '$actiontake', as_duedate = '$date', as_owner = '$owner', as_details_has_value = 'true' WHERE ri_id = '$assess_id' AND c_id = '$company_id'";
                    $RiskInserted = $con->query($UpdateRisk);  
                    if ($RiskInserted) {
                        
                        array_push($message, 'Risk Details Updated Successfully!!');
                        
                    } else {
                        array_push($message, 'Error 502: Error 01!!');
                    }
                }
            } else {
                array_push($message, 'Error 402: Error!!');
            }
            
        }
        
        $CheckIfAssessmentDetailsExist = "SELECT * FROM as_details WHERE ri_id = '$assess_id' AND c_id = '$company_id'";
        $AssessmentDetailsExist = $con->query($CheckIfAssessmentDetailsExist);
        if ($AssessmentDetailsExist->num_rows > 0) {	
            $ass_exist = true;	

			$info = $AssessmentDetailsExist->fetch_assoc();

			$as_risk = $info['as_risk'];
            $as_hazard = $info['as_hazard'];
            $as_like = $info['as_like'];
            $as_consequence = $info['as_consequence'];
            $as_rating = $info['as_rating'];
            $as_descript = $info['as_descript'];
            $as_effect = $info['as_effect'];
            $as_action = $info['as_action'];
            $as_duedate = $info['as_duedate'];
            $as_owner = $info['as_owner'];
            
            // $custom_control_main = $info['custom_control_main'];
            // $custom_treatment_main = $info['custom_treatment_main'];
            
            $_assess_id = $info['as_id'];

            $recommended_control = $info['recommended_control'];
            $saved_control = $info['saved_control'];	
            $saved_treatment = $info['saved_treatment'];	
            $custom_control = $info['custom_control'];	
            $custom_treatment = $info['custom_treatment'];	
            
            $as_details_has_value = $info['as_details_has_value'];	

            $un_custom_control = unserialize($custom_control);
            $un_custom_treatment = unserialize($custom_treatment);
            
            $hasCustomControl = is_array($un_custom_control);
            $hasCustomTreatment = is_array($un_custom_treatment);

            if ($hasCustomControl == true) {
                #if value in db is array
                $customControlArrayStatus = 'true';
                if ($custom_control == 'a:1:{i:0;s:0:"";}') {
                    #empty array
                    $customControlValuesStatus = 'empty';
                    #show a single empty textbox
                } else if ($custom_control == null){
                    $customControlValuesStatus = 'empty';
                    #show all details
                } else {
                    $customControlValuesStatus = 'not-empty';
                    #show all details
                }
            } else {
                $customControlArrayStatus = 'false';
            }

            if ($hasCustomTreatment == true) {
                #if value in db is array
                $customTreatmentArrayStatus = 'true';
                if ($custom_treatment == 'a:1:{i:0;s:0:"";}') {
                    #empty array
                    $customTreatmentValuesStatus = 'empty';
                    #show a single empty textbox
                } else if ($custom_treatment == null) {
                    $customTreatmentValuesStatus = 'empty';
                    #show all details
                } else {
                    $customTreatmentValuesStatus = 'not-empty';
                    #show all details
                }   
            } else {
                $customTreatmentArrayStatus = 'false';
            }
            
			
			#$toEdit = true;
            #$selrisk = -1;
            #$selhazard = -1;
            #$cathazard = -1;
            $selcontrol = -1;
            $detId = -1;

            $CheckIfAssessmentExist = "SELECT * FROM as_assessment WHERE as_id = '$_assess_id' AND c_id = '$company_id'";
            $AssessmentExist = $con->query($CheckIfAssessmentExist);
            if ($AssessmentExist->num_rows > 0) {
                $as_info = $AssessmentExist->fetch_assoc();
                $riskType_ = $as_info["as_type"];

                $query="SELECT * FROM as_types WHERE idtype = '$riskType_'";
                $result=$con->query($query);
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $riskType = $row['ty_name'];
                }else{
                    $riskType = 'Error!';
                }
            }else{
                $riskType = 'Error!!';
            }
            
            #get hazards
            $CheckIfHazardExist = "SELECT * FROM as_cat WHERE idcat = '$as_hazard'";
            $HazardExist = $con->query($CheckIfHazardExist);
            if ($HazardExist->num_rows > 0) {
                $as_h_info = $HazardExist->fetch_assoc();
                $cat_risk = $as_h_info["cat_risk"];
            }else{
                $cat_risk = 'Error!!';
            }
            
            $cathazard = $cat_risk;
            $selrisk = $as_risk;
            $selhazard = $as_hazard;
            
            
        }else{
            $ass_exist = false;
        }
        
        

    } else {
        $toDisplay = false;
    }
    
    
    #add a new column to the db, custom_control_main, custom_treatment_main
    #change risk rating to risk evaluation rating and make them col-lg-6
    #remove the no custom ... created yet from the while and make it a response to avoid looping, change WHERE company_id to c_id
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Edit Risk | <?php echo $siteEndTitle; ?></title>
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
                        <?php include '../../layout/alert.php'; ?>
                        <form method="post">
                        <!-- Identification -->
                        <div class="card-header">
                            <h3 class="d-inline">Risk Identification</h3>
                            <a href='assessment-details?id=<?php echo $_assess_id;?>' class="btn btn-primary btn-icon icon-left header-a">Assessment Details <i class="fas fa-arrow-right"></i></a>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Assessment Type</label>
                                <div id="risk_type_div" style='font-weight:400;text-transform:capitalize;'>
                                    <?php echo $riskType; ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Risk</label>
                                <div id="risk_div">
                                    <?php echo listRisks($riskType_, $as_risk, $con); ?>
                                </div>
                            </div>
                            <div class="form-group hazard">
                                <label>Risk Sub Category</label>
                                <div id="hazard_div">
                                    <?php echo listHazards($cathazard, $as_hazard, $con) ;?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Risk Description</label>
                                <textarea name="descript" rows="4" class="form-control" placeholder="Enter risk description..." required><?php echo $as_descript; ?></textarea>
                            </div>
                        </div>
                        
                        <div class='div_divider'></div>
                        
                        <!-- Evaluation -->
                        <div class="card-header hh">
                            <h3 class="d-inline">Risk Evaluation</h3>
                            <i class="fas fa-question btn btn-primary btn-icon btn-small btn-help header-a" id="likeli_conseq"></i>
                        </div>
                        
                        <div class="card-body">
                            <div class="row custom-row">
                                <div class="form-group col-lg-4 col-12">
                                    <label>Likelihood</label>
                                    <?php echo listLikelihood($as_like , $con);?>
                                </div>
                                <div class="form-group col-lg-4 col-12">
                                    <label>Consequence</label>
                                    <?php echo listConsequence($as_consequence , $con); ?>
                                </div>
                                <div class="form-group col-lg-4 col-12 risk-rating">
                                    <label>Risk Rating</label>
                                    <div id="rating"></div>
                                </div>
                                <div class="form-group col-lg-4 col-12 risk-rating-show">
                                    <label>Risk Rating</label>
                                    <div id="rating-show"><?php echo rating($as_like, $as_consequence, $con); ?></div>
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
                                    <i class="fas fa-question btn btn-primary btn-icon btn-small btn-help" id="swal-custom-2"></i>
                                </label>
                                <select name="existing_ct" id="existing_ct" class="form-control" required>
                                    <option value="0" <?php if($recommended_control == 'null'){ echo 'selected';} ?>>None Selected</option>
                                    <?php echo listControlSelected($recommended_control, $con); ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="help-label">
                                    Saved Custom Controls
                                </label>
                                <div class="add-customs">
                                    <div style='width:100%;margin-right:5px;' id='fh4nfve'>
                                    <select name="saved-control" class="form-control" required>
                                        <?php echo listCompanyControlSelected($company_id, $saved_control, $con); ?>
                                    </select>
                                    </div>
                                    <a href='../customs/new-control?redirect=true' target='_blank' id='fn4h9nf' class="btn btn-sm btn-primary" style='width: 15%;display:flex;justify-content:center;align-items:center;'>+ Create New</a>
                                    <buttton id='f93nfo0' class="btn btn-sm btn-primary" type='button' data-toggle="tooltip" title="Refresh Customs List" data-placement="left" style='margin-left:5px;display:flex;justify-content:center;align-items:center;font-size:20px;padding:0 10px;'><i class='fas fa-spinner'></i></buttton>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="help-label">
                                    Assessment Specific Controls
                                </label>
                                
                                <?php if($custom_control == 'null'){ ?>
                                <div class="add-customs">
                                    <input type="text" class="form-control" placeholder="Enter custom control description..." name='custom-control[]'>
                                    <button type="button" class="btn btn-sm btn-primary" id="btn-append-custom-control">+ Add</button>
                                </div>
                                <div id='add-customs-control'></div>
                                <?php }else{ ?>
                                
                                <?php foreach(unserialize($custom_control) as $value){ ?>
                                <div class="add-customs">
                                    <input type="text" class="form-control" placeholder="Enter custom control description..." name='custom-control[]' value='<?php echo $value; ?>'/>
                                    <button type="button" class="btn btn-sm btn-primary" id="btn-append-custom-control">+ Add</button>
                                </div>
                                <?php break; ?>
                                <?php } ?>
                                
                                <div id='add-customs-control'>
                                    <?php foreach(unserialize($custom_control) as $k => $value){ ?>
                                    <?php if($k){ ?>
                                        <div style="display:flex;justify-content:center;align-items:center;">
                                            <input type="text" class="form-control" value='<?php echo $value; ?>' placeholder="Enter Custom control Description..." style="margin-top:5px;" name="custom-control[]" />
                                            <buttton class="btn btn-sm btn-primary remove_button r" type="button"><i class="fas fa-minus"></i></buttton>
                                        </div>
                                    <?php } ?>
                                    <?php } ?>
                                </div>
                                <?php } ?>
                                
                            </div>
                            <div class="form-group">
                                <label>Control Effectiveness</label>
                                <textarea name="effectiveness" rows="4" class="form-control" placeholder="Enter control effectiveness..." required><?php echo $as_effect; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Action Type</label>
                                <?php echo listActions($as_action, $con); ?>
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
                                        <?php echo listCompanyTreatmentSelected($company_id, $saved_treatment, $con); ?>
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
                                
                                <?php if($custom_treatment == 'null'){ ?>
                                <div class="add-customs">
                                    <input type="text" class="form-control" placeholder="Enter custom Treatment description..." name='custom-treatment[]'>
                                    <button type="button" class="btn btn-sm btn-primary" id="btn-append-custom-treatment">+ Add</button>
                                </div>
                                <div id='add-customs-treatment'></div>
                                <?php }else{ ?>
                                
                                <?php foreach(unserialize($custom_treatment) as $value){ ?>
                                <div class="add-customs">
                                    <input type="text" class="form-control" placeholder="Enter custom Treatment description..." name='custom-treatment[]' value='<?php echo $value; ?>'/>
                                    <button type="button" class="btn btn-sm btn-primary" id="btn-append-custom-treatment">+ Add</button>
                                </div>
                                <?php break; ?>
                                <?php } ?>
                                
                                <div id='add-customs-treatment'>
                                    <?php foreach(unserialize($custom_treatment) as $k => $value){ ?>
                                    <?php if($k){ ?>
                                        <div style="display:flex;justify-content:center;align-items:center;">
                                            <input type="text" class="form-control" value='<?php echo $value; ?>' placeholder="Enter Custom Treatment Description..." style="margin-top:5px;" name="custom-treatment[]" />
                                            <buttton class="btn btn-sm btn-primary remove_button_t r" type="button"><i class="fas fa-minus"></i></buttton>
                                        </div>
                                    <?php } ?>
                                    <?php } ?>
                                </div>
                                <?php } ?>
                                
                            </div>
                            <div class='row custom-row'>
                                <div class="form-group col-lg-8 col-12">
                                    <label>Action Owner</label>
                                    <input name="owner" id="owner" type="text" maxlength="100" class="form-control" placeholder="Enter action owner..." required value="<?php echo $as_owner;  ?>">
                                </div>
                                <div class="form-group col-lg-4 col-12">
                                    <label>Due Date</label>
                                    <input name="date" id="date" type="text" maxlength="100" class="form-control datepicker" placeholder="Select date..." required style="cursor:pointer;" value="<?php echo date("Y-m-d", strtotime($as_duedate)); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <button type="submit" class="btn btn-md btn-primary" name="update-risk">Update Risk</button>
                                <button type="button" class="btn btn-md btn-warning" id="btn_cancel">Cancel</button>
                                <input type="hidden" name="assessment" value="<?php echo $assess_id; ?>">
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
    <script>
        $(document).ready(function () {
            
        
            
            $("#consequence").change(function (e) {
              $(".risk-rating-show").hide();
            });
            
            $("#likelihood").change(function (e) {
              $(".risk-rating-show").hide();
            });
        
        
        
            
        });
    </script>
    <script src="<?php echo $file_dir; ?>assets/bundles/prism/prism.js"></script>
    <script src="<?php echo $file_dir; ?>assets/bundles/sweetalert/sweetalert.min.js"></script>
    <script src="<?php echo $file_dir; ?>assets/js/page/sweetalert.js"></script>
    <script src="<?php echo $file_dir; ?>assets/js/admin.custom.js"></script>
    <script src="<?php echo $file_dir; ?>assets/bundles/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src='../../assets/js/admin/assessment.js'></script>
    <style>
        textarea{
            min-height: 120px !important;
        }
        .div_divider{
            margin: 20px 0px;
        }
        .card-header.hh{
            display:flex;
            justify-content:space-between;
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
        ::placeholder { 
            text-transform: capitalize;
            opacity: 1; /* Firefox */
        }

        :-ms-input-placeholder { 
            text-transform: capitalize;
        }

        ::-ms-input-placeholder { 
            text-transform: capitalize;
        }
        .risk-rating{
            display:none;
        }
        .remove_button.r,
        .remove_button_t.r{
            margin-left:5px;display:flex;justify-content:center;align-items:center;font-size:20px;padding:12px 10px;
        }
    </style>
    
</body>

</html>