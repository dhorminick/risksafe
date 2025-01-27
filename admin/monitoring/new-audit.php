<?php
    session_start();
    $file_dir = '../../';

    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'auth/sign-in?r=/monitoring/audits');
        exit();
    }
  
    $message = [];
    include $file_dir.'layout/db.php';
    include '../ajax/audits.php';
    include $file_dir.'layout/admin__config.php';

    if(isset($_POST["create-audit"])){
        $control_to_be_auditted = sanitizePlus($_POST["control_to_be_auditted"]);
        if(isset($_POST["control_to_be_auditted"]) == 'custom' || isset($_POST["control_to_be_auditted"]) == 'recommended'){
            $company = sanitizePlus($_POST["company"]);
			$industry = sanitizePlus($_POST["industry"]);
			$team = sanitizePlus($_POST["team"]);
			$task = sanitizePlus($_POST["task"]);
			$assessor = sanitizePlus($_POST["assessor"]);
			
			$root_cause = sanitizePlus($_POST["root_cause"]);
			$rationale = sanitizePlus($_POST["rationale"]);
			
			
			$site = sanitizePlus($_POST["site"]);
			$date = sanitizePlus($_POST["date"]);
            $date = date("Y-m-d", strtotime($date));

			$time = sanitizePlus($_POST["time"]);
			$street = sanitizePlus($_POST["street"]);
			$building = sanitizePlus($_POST["building"]);
			$zipcode = sanitizePlus($_POST["zipcode"]);
			$state = sanitizePlus($_POST["state"]);
			$country = sanitizePlus($_POST["country"]);
			
			$Effectivness = sanitizePlus($_POST["effectivness"]);
			$freq = sanitizePlus($_POST["freq"]);
     
          $next = getNext($date, $freq);
          $next = date("Y-m-d", strtotime($next));
          $aud_id = secure_random_string(10);
          
          if($control_to_be_auditted == 'recommended'){
              $control = sanitizePlus($_POST["existing"]);
              if(isset($_POST["subControl"])){
                  $subControl = sanitizePlus($_POST["subControl"]);
              }else{
                  $subControl = 'null';
              };
          }else{
              $control = sanitizePlus($_POST["control"]);
              $subControl = 'null';
          }
			
          $typeOfControl = $control;

      $queryAudit = "INSERT INTO as_auditcontrols 
      (con_user, con_company, con_industry, con_team, control_type, con_task, con_assessor, con_site, con_date, con_time, con_street, con_building, con_zipcode, con_state, con_country, con_control, con_effect, subControl, con_next, con_observation, con_rootcause, con_frequency, c_id, aud_id) 
      VALUES ('$userId', '$company', '$industry', '$team', '$control_to_be_auditted', '$task', '$assessor', '$site', '$date', '$time', '$street', '$building', '$zipcode', '$state', '$country', '$typeOfControl', '$Effectivness', '$subControl', '$next', '$rationale', '$root_cause', '$freq', '$company_id', '$aud_id')";
      $auditCreated = $con->query($queryAudit);
        if ($auditCreated) {
          #send notification
            $datetime = date("Y-m-d H:i:s");
            $notification_message = 'New Audit Created';
            $notifier = $userId;
            $link = "admin/monitoring/audit-details?id=".$aud_id;
            $type = 'audit';
            $case = 'new';
            $id = $aud_id;
            $returnArray = createNotification($company_id, $notification_message, $datetime, $notifier, $link, $type, $case, $con, $sitee);
            
            header("Location: audit-details?id=".$aud_id);
            exit();
        }else{
            array_push($message, 'Error 502: Error Creating Audit '.$con -> error);
        }
    }else{
        array_push($message, 'Error 402: Incomplete Data');
    }
    
    }

    $userName_audit = ucwords($_SESSION["u_name"]);
    $edit = true;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Create New Audit | <?php echo $siteEndTitle; ?></title>
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
        <!-- Main Content -->
        <div class="main-content">
            <section class="section">
            <div class="section-body">
                <div class="card">
                  <!--<div class="card-body">-->
                    <?php require $file_dir.'layout/alert.php' ?>
                    <form method="post">
                        <div class="card-body">
                            <div class="card-header" style='display:flex;justify-content:space-between;'>
                                <h3 class="subtitle">Audited Control Details:</h3>
                                <a href='audits' class="btn btn-primary btn-icon icon-left header-a"><i class="fas fa-arrow-left"></i> Back</a>
                            </div>
                            <div class="card-body">
                                <div class="form-group __ss__">
                                  <label>Select Control To Be Auditted:</label>
                                  <div style='display:flex;'>
                                      <div class='__ss_main'>
                                          <input type='radio' id='custom_audit' value='custom' checked name='control_to_be_auditted' />
                                          <label>Custom Controls</label>
                                      </div>
                                      <div class='__ss_main'>
                                          <input type='radio' id='monitoring_audit' value='monitoring' name='control_to_be_auditted' />
                                          <label>Monitorings</label>
                                      </div>
                                      <div class='__ss_main'>
                                          <input type='radio' id='recommended_audit' value='recommended' name='control_to_be_auditted' />
                                          <label>Recommended Controls</label>
                                      </div>
                                  </div>
                                </div>
                                <div class="form-group" id='form_reccommended'>
                                  <label>Select Reccommended Control:</label>
                                  <select name="existing" id="control-type" class="form-control" required>
                                    <option value="0" selected>Please select type...</option>
                                    <?php echo listTypes(-1, $con); ?>
                                  </select>
                                </div>
                                <div class="form-group" id='form_custom'>
                                  <label>Select Custom Control:</label>
                                    
                                    
                                    <div class="add-customs">
                                            <div style='width:100%;margin-right:5px;' id='fh4nfve_110'>
                                                <select name="control" class="form-control" required>
                                                    <?php echo listCompanyControl($company_id, $con); ?>
                                                </select>
                                            </div>
                                            <a href='../customs/new-control?redirect=true' target='_blank' class="btn btn-sm btn-primary" id='fn4h9nf' style='width: 15%;display:flex;justify-content:center;align-items:center;'>+ Create New</a>
                                            <buttton id='f93nfo0_110' class="btn btn-sm btn-primary" type='button' data-toggle="tooltip" title="Refresh Customs List" data-placement="left" style='margin-left:5px;display:flex;justify-content:center;align-items:center;font-size:20px;padding:0 10px;'><i class='fas fa-spinner'></i></buttton>
                                    </div>
                                    
                                    <div id='add-ctrl' style='margin-top:5px;'></div>
                                    
                                </div>
                                <div class="form-group" id='form_monitoring'>
                                  <label>Select Monitoring:</label>
                                    
                                    
                                    <div class="add-customs">
                                            <div style='width:100%;margin-right:5px;' id='fh4nfve_1100'>
                                                <select name="control" class="form-control" required>
                                                    <?php echo listMonitorings($company_id, $con); ?>
                                                </select>
                                            </div>
                                            <a href='../monitoring/new-monitoring?redirect=true' target='_blank' class="btn btn-sm btn-primary" id='fn4h9nff' style='width: 15%;display:flex;justify-content:center;align-items:center;'>+ Create New</a>
                                            <buttton id='f93nfo0_1100' class="btn btn-sm btn-primary" type='button' data-toggle="tooltip" title="Refresh Customs List" data-placement="left" style='margin-left:5px;display:flex;justify-content:center;align-items:center;font-size:20px;padding:0 10px;'><i class='fas fa-spinner'></i></buttton>
                                    </div>
                                    
                                </div>
                                
                                <div class="form-group">
                                    <label>Control Rationale:</label>
                                    <textarea name="rationale" class="form-control" placeholder="Control Rationale..." required></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label>Control Root Cause:</label>
                                    <textarea name="root_cause"  class="form-control" placeholder="Control Root Cause..." required></textarea>
                                </div>
                                
                                <div class="row custom-row">
                                <div class="form-group col-lg-6 col-12">
                                    <label>Control Effectiveness:</label>
                                    <select name="effectivness" class="form-control" required>
                                        <option value="0" selected>Not Assessed</option>
                                        <option value="2">Effective</option>
                                        <option value="1">Not Effective</option>
                                    </select>
                                </div>
                                <div class="form-group col-lg-6 col-12">
                                    <label>Frequency Of Application (FoA):</label>
                                    <select id="freq" class="form-control" name="freq" required>
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
                            </div>
                            <div class="card-body"></div>
                            <div class="card-header"><h3 class="subtitle">Audit Data:</h3></div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Company</label>
                                    <input value="<?php echo $company_name; ?>" name="company" type="text" class="form-control" placeholder="Company..." required>
                                </div>
                                <div class="form-group">
                                  <label>Industry Type</label>
                                  <input name="industry" type="text" class="form-control" placeholder="Industry Type..." required>
                
                                </div>
                                <div class="form-group">
                                  <label>Business Unit / Team</label>
                                  <input id="team" name="team" type="text" class="form-control" placeholder="Business Unit or Team..." required>
                
                                </div>
                                <div class="form-group">
                                  <label>Process, Task or Activity</label>
                                  <input id="task" name="task" type="text" class="form-control" placeholder="Process, Task or Activity..." required>
                
                                </div>
                                
                                <div class="row custom-row">
                                <div class="form-group col-lg-6 col-12">
                                  <label>Audit Assessor</label>
                                  <input value="<?php echo $userName_audit; ?>" id="assessor" name="assessor" type="text" class="form-control" placeholder="Audit Assessor..." required>
                
                                </div>
                                
                                <div class="form-group col-lg-3 col-12">
                                  <label>Date of Audit</label>
                                  <input name="date" id="date" type="text" class="form-control datepicker" placeholder="Audit Date..." required style="cursor:pointer;">
                
                                </div>

                                <div class="form-group col-lg-3 col-12">
                                  <label for="time">Time</label>
                                  <input name="time" id="time" type="text" class="form-control timepicker" placeholder="Audit Time..." pattern="^([01]?[0-9]|2[0-3]):[0-5][0-9] ?(?:AM|PM|am|pm)?$" title="Please enter a valid time in the format HH:mm AM/PM or HH:mm (24-hour format)." required>
                                  <div id="timeFeedback" class="invalid-feedback">
                                    Please enter a valid time in the format HH:mm AM/PM or HH:mm (24-hour format).
                                  </div>
                                </div>
                                </div>
                                
                                <div class="form-group">
                                  <label>Site</label>
                                  <input name="site" type="text" class="form-control" placeholder="Site..." required>
                
                                </div>
                                
                                <div class="row custom-row">
                                <div class="form-group col-lg-6 col-12">
                                  <label>Street Address</label>
                                  <input name="street" type="text" class="form-control" placeholder="Street Address..." required>
                
                                </div>
                                <div class="form-group col-lg-6 col-12">
                                  <label>Building</label>
                                  <input name="building" type="text" class="form-control" placeholder="Building..." required>
                
                                </div>
                                </div>
                                
                                <div class="row custom-row">
                                  <div class="form-group col-12 col-lg-5">
                                    <label>Country</label>
                                    <input name="country" type="text" class="form-control" placeholder="Country..." required>
                  
                                  </div>
                                  <div class="form-group col-12 col-lg-4">
                                    <label>State</label>
                                    <input name="state" type="text" class="form-control" placeholder="State..." required>
                  
                                  </div>
                                  <div class="form-group col-12 col-lg-3">
                                    <label>Zip Code</label>
                                    <input name="zipcode" type="text" class="form-control" placeholder="Zip Code..." required>
                  
                                  </div>
                                </div>
                  
                            </div>
                            <div class="card-body">
                                <div class="form-group text-right">
                                    <button type="submit" class="btn btn-md btn-icon icon-left btn-primary" name="create-audit"><i class='fas fa-check'></i> Save Audit</button>
                                    <button type="button" class="btn btn-md btn-warning" id="btn_cancel">Cancel</button>

                                </div>
                            </div>
                        </div>
                        <div class="card-footer"></div>
                    </form>
                    <form id="getSubControl" class="ajax-form">
                      <input type="hidden" name="selected" id="get_subcontrol">
                    </form>
                  <!--</div>-->
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
        textarea{
            min-height: 120px !important;
        }
        .card{
          padding: 10px;
        }
        .__ss__ div.__ss_main + div.__ss_main{
         margin-left:20px;   
        }
        .__ss__ div.__ss_main label{
         margin-right:10px;
         margin-bottom:5px;
         font-weight: 400;
        }
        .__ss__ div.__ss_main input{
            margin-bottom: 6px;
            margin-right: 7px;
        }
        
        .__ss__ div.__ss_main{
            display:flex;align-items:center;
        }
        
        .__ss__{
            display:flex;
            flex-direction:column;
        }
    </style>
    <script>
    $("#f93nfo0_110").click(function (e) {
          $("#fh4nfve_110").load(" #fh4nfve_110 > *");
        });
        
        $("#f93nfo0_1100").click(function (e) {
          $("#fh4nfve_1100").load(" #fh4nfve_1100 > *");
        });
        
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
        $("input[type=radio]").click(function () {
            var type = $(this).attr('id');
            if($(this).prop("checked") && type == 'custom_audit') { 
                $("#form_reccommended").hide();
                $("#form_custom").show();
                $("#form_monitoring").hide();
                $('.sub-control').hide();
            }else if($(this).prop("checked") && type == 'recommended_audit') { 
                $("#form_reccommended").show();
                $('.sub-control').show();
                $("#form_custom").hide();
                $("#form_monitoring").hide();
            }else if($(this).prop("checked") && type == 'monitoring_audit') { 
                $("#form_reccommended").hide();
                $('.sub-control').show();
                $("#form_custom").hide();
                $("#form_monitoring").show();
            }else{
                $("#form_reccommended").hide();
                $("#form_custom").show();
                $('.sub-control').hide();
                $("#form_monitoring").hide();
            }
        });
        $("#form_reccommended").hide();
        $("#form_custom").show();
        $("#form_monitoring").hide();
        
        // incidents
            var _maxFieldTeatmen = 10; //Input fields increment limitation
            var _adButtonTreatmen = $('#btn-append-ctrl'); //Add button selector
            var _wraperTreatmen = $('#add-ctrl'); //Input field wrapperTreatment
            var _fieldHTLTreatmen = '<div style="display:flex;justify-content:center;align-items:center;gap:5px;margin-top:5px;"> <select name="control[]" class="form-control" required> <?php echo listCompanyControl($company_id, $con); ?> </select> <buttton class="btn btn-sm btn-primary remove_button_t" type="button" style="margin-left:5px;display:flex;justify-content:center;align-items:center;font-size:20px;padding:12px 10px;"><i class="fas fa-minus"></i></buttton></div>';
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