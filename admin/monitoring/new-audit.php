<?php
    $file_dir = '../../';
    $message = [];
    include '../../layout/db.php';
    include '../../layout/admin_config.php';

    if(isset($_POST["create-assessment"])){
        $update_userid = $_SESSION["userid"];
        $update_type = $_REQUEST["type"];
        $update_team = $_REQUEST["team"];
        $update_task = $_REQUEST["task"];
        $update_description = $_REQUEST["description"];
        $update_owner = $_REQUEST["owner"];
        $update_date = $_REQUEST["date"];
        $update_assessor = $_REQUEST["assessor"];
        $update_approval = $_REQUEST["approval"]; 
    }
    $edit = true;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Otika - Admin Dashboard Template</title>
  <?php require '../../layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/footer.custom.css">
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
                    <form role="form" class="form" id="form" action="../controller/users.php" method="post">
                        <div class="card-header"></div>
                        <div class="card-body">
                            <div class="card-header"><h3 class="subtitle">Control Details</h3></div>
                            <div class="card-body">
                                <div class="form-group">
									<label>Type of Control</label>
									<select name="existing" id="typeOfControl" onchange="toggleTextbox()" class="form-control" required>
										<option selected>Please select type...</option>
                                        <?php
                                            $selected = -1;
                                            $query="SELECT * FROM as_types ORDER BY idtype";
                                            $result=$con->query($query);
                                            $response="";
                                            while ($row=$result->fetch_assoc()) {
                                                $response.='<option value="' . $row["idtype"] . '"';
                                                if ($selected==$row["idtype"]) $response.=' selected';
                                                $response.='>' . $row["ty_name"] . '</option>';
                                            }
									
                                            echo $response;
										?>
									</select>
								</div>
                                <div class="form-group">
                                    <label>Sub Control</label>
                                    <select name="subControl" id="subNamesDropdown" class="form-control"></select>

                                </div>

                                <div class="form-group">
                                    <label>Create Custom Control</label>
                                    <input value="<?php if ($edit) echo $info["con_control"]; ?>" name="control" type="text" id="createCustomControl" maxlength="255" class="form-control" placeholder="Enter control name...">
                                    </div>
                                    <div class="form-group">
                                    <label>Treatment</label>
                                    <input value="<?php if ($edit) echo $info["aud_treatment"]; ?>" name="audi_treatment" type="text" id="control" maxlength="255" class="form-control" placeholder="Enter Treatment name..." required>

                                </div>
                                <div class="form-group">
                                    <label>Effectiveness</label>
                                    <select name="Effectivness" class="form-control" required>
                                        <option value="3" <?php if ($edit && $info['con_effect'] == 3) echo "selected"; ?>>Not selected</option>
                                        <option value="2" <?php if ($edit && $info['con_effect'] == 2) echo "selected"; ?>>Effective</option>
                                        <option value="1" <?php if ($edit && $info['con_effect'] == 1) echo "selected"; ?>>Not effective</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Frequency</label>
                                    <select id="freq" class="form-control" name="freq">
                                        <option value="0">Not set</option>
                                        <option value="1">Every day</option>
                                        <option value="7">Every week</option>
                                        <option value="30">Every month</option>
                                    </select>
                                </div>
                            </div>
                            <div class="card-body"></div>
                            <div class="card-header"><h3 class="subtitle">Audit Details</h3></div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Company</label>
                                    <input value="<?php if ($edit) echo $info["con_company"]; ?>" name="company" type="text" maxlength="255" class="form-control" placeholder="Enter company name..." required>
                                </div>
                                <div class="form-group">
                                  <label>Industry Type</label>
                                  <input value="<?php if ($edit) echo $info["con_industry"]; ?>" name="industry" type="text" maxlength="255" class="form-control" placeholder="Enter industry type..." required>
                
                                </div>
                                <div class="form-group">
                                  <label>Business Unit / Team</label>
                                  <input value="<?php if ($edit) echo $info["con_team"]; ?>" id="team" name="team" type="text" maxlength="255" class="form-control" placeholder="Enter business unit/team name..." required>
                
                                </div>
                                <div class="form-group">
                                  <label>Process / Task / Activity</label>
                                  <input value="<?php if ($edit) echo $info["con_task"]; ?>" id="task" name="task" type="text" maxlength="255" class="form-control" placeholder="Enter process/task/activity name..." required>
                
                                </div>
                                <div class="form-group">
                                  <label>Assessor Name</label>
                                  <input value="<?php if ($edit) echo $info["con_assessor"]; ?>" id="assessor" name="assessor" type="text" maxlength="255" class="form-control" placeholder="Enter assessor name..." required>
                
                                </div>
                                <div class="form-group">
                                  <label>Site</label>
                                  <input value="<?php if ($edit) echo $info["con_site"]; ?>" name="site" type="text" maxlength="255" class="form-control" placeholder="Enter site name..." required>
                
                                </div>
                                <div class="form-group">
                                  <label>Date of Audit</label>
                                  <input name="date" id="date" type="text" maxlength="20" class="form-control readonly" placeholder="Select date..." required readonly style="cursor:pointer;" value="<?php if ($edit) { echo date("m/d/Y", strtotime($info["con_date"])); } else { echo date("m/d/Y"); } ?>">
                
                                </div>
                                <div class="form-group">
                                  <label for="time">Time</label>
                                  <input value="<?php if ($edit) echo $info["con_time"]; ?>" name="time" id="time" type="text" maxlength="8" class="form-control" placeholder="Enter time..." pattern="^([01]?[0-9]|2[0-3]):[0-5][0-9] ?(?:AM|PM|am|pm)?$" title="Please enter a valid time in the format HH:mm AM/PM or HH:mm (24-hour format)." required>
                                  <div id="timeFeedback" class="invalid-feedback">
                                    Please enter a valid time in the format HH:mm AM/PM or HH:mm (24-hour format).
                                  </div>
                                </div>
                
                
                                <div class="form-group">
                                  <label>Street Address</label>
                                  <input value="<?php if ($edit) echo $info["con_street"]; ?>" name="street" type="text" maxlength="255" class="form-control" placeholder="Enter street address..." required>
                
                                </div>
                                <div class="form-group">
                                  <label>Building</label>
                                  <input value="<?php if ($edit) echo $info["con_building"]; ?>" name="building" type="text" maxlength="255" class="form-control" placeholder="Enter building..." required>
                
                                </div>
                                <div class="form-group">
                                  <label>Zip Code</label>
                                  <input value="<?php if ($edit) echo $info["con_zipcode"]; ?>" name="zipcode" type="text" maxlength="50" class="form-control" placeholder="Enter zip code..." required>
                
                                </div>
                                <div class="form-group">
                                  <label>State</label>
                                  <input value="<?php if ($edit) echo $info["con_state"]; ?>" name="state" type="text" maxlength="50" class="form-control" placeholder="Enter state name..." required>
                
                                </div>
                                <div class="form-group">
                                  <label>Country</label>
                                  <input value="<?php if ($edit) echo $info["con_country"]; ?>" name="country" type="text" maxlength="50" class="form-control" placeholder="Enter country name..." required>
                
                                </div>
                  
                            </div>
                            <div class="card-body" style="margin-top: -45px !important;">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-md btn-info" id="btn_save">Save Audit</button>
                                    <button type="button" class="btn btn-md btn-warning" id="btn_cancel">Cancel</button>

                                    <input name="action" type="hidden" value="<?php echo $_REQUEST["action"]; ?>" />
                                    <input name="id" type="hidden" value="<?php if ($edit) echo $info["idcontrol"]; ?>" />
                                    <input name="return" type="hidden" value="<?php if ($edit) echo $_REQUEST["return"]; ?>" />
                                </div>
                            </div>
                        </div>
                        <div class="card-footer"></div>
                    </form>
                </div>
            </div>
            </section>
        </div>
        <?php require '../../layout/footer.php' ?>
        </footer>
        </div>
    </div>
    <?php require '../../layout/general_js.php' ?>
    <style>
        textarea{
            min-height: 120px !important;
        }
    </style>
</body>
</html>