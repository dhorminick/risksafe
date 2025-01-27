<?php
    session_start();
    $file_dir = '../../';

    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'login?r=/customs/controls');
        exit();
    }
  
    $message = [];
    include $file_dir.'layout/db.php';
    include $file_dir.'layout/admin__config.php';
    include '../ajax/customs.php';
    
    if(isset($_POST["update-control"]) && isset($_POST['c__id'])){
                
                $id = sanitizePlus($_POST["c__id"]);
                
                $title = sanitizePlus($_POST["title"]);
                $description = htmlentities(trim($_POST["description"]));
                $effectiveness = sanitizePlus($_POST["effectiveness"]);
                $frequency = sanitizePlus($_POST["frequency"]);
                $category = sanitizePlus($_POST["category"]);
                
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
                
                $query = "UPDATE as_customcontrols SET title = '$title', treatment_type = '$treatment_type', treatment = '$treatment', description = '$description', effectiveness = '$effectiveness', frequency = '$frequency', category = '$category' WHERE control_id = '$id' AND c_id = '$company_id'";
                $customCreated = $con->query($query);
                if ($customCreated) {
                    array_push($message, 'Control Details Updated Successfully!!');
                }else{
                  array_push($message, 'Error 502: Error Updating Control!!');
                }
            }
            
    if (isset($_GET['id']) && isset($_GET['id']) !== "") {
        $toDisplay = true;   
        $id = sanitizePlus($_GET['id']);
        $CheckIfCustomExist = "SELECT * FROM as_customcontrols WHERE control_id = '$id' AND c_id = '$company_id'";
        $CustomExist = $con->query($CheckIfCustomExist);
        if ($CustomExist->num_rows > 0) {	
            $aud_exist = true;
            $info = $CustomExist->fetch_assoc();

            

        }else{
          $aud_exist = false;
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
  <title>Edit Custom Controls | <?php echo $siteEndTitle; ?></title>
  <?php require $file_dir.'layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/admin.custom.css">
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
              <?php if ($aud_exist == true) { ?>
                <div class="card" style='padding:10px;'>
                    <form role="form" method="post">
                        <div class="card-bodyy">
                            <?php require $file_dir.'layout/alert.php' ?>
                            <div class="card-header">
                                <h3 class="subtitle">Control Details</h3>
                                <a class="btn btn-primary btn-icon icon-left header-a" href="controls?id=<?php echo $info['control_id']; ?>"><i class="fas fa-arrow-left"></i> Back</a>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                  <label>Control Category</label>
                                  <select name="category" id="control-type" class="form-control" required>
                                    <option value="0">No Category</option>
                                    <?php echo listTypes($info["category"], $con); ?>
                                  </select>
                                </div>
                                <input type='hidden' name='c__id' value='<?php echo $info['control_id']; ?>' />

                                <div class="form-group">
                                    <label>Control Title:</label>
                                    <input name="title" value="<?php echo $info["title"]; ?>" type="text" class="form-control" placeholder="Enter Control Title..." required>
                                </div>

                                <div class="form-group">
                                    <label>Control Description:</label>
                                    <textarea rows='3' class="form-control" name="description" class="form-control"  placeholder="Enter Control Description">
                                        <?php echo html_entity_decode($info["description"]); ?>
                                    </textarea>
                                </div>

                                <div class="row custom-row">
                                    <div class="form-group col-lg-6 col-12">
                                        <label>Effectiveness</label>
                                        <select name="effectiveness" class="form-control" required>
                                            <option value="1" <?php if ($info['effectiveness'] == 1) echo "selected"; ?>>Effective</option>
                                            <option value="2" <?php if ($info['effectiveness'] == 2) echo "selected"; ?>>InEffective</option>
                                            <option value="3" <?php if ($info['effectiveness'] == 3) echo "selected"; ?>>Unassessed</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-6 col-12">
                                        <label>Frequency</label>
                                        <select class="form-control" name="frequency" required>
                                            <option value="1" <?php if ($info['frequency'] == 1) echo "selected"; ?>>Daily Controls</option>
                                            <option value="2" <?php if ($info['frequency'] == 2) echo "selected"; ?>>Weekly Controls</option>
                                            <option value="3" <?php if ($info['frequency'] == 3) echo "selected"; ?>>Fort-Nightly Controls</option>
                                            <option value="4" <?php if ($info['frequency'] == 4) echo "selected"; ?>>Monthly Controls</option>
                                            <option value="5" <?php if ($info['frequency'] == 5) echo "selected"; ?>>Semi-Annually Controls</option>
                                            <option value="6" <?php if ($info['frequency'] == 6) echo "selected"; ?>>Annually Controls</option>
                                            <option value="7" <?php if ($info['frequency'] == 7) echo "selected"; ?>>As Required</option>
                                        </select>
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
                                                Control Specific Treatments
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
                                                Control Specific Treatments
                                            </label>
                                            <div class="add-customs">
                                                <input type="text" class="form-control" placeholder="Enter custom control description..." name='custom-treatment[]'>
                                                <button type="button" class="btn btn-sm btn-primary" id="btn-append-custom-treatment">+ Add</button>
                                            </div>
                                            <div id='add-customs-treatment'></div>
                                        </div>
                                        <?php } ?>
                                        
                                        
                                        <div class="form-group" id='na_type_t'> </div>
                                
                                    
                                    
                                    <div class='row custom-row'>
                                        <div class="form-group col-lg-8 col-12">
                                            <label>Action Owner</label>
                                            <input name="owner" id="owner" type="text" maxlength="100" class="form-control" placeholder="Enter action owner..." required value="<?php echo ucwords($info['owner']);  ?>">
                                        </div>
                                        <div class="form-group col-lg-4 col-12">
                                            <label>Due Date</label>
                                            <input name="date" id="date" type="text" maxlength="100" class="form-control datepicker" placeholder="Select date..." required style="cursor:pointer;" value="<?php echo date("Y-m-d", strtotime($info['due_date'])); ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-group text-right">
                                    <button type="submit" class="btn btn-md btn-primary" name="update-control">Update Control Details</button>
                                    <button type="button" class="btn btn-md btn-warning" id="btn_cancel">Cancel</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer"></div>
                    </form>
                </div>
                <style>.main-footer{margin-top:0px;}</style>
                <?php }else{ ?>
                <div class="card">
                    <div class="card-body" style="display:flex;justify-content:center;align-items:center;min-height:500px;">
                        <div style="width:100%;min-height:400px;display:flex;justify-content:center;align-items:center;">
                            <div style="text-align: center;"> 
                                 <h3>Empty Data!!</h3>
                                 Custom Control Doesn't Exist!!,
                                 <p><a href="new-control" class="btn btn-primary btn-icon icon-left mt-2"><i class="fas fa-plus"></i> Create New Control</a></p>
                             </div>
                         </div>
                    </div>
                </div>
                <style>.main-footer{margin-top:0px;}</style>
                <?php } ?>
                <?php }else{ ?>
                <div class="card">
                    <div class="card-body" style="display:flex;justify-content:center;align-items:center;min-height:500px;">
                        <div style="width:100%;min-height:400px;display:flex;justify-content:center;align-items:center;">
                            <div style="text-align: center;"> 
                                 <h3>Empty Data!!</h3>
                                 Missing Parameters,
                                 <p><a href="controls" class="btn btn-primary btn-icon icon-left mt-2"><i class="fas fa-arrow-left"></i> Back</a></p>
                             </div>
                         </div>
                    </div>
                </div>
                <style>.main-footer{margin-top:0px;}</style>
                <?php } ?>
            </div>
            </section>
        </div>
        <?php require $file_dir.'layout/footer.php' ?>
        </footer>
        </div>
    </div>
    <?php require $file_dir.'layout/general_js.php' ?>
    <style>
        textarea{
            min-height: 120px !important;
        }
    </style>
    
    <script>
    
    $("#f93nfo1_").click(function (e) {
          $("#control-type").load(" #control-type > *");
        });
        
       $("#f93nfo1").click(function (e) {
          $("#fh4nfvf").load(" #fh4nfvf > *");
        });
        
        $("#effectiveness").change(function(e) { 
        var riskValue = $(this).val();
        if (riskValue === 2 || riskValue === '2') {
             $(".show_treatment").show();
        } else {
            $(".show_treatment").hide();
        }
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