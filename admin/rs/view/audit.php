<?php

include_once("../controller/auth.php");
include_once("../config.php");
include_once('../model/assessment.php');
include_once('../model/audit.php');

if (isset($_REQUEST["response"]) and $_REQUEST["response"] == "true") {
  $msg = "Audit of controls saved successfully.";
}

if (isset($_REQUEST["response"]) and $_REQUEST["response"] == "err") {
  $msg = "Error saving audit of controls, please try again.";
}

$audit = new audit();
if ($_REQUEST["action"] == "edit") {
  $edit = true;
  $info = $audit->getAudit($_REQUEST["id"]);
} else {
  $edit = false;
}




?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include_once("header.php"); ?>
</head>
<style>
  #timeFeedback {
    display: none;
  }
</style>
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
            echo 'Edit Audit of Control';
          } else {
            echo 'New Audit of Control';
          }
          ?>
        </h1>
        <div class="col-lg-9 col-md-12">
          <div class="panel panel-default">
            <div class="panel-body">

              <form role="form" id="form" action="../controller/audit.php" method="post">

                <div class="alert alert-info" id="notify" <?php if (!isset($msg)) { ?>style="display: none;" <?php } ?>>
                  <?php if (isset($msg)) echo $msg; ?>
                </div>
                <h3 class="subtitle">Control Details</h3>
                <div class="form-group" id="existing_div">
                  <label>Type of control<a href="#" data-tooltip="You can select existing control from your risk assessments, or you can create your own custom control"><img src="../img/help_ico.gif" / class="help-ico"></a></label>
                  <select name="existing" id="typeOfControl" onchange="toggleTextbox()" class="form-control">
                    <option value="">
                      <?php
                      if ($edit) {
                        echo 'Choose another existing control';
                      } else {
                        echo 'Select Type of control';
                      }
                      ?>
                    </option>
                    <?php
                    $ass = new audit;
                    echo $ass->listTypes(-1);
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
                <h3 class="subtitle">Audit Details</h3>
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
                  <input name="date" id="date" type="text" maxlength="20" class="form-control readonly" placeholder="Select date..." required readonly style="cursor:pointer;" value="<?php if ($edit) {
                                                                                                                                                                                        echo date("m/d/Y", strtotime($info["con_date"]));
                                                                                                                                                                                      } else {
                                                                                                                                                                                        echo date("m/d/Y");
                                                                                                                                                                                      } ?>">

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
                <div class="form-group">
                  <button type="submit" class="btn btn-md btn-info" id="btn_save">Save Audit</button>
                  <button type="button" class="btn btn-md btn-warning" id="btn_cancel">Cancel</button>

                  <input name="action" type="hidden" value="<?php echo $_REQUEST["action"]; ?>" />
                  <input name="id" type="hidden" value="<?php if ($edit) echo $info["idcontrol"]; ?>" />
                  <input name="return" type="hidden" value="<?php if ($edit) echo $_REQUEST["return"]; ?>" />
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
        $("#date").datepicker();
      });

      $(function() {
        $('#time').timepicker({
          'scrollDefault': 'now'
        });
      });

      $("#btn_cancel").click(function(e) {
        <?php if (isset($_REQUEST["return"]) and $_REQUEST["return"] == "auditcriteria") { ?>
          $(location).attr("href", "auditcriteria.php?id=<?php echo $_REQUEST["id"]; ?>");
        <?php } else { ?>
          $(location).attr("href", "audits.php");
        <?php } ?>
      });

      $("#existing").change(function(e) {

        if ($("#existing").val() == "-1") {
          $("#control").val('');
          $("#team").val('');
          $("#task").val('');
          $("#assessor").val('');
        } else {

          $.ajax({
              method: "POST",
              url: "../controller/audit.php?action=getcontrolinfo&id=" + $("#existing").val(),
              async: false
            })
            .done(function(msg) {
              arr = JSON.parse(msg);
              $("#control").val(arr.control);
              $("#team").val(arr.team);
              $("#task").val(arr.task);
              $("#assessor").val(arr.assessor)
            });
        }
      });

    });
  </script>
  <script>
    $(document).ready(function() {
      $('#typeOfControl').change(function() {
        var selectedOption = $(this).find(':selected');
        var controlId = selectedOption.data('id');
        console.log(controlId);
        // Make an AJAX request to get the related sub_names
        $.ajax({
          url: '../controller/audit.php?action=getSubInfo',
          method: 'POST',
          data: {
            controlId: controlId
          },
          dataType: 'json',
          success: function(response) {
            // Update the sub_names dropdown with the retrieved data
            var subNamesDropdown = $('#subNamesDropdown');
            subNamesDropdown.empty();
            $.each(response, function(index, subName) {
              subNamesDropdown.append($('<option>', {
                value: subName,
                text: subName
              }));
            });
          }
        });
      });
    });
  </script>
 <script>
    $(document).ready(function () {
        // Hide the invalid-feedback div initially
        $("#timeFeedback").hide();

        // Function to validate the time format using regular expression
        function isValidTimeFormat(time) {
            var regex = /^([01]?[0-9]|2[0-3]):[0-5][0-9] ?(?:AM|PM|am|pm)?$/;
            return regex.test(time);
        }

        // Function to perform validation and show/hide the feedback div
        function validateTimeInput() {
            var timeInput = $("#time").val();

            if (timeInput.trim() === "") {
                // Hide the invalid-feedback div if the input field is empty
                $("#timeFeedback").hide();
            } else if (!isValidTimeFormat(timeInput)) {
                // Show the invalid-feedback div if the time format is incorrect
                $("#timeFeedback").show();
            } else {
                // Hide the invalid-feedback div if the time format is correct
                $("#timeFeedback").hide();
            }
        }

        // Attach keyup event to the input field
        $("#time").on("keyup", function () {
            validateTimeInput();
        });

        // Check validation only when the user types in the input field
        $("#time").on("focusout", function () {
            validateTimeInput();
        });
    });
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