<?php
    session_start();
    $file_dir = '../../';
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'login?r=/compliances/applicable-procedure');
        exit();
    }
    $message = [];
    include '../../layout/db.php';
    include '../../layout/admin_config.php';
    #include '../../layout/user_details.php';

    if(isset($_POST["create-procedure"])){

        $procedureTitle = sanitizePlus($_POST["procedureTitle"]);
        $procedureNumber = sanitizePlus($_POST["procedureNumber"]);
        $procedureDescription = sanitizePlus($_POST["procedureDescription"]);
        $procedureEffectiveDate = sanitizePlus($_POST["procedureEffectiveDate"]);
        $procedureReviewDate = sanitizePlus($_POST["procedureReviewDate"]);
        $applicability = sanitizePlus($_POST["applicability"]);
        $complianceRequirements = sanitizePlus($_POST["complianceRequirements"]);
        $resources = sanitizePlus($_POST["resources"]);
        $procedureApproval = sanitizePlus($_POST["procedureApproval"]);
        $procedureReview = sanitizePlus($_POST["procedureReview"]);
        $procedureAcknowledgment = sanitizePlus(isset($_POST["procedureAcknowledgment"]) ? 1 : 0);
        
		$p_id = secure_random_string(10);
        $query = "INSERT INTO as_procedures (procedure_user_id, ProcedureTitle, ProcedureNumber, ProcedureDescription, ProcedureEffectiveDate, ProcedureReviewDate, Applicability, ComplianceRequirements, Resources, ProcedureApproval, ProcedureReview, ProcedureAcknowledgment, c_id, p_id)
        VALUES ('$userId', '$procedureTitle', '$procedureNumber', '$procedureDescription', '$procedureEffectiveDate','$procedureReviewDate', '$applicability', '$complianceRequirements', '$resources', '$procedureApproval', '$procedureReview', '$procedureAcknowledgment', '$company_id', '$p_id')";
        $ProcedureCreated = $con->query($query);
        if ($ProcedureCreated) {
            header("Location: applicable-procedure?id=".$p_id);
        }else{
            array_push($message, 'Error 502: Error!!');
        }	
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Create New Applicable Procedure | <?php echo $siteEndTitle; ?></title>
  <?php require '../../layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/footer.custom.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/admin.custom.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/bundles/bootstrap-daterangepicker/daterangepicker.css">
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
                    <form method="post">
                        <div class="card-header"></div>
                        <div class="card-body">
                            <?php require '../../layout/alert.php' ?>
                            <div class="card-header">
                                <h3 class="d-inline">Create New Procedure</h3>
                                <a class="btn btn-primary btn-icon icon-left header-a" href="applicable-procedure"><i class="fas fa-arrow-left"></i> View All</a>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="procedureTitle">Procedure Title</label>
                                    <input type="text" class="form-control" id="procedureTitle" name="procedureTitle" value="" required>
                                </div>
                                <div class="form-group">
                                    <label for="procedureNumber">Procedure Number</label>
                                    <input type="text" class="form-control" id="procedureNumber" name="procedureNumber" value="" required>
                                </div>
                                <div class="form-group">
                                    <label for="procedureDescription">Procedure Description</label>
                                    <textarea class="form-control" id="procedureDescription" name="procedureDescription" rows="4" value="" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="procedureEffectiveDate">Procedure Effective Date</label>
                                    <input type="text" class="form-control datepicker" id="procedureEffectiveDate" name="procedureEffectiveDate" value="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="procedureReviewDate">Procedure Review Date</label>
                                    <input type="text" class="form-control datepicker" id="procedureReviewDate" name="procedureReviewDate" value="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="applicability">Applicability</label>
                                    <textarea class="form-control" id="applicability" name="applicability" rows="4" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="complianceResponsibility">Procedure Compliance Requirements</label>
                                    <textarea class="form-control" id="complianceResponsibility" name="complianceRequirements" rows="4" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="resources">Resources</label>
                                    <textarea class="form-control" id="resources" name="resources" rows="4" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="procedureApproval">Procedure Approval</label>
                                    <input type="text" class="form-control" id="procedureApproval" name="procedureApproval" value="" required>
                                </div>
                                <div class="form-group">
                                    <label for="procedureReview">Procedure Review</label>
                                    <textarea class="form-control" id="procedureReview" name="procedureReview" rows="4" value="" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="procedureAcknowledgment">Procedure Acknowledgment</label>
                                    <div class="form-check" style="display: flex;align-items:center;">
                                        <input class="form-check-input" type="checkbox" id="gridCheck" name="procedureAcknowledgment">
                                        <label class="form-check-label" for="gridCheck">
                                        I acknowledge the procedure
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
									<button type="submit" class="btn btn-md btn-primary" name="create-procedure">Create Procedure</button>
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
        <?php require '../../layout/footer.php' ?>
        </footer>
        </div>
    </div>
    <?php require '../../layout/general_js.php' ?>
    <script src="<?php echo $file_dir; ?>assets/bundles/bootstrap-daterangepicker/daterangepicker.js"></script>
    <style>
        textarea{
            min-height: 120px !important;
        }
    </style>
</body>
</html>