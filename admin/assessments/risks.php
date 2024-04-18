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

    if (isset($_GET['id']) && isset($_GET['id']) !== "") {
        $ri_id = sanitizePlus($_GET['id']);
        $toDisplay = true;
        $CheckIfAssessmentDetailsExist = "SELECT * FROM as_details WHERE ri_id = '$ri_id' AND c_id = '$company_id'";
        $AssessmentDetailsExist = $con->query($CheckIfAssessmentDetailsExist);
        if ($AssessmentDetailsExist->num_rows > 0) {	
            $ass_exist = true;	

			$info = $AssessmentDetailsExist->fetch_assoc();

			$as_risk = $info['as_risk'];
			$assess_id = $info['as_id'];
            $as_hazard = $info['as_hazard'];
            $as_like = $info['as_like'];
            $as_consequence = $info['as_consequence'];
            $as_rating = $info['as_rating'];
            $as_descript = $info['as_descript'];
            $as_effect = $info['as_effect'];
            $as_action = $info['as_action'];
            $as_duedate = $info['as_duedate'];
            $as_owner = $info['as_owner'];
            $custom_control_main = $info['custom_control_main'];
            $custom_treatment_main = $info['custom_treatment_main'];

            $recommended_control = $info['recommended_control'];
            $saved_control = $info['saved_control'];	
            $saved_treatment = $info['saved_treatment'];	
            $custom_control = $info['custom_control'];	
            $custom_treatment = $info['custom_treatment'];
            // $custom_treatment_main = $info['custom_treatment_main'];
            // $custom_control_main = $info['custom_control_main'];
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
                } else if ($custom_control == 'null'){
                    $customControlValuesStatus = 'empty';
                } else if ($custom_control == null){
                    $customControlValuesStatus = 'empty';
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
                } else if ($custom_treatment == 'null') {
                    $customTreatmentValuesStatus = 'empty';
                    #show all details
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
            $selrisk = -1;
            $selhazard = -1;
            $cathazard = -1;
            $selcontrol = -1;
            $detId = -1;

            $CheckIfAssessmentExist = "SELECT * FROM as_assessment WHERE as_id = '$assess_id' AND c_id = '$company_id'";
            $AssessmentExist = $con->query($CheckIfAssessmentExist);
            if ($AssessmentExist->num_rows > 0) {
                $as_info = $AssessmentExist->fetch_assoc();
                $riskType = $as_info["as_type"];

                $query="SELECT * FROM as_types WHERE idtype = '$riskType'";
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
  <title>Risk Details | <?php echo $siteEndTitle; ?></title>
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
                <?php if ($toDisplay == true) { ?>
                <?php if ($ass_exist == true) { ?>
                <div class="card toEdit">
                    <div class="card-body">
                        <!-- Identification -->
                        <div class="card-header">
                            <h3 class="d-inline">Risk Identification</h3>
                            <a class="btn btn-primary btn-icon icon-left header-a" href="assessment-details?id=<?php echo $assess_id; ?>"><i class="fas fa-arrow-left"></i> Go Back</a>
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
                                    <div class="r_desc"><?php echo getRisks($as_risk, $con); ?></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Risk Hazard</label>
                                <div id="hazard_div">
                                    <div class="r_desc"><?php echo getHazards($as_hazard, $con) ;?></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Risk Description</label>
                                <div class="r_desc"><?php echo $as_descript; ?></div>
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
                                    <div class="r_desc"><?php echo getLikelihood($as_like, $con);?></div>
                                </div>
                                <div class="form-group col-lg-4 col-12">
                                    <label>Consequence</label>
                                    <div class="r_desc"><?php echo getConsequence($as_consequence , $con); ?></div>
                                </div>
                                <div class="form-group col-lg-4 col-12">
                                    <label>Evaluation Rating</label>
                                    <div class="r_desc"><?php echo rating($as_like, $as_consequence, $con); ?></div>
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
                                <div class="r_desc"><?php echo getControlSelected($recommended_control, $con); ?></div>
                            </div>
                            <div class="form-group">
                                <label class="help-label">
                                    Saved Custom Controls
                                </label>
                                <div class="add-customs">
                                    <div class="r_desc"><?php echo getCompanyControlSelected($company_id, $saved_control, $con); ?></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="help-label">
                                    Assessment Specific Controls
                                </label>
                                <div class="add-customss">
                                    <?php if($custom_control == 'null'){ ?>
                                    <div class="r_desc">No Assessment Specific Controls Specified!</div>
                                    <?php }else{ ?>
                                    <div id='add-customs-control'>
                                        <ul class="r_value_ul">
                                        <?php foreach (unserialize($custom_control) as $value) { ?>
                                            <?php if ($value !== '' || $value !== null) { ?>
                                            <li class="r_value"><?php echo ucwords($value); ?></li>
                                        <?php }} ?>
                                        </ul>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Control Effectiveness</label>
                                <div class="r_desc"><?php echo $as_effect; ?></div>
                            </div>
                            <div class="form-group">
                                <label>Action Type</label>
                                <div class="r_desc"><?php echo getlistActions($as_action, $con); ?> </div>
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
                                    <div class="r_desc"><?php echo getCompanyTreatmentSelected($company_id, $saved_treatment, $con); ?></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="help-label">
                                    Assessment Specific Treatments
                                </label>
                                <div id='add-customss'>
                                    <?php if($custom_treatment == 'null'){ ?>
                                    <div class="r_desc">No Assessment Specific Treatment Specified!</div>
                                    <?php }else{ ?>
                                    <div id='add-customs-control'>
                                        <ul class="r_value_ul">
                                        <?php foreach (unserialize($custom_treatment) as $value) { ?>
                                            <?php if ($value !== '' || $value !== null) { ?>
                                            <li class="r_value"><?php echo ucwords($value); ?></li>
                                        <?php }} ?>
                                        </ul>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class='row custom-row'>
                            <div class="form-group col-lg-8 col-12">
                                <label>Action Owner</label>
                                <div class="r_desc"><?php echo $as_owner;  ?></div>
                            </div>
                            <div class="form-group col-lg-4 col-12">
                                <label>Due Date</label>
                                <div class="r_desc"><?php echo date("Y-m-d", strtotime($as_duedate)); ?></div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
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
    </style>
    
</body>

</html>