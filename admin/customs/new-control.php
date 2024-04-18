<?php
    session_start();
    $file_dir = '../../';

    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'login?r=/customs/new-control');
        exit();
    }
  
    $message = [];
    include '../../layout/db.php';
    include '../ajax/customs.php';
    include '../../layout/admin_config.php';

    if(isset($_POST["create-control"])){
        
        $title = sanitizePlus($_POST["title"]);
        $description = sanitizePlus($_POST["description"]);
        $effectiveness = sanitizePlus($_POST["effectiveness"]);
        $frequency = sanitizePlus($_POST["frequency"]);
        $category = sanitizePlus($_POST["category"]);
        $control_id = secure_random_string(10);
        
        $cus_date = date("Y-m-d");
        
        $query = "INSERT INTO as_customcontrols ( title, description, effectiveness, frequency, category, c_id, control_id, cus_date) VALUES ('$title', '$description', '$effectiveness', '$frequency', '$category', '$company_id', '$control_id', '$cus_date')";
        $created = $con->query($query);
        if ($created) {
            #send notification
            $datetime = date("Y-m-d H:i:s");
            $notification_message = 'New Custom Control Created';
            $notifier = $userId;
            $link = "admin/customs/controls?id=".$control_id;
            $type = 'control';
            $case = 'new';
            #$case_type = 'new-risk';
            $id = $treatment_id;
            $returnArray = sendNotificationUser($company_id, $notification_message, $datetime, $notifier, $link, $type, $case, $id, $con);
            
            header("Location: controls?id=".$control_id);
        }else{
            array_push($message, 'Error 502: Error Creating Control!!');
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta
   content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Create New Custom Control | <?php echo $siteEndTitle; ?></title>
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
        <!-- Main
         Content -->
        <div class="main-content">
            <section class="section">
            <div class="section-body">
                <div class="card">
                  <div class="card-body">
                    <?php require '../../layout/alert.php' ?>
                    <?php if(isset($_GET['redirect']) && isset($_GET['redirect']) == "true"){ ?>
                    <div class="note"><strong>NOTE:</strong> After Registering A New Custom Control, Go Back To The Already Opened Risk Assessment Page, And Refresh The Customs List With The Refresh Button At The Far Right Corner Of The Form.</div>
                    <?php } ?>
                    <form method="post">
                        <div class="card-header">
                            <h3 class="subtitle d-inline">Control Details</h3>
                            <a href='controls' class="btn btn-primary btn-icon icon-left header-a">View All <i class="fas fa-arrow-right"></i></a>
                        </div>
                        <div class="card-bodyy">
                            <div class="card-body">
                                <div class="form-group">
                                  <label>Control Category</label>
                                  <select name="category" id="control-type" class="form-control" required>
                                    <option value="null" selected>No Category</option>
                                    <?php echo listTypes(-1, $con); ?>
                                  </select>
                                </div>
                                
                                <div class="form-group">
                                    <label>Control Title:</label>
                                    <input name="title" type="text" class="form-control" placeholder="Enter Control Title..." required>
                                </div>

                                <div class="form-group">
                                    <label>Control Description:</label>
                                    <textarea name="description" class="form-control" placeholder="Enter Control Description"></textarea>
                                </div>

                                <div class="row custom-row">
                                    <div class="form-group col-lg-6 col-12">
                                        <label>Effectiveness</label>
                                        <select name="effectiveness" class="form-control" required>
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
                            </div>
                             <div class="card-body">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-md btn-primary" name="create-control">Save Custom Control</button>
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
        .note{
            border-left: 7px solid var(--custom-primary);
            background-color: var(--card-border);
            color: black;
            padding: 10px;
            margin: 0px 0px 20px 0px;
            border-radius: 0px 5px 5px 0px;
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
      
    </script>
</body>
</html>