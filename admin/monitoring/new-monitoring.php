<?php
    session_start();
    $file_dir = '../../';

    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'auth/sign-in?r=/monitoring/new-monitoring');
        exit();
    }
  
    $message = [];
    include $file_dir.'layout/db.php';
    include $file_dir.'layout/admin__config.php';
    include '../ajax/customs.php';

    if(isset($_POST["create-control"])){
        
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
        
        $m_id = secure_random_string(10);
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
                
        
        $cus_date = date("Y-m-d");
        
        $query = "INSERT INTO as_monitoring
        ( title, treatment_type, treatment, description, effectiveness, frequency, c_id, m_id, created_at, keys, type, nature, owner, steps, outcome, audit_outcome, next, techniques) VALUES 
        ('$title', '$treatment_type', '$treatment', '$description', '$effectiveness', '$frequency', '$company_id', '$m_id', '$cus_date', '$key', '$type', '$nature', '$owner', '$steps', '$outcome', '$audit_outcome', '$next', '$techniques')";
        $created = $con->query($query);
        if ($created) {
            #send notification
            $datetime = date("Y-m-d H:i:s");
            $notification_message = 'New Monitoring Created';
            $notifier = $userId;
            $link = "admin/monitoring/all-monitoring?id=".$m_id;
            $type = 'monitoring';
            $case = 'new';
            $id = $m_id;

            $returnArray = createNotification($company_id, $notification_message, $datetime, $notifier, $link, $type, $case, $con, $sitee);
            
            header("Location: all-monitoring?id=".$m_id);
            exit();
        }else{
            array_push($message, 'Error 502: Error Creating Monitoring!!');
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta
   content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Create New Monitoring | <?php echo $siteEndTitle; ?></title>
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
                    
                    <form method="post">
                        <div class="card-header">
                            <h3 class="subtitle d-inline">Monitoring Details</h3>
                            <a href='all-monitoring' class="btn btn-primary btn-icon icon-left header-a"><i class="fas fa-arrow-left"></i> Back</a>
                        </div>
                        <div class="card-bodyy">
                            <div class="card-body">
                                <!--<div class="form-group">-->
                                <!--  <label>Incident</label>-->
                                  
                                <!--  <div class="add-customs">-->
                                <!--        <div style='width:100%;margin-right:5px;' id='fh4nfvf'>-->
                                <!--        <select name="category" id="control-type" class="form-control" required>-->
                                <!--            <?php #echo __listCompanyIncidents($company_id, $con); ?>-->
                                <!--        </select>-->
                                <!--        </div>-->
                                <!--        <a href='../business/new-incident?redirect=true' target='_blank' class="btn btn-sm btn-primary" style='width: 15%;display:flex;justify-content:center;align-items:center;'>+ Create New</a>-->
                                <!--        <buttton id='f93nfo1_' class="btn btn-sm btn-primary" type='button' data-toggle="tooltip" title="Refresh List" data-placement="left" style='margin-left:5px;display:flex;justify-content:center;align-items:center;font-size:20px;padding:0 10px;'><i class='fas fa-spinner'></i></buttton>-->
                                <!--    </div>-->
                                <!--</div>-->
                                
                                <div class="row custom-row">
                                    <div class="form-group col-lg-3 col-12">
                                        <label>Control Number:</label>
                                        <input name="number" type="text" class="form-control" value="<?php echo 'CT-'.strtoupper(secure_random_string(5)); ?>" readonly required>
                                    </div>
                                    <div class="form-group col-lg-9 col-12">
                                        <label>Control Title:</label>
                                        <input name="title" type="text" class="form-control" placeholder="Enter Control Title..." required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Control Description:</label>
                                    <textarea name="description" class="form-control" placeholder="Enter Control Description"></textarea>
                                </div>
                                
                                <div class="row custom-row">
                                    <div class="form-group col-lg-4 col-12">
                                        <label>Control:</label>
                                        <select name="key" class="form-control" required>
                                            <option value="Key" selected>Key</option>
                                            <option value="No Key">No Key</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-4 col-12">
                                        <label>Control Type:</label>
                                        <select class="form-control" name="type" required>
                                            <option value="Automated" selected>Automated</option>
                                            <option value="Manual">Manual</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-4 col-12">
                                        <label>Control Nature:</label>
                                        <select class="form-control" name="nature" required>
                                            <option value="Preventative" selected>Preventative</option>
                                            <option value="Detective">Detective</option>
                                            <option value="Corrective">Corrective</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="row custom-row">
                                    <div class="form-group col-lg-4 col-12">
                                        <label>Control Testing Techniques:</label>
                                        <select name="techniques" class="form-control" required>
                                            <option value="Observation" selected>Observation</option>
                                            <option value="Inspection">Inspection</option>
                                            <option value="Re-Performance">Re-Performance</option>
                                            <option value="Enquiry">Enquiry</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-8 col-12">
                                        <label>Control Owner:</label>
                                        <input name="owner" type="text" class="form-control" placeholder="Enter Control Owner..." required>
                                    </div>
                                </div>

                                <div class="row custom-row">
                                    <div class="form-group col-lg-6 col-12">
                                        <label>Effectiveness</label>
                                        <select name="effectiveness" class="form-control" id='effectiveness' required>
                                            <option value="1" selected>Effective</option>
                                            <option value="2">InEffective</option>
                                            <option value="3">Unassessed</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-6 col-12">
                                        <label>Frequency</label>
                                        <select class="form-control" name="frequency" required>
                                            <option value="1" selected>Daily Controls</option>
                                            <option value="2">Weekly Controls</option>
                                            <option value="3">Fort-Nightly Controls</option>
                                            <option value="4">Monthly Controls</option>
                                            <option value="5">Semi-Annually Controls</option>
                                            <option value="6">Annually Controls</option>
                                            <option value="7">As Required</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="help-label">
                                        Control Test Steps:
                                    </label>
                                    <div class="add-customs customs">
                                        <div style='width:100%;margin-right:5px;'>
                                            <input name="steps[]" type="text" class="form-control" placeholder="Enter Step..." required>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-primary" id="btn-append-test-steps" style='margin-left:5px;'>+ Add</button>
                                    </div>
                                    
                                    <div id='add-test-steps' style='margin-top:5px;'></div>
                                </div>
                                
                                <div class="form-group">
                                    <label>Control Outcome:</label>
                                    <textarea name="outcome" class="form-control" placeholder="Enter Control Description"></textarea>
                                </div>
                                
                                <div class="row custom-row">
                                    <div class="form-group col-lg-8 col-12">
                                        <label>Audit Outcome:</label>
                                        <input name="audit-outcome" type="text" class="form-control" required>
                                    </div>
                                    <div class="form-group col-lg-4 col-12">
                                        <label>Next Audit:</label>
                                        <input name="next" type="date" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Treatment -->
                            <div class="card-body show_treatment">
                                <div class="form-group" style='display:flex;gap:50px;'>
                                    <div>
                                        <input type='radio' id='assessment-specific-t' value='custom' name='treatment-type' checked />
                                        <label for='assessment-specific-t'>Monitoring Specific Treatments</label>
                                    </div>
                                    <div>
                                        <input type='radio' id='saved-t' value='saved' name='treatment-type' />
                                        <label for='saved-t'>Saved Custom Controls</label>
                                    </div>
                                    <div>
                                        <input type='radio' id='na-t' value='na' name='treatment-type' />
                                        <label for='na-t'>N/A</label>
                                    </div>
                                </div>
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
                                <div class="form-group" id='custom_type_t'>
                                    <label class="help-label">
                                        Control Specific Treatments
                                    </label>
                                    <div class="add-customs">
                                        <input type="text" class="form-control" placeholder="Enter custom control description..." name='custom-treatment[]'>
                                        <button type="button" class="btn btn-sm btn-primary" id="btn-append-custom-treatment">+ Add</button>
                                    </div>
                                    <div id='add-customs-treatment'></div>
                                </div>
                                <div class="form-group" id='na_type_t' style='margin-top:-20px;'></div>
                                
                            </div>
                        
                             <div class="card-body">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-md btn-primary" name="create-control">Save Control</button>
                                    <button type="button" class="btn btn-md btn-warning" id="btn_cancel">Cancel</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer"></div>
                    </form>
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