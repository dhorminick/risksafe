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
    include '../../layout/db.php';
    include '../../layout/admin__config.php';
    include '../ajax/compliances.php';
    
    // $module_id = 'wnpzc2gqu7';
    
    if(isset($_POST["update-compliance"]) && isset($_POST["id"])){
        $error = false;
        
                $id = sanitizePlus($_POST["id"]);
                $module = sanitizePlus($_POST["module"]);
                $compliancestandard = sanitizePlus($_POST["compliancestandard"]);
                $legislation = sanitizePlus($_POST["legislation"]);
                $control_req = sanitizePlus($_POST["control"]);
                $training = sanitizePlus($_POST["training"]);
                $freq = sanitizePlus($_POST["freq"]);
                $compliancestatus = sanitizePlus($_POST["compliancestatus"]);
                $officer = sanitizePlus($_POST["officer"]);
                
                $incidents = serialize($_POST["incidents"]);
                
                if(isset($_POST['imported_control'])){
                    $imported_control = sanitizePlus($_POST['imported_control']);
                }else{
                    $imported_control = null;
                }
                
                if(isset($_POST['imported_treatment'])){
                    $imported_treatment = sanitizePlus($_POST['imported_treatment']);
                }else{
                    $imported_treatment = null;
                }
                
                
                $control_type = sanitizePlus($_POST["control-type"]);
                $treatment_type = sanitizePlus($_POST["treatment-type"]);
                
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
                
                $com_id = $id;
                
                $filename = $_FILES["file"]["name"];
                
                if(isset($filename) && $filename != ''){
                    $fileWasUploaded = true;
                	$file_basename = substr($filename, 0, strripos($filename, '.')); // get file extention
                	$file_ext = substr($filename, strripos($filename, '.')); // get file name
                	$filesize = $_FILES["file"]["size"];
                	#echo $filesize;exit();
                	$allowed_file_types = array('.doc','.docx','.rtf','.pdf','.jpg','.png','.jpeg','.xls');	
                
                	if (in_array($file_ext,$allowed_file_types) && ($filesize < 5000000)) {	
                		// Rename file
                		$newfilename = 'compliance_evidence_'.$com_id . $file_ext;
                		
                		if(move_uploaded_file($_FILES["file"]["tmp_name"], "evidence/" . $newfilename)){
                            $error = false;
                            $targetFilePath = $newfilename;
                            
                        }else{
                            $error = true;
                            array_push($message, 'Error 502: Error Uploading Evidence!!');
                        }
                	} elseif (empty($file_basename)) {	
                		// file selection error
                		#echo "Please select a file to upload.";
                		$error = true;
                		array_push($message, "Error Uploading File: Please Select A File For Upload!!");
                	} elseif ($filesize > 5000000) {	
                		// file size error
                		#echo "The file you are trying to upload is too large.";
                		$error = true;
                		array_push($message, "Error Uploading File: File Too Large, Maximum Allowed - 5MB!!");
                	} else {
                		// file type error
                		#echo "Only these file typs are allowed for upload: " . implode(', ',$allowed_file_types);
                		$error = true;
                		array_push($message, "Error Uploading File: Only these file types are allowed: " . implode(', ',$allowed_file_types));
                		unlink($_FILES["file"]["tmp_name"]);
                	}
                }else{
                    $fileWasUploaded = false;
                    $targetFilePath = 'null';
                    
                }
                
                if($error === false){
                    $date = date("Y-m-d");
                    $query = "UPDATE as_compliancestandard SET control_type = '$control_type', incidents = '$incidents', treatment_type = '$treatment_type', module = '$module', com_compliancestandard = '$compliancestandard', imported_controls = '$imported_control', imported_treatments = '$imported_treatment', com_legislation = '$legislation', com_controls = '$control_req', com_training = '$training', co_status = '$compliancestatus', com_officer = '$officer', com_documentation = '$targetFilePath', existing_ct = '$control', existing_tr = '$treatment', frequency = '$freq' WHERE c_id = '$company_id' AND compli_id = '$id'";              
                    $sql = mysqli_query($con, $query);
                    if ($sql) {
                        array_push($message, 'Compliance Values Updated Successfully!!');
                    } else {
                        array_push($message, 'Error 502: Error!!');
                    }
                }
            }
            
    if (isset($_GET['id']) && isset($_GET['id']) !== "") {
        $toDisplay = true;   
        $id = sanitizePlus($_GET['id']);
        $CheckIfProcedureExist = "SELECT * FROM as_compliancestandard WHERE compli_id = '$id' AND c_id = '$company_id'";
        $ProcedureExist = $con->query($CheckIfProcedureExist);
        if ($ProcedureExist->num_rows > 0) {	
            $compli_exist = true;
			$info = $ProcedureExist->fetch_assoc();
			
			$recommended_control = $info['existing_ct'];
            $saved_control = $info['saved_control'];	
            $saved_treatment = $info['saved_treatment'];	
            $custom_control = $info['custom_control'];	
            $custom_treatment = $info['custom_treatment'];
            
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
            
            $existing_ct = $info['existing_ct'];
            $freq = $info['frequency']; # unused
            
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
                $uploadedEvidence = '<a href="evidence/'.$evidence.'" target="_blank">View File</a>';
            }

            
        }else{
            $compli_exist = false;
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
  <title>Update Compliance | <?php echo $siteEndTitle; ?></title>
  <?php require '../../layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/footer.custom.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/admin.custom.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/bundles/prism/prism.css">
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
                <?php if ($compli_exist == true) { ?>
                <div class="card">
                    <form method="post" enctype="multipart/form-data">
                        <div class="card-body">
                            <?php require '../../layout/alert.php' ?>

                            <div class="card-header">
                                <h3 class="d-inline">Compliance Information</h3>
                                <a class="btn btn-primary btn-icon icon-left header-a" href="compliance-details?id=<?php echo $info['compli_id']; ?>"><i class="fas fa-arrow-left"></i> Back</a>
                            </div>
                            <div class="card-body">
                                
                                <div class="form-group">
									<label>Compliance: </label>
									<select class="form-control" name="module" id="module">
										<?php echo listModuleCompliance($info['module'], $info['compliance_module'], $con); ?>
									</select>
								</div>
								
                                <div class="form-group">
									<label>Compliance Obligation: </label>
									<textarea name="compliancestandard" class="form-control" id='compliance__obligation' placeholder="Enter Compliance Task Or Obligation..." required><?php echo str_replace("?", " - ", preg_replace('#<br\s*/?>#i', ' ', $info['com_compliancestandard'])); ?></textarea>
								</div>
								
								<div class="form-group">
									<label>Compliance Requirements: </label>
									<textarea name="training" class="form-control lg" id='compliance__requirements' placeholder="Enter Compliance Requirements..." required><?php echo $info['com_training']; ?></textarea>
								</div>
								
								<input name='id' value='<?php echo $info['compli_id']; ?>' type='hidden' />
								
								<div class='row custom-row'>
								<div class="form-group col-12 col-lg-4">
									<label for='com_officer'>Compliance Officer: </label>
									<input name="officer" id="com_officer" value='<?php echo $info['com_officer']; ?>' type="text" maxlength="255" class="form-control" placeholder="Enter Compliance Officer..." required>
								</div>
								<div class="form-group col-12 col-lg-8">
									<label for='reference'>Legislation: </label>
									<input name="legislation" id='reference' class="form-control" placeholder="Enter Legislation..." value='<?php echo str_replace("<br />", ",", nl2br($info['com_legislation'])); ?>' required />
								</div>
								</div>
                                
							<div class='row custom-row'>
								<div class="form-group col-12 col-lg-3">
									<label for='com_status'>Compliance Status: </label>
									<?php if($info['co_status'] == null || $info['co_status'] == ''){$info['co_status'] = 'Un-Assessed';} ?>
									<select class="form-control" name="compliancestatus" id='com_status'>
										<?php 
    										if($info['co_status'] == "Un-Assessed" || strtolower($info['co_status']) == "un-assessed" || strtolower($info['co_status']) == "unassessed"){
    										    $effect = 'unaccessed';
    										}else{
    										    $effect = $info['co_status'];
    										}
    										
										    echo listEffectiveness($effect);
										?>
									</select>

								</div>
								<div class="form-group col-12 col-lg-3">
									<label for='com_freq'>Compliance Frequency: </label>
									<select class="form-control" name="freq" id='com_freq'>
                                        <?php echo listFrequencies($info['frequency']); ?>
									</select>

								</div>
                                <div class="form-group col-12 col-lg-6">
									<label>Documentation & Evidence: </label>
									    <div class="input-group">
                                            <div class='file_name form-control'><?php echo $uploadedEvidence; ?></div>
                                            <div class="input-group-append">
                                                <div class="input-group-text" id='file_opener' style="cursor:pointer;">
                                                    <i class="fa fa-plus"></i>
                                                </div>
                                            </div>
                                        </div>
									<div class="col-12" style='margin-bottom:10px;'>
									</div>
									<input id="file_main" name="file" type="file">
								</div>
								</div>
							<?php if($info['action_type'] == 'actions'){ ?>
							<div class="form-group">
								<label>Compliance Actions: </label>
								<textarea name="action" class="form-control lg" placeholder="Enter Compliance Actions..." required><?php echo str_replace("?", " - ", preg_replace('#<br\s*/?>#i', ' ', $info['action'])); ?></textarea>
							</div>
							</div> <!-- Close Card Body -->
                            <?php }else{ ?>
                            </div> <!-- Close Card Body -->
                            
                            <div class='div_divider'></div>
                            
                            <!-- Incidents -->
                                <div class="card-header">
                                    <h3 class="d-inline">Compliance Incidents</h3>
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
                                #custom_type{
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
                                <?php if($info['type'] == 'imported' && $info['imported_controls'] != null ){ ?>
    							<div class="form-group">
    								<label>Imported Controls: </label>
    								<textarea name="imported_control" class="form-control" placeholder="Imported Controls..." required><?php echo $info['imported_controls']; ?></textarea>
    							</div> 
                                <?php } ?>
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
                                        
                                        <div class='c_type' id='control_selctor'>
                                            <div id='fetchControls' style='width:100%;'>
                                            <?php 
                                                $controls = unserialize($info['existing_ct']);
                                                $controls_more = $controls;
                                                $c_count = 0;
                                                foreach($controls as $control){
                                                    $c_count++;
                                                    echo listComplianceRecommendedControl_Selected($info['module'], $control, $con);
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
                                                <?php echo listComplianceRecommendedControl_Selected($info['module'], $_control, $con); ?>
                                                <buttton class="btn btn-sm btn-primary remove_button_t rmv_btn" style='margin-left:5px;display:flex;justify-content:center;align-items:center;font-size:20px;padding:12px 10px;' type="button"><i class="fas fa-minus"></i></buttton>
                                            </div>
                                            <?php } ?>
                                        </div>
                                        
                                    </div> 
                                    <?php }else{ ?>
                                    <div class="form-group" id='recommended_type'>
                                        <label class="help-label">
                                            RiskSafe Recommended Controls
                                        </label>
                                        
                                        <div class='c_type' id='control_selctor'>
                                            <div id='fetchControls' style='width:100%;'>
                                            <?php echo listComplianceRecommendedControl_Selected($info['module'], 'null', $con); ?>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-primary" id="btn-append-rec-control">+ Add</button>
                                        </div>
                                            
                                        <div id='add-rec-control'></div>
                                    </div> 
                                    <?php } ?>
                                    
                                    <?php if($info['control_type'] == 'saved'){ ?>
                                    <div class="form-group" id='saved_type'>
                                        <label class="help-label">
                                            Saved Custom Controls
                                        </label>
                                        <div class="add-customs">
                                            <?php 
                                                $controls = unserialize($info['existing_ct']);
                                                $controls_more = $controls;
                                                $c_count = 0;
                                                foreach($controls as $control){
                                                    $c_count++;
                                                    echo "<div style='width:100%;margin-right:5px;' id='fh4nfve_110'>";
                                                    echo listCompanyControlSelected_New($company_id, $control, $con);
                                                    echo "</div>";
                                                    break;
                                                }
                                            ?>
                                            
                                            <a href='../customs/new-control?redirect=true' target='_blank' class="btn btn-sm btn-primary" id='fn4h9nf' style='width: 15%;display:flex;justify-content:center;align-items:center;'>+ Create New</a>
                                            <buttton id='f93nfo0_110' class="btn btn-sm btn-primary" type='button' data-toggle="tooltip" title="Refresh Customs List" data-placement="left" style='margin-left:5px;display:flex;justify-content:center;align-items:center;font-size:20px;padding:0 10px;'><i class='fas fa-spinner'></i></buttton>
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
                                                $controls = unserialize($info['existing_ct']);
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
                                
                                
                                <div class="form-group">
									<label>Control Requirements: </label>
									<input name="control" value='<?php echo $info['com_controls']; ?>' type="text" maxlength="255" class="form-control" placeholder="Type Something..." required>

								</div>
                            </div>
                            <div class='div_divider'></div>

                            <!-- Treatment -->
                            <div class="card-header hh">
                                <h3 class="d-inline">Treatment Plans</h3>
                                <i class="fas fa-question btn btn-primary btn-icon btn-small btn-help header-a" id="swal-custom-1"></i>
                            </div>
                            <div class="card-body">
                                <?php if($info['type'] == 'imported' && $info['imported_treatments'] != null ){ ?>
    							<div class="form-group">
    								<label>Imported Treatments: </label>
    								<textarea name="imported_treatment" class="form-control" placeholder="Imported Treatments..." required><?php echo $info['imported_treatments']; ?></textarea>
    							</div> 
                                <?php } ?>
                                
                                <div class="form-group" style='display:flex;gap:50px;'>
                                <div>
                                    <input type='radio' id='assessment-specific-t' value='custom' name='treatment-type' <?php if($info['treatment_type'] == 'custom'){ echo 'checked'; } ?> />
                                    <label for='assessment-specific-t'>Assessment Specific Treatments</label>
                                </div>
                                <div>
                                    <input type='radio' id='saved-t' value='saved' name='treatment-type' <?php if($info['treatment_type'] == 'saved'){ echo 'checked'; } ?> />
                                    <label for='saved-t'>Saved Custom Controls</label>
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
                                            $treatments = unserialize($info['existing_tr']);
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
                                        Compliance Specific Treatments
                                    </label>
                                    <div class="add-customs">
                                        <?php 
                                            $treatments = unserialize($info['existing_tr']);
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
                            </div>
                            <?php } ?>
                            
                            <div class="card-body">
                                <div class="form-group">
									<button type="submit" class="btn btn-md btn-primary" name="update-compliance">Update Compliance</button>
									<button type="button" class="btn btn-md btn-warning" id="btn_cancel">Cancel</button>
								</div>
                            </div>
                        </div>
                        <div class="card-footer"></div>
                    </form>
                    
                    <form id='getModule'>
                        <input type='hidden' name='module_id' id='module_id' />
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
        <?php require '../../layout/footer.php' ?>
        </footer>
        </div>
    </div>
    <?php require '../../layout/general_js.php' ?>
    <script src="<?php echo $file_dir; ?>assets/bundles/prism/prism.js"></script>
    <script src="<?php echo $file_dir; ?>assets/bundles/sweetalert/sweetalert.min.js"></script>
    <script src="<?php echo $file_dir; ?>assets/js/page/sweetalert.js"></script>
    <script src="<?php echo $file_dir; ?>assets/js/admin.custom.js"></script>
    <style>
    .c_type{
            display:flex;
            gap:10px;
        }
        .c_type button{
            width:10% !important;
        }
        textarea{
            min-height: 120px !important;
        }
        textarea.lg{
            min-height: 200px !important;
        }
        .div_divider{
            margin: 20px 0px;
        }
        .card-header.hh{
            display:flex;
            justify-content:space-between;
        }
    </style>
    <script>
    
     $("#f93nfo0_1111").click(function (e) {
          $("#fh4nfve_1111").load(" #fh4nfve_1111 > *");
        });
        
        $("#f93nfo0_110").click(function (e) {
          $("#fh4nfve_110").load(" #fh4nfve_110 > *");
        });

        let fieldHTMLTreatent = '';
        
        <?php if($toDisplay === true && $info !== null && $info['module'] !== null){ ?>
        fieldHTMLTreatent = '<div style="display:flex;justify-content:center;align-items:center;gap:5px;margin-top:5px;"> <?php echo listComplianceRecommendedControl_Selected($info["module"], "null", $con); ?> <buttton class="btn btn-sm btn-primary remove_button_t" type="button" style="margin-left:5px;display:flex;justify-content:center;align-items:center;font-size:20px;padding:12px 10px;"><i class="fas fa-minus"></i></buttton></div>';
        <?php }else{ ?>
        fieldHTMLTreatent = '<div style="display:flex;justify-content:center;align-items:center;gap:5px;margin-top:5px;"> <select name="existing_ct[]" class="form-control" required> <option value="null" selected>None Selected</option> <option>Error</option> </select> <buttton class="btn btn-sm btn-primary remove_button_t" type="button" style="margin-left:5px;display:flex;justify-content:center;align-items:center;font-size:20px;padding:12px 10px;"><i class="fas fa-minus"></i></buttton></div>';
        <?php } ?>

        $("#module").change(function (e) {
            var moduleValue = $("#module").val();
            $('#add-rec-control').html(null);
            
            if (moduleValue == "none") {
                $("#compliance__obligation").val('');
                $("#compliance__requirements").val('');
            } else {
                $("#compliance__obligation").val('Fetching Compliance Obligation...');
                $("#compliance__requirements").val('Fetching Compliance Requirements...');
                
                $("#module_id").val('');
                $("#module_id").val(moduleValue);
                    
                    
                $("#getModule").submit();
            }
            
        });
        
        $("#getModule").submit(function (event) {
          event.preventDefault();
        
          var formValues = $(this).serialize();
          $.post("../ajax/compliances", {
            getModule: formValues,
          }).done(function (data) {
            const jsonObject = JSON.parse(data);
            $("#compliance__obligation").val(jsonObject.obligation);
            $("#compliance__requirements").val(jsonObject.requirements);
            $("#com_officer").val(jsonObject.officers);
            $("#reference").val(jsonObject.reference);
            $("#com_freq").html(jsonObject.frequency);
            $("#com_status").html(jsonObject.effectiveness);
                $("#existing_ct").html(jsonObject.controls);
            
            
            if(jsonObject.hasData === true){
                $('#btn-append-rec-control').show();
            }
            
            if(jsonObject.hasControl === true){
                $('#btn-append-rec-control').show();
                fieldHTMLTreatent = '<div style="display:flex;justify-content:center;align-items:center;gap:5px;margin-top:5px;"> <select name="existing_ct[]" class="form-control" required> <option value="null" selected>None Selected</option> '+jsonObject.controls+'</select> <buttton class="btn btn-sm btn-primary remove_button_t" type="button" style="margin-left:5px;display:flex;justify-content:center;align-items:center;font-size:20px;padding:12px 10px;"><i class="fas fa-minus"></i></buttton></div>';
            }else{
                fieldHTMLTreatent = '';
                $('#btn-append-rec-control').hide();
                // $("#existing_ct").html(jsonObject.controls);
            }
            
            
            setTimeout(function () {
              $("#getModule input").val("");
            }, 0);
          });
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
                    
                }else if(val == 'saved'){
                    $('#recommended_type').hide();
                    $('#custom_type').hide();
                    $('#saved_type').show();
                    $('#na_type_c').hide();
                }else if(val == 'custom'){
                    $('#recommended_type').hide();
                    $('#custom_type').show();
                    $('#saved_type').hide();
                    $('#na_type_c').hide();
                }else if(val == 'na'){
                    $('#recommended_type').hide();
                    $('#custom_type').hide();
                    $('#saved_type').hide();
                    $('#na_type_c').show();
                }else{
                    $('#recommended_type').show();
                    $('#custom_type').hide();
                    $('#saved_type').hide();
                    $('#na_type_c').hide();
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
            // var fieldHTMLTreatent = '<div style="display:flex;justify-content:center;align-items:center;gap:5px;margin-top:5px;"><select name="existing_ct[]" class="form-control" required> <option value="null" selected>None Selected</option> <?php echo listControl($company_id, $con); ?> </select> <buttton class="btn btn-sm btn-primary remove_button_t" type="button" style="margin-left:5px;display:flex;justify-content:center;align-items:center;font-size:20px;padding:12px 10px;"><i class="fas fa-minus"></i></buttton></div>';
            var x_Treatmnt = 1; //Initial field counter is 1
            
            // Once add button is clicked
            $(addButtonTreament).click(function(){
                //Check maximum number of input fields
                if(x_Treatmnt < maxFieldTreatmnt){ 
                    alert('reached here');
                    
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
            var fieldHTMLTreatmen = '<div style="display:flex;justify-content:center;align-items:center;gap:5px;margin-top:5px;"> <select name="saved-control[]" class="form-control" required> <?php echo listCompanyControl($company_id, $con); ?></select> <buttton class="btn btn-sm btn-primary remove_button_t" type="button" style="margin-left:5px;display:flex;justify-content:center;align-items:center;font-size:20px;padding:12px 10px;"><i class="fas fa-minus"></i></buttton></div>';
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
</body>
</html>