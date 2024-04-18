<?php
    session_start();
    $file_dir = '../../';

    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'login?r=/customs/treatments');
        exit();
    }
  
    $message = [];
    include '../../layout/db.php';
    include '../ajax/customs.php';
    include '../../layout/admin_config.php';

    if (isset($_GET['id']) && isset($_GET['id']) !== "") {
        $toDisplay = true;   
        $id = sanitizePlus($_GET['id']);
        $CheckIfCustomExist = "SELECT * FROM as_customtreatments WHERE treatment_id = '$id' AND c_id = '$company_id'";
        $CustomExist = $con->query($CheckIfCustomExist);
        if ($CustomExist->num_rows > 0) {	
            $aud_exist = true;
            $info = $CustomExist->fetch_assoc();

            if(isset($_POST["update-treatment"])){

                $title = sanitizePlus($_POST["title"]);
                $description = sanitizePlus($_POST["description"]);
                $status = sanitizePlus($_POST["status"]);
                #$effectiveness = sanitizePlus($_POST["effectiveness"]);
                #$frequency = sanitizePlus($_POST["frequency"]);
                #$category = sanitizePlus($_POST["category"]);
                
                $query = "UPDATE as_customtreatments SET title = '$title', description = '$description', status = '$status' WHERE treatment_id = '$id' AND c_id = '$company_id'";
                $customCreated = $con->query($query);
                if ($customCreated) {
                    #create notification and send notifier email
                        $notification_message = "Custom Treatment Modified Successfully";
                        $datetime = date("Y-m-d H:i:s");
                        $notify_link = "admin/customs/treatments?id=".$id;
                        $notifier = $userId;
                        $type = 'treatment';
                        $case = 'edit';
                        $notificationResult = createNotification($company_id, $notification_message, $datetime, $notifier, $notify_link, $type, $case, $con);
                        # $notificationResult = savenotification($company_id, $notification_message, 0, 0, $assessmentId, $risk, $descript, $date, $con, $role);
                        if ($notificationResult == 'true') {
                            array_push($message, 'Treatment Details Updated Successfully!!');
                            header("Location: treatments?id=".$id);
                            exit;
                        } else {
                            array_push($message, 'Error Updating Treatment Details!!');
                            header("Location: edit-treatments?id=".$id);
                            exit;
                        }
                }else{
                  array_push($message, 'Error 502: Error Updating Treatment!!');
                }
            }

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
  <title>Edit Custom Treatments | <?php echo $siteEndTitle; ?></title>
  <?php require '../../layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/admin.custom.css">
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
                        <div class="card-header"></div>
                        <div class="card-bodyy">
                            <div class="card-header"><h3 class="subtitle">Treatment Details</h3></div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Treatment Title:</label>
                                    <input name="title" value="<?php echo $info["title"]; ?>" type="text" class="form-control" placeholder="Enter Treatment Title..." required>
                                </div>

                                <div class="form-group">
                                    <label>Treatment Description:</label>
                                    <textarea name="description" class="form-control" placeholder="Enter Treatment Description">
                                        <?php echo trim($info["description"]); ?>
                                    </textarea>
                                </div>

                                <div class="row custom-row">
                                    <div class="form-group col-12">
                                        <label>Treatment Status</label>
                                        <select name="status" class="form-control" required>
                                            <option value="1" <?php if ($info['status'] == 1) echo "selected"; ?>>Completed</option>
                                            <option value="2" <?php if ($info['status'] == 2) echo "selected"; ?>>In Progress</option>
                                            <option value="3" <?php if ($info['status'] == 3) echo "selected"; ?>>Not Started</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body" style="margin-top: -45px !important;">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-md btn-primary" name="update-treatment">Update Treatment Details</button>
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
                                 Custom Treatment Doesn't Exist!!,
                                 <p><a href="new-treatment" class="btn btn-primary btn-icon icon-left mt-2"><i class="fas fa-plus"></i> Create New Treatment</a></p>
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
                                 <p><a href="treatments" class="btn btn-primary btn-icon icon-left mt-2"><i class="fas fa-arrow-left"></i> Back</a></p>
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
    <style>
        textarea{
            min-height: 120px !important;
        }
    </style>
</body>
</html>