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

    if (isset($_GET['id']) && isset($_GET['id']) !== "") {
        $toDisplay = true;   
        $id = sanitizePlus($_GET['id']);
        $CheckIfIncidentExist = "SELECT * FROM as_incidents WHERE in_id = '$id' AND c_id = '$company_id'";
        $IncidentExist = $con->query($CheckIfIncidentExist);
        if ($IncidentExist->num_rows > 0) {	
            $in_exist = true;
			$info = $IncidentExist->fetch_assoc();

            if (isset($_POST['update-incident'])) {
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
                
                $query = "UPDATE as_incidents SET in_title = '$title', treatment_type = '$treatment_type', treatment = '$treatment', in_date = '$date', in_reported = '$reported', in_team = '$team', in_financial = '$financial', in_injuries = '$injuries', in_complaints = '$complaints', in_compliance = '$compliance', in_descript = '$descript', in_impact = '$impact', in_priority = '$priority', in_status = '$status' WHERE c_id = '$company_id' AND in_id = '$id'";
                $incidentUpdated = $con->query($query);
                if ($incidentUpdated) {
                    array_push($message, 'Incident Updated Successfully!!');
                }else{
                    array_push($message, 'Error Updating Incident Details!!');
                }	
            }
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
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Edit Incident | <?php echo $siteEndTitle; ?></title>
  <?php require $file_dir.'layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/footer.custom.css">
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
                <?php if($toDisplay == true){ ?>
                <?php if ($in_exist == true) { ?>
                <div class="card">
                    <form method="post">
                        <div class="card-header"></div>
                        <div class="card-body">
                            <?php require $file_dir.'layout/alert.php' ?>
                            <div class="card-header">
                                <h3 class="d-inline">Edit Incident</h3>
                                <a class="btn btn-primary btn-icon icon-left header-a" href="incidents?id=<?php echo $info['in_id']; ?>"><i class="fas fa-arrow-left"></i> Back</a>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Case Title</label>
                                    <input value="<?php echo $info["in_title"];?>" name="title" type="text" maxlength="255" class="form-control" placeholder="Enter case title" required>        
                                </div>
                                <div class='row custom-row'>
                                <div class="form-group col-lg-8 col-12">
                                    <label>Reported By</label>
                                    <input value="<?php echo $info["in_reported"];?>" name="reported" type="text" maxlength="255" class="form-control" placeholder="Enter person who reported the incident..." required>
                                </div>
                                <div class="form-group col-lg-4 col-12">
                                    <label>Date Occured</label>
                                    <input value="<?php if($info["in_date"] = '0000-00-00'){echo date('Y-m-d');}else{echo date("Y-m-d", strtotime($info["in_date"]));} ?>" name="date" type="text" maxlength="255" class="form-control datepicker" placeholder="Enter incident date occured..." required>
                                </div>
                                
                                <div class="form-group col-lg-8 col-12">
                                    <label>Team or Department</label>
                                    <input value="<?php echo $info["in_team"];?>" name="team" type="text" maxlength="255" class="form-control" placeholder="Enter team or department..." required>
                                </div>
                                <div class="form-group col-lg-4 col-12">
                                    <label>Financial Loss</label>
                                    <input value="<?php echo $info["in_financial"];?>" name="financial" type="text" maxlength="255" class="form-control" placeholder="" required>
                                </div>
                                </div>
                                <div class="form-group">
                                    <label>Injuries</label>
                                    <input value="<?php echo $info["in_injuries"];?>" name="injuries" type="text" maxlength="255" class="form-control" placeholder="" required>
                                </div>
                                <div class="form-group">
                                    <label>Complaints</label>
                                    <input value="<?php echo $info["in_complaints"];?>" name="complaints" type="text" maxlength="255" class="form-control" placeholder="" required>
                                </div>
                                <div class="form-group">
                                    <label>Compliance breach</label>
                                    <input value="<?php echo $info["in_compliance"];?>" name="compliance" type="text" maxlength="255" class="form-control" placeholder="" required>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea rows="3" class="form-control" placeholder="Enter description..." class="form-control" name="descript"><?php echo $info["in_descript"];?></textarea>            
                                </div>
                                <div class="form-group">
                                    <label>Impact</label>
                                    <textarea rows="3" class="form-control" placeholder="Enter impact..." class="form-control" name="impact"><?php echo $info["in_impact"];?></textarea>            
                                </div>
                                <div class='row custom-row'>
                                <div class="form-group col-lg-6 col-12">
                                    <label>Priority</label>
                                    <select class="form-control" name="priority" >
                                        <option value="High" <?php if($info["in_status"] == "High") echo "selected='selected'"; ?> >High</option>
                                        <option value="Medium" <?php if($info["in_status"] == "Medium") echo "selected='selected'"; ?> >Medium</option>
                                        <option value="Low" <?php if($info["in_status"] == "Low") echo "selected='selected'"; ?> >Low</option>
                                    </select>
                                </div>
                                <div class="form-group col-lg-6 col-12">
                                    <label>Status</label>
                                    <select class="form-control" name="status" >
                                        <option value="Open" <?php if($info["in_status"] == "Open") echo "selected='selected'"; ?> >Open</option>
                                        <option value="Closed" <?php if($info["in_status"] == "Closed") echo "selected='selected'"; ?> >Closed</option>
                                        <option value="In Progress" <?php if($info["in_status"] == "In Progress") echo "selected='selected'"; ?> >In Progress</option>
                                    </select>
                                </div>
                                </div>
                                
                                <!-- Treatment -->
                                <div class="card-header hh">
                                    <h3 class="d-inline">Treatment Plans</h3>
                                </div>
                                <div class="card-body">
                                    <div class="form-group" style='display:flex;gap:50px;'>
                                        <div>
                                            <input type='radio' id='assessment-specific-t' value='custom' name='treatment-type' <?php if($info['treatment_type'] == 'custom'){ echo 'checked'; } ?> />
                                            <label for='assessment-specific-t'>Incident Specific Treatments</label>
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
                                                Incident Specific Treatments
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
                                                Incident Specific Treatments
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
                        
                        
                            </div>
                            <div class="card-body">
                                <div class="form-group">
									<button type="submit" class="btn btn-md btn-primary" name="update-incident">Update Incident</button>
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
                            <div style="display:flex;justify-content:center;align-items:center;width:100%;margin:10px 0px;">Incident Doesn't Exist!!</div>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <?php }else{ ?>
                <div class="card">
                    <div class="card-body" style="display:flex;justify-content:center;align-items:center;min-height:500px;">
                        <div>
                            <h3 style="display:flex;justify-content:center;align-items:center;width:100%;">Error 402!!</h3>
                            <div style="margin:10px;display:flex;justify-content:center;align-items:center;width:100%;">Missing Parameters!!</div>
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
    <script src="<?php echo $file_dir; ?>assets/bundles/bootstrap-daterangepicker/daterangepicker.js"></script>
    <style>
        textarea{
            min-height: 120px !important;
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