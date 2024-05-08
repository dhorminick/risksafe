<?php
    session_start();
    $file_dir = '../../';
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'login?r=/compliances/all');
        exit();
    }
    $message = [];
    include '../../layout/db.php';
    include '../../layout/admin__config.php';
    include '../ajax/compliances.php';

    if(isset($_POST["create-compliance"])){
        
        $compliancestandard = sanitizePlus($_POST["compliancestandard"]);
        $legislation = sanitizePlus($_POST["legislation"]);
        $existing_tr = 'null';
        $existing_ct = sanitizePlus($_POST["existing_ct"]);
        $control = sanitizePlus($_POST["control"]);
        $training = sanitizePlus($_POST["training"]);
        $compliancestatus = sanitizePlus($_POST["compliancestatus"]);
        $officer = sanitizePlus($_POST["officer"]);
        
        // $custom_control_main = sanitizePlus($_POST["custom-control-main"]);
        // $custom_treatment_main = sanitizePlus($_POST["custom-treatment-main"]);
        
        $freq = sanitizePlus($_POST["freq"]);
        
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
        
        $com_id = secure_random_string(10);
        
        $filename = $_FILES["file"]["name"];
        
        if(isset($filename) && $filename != ''){
            $fileWasUploaded = true;
        	$file_basename = substr($filename, 0, strripos($filename, '.')); // get file extention
        	$file_ext = substr($filename, strripos($filename, '.')); // get file name
        	$filesize = $_FILES["file"]["size"];
        	#echo $filesize;exit();
        	$allowed_file_types = array('.doc','.docx','.rtf','.pdf','.jpg','.png','.jpeg','.xls', '.csv', '.xlsx');	
        
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
                    #$com_id = secure_random_string(10);
                    $date = date("Y-m-d");
                    $query = "INSERT INTO as_compliancestandard (com_user_id, com_compliancestandard, com_legislation, com_controls, com_training, co_status, com_officer, com_documentation,existing_tr,existing_ct, c_id, compli_id, saved_control, saved_treatment, custom_control, custom_treatment, frequency) VALUES ('$userId', '$compliancestandard', '$legislation', '$control', '$training', '$compliancestatus', '$officer', '$targetFilePath','$existing_tr','$existing_ct','$company_id','$com_id', '$saved_control', '$saved_treatment', '$custom_control', '$custom_treatment', '$freq')";
                    $sql = mysqli_query($con, $query);
                    if ($sql) {
                        #notify
                        $datetime = date("Y-m-d H:i:s");
                            $notification_message = 'New Compliance Standard Created';
                            $type = 'compliance';
                            $notifier = $userId;
                            $link = "admin/compliance/compliance-details?id=".$com_id;
                            $case = 'new';
                            #$case_type = 'new-risk';
                            $id = $com_id;
                            $returnArray = createNotification($company_id, $notification_message, $datetime, $notifier, $link, $type, $case, $id, $con, $sitee);
                        
                        header('Location: compliance-details?id='.$com_id);
                    } else {
                        array_push($message, 'Error 502: Error!!');
                    }
                }else{
                    
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
            #$com_id = secure_random_string(10);
            $date = date("Y-m-d");
            $query = "INSERT INTO as_compliancestandard (com_user_id, com_compliancestandard, com_legislation, com_controls, com_training, co_status, com_officer, com_documentation,existing_tr,existing_ct, c_id, compli_id, saved_control, saved_treatment, custom_control, custom_treatment, frequency) VALUES 
            ('$userId', '$compliancestandard', '$legislation', '$control', '$training', '$compliancestatus', '$officer', '$targetFilePath','$existing_tr','$existing_ct','$company_id','$com_id', '$saved_control', '$saved_treatment', '$custom_control', '$custom_treatment', '$freq')";
            $sql = mysqli_query($con, $query);
            if ($sql) {
                #notify
                $datetime = date("Y-m-d H:i:s");
                    $notification_message = 'New Compliance Standard Created';
                    $type = 'compliance';
                    $notifier = $userId;
                    $link = "admin/compliance/compliance-details?id=".$com_id;
                    $case = 'new';
                    #$case_type = 'new-risk';
                    $id = $com_id;
                    $returnArray = createNotification($company_id, $notification_message, $datetime, $notifier, $link, $type, $case, $id, $con, $sitee);
                
                header('Location: compliance-details?id='.$com_id);
            } else {
                array_push($message, 'Error 502: Error!!');
            }
        }
        

    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Create New Compliance | <?php echo $siteEndTitle; ?></title>
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
                <div class="card">
                    <form method="post" enctype="multipart/form-data">
                        <div class="card-header"></div>
                        <div class="card-body">
                            <?php require '../../layout/alert.php' ?>

                            <div class="card-header">
                                <h3 class="d-inline">Compliance Information</h3>
                                <a class="btn btn-primary btn-icon icon-left header-a" href="all"><i class="fas fa-arrow-left"></i> Back</a>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
									<label>Compliance Standard: </label>
									<input name="compliancestandard" type="text" maxlength="100" class="form-control" placeholder="Enter Compliance Standard..." required>

								</div>
								<div class='row custom-row'>
                                <div class="form-group col-12 col-lg-4">
									<label>Compliance Officer: </label>
									<input name="officer" type="text" maxlength="255" class="form-control" placeholder="Enter Compliance Officer..." required>
								</div>
								<div class="form-group col-12 col-lg-8">
									<label>Legislation: </label>
									<input name="legislation" type="text" maxlength="255" class="form-control" placeholder="Enter Legislation..." required>

								</div>
								</div>
                                <div class="form-group">
									<label>Compliance Requirements: </label>
									<textarea name="training" class="form-control" placeholder="Enter Compliance Requirements..." required></textarea>
								</div>
								<div class='row custom-row'>
                                <div class="form-group col-12 col-lg-3">
									<label>Compliance Status: </label>
									<select class="form-control" name="compliancestatus">
										<option value="Effective">Effective</option>
										<option value="Ineffective">Ineffective</option>
									</select>

								</div>
                                <div class="form-group col-12 col-lg-3">
									<label>Compliance Frequency: </label>
									<select class="form-control" name="freq">
										<option value="1">Daily Applications</option>
                                        <option value="2">Weekly Applications</option>
                                        <option value="4">Monthly Applications</option>
                                        <option value="5">Quaterly Applications</option>
                                        <option value="6">Annually Applications</option>
                                        <option value="7">As Required</option>
									</select>

								</div>
                                <div class="form-group col-12 col-lg-6">
									<label>Documentation & Evidence: </label>
									    <div class="input-group">
                                            <div class='file_name form-control'>Select File:</div>
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
                                        <option value="0" selected>None Selected</option>
                                        <?php echo listControl($company_id, $con); ?>
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
                                            <?php echo listCompanyControl($company_id, $con); ?>
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
                                    <div class="add-customs">
                                        <input type="text" class="form-control" placeholder="Enter custom control description..." name='custom-control[]'>
                                        <button type="button" class="btn btn-sm btn-primary" id="btn-append-custom-control">+ Add</button>
                                    </div>
                                    <div id='add-customs-control'></div>
                                    <div class="custom-controls"></div>
                                </div>
                                <div class="form-group">
									<label>Control Requirements: </label>
									<input name="control" type="text" maxlength="255" class="form-control" placeholder="Type Something..." required>

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
                                        Compliance Specific Treatments
                                    </label>
                                    <div class="add-customs">
                                        <input type="text" class="form-control" placeholder="Enter custom control description..." name='custom-treatment[]'>
                                        <button type="button" class="btn btn-sm btn-primary" id="btn-append-custom-treatment">+ Add</button>
                                    </div>
                                    <div id='add-customs-treatment'></div>
                                </div>
                                
                                <div class="clearfix" id="treatments">
                                    <?php #echo rawurldecode(listTreatments($company_id, $con)); ?>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="form-group">
									<button type="submit" class="btn btn-md btn-primary" name="create-compliance">Create Compliance</button>
									<button type="button" class="btn btn-md btn-warning" id="btn_cancel">Cancel</button>
								</div>
                            </div>
                        </div>
                        <div class="card-footer"></div>
                    </form>
                </div>
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