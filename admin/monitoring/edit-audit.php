<?php
    session_start();
    $file_dir = '../../';

    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'login?r=/monitoring/audits');
        exit();
    }
  
    $message = [];
    include '../../layout/db.php';
    include '../ajax/audits.php';
    include '../../layout/admin_config.php';
    
    if (isset($_GET['id']) && isset($_GET['id']) !== "") {
        $toDisplay = true;   
        $id = sanitizePlus($_GET['id']);
        
        if(isset($_POST["update-audit"])){
              $company = sanitizePlus($_POST["company"]);
              $industry = sanitizePlus($_POST["industry"]);
              $team = sanitizePlus($_POST["team"]);
              $task = sanitizePlus($_POST["task"]);
              $assessor = sanitizePlus($_POST["assessor"]);
              $site = sanitizePlus($_POST["site"]);
              $date = sanitizePlus($_POST["date"]);
              $date = date("Y-m-d", strtotime($date));
              $time = sanitizePlus($_POST["time"]);
              $street = sanitizePlus($_POST["street"]);
              $building = sanitizePlus($_POST["building"]);
              $zipcode = sanitizePlus($_POST["zipcode"]);
              $state = sanitizePlus($_POST["state"]);
              $country = sanitizePlus($_POST["country"]);
              #if(isset($_POST["control"])){$control = sanitizePlus($_POST["control"]);}else{$control = '';};
              $existing = sanitizePlus($_POST["existing"]);
              $audi_treatment = sanitizePlus($_POST["audi_treatment"]);
              $Effectivness = sanitizePlus($_POST["Effectivness"]);
              $freq = sanitizePlus($_POST["freq"]);
              #if(isset($_POST["subControl"])){$subControl = sanitizePlus($_POST["subControl"]);}else{$subControl = $existing;};
              #$subControl = sanitizePlus($_POST["subControl"]);
              $next = getNext($date, $freq);
              $next = date("Y-m-d", strtotime($next));

              $query = "UPDATE as_auditcontrols SET con_company = '$company', con_industry = '$industry', con_team = '$team', con_task = '$task', con_assessor = '$assessor', con_site = '$site', con_date = '$date', con_time = '$time', con_street = '$street', con_building = '$building', con_zipcode = '$zipcode', con_state = '$state', con_country = '$country', aud_treatment = '$audi_treatment', con_effect = '$Effectivness', con_next = '$next', con_frequency = '$freq', con_control = '', subControl = '' WHERE aud_id = '$id' AND c_id = '$company_id'";
              $auditCreated = $con->query($query);
              if ($auditCreated) {
                #send notif and redirect
                array_push($message, 'Audit Updated Successfully!!');
              }else{
                array_push($message, 'Error 502: Error Updating Audit!!');
              }
            }
        $CheckIfAuditExist = "SELECT * FROM as_auditcontrols WHERE aud_id = '$id' AND c_id = '$company_id'";
        $AuditExist = $con->query($CheckIfAuditExist);
        if ($AuditExist->num_rows > 0) {	
            $aud_exist = true;
            $info = $AuditExist->fetch_assoc();
        }else{
          $aud_exist = false;
        }
    } else {
        $toDisplay = false;
    }
        
    $edit = true;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Edit Audit | <?php echo $siteEndTitle; ?></title>
  <?php require '../../layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/admin.custom.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/bundles/bootstrap-timepicker/css/bootstrap-timepicker.min.css">
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
              <?php if($toDisplay == true){ ?>
              <?php if ($aud_exist == true) { ?>
                <div class="card">
                    <form role="form" method="post">
                        <div class="card-header">
                            <a class="btn btn-primary btn-icon icon-left header-a" href="audits"><i class="fas fa-arrow-left"></i> Back</a>
                        </div>
                        <div class="card-body">
                            <?php require '../../layout/alert.php' ?>
                            <div class="card-header"><h3 class="subtitle">Audited Control Details:</h3></div>
                            <div class="card-body">
                                <div class="form-group">
                                  <label>Type of Control</label>
                                  <select name="existing" id="control-type" class="form-control" required>
                                    <option value="0">Please select type...</option>
                                    <?php echo listTypes(-1, $con); ?>
                                  </select>
                                </div>
                                <div class="form-group sub-control">
                                    <label>Sub Control</label>
                                    <div id="sub-control"></div>
                                </div>
                                
                                <div class="form-group">
                                    <label>Control Rationale:</label>
                                    <textarea name="rationale" class="form-control" placeholder="Control Rationale..." required><?php echo nl2br($info['con_observation']); ?></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label>Control Root Cause:</label>
                                    <textarea name="root_cause"  class="form-control" placeholder="Control Root Cause..." required><?php echo nl2br($info['con_rootcause']); ?></textarea>
                                </div>
                                
                                <div class="row custom-row">
                                <div class="form-group col-lg-6 col-12">
                                    <label>Control Effectiveness:</label>
                                    <select name="Effectivness" class="form-control" required>
                                        <option value="0" <?php if ($info["con_effect"] == 0) echo 'selected'; ?>>Unaccessed</option>
                                        <option value="1" <?php if ($info["con_effect"] == 1) echo 'selected'; ?>> Ineffective</option>
                                        <option value="2" <?php if ($info["con_effect"] == 2) echo 'selected'; ?>> Effective</option>
                                    </select>
                                </div>
                                <div class="form-group col-lg-6 col-12">
                                    <label>Frequency Of Application (FoA):</label>
                                    <select id="freq" class="form-control" name="freq">
                                        <option value="1" <?php if ($info["con_frequency"] == 1) echo "selected"; ?>>Daily Controls</option>
                                        <option value="2" <?php if ($info["con_frequency"] == 2) echo "selected"; ?>>Weekly Controls</option>
                                        <option value="3" <?php if ($info["con_frequency"] == 3) echo "selected"; ?>>Fort-Nightly Controls</option>
                                        <option value="4" <?php if ($info["con_frequency"] == 4) echo "selected"; ?>>Monthly Controls</option>
                                        <option value="5" <?php if ($info["con_frequency"] == 5) echo "selected"; ?>>Semi-Annually Controls</option>
                                        <option value="6" <?php if ($info["con_frequency"] == 6) echo "selected"; ?>>Annually Controls</option>
                                        <option value="7" <?php if ($info["con_frequency"] == 7) echo "selected"; ?>>As Required</option>
                                    </select>
                                </div>
                                </div>
                            </div>
                            <div class="card-body"></div>
                            <div class="card-header"><h3 class="subtitle">Audit Data:</h3></div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Company:</label>
                                    <input value="<?php echo $info["con_company"]; ?>" name="company" type="text" maxlength="255" class="form-control" placeholder="Company..." required>
                                </div>
                                <div class="form-group">
                                  <label>Industry Type:</label>
                                  <input value="<?php echo $info["con_industry"]; ?>" name="industry" type="text" maxlength="255" class="form-control" placeholder="Industry Type..." required>
                
                                </div>
                                <div class="form-group">
                                  <label>Business Unit or Team:</label>
                                  <input value="<?php echo $info["con_team"]; ?>" id="team" name="team" type="text" maxlength="255" class="form-control" placeholder="Business Unit or Team..." required>
                
                                </div>
                                <div class="form-group">
                                  <label>Process, Task or Activity:</label>
                                  <input value="<?php echo $info["con_task"]; ?>" id="task" name="task" type="text" maxlength="255" class="form-control" placeholder="Process, Task or Activity..." required>
                
                                </div>
                                
                                <div class="row custom-row">
                                <div class="form-group col-lg-6 col-12">
                                  <label>Audit Assessor:</label>
                                  <input value="<?php echo $info["con_assessor"]; ?>" id="assessor" name="assessor" type="text" maxlength="255" class="form-control" placeholder="Audit Assessor..." required>
                
                                </div>
                                <div class="form-group col-lg-3 col-12">
                                  <label>Date of Audit:</label>
                                  <input name="date" type="text" class="form-control datepicker readonly" placeholder="Select date..." required readonly style="cursor:pointer;" value="<?php echo date("Y-m-d", strtotime($info["con_date"])); ?>">
                                </div>
                                <div class="form-group col-lg-3 col-12">
                                  <label for="time">Time:</label>
                                  <input value="<?php echo $info["con_time"]; ?>" name="time" type="text" class="form-control timepicker" placeholder="Select time..." pattern="^([01]?[0-9]|2[0-3]):[0-5][0-9] ?(?:AM|PM|am|pm)?$" title="Please enter a valid time in the format HH:mm AM/PM or HH:mm (24-hour format)." required>
                                </div>
                                </div>
                                
                                <div class="form-group">
                                  <label>Site:</label>
                                  <input value="<?php echo $info["con_site"]; ?>" name="site" type="text" maxlength="255" class="form-control" placeholder="Site..." required>
                
                                </div>
            
                                <div class="form-group">
                                  <label>Street Address:</label>
                                  <input value="<?php echo $info["con_street"]; ?>" name="street" type="text" maxlength="255" class="form-control" placeholder="Street Address..." required>
                
                                </div>
                                <div class="form-group">
                                  <label>Building:</label>
                                  <input value="<?php echo $info["con_building"]; ?>" name="building" type="text" maxlength="255" class="form-control" placeholder="Building..." required>
                                  
                                </div>
                                
                                <div class="row custom-row">
                                  <div class="form-group col-12 col-lg-5">
                                    <label>Country:</label>
                                    <input value="<?php echo $info["con_country"]; ?>" name="country" type="text" maxlength="50" class="form-control" placeholder="Enter country name..." required>
                  
                                  </div>
                                  <div class="form-group col-12 col-lg-4">
                                    <label>State:</label>
                                    <input value="<?php echo $info["con_state"]; ?>" name="state" type="text" maxlength="50" class="form-control" placeholder="Enter state name..." required>
                  
                                  </div>
                                  <div class="form-group col-12 col-lg-3">
                                    <label>Zip Code:</label>
                                    <input value="<?php echo $info["con_zipcode"]; ?>" name="zipcode" type="text" maxlength="50" class="form-control" placeholder="Enter zip code..." required>
                  
                                  </div>
                                </div>
                  
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-md btn-primary" name="update-audit">Update Audit Details</button>
                                    <button type="button" class="btn btn-md btn-warning" id="btn_cancel">Cancel</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer"></div>
                    </form>
                    <form id="getSubControl" class="ajax-form">
                      <input type="hidden" name="selected" id="get_subcontrol">
                    </form>
                </div>
                <style>.main-footer{margin-top:0px;}</style>
                <?php }else{ ?>
                <div class="card">
                    <div class="card-body" style="display:flex;justify-content:center;align-items:center;min-height:500px;">
                        <div style="width:100%;min-height:400px;display:flex;justify-content:center;align-items:center;">
                            <div style="text-align: center;"> 
                                 <h3>Empty Data!!</h3>
                                 Audit Of Control Doesn't Exist!!,
                                 <p><a href="/help#data-error" class="btn btn-primary btn-icon icon-left mt-2"><i class="fas fa-arrow-left"></i> Help</a></p>
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
                                 <p><a href="audits" class="btn btn-primary btn-icon icon-left mt-2"><i class="fas fa-arrow-left"></i> Back</a></p>
                             </div>
                         </div>
                    </div>
                </div>
                <style>.main-footer{margin-top:0px;}</style>
                <?php } ?>
            </div>
            </section>
        </div>
        <?php require '../../layout/footer.php' ?>
        </footer>
        </div>
    </div>
    <?php require '../../layout/general_js.php' ?>
    <script src="<?php echo $file_dir; ?>assets/bundles/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
    <script src="<?php echo $file_dir; ?>assets/bundles/bootstrap-daterangepicker/daterangepicker.js"></script>
    <style>
        textarea{
            min-height: 120px !important;
        }
    </style>
    <script>
      $(".sub-control").hide();
      $("#control-type").change(function(e) { 
        var riskValue = $("#control-type").val();
        if (riskValue == '0') {
            $(".sub-control").hide();
        } else {
            $("#get_subcontrol").val();
            $("#get_subcontrol").val(riskValue);
            $("#getSubControl").submit();
        }
      });
      $("#getSubControl").submit(function (event) {
        // alert('first first stop!');
        event.preventDefault();
  
        var formValues = $(this).serialize();
  
        $.post("../ajax/audits", {
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
      
    </script>
</body>
</html>