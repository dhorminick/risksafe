<?php
    session_start();
    $file_dir = '../../';

    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'login?r=/monitoring/new-audit');
        exit();
    }
  
    $message = [];
    include '../../layout/db.php';
    include '../ajax/audits.php';
    include '../../layout/admin_config.php';
    // include '../../layout/user_details.php';

    if(isset($_POST["create-audit"])){
 
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
      if(isset($_POST["control"])){$control = sanitizePlus($_POST["control"]);}else{$control = '';};
			$existing = sanitizePlus($_POST["existing"]);
			#$audi_treatment = sanitizePlus($_POST["audi_treatment"]);
			$Effectivness = sanitizePlus($_POST["effectivness"]);
			$freq = sanitizePlus($_POST["freq"]);
      if(isset($_POST["subControl"])){$subControl = sanitizePlus($_POST["subControl"]);}else{$subControl = $existing;};
      #$subControl = sanitizePlus($_POST["subControl"]);
      $next = getNext($date, $freq);
      $next = date("Y-m-d", strtotime($next));
      $aud_id = secure_random_string(10);

      $typeOfControl = '';
      if ($control != '') {
        $typeOfControl = $control;
      } else {
        $typeOfControl = $existing;
      }

      $queryAudit = "INSERT INTO as_auditcontrols 
      (con_user, con_company, con_industry, con_team, con_task, con_assessor, con_site, con_date, con_time, con_street, con_building, con_zipcode, con_state, con_country, con_control, con_effect, subControl, con_next, con_observation, con_rootcause, con_frequency, c_id, aud_id) 
      VALUES ('$userId', '$company', '$industry', '$team', '$task', '$assessor', '$site', '$date', '$time', '$street', '$building', '$zipcode', '$state', '$country', '$typeOfControl', '$Effectivness', '$subControl', '$next', '$rationale', '$root_cause', '$freq', '$company_id', '$aud_id')";
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
            $returnArray = sendNotificationUser($company_id, $notification_message, $datetime, $notifier, $link, $type, $case, $id, $con);
            header("Location: audit-details?id=".$aud_id);
            exit();
        }else{
            array_push($message, 'Error 502: Error Creating Audit '.$con -> error);
        }
    }

    $userName_audit = 'Me';
    $edit = true;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Create New Audit | <?php echo $siteEndTitle; ?></title>
  <?php require '../../layout/general_css.php' ?>
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
        <?php require '../../layout/header.php' ?>
        <?php require '../../layout/sidebar_admin.php' ?>
        <!-- Main Content -->
        <div class="main-content">
            <section class="section">
            <div class="section-body">
                <div class="card">
                  <div class="card-body">
                    <?php require '../../layout/alert.php' ?>
                    <form method="post">
                        <div class="card-header">
                          <a href='audits' class="btn btn-primary btn-icon icon-left header-a"><i class="fas fa-arrow-left"></i> Back</a>
                        </div>
                        <div class="card-body">
                            <div class="card-header"><h3 class="subtitle">Audited Control Details:</h3></div>
                            <div class="card-body">
                                <div class="form-group">
                                  <label>Type of Control</label>
                                  <select name="existing" id="control-type" class="form-control" required>
                                    <option value="0" selected>Please select type...</option>
                                    <?php echo listTypes(-1, $con); ?>
                                  </select>
                                </div>
                                <div class="form-group sub-control">
                                    <label>Sub Control</label>
                                    <div id="sub-control"></div>
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

                
                
                                <div class="form-group">
                                  <label>Street Address</label>
                                  <input name="street" type="text" class="form-control" placeholder="Street Address..." required>
                
                                </div>
                                <div class="form-group">
                                  <label>Building</label>
                                  <input name="building" type="text" class="form-control" placeholder="Building..." required>
                
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
                                <div class="form-group">
                                    <button type="submit" class="btn btn-md btn-primary" name="create-audit">Save Audit</button>
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
                </div>
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
        .card{
          padding: 10px;
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