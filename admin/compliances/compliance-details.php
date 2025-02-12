<?php
    session_start();
    $file_dir = '../../';
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'auth/sign-in?r=/compliances/all');
        exit();
    }
    $message = [];
    include $file_dir.'layout/db.php';
    include $file_dir.'layout/admin__config.php';
    include '../ajax/compliances.php';
    
    function get__Frequency($freq){

		if ($freq == 7) {
			return "As Required";
		} else if ($freq == 1 || strtolower($freq) == 'daily') {
			return "Daily Controls";
		} else if ($freq == 2 || strtolower($freq) == 'weekly') {
			return "Weekly Controls";
		} else if ($freq == 3) {
			return "Fort-Nightly Controls";
		} else if ($freq == 4 || strtolower($freq) == 'monthly') {
			return "Monthly Controls";
		} else if ($freq == 5) {
			return "Semi-Annually Controls";
		} else if ($freq == 6 || strtolower($freq) == 'annually') {
			return "Annually Controls";
		} else {
			return "None Specified";
		}
	}
	function check_null($data, $response, $param){
	    if($data == '' || $data == null || $data == ' ' || $data == $param){
	        $data = $response;
	    }else{
	        $data = $data;
	    }
	    
	    return $data;
	}
    
    if (isset($_GET['id']) && isset($_GET['id']) !== "") {
        $toDisplay = true;   
        $id = sanitizePlus($_GET['id']);
        $CheckIfProcedureExist = "SELECT * FROM as_compliancestandard WHERE compli_id = '$id' AND c_id = '$company_id'";
        $ProcedureExist = $con->query($CheckIfProcedureExist);
        if ($ProcedureExist->num_rows > 0) {	
            $compli_exist = true;
			$info = $ProcedureExist->fetch_assoc();
			
// 			$compliance_type = $info['type'];
			
// 			$recommended_control = $info['existing_ct'];
//             $saved_control = $info['saved_control'];	
//             $saved_treatment = $info['saved_treatment'];	
//             $custom_control = $info['custom_control'];	
//             $custom_treatment = $info['custom_treatment'];
            
            if($info['type'] == 'imported' && $info['saved_control'] == null || $info['type'] == 'imported' && $info['saved_control'] == ''){
                $saved_control = '1';
            }
            
            if($info['type'] == 'imported' && $info['saved_treatment'] == null || $info['type'] == 'imported' && $info['saved_treatment'] == ''){
                $saved_treatment = '1';
            }
            
            if($info['type'] == 'imported' && $info['custom_control'] == null || $info['type'] == 'imported' && $info['custom_control'] == ''){
                $custom_control = 'null';
            }
            
            if($info['type'] == 'imported' && $info['custom_treatment'] == null || $info['type'] == 'imported' && $info['custom_treatment'] == ''){
                $custom_treatment = 'null';
            }
            
            // if($custom_control == null || $custom_control == 'null'){
            //     $un_custom_control = 'null';
            // }else{
            //     $un_custom_control = unserialize($custom_control);
            // }
            
            // if($custom_treatment == null || $custom_treatment == 'null'){
            //     $un_custom_treatment = 'null';
            // }else{
            //     $un_custom_treatment = unserialize($custom_treatment);
            // }
            
            // $hasCustomControl = is_array($un_custom_control);
            // $hasCustomTreatment = is_array($un_custom_treatment);
            
            $evidence = $info['com_documentation'];
            
            if($evidence == 'null'){
                $uploadedEvidence = 'None Uploaded';
            }else{
                $uploadedEvidence = '<a href="evidence/'.$evidence.'" target="_blank" class="bb">View File</a>';
            }

            // if ($hasCustomControl == true) {
            //     #if value in db is array
            //     $customControlArrayStatus = 'true';
            //     if ($custom_control == 'a:1:{i:0;s:0:"";}') {
            //         #empty array
            //         $customControlValuesStatus = 'empty';
            //         #show a single empty textbox
            //     } else if ($custom_control == null){
            //         $customControlValuesStatus = 'empty';
            //         #show all details
            //     } else {
            //         $customControlValuesStatus = 'not-empty';
            //         #show all details
            //     }
            // } else {
            //     $customControlArrayStatus = 'false';
            // }

            // if ($hasCustomTreatment == true) {
            //     #if value in db is array
            //     $customTreatmentArrayStatus = 'true';
            //     if ($custom_treatment == 'a:1:{i:0;s:0:"";}') {
            //         #empty array
            //         $customTreatmentValuesStatus = 'empty';
            //         #show a single empty textbox
            //     } else if ($custom_treatment == null) {
            //         $customTreatmentValuesStatus = 'empty';
            //         #show all details
            //     } else {
            //         $customTreatmentValuesStatus = 'not-empty';
            //         #show all details
            //     }   
            // } else {
            //     $customTreatmentArrayStatus = 'false';
            // }
            
        }else{
            $compli_exist = false;
        }
    } else {
        $toDisplay = false;
    }
    
    #array_push($message, 'Error 502: Error!!');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Compliance Details | <?php echo $siteEndTitle; ?></title>
  <?php require $file_dir.'layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/footer.custom.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/admin.custom.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/bundles/prism/prism.css">
