<?php
    session_start();
    $file_dir = '../../';
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'auth/sign-in?r=/business/insurances');
        exit();
    }
    $message = [];
    include $file_dir.'layout/db.php';
    include $file_dir.'layout/admin__config.php';

    if(isset($_POST["create-insurance"])){

        $type = sanitizePlus($_POST["type"]);
        $coverage = sanitizePlus($_POST["coverage"]);
        $exclusions = sanitizePlus($_POST["exclusions"]);
        $company = sanitizePlus($_POST["company"]);
        $date = sanitizePlus($_POST["date"]);
        $details = sanitizePlus($_POST["details"]);
        $actions = sanitizePlus($_POST["actions"]);
        
		$in_id = secure_random_string(10);
        $query = "INSERT INTO as_insurance (is_user, is_type, is_coverage, is_exclusions, is_company, is_date, is_details, is_actions, c_id, in_id) 
        VALUES ('$userId', '$type', '$coverage', '$exclusions', '$company', '$date', '$details','$actions', '$company_id', '$in_id')";
        $InsuranceCreated = $con->query($query);
        if ($InsuranceCreated) {
            #send notification
            $datetime = date("Y-m-d H:i:s");
            $notification_message = 'New Insurance Registered';
            $notifier = $userId;
            $link = "admin/business/insurances?id=".$in_id;
            $type = 'insurance';
            $case = 'new';
            $id = $in_id;
            $returnArray = createNotification($company_id, $notification_message, $datetime, $notifier, $link, $type, $case, $con, $sitee);
            header("Location: insurances?id=".$in_id);
        }else{
            array_push($message, 'Error 502: Error!!'.$con->error);
        }	
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Create New Insurance | <?php echo $siteEndTitle; ?></title>
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
                    <form method="post">
                        <div class="card-header"></div>
                        <div class="card-body">
                            <?php require $file_dir.'layout/alert.php' ?>
                            <div class="card-header">
                                <h3 class="d-inline">Create New Insurance</h3>
                                <a class="btn btn-primary btn-icon icon-left header-a" href="insurances"><i class="fas fa-arrow-left"></i> Back</a>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Insurance Type</label>
                                    <input name="type" type="text" id="control" maxlength="255" class="form-control" placeholder="Enter insurance type..." required>
                                
                                </div>
                                <div class="form-group">
                                    <label>Policy Coverage</label>
                                    <textarea rows="3" class="form-control" placeholder="Enter policy coverage..." class="form-control" name="coverage"></textarea>		            		        
                                </div>
                                <div class="form-group">
                                    <label>Policy Exclusions</label>
                                    <textarea rows="3" class="form-control" placeholder="Enter policy exclusions..." class="form-control" name="exclusions"></textarea>		            		        
                                </div>
                                <div class="form-group">
                                    <label>Insurance Company and Contact</label>
                                    <textarea rows="3" class="form-control" placeholder="Enter insurance company and contact details..." class="form-control" name="company"></textarea>		            		        
                                </div>
                                <div class="form-group">
                                    <label>Last Review Date</label>
                                    <input name="date" type="text" maxlength="20" class="form-control datepicker" placeholder="Select last review date..." required value="<?php echo date('Y-m-d'); ?>">
                                
                                </div>
                                <div class="form-group">
                                    <label>Details of Claims</label>
                                    <textarea rows="3" class="form-control" placeholder="Enter details of claims..." class="form-control" name="details"></textarea>		            		        
                                </div>
                                <div class="form-group">
                                    <label>Follow-up Actions</label>
                                    <textarea rows="3" class="form-control" placeholder="Enter follow-up actions..." class="form-control" name="actions"></textarea>		        		       
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
									<button type="submit" class="btn btn-md btn-primary" name="create-insurance">Create Insurance</button>
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
    </style>
    <script>$("#date").datepicker();</script>
</body>
</html>