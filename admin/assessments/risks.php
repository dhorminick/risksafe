<?php
    session_start();
    $file_dir = '../../';
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'login?r=/assessments/all');
        exit();
    }
    $message = [];
    include '../../layout/db.php';
    include '../../layout/admin__config.php';
    include '../ajax/assessment.php';

    $ass_exist = false;
    $noValue = true;
    $risk__industry = $_SESSION['risk_industry'];

    if (isset($_GET['id']) && isset($_GET['id']) !== "") {
        $ri_id = sanitizePlus($_GET['id']);
        $toDisplay = true;
        $CheckIfAssessmentDetailsExist = "SELECT * FROM as_assessment_new WHERE risk_id = '$ri_id' AND c_id = '$company_id'";
        $AssessmentDetailsExist = $con->query($CheckIfAssessmentDetailsExist);
        if ($AssessmentDetailsExist->num_rows > 0) {	
            $ass_exist = true;	

			$info = $AssessmentDetailsExist->fetch_assoc();
// 			$control_type = $info['control_type'];
// 			if($control_type == 'recommended'){
//                 $label = 'Recommended Control';
//             }else if($control_type == 'saved'){
//                 $control = serialize($_POST["saved-control"]);
//             }else if($control_type == 'custom'){
//                 $control = serialize($_POST["custom-control"]);
//             }else{
//                 echo 'Error';
//                 exit();
//             }

        }else{
            $ass_exist = false;
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
  <title>Risk Details | <?php echo $siteEndTitle; ?></title>
  <?php require '../../layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/footer.custom.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/admin.custom.css">
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
                <?php if ($toDisplay == true) { ?>
                <?php if ($ass_exist == true) { ?>
                <div class="card toEdit">
                    <div class="card-body">
                        <!-- Identification -->
                        <div class="card-header">
                            <h3 class="d-inline">Risk Identification</h3>
                            <a class="btn btn-primary btn-icon icon-left header-a" href="assessment-details?id=<?php echo $info['assessment']; ?>"><i class="fas fa-arrow-left"></i> Go Back</a>
                        </div>
                        <div class="card-body">
                            
                            <div class="form-group" style='margin-bottom:20px;'>
                                <label>Selected Risk Industry:</label>
                                <div style='font-weight:400;font-size:16px;'>
                                    <?php echo $getIndustry = ucwords(getIndustryTitle($risk__industry, $con)); ?>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Risk:</label>
                                <div id="risk_div">
                                    <div class="r_desc"><?php if($info['risk_type'] == 'custom'){echo ucwords(getCustomRisks_New($info['risk'], $con));}else{ echo ucwords(getRisks_New($info['risk'], $con)); }; ?></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Sub Risk:</label>
                                <div id="hazard_div">
                                    <div class="r_desc"><?php if($info['risk_type'] == 'custom'){echo ucwords($info['sub_risk']);}else{ echo ucwords(getHazards_New($info['risk'], $info['sub_risk'], $con)); }; ?></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Risk Description:</label>
                                <div class="r_desc"><?php echo ucfirst($info['description']); ?></div>
                            </div>
                        </div>
                        
                        <div class='div_divider'></div>
                        
                        <!-- Evaluation -->
                        <div class="card-header hh">
                            <h3 class="d-inline">Risk Evaluation</h3>
                            <i class="fas fa-question btn btn-primary btn-icon btn-small btn-help header-a" id="likeli_conseq"></i>
                        </div>
                        
                        <div class="card-body">
                            <div class="row custom-row">
                                <div class="form-group col-lg-4 col-12">
                                    <label>Likelihood</label>
                                    <div class="r_desc"><?php echo getLikelihood($info['likelihood'], $con);?></div>
                                </div>
                                <div class="form-group col-lg-4 col-12">
                                    <label>Consequence</label>
                                    <div class="r_desc"><?php echo getConsequence($info['consequence'] , $con); ?></div>
                                </div>
                                <div class="form-group col-lg-4 col-12">
                                    <label>Evaluation Rating</label>
                                    <div class="r_desc"><?php echo rating($info['likelihood'], $info['consequence'], $con); ?></div>
                                </div>
                            </div>
                        </div>
                        
                        
                        <div class='div_divider'></div>

                        <!-- Controls -->
                        <div class="card-header hh">
                            <h3 class="d-inline">Control Actions</h3>
                            <i class="fas fa-question btn btn-primary btn-icon btn-small btn-help header-a" id="swal-custom-2"></i>
                        </div>
                        
                        <div class="card-body">
                            <div class="form-group">
                                <label class="help-label">
                                    Selected Controls
                                </label>
                                <div class="r_desc">
                                    <ul>
                                    <?php 
                                        $controls = unserialize($info['control']);
                                        foreach($controls as $control){
                                    ?>
                                    <li>
                                        <?php 
                                        if($info['control_type'] == 'recommended'){
                                            echo ucfirst(getControlTitle($info['risk'], $control, $con)); 
                                        }else if($info['control_type'] == 'saved'){
                                            echo ucfirst(getControlTitle_Saved($info['risk'], $control, $con)); 
                                        }else{
                                            echo ucfirst(getControlTitle_Custom($info['risk'], $control, $con)); 
                                        }
                                            
                                        ?>
                                    </li>
                                    <?php } ?>
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Control Effectiveness</label>
                                <div class="r_desc"><?php echo ucfirst($info['control_effectiveness']); ?></div>
                            </div>
                            <div class="form-group">
                                <label>Action Type</label>
                                <div class="r_desc"><?php echo getlistActions($info['control_action'], $con); ?> </div>
                            </div>
                        </div>
                        
                        <div class='div_divider'></div>

                        <!-- Treatment -->
                        <div class="card-header hh">
                            <h3 class="d-inline">Treatment Plans</h3>
                            <i class="fas fa-question btn btn-primary btn-icon btn-small btn-help header-a" id="swal-custom-1"></i>
                        </div>
                        
                        <div class="card-body">
                            <div class="form-group">
                                <label class="help-label">
                                    Selected Treatments
                                </label>
                                <div class="r_desc">
                                    <ul>
                                    <?php 
                                        $treatments = unserialize($info['treatment']);
                                        foreach($treatments as $treatment){
                                    ?>
                                    <li><?php echo ucfirst(getAssessmentTreatment($info['treatment_type'], $treatment, $company_id, $con)); ?></li>
                                    <?php } ?>
                                    </ul>
                                </div>
                            </div>
                            
                            <div class='row custom-row'>
                            <div class="form-group col-lg-8 col-12">
                                <label>Action Owner</label>
                                <div class="r_desc"><?php echo ucwords($info['owner']);  ?></div>
                            </div>
                            <div class="form-group col-lg-4 col-12">
                                <label>Due Date</label>
                                <div class="r_desc"><?php echo date("Y-m-d", strtotime($info['due_date'])); ?></div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php }else{ ?>
                <div class="card">
                    <div class="card-body" style="display:flex;justify-content:center;align-items:center;min-height:500px;">
                        <div>
                            <h3 style="display:flex;justify-content:center;align-items:center;width:100%;">Error 402!!</h3>
                            <div style="display:flex;justify-content:center;align-items:center;width:100%;">Risk Does Not Exist!!</div>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <?php }else{ ?>
                <div class="card">
                    <div class="card-body" style="display:flex;justify-content:center;align-items:center;min-height:500px;">
                        <div>
                            <h3 style="display:flex;justify-content:center;align-items:center;width:100%;">Error 402!!</h3>
                            <div style="display:flex;justify-content:center;align-items:center;width:100%;">Missing Parameters!!</div>
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
        .assessment-hr{
            margin: 20px 30px 40px 30px;
        }
        .card{
            margin: 10px 0px;
        }
        .main-footer{
            margin-top: 10px !important;
        }
        ::placeholder { 
            text-transform: capitalize;
            opacity: 1; /* Firefox */
        }

        :-ms-input-placeholder { 
            text-transform: capitalize;
        }

        ::-ms-input-placeholder { 
            text-transform: capitalize;
        }
    </style>
    
</body>

</html>