</head>

<body>
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <?php require $file_dir.'layout/header.php' ?>
        <?php require $file_dir.'layout/sidebar_admin.php' ?>
        <!-- Main Content -->
        <div class="main-content">
            <section class="section">
            <div class="section-body">
                <?php if($toDisplay == true){ ?>
                <?php if ($compli_exist == true) { ?>
                <div class="card">
                    <form method="post" enctype="multipart/form-data">
                        <div class="card-body">
                            <?php require $file_dir.'layout/alert.php' ?>

                            <div class="card-header">
                                <h3 class="subtitle">Compliance Information</h3>
                                <a class="btn btn-primary btn-icon icon-left header-a" href="all"><i class="fas fa-arrow-left"></i> Back <span class='hide-sm' style='font-size: 12px;'>To Compliances</span></a>
                            </div>
                            <div class="card-body">
                                <?php if($info['module'] !== 'null'){ ?>
                                <div class="form-group">
									<label>Compliance: </label>
									<div class="r_desc"><?php echo getSelectedCompliance($info['module'], $con); ?></div>
								</div>
								<?php } ?>
								
                                <div class="form-group">
									<label>Compliance Task or Obligation: </label>
									<div class="r_desc"><?php echo check_null($info['com_compliancestandard'], 'None Specified', 'Error!!'); ?></div>
								</div>
								<div class="form-group">
									<label>Reference / Legislation: </label>
									<div class="r_desc"><?php echo check_null(nl2br($info['com_legislation']), 'None Specified', 'Error!!'); ?></div>
								</div>
                                
                                <div class="form-group">
									<label>Compliance Requirements:: </label>
									<div class="r_desc"><?php echo check_null(nl2br($info['com_training']), 'None Specified', 'Error!!'); ?></div>
								</div>
								<div class='row custom-row'>
								<div class="form-group col-12 col-lg-3">
									<label>Compliance Officer: </label>
									<div class="r_desc"><?php echo check_null($info['com_officer'], 'None Specified', 'Error!!'); ?></div>
								</div>
                                <div class="form-group col-12 col-lg-3">
									<label>Compliance Status: </label>
									<div class="r_desc">
									    <?php 
									        if($info['co_status'] == "Un-Assessed" || strtolower($info['co_status']) == "un-assessed" || strtolower($info['co_status']) == "unassessed"){
    										    $effect = 'unaccessed';
    										}else{
    										    $effect = $info['co_status'];
    										}
    										
										    echo getEffectivenessTitle($effect);
									    ?>
									 </div>
								</div>
								
								<div class="form-group col-12 col-lg-3">
									<label>Compliance Frequency: </label>
									<div class="r_desc">
									    <?php echo getFrequencyTitle($info['frequency']); ?>
									</div>
								</div>
								
                                <div class="form-group col-12 col-lg-3">
									<label>Documentation & Evidence: </label>
									<div class="r_desc"><?php echo $uploadedEvidence; ?></div>
								</div>
								</div>
							</div>
							
							
							<!-- Incident -->
                            <div class='div_divider'></div>
    
                            <!-- Incident -->
                            <div class="card-header hh">
                                <h3 class="d-inline">Compliance Incident</h3>
                            </div>
                            <div class="card-body">
                                <?php if($info['incidents'] === null){ ?>
    							<div class="form-group">
                                    <label class="help-label">Incidents</label>
                                    <div class="r_desc">None Specified!</div>
                                </div>
                                <?php }else{ ?>
                                <div class="form-group">
                                    <label class="help-label">
                                        Selected Incidents
                                    </label>
                                    <div class="r_desc">
                                        <ul>
                                        <?php
                                            $treatments = unserialize($info['incidents']);
                                            foreach($treatments as $treatment){
                                        ?>
                                        <li><?php echo ucfirst(__getIncident($company_id, $treatment, $con)); ?></li>
                                        <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                            
                            <div class='div_divider'></div>

                            <!-- Controls -->
                            <div class="card-header hh">
                                <h3 class="d-inline">Control Actions</h3>
                                <i class="fas fa-question btn btn-primary btn-icon btn-small btn-help header-a" id="swal-custom-2"></i>
                            </div>
                            <div class="card-body">
                                <?php if($info['type'] == 'imported' && $info['imported_controls'] != null){ ?>
                                <div class="form-group">
                                    <label class="help-label"> Imported Controls </label>
                                    <div class="r_desc"><?php echo check_null($info['imported_controls'], 'No Control Imported', 'Error!'); ?></div>
                                </div>
                                <?php } ?>
                                <div class="form-group">
                                <label class="help-label">
                                    Selected Controls
                                </label>
                                <div class="r_desc">
                                    <ul>
                                    <?php if($info['control_type'] === 'na'){ ?>
                                    Control Not Assessed!
                                    <?php    }else{
                                        $controls = unserialize($info['existing_ct']);
                                        foreach($controls as $control){
                                    ?>
                                    <li>
                                        <?php 
                                        if($info['control_type'] === 'recommended'){
                                            echo ucfirst(getControlTitle($control, $con)); 
                                        }else if($info['control_type'] === 'saved'){
                                            echo ucfirst(getControlTitle_Saved($control, $con)); 
                                        }else{
                                            echo ucfirst($control); 
                                        }
                                            
                                        ?>
                                    </li>
                                    <?php }} ?>
                                    </ul>
                                </div>
                            </div>
                                
                                <div class="form-group">
									<label>Control Requirements: </label>
									<div class="r_desc"><?php echo check_null($info['com_controls'], 'None Specified', 'Error!'); ?></div>
								</div>
                            </div>
                            
                            
                            <!-- Treatment -->
                            <div class='div_divider'></div>
    
                            <!-- Treatment -->
                            <div class="card-header hh">
                                <h3 class="d-inline">Treatment Plans</h3>
                                <i class="fas fa-question btn btn-primary btn-icon btn-small btn-help header-a" id="swal-custom-1"></i>
                            </div>
                            <div class="card-body">
                                <?php if($info['type'] == 'imported' && $info['imported_treatments'] != null){ ?>
    							<div class="form-group">
                                    <label class="help-label"> Imported Treatments: </label>
                                    <div class="r_desc"><?php echo check_null($info['imported_treatments'], 'No Treatment Imported', 'Error!'); ?></div>
                                </div>
                                <?php }else{ ?>
                                <div class="form-group">
                                    <label class="help-label">
                                        Selected Treatments
                                    </label>
                                    <div class="r_desc">
                                        <ul>
                                        <?php if($info['treatment_type'] === 'na'){ ?>
                                        Treatment Not Assessed!
                                        <?php    }else{ 
                                            $treatments = unserialize($info['existing_tr']);
                                            foreach($treatments as $treatment){
                                        ?>
                                        <li><?php echo ucfirst(getComplianceTreatment($info['treatment_type'], $treatment, $company_id, $con)); ?></li>
                                        <?php }} ?>
                                        </ul>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="card-body">
                            <div class="form-group">
								<a href="edit-compliance?id=<?php echo $id; ?>" class="btn btn-md btn-primary">Edit Compliance</a>
								<button type="button" class="btn btn-md btn-warning" id="btn_cancel">Cancel</button>
							</div>
							</div>
                        </div>
                        
                    </form>
                </div>
                <?php }else{ ?>
                <div class="card">
                    <div class="card-body" style="display:flex;justify-content:center;align-items:center;min-height:500px;">
                        <div>
                            <h3 style="display:flex;justify-content:center;align-items:center;width:100%;">Error 402!!</h3>
                            <div style="display:flex;justify-content:center;align-items:center;width:100%;margin:10px 0px;">Compliance Standard Doesn't Exist!!</div>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <?php }else{ ?>
                <div class="card">
                    <div class="card-body" style="display:flex;justify-content:center;align-items:center;min-height:500px;">
                        <div>
                            <h3 style="display:flex;justify-content:center;align-items:center;width:100%;">Error 402!!</h3>
                            <div style="display:flex;justify-content:center;align-items:center;width:100%;margin:10px 0px;">Missing Parameters!!</div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
            </section>
        </div>
        <?php require $file_dir.'layout/footer.php' ?>
        </footer>
        </div>
    </div>
    <?php require $file_dir.'layout/general_js.php' ?>
    <script src="<?php echo $file_dir; ?>assets/bundles/prism/prism.js"></script>
    <script src="<?php echo $file_dir; ?>assets/bundles/sweetalert/sweetalert.min.js"></script>
    <script src="<?php echo $file_dir; ?>assets/js/page/sweetalert.js"></script>
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
    </style>
</body>
</html>