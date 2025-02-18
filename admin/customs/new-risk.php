<?php
    session_start();
    $file_dir = '../../';

    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'login?r=/customs/new-risk');
        exit();
    }
  
    $message = [];
    include $file_dir.'layout/db.php';
    include $file_dir.'layout/admin__config.php';
    include '../ajax/customs.php';

    if(isset($_POST["create-risk"])){
        
        $title = sanitizePlus($_POST["title"]);
        $sub = sanitizePlus($_POST["sub"]);
        $description = sanitizePlus($_POST["description"]);
        $owner = sanitizePlus($_POST["owner"]);
        $industry = sanitizePlus($_POST["industry"]);
        
        $risk_id = secure_random_string(10);
        $date__time = date("Y-m-d");
        
        $query = "INSERT INTO as_customrisks ( title, description, sub, owner, c_id, risk_id, cus_date, industry ) VALUES ('$title', '$description', '$sub', '$owner', '$company_id', '$risk_id', '$date__time', '$industry')";
        $created = $con->query($query);
        if ($created) {
            #send notification
            $datetime = date("Y-m-d H:i:s");
            $notification_message = 'New Custom Risk Created';
            $notifier = $userId;
            $link = "admin/customs/risks?id=".$risk_id;
            $type = 'risk';
            $case = 'new';
            $id = $risk_id;
            
            $returnArray = createNotification($company_id, $notification_message, $datetime, $notifier, $link, $type, $case, $con, $sitee);
            
            // header("Location: treatments?id=".$treatment_id);
            // exit();
            array_push($message, 'Custom Risk Created Successfully!!');
        }else{
            array_push($message, 'Error 502: Error Creating Risk!!');
        }
    }
    
    $risk__industry = $_SESSION['risk_industry'];
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta
   content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Create New Custom Risk | <?php echo $siteEndTitle; ?></title>
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
                    <div class="note"><strong>NOTE:</strong> After Registering A New Custom Risk, Go Back To The Already Opened Risk Assessment Page, And Refresh The Customs List With The Refresh Button At The Far Right Corner Of The Form.</div>
                    <?php } ?>
                    <form method="post">
                        <div class="card-header">
                            <h3 class="subtitle d-inline">Risk Details</h3>
                            <a href='risks' class="btn btn-primary btn-icon icon-left header-a"><i class="fas fa-arrow-left"></i> Back</a>
                        </div>
                        <div class="card-bodyy">
                            <div class="card-body">
                                <div class="row custom-row">
                                    <div class="form-group col-12 col-lg-8">
                                        <label>Risk:</label>
                                        <input name="title" type="text" class="form-control" placeholder="Enter Risk..." required>
                                    </div>
                                    
                                    <div class="form-group col-12 col-lg-4">
                                        <label>Industry Type:</label>
                                        <?php echo getIndustries($risk__industry, $con); ?>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label>Risk Sub Category:</label>
                                    <input name="sub" type="text" class="form-control" placeholder="Enter Risk Sub Category..." required>
                                </div>

                                <div class="form-group">
                                    <label>Risk Description:</label>
                                    <textarea name="description" class="form-control" placeholder="Enter Risk Description"></textarea>
                                </div>
                                
                                <div class='row custom-row'>
                                    <!-- <div class="form-group col-12 col-lg-8">
                                        <label>Control Actions:</label>
                                        
                                    </div> -->
                                    
                                    <div class="form-group col-12">
                                        <label for='owner'>Risk Owner:</label>
                                        <input id='owner' name="owner" type="text" class="form-control" placeholder="Enter Risk Owner..." required>
                                    </div>
                                </div>

                            </div>
                             <div class="card-body" style="margin-top: -45px !important;">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-md btn-primary" name="create-risk">Save Custom Risk</button>
                                    <button type="reset" class="btn btn-md btn-warning" id="btn_cancel">Cancel</button>
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