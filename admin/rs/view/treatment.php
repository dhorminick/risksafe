<?php

include_once("../controller/auth.php");
include_once("../config.php");
include_once('../model/treatment.php');



if (isset($_REQUEST["response"]) and $_REQUEST["response"] == "true") {
  $msg = "Treatment saved successfully.";
}

if (isset($_REQUEST["response"]) and $_REQUEST["response"] == "err") {
  $msg = "Error saving treatment, please try again.";
}

$treat = new treatment();
if ($_REQUEST["action"] == "edit") {
  $edit = true;
  $info = $treat->getTreatment($_REQUEST["id"]);
} else {
  $edit = false;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include_once("header.php"); ?>
</head>

<body>
  <!-- header -->
  <div id="top-nav" class="navbar navbar-inverse navbar-static-top" style="background-color:#09F;border:0;">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
        <a class="navbar-brand" href="main.php" style="font-weight:900;color:#fff;"><?php echo APP_TITLE; ?></a>
      </div>
      <div class="navbar-collapse collapse">
        <ul class="nav navbar-nav navbar-right">
          <li class="dropdown"> <a class="dropdown-toggle" role="button" data-toggle="dropdown" href="#" style="font-weight:900;color:#fff;"><i class="glyphicon glyphicon-user"></i> Account <span class="caret"></span></a>
            <ul id="g-account-menu" class="dropdown-menu" role="menu">
              <?php include_once("menu_top.php"); ?>
            </ul>
          </li>
        </ul>
      </div>
    </div>
    <!-- /container -->
  </div>
  <!-- /Header -->

  <!-- Main -->
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-3 col-sm-12">
        <!-- Left column -->
        <?php include_once("menu.php"); ?>
        <!-- /col-3 -->
      </div>
      <div class="col-lg-9 col-md-12">
        <h1 class="page-header">
          <?php
          if ($edit) {
            echo 'Edit Treatment';
          } else {
            echo 'New Treatment';
          }
          ?>
        </h1>
        <div class="col-lg-9 col-md-12">
          <div class="panel panel-default">
            <div class="panel-body">
              <form role="form" id="form" action="../controller/treatment.php">
                <div class="alert alert-info" id="notify" <?php if (!isset($msg)) { ?>style="display: none;" <?php } ?>>
                  <?php if (isset($msg)) echo $msg; ?>
                </div>

                <h3 class="subtitle">Treatment Details</h3>
                <div class="form-group" id="existing_div">
                  <label>Type of treatment<a href="#" data-tooltip="You can select an existing treatment from your risk assessments, or you can create your own custom treatment"><img src="../img/help_ico.gif" class="help-ico"></a></label>

                  <select name="existing" id="typeOfControl" onchange="toggleTextbox()" class="form-control" required>
                    <option value="-1" <?php echo ($edit && $info["tre_treatment"] == -1) ? 'selected' : ''; ?>>
                      <?php
                      if ($edit) {
                        echo 'Choose another existing treatment';
                      } else {
                        echo 'Select type of treatments';
                      }
                      ?>
                    </option>
                    <?php
                    $db = new db;
                    $conn = $db->connect();
                    $userid =$_SESSION['userid'];
                 
                  
                    $query = "SELECT aud_treatment FROM as_auditcontrols WHERE aud_treatment IS NOT NULL AND con_user = '$userid'";
                    $result = mysqli_query($conn, $query);
                    if ($result) {
                      while ($row = mysqli_fetch_assoc($result)) {
                        $treatmentName = $row['aud_treatment'];
                        $selected = ($edit && $existingTreatment == $treatmentName) ? 'selected' : '';
                        echo "<option value='" . htmlspecialchars($treatmentName) . "' $selected>" . htmlspecialchars($treatmentName) . "</option>";
                      }
                    } else {
                      echo "Error retrieving treatments from the database.";
                    }
                    mysqli_close($conn);
                    ?>
                  </select>
                </div>

                <div class="form-group">
                  <label>Create Treatment</label>
                  <input value="<?php if ($edit) echo $info["tre_treatment"]; ?>" name="treatment" type="text" id="createCustomControl" maxlength="255" class="form-control" placeholder="Enter treatment..." required>

                </div>
                <div class="form-group">
                  <label>Cost/benefits</label>
                  <input value="<?php if ($edit) echo $info["tre_cost_ben"]; ?>" name="cost_ben" type="text" maxlength="255" class="form-control" placeholder="Enter cost/benefits..." required>

                </div>
                <div class="form-group">
                  <label>Progress update</label>
                  <textarea name="progress" rows="4" class="form-control" placeholder="Enter progress update..." required><?php if ($edit) echo $info["tre_progress"]; ?></textarea>

                </div>
                <div class="form-group">
                  <label>Owner</label>
                  <input value="<?php if ($edit) echo $info["tre_owner"]; ?>" id="owner" name="owner" type="text" maxlength="100" class="form-control" placeholder="Enter owner..." required>

                </div>
                <div class="form-group">
                  <label>Start date</label>
                  <input name="start" id="start" type="text" maxlength="20" class="form-control readonly" placeholder="Select start date..." required readonly style="cursor:pointer;" value="<?php if ($edit) {
                                                                                                                                                                                                echo date("m/d/Y", strtotime($info["tre_start"]));
                                                                                                                                                                                              } else {
                                                                                                                                                                                                echo date("m/d/Y");
                                                                                                                                                                                              } ?>">

                </div>
                <div class="form-group">
                  <label>Due date</label>
                  <input name="due" id="due" type="text" maxlength="20" class="form-control readonly" placeholder="Select due date..." required readonly style="cursor:pointer;" value="<?php if ($edit) {
                                                                                                                                                                                          echo date("m/d/Y", strtotime($info["tre_due"]));
                                                                                                                                                                                        } else {
                                                                                                                                                                                          echo date("m/d/Y");
                                                                                                                                                                                        } ?>">

                </div>
                <div class="form-group">
                  <label>Status</label>
                  <select name="status" class="form-control" required>
                    <option value="1" <?php if ($edit and $info["tre_status"] == 1) echo ' selected'; ?>>In progress</option>
                    <option value="2" <?php if ($edit and $info["tre_status"] == 2) echo ' selected'; ?>>Completed</option>
                    <option value="3" <?php if ($edit and $info["tre_status"] == 3) echo ' selected'; ?>>Cancelled</option>
                  </select>

                </div>
                <h3 class="subtitle">Business details</h3>
                <div class="form-group">
                  <label>Team/business unit</label>
                  <input value="<?php if ($edit) echo $info["tre_team"]; ?>" id="team" name="team" type="text" maxlength="255" class="form-control" placeholder="Enter team/business unit..." required>

                </div>
                <div class="form-group">
                  <label>Assessor name</label>
                  <input value="<?php if ($edit) echo $info["tre_assessor"]; ?>" id="assessor" name="assessor" type="text" maxlength="100" class="form-control" placeholder="Enter assessor name..." required>

                </div>
                <div class="form-group ">

                  <button type="submit" class="btn btn-md btn-info" id="btn_save">Save Treatment</button>
                  <button type="button" class="btn btn-md btn-warning" id="btn_cancel">Cancel</button>
                  <input name="action" type="hidden" value="<?php echo $_REQUEST["action"]; ?>" />
                  <input name="id" type="hidden" value="<?php if ($edit) echo $info["idtreatment"]; ?>" />
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!--/col-span-9-->
    </div>
  </div>

  <!-- /Main -->

  <?php include_once("footer.php"); ?>
  <script>
    $(document).ready(function(e) {

      $(function() {
        $("#start, #due").datepicker();
      });


      $("#btn_cancel").click(function(e) {
        $(location).attr("href", "treatments.php");
      });

      $("#existing").change(function(e) {

        if ($("#existing").val() == "-1") {
          $("#treatment").val('');
          $("#team").val('');
          $("#assessor").val('');
        } else {

          $.ajax({
              method: "POST",
              url: "../controller/treatment.php?action=gettreatmentinfo&id=" + $("#existing").val(),
              async: false
            })
            .done(function(msg) {
              arr = JSON.parse(msg);
              $("#treatment").val(arr.treatment);
              $("#team").val(arr.team);
              $("#assessor").val(arr.assessor)
            });
        }
      });

    });
  </script>
  </script>
  <script>
    function toggleTextbox() {
      var dropdown = document.getElementById("typeOfControl");
      var textbox = document.getElementById("createCustomControl");

      if (dropdown.value === "-1") {
        textbox.disabled = false;
      } else {
        textbox.disabled = true;
      }
    }
  </script>
</body>

</html>