<?php
    session_start();
    $file_dir = '../../';
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'login?r=/compliances/all');
        exit();
    }
    $message = [];
    include '../../layout/db.php';
    include '../../layout/admin__config.php';
    include '../ajax/compliances.php';
    
    if (isset($_GET['id']) && isset($_GET['id']) !== "") {
        $toDisplay = true;   
        $id = sanitizePlus($_GET['id']);
        $CheckIfProcedureExist = "SELECT * FROM as_compliancestandard WHERE compli_id = '$id' AND c_id = '$company_id'";
        $ProcedureExist = $con->query($CheckIfProcedureExist);
        if ($ProcedureExist->num_rows > 0) {	
            $compli_exist = true;
			$info = $ProcedureExist->fetch_assoc();
			
			$compliance_type = $info['type'];
			
			$recommended_control = $info['existing_ct'];
            $saved_control = $info['saved_control'];	
            $saved_treatment = $info['saved_treatment'];	
            $custom_control = $info['custom_control'];	
            $custom_treatment = $info['custom_treatment'];
            
            $un_custom_control = unserialize($custom_control);
            $un_custom_treatment = unserialize($custom_treatment);
            
            $hasCustomControl = is_array($un_custom_control);
            $hasCustomTreatment = is_array($un_custom_treatment);
            
            $evidence = $info['com_documentation'];
            
            if($evidence == 'null'){
                $uploadedEvidence = 'None Uploaded';
            }else{
                $uploadedEvidence = '<a href="evidence/'.$evidence.'" target="_blank" class="bb">View File</a>';
            }

            if ($hasCustomControl == true) {
                #if value in db is array
                $customControlArrayStatus = 'true';
                if ($custom_control == 'a:1:{i:0;s:0:"";}') {
                    #empty array
                    $customControlValuesStatus = 'empty';
                    #show a single empty textbox
                } else if ($custom_control == null){
                    $customControlValuesStatus = 'empty';
                    #show all details
                } else {
                    $customControlValuesStatus = 'not-empty';
                    #show all details
                }
            } else {
                $customControlArrayStatus = 'false';
            }

            if ($hasCustomTreatment == true) {
                #if value in db is array
                $customTreatmentArrayStatus = 'true';
                if ($custom_treatment == 'a:1:{i:0;s:0:"";}') {
                    #empty array
                    $customTreatmentValuesStatus = 'empty';
                    #show a single empty textbox
                } else if ($custom_treatment == null) {
                    $customTreatmentValuesStatus = 'empty';
                    #show all details
                } else {
                    $customTreatmentValuesStatus = 'not-empty';
                    #show all details
                }   
            } else {
                $customTreatmentArrayStatus = 'false';
            }
            
        }else{
            $compli_exist = false;
        }
    } else {
        $toDisplay = false;
    }
    
    #array_push($message, 'Error 502: Error!!');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Compliance Details | <?php echo $siteEndTitle; ?></title>
  <?php require '../../layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/footer.custom.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/admin.custom.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/bundles/prism/prism.css">
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
                <?php if ($compli_exist == true) { ?>
                <div class="card">
                    <form method="post" enctype="multipart/form-data">
                        <div class="card-body">
                            <?php require '../../layout/alert.php' ?>

                            <div class="card-header">
                                <h3 class="subtitle">Compliance Information</h3>
                                <a class="btn btn-primary btn-icon icon-left header-a" href="all"><i class="fas fa-arrow-left"></i> Back <span class='hide-sm' style='font-size: 12px;'>To Compliances</span></a>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
									<label>Compliance Task or Obligation: </label>
									<div class="r_desc"><?php echo $info['com_compliancestandard']; ?></div>
								</div>
								<div class="form-group">
									<label>Reference / Legislation: </label>
									<div class="r_desc"><?php echo nl2br($info['com_legislation']); ?></div>
								</div>
                                
                                <div class="form-group">
									<label>Compliance Requirements:: </label>
									<div class="r_desc"><?php echo nl2br($info['com_training']); ?></div>
								</div>
								<div class='row custom-row'>
								<div class="form-group col-12 col-lg-4">
									<label>Compliance Officer: </label>
									<div class="r_desc"><?php echo $info['com_officer']; ?></div>
								</div>
                                <div class="form-group col-12 col-lg-3">
									<label>Compliance Status: </label>
									<div class="r_desc"><?php echo $info['co_status']; ?></div>
								</div>

                                <div class="form-group col-12 col-lg-5">
									<label>Documentation & Evidence: </label>
									<div class="r_desc"><?php echo $uploadedEvidence; ?></div>
								</div>
								</div>
							</div>
                            <?php if($info['type'] == 'imported'){ ?>
                            <!-- Controls -->
                            <div class="card-header">
                                <h3 class="subtitle">Compliance Actions</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="help-label">
                                        Compliance Controls
                                    </label>
                                    <div class="r_desc"><?php echo str_replace("?", " - ", nl2br($info['imported_controls'])); ?></div>
                                </div>
                            </div>
                            <?php if($info['imported_treatments'] === '' || $info['imported_treatments'] == null || $info['imported_treatments'] == ' '){ ?>
                            <?php }else{ ?>
                            <hr class="assessment-hr">

                            <!-- Treatment -->
                            <div class="card-header">
                                <h3 class="card-header-h">Treatment Plans</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="help-label">
                                        Compliance Treatments
                                    </label>
                                    <div class="r_desc" <?php if(strlen($info['imported_treatments'] >= 100)){ ?>style='margin-top:-20px; !important'<?php } ?>><?php echo str_replace("?", " - ", nl2br($info['imported_treatments'])); ?></div>
                                </div>
                            </div>
                            <?php } ?>
                            
                            <?php }else{ ?>
                            
                            <div class='div_divider'></div>

                            <!-- Controls -->
                            <div class="card-header hh">
                                <h3 class="d-inline">Control Actions</h3>
                                <i class="fas fa-question btn btn-primary btn-icon btn-small btn-help header-a" id="swal-custom-2"></i>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="help-label">
                                        RiskSafe Recommended Controls
                                    </label>
                                    <div class="r_desc"><?php echo getControlSelected($recommended_control, $con); ?></div>
                                </div>
                                <div class="form-group">
                                    <label class="help-label">
                                        Saved Custom Controls
                                    </label>
                                    <div class="add-customs">
                                        <div class="r_desc"><?php echo getCompanyControlSelected($company_id, $saved_control, $con); ?></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="help-label">
                                        Compliance Specific Controls
                                    </label>
                                    <div class="add-customss">
                                        <?php if($custom_control == 'null'){ ?>
                                        <div class="r_desc">No Compliance Specific Controls Specified!</div>
                                        <?php }else{ ?>
                                        <div id='add-customs-control'>
                                            <ul class="r_value_ul">
                                            <?php foreach (unserialize($custom_control) as $value) { ?>
                                                <?php if ($value !== '' || $value !== null) { ?>
                                                <li class="r_value"><?php echo ucwords($value); ?></li>
                                            <?php }} ?>
                                            </ul>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group">
									<label>Control Requirements: </label>
									<div class="r_desc"><?php echo $info['com_controls']; ?></div>
								</div>
                            </div>
                            
                            
                            <!-- Treatment -->
                            <div class='div_divider'></div>
    
                            <!-- Treatment -->
                            <div class="card-header hh">
                                <h3 class="d-inline">Treatment Plans</h3>
                                <i class="fas fa-question btn btn-primary btn-icon btn-small btn-help header-a" id="swal-custom-1"></i>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="help-label">
                                        Saved Custom Treatments
                                    </label>
                                    <div class="add-customs">
                                        <div class="r_desc"><?php echo getCompanyTreatmentSelected($company_id, $saved_treatment, $con); ?></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="help-label">
                                        Compliance Specific Treatments
                                    </label>
                                    <div class="add-customss">
                                        <?php if($custom_treatment == 'null'){ ?>
                                        <div class="r_desc">No Compliance Specific Treatment Specified!</div>
                                        <?php }else{ ?>
                                        <div id='add-customs-control'>
                                            <ul class="r_value_ul">
                                            <?php foreach (unserialize($custom_treatment) as $value) { ?>
                                                <?php if ($value !== '' || $value !== null) { ?>
                                                <li class="r_value"><?php echo ucwords($value); ?></li>
                                            <?php }} ?>
                                            </ul>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="card-footer">
                            <div class="card-body">
                            <div class="form-group">
								<a href="edit-compliance?id=<?php echo $id; ?>" class="btn btn-md btn-primary">Edit Compliance</a>
								<button type="button" class="btn btn-md btn-warning" id="btn_cancel">Cancel</button>
							</div>
							</div>
                        </div>
                        
                    </form>
                </div>
                <?php }else{ ?>
                <div class="card">
                    <div class="card-body" style="display:flex;justify-content:center;align-items:center;min-height:500px;">
                        <div>
                            <h3 style="display:flex;justify-content:center;align-items:center;width:100%;">Error 402!!</h3>
                            <div style="display:flex;justify-content:center;align-items:center;width:100%;margin:10px 0px;">Compliance Standard Doesn't Exist!!</div>
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
    <script src="<?php echo $file_dir; ?>assets/bundles/prism/prism.js"></script>
    <script src="<?php echo $file_dir; ?>assets/bundles/sweetalert/sweetalert.min.js"></script>
    <script src="<?php echo $file_dir; ?>assets/js/page/sweetalert.js"></script>
    <script src="<?php echo $file_dir; ?>assets/js/admin.custom.js"></script>
    <style>
        textarea{
            min-height: 120px !important;
        }
        .div_divider{
            margin: 20px 0px;
        }
        .card-header.hh{
            display:flex;
            justify-content:space-between;
        }
    </style>
</body>
</html>