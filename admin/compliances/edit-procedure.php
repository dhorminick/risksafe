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

    if (isset($_GET['id']) && isset($_GET['id']) !== "") {
        $toDisplay = true;   
        $id = sanitizePlus($_GET['id']);
        
        if (isset($_POST['update-procedure'])) {
                $procedureTitle = sanitizePlus($_POST["procedureTitle"]);
                $procedureNumber = sanitizePlus($_POST["procedureNumber"]);
                $procedureDescription = sanitizePlus($_POST["procedureDescription"]);
                $procedureEffectiveDate = sanitizePlus($_POST["procedureEffectiveDate"]);
                $procedureReviewDate = sanitizePlus($_POST["procedureReviewDate"]);
                $applicability = sanitizePlus($_POST["applicability"]);
                #$procedureRequirements = sanitizePlus($_POST["procedureRequirements"]);
                $complianceResponsibility = sanitizePlus($_POST["procedureRequirements"]);
                $resources = sanitizePlus($_POST["resources"]);
                $procedureApproval = sanitizePlus($_POST["procedureApproval"]);
                $procedureReviewRevisionHistory = sanitizePlus($_POST["procedureReviewRevisionHistory"]);
                $procedureAcknowledgment = sanitizePlus(isset($_POST["procedureAcknowledgment"]) ? 1 : 0);
                
                $query = "UPDATE as_procedures SET ProcedureTitle = '$procedureTitle', ProcedureNumber = '$procedureNumber', ProcedureDescription = '$procedureDescription', ProcedureEffectiveDate = '$procedureEffectiveDate', ProcedureReviewDate = '$procedureReviewDate', Applicability = '$applicability', ComplianceRequirements = '$procedureRequirements', Resources = '$resources', ProcedureApproval = '$procedureApproval', ProcedureReview = '$procedureReviewRevisionHistory', ProcedureAcknowledgment = '$procedureAcknowledgment' WHERE c_id = '$company_id' AND p_id = '$id'";
                $incidentUpdated = $con->query($query);
                if ($incidentUpdated) {
                    #notify
                    header("Location: applicable-procedure?id=".$id);
                }else{
                    array_push($message, 'Error 502: Error!!');
                }	
            }
            
        $CheckIfProcedureExist = "SELECT * FROM as_procedures WHERE p_id = '$id' AND c_id = '$company_id'";
        $ProcedureExist = $con->query($CheckIfProcedureExist);
        if ($ProcedureExist->num_rows > 0) {	
            $in_exist = true;
			$info = $ProcedureExist->fetch_assoc();

            
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
  <title>Edit Procedures | <?php echo $siteEndTitle; ?></title>
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
                <?php if($toDisplay == true){ ?>
                <?php if ($in_exist == true) { ?>
                <div class="card">
                    <form method="post">
                        <div class="card-header"></div>
                        <div class="card-body">
                            <?php require '../../layout/alert.php' ?>
                            <div class="card-header">
                                <h3 class="d-inline">Edit Procedure</h3>
                                <a class="btn btn-primary btn-icon icon-left header-a" href="applicable-procedure"><i class="fas fa-arrow-left"></i> View All</a>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="procedureTitle">Procedure Title</label>
                                    <input value="<?php echo $info['ProcedureTitle'];?>" type="text" class="form-control" id="procedureTitle" name="procedureTitle" required>
                                </div>
                                <div class="form-group">
                                    <label for="procedureNumber">Procedure Number</label>
                                    <input value="<?php echo $info['ProcedureNumber'];?>" type="text" class="form-control" id="procedureNumber" name="procedureNumber" required>
                                </div>
                                <div class="form-group">
                                    <label for="procedureDescription">Procedure Description</label>
                                    <textarea class="form-control" id="procedureDescription" name="procedureDescription" rows="4" required><?php echo $info['ProcedureDescription'];?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="procedureEffectiveDate">Procedure Effective Date</label>
                                    <input value="<?php echo date('Y-m-d', strtotime($info['ProcedureEffectiveDate']));?>" type="text" class="form-control datepicker" id="procedureEffectiveDate" name="procedureEffectiveDate" required>
                                </div>
                                <div class="form-group">
                                    <label for="procedureReviewDate">Procedure Review Date</label>
                                    <input value="<?php echo $info['ProcedureReviewDate'];?>" type="date" class="form-control" id="procedureReviewDate" name="procedureReviewDate" required>
                                </div>
                                <div class="form-group">
                                    <label for="applicability">Applicability</label>
                                    <textarea class="form-control" id="applicability" name="applicability" rows="4" required><?php echo $info['Applicability'];?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="procedureRequirements">Procedure Compliance Requirements</label>
                                    <textarea class="form-control" id="procedureRequirements" name="procedureRequirements" rows="4" required><?php echo $info['ComplianceRequirements'];?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="relatedDocuments">Resources</label>
                                    <textarea class="form-control" id="relatedDocuments" name="resources" rows="4" required><?php echo $info['Resources'];?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="procedureApproval">Procedure Approval</label>
                                    <input value="<?php echo $info['ProcedureApproval'];?>"  type="text" class="form-control" id="procedureApproval" name="procedureApproval" required>
                                </div>
                                <div class="form-group">
                                    <label for="procedureReviewRevisionHistory">Procedure Review</label>
                                    <textarea class="form-control" id="procedureReviewRevisionHistory" name="procedureReviewRevisionHistory" rows="4" required><?php echo $info['ProcedureReview'];?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="procedureAcknowledgment">Procedure Acknowledgment</label>
                                    <div class="form-check" style="display: flex;align-items:center;">
                                        <input <?php if ($info['ProcedureAcknowledgment'] == '1') echo 'checked="checked"'; ?> class="form-check-input" type="checkbox" id="gridCheck" name="procedureAcknowledgment">
                                        <label class="form-check-label" for="gridCheck">
                                        I acknowledge the procedure
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body" style="margin-top: -45px !important;">
                                <div class="form-group">
									<button type="submit" class="btn btn-md btn-primary" name="update-procedure">Update Procedure</button>
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
                            <div style="display:flex;justify-content:center;align-items:center;width:100%;margin:10px 0px;">Applicable Procedure Doesn't Exist!!</div>
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