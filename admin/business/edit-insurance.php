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

    if (isset($_GET['id']) && isset($_GET['id']) !== "") {
        $toDisplay = true;   
        $id = sanitizePlus($_GET['id']);
        $CheckIfInsuranceExist = "SELECT * FROM as_insurance WHERE in_id = '$id' AND c_id = '$company_id'";
        $InsuranceExist = $con->query($CheckIfInsuranceExist);
        if ($InsuranceExist->num_rows > 0) {	
            $in_exist = true;
			$info = $InsuranceExist->fetch_assoc();

            if (isset($_POST['update-insurance'])) {
                
                $type = sanitizePlus($_POST["type"]);
                $coverage = sanitizePlus($_POST["coverage"]);
                $exclusions = sanitizePlus($_POST["exclusions"]);
                $company = sanitizePlus($_POST["company"]);
                $date = sanitizePlus($_POST["date"]);
                $details = sanitizePlus($_POST["details"]);
                $actions = sanitizePlus($_POST["actions"]);
                
                $query = "UPDATE as_insurance SET is_type = '$type', is_coverage = '$coverage', is_exclusions = '$exclusions', is_company = '$company', is_date = '$date', is_details = '$details', is_actions = '$actions' WHERE c_id = '$company_id' AND in_id = '$id'";
                $insuranceUpdated = $con->query($query);
                if ($insuranceUpdated) {
                    array_push($message, 'Insurance Updated Successfully!!');
                }else{
                    array_push($message, 'Error Updating Insurance Details!!');
                }	
            }
        }else{
            $in_exist = false;
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
  <title>Edit Insurance | <?php echo $siteEndTitle; ?></title>
  <?php require $file_dir.'layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/footer.custom.css">
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
                <?php if($toDisplay == true){ ?>
                <?php if ($in_exist == true) { ?>
                <div class="card">
                    <form method="post">
                        <div class="card-header"></div>
                        <div class="card-body">
                            <?php require $file_dir.'layout/alert.php' ?>
                            <div class="card-header">
                                <h3 class="d-inline">Edit Insurance</h3>
                                <a class="btn btn-primary btn-icon icon-left header-a" href="insurances?id=<?php echo $info['in_id']; ?>"><i class="fas fa-arrow-left"></i> Back</a>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Insurance Type</label>
                                    <input name="type" value="<?php echo $info['is_type']; ?>" type="text" id="control" maxlength="255" class="form-control" placeholder="Enter insurance type..." required>
                                
                                </div>
                                <div class="form-group">
                                    <label>Policy Coverage</label>
                                    <textarea rows="3" class="form-control" placeholder="Enter policy coverage..." class="form-control" name="coverage"><?php echo $info['is_coverage']; ?></textarea>		            		        
                                </div>
                                <div class="form-group">
                                    <label>Policy Exclusions</label>
                                    <textarea rows="3" class="form-control" placeholder="Enter policy exclusions..." class="form-control" name="exclusions"><?php echo $info['is_exclusions']; ?></textarea>		            		        
                                </div>
                                <div class="form-group">
                                    <label>Insurance Company and Contact</label>
                                    <textarea rows="3" class="form-control" placeholder="Enter insurance company and contact details..." class="form-control" name="company"><?php echo $info['is_company']; ?></textarea>		            		        
                                </div>
                                <div class="form-group">
                                    <label>Last Review Date</label>
                                    <input name="date" type="date" maxlength="20" class="form-control datepicker" placeholder="Select last review date..." required style="cursor:pointer;" value="<?php if($info["is_date"] = '0000-00-00'){echo date('Y-m-d');}else{echo date("Y-m-d", strtotime($info["is_date"]));} ?>">
                                
                                </div>
                                <div class="form-group">
                                    <label>Details of Claims</label>
                                    <textarea rows="3" class="form-control" placeholder="Enter details of claims..." class="form-control" name="details"><?php echo $info['is_details']; ?></textarea>		            		        
                                </div>
                                <div class="form-group">
                                    <label>Follow-up Actions</label>
                                    <textarea rows="3" class="form-control" placeholder="Enter follow-up actions..." class="form-control" name="actions"><?php echo $info['is_actions']; ?></textarea>		        		       
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
									<button type="submit" class="btn btn-md btn-primary" name="update-insurance">Update Insurance</button>
									<button type="button" class="btn btn-md btn-warning" id="btn_cancel">Cancel</button>
								</div>
                            </div>
                        </div>
                        <div class="card-footer"></div>
                    </form>
                </div>
                <?php }else{ ?>
                <div class="card">
                    <div class="card-body" style="display:flex;justify-content:center;align-items:center;min-height:500px;">
                        <div>
                            <h3 style="display:flex;justify-content:center;align-items:center;width:100%;">Error 402!!</h3>
                            <div style="display:flex;justify-content:center;align-items:center;width:100%;margin:10px 0px;">Insurance Doesn't Exist!!</div>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <?php }else{ ?>
                <div class="card">
                    <div class="card-body" style="display:flex;justify-content:center;align-items:center;min-height:500px;">
                        <div>
                            <h3 style="display:flex;justify-content:center;align-items:center;width:100%;">Error 402!!</h3>
                            <div style="margin:10px;display:flex;justify-content:center;align-items:center;width:100%;">Missing Parameters!!</div>
                        </div>
                    </div>
                </div>
                <?php } ?>
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