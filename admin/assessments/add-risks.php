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
            // $riskType = $assessment_details["as_type"];
            $riskType = $_SESSION['risk_industry'];
			
			$toEdit = true;
            $selrisk = -1;
  
        }else{
            $ass_exist = false;
        }

        if (isset($_POST['update-assessment-details']) && isset($_POST["control-type"]) && isset($_POST["treatment-type"])) {
            $error = false;
            
            $assessmentId = sanitizePlus($_POST["assessment"]);

            if($assessmentId == $ass_Id){
                // as_assessment_new
                
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
                
                if($treatment_type == 'saved'){
                    $treatment = serialize($_POST["saved-treatment"]);
                }else if($treatment_type == 'custom'){
                    $treatment = serialize($_POST["custom-treatment"]);
                }else{
                    $error = true;
                    array_push($message, 'Error 402: Treatment Type Error!!');
                }
                
                if($error == false){
                    # code...
                    $ri_id = secure_random_string(10);
                    $date = date("Y-m-d", strtotime($date));
                    $created = date("Y-m-d");
                    $rating = calculateRating($likelihood, $consequence, $con);
                    
                    $InsertRisk = "INSERT INTO as_assessment_new (industry, risk_type, risk, sub_risk, description, likelihood, consequence, rating, control_type, control, control_effectiveness, control_action, treatment_type, treatment, owner, due_date, created_on, c_id, assessment, risk_id) 
                    VALUES ('$riskType', '$risk_type', '$risk', '$hazard', '$descript', '$likelihood', '$consequence', '$rating', '$control_type', '$control', '$effectiveness', '$actiontake', '$treatment_type', '$treatment', '$owner', '$date', '$created', '$company_id', '$assessmentId', '$ri_id')";
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
                        
                        #redir
                        header("Location: risks?id=".$ri_id);
                        exit();
                    } else {
                        array_push($message, 'Error 502: Error 01!!');
                    }
                }
                
            }else{
                array_push($message, 'Error 402: Assessment Error!!');
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
                                <label>Risk</label>
                                <div id="risk_div">
                                    <?php echo listRisksNew($riskType, $selrisk, $company_id, $con); ?>
                                </div>
                            </div>
                            <div class="form-group hazard">
                                <label>Risk Sub Category</label>
                                <div class='hazard_empty' style='font-weight:400;margin-top:5px;'>Select A Risk Above...</div>
                                <div id="hazard_div">
                                    <?php #echo listHazards($cathazard, $selhazard, $con) ;?>
                                </div>
                                
                                                        
                            </div>
                            <input type='hidden' name='risk_type' id='selected_risk_type' />
                            <div class="form-group">
                                <label>Risk Description</label>
                                <textarea name="descript" rows="4" class="form-control" id='risk-description' placeholder="Enter risk description..." required><?php if(isset($_POST['descript'])){echo $_POST['descript'];} ?></textarea>
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
                                <?php echo listLikelihood(2 , $con);?>
                            </div>
                            <div class="form-group col-lg-4 col-12">
                                <label>Consequence</label>
                                <?php echo listConsequence(3 , $con); ?>
                            </div>
                            <div class="form-group risk-rating col-lg-4 col-12">
                                <label>Risk Rating</label>
                                <div id="rating"><span class="rat_high"><i class="fas fa-exclamation"></i> High</span></div>
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
                                    <label for='assessment-specific'>Assessment Specific Controls</label>
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
                                            <option value="null" selected>None Selected</option>
                                            <?php echo listControl($company_id, $con); ?>
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
                        
                        <!-- Submit -->
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
            <form id="getHazard" class="ajax-form">
                <input type="hidden" name="category" id="get_hazard">
            </form>
            <form id="getDescription" class="ajax-form">
                <input type="hidden" name="category" id="get_desc">
            </form>
            
            
            
            <form id="getCustomHazard" class="ajax-form">
                <input type="hidden" name="risk" id="get_customhazard">
            </form>
            
            
            
            <form id="getRating" class="ajax-form">
                <input type="hidden" name="consequence" id="get_risk_consequence">
                <input type="hidden" name="likelihood" id="get_risk_likelihood">
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
        $("#fetchControls").html('<span style="font-weight:400;">Select Risk Above To Get Recommended Control 2!!</span>');
        $('#btn-append-rec-control').hide();
        let fieldHTMLTreatent = 'empty';
        let userRisks = [<?php $query="SELECT * FROM as_customrisks WHERE c_id = '$company_id'"; $result=$con->query($query); if ($result->num_rows > 0) { while($row=$result->fetch_assoc()){ ?> "<?php echo $row['risk_id']; ?>", <?php } }else{ echo 'empty'; } ?> ]
        
        $("#getControls").submit(function (event) {
          event.preventDefault();
        
          var formValues = $(this).serialize();
          $("#fetchControls").val('Fetching Controls...');
          $.post("../ajax/assessment", {
            getControls: formValues,
          }).done(function (data) {
          // $("#control_selctor").html('<div id="fetchControls" style="width:100%;"></div>');
            fieldHTMLTreatent = '<div style="display:flex;justify-content:center;align-items:center;gap:5px;margin-top:5px;"> '+data+' <buttton class="btn btn-sm btn-primary remove_button_t" type="button" style="margin-left:5px;display:flex;justify-content:center;align-items:center;font-size:20px;padding:12px 10px;"><i class="fas fa-minus"></i></buttton></div>';
            $("#fetchControls").html(data);
            $('#btn-append-rec-control').show();
            setTimeout(function () {
              $("#getControls input").val("");
            }, 0);
          });
        });
        
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

        $("#f93nfo0_11").click(function (e) {
          $("#fh4nfve_11").load(" #fh4nfve_11 > *");
        });
        
        $("#risk").change(function (e) {
            var riskValue = $("#risk").val();
            
            if (riskValue == "0") {
                  $(".hazard_empty").show();
                  $("#hazard_div").html('');
                  $("#risk-description").val('');
                  $('#owner').val('');
                  
                  $("#fetchControls").html('Select Risk Above To Get Recommended Control 3!!');
            	  $('#btn-append-rec-control').hide();
            	  $('#selected_risk_type').val('');
                  // $("#control_selctor").html('Select Risk Above To Get Recommended Control 3!!');
              } else {
              // $("#control_selctor").html('<div id="fetchControls" style="width:100%;"></div>');
                	
                if(userRisks !== 'empty' && userRisks.includes(riskValue)){
                    $('#selected_risk_type').val('custom');
                    $('#get_customhazard').val(riskValue);
                    
                    $('#getCustomHazard').submit();
                    $("#fetchControls").html('No Recommended Controls For Custom Risk!!');
                    $('#btn-append-rec-control').hide();
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
                }
              }
            
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
                    
                    
                    var riskValue = $("#risk").val();
                    var riskType = $('#selected_risk_type').val();
                          if(riskType == "custom"){
                              $("#control_selctor").html('No Recommended Controls For Custom Risk!!');
                          }else{
                            
                              if (riskValue == "0") {
                                 $("#control_selctor").html('Select Risk Above To Get Recommended Control 1!!');
                              } else {
                                  $("#risk_val").val();
                                    $("#risk_val").val(riskValue);
                                    
                                    $("#getControls").submit();
                                  
                              }
                          }
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
            
            
    </script>
    
    <style>
    /*#recommended_type,*/
    #saved_type,
    #custom_type{
        display:none;
    }
    #saved_type_t{
        display:none;
    }
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