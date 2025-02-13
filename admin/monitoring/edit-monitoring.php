<?php
    session_start();
    $file_dir = '../../';

    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'auth/sign-in?r=/monitoring/all-monitoring');
        exit();
    }
  
    $message = [];
    $toDisplay = false;
    
    include $file_dir.'layout/db.php';
    include $file_dir.'layout/admin__config.php';
    include '../ajax/customs.php';

    if(isset($_POST["update-control"]) && isset($_POST["id"])){
        
        $title = sanitizePlus($_POST["title"]);
        $number = sanitizePlus($_POST["number"]);
        $description = htmlentities(trim($_POST["description"]));
        $effectiveness = sanitizePlus($_POST["effectiveness"]);
        $frequency = sanitizePlus($_POST["frequency"]);
        
        $key = sanitizePlus($_POST["key"]);
        $type = sanitizePlus($_POST["type"]);
        $nature = sanitizePlus($_POST["nature"]);
        
        $owner = sanitizePlus($_POST["owner"]);

        $steps = serialize($_POST["steps"]);

        $outcome = sanitizePlus($_POST["outcome"]);
        $audit_outcome = sanitizePlus($_POST["audit-outcome"]);
        $next = sanitizePlus($_POST["next"]);
        
        $m_id = sanitizePlus($_POST["id"]);
        $techniques = serialize($_POST["techniques"]);
        
        $treatment_type = sanitizePlus($_POST["treatment-type"]);
        if($treatment_type === 'saved'){
                    $treatment = serialize($_POST["saved-treatment"]);
                }else if($treatment_type === 'custom'){
                    $treatment = serialize($_POST["custom-treatment"]);
                }else if($treatment_type == 'na'){
                    $treatment = 'Not Assessed!';
                }else{
                    $treatment = 'Not Assessed!';
                }
                
        

        $query = "UPDATE as_monitoring SET title = '$title', treatment_type = '$treatment_type', treatment = '$treatment', description = '$description', effectiveness = '$effectiveness', frequency = '$frequency', keys = '$key', type = '$type', nature = '$nature', owner = '$owner', steps = '$steps', outcome = '$outcome', audit_outcome = '$audit_outcome', next = '$next', techniques = '$techniques'
        WHERE m_id = '$m_id' AND c_id = '$company_id'";
        $created = $con->query($query);
        if ($created) {
            array_push($message, 'Monitoring updated successfully!!');
        }else{
            array_push($message, 'Error 502: Error Creating Monitoring!!');
        }
    }
    
    if (isset($_GET['id']) && isset($_GET['id']) !== "") {
        $toDisplay = true;   
        $id = sanitizePlus($_GET['id']);
        $CheckIfIncidentExist = "SELECT * FROM as_monitoring WHERE m_id = '$id' AND c_id = '$company_id'";
        $IncidentExist = $con->query($CheckIfIncidentExist);
        if ($IncidentExist->num_rows > 0) {	
            $in_exist = true;
            $info = $IncidentExist->fetch_assoc();
		}else{
            $in_exist = false;
        }
    } else {
        $toDisplay = false;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta
   content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Edit Monitoring | <?php echo $siteEndTitle; ?></title>
  <?php require $file_dir.'layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/footer.custom.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/admin.custom.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/bundles/bootstrap-timepicker/css/bootstrap-timepicker.min.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/bundles/bootstrap-daterangepicker/daterangepicker.css">
</head>

<body>
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <?php require $file_dir.'layout/header.php' ?>
        <?php require $file_dir.'layout/sidebar_admin.php' ?>
        <!-- Main
         Content -->
        <div class="main-content">
            <section class="section">
            <div class="section-body">
                <div class="card">
                  <div class="card-body">
                    <?php require $file_dir.'layout/alert.php' ?>
                    <?php if($toDisplay === true){ ?>
                    <form method="post">
                        <div class="card-header">
                            <h3 class="subtitle d-inline">Edit Monitoring Details</h3>
                            <a href='all-monitoring' class="btn btn-primary btn-icon icon-left header-a"><i class="fas fa-arrow-left"></i> Back</a>
                        </div>
                        <div class="card-bodyy">
                            <div class="card-body">
                                <div class="row custom-row">
                                    <div class="form-group col-lg-3 col-12">
                                        <label>Control Number:</label>
                                        <input name="number" type="text" class="form-control" value='<?php echo $info['number']; ?>' required>
                                    </div>
                                    <div class="form-group col-lg-9 col-12">
                                        <label>Control Title:</label>
                                        <input name="title" type="text" class="form-control" value='<?php echo $info['title']; ?>' placeholder="Enter Control Title..." required>
                                    </div>
                                </div>
                                
                                <input name="id" type="hidden" value='<?php echo $info['m_id']; ?>' required>

                                <div class="form-group">
                                    <label>Control Description:</label>
                                    <textarea name="description" class="form-control" placeholder="Enter Control Description"><?php echo $info['description']; ?></textarea>
                                </div>
                                
                                <div class="row custom-row">
                                    <div class="form-group col-lg-4 col-12">
                                        <label>Control:</label>
                                        <select name="key" class="form-control" required>
                                            <option value="Key" <?php if($info['keys'] === 'Key'){echo ' selected';}; ?>>Key</option>
                                            <option value="No Key" <?php if($info['keys'] === 'No Key'){echo ' selected';}; ?>>No Key</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-4 col-12">
                                        <label>Control Type:</label>
                                        <select class="form-control" name="type" required>
                                            <option value="Automated" <?php if($info['type'] === 'Automated'){echo ' selected';}; ?>>Automated</option>
                                            <option value="Manual" <?php if($info['type'] === 'Manual'){echo ' selected';}; ?>>Manual</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-4 col-12">
                                        <label>Control Nature:</label>
                                        <select class="form-control" name="nature" required>
                                            <option value="Preventative" <?php if($info['nature'] === 'Preventative'){echo ' selected';}; ?>>Preventative</option>
                                            <option value="Detective" <?php if($info['nature'] === 'Detective'){echo ' selected';}; ?>>Detective</option>
                                            <option value="Corrective" <?php if($info['nature'] === 'Corrective'){echo ' selected';}; ?>>Corrective</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="row custom-row">
                                    <div class="form-group col-lg-4 col-12">
                                        <label>Control Testing Techniques:</label>
                                        <select name="techniques" class="form-control" required>
                                            <option value="Observation" <?php if($info['techniques'] === 'Observation'){echo ' selected';}; ?>>Observation</option>
                                            <option value="Inspection" <?php if($info['techniques'] === 'Inspection'){echo ' selected';}; ?>>Inspection</option>
                                            <option value="Re-Performance" <?php if($info['techniques'] === 'Re-Performance'){echo ' selected';}; ?>>Re-Performance</option>
                                            <option value="Enquiry" <?php if($info['techniques'] === 'Enquiry'){echo ' selected';}; ?>>Enquiry</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-8 col-12">
                                        <label>Control Owner:</label>
                                        <input name="owner" type="text" class="form-control" placeholder="Enter Control Owner..." value='<?php echo $info['owner']; ?>' required>
                                    </div>
                                </div>

                                <div class="row custom-row">
                                    <div class="form-group col-lg-6 col-12">
                                        <label>Effectiveness</label>
                                        <select name="effectiveness" class="form-control" id='effectiveness' required>
                                            <option value="1" <?php if($info['effectiveness'] === '1' || $info['effectiveness'] === 1){echo ' selected';}; ?>>Effective</option>
                                            <option value="2" <?php if($info['effectiveness'] === '2' || $info['effectiveness'] === 2){echo ' selected';}; ?>>InEffective</option>
                                            <option value="3" <?php if($info['effectiveness'] === '3' || $info['effectiveness'] === 3){echo ' selected';}; ?>>Unassessed</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-6 col-12">
                                        <label>Frequency</label>
                                        <select class="form-control" name="frequency" required>
                                            <option value="1" <?php if($info['frequency'] === '1' || $info['frequency'] === 1){echo ' selected';}; ?>>Daily Controls</option>
                                            <option value="2" <?php if($info['frequency'] === '2' || $info['frequency'] === 2){echo ' selected';}; ?>>Weekly Controls</option>
                                            <option value="3" <?php if($info['frequency'] === '3' || $info['frequency'] === 3){echo ' selected';}; ?>>Fort-Nightly Controls</option>
                                            <option value="4" <?php if($info['frequency'] === '4' || $info['frequency'] === 4){echo ' selected';}; ?>>Monthly Controls</option>
                                            <option value="5" <?php if($info['frequency'] === '5' || $info['frequency'] === 5){echo ' selected';}; ?>>Semi-Annually Controls</option>
                                            <option value="6" <?php if($info['frequency'] === '6' || $info['frequency'] === 6){echo ' selected';}; ?>>Annually Controls</option>
                                            <option value="7" <?php if($info['frequency'] === '7' || $info['frequency'] === 7){echo ' selected';}; ?>>As Required</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="help-label">
                                        Control Test Steps:
                                    </label>
                                    <div class="add-customs">
                                        <?php 
                                            $treatments = unserialize($info['treatment']);
                                            $treatment_more = $treatments;
                                            $t___count = 0;
                                            foreach($treatments as $treatment){
                                                $t___count++;
                                                echo "<div class='add-customs' style='width:100%;'>";
                                                echo '<input type="text" class="form-control" value='.ucfirst($treatment).' placeholder="Enter step" name="steps[]">';
                                                echo '<button type="button" class="btn btn-sm btn-primary" id="btn-append-test-steps">+ Add</button>';
                                                echo "</div>";
                                                break;
                                            }
                                        ?>
                                    </div>
                                    <div id='add-test-steps'>
                                        <?php 
                                            unset($treatment_more[0]);
                                            foreach($treatment_more as $_treatment){
                                        ?>
                                        <div style="display:flex;justify-content:center;align-items:center;gap:5px;margin-top:5px;"> 
                                            <input type="text" class="form-control" value='<?php echo ucfirst($_treatment); ?>' placeholder="Enter step" style="margin-top:5px;" name="steps[]"  required/>
                                            <buttton class="btn btn-sm btn-primary remove_button_t rmv_btn" style='margin-left:5px;display:flex;justify-content:center;align-items:center;font-size:20px;padding:12px 10px;' type="button"><i class="fas fa-minus"></i></buttton>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label>Control Outcome:</label>
                                    <textarea name="outcome" class="form-control" placeholder="Enter Control Description"><?php echo $info['outcome']; ?></textarea>
                                </div>
                                
                                <div class="row custom-row">
                                    <div class="form-group col-lg-8 col-12">
                                        <label>Audit Outcome:</label>
                                        <input name="audit-outcome" type="text" class="form-control" value='<?php echo $info['audit_outcome']; ?>' required>
                                    </div>
                                    <div class="form-group col-lg-4 col-12">
                                        <label>Next Audit:</label>
                                        <input name="next" type="date" class="form-control" value='<?php echo $info['next']; ?>' required>
                                    </div>
                                </div>
                            </div>
                            
                            <style>
                                    <?php if($info['effectiveness'] !== 2 || $info['effectiveness'] !== '2'){ ?>
                                        .show_treatment{
                                            display:none;
                                        }
                                        <?php } ?>
                                </style>
                                
                                <!-- Treatment -->
                                <div class="card-body show_treatment">
                                    <div class="form-group" style='display:flex;gap:50px;'>
                                        <div>
                                            <input type='radio' id='assessment-specific-t' value='custom' name='treatment-type' <?php if($info['treatment_type'] == 'custom'){ echo 'checked'; } ?> />
                                            <label for='assessment-specific-t'>Control Specific Treatments</label>
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
                                                        echo __listCompanyTreatmentSelected_New($company_id, $treatment, $con);
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
                                                    <?php echo __listCompanyTreatmentSelected_New($company_id, $_treatment, $con); ?>
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
                                                    <?php echo __listCompanyTreatment($company_id, $con); ?>
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
                                                Monitoring Specific Treatments
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
                                                Monitoring Specific Treatments
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
                        
                        
                             <div class="card-body">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-md btn-primary" name="update-control">Update Control</button>
                                    <button type="button" class="btn btn-md btn-warning" id="btn_cancel">Cancel</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer"></div>
                    </form>
                    <?php }else{ ?>
                    <div class="card">
                        <div style="width:100%;min-height:400px;display:flex;justify-content:center;align-items:center;">
                            <div style="text-align: center;"> 
                                 <h3>Data Error!!</h3>
                                 Control ID does not exist, or was not specified!
                                 <p><a href="all-monitoring" class="btn btn-primary btn-icon icon-left mt-2"><i class="fas fa-arror-left"></i> Back</a></p></div>
                         </div>
                    </div>
                    <?php } ?>
                  </div>
                </div>
            </div>
            </section>
        </div>
        <?php require $file_dir.'layout/footer.php' ?>
        </footer>
        </div>
    </div>
    <?php require $file_dir.'layout/general_js.php' ?>
    <script src="<?php echo $file_dir; ?>assets/bundles/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
    <script src="<?php echo $file_dir; ?>assets/bundles/bootstrap-daterangepicker/daterangepicker.js"></script>
    <style>
    .add-customs.customs input {
     width: 100%; 
}
        textarea{
            min-height: 120px !important;
        }
        .card{
          padding: 10px;
        }
        .note{
            border-left: 7px solid var(--custom-primary);
            background-color: var(--card-border);
            color: black;
            padding: 10px;
            margin: 0px 0px 20px 0px;
            border-radius: 0px 5px 5px 0px;
        }
         #saved_type,
    #custom_type{
        display:none;
    }
    #saved_type_t{
        display:none;
    }
    #na_type_t{
        display:none;
    }
    .show_treatment{
        display:none;
    }
    </style>
    <script>
    
    
    
    $("#f93nfo1_").click(function (e) {
          $("#control-type").load(" #control-type > *");
        });
        
         $("#f93nfo1").click(function (e) {
          $("#fh4nfvf").load(" #fh4nfvf > *");
        });
        
        
      $(".sub-control").hide();
    //   $("#control-type").change(function(e) { 
    //     var riskValue = $("#control-type").val();
    //     if (riskValue == '2') {
    //         $(".sub-control").hide();
    //     } else {
    //         $("#get_subcontrol").val();
    //         $("#get_subcontrol").val(riskValue);
    //         $("#getSubControl").submit();
    //     }
    //   });
      
      
      $("#effectiveness").change(function(e) { 
        var riskValue = $(this).val();
        if (riskValue === 2 || riskValue === '2') {
             $(".show_treatment").show();
        } else {
            $(".show_treatment").hide();
        }
      });
      
      
      $("#getSubControl").submit(function (event) {
        // alert('first first stop!');
        event.preventDefault();
  
        var formValues = $(this).serialize();
  
        $.post("../ajax/audits.php", {
            getSubControl: formValues,
        }).done(function (data) {
            // alert(data);
            $("#sub-control").html(data);
            $(".sub-control").show();
            setTimeout(function () {
                $("#getSubControl input").val('');
            }, 1000);
            
            // alert('second stop!');
        });
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
        
        // saved treatments
            var maxFieldTeatmen = 10; //Input fields increment limitation
            var adButtonTreatmen = $('#btn-append-saved-treatment'); //Add button selector
            var wraperTreatmen = $('#add-saved-treatment'); //Input field wrapperTreatment
            var fieldHTLTreatmen = '<div style="display:flex;justify-content:center;align-items:center;gap:5px;margin-top:5px;"> <select name="saved-treatment[]" class="form-control" required> <?php echo __listCompanyTreatment($company_id, $con); ?></select> <buttton class="btn btn-sm btn-primary remove_button_t" type="button" style="margin-left:5px;display:flex;justify-content:center;align-items:center;font-size:20px;padding:12px 10px;"><i class="fas fa-minus"></i></buttton></div>';
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
            
            
            // custom treatments
            var _maxFieldTeatmen = 10; //Input fields increment limitation
            var _adButtonTreatmen = $('#btn-append-custom-treatment'); //Add button selector
            var _wraperTreatmen = $('#add-customs-treatment'); //Input field wrapperTreatment
            var _fieldHTLTreatmen = '<div style="display:flex;justify-content:center;align-items:center;gap:5px;margin-top:5px;"> <input type="text" class="form-control" placeholder="Enter custom control description..." name="custom-treatment[]"> <buttton class="btn btn-sm btn-primary remove_button_t" type="button" style="margin-left:5px;display:flex;justify-content:center;align-items:center;font-size:20px;padding:12px 10px;"><i class="fas fa-minus"></i></buttton></div>';
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
            
            // test steps
            var __maxFieldTeatmen = 10; //Input fields increment limitation
            var __adButtonTreatmen = $('#btn-append-test-steps'); //Add button selector
            var __wraperTreatmen = $('#add-test-steps'); //Input field wrapperTreatment
            var __fieldHTLTreatmen = '<div style="display:flex;justify-content:center;align-items:center;gap:5px;margin-top:5px;"> <input type="text" class="form-control" placeholder="Enter step..." name="steps[]"> <buttton class="btn btn-sm btn-primary remove_button_t" type="button" style="margin-left:5px;display:flex;justify-content:center;align-items:center;font-size:20px;padding:12px 10px;"><i class="fas fa-minus"></i></buttton></div>';
            var __x_Treatmens = 1; //Initial field counter is 1
            
            // Once add button is clicked
            $(__adButtonTreatmen).click(function(){
                //Check maximum number of input fields
                if(__x_Treatmens < __maxFieldTeatmen){ 
                    __x_Treatmens++; //Increase field counter
                    $(__wraperTreatmen).append(__fieldHTLTreatmen); //Add field html
                }else{
                    alert('A maximum of '+__maxFieldTeatmen+' fields are allowed to be added. ');
                }
            });
            
            // Once remove button is clicked
            $(__wraperTreatmen).on('click', '.remove_button_t', function(e){
                e.preventDefault();
                $(this).parent('div').remove(); //Remove field html
                __x_Treatmens--; //Decrease field counter
            });
      
    </script>
</body>
</html>