<?php
    session_start();
    $file_dir = '../../';

    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'login?r=/customs/new-treatment');
        exit();
    }
  
    $message = [];
    include $file_dir.'layout/db.php';
    include $file_dir.'layout/admin__config.php';
    include '../ajax/customs.php';

    if(isset($_POST["create-treatments"])){
        
        $title = sanitizePlus($_POST["title"]);
        $description = sanitizePlus($_POST["description"]);
        #$effectiveness = sanitizePlus($_POST["effectiveness"]);
        #$frequency = sanitizePlus($_POST["frequency"]);
        #$category = sanitizePlus($_POST["category"]);
        $status = sanitizePlus($_POST["status"]);
        $treatment_id = secure_random_string(10);
        $date__time = date("Y-m-d H:i:s");
        
        $query = "INSERT INTO as_customtreatments ( title, description, status, c_id, treatment_id, cus_date ) VALUES ('$title', '$description', '$status', '$company_id', '$treatment_id', '$date__time')";
        $created = $con->query($query);
        if ($created) {
            #send notification
            $datetime = date("Y-m-d H:i:s");
            $notification_message = 'New Custom Treatment Created';
            $notifier = $userId;
            $link = "admin/customs/treatments?id=".$treatment_id;
            $type = 'treatment';
            $case = 'new';
            #$case_type = 'new-risk';
            $id = $treatment_id;
            
            $returnArray = createNotification($company_id, $notification_message, $datetime, $notifier, $link, $type, $case, $con, $sitee);
            
            header("Location: treatments?id=".$treatment_id);
            exit();
        }else{
            array_push($message, 'Error 502: Error Creating Treatment!!');
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta
   content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Create New Custom Treatment | <?php echo $siteEndTitle; ?></title>
  <?php require $file_dir.'layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/footer.custom.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/admin.custom.css">
</head>

<body>
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <?php require $file_dir.'layout/header.php' ?>
        <?php require $file_dir.'layout/sidebar_admin.php' ?>
        <!-- Main
         Content -->
        <div class="main-content">
            <section class="section">
            <div class="section-body">
                <div class="card">
                  <div class="card-body">
                    <?php require $file_dir.'layout/alert.php' ?>
                    <?php if(isset($_GET['redirect']) && isset($_GET['redirect']) == "true"){ ?>
                    <div class="note"><strong>NOTE:</strong> After Registering A New Custom Control, Go Back To The Already Opened Risk Assessment Page, And Refresh The Customs List With The Refresh Button At The Far Right Corner Of The Form.</div>
                    <?php } ?>
                    <form method="post">
                        <div class="card-header">
                            <h3 class="subtitle d-inline">Treatment Details</h3>
                            <a href='treatments' class="btn btn-primary btn-icon icon-left header-a"><i class="fas fa-arrow-left"></i> Back</a>
                        </div>
                        <div class="card-bodyy">
                            <div class="card-body">
                                
                                <div class="row custom-row">
                                    <div class="form-group col-12 col-lg-8">
                                        <label>Treatment Title:</label>
                                        <input name="title" type="text" class="form-control" placeholder="Enter Treatment Title..." required>
                                    </div>
                                    <div class="form-group col-12 col-lg-4">
                                        <label>Treatment Status</label>
                                        <select name="status" class="form-control" required>
                                            <option value="1">Completed</option>
                                            <option value="2">In Progress</option>
                                            <option value="3">Not Started</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Treatment Description:</label>
                                    <textarea name="description" class="form-control" placeholder="Enter Treatment Description"></textarea>
                                </div>

                            </div>
                             <div class="card-body" style="margin-top: -45px !important;">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-md btn-primary" name="create-treatments">Save Custom Treatment</button>
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
        <?php require $file_dir.'layout/footer.php' ?>
        </footer>
        </div>
    </div>
    <?php require $file_dir.'layout/general_js.php' ?>
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
</body>
</html>