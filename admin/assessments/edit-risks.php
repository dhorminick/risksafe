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
        $assess_id = sanitizePlus($_GET['id']);
        $toDisplay = true;
        
        if (isset($_POST['update-risk']) && isset($_POST["control-type"]) && isset($_POST["treatment-type"])) {
            $error = false;
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
                $risk_type = sanitizePlus($_POST['risk_type']);
                $owner = sanitizePlus($_POST["owner"]);
                $control_type = sanitizePlus($_POST["control-type"]);
                $treatment_type = sanitizePlus($_POST["treatment-type"]);
                
                $incidents = serialize($_POST["incidents"]);
                
                $causes = serialize($_POST["causes"]);
                $kri = sanitizePlus($_POST["kri"]);
                
                if($control_type == 'recommended'){
                    $control = serialize($_POST["existing_ct"]);
                }else if($control_type == 'saved'){
                    $control = serialize($_POST["saved-control"]);
                }else if($control_type == 'custom'){
                    $control = serialize($_POST["custom-control"]);
                }else if($control_type == 'na'){
                    $control = 'Not Assessed!';
                }else{
                    $error = true;
                    array_push($message, 'Error 402: Control Type Error!!');
                }
                
                if($treatment_type == 'saved'){
                    $treatment = serialize($_POST["saved-treatment"]);
                }else if($treatment_type == 'custom'){
                    $treatment = serialize($_POST["custom-treatment"]);
                }else if($treatment_type == 'na'){
                    $treatment = 'Not Assessed!';
                }else{
                    $error = true;
                    array_push($message, 'Error 402: Treatment Type Error!!');
                }
                
                if($error == false){
                    # code...
                    $rating = calculateRating($likelihood, $consequence, $con);
                    $updateRisk = "UPDATE as_assessment_new SET kri = '$kri', incidents = '$incidents', causes = '$causes', risk_type = '$risk_type', risk = '$risk', sub_risk = '$hazard', description = '$descript', likelihood = '$likelihood', consequence = '$consequence', rating= '$rating', control_type = '$control_type', control = '$control', control_effectiveness = '$effectiveness', control_action = '$actiontake', treatment_type = '$treatment_type', treatment = '$treatment', owner = '$owner', due_date = '$date' WHERE risk_id = '$assessmentId' AND c_id = '$company_id'";
                    $RiskInserted = $con->query($updateRisk);  
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
        
        $CheckIfAssessmentDetailsExist = "SELECT * FROM as_assessment_new WHERE risk_id = '$assess_id' AND c_id = '$company_id'";
        $AssessmentDetailsExist = $con->query($CheckIfAssessmentDetailsExist);
        if ($AssessmentDetailsExist->num_rows > 0) {	
            $ass_exist = true;	

			$info = $AssessmentDetailsExist->fetch_assoc();
            
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
                        <div class='card-body'><strong>NOTE:</strong> Selecting a new risk, would reset the sub risk, risk description, and recommended controls section if in use!!</div>
                        <form method="post">
                        <!-- Identification -->
                        <div class="card-header">
                            <h3 class="d-inline">Risk Identification</h3>
                            <a href='assessment-details?id=<?php echo $info['assessment'];?>' class="btn btn-primary btn-icon icon-left header-a">Assessment Details <i class="fas fa-arrow-right"></i></a>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Assessment Industry: </label>
                                <div id="risk_type_div" style='font-weight:400;text-transform:capitalize;'>
                                    <?php echo ucwords(getIndustryTitle($info['industry'], $con)); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Risk</label>
                                <div id="risk_div" style='display:flex;align-items:center;'>
                                    <div style='width:100%;margin-right:5px;' id='_riskdiv'>
                                        <?php echo listRisksNew($info['industry'], $info['risk'], $company_id, $con, 'hide'); ?>
                                    </div>
                                    <div style='display:flex;align-items:center;gap:10px;'>
                                        <a href='../customs/new-risk?redirect=true' target='_blank' class="btn btn-sm btn-primary" style='width: 15%;display:flex;justify-content:center;align-items:center;'>+ Create New</a>
                                        <buttton id='_riskdivloader' class="btn btn-sm btn-primary" type='button' style='margin-left:5px;display:flex;justify-content:center;align-items:center;font-size:20px;padding:0 10px;'><i class='fas fa-spinner'></i></buttton>
                                    </div>
                                </div>
                            </div>
                            
                            <input type='hidden' name='risk_type' id='selected_risk_type' />
                            
                            <div class="form-group hazard">
                                <label>Risk Sub Category</label>
                                <div id="hazard_div">
                                    <?php if($info['risk_type'] == 'custom'){ ?>
                                    <input type="text" name="hazard" class="form-control" value="<?php echo ucfirst($info['sub_risk']); ?>" />
                                    <?php }else{ ?>
                                    <?php echo listHazardsNewSelected($info['risk'], $info['sub_risk'], $con);?>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Risk Description</label>
                                <textarea name="descript" rows="4" id='risk-description' class="form-control" placeholder="Enter risk description..." required><?php echo $info['description']; ?></textarea>
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
                                <div class='col-12'>Inherent Evaluation</div>
                                <div class="form-group col-lg-4 col-12">
                                    <label>Likelihood</label>
                                    <?php echo listLikelihood($info['likelihood'] , $con);?>
                                </div>
                                <div class="form-group col-lg-4 col-12">
                                    <label>Consequence</label>
                                    <?php echo listConsequence($info['consequence'] , $con); ?>
                                </div>
                                <div class="form-group col-lg-4 col-12 risk-rating">
                                    <label>Risk Rating</label>
                                    <div id="rating"></div>
                                </div>
                                <div class="form-group col-lg-4 col-12 risk-rating-show">
                                    <label>Risk Rating</label>
                                    <div id="rating-show"><?php echo rating($info['likelihood'], $info['consequence'], $con); ?></div>
                                </div>
                            </div>
                            
                            <?php if($info['rating_residual'] !== null){ ?>
                            <div class="row custom-row">
                                <div class='col-12'>Residual Evaluation</div>
                                <div class="form-group col-lg-4 col-12">
                                    <label>Likelihood</label>
                                    <?php echo listLikelihood($info['likelihood_residual'] , $con);?>
                                </div>
                                <div class="form-group col-lg-4 col-12">
                                    <label>Consequence</label>
                                    <?php echo listConsequence($info['consequence_residual'] , $con); ?>
                                </div>
                                <!--<div class="form-group col-lg-4 col-12 risk-rating-residual">-->
                                <!--    <label>Risk Rating</label>-->
                                <!--    <div id="rating_residual"></div>-->
                                <!--</div>-->
                                <div class="form-group col-lg-4 col-12 risk-rating-show-residual">
                                    <label>Risk Rating</label>
                                    <div id="rating_residual"><?php echo rating($info['likelihood_residual'], $info['consequence_residual'], $con); ?></div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                        
                        <div class='divider'></div>
                        
                        <div class="card-header hh">
                            <h3 class="d-inline">Risk Causes</h3>
                        </div>
                        <div class="card-body">
                            <?php if($info['causes'] !== null){ ?>
                            <div class="form-group">
                                    <label class="help-label">
                                        Risk Causes
                                    </label>
                                    <div class="add-customs">
                                        <?php 
                                            $causes = unserialize($info['causes']);
                                            $cause_more = $causes;
                                            $t_count = 0;
                                            foreach($causes as $cause){
                                                $t_count++;
                                                echo "<div class='add-customs' style='width:100%;'>";
                                                echo '<input type="text" class="form-control" value="'.ucfirst($treatment).'" placeholder="Enter cause..." name="causes[]">';
                                                echo '<button type="button" class="btn btn-sm btn-primary" id="btn-append-causes">+ Add</button>';
                                                echo "</div>";
                                                break;
                                            }
                                        ?>
                                    </div>
                                    <div id='add-causes'>
                                        <?php 
                                            unset($cause_more[0]);
                                            foreach($cause_more as $_cause){
                                        ?>
                                        <div style="display:flex;justify-content:center;align-items:center;gap:5px;margin-top:5px;"> 
                                            <input type="text" class="form-control" value='<?php echo ucfirst($_cause); ?>' placeholder="Enter cause..." name="causes[]" style="margin-top:5px;"  required/>
                                            <buttton class="btn btn-sm btn-primary remove_button_causes" style='margin-left:5px;display:flex;justify-content:center;align-items:center;font-size:20px;padding:12px 10px;' type="button"><i class="fas fa-minus"></i></buttton>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php }else{ ?>
                            No risk causes specified!
                            <?php } ?>
                        </div>
                        
                        <div class='div_divider'></div>
                        
                        <!-- Indicators -->
                        <div class="card-header">
                            <h3 class="d-inline">Risk Indicator</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                    <label class="help-label">
                                        KRI
                                    </label>
                                    <div class="add-customs">
                                        <div style='width:100%;margin-right:5px;' id='fh4nfve_111'>
                                            <select name="kri" class="form-control" required>
                                                <?php echo listKRI($company_id,  $con, $info['kri']); ?>
                                            </select>
                                        </div>
                                        <a href='../monitoring/new-kri?redirect=true' target='_blank' class="btn btn-sm btn-primary" id='fn4h9nf' style='width: 15%;display:flex;justify-content:center;align-items:center;'>+ Create New</a>
                                        <buttton id='f93nfo0_111' class="btn btn-sm btn-primary" type='button' data-toggle="tooltip" title="Refresh Customs List" data-placement="left" style='margin-left:5px;display:flex;justify-content:center;align-items:center;font-size:20px;padding:0 10px;'><i class='fas fa-spinner'></i></buttton>
                                        <!--<button type="button" class="btn btn-sm btn-primary" id="btn-append-saved-control" style='margin-left:5px;'>+ Add</button>-->
                                    </div>
                                    
                                    <!--<div id='add-saved-control' style='margin-top:5px;'></div>-->
                            </div>
                        </div>
                        
                        <div class='div_divider'></div>
                            
                            <!-- Incidents -->
                                <div class="card-header">
                                    <h3 class="d-inline">Risk Incidents</h3>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                    <label class="help-label">
                                        Incidents
                                    </label>
                                    <div class="add-customs">
                                        <?php 
                                            $controls = unserialize($info['incidents']);
                                            $controls_more = $controls;
                                            $c_count = 0;
                                            foreach($controls as $control){
                                                $c_count++;
                                                echo "<div style='width:100%;margin-right:5px;' id='fh4nfve_1111'>";
                                                echo __listCompanyIncidents_Selected($company_id, $con, $control);
                                                echo "</div>";
                                                break;
                                            }
                                        ?>
                                        
                                        <a href='../customs/new-incident?redirect=true' target='_blank' class="btn btn-sm btn-primary" id='fn4h9nf' style='width: 15%;display:flex;justify-content:center;align-items:center;'>+ Create New</a>
                                        <buttton id='f93nfo0_1111' class="btn btn-sm btn-primary" type='button' data-toggle="tooltip" title="Refresh Customs List" data-placement="left" style='margin-left:5px;display:flex;justify-content:center;align-items:center;font-size:20px;padding:0 10px;'><i class='fas fa-spinner'></i></buttton>
                                        <button type="button" class="btn btn-sm btn-primary" id="btn-append-incident" style='margin-left:5px;'>+ Add</button>
                                    </div>
                                    
                                    <div id='add-incident' style='margin-top:5px;'>
                                        <?php 
                                            unset($controls_more[0]);
                                            foreach($controls_more as $_control){
                                        ?>
                                        <div style="display:flex;justify-content:center;align-items:center;gap:5px;margin-top:5px;"> 
                                            <?php echo __listCompanyIncidents_Selected($company_id, $con, $_control); ?>
                                            <buttton class="btn btn-sm btn-primary remove_button_t rmv_btn" style='margin-left:5px;display:flex;justify-content:center;align-items:center;font-size:20px;padding:12px 10px;' type="button"><i class="fas fa-minus"></i></buttton>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                </div>
                                
                        <div class='div_divider'></div>
                        
                        
                        <style>
                            <?php if($info['control_type'] == 'recommended'){ ?>
                            #saved_type,
                            #custom_type{
                                display:none;
                            }
                            #no_type_c{
                                    display:none;
                            }
                            <?php }else if($info['control_type'] == 'saved'){ ?>
                            #recommended_type,
                            #custom_type{
                                display:none;
                            }
                            #no_type_c{
                                    display:none;
                            }
                            <?php }else if($info['control_type'] == 'custom'){ ?>
                            #recommended_type,
                            #saved_type{
                                display:none;
                            }
                            #no_type_c{
                                    display:none;
                            }
                            <?php }else if($info['control_type'] == 'na'){ ?>
                                #recommended_type,
                            #saved_type,
                            #custom_type,
                            #no-effect{
                                display:none;
                            }
                            <?php } ?>
                        </style>

                        <!-- Controls -->
                        <div class="card-header hh">
                            <h3 class="d-inline">Control Actions</h3>
                            <i class="fas fa-question btn btn-primary btn-icon btn-small btn-help header-a" id="swal-custom-2"></i>
                        </div>
                        <div class="card-body">
                            <div class="form-group" style='display:flex;gap:50px;'>
                                <div>
                                    <input type='radio' id='recommended' value='recommended' name='control-type' <?php if($info['control_type'] == 'recommended'){ echo 'checked'; } ?> />
                                    <label for='recommended'>Recommended Controls</label>
                                </div>
                                <div>
                                    <input type='radio' id='saved' value='saved' name='control-type' <?php if($info['control_type'] == 'saved'){ echo 'checked'; } ?> />
                                    <label for='saved'>Saved Custom Controls</label>
                                </div>
                                <div>
                                    <input type='radio' id='assessment-specific' value='custom' name='control-type' <?php if($info['control_type'] == 'custom'){ echo 'checked'; } ?> />
                                    <label for='assessment-specific'>Assessment Specific Controls</label>
                                </div>
                                <div>
                                    <input type='radio' id='na-c' value='na' name='control-type' <?php if($info['control_type'] == 'na'){ echo 'checked'; } ?> />
                                    <label for='na-c'>N/A</label>
                                </div>
                            </div>
                            
                            <div id='control_type'>
                                <?php if($info['control_type'] == 'recommended'){ ?>
                                <div class="form-group" id='recommended_type'>
                                    <label class="help-label">
                                        RiskSafe Recommended Controls
                                    </label>
                                    
                                    <?php if($info['risk_type'] == 'custom') { ?>
                                    <div class='c_type' id='control_selctor'>
                                        <div id='fetchControls' style='width:100%;'>
                                            No Recommended Controls For Custom Risk!!
                                        </div>
                                    </div>
                                    
                                    <div id='add-rec-control'></div>
                                    <?php }else{ ?>
                                    <div class='c_type' id='control_selctor'>
                                        <div id='fetchControls' style='width:100%;'>
                                        <?php 
                                            $controls = unserialize($info['control']);
                                            $controls_more = $controls;
                                            $c_count = 0;
                                            foreach($controls as $control){
                                                $c_count++;
                                                echo listControl_NewSelected($info['risk'], $control, $con);
                                                break;
                                            }
                                        ?>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-primary" id="btn-append-rec-control">+ Add</button>
                                    </div>
                                    
                                    <div id='add-rec-control'>
                                        <?php 
                                            unset($controls_more[0]);
                                            foreach($controls_more as $_control){
                                        ?>
                                        <div style="display:flex;justify-content:center;align-items:center;gap:5px;margin-top:5px;"> 
                                            <?php echo listControl_NewSelected($info['risk'], $_control, $con); ?>
                                            <buttton class="btn btn-sm btn-primary remove_button_t rmv_btn" style='margin-left:5px;display:flex;justify-content:center;align-items:center;font-size:20px;padding:12px 10px;' type="button"><i class="fas fa-minus"></i></buttton>
                                        </div>
                                        <?php } ?>
                                    </div>
                                    <?php } ?>
                                    
                                </div> 
                                <?php }else{ ?>
                                <div class="form-group" id='recommended_type'>
                                    <label class="help-label">
                                        RiskSafe Recommended Controls
                                    </label>
                                    
                                    <?php if($info['risk_type'] == 'custom') { ?>
                                        <div class='c_type' id='control_selctor'>
                                            <div id='fetchControls' style='width:100%;'>
                                                No Recommended Controls For Custom Risk!!
                                            </div>
                                            
                                            <button type="button" class="btn btn-sm btn-primary" id="btn-append-rec-control">+ Add</button>
                                        </div>
                                        
                                        <div id='add-rec-control'></div>
                                    <?php }else{ ?>
                                        <div class='c_type' id='control_selctor'>
                                            <div id='fetchControls' style='width:100%;'>
                                            <?php echo listControl_New($info['risk'], $con); ?>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-primary" id="btn-append-rec-control">+ Add</button>
                                        </div>
                                        
                                        <div id='add-rec-control'></div>
                                    <?php } ?>
                                </div> 
                                <?php } ?>
                                
                                <?php if($info['control_type'] == 'saved'){ ?>
                                <div class="form-group" id='saved_type'>
                                    <label class="help-label">
                                        Saved Custom Controls
                                    </label>
                                    <div class="add-customs">
                                        <?php 
                                            $controls = unserialize($info['control']);
                                            $controls_more = $controls;
                                            $c_count = 0;
                                            foreach($controls as $control){
                                                $c_count++;
                                                echo "<div style='width:100%;margin-right:5px;' id='fh4nfve_11'>";
                                                echo listCompanyControlSelected_New($company_id, $control, $con);
                                                echo "</div>";
                                                break;
                                            }
                                        ?>
                                        
                                        <a href='../customs/new-control?redirect=true' target='_blank' class="btn btn-sm btn-primary" id='fn4h9nf' style='width: 15%;display:flex;justify-content:center;align-items:center;'>+ Create New</a>
                                        <buttton id='f93nfo0_11' class="btn btn-sm btn-primary" type='button' data-toggle="tooltip" title="Refresh Customs List" data-placement="left" style='margin-left:5px;display:flex;justify-content:center;align-items:center;font-size:20px;padding:0 10px;'><i class='fas fa-spinner'></i></buttton>
                                        <button type="button" class="btn btn-sm btn-primary" id="btn-append-saved-control" style='margin-left:5px;'>+ Add</button>
                                    </div>
                                    
                                    <div id='add-saved-control' style='margin-top:5px;'>
                                        <?php 
                                            unset($controls_more[0]);
                                            foreach($controls_more as $_control){
                                        ?>
                                        <div style="display:flex;justify-content:center;align-items:center;gap:5px;margin-top:5px;"> 
                                            <?php echo listCompanyControlSelected_New($company_id, $_control, $con); ?>
                                            <buttton class="btn btn-sm btn-primary remove_button_t rmv_btn" style='margin-left:5px;display:flex;justify-content:center;align-items:center;font-size:20px;padding:12px 10px;' type="button"><i class="fas fa-minus"></i></buttton>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <?php }else{ ?>
                                <div class="form-group" id='saved_type'>
                                    <label class="help-label">
                                        Saved Custom Controls
                                    </label>
                                    <div class="add-customs">
                                        <div style='width:100%;margin-right:5px;' id='fh4nfve_11'>
                                            <select name="saved-control[]" class="form-control" required>
                                                <?php echo listCompanyControl($company_id, $con); ?>
                                            </select>
                                        </div>
                                        <a href='../customs/new-control?redirect=true' target='_blank' class="btn btn-sm btn-primary" id='fn4h9nf' style='width: 15%;display:flex;justify-content:center;align-items:center;'>+ Create New</a>
                                        <buttton id='f93nfo0_11' class="btn btn-sm btn-primary" type='button' data-toggle="tooltip" title="Refresh Customs List" data-placement="left" style='margin-left:5px;display:flex;justify-content:center;align-items:center;font-size:20px;padding:0 10px;'><i class='fas fa-spinner'></i></buttton>
                                        <button type="button" class="btn btn-sm btn-primary" id="btn-append-saved-control" style='margin-left:5px;'>+ Add</button>
                                    </div>
                                    
                                    <div id='add-saved-control' style='margin-top:5px;'></div>
                                </div>
                                <?php } ?>
                                
                                <?php if($info['control_type'] == 'custom'){ ?>
                                <div class="form-group" id='custom_type'>
                                    <label class="help-label">
                                        Assessment Specific Controls
                                    </label>
                                    <div class="add-customs">
                                        <?php 
                                            $controls = unserialize($info['control']);
                                            $controls_more = $controls;
                                            $c_count = 0;
                                            foreach($controls as $control){
                                                $c_count++;
                                        ?>
                                        <input type="text" class="form-control" placeholder="Enter custom control description..." value='<?php echo $control; ?>' name='custom-control[]'>
                                        <button type="button" class="btn btn-sm btn-primary" id="btn-append-custom-control">+ Add</button>
                                        <?php break; } ?>
                                    </div>
                                    <div id='add-customs-control'>
                                        <?php 
                                            unset($controls_more[0]);
                                            foreach($controls_more as $_control){
                                        ?>
                                        <div style="display:flex;justify-content:center;align-items:center;gap:5px;margin-top:5px;"> 
                                            <input type='text' class='form-control' placeholder='Enter custom control description...' value='<?php echo $control; ?>' style='margin-top:5px;' name='custom-control[]'>
                                            <buttton class="btn btn-sm btn-primary remove_button" style='margin-left:5px;display:flex;justify-content:center;align-items:center;font-size:20px;padding:12px 10px;' type="button"><i class="fas fa-minus"></i></buttton>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <?php }else{ ?>
                                <div class="form-group" id='custom_type'>
                                    <label class="help-label">
                                        Assessment Specific Controls
                                    </label>
                                    <div class="add-customs">
                                        <input type="text" class="form-control" placeholder="Enter custom control description..." name='custom-control[]'>
                                        <button type="button" class="btn btn-sm btn-primary" id="btn-append-custom-control">+ Add</button>
                                    </div>
                                    <div id='add-customs-control'></div>
                                </div>
                                <?php } ?>
                                
                                <div class="form-group" id='na_type_c'> </div>
                            </div>
                            
                            <div class="form-group" id='no-effect'>
                                <label>Control Effectiveness</label>
                                <textarea name="effectiveness" rows="4" class="form-control" placeholder="Enter control effectiveness..." required><?php echo $info['control_effectiveness']; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Action Type</label>
                                <?php echo listActions($info['control_action'], $con); ?>
    
                            </div>
                        </div>
                        
                        <div class='div_divider'></div>
                        
                        <!-- Treatment -->
                        <div class="card-header hh">
                            <h3 class="d-inline">Treatment Plans</h3>
                            <i class="fas fa-question btn btn-primary btn-icon btn-small btn-help header-a" id="swal-custom-1"></i>
                        </div>
                        <div class="card-body">
                            <div class="form-group" style='display:flex;gap:50px;'>
                                <div>
                                    <input type='radio' id='assessment-specific-t' value='custom' name='treatment-type' <?php if($info['treatment_type'] == 'custom'){ echo 'checked'; } ?> />
                                    <label for='assessment-specific-t'>Assessment Specific Treatments</label>
                                </div>
                                <div>
                                    <input type='radio' id='saved-t' value='saved' name='treatment-type' <?php if($info['treatment_type'] == 'saved'){ echo 'checked'; } ?> />
                                    <label for='saved-t'>Saved Custom Treatments</label>
                                </div>
                                <div>
                                    <input type='radio' id='na-t' value='na' name='treatment-type' <?php if($info['treatment_type'] == 'na'){ echo 'checked'; } ?> />
                                    <label for='na-t'>N/A</label>
                                </div>
                            </div>
                            
                            <style>
                                <?php if($info['treatment_type'] == 'custom'){ ?>
                                #saved_type_t{
                                    display:none;
                                }
                                #no_type_t{
                                    display:none;
                                }
                                <?php }else if($info['treatment_type'] == 'saved'){ ?>
                                #custom_type_t{
                                    display:none;
                                }
                                #no_type_t{
                                    display:none;
                                }
                                <?php }else if($info['treatment_type'] == 'na'){ ?>
                                #custom_type_t{
                                    display:none;
                                }
                                #saved_type_t{
                                    display:none;
                                }
                                
                                <?php } ?>
                            </style>
                            
                                <?php if($info['treatment_type'] == 'saved'){ ?>
                                <div class="form-group" id='saved_type_t'>
                                    <label class="help-label">
                                        Saved Custom Treatments
                                    </label>
                                    <div class="add-customs">
                                        <?php 
                                            $treatments = unserialize($info['treatment']);
                                            $treatment_more = $treatments;
                                            $t_count = 0;
                                            foreach($treatments as $treatment){
                                                $t_count++;
                                                echo "<div style='width:100%;margin-right:5px;' id='fh4nfvf'>";
                                                echo listCompanyTreatmentSelected_New($company_id, $treatment, $con);
                                                echo "</div>";
                                                break;
                                            }
                                        ?>
                                        
                                        <a href='../customs/new-treatment?redirect=true' target='_blank' class="btn btn-sm btn-primary" style='width: 15%;display:flex;justify-content:center;align-items:center;'>+ Create New</a>
                                        <buttton id='f93nfo1' class="btn btn-sm btn-primary" type='button' data-toggle="tooltip" title="Refresh Customs List" data-placement="left" style='margin-left:5px;display:flex;justify-content:center;align-items:center;font-size:20px;padding:0 10px;'><i class='fas fa-spinner'></i></buttton>
                                        <button type="button" class="btn btn-sm btn-primary" id="btn-append-saved-treatment" style='margin-left:5px;'>+ Add</button>
                                    </div>
                                    
                                    <div id='add-saved-treatment' style='margin-top:5px;'>
                                        <?php 
                                            unset($treatment_more[0]);
                                            foreach($treatment_more as $_treatment){
                                        ?>
                                        <div style="display:flex;justify-content:center;align-items:center;gap:5px;margin-top:5px;"> 
                                            <?php echo listCompanyTreatmentSelected_New($company_id, $_treatment, $con); ?>
                                            <buttton class="btn btn-sm btn-primary remove_button_t rmv_btn" style='margin-left:5px;display:flex;justify-content:center;align-items:center;font-size:20px;padding:12px 10px;' type="button"><i class="fas fa-minus"></i></buttton>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <?php }else{ ?>
                                <div class="form-group" id='saved_type_t'>
                                    <label class="help-label">
                                        Saved Custom Treatments
                                    </label>
                                    <div class="add-customs">
                                        <div style='width:100%;margin-right:5px;' id='fh4nfvf'>
                                        <select name="saved-treatment[]" class="form-control" required style='margin-right:5px;'>
                                            <?php echo listCompanyTreatment($company_id, $con); ?>
                                        </select>
                                        </div>
                                        <a href='../customs/new-treatment?redirect=true' target='_blank' class="btn btn-sm btn-primary" style='width: 15%;display:flex;justify-content:center;align-items:center;'>+ Create New</a>
                                        <buttton id='f93nfo1' class="btn btn-sm btn-primary" type='button' data-toggle="tooltip" title="Refresh Customs List" data-placement="left" style='margin-left:5px;display:flex;justify-content:center;align-items:center;font-size:20px;padding:0 10px;'><i class='fas fa-spinner'></i></buttton>
                                        <button type="button" class="btn btn-sm btn-primary" id="btn-append-saved-treatment" style='margin-left:5px;'>+ Add</button>
                                    </div>
                                    
                                    <div id='add-saved-treatment' style='margin-top:5px;'></div>
                                </div>
                                <?php } ?>
                                
                                <?php if($info['treatment_type'] == 'custom'){ ?>
                                <div class="form-group" id='custom_type_t'>
                                    <label class="help-label">
                                        Assessment Specific Treatments
                                    </label>
                                    <div class="add-customs">
                                        <?php 
                                            $treatments = unserialize($info['treatment']);
                                            $treatment_more = $treatments;
                                            $t_count = 0;
                                            foreach($treatments as $treatment){
                                                $t_count++;
                                                echo "<div class='add-customs' style='width:100%;'>";
                                                echo '<input type="text" class="form-control" value='.ucfirst($treatment).' placeholder="Enter custom control description..." name="custom-treatment[]">';
                                                echo '<button type="button" class="btn btn-sm btn-primary" id="btn-append-custom-treatment">+ Add</button>';
                                                echo "</div>";
                                                break;
                                            }
                                        ?>
                                    </div>
                                    <div id='add-customs-treatment'>
                                        <?php 
                                            unset($treatment_more[0]);
                                            foreach($treatment_more as $_treatment){
                                        ?>
                                        <div style="display:flex;justify-content:center;align-items:center;gap:5px;margin-top:5px;"> 
                                            <input type="text" class="form-control" value='<?php echo ucfirst($_treatment); ?>' placeholder="Enter Custom Treatment Description..." style="margin-top:5px;" name="custom-treatment[]"  required/>
                                            <buttton class="btn btn-sm btn-primary remove_button_t rmv_btn" style='margin-left:5px;display:flex;justify-content:center;align-items:center;font-size:20px;padding:12px 10px;' type="button"><i class="fas fa-minus"></i></buttton>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <?php }else{ ?>
                                <div class="form-group" id='custom_type_t'>
                                    <label class="help-label">
                                        Assessment Specific Treatments
                                    </label>
                                    <div class="add-customs">
                                        <input type="text" class="form-control" placeholder="Enter custom control description..." name='custom-treatment[]'>
                                        <button type="button" class="btn btn-sm btn-primary" id="btn-append-custom-treatment">+ Add</button>
                                    </div>
                                    <div id='add-customs-treatment'></div>
                                </div>
                                <?php } ?>
                                
                                
                                <div class="form-group" id='na_type_t'> </div>
                        
                            
                            
                            <div class='row custom-row'>
                                <div class="form-group col-lg-8 col-12">
                                    <label>Action Owner</label>
                                    <input name="owner" id="owner" type="text" maxlength="100" class="form-control" placeholder="Enter action owner..." required value="<?php echo ucwords($info['owner']);  ?>">
                                </div>
                                <div class="form-group col-lg-4 col-12">
                                    <label>Due Date</label>
                                    <input name="date" id="date" type="text" maxlength="100" class="form-control datepicker" placeholder="Select date..." required style="cursor:pointer;" value="<?php echo date("Y-m-d", strtotime($info['due_date'])); ?>">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Submit -->
                        <div class="card-body">
                            <div class="form-group">
                                <button type="submit" class="btn btn-md btn-primary" name="update-risk">Update Risk</button>
                                <button type="button" class="btn btn-md btn-warning" id="btn_cancel">Cancel</button>
                                <input type="hidden" name="assessment" value="<?php echo $info['risk_id']; ?>">
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
            <form id="getHazard" class="ajax-form">
                <input type="hidden" name="category" id="get_hazard">
            </form>
            
            <form id="getCustomHazard" class="ajax-form">
                <input type="hidden" name="risk" id="get_customhazard">
            </form>
            
            <form id="getDescription" class="ajax-form">
                <input type="hidden" name="category" id="get_desc">
            </form>
            <form id="getRating" class="ajax-form">
                <input type="hidden" name="consequence" id="get_risk_consequence">
                <input type="hidden" name="likelihood" id="get_risk_likelihood">
            </form>
            <form id="getRating_r" class="ajax-form">
                <input type="hidden" name="consequence" id="get_risk_consequence_r">
                <input type="hidden" name="likelihood" id="get_risk_likelihood_r">
            </form>
            <form id="getControls" class="ajax-form">
                <input type="hidden" name="risk" id="risk_val">
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
    
     $("#f93nfo0_1111").click(function (e) {
          $("#fh4nfve_1111").load(" #fh4nfve_1111 > *");
        });
        
        $("#_riskdivloader").click(function (e) {
          $("#_riskdiv").load(" #_riskdiv > *");
        });

        
        let fieldHTMLTreatent = 'empty';
        let userRisks = <?php $query="SELECT * FROM as_customrisks WHERE c_id = '$company_id'"; $result=$con->query($query); if ($result->num_rows > 0){ ?> [ <?php while($row=$result->fetch_assoc()){ ?> "<?php echo $row['risk_id']; ?>", <?php } echo ']'; }else{ echo "'empty';"; } ?> 
        
        <?php if($info['risk_type'] == 'custom') { ?> $('#btn-append-rec-control').hide(); <?php } ?>
        
        <?php if($info['control_type'] !== 'recommended'){ ?>
        
        // $("#fetchControls").html('<span style="font-weight:400;">Select Risk Above To Get Recommended Control!!</span>');
        fieldHTMLTreatent = '<div style="display:flex;justify-content:center;align-items:center;gap:5px;margin-top:5px;"><?php echo listControl_New($info['risk'], $con); ?> <buttton class="btn btn-sm btn-primary remove_button_t" type="button" style="margin-left:5px;display:flex;justify-content:center;align-items:center;font-size:20px;padding:12px 10px;"><i class="fas fa-minus"></i></buttton></div>';
        <?php } ?>
        
        $("#getCustomHazard").submit(function (event) {
          event.preventDefault();
        
          var formValues = $(this).serialize();
          $.post("../ajax/assessment", {
            getCustomData: formValues,
          }).done(function (data) {
            const jsonObject = JSON.parse(data);
            $(".hazard_empty").hide();
            $(".hazard").show();
            $('#hazard_div').html('<input type="text" name="hazard" class="form-control" value="'+jsonObject.sub+'" />');
            $("#risk-description").val(jsonObject.desc);
            $("#owner").val(jsonObject.owner);
            setTimeout(function () {
              $("#getCustomHazard input").val("");
            }, 0);
          });
        });
        
        $("#getRating_r").submit(function (event) {
  // alert('first first stop!');
  event.preventDefault();

  var formValues = $(this).serialize();
  $("#rating_residual").html('Calculating Rating...');
  $.post("../ajax/assessment", {
    getRating_r: formValues,
  }).done(function (data) {
    // alert(data);
    $("#rating_residual").html(data);
    // $(".risk-rating-residual").hide();
    setTimeout(function () {
      $("#getRating_r input").val("");
    }, 0);

    // alert('second stop!');
  });
});

$("#consequence_residual").change(function (e) {
  var riskConsequence = $(this).val();
  var likelihoodValue = $("#likelihood_residual").val();
  if (riskConsequence !== "0" && likelihoodValue !== "0") {
    likelihoodValue = likelihoodValue * 1;
    riskConsequence = riskConsequence * 1;

    $("#get_risk_consequence_r").val(riskConsequence);
    $("#get_risk_likelihood_r").val(likelihoodValue);

    $("#getRating_r").submit();
  } else {
  }
});

$("#likelihood_residual").change(function (e) {
  var riskLikelihood = $(this).val();
  var consequenceValue = $("#consequence_residual").val();

  if (riskLikelihood !== "0" && consequenceValue !== "0") {
    consequenceValue = consequenceValue * 1;
    riskLikelihood = riskLikelihood * 1;

    $("#get_risk_consequence_r").val(consequenceValue);
    $("#get_risk_likelihood_r").val(riskLikelihood);

    $("#getRating_r").submit();
  } else {
  }
});
        
        $("#risk").change(function (e) {
            var riskValue = $("#risk").val();
            
            if (riskValue == "0") {
                  $(".hazard_empty").show();
                  $("#hazard_div").html('');
                  $("#risk-description").val('');
                  $('#owner').val('');
                  
                  $('#btn-append-rec-control').hide();
                  $("#fetchControls").html('Select Risk Above To Get Recommended Control!!');
                  $('#selected_risk_type').val('');
              } else {
                
                if(userRisks !== 'empty' && userRisks.includes(riskValue)){
                    $('#selected_risk_type').val('custom');
                    $('#get_customhazard').val(riskValue);
                    
                    $('#getCustomHazard').submit();
                    
                    $('#btn-append-rec-control').hide();
                    $("#fetchControls").html('No Recommended Controls For Custom Risk!!');
                }else{
                    $('#selected_risk_type').val('site');
                    $('#owner').val('');
                    
                    $("#get_hazard").val();
                    $("#get_hazard").val(riskValue);
                    $("#get_desc").val();
                    $("#get_desc").val(riskValue);
                    
                    $("#risk_val").val();
                    $("#risk_val").val(riskValue);
                                        
                    $("#getControls").submit();
                    
                    $("#getHazard").submit();
                    $("#getDescription").submit();
                    
                    $('#btn-append-rec-control').show();
                }
              }
            
        });
        
        $("#getControls").submit(function (event) {
          event.preventDefault();
        
          var formValues = $(this).serialize();
          $("#fetchControls").val('Fetching Controls...');
          $.post("../ajax/assessment", {
            getControls: formValues,
          }).done(function (data) {
            fieldHTMLTreatent = '<div style="display:flex;justify-content:center;align-items:center;gap:5px;margin-top:5px;"> '+data+' <buttton class="btn btn-sm btn-primary remove_button_t" type="button" style="margin-left:5px;display:flex;justify-content:center;align-items:center;font-size:20px;padding:12px 10px;"><i class="fas fa-minus"></i></buttton></div>';
            $("#fetchControls").html(data);
            $("#add-rec-control").html('');
            $('#btn-append-rec-control').show();
            setTimeout(function () {
              $("#getControls input").val("");
            }, 0);
          });
        });

        $("#f93nfo0_11").click(function (e) {
          $("#fh4nfve_11").load(" #fh4nfve_11 > *");
        });
    
        $("input[type='radio'][name='control-type']") // select the radio by its id
        .change(function(){ // bind a function to the change event
            if( $(this).is(":checked") ){ // check if the radio is checked
                var val = $(this).val(); // retrieve the value
                // alert(val);
                if(val == 'recommended_type'){
                    $('#recommended').show();
                    $('#custom_type').hide();
                    $('#saved_type').hide();
                    $('#na_type_c').hide();
                    $('#no-effect').show();
                    
                    var riskValue = $("#risk").val();
                    var riskType = $('#selected_risk_type').val();
                          if(riskType == "custom"){
                              $('#btn-append-rec-control').hide();
                              $("#fetchControls").html('No Recommended Controls For Custom Risk!!');
                          }else{
                            
                              if (riskValue == "0") {
                                 $("#fetchControls").html('Select Risk Above To Get Recommended Control!!');
                              } else {
                                  $('#btn-append-rec-control').show();
                                  $("#risk_val").val();
                                    $("#risk_val").val(riskValue);
                                    
                                    $("#getControls").submit();
                                  
                              }
                          }
                    
                }else if(val == 'saved'){
                    $('#recommended_type').hide();
                    $('#custom_type').hide();
                    $('#saved_type').show();
                    $('#na_type_c').hide();
                    $('#no-effect').show();
                    
                }else if(val == 'custom'){
                    $('#recommended_type').hide();
                    $('#custom_type').show();
                    $('#saved_type').hide();
                    $('#na_type_c').hide();
                    $('#no-effect').show();
                }else if(val == 'na'){
                    $('#recommended_type').hide();
                    $('#custom_type').hide();
                    $('#saved_type').hide();
                    $('#na_type_c').show();
                     $('#no-effect').hide();
                }else{
                    $('#recommended_type').show();
                    $('#custom_type').hide();
                    $('#saved_type').hide();
                    $('#na_type_c').hide();
                    $('#no-effect').show();
                }
                // alert('works');
            }
        });
        
        $("input[type='radio'][name='treatment-type']") // select the radio by its id
        .change(function(){ // bind a function to the change event
            if( $(this).is(":checked") ){ // check if the radio is checked
                var val = $(this).val(); // retrieve the value
                // alert(val);
                if(val == 'saved'){
                    $('#custom_type_t').hide();
                    $('#saved_type_t').show();
                    $('#na_type_t').hide();
                }else if(val == 'custom'){
                    $('#custom_type_t').show();
                    $('#saved_type_t').hide();
                    $('#na_type_t').hide();
                }else if(val == 'na'){
                    $('#custom_type_t').hide();
                    $('#saved_type_t').hide();
                    $('#na_type_t').show();
                }else{
                    $('#custom_type_t').hide();
                    $('#saved_type_t').show();
                    $('#na_type_t').hide();
                }
            }
        });
        
        
            var maxFieldTreatmnt = 10; //Input fields increment limitation
            var addButtonTreament = $('#btn-append-rec-control'); //Add button selector
            var wrapperTreatent = $('#add-rec-control'); //Input field wrapperTreatent
            // var fieldHTMLTreatent = '<div style="display:flex;justify-content:center;align-items:center;gap:5px;margin-top:5px;"><select name="existing_ct[]" class="form-control" required> <option value="null" selected>None Selected</option> <?php #echo listControl($company_id, $con); ?> </select> <buttton class="btn btn-sm btn-primary remove_button_t" type="button" style="margin-left:5px;display:flex;justify-content:center;align-items:center;font-size:20px;padding:12px 10px;"><i class="fas fa-minus"></i></buttton></div>';
            var x_Treatmnt = 1; //Initial field counter is 1
            
            // Once add button is clicked
            $(addButtonTreament).click(function(){
                //Check maximum number of input fields
                if(x_Treatmnt < maxFieldTreatmnt){ 
                    x_Treatmnt++; //Increase field counter
                    $(wrapperTreatent).append(fieldHTMLTreatent); //Add field html
                }else{
                    alert('A maximum of '+maxFieldTreatmnt+' fields are allowed to be added. ');
                }
            });
            
            // Once remove button is clicked
            $(wrapperTreatent).on('click', '.remove_button_t', function(e){
                e.preventDefault();
                $(this).parent('div').remove(); //Remove field html
                x_Treatmnt--; //Decrease field counter
            });
            
            //saved
            var maxFieldTreatmen = 10; //Input fields increment limitation
            var addButtonTreatmen = $('#btn-append-saved-control'); //Add button selector
            var wrapperTreatmen = $('#add-saved-control'); //Input field wrapperTreatment
            var fieldHTMLTreatmen = '<div style="display:flex;justify-content:center;align-items:center;gap:5px;margin-top:5px;"> <select name="saved-control[]" class="form-control" required> <option value="null" selected>None Selected</option> <?php echo listCompanyControl($company_id, $con); ?></select> <buttton class="btn btn-sm btn-primary remove_button_t" type="button" style="margin-left:5px;display:flex;justify-content:center;align-items:center;font-size:20px;padding:12px 10px;"><i class="fas fa-minus"></i></buttton></div>';
            var x_Treatmen = 1; //Initial field counter is 1
            
            // Once add button is clicked
            $(addButtonTreatmen).click(function(){
                //Check maximum number of input fields
                if(x_Treatmen < maxFieldTreatmen){ 
                    x_Treatmen++; //Increase field counter
                    $(wrapperTreatmen).append(fieldHTMLTreatmen); //Add field html
                }else{
                    alert('A maximum of '+maxFieldTreatmen+' fields are allowed to be added. ');
                }
            });
            
            // Once remove button is clicked
            $(wrapperTreatmen).on('click', '.remove_button_t', function(e){
                e.preventDefault();
                $(this).parent('div').remove(); //Remove field html
                x_Treatmen--; //Decrease field counter
            });
            
            
            // saved treatments
            var maxFieldTeatmen = 10; //Input fields increment limitation
            var adButtonTreatmen = $('#btn-append-saved-treatment'); //Add button selector
            var wraperTreatmen = $('#add-saved-treatment'); //Input field wrapperTreatment
            var fieldHTLTreatmen = '<div style="display:flex;justify-content:center;align-items:center;gap:5px;margin-top:5px;"> <select name="saved-treatment[]" class="form-control" required> <?php echo listCompanyTreatment($company_id, $con); ?></select> <buttton class="btn btn-sm btn-primary remove_button_t" type="button" style="margin-left:5px;display:flex;justify-content:center;align-items:center;font-size:20px;padding:12px 10px;"><i class="fas fa-minus"></i></buttton></div>';
            var x_Treatmens = 1; //Initial field counter is 1
            
            // Once add button is clicked
            $(adButtonTreatmen).click(function(){
                //Check maximum number of input fields
                if(x_Treatmens < maxFieldTeatmen){ 
                    x_Treatmens++; //Increase field counter
                    $(wraperTreatmen).append(fieldHTLTreatmen); //Add field html
                }else{
                    alert('A maximum of '+maxFieldTeatmen+' fields are allowed to be added. ');
                }
            });
            
            // Once remove button is clicked
            $(wraperTreatmen).on('click', '.remove_button_t', function(e){
                e.preventDefault();
                $(this).parent('div').remove(); //Remove field html
                x_Treatmens--; //Decrease field counter
            });
            
            
            // causes
            var _maxFieldTeatmen = 20; //Input fields increment limitation
            var _adButtonTreatmen = $('#btn-append-causes'); //Add button selector
            var _wraperTreatmen = $('#add-causes'); //Input field wrapperTreatment
            var _fieldHTLTreatmen = '<div style="display:flex;justify-content:center;align-items:center;gap:5px;margin-top:5px;"> <input type="text" class="form-control" placeholder="Enter cause..." name="causes[]"> <button type="button" class="btn btn-sm btn-primary remove_button_causes" style="margin-left:5px;display:flex;justify-content:center;align-items:center;font-size:20px;padding:12px 10px;"><i class="fas fa-minus"></i></buttton></div>';
            var _x_Treatmens = 1; //Initial field counter is 1
            
            
            
            // Once add button is clicked
            $(_adButtonTreatmen).click(function(){
                //Check maximum number of input fields
                if(_x_Treatmens < _maxFieldTeatmen){ 
                    _x_Treatmens++; //Increase field counter
                    $(_wraperTreatmen).append(_fieldHTLTreatmen); //Add field html
                }else{
                    alert('A maximum of '+_maxFieldTeatmen+' fields are allowed to be added. ');
                }
            });
            
            // Once remove button is clicked
            $(_wraperTreatmen).on('click', '.remove_button_causes', function(e){
                e.preventDefault();
                $(this).parent('div').remove(); //Remove field html
                _x_Treatmens--; //Decrease field counter
            });
            
            // incidents
            var _maxFieldTeatmen = 10; //Input fields increment limitation
            var _adButtonTreatmen = $('#btn-append-incident'); //Add button selector
            var _wraperTreatmen = $('#add-incident'); //Input field wrapperTreatment
            var _fieldHTLTreatmen = '<div style="display:flex;justify-content:center;align-items:center;gap:5px;margin-top:5px;"> <select name="incidents[]" class="form-control" required> <?php echo __listCompanyIncidents($company_id, $con); ?> </select> <buttton class="btn btn-sm btn-primary remove_button_t" type="button" style="margin-left:5px;display:flex;justify-content:center;align-items:center;font-size:20px;padding:12px 10px;"><i class="fas fa-minus"></i></buttton></div>';
            var _x_Treatmens = 1; //Initial field counter is 1
            
            // Once add button is clicked
            $(_adButtonTreatmen).click(function(){
                //Check maximum number of input fields
                if(_x_Treatmens < _maxFieldTeatmen){ 
                    _x_Treatmens++; //Increase field counter
                    $(_wraperTreatmen).append(_fieldHTLTreatmen); //Add field html
                }else{
                    alert('A maximum of '+_maxFieldTeatmen+' fields are allowed to be added. ');
                }
            });
            
            // Once remove button is clicked
            $(_wraperTreatmen).on('click', '.remove_button_t', function(e){
                e.preventDefault();
                $(this).parent('div').remove(); //Remove field html
                _x_Treatmens--; //Decrease field counter
            });
            
    </script>
    <style>
        select.form-control, 
        option{
            text-overflow: pre !important;
        }
        textarea{
            min-height: 120px !important;
        }
        .c_type{
            display:flex;
            gap:10px;
        }
        button.btn.btn-sm.btn-primary.rmv_btn{
            /*margin-left:5px;display:flex;justify-content:center;align-items:center;font-size:20px;padding:12px 10px;*/
        }
        .c_type button{
            width:10% !important;
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