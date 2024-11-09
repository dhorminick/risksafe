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
    $hasModule = false;
    $moduleExists = true; #it exists initially, so we dont get false on page load and see module error message instead of select module message
    $module_id = 'null';
    
    if (isset($_GET['module']) && isset($_GET['module']) !== "") {
        $module_id = strtolower(sanitizePlus($_GET['module']));
        
        $hasModule = true;
        if(moduleExist($module_id, $con) === true){
            $moduleExists = true;
        }else{
            $moduleExists = false;
        }
        
    }else{
        $hasModule = false;
    }
    

    if(isset($_POST["create-compliance"]) && isset($_POST["compliance_module"])){
        // var_dump($_POST);
        
        // exit();
        
        $error = false;
        
        $compliancestandard = sanitizePlus($_POST["compliancestandard"]);
        $compliance_module = sanitizePlus($_POST["compliance_module"]);
        $legislation = sanitizePlus($_POST["legislation"]);
        $module = sanitizePlus($_POST['module']);
        $control_type = sanitizePlus($_POST["control-type"]);
        $treatment_type = sanitizePlus($_POST["treatment-type"]);
        $control_req = sanitizePlus($_POST["control-req"]);
        $training = sanitizePlus($_POST["training"]);
        $compliancestatus = sanitizePlus($_POST["compliancestatus"]);
        $officer = sanitizePlus($_POST["officer"]);
        
        // $custom_control_main = sanitizePlus($_POST["custom-control-main"]);
        // $custom_treatment_main = sanitizePlus($_POST["custom-treatment-main"]);
        
        $freq = sanitizePlus($_POST["freq"]);
        
                
                
                if($treatment_type == 'saved'){
                    $treatment = serialize($_POST["saved-treatment"]);
                }else if($treatment_type == 'custom'){
                    $treatment = serialize($_POST["custom-treatment"]);
                }else{
                    $error = true;
                    array_push($message, 'Error 402: Treatment Type Error!!');
                }
                
        if($control_type == 'recommended'){
                    $control = serialize($_POST["existing_ct"]);
                }else if($control_type == 'saved'){
                    $control = serialize($_POST["saved-control"]);
                }else if($control_type == 'custom'){
                    $control = serialize($_POST["custom-control"]);
                }else{
                    $error = true;
                    array_push($message, 'Error 402: Control Type Error!!');
                }
                
        
        if($error === false){
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
            
            
                    if(move_uploaded_file($_FILES["file"]["tmp_name"], "evidence/" . $newfilename)){
                        $error = false;
                        $targetFilePath = $newfilename;

                    }else{
                        array_push($message, 'Unable to upload documentation');
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
                $error = false;
            }
            
            if($error === false){
                $date = date("Y-m-d");
                        $query = "INSERT INTO as_compliancestandard (compliance_module, control_type, treatment_type, module, com_user_id, com_compliancestandard, com_legislation, com_controls, com_training, co_status, com_officer, com_documentation,existing_tr,existing_ct, c_id, compli_id, saved_control, saved_treatment, custom_control, custom_treatment, frequency) 
                        VALUES ('$compliance_module', '$control_type', '$treatment_type', '$module', '$userId', '$compliancestandard', '$legislation', '$control_req', '$training', '$compliancestatus', '$officer', '$targetFilePath','$treatment','$control','$company_id','$com_id', 'null', 'null', 'null', 'null', '$freq')";
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
                                $returnArray = createNotification($company_id, $notification_message, $datetime, $notifier, $link, $type, $case, $con, $sitee);
                            
                            header('Location: compliance-details?id='.$com_id);
                            exit();
                        } else {
                            array_push($message, 'Error 502: Error!!');
                        }
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
                <div class="card">
                    <form method="post" enctype="multipart/form-data">
                        <div class="card-header"></div>
                        <div class="card-body">
                            <?php require $file_dir.'layout/alert.php' ?>
    
                            <div class="card-header">
                                <h3 class="d-inline">Compliance Information</h3>
                                <a class="btn btn-primary btn-icon icon-left header-a" href="all"><i class="fas fa-arrow-left"></i> Back</a>
                            </div>
                            <div class="card-body">
                                <?php if($hasModule === true){ ?> 
                                <div class='form-group' style="margin-bottom:10px;"><strong>NOTE:</strong> Selecting a new module, would reset the compliance data in use!!</div>
                                <?php } ?>
                                
                                <div class="form-group">
									<label for="compliance_module">Select Compliance Module: </label>
									<select class="form-control" name="compliance_module" id="compliance_module">
										<?php 
										if($hasModule === true){
										    echo listModules($con, $module_id); 
										}else{
										    echo listModules($con); 
										}
										?>
									</select>
								</div>
                            </div>
                            
                            <?php if($hasModule === true && $moduleExists === true){ ?>
                            
                            <?php if($moduleExists === false){ ?>
                            <div class="card-body">Module Error: Selected module does not exist!! </div>
                            <?php }else{ ?>
                            <div class="card-body">
                                <div class="form-group">
									<label>Compliance: </label>
									<select class="form-control" name="module" id="module">
										<?php echo listModuleCompliance('null', $module_id, $con); ?>
									</select>
								</div>
								
                                <div class="form-group">
									<label>Compliance Obligation: </label>
									<textarea name="compliancestandard" class="form-control" id='compliance__obligation' placeholder="Enter Compliance Task or Obligation..." required></textarea>

								</div>
                                <div class="form-group">
									<label>Compliance Requirements: </label>
									<textarea name="training" class="form-control" id='compliance__requirements' placeholder="Enter Compliance Requirements..." required></textarea>
								</div>
								<div class='row custom-row'>
                                <div class="form-group col-12 col-lg-4">
									<label for='com_officer'>Compliance Officer: </label>
									<input name="officer" id="com_officer" type="text" maxlength="255" class="form-control" placeholder="Enter Compliance Officer..." required>
								</div>
								<div class="form-group col-12 col-lg-8">
									<label for='reference'>Legislation: </label>
									<input name="legislation" id='reference' type="text" maxlength="255" class="form-control" placeholder="Enter Legislation..." required>
								</div>
								</div>
								<div class='row custom-row'>
                                <div class="form-group col-12 col-lg-3">
									<label for='com_status'>Compliance Status: </label>
									<select class="form-control" name="compliancestatus" id='com_status'>
										<?php echo listEffectiveness();  ?>
									</select>

								</div>
                                <div class="form-group col-12 col-lg-3">
									<label for='com_freq'>Compliance Frequency: </label>
									<select class="form-control" name="freq" id='com_freq'>
                                        <?php echo listFrequencies(); ?>
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
                                
                                <div class="form-group" style='display:flex;gap:50px;'>
                                    <div>
                                        <input type='radio' id='recommended' value='recommended' name='control-type' checked />
                                        <label for='recommended'>Recommended Controls</label>
                                    </div>
                                    <div>
                                        <input type='radio' id='saved' value='saved' name='control-type' />
                                        <label for='saved'>Saved Custom Controls</label>
                                    </div>
                                    <div>
                                        <input type='radio' id='assessment-specific' value='custom' name='control-type' />
                                        <label for='assessment-specific'>Compliance Specific Controls</label>
                                    </div>
                                </div>
                                
                                <div id='control_type'>
                                    <div class="form-group" id='recommended_type'>
                                        <label class="help-label">
                                            RiskSafe Recommended Controls
                                        </label>
                                        <div class='c_type' id='control_selctor'>
                                            <div id='fetchControls' style='width:100%;'>
                                            <select name="existing_ct[]" id="existing_ct" class="form-control" required>
                                                <option>Select a compliance above to get recommended controls!</option>
                                            </select>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-primary" id="btn-append-rec-control">+ Add</button>
                                        </div>
                                        
                                        <div id='add-rec-control'></div>
                                    </div> 
                                    
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
                                    
                                    <div class="form-group" id='custom_type'>
                                        <label class="help-label">
                                            Assessment Specific Controls
                                        </label>
                                        <div class="add-customs">
                                            <input type="text" class="form-control" placeholder="Enter custom control description..." name='custom-control[]'>
                                            <button type="button" class="btn btn-sm btn-primary" id="btn-append-custom-control">+ Add</button>
                                        </div>
                                        <div id='add-customs-control'></div>
                                        <div class="custom-controls"></div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
									<label>Control Requirements: </label>
									<input name="control-req" type="text" maxlength="255" class="form-control" placeholder="Type Something..." required>

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
                                    <input type='radio' id='assessment-specific-t' value='custom' name='treatment-type' checked />
                                    <label for='assessment-specific-t'>Assessment Specific Treatments</label>
                                </div>
                                <div>
                                    <input type='radio' id='saved-t' value='saved' name='treatment-type' />
                                    <label for='saved-t'>Saved Custom Controls</label>
                                </div>
                            </div>
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
                            <div class="form-group" id='custom_type_t'>
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
                            <?php } ?>
                            
                            <?php }else{ ?>
                            <div class="card-body" style="margin-top:-20px;"> 
                                <?php if($moduleExists === false){ ?>
                                    Module Error: Selected module does not exist!! 
                                <?php }else{ ?>
                                    Select compliance module above!! 
                                <?php } ?>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="card-footer"></div>
                    </form>
                </div>
            </div>
            </section>
            <form id='getModule'>
                <input type='hidden' name='module_id' id='module_id' />
            </form>
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
    #saved_type,
    #custom_type{
        display:none;
    }
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
        .c_type{
            display:flex;
            gap:10px;
        }
        .c_type button{
            width:10% !important;
        }
        
        #saved_type_t{
        display:none;
    }
    </style>
    <script>
        let fieldHTMLTreatent = 'empty';
        $('#btn-append-rec-control').hide();
        
        $("#compliance_module").change(function (e) {
            var moduleValue = $("#compliance_module").val();
            if (moduleValue === "none") {
                window.location.assign("new-compliance?module="+null)
            } else {
                window.location.assign("new-compliance?module="+moduleValue)
            }
        });
        
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
                    
                    
                    // var riskValue = $("#module").val();
                    //           if (riskValue == "0") {
                    //              $("#control_selctor").html('Select Compliance Above To Get Recommended Control 1!!');
                    //           } else {
                    //               $("#risk_val").val();
                    //                 $("#risk_val").val(riskValue);
                                    
                    //                 $("#getControls").submit();
                                  
                    //           }
                    
                    
                }else if(val == 'saved'){
                    $('#recommended_type').hide();
                    $('#custom_type').hide();
                    $('#saved_type').show();
                }else if(val == 'custom'){
                    $('#recommended_type').hide();
                    $('#custom_type').show();
                    $('#saved_type').hide();
                }else{
                    $('#recommended_type').show();
                    $('#custom_type').hide();
                    $('#saved_type').hide();
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
                }else if(val == 'custom'){
                    $('#custom_type_t').show();
                    $('#saved_type_t').hide();
                }else{
                    $('#custom_type_t').hide();
                    $('#saved_type_t').show();
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
    </script>
</body>
</html>