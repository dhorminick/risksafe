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
    
    if(isset($_POST["update-compliance"]) && isset($_POST["id"])){
                $id = sanitizePlus($_POST["id"]);
                $compliancestandard = sanitizePlus($_POST["compliancestandard"]);
                $legislation = sanitizePlus($_POST["legislation"]);
                $control = sanitizePlus($_POST["control"]);
                $training = sanitizePlus($_POST["training"]);
                $freq = sanitizePlus($_POST["freq"]);
                $compliancestatus = sanitizePlus($_POST["compliancestatus"]);
                $officer = sanitizePlus($_POST["officer"]);
                
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
                			
                // 		if (file_exists("upload/" . $newfilename)) {
                // 			// file already exists error
                // 			echo "You have already uploaded this file.";
                // 		} else {		
                			
                // 		}
                
                        if(move_uploaded_file($_FILES["file"]["tmp_name"], "evidence/" . $newfilename)){
                            
                            $targetFilePath = $newfilename;
                            $date = date("Y-m-d");
                            $query = "UPDATE as_compliancestandard SET com_compliancestandard = '$compliancestandard', imported_controls = '$imported_control', imported_treatments = '$imported_treatment', com_legislation = '$legislation', com_controls = '$control', com_training = '$training', co_status = '$compliancestatus', com_officer = '$officer', com_documentation = '$targetFilePath', existing_ct = '$existing_ct', saved_control = '$saved_control', saved_treatment = '$saved_treatment', custom_control = '$custom_control', custom_treatment = '$custom_treatment', frequency = '$freq' WHERE c_id = '$company_id' AND compli_id = '$id'";               
                            $sql = mysqli_query($con, $query);
                            if ($sql) {
                                header('Location: compliance-details?id='.$com_id);
                            } else {
                                array_push($message, 'Error 502: Error!!');
                            }
                        }else{
                            array_push($message, 'Error 502: Error Uploading Evidence!!');
                        }
                	} elseif (empty($file_basename)) {	
                		// file selection error
                		#echo "Please select a file to upload.";
                		array_push($message, "Error Uploading File: Please Select A File For Upload!!");
                	} elseif ($filesize > 5000000) {	
                		// file size error
                		#echo "The file you are trying to upload is too large.";
                		array_push($message, "Error Uploading File: File Too Large, Maximum Allowed - 5MB!!");
                	} else {
                		// file type error
                		#echo "Only these file typs are allowed for upload: " . implode(', ',$allowed_file_types);
                		array_push($message, "Error Uploading File: Only these file types are allowed: " . implode(', ',$allowed_file_types));
                		unlink($_FILES["file"]["tmp_name"]);
                	}
                }else{
                    $fileWasUploaded = false;
                    $targetFilePath = 'null';
                    $date = date("Y-m-d");
                    $query = "UPDATE as_compliancestandard SET com_compliancestandard = '$compliancestandard', imported_controls = '$imported_control', imported_treatments = '$imported_treatment', com_legislation = '$legislation', com_controls = '$control', com_training = '$training', co_status = '$compliancestatus', com_officer = '$officer', com_documentation = '$targetFilePath', existing_ct = '$existing_ct', saved_control = '$saved_control', saved_treatment = '$saved_treatment', custom_control = '$custom_control', custom_treatment = '$custom_treatment', frequency = '$freq' WHERE c_id = '$company_id' AND compli_id = '$id'";              
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
            $freq = $info['frequency'];
            
            if($custom_control == null || $custom_control == 'null'){
                $un_custom_control = 'null';
            }else{
                $un_custom_control = unserialize($custom_control);
            }
            
            if($custom_treatment == null || $custom_treatment == 'null'){
                $un_custom_treatment = 'null';
            }else{
                $un_custom_treatment = unserialize($custom_treatment);
            }
            
            
            $hasCustomControl = is_array($un_custom_control);
            $hasCustomTreatment = is_array($un_custom_treatment);
            
            $evidence = $info['com_documentation'];
            
            if($evidence == 'null'){
                $uploadedEvidence = 'None Uploaded';
            }else{
                $uploadedEvidence = '<a href="evidence/'.$evidence.'" target="_blank">View File</a>';
            }

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
									<label>Compliance Obligation: </label>
									<textarea name="compliancestandard" class="form-control" placeholder="Enter Compliance Task Or Obligation..." required><?php echo str_replace("?", " - ", preg_replace('#<br\s*/?>#i', ' ', $info['com_compliancestandard'])); ?></textarea>
								</div>
								<input name='id' value='<?php echo $info['compli_id']; ?>' type='hidden' />
								<div class='row custom-row'>
								<div class="form-group col-12 col-lg-4">
									<label>Compliance Officer: </label>
									<input name="officer" value='<?php echo $info['com_officer']; ?>' type="text" maxlength="255" class="form-control" placeholder="Enter Compliance Officer..." required>
								</div>
								<div class="form-group col-12 col-lg-8">
									<label>Legislation: </label>
									<input name="legislation" class="form-control" placeholder="Enter Legislation..." value='<?php echo str_replace("<br />", ",", nl2br($info['com_legislation'])); ?>' required />
								</div>
								</div>
                                
                                <div class="form-group">
									<label>Compliance Requirements: </label>
									<textarea name="training" class="form-control lg" placeholder="Enter Compliance Requirements..." required><?php echo $info['com_training']; ?></textarea>
								</div>
								
							<div class='row custom-row'>
								<div class="form-group col-12 col-lg-3">
									<label>Compliance Status: </label>
									<?php if($info['co_status'] == null || $info['co_status'] == ''){$info['co_status'] = 'Un-Assessed';} ?>
									<select class="form-control" name="compliancestatus">
										<option <?php if($info['co_status'] == "Effective") echo 'selected ';?>value="Effective">Effective</option>
										<option <?php if($info['co_status'] == "Ineffective") echo 'selected ';?>value="Ineffective">Ineffective</option>
										<option <?php if($info['co_status'] == "Un-Assessed") echo 'selected ';?>value="Un-Assessed">Un-Assessed</option>
									</select>

								</div>
								<div class="form-group col-12 col-lg-3">
									<label>Compliance Frequency: </label>
									<select class="form-control" name="freq">
										<option value="1" <?php if ($freq == 1 || strtolower($freq) == 'daily') echo "selected"; ?>>Daily Controls</option>
                                        <option value="2" <?php if ($freq == 2 || strtolower($freq) == 'weekly') echo "selected"; ?>>Weekly Controls</option>
                                        <option value="3" <?php if ($freq == 3) echo "selected"; ?>>Fort-Nightly Controls</option>
                                        <option value="4" <?php if ($freq == 4 || strtolower($freq) == 'monthly') echo "selected"; ?>>Monthly Controls</option>
                                        <option value="5" <?php if ($freq == 5 || strtolower($freq) == 'half yearly') echo "selected"; ?>>Semi-Annually Controls</option>
                                        <option value="6" <?php if ($freq == 6 || strtolower($freq) == 'annually') echo "selected"; ?>>Annually Controls</option>
                                        <option value="7" <?php if ($freq == 7) echo "selected"; ?>>As Required</option>
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
                                <div class="form-group">
                                    <label class="help-label">
                                        RiskSafe Recommended Controls
                                    </label>
                                    <select name="existing_ct" id="existing_ct" class="form-control" required>
                                        <option value="0">None Selected</option>
                                        <?php echo listControlSelected($existing_ct, $con); ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="help-label">
                                        Saved Custom Controls
                                    </label>
                                    <div class="add-customs">
                                        <div style='width:100%;margin-right:5px;' id='fh4nfve'>
                                        <select name="saved-control" class="form-control" required style='margin-right:5px;'>
                                            <!-- add none selected -->
                                            <?php 
                                                if($saved_control == '1' || !$saved_control || $saved_control == null || $saved_control == 'null'){
                                                    echo listCompanyControl($company_id, $con);
                                                }else{
                                                    echo listCompanyControlSelected($company_id, $saved_control, $con);
                                                }
                                            ?>
                                        </select>
                                        </div>
                                        <a href='../customs/new-control?redirect=true' target='_blank' id='fn4h9nf' class="btn btn-sm btn-primary" style='width: 15%;display:flex;justify-content:center;align-items:center;'>+ Create New</a>
                                        <buttton id='f93nfo0' class="btn btn-sm btn-primary" type='button' data-toggle="tooltip" title="Refresh Customs List" data-placement="left" style='margin-left:5px;display:flex;justify-content:center;align-items:center;font-size:20px;padding:0 10px;'><i class='fas fa-spinner'></i></buttton>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="help-label">
                                        Compliance Specific Controls
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
                                                <input type="text" class="form-control" value='<?php echo $value; ?>' placeholder="Enter Custom control Description..." style="margin-top:5px;margin-right:5px;" name="custom-control[]" />
                                                <buttton class="btn btn-sm btn-primary remove_button r" type="button"><i class="fas fa-minus"></i></buttton>
                                            </div>
                                        <?php } ?>
                                        <?php } ?>
                                    </div>
                                    <?php } ?>
                                    
                                    <div class="custom-controls"></div>
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
                                <div class="form-group">
                                    <label class="help-label">
                                        Saved Custom Treatments
                                    </label>
                                    <div class="add-customs">
                                        <div style='width:100%;margin-right:5px;' id='fh4nfvf'>
                                        <select name="saved-treatment" class="form-control" required style='margin-right:5px;'>
                                            <?php 
                                                if($saved_treatment == '1' || !$saved_treatment || $saved_treatment == null || $saved_treatment == 'null'){
                                                    echo listCompanyTreatment($company_id, $con);
                                                }else{
                                                    echo listCompanyTreatmentSelected($company_id, $saved_treatment, $con); 
                                                }
                                            ?>
                                        </select>
                                        </div>
                                        <a href='../customs/new-treatment?redirect=true' target='_blank' class="btn btn-sm btn-primary" style='width: 15%;display:flex;justify-content:center;align-items:center;'>+ Create New</a>
                                        <buttton id='f93nfo1' class="btn btn-sm btn-primary" type='button' data-toggle="tooltip" title="Refresh Customs List" data-placement="left" style='margin-left:5px;display:flex;justify-content:center;align-items:center;font-size:20px;padding:0 10px;'><i class='fas fa-spinner'></i></buttton>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="help-label">
                                        Compliance Specific Treatments
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
                                                <input type="text" class="form-control" value='<?php echo $value; ?>' placeholder="Enter Custom Treatment Description..." style="margin-top:5px;margin-right:5px;" name="custom-treatment[]" />
                                                <buttton class="btn btn-sm btn-primary remove_button_t r" type="button"><i class="fas fa-minus"></i></buttton>
                                            </div>
                                        <?php } ?>
                                        <?php } ?>
                                    </div>
                                    <?php } ?>
                                
                                </div>
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
</body>
</html>