<?php
    session_start();
    $file_dir = '../../';
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'auth/sign-in?r=/business/incidents');
        exit();
    }
    $message = [];
    include $file_dir.'layout/db.php';
    include $file_dir.'layout/admin__config.php';

    if(isset($_POST["create-incident"])){

        $title = sanitizePlus($_POST["title"]);
        $date = sanitizePlus($_POST["date"]);
        $reported = sanitizePlus($_POST["reported"]);
        $team = sanitizePlus($_POST["team"]);
        $financial = sanitizePlus($_POST["financial"]);
        $injuries = sanitizePlus($_POST["injuries"]);
        $complaints = sanitizePlus($_POST["complaints"]);
        $compliance = sanitizePlus($_POST["compliance"]);
        $descript = sanitizePlus($_POST["descript"]);
        $impact = sanitizePlus($_POST["impact"]);
        $priority = sanitizePlus($_POST["priority"]);
        $status = sanitizePlus($_POST["status"]);
        
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
        
		$in_id = secure_random_string(10);
        $query = "INSERT INTO as_incidents (in_user, treatment_type, treatment, in_title, in_date, in_reported, in_team, in_financial, in_injuries, in_complaints, in_compliance, in_descript, in_impact, in_priority, in_status, c_id, in_id) 
        VALUES ('$userId', '$treatment_type', '$treatment', '$title', '$date', '$reported', '$team', '$financial', '$injuries','$complaints', '$compliance','$descript', '$impact', '$priority', '$status', '$company_id', '$in_id')";
        $incidentCreated = $con->query($query);
        if ($incidentCreated) {
            #send notification
            $datetime = date("Y-m-d H:i:s");
            $notification_message = 'New Incident Registered';
            $notifier = $userId;
            $link = "admin/business/incidents?id=".$in_id;
            $type = 'incident';
            $case = 'new';
            $id = $in_id;
            $returnArray = createNotification($company_id, $notification_message, $datetime, $notifier, $link, $type, $case, $con, $sitee);
            header("Location: incidents?id=".$in_id);
        }else{
            array_push($message, 'Error 502: Error!!');
        }	
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Create New Incident | <?php echo $siteEndTitle; ?></title>
  <?php require $file_dir.'layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/footer.custom.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/admin.custom.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/bundles/bootstrap-daterangepicker/daterangepicker.css">
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
                    <form method="post">
                        <div class="card-header"></div>
                        <div class="card-body">
                            <?php require $file_dir.'layout/alert.php' ?>
                            <div class="card-header">
                                <h3 class="d-inline">Create New Incident</h3>
                                <a class="btn btn-primary btn-icon icon-left header-a" href="incidents"><i class="fas fa-arrow-left"></i> View All</a>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Case Title:</label>
                                    <input name="title" type="text" maxlength="255" class="form-control" placeholder="Enter case title" required>        
                                </div>
                                <div class="row custom-row">
                                    <div class="form-group col-lg-8 col-12">
                                        <label>Reported By:</label>
                                        <input name="reported" type="text" maxlength="255" class="form-control" placeholder="Enter person who reported the incident..." required>
                                    </div>
                                    <div class="form-group col-lg-4 col-12">
                                        <label>Date Occured:</label>
                                        <input name="date" type="date" maxlength="255" class="form-control datepicker" placeholder="Enter incident date occured..." value='<?php echo date('Y-m-d'); ?>' required>
                                    </div>
                                
                                    <div class="form-group col-lg-8 col-lg-12">
                                        <label>Team or Department:</label>
                                        <input name="team" type="text" maxlength="255" class="form-control" placeholder="Enter team or department..." required>
                                    </div>
                                    <div class="form-group col-lg-4 col-lg-12">
                                        <label>Financial Loss:</label>
                                        <input name="financial" type="text" maxlength="255" class="form-control" placeholder="" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Injuries:</label>
                                    <input name="injuries" type="text" maxlength="255" class="form-control" placeholder="" required>
                                </div>
                                <div class="form-group">
                                    <label>Complaints:</label>
                                    <input name="complaints" type="text" maxlength="255" class="form-control" placeholder="" required>
                                </div>
                                <div class="form-group">
                                    <label>Compliance breach:</label>
                                    <input name="compliance" type="text" maxlength="255" class="form-control" placeholder="" required>
                                </div>
                                <div class="form-group">
                                    <label>Description:</label>
                                    <textarea rows="3" class="form-control" placeholder="Enter description..." class="form-control" name="descript"></textarea>            
                                </div>
                                <div class="form-group">
                                    <label>Impact:</label>
                                    <textarea rows="3" class="form-control" placeholder="Enter impact..." class="form-control" name="impact"></textarea>            
                                </div>
                                <div class="row custom-row">
                                    <div class="form-group col-lg-6 col-12">
                                        <label>Priority:</label>
                                        <select class="form-control" name="priority" >
                                            <option value="High">High</option>
                                            <option value="Medium">Medium</option>
                                            <option value="Low">Low</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-6 col-12">
                                        <label>Status:</label>
                                        <select class="form-control" name="status" >
                                            <option value="Open">Open</option>
                                            <option value="Closed">Closed</option>
                                            <option value="In Progress">In Progress</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Treatment -->
                            <div class="card-header hh">
                                <h3 class="d-inline">Treatment Plans</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group" style='display:flex;gap:50px;'>
                                    <div>
                                        <input type='radio' id='assessment-specific-t' value='custom' name='treatment-type' checked />
                                        <label for='assessment-specific-t'>Incident Specific Treatments</label>
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
                                        Incident Specific Treatments
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
    								<button type="submit" class="btn btn-md btn-primary" name="create-incident">Create Incident</button>
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
        <?php require $file_dir.'layout/footer.php' ?>
        </footer>
        </div>
    </div>
    <?php require $file_dir.'layout/general_js.php' ?>
    <script src="<?php echo $file_dir; ?>assets/bundles/bootstrap-daterangepicker/daterangepicker.js"></script>
    <style>
        textarea{
            min-height: 120px !important;
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
    </style>
    <script>
        // $("#date").datepicker();
        
        $("#f93nfo1").click(function (e) {
          $("#fh4nfvf").load(" #fh4nfvf > *");
        });
        
        $("input[type='radio'][name='treatment-type']") // select the radio by its id
        .change(function(){ // bind a function to the change event
            if( $(this).is(":checked") ){ // check if the radio is checked
                var val = $(this).val(); // retrieve the value
                // alert(val);
                // return;
                
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
    </script>
</body>
</html>