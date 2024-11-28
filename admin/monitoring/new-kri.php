<?php
    session_start();
    $file_dir = '../../';
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'auth/sign-in?r=/monitoring/kri');
        exit();
    }
    $message = [];
    include $file_dir.'layout/db.php';
    include $file_dir.'layout/admin__config.php';

    if(isset($_POST["create-kri"])){
        
        $indicator = sanitizePlus($_POST['indicator']);
        $description = sanitizePlus($_POST['description']);
        $category = sanitizePlus($_POST['category']);
        $owner = sanitizePlus($_POST['owner']);
        $status = sanitizePlus($_POST['status']);
        $frequency = sanitizePlus($_POST['frequency']);
        $threshold = sanitizePlus($_POST['threshold']);
        $current = sanitizePlus($_POST['current']);
        $target = sanitizePlus($_POST['target']);
        $start = sanitizePlus($_POST['start']);
        $trend = sanitizePlus($_POST['trend']);
        $priority = sanitizePlus($_POST['priority']);

        #
        
        $kri_id = secure_random_string(10);
        
        $query = "INSERT INTO kri (indicator, description, category, owner, status, frequency, threshold, current, target, start, trend, priority, c_id, k_id) VALUES ('$indicator', '$description', '$category', '$owner', '$status', '$frequency', '$threshold', '$current', '$target', '$start', '$trend', '$priority', '$company_id', '$kri_id')";
        $treCreated = $con->query($query);
        if ($treCreated) {
            #send notification
            $datetime = date("Y-m-d H:i:s");
            $notification_message = 'New KRI Created';
            $notifier = $userId;
            $link = "admin/monitoring/kri?id=".$kri_id;
            $type = 'kri';
            $case = 'new';
            $id = $kri_id;
            $returnArray = createNotification($company_id, $notification_message, $datetime, $notifier, $link, $type, $case, $con, $sitee);
            
            header("Location: kri?id=".$kri_id);
            exit();
        }else{
            array_push($message, 'Error 502: Error Creating KRI!!');
        }	
    }
    
    $userName_audit = ucwords($_SESSION["u_name"]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Create New KRI | <?php echo $siteEndTitle; ?></title>
  <?php require $file_dir.'layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/footer.custom.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/admin.custom.css">
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
                    <?php if(isset($_GET['redirect']) && isset($_GET['redirect']) == "true"){ ?>
                    <div class="note"><strong>NOTE:</strong> After Registering A New KRI, Go Back To The Already Opened Risk Page, And Refresh The KRI List With The Refresh Button At The Far Right Corner Of The Form.</div>
                    <?php } ?>
                    <form method="post" action=''>
                        <div class="card-header"></div>
                        <div class="card-body">
                            <?php require $file_dir.'layout/alert.php' ?>
                            <div class="card-header">
                                <h3 class="d-inline">Create New KRI</h3>
                                <a class="btn btn-primary btn-icon icon-left header-a" href="treatments"><i class="fas fa-arrow-left"></i> Back</a>
                            </div>
                            <div class="card-body">
                                
                                <div class="form-group">
                                    <label>Indicator Name:</label>
                                    <input name="indicator" type="text" class="form-control" placeholder="KRI" required>
                                </div>
                                <div class="form-group">
                                    <label>Description:</label>
                                    <textarea name="description" rows="4" class="form-control" placeholder="KRI Description" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Category:</label>
                                    <input name="category" type="text" class="form-control" placeholder="KRI Category" required>
                                </div>
                                
                                
                                <div class="row custom-row">
                                <div class="form-group col-12 col-lg-4">
                                    <label>Owner:</label>
                                    <input name="owner" type="text" class="form-control" placeholder="Owner" required>
                                </div>
                                
                                <div class="form-group col-lg-4 col-12">
                                    <label>Status :</label>
                                    <select name="status" class="form-control" required>
                                        <?php echo _listStatus(); ?>    
                                    </select> 
                                </div>
                                <div class="form-group col-lg-4 col-12">
                                    <label>Frequency of Monitoring:</label>
                                    <select name="frequency" class="form-control" required>
                                        <?php echo _listFrequencies(); ?>    
                                    </select> 
                                </div>
                                </div>
                                
                                <div class="row custom-row">
                                <div class="form-group col-lg-4 col-12">
                                    <label>Threshold:</label>
                                    <div class='flexbox'>
                                        <input name="threshold" type="number" class="form-control" min="0" max="100" placeholder="0" required>
                                        <div class='btn'>%</div>
                                    </div>
                                </div>
                                <div class="form-group col-lg-4 col-12">
                                    <label>Current Value:</label>
                                    <div class='flexbox'>
                                        <input name="current" type="number" class="form-control" min="0" max="100" placeholder="0" required>
                                        <div class='btn'>%</div>
                                    </div>
                                </div>
                                <div class="form-group col-lg-4 col-12">
                                    <label>Target Value:</label>
                                    <div class='flexbox'>
                                        <input name="target" type="number" class="form-control" min="0" max="100" placeholder="0" required>
                                        <div class='btn'>%</div>
                                    </div>
                                </div>
                                </div>
                                
                                <div class="row custom-row">
                                <div class="form-group col-lg-4 col-12">
                                    <label>Date Captured:</label>
                                    <input name="start" type="text" class="form-control datepicker" required style="cursor:pointer;">
                
                                </div>
                                <div class="form-group col-lg-4 col-12">
                                    <label>Trend:</label>
                                    <select name="trend" class="form-control" required>
                                        <?php echo _listTrend(); ?>    
                                    </select> 
                                </div>
                                <div class="form-group col-lg-4 col-12">
                                    <label>Priority:</label>
                                    <select name="priority" class="form-control" required>
                                        <?php echo _listPriority(); ?>    
                                    </select>                                
                                </div>
                                </div>

                                
                            </div>
                            <div class="card-body">
                                <div class="form-group text-right">
									<button class="btn btn-md btn-primary btn-icon icol-left" name="create-kri"><i class='fas fa-check'></i> Create KRI</button>
									<button type="button" class="btn btn-md btn-warning" id="btn_cancel">Cancel</button>
								</div>
                            </div>
                        </div>
                        <div class="card-footer"></div>
                    </form>
                </div>
            </div>
            </section>
        </div>
        <?php require $file_dir.'layout/footer.php' ?>
        </footer>
        </div>
    </div>
    <?php require $file_dir.'layout/general_js.php' ?>
    <script src="<?php echo $file_dir; ?>assets/bundles/bootstrap-daterangepicker/daterangepicker.js"></script>
    <style>
        textarea{
            min-height: 120px !important;
        }
        .flexbox{
            display:flex;
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