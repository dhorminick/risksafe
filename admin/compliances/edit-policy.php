<?php
    session_start();
    $file_dir = '../../';
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'auth/sign-in?r=/compliances/applicable-policy');
        exit();
    }
    $message = [];
    include $file_dir.'layout/db.php';
    include $file_dir.'layout/admin__config.php';

    if (isset($_GET['id']) && isset($_GET['id']) !== "") {
        $toDisplay = true;   
        $id = sanitizePlus($_GET['id']);
        $CheckIfPolicyExist = "SELECT * FROM policyfields WHERE p_id = '$id' AND c_id = '$company_id'";
        $PolicyExist = $con->query($CheckIfPolicyExist);
        if ($PolicyExist->num_rows > 0) {	
            $in_exist = true;
			$info = $PolicyExist->fetch_assoc();

            if (isset($_POST['update-policy'])) {
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
                
                $query = "UPDATE policyfields SET PolicyTitle = '$policyTitle', PolicyNumber = '$policyNumber', PolicyDescription = '$policyDescription', PolicyEffectiveDate = '$policyEffectiveDate', PolicyReviewDate = '$policyReviewDate', Applicability = '$applicability', PolicyRequirements = '$policyRequirements', ComplianceResponsibility = '$complianceResponsibility', RelatedDocuments = '$relatedDocuments', PolicyApproval = '$policyApproval', PolicyReviewRevisionHistory = '$policyReviewRevisionHistory', PolicyAcknowledgment = '$policyAcknowledgment' WHERE c_id = '$company_id' AND p_id = '$id'";
                $incidentUpdated = $con->query($query);
                if ($incidentUpdated) {
                    // header("Location: applicable-policy?id=".$id);
                    array_push($message, 'Policy Data Updated Successfully!!');
                }else{
                    array_push($message, 'Error 502: Error!!');
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
  <title>Edit Policies | <?php echo $siteEndTitle; ?></title>
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
                <?php if($toDisplay == true){ ?>
                <?php if ($in_exist == true) { ?>
                <div class="card">
                    <form method="post">
                        <div class="card-header"></div>
                        <div class="card-body">
                            <?php require $file_dir.'layout/alert.php' ?>
                            <div class="card-header">
                                <h3 class="d-inline">Edit Policy</h3>
                                <a class="btn btn-primary btn-icon icon-left header-a" href="applicable-policy?id=<?php echo $info['p_id']; ?>"><i class="fas fa-arrow-left"></i> Back</a>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="policyTitle">Policy Title</label>
                                    <input value="<?php echo $info['PolicyTitle'];?>" type="text" class="form-control" id="policyTitle" name="policyTitle" required>
                                </div>
                                <div class="form-group">
                                    <label for="policyNumber">Policy Number</label>
                                    <input value="<?php echo $info['PolicyNumber'];?>" type="text" class="form-control" id="policyNumber" name="policyNumber" required>
                                </div>
                                <div class="form-group">
                                    <label for="policyDescription">Policy Description</label>
                                    <textarea class="form-control" id="policyDescription" name="policyDescription" rows="4" required><?php echo $info['PolicyDescription'];?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="policyEffectiveDate">Policy Effective Date</label>
                                    <input value="<?php echo date('Y-m-d', strtotime($info['PolicyEffectiveDate']));?>" type="text" class="form-control datepicker" id="policyEffectiveDate" name="policyEffectiveDate" required>
                                </div>
                                <div class="form-group">
                                    <label for="policyReviewDate">Policy Review Date</label>
                                    <input value="<?php echo date('Y-m-d', strtotime($info['PolicyReviewDate']));?>" type="text" class="form-control datepicker" id="policyReviewDate" name="policyReviewDate" required>
                                </div>
                                <div class="form-group">
                                    <label for="applicability">Applicability</label>
                                    <textarea class="form-control" id="applicability" name="applicability" rows="4" required><?php echo $info['Applicability'];?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="policyRequirements">Policy Requirements</label>
                                    <textarea class="form-control" id="policyRequirements" name="policyRequirements" rows="4" required><?php echo $info['PolicyRequirements'];?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="complianceResponsibility">Compliance Responsibility</label>
                                    <textarea class="form-control" id="complianceResponsibility" name="complianceResponsibility" rows="4" required><?php echo $info['ComplianceResponsibility'];?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="relatedDocuments">Related Documents</label>
                                    <textarea class="form-control" id="relatedDocuments" name="relatedDocuments" rows="4" required><?php echo $info['RelatedDocuments'];?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="policyApproval">Policy Approval</label>
                                    <input value="<?php echo $info['RelatedDocuments'];?>"  type="text" class="form-control" id="policyApproval" name="policyApproval" required>
                                </div>
                                <div class="form-group">
                                    <label for="policyReviewRevisionHistory">Policy Review and Revision History</label>
                                    <textarea class="form-control" id="policyReviewRevisionHistory" name="policyReviewRevisionHistory" rows="4" required><?php echo $info['PolicyReviewRevisionHistory'];?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="policyAcknowledgment">Policy Acknowledgment</label>
                                    <div class="form-check" style="display: flex;align-items:center;">
                                        <input <?php if ($info['PolicyAcknowledgment'] == '1') echo 'checked="checked"'; ?> class="form-check-input" type="checkbox" id="gridCheck" name="policyAcknowledgment">
                                        <label class="form-check-label" for="gridCheck">
                                        I acknowledge the policy
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body" style="margin-top: -45px !important;">
                                <div class="form-group">
									<button type="submit" class="btn btn-md btn-primary" name="update-policy">Update Policy</button>
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
                            <div style="display:flex;justify-content:center;align-items:center;width:100%;margin:10px 0px;">Applicable Policy Doesn't Exist!!</div>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <?php }else{ ?>
                <div class="card">
                    <div class="card-body" style="display:flex;justify-content:center;align-items:center;min-height:500px;">
                        <div>
                            <h3 style="display:flex;justify-content:center;align-items:center;width:100%;">Error 402!!</h3>
                            <div style="display:flex;justify-content:center;align-items:center;width:100%;margin:10px 0px;">Missing Parameters!!</div>
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
</body>
</html>