<?php
    session_start();
    $file_dir = '../../';
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'login?r=/compliances/applicable-policy');
        exit();
    }
    $message = [];
    include '../../layout/db.php';
    include '../../layout/admin_config.php';
    #include '../../layout/user_details.php';

    if(isset($_POST["create-policy"])){

        $policyTitle = sanitizePlus($_POST["policyTitle"]);
        $policyNumber = sanitizePlus($_POST["policyNumber"]);
        $policyDescription = sanitizePlus($_POST["policyDescription"]);
        $policyEffectiveDate = sanitizePlus($_POST["policyEffectiveDate"]);
        $policyReviewDate = sanitizePlus($_POST["policyReviewDate"]);
        $applicability = sanitizePlus($_POST["applicability"]);
        $policyRequirements = sanitizePlus($_POST["policyRequirements"]);
        $complianceResponsibility = sanitizePlus($_POST["complianceResponsibility"]);
        $relatedDocuments = sanitizePlus($_POST["relatedDocuments"]);
        $policyApproval = sanitizePlus($_POST["policyApproval"]);
        $policyReviewRevisionHistory = sanitizePlus($_POST["policyReviewRevisionHistory"]);
        $policyAcknowledgment = sanitizePlus(isset($_POST["policyAcknowledgment"]) ? 1 : 0);
        
		$p_id = secure_random_string(10);
        $query = "INSERT INTO policyfields (policy_user_id, PolicyTitle, PolicyNumber, PolicyDescription, PolicyEffectiveDate, PolicyReviewDate, Applicability, PolicyRequirements, ComplianceResponsibility, RelatedDocuments, PolicyApproval, PolicyReviewRevisionHistory, PolicyAcknowledgment, c_id, p_id)
        VALUES ('$userId', '$policyTitle', '$policyNumber', '$policyDescription', '$policyEffectiveDate','$policyReviewDate', '$applicability', '$policyRequirements','$complianceResponsibility', '$relatedDocuments', '$policyApproval', '$policyReviewRevisionHistory', '$policyAcknowledgment', '$company_id', '$p_id')";
        $PolicyCreated = $con->query($query);
        if ($PolicyCreated) {
            header("Location: applicable-policy?id=".$p_id);
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
  <title>Create New Applicable Policy | <php echo $siteEndTitle; ?></title>
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
                                <h3 class="d-inline">Create New Policy</h3>
                                <a class="btn btn-primary btn-icon icon-left header-a" href="applicable-policy"><i class="fas fa-arrow-left"></i> View All</a>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="policyTitle">Policy Title</label>
                                    <input type="text" class="form-control" id="policyTitle" name="policyTitle" value="" required>
                                </div>
                                <div class="form-group">
                                    <label for="policyNumber">Policy Number</label>
                                    <input type="text" class="form-control" id="policyNumber" name="policyNumber" value="" required>
                                </div>
                                <div class="form-group">
                                    <label for="policyDescription">Policy Description</label>
                                    <textarea class="form-control" id="policyDescription" name="policyDescription" rows="4" value="" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="policyEffectiveDate">Policy Effective Date</label>
                                    <input type="text" class="form-control datepicker" id="policyEffectiveDate" name="policyEffectiveDate" value="" required>
                                </div>
                                <div class="form-group">
                                    <label for="policyReviewDate">Policy Review Date</label>
                                    <input type="text" class="form-control datepicker" id="policyReviewDate" name="policyReviewDate" value="" required>
                                </div>
                                <div class="form-group">
                                    <label for="applicability">Applicability</label>
                                    <textarea class="form-control" id="applicability" name="applicability" rows="4" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="policyRequirements">Policy Requirements</label>
                                    <textarea class="form-control" id="policyRequirements" name="policyRequirements" rows="4" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="complianceResponsibility">Compliance Responsibility</label>
                                    <textarea class="form-control" id="complianceResponsibility" name="complianceResponsibility" rows="4" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="relatedDocuments">Related Documents</label>
                                    <textarea class="form-control" id="relatedDocuments" name="relatedDocuments" rows="4" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="policyApproval">Policy Approval</label>
                                    <input type="text" class="form-control" id="policyApproval" name="policyApproval" value="" required>
                                </div>
                                <div class="form-group">
                                    <label for="policyReviewRevisionHistory">Policy Review and Revision History</label>
                                    <textarea class="form-control" id="policyReviewRevisionHistory" name="policyReviewRevisionHistory" rows="4" value="" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="policyAcknowledgment">Policy Acknowledgment</label>
                                    <div class="form-check" style="display: flex;align-items:center;">
                                        <input class="form-check-input" type="checkbox" id="gridCheck" name="policyAcknowledgment">
                                        <label class="form-check-label" for="gridCheck">
                                        I acknowledge the policy
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body" style="margin-top: -45px !important;">
                                <div class="form-group">
									<button type="submit" class="btn btn-md btn-primary" name="create-policy">Create Policy</button>
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