<?php
    session_start();
    $file_dir = '../../';
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'login?r=/reports/risk-reports');
        exit();
    }
    $message = [];
    include '../../layout/db.php';
    include '../../layout/admin__config.php';
    include '../ajax/reports.php';

    switch ($role) {
        case 'user':
            $whois = 'Your Admin';
            break;
        
        case 'admin':
            $whois = 'RiskSafe <a href="/contact-us?issue=missing-report" class="bb">Tech Support</a>';
            break;
        
        default:
            $whois = 'error';
            break;
    }

    if (isset($_GET['id']) && isset($_GET['id']) !== "") {

        $ass_Id = sanitizePlus($_GET['id']);
        $toDisplay = true;
        $totalrisks = rowCount($con, "as_details", "as_assessment", $ass_Id);
        #confirm assessment
        $CheckIfAssessmentExist = "SELECT * FROM as_assessment LEFT JOIN as_types ON as_assessment.as_type  = as_types.idtype WHERE as_id = '$ass_Id'";
        $AssessmentExist = $con->query($CheckIfAssessmentExist);
        if ($AssessmentExist->num_rows > 0) {	
            $ass_exist = true;	
			$assessment_details = $AssessmentExist->fetch_assoc();
            
            $user = $assessment_details['as_user'];
            $type = $assessment_details['as_type'];
            $team = $assessment_details['as_team'];
            $task = $assessment_details['as_task'];
            $descript = $assessment_details['as_descript'];
            $number = $assessment_details['as_number'];
            $owner = $assessment_details['as_owner'];
            $next = $assessment_details['as_next'];
            $assessor = $assessment_details['as_assessor'];
            $approval = $assessment_details['as_approval'];
            $completed = $assessment_details['as_completed'];
            $date = $assessment_details['as_date'];

            $query="SELECT * FROM as_details WHERE as_assessment = '$ass_Id'";
            $result = $con->query($query);
            if ($result->num_rows > 0) {
                $dataExist = true;
            }else{
                $dataExist = false;
            }

            switch ($approval) {

                case 1:
                    $approval = 'In progress';
                    break;

                case 2:
                    $approval = 'Approved';
                    break;

                case 3:
                    $approval = 'Closed';
                    break;
            }

		} else {
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
  <title>Report Details | <?php echo $siteEndTitle; ?></title>
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
                <div class="card">
                    <div class="card-header">
                        <h3 class="subtitle d-inline">Report Details:</h3>
                        <a class="btn btn-primary btn-icon icon-left header-a" href="../assessments/edit-assessment?id=<?php echo $ass_Id; ?>"><i class="fas fa-pen"></i> Edit <span class='hide-md' style='font-size:12px;'>Assessment</span></a>
                    </div>
                    <div class="card-body">
                        <div class="row section-rows customs">
                            <div class="user-description col-12 col-lg-6">
                                <label>Assessment Team :</label>
                                <div class="description-text"><?php echo $team; ?></div>
                            </div>
                            <div class="user-description col-12 col-lg-3">
                                <label>Issued On :</label>
                                <div class="description-text"><?php echo $date; ?></div>
                            </div>
                            <div class="user-description col-12 col-lg-3">
                                <label>Next Assessment :</label>
                                <div class="description-text"><?php echo $date; ?></div>
                            </div>
                            <hr>
                            <div class="user-description col-12">
                                <label>Assessment Task :</label>
                                <div class="description-text"><?php echo $task; ?></div>
                            </div>
                            <div class="user-description col-12">
                                <label>Assessment Description :</label>
                                <div class="description-text"><?php echo $descript; ?></div>
                            </div>
                            <div class="user-description col-12">
                                <label>Process Owner :</label>
                                <div class="description-text"><?php echo $owner; ?></div>
                            </div>
                            <div class="user-description col-12 col-lg-7">
                                <label>Assessor :</label>
                                <div class="description-text"><?php echo $assessor; ?></div>
                            </div>
                            <div class="user-description col-12 col-lg-4">
                                <label>Assessment Approval :</label>
                                <div class="description-text"><?php echo $approval; ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="card-header">
                        <h3 class="subtitle d-inline">Risk Chart:</h3>
                        <a class="btn btn-primary btn-icon icon-left header-a" href="../assessments/add-risks?id=<?php echo $ass_Id; ?>"><i class="fas fa-plus"></i> Add Risks</a>
                    </div>
                    <div class="card-body">
                        <?php if($dataExist !== true){ ?>
                            <div style="width:100%;text-align:center;margin:20px 0px;">
                                No Risk Added To This Assessment Yet,
                                <p><a href="../assessments/add-risk?sid=<?php echo $ass_Id; ?>" class="btn btn-primary btn-icon icon-left mt-2"><i class="fas fa-plus"></i> Add Risks</a></p>
                            </div>
                        <?php }else{ ?>
                            <div class="row">
                                <div class="col-12">
                                <!-- Main Chart -->
                                <table width="100%" border="1" cellspacing="0" cellpadding="3">
                                    <tr>
                                        <td colspan="2" rowspan="2" align="center" valign="middle">&nbsp;</td>
                                        <td colspan="6" align="center" valign="middle"><strong>Consequence</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="14%" align="center" valign="middle" bgcolor="#EAEAEA">
                                            Insignificant</td>
                                        <td width="14%" align="center" valign="middle" bgcolor="#EAEAEA">Minor</td>
                                        <td width="14%" align="center" valign="middle" bgcolor="#EAEAEA">Moderate
                                        </td>
                                        <td width="14%" align="center" valign="middle" bgcolor="#EAEAEA">Major</td>
                                        <td width="14%" align="center" valign="middle" bgcolor="#EAEAEA">Severe</td>
                                        <td width="12%" align="center" valign="middle" bgcolor="#EAEAEA">
                                            <strong>Totals</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="3%" rowspan="6" align="center" valign="middle" class="tbl_rotate"><strong>Likelihood</strong></td>
                                        <td width="14%" align="center" valign="middle" bgcolor="#EAEAEA">Almost
                                            certain</td>
                                        <td width="14%" align="center" valign="middle" bgcolor="#FFFF00">
                                            <?php echo countChart($ass_Id, 1, 1, $con); ?></td>
                                        <td width="14%" align="center" valign="middle" bgcolor="#FF9900">
                                            <?php echo countChart($ass_Id, 1, 2, $con); ?></td>
                                        <td width="14%" align="center" valign="middle" bgcolor="#FF0000">
                                            <?php echo countChart($ass_Id, 1, 3, $con); ?></td>
                                        <td width="14%" align="center" valign="middle" bgcolor="#FF0000">
                                            <?php echo countChart($ass_Id, 1, 4, $con); ?></td>
                                        <td width="14%" align="center" valign="middle" bgcolor="#FF0000">
                                            <?php echo countChart($ass_Id, 1, 5, $con); ?></td>
                                        <td width="12%" align="center" valign="middle">
                                            <strong><?php echo countLikelihood($ass_Id, 1, $con); ?></strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="14%" align="center" valign="middle" bgcolor="#EAEAEA">Likely
                                        </td>
                                        <td width="14%" align="center" valign="middle" bgcolor="#FFFF00">
                                            <?php echo countChart($ass_Id, 2, 1, $con); ?></td>
                                        <td width="14%" align="center" valign="middle" bgcolor="#FF9900">
                                            <?php echo countChart($ass_Id, 2, 2, $con); ?></td>
                                        <td width="14%" align="center" valign="middle" bgcolor="#FF9900">
                                            <?php echo countChart($ass_Id, 2, 3, $con); ?></td>
                                        <td width="14%" align="center" valign="middle" bgcolor="#FF0000">
                                            <?php echo countChart($ass_Id, 2, 4, $con); ?></td>
                                        <td width="14%" align="center" valign="middle" bgcolor="#FF0000">
                                            <?php echo countChart($ass_Id, 2, 5, $con); ?></td>
                                        <td width="12%" align="center" valign="middle">
                                            <strong><?php echo countLikelihood($ass_Id, 2, $con); ?></strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="14%" align="center" valign="middle" bgcolor="#EAEAEA">Possible
                                        </td>
                                        <td width="14%" align="center" valign="middle" bgcolor="#00FF00">
                                            <?php echo countChart($ass_Id, 3, 1, $con); ?></td>
                                        <td width="14%" align="center" valign="middle" bgcolor="#FFFF00">
                                            <?php echo countChart($ass_Id, 3, 2, $con); ?></td>
                                        <td width="14%" align="center" valign="middle" bgcolor="#FF9900">
                                            <?php echo countChart($ass_Id, 3, 3, $con); ?></td>
                                        <td width="14%" align="center" valign="middle" bgcolor="#FF9900">
                                            <?php echo countChart($ass_Id, 3, 4, $con); ?></td>
                                        <td width="14%" align="center" valign="middle" bgcolor="#FF0000">
                                            <?php echo countChart($ass_Id, 3, 5, $con); ?></td>
                                        <td width="12%" align="center" valign="middle">
                                            <strong><?php echo countLikelihood($ass_Id, 3, $con); ?></strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="14%" align="center" valign="middle" bgcolor="#EAEAEA">Unlikely
                                        </td>
                                        <td width="14%" align="center" valign="middle" bgcolor="#00FF00">
                                            <?php echo countChart($ass_Id, 4, 1, $con); ?></td>
                                        <td width="14%" align="center" valign="middle" bgcolor="#00FF00">
                                            <?php echo countChart($ass_Id, 4, 2, $con); ?></td>
                                        <td width="14%" align="center" valign="middle" bgcolor="#FFFF00">
                                            <?php echo countChart($ass_Id, 4, 3, $con); ?></td>
                                        <td width="14%" align="center" valign="middle" bgcolor="#FFFF00">
                                            <?php echo countChart($ass_Id, 4, 4, $con); ?></td>
                                        <td width="14%" align="center" valign="middle" bgcolor="#FF9900">
                                            <?php echo countChart($ass_Id, 4, 5, $con); ?></td>
                                        <td width="12%" align="center" valign="middle">
                                            <strong><?php echo countLikelihood($ass_Id, 4, $con); ?></strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="14%" align="center" valign="middle" bgcolor="#EAEAEA">Rare</td>
                                        <td width="14%" align="center" valign="middle" bgcolor="#00FF00">
                                            <?php echo countChart($ass_Id, 5, 1, $con); ?></td>
                                        <td width="14%" align="center" valign="middle" bgcolor="#00FF00">
                                            <?php echo countChart($ass_Id, 5, 2, $con); ?></td>
                                        <td width="14%" align="center" valign="middle" bgcolor="#00FF00">
                                            <?php echo countChart($ass_Id, 5, 3, $con); ?></td>
                                        <td width="14%" align="center" valign="middle" bgcolor="#FFFF00">
                                            <?php echo countChart($ass_Id, 5, 4, $con); ?></td>
                                        <td width="14%" align="center" valign="middle" bgcolor="#FFFF00">
                                            <?php echo countChart($ass_Id, 5, 5, $con); ?></td>
                                        <td width="12%" align="center" valign="middle">
                                            <strong><?php echo countLikelihood($ass_Id, 5, $con); ?></strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="14%" align="center" valign="middle" bgcolor="#EAEAEA">
                                            <strong>Totals</strong>
                                        </td>
                                        <td width="14%" align="center" valign="middle">
                                            <strong><?php echo countConsequence($ass_Id, 1, $con); ?></strong>
                                        </td>
                                        <td width="14%" align="center" valign="middle">
                                            <strong><?php echo countConsequence($ass_Id, 2, $con); ?></strong>
                                        </td>
                                        <td width="14%" align="center" valign="middle">
                                            <strong><?php echo countConsequence($ass_Id, 3, $con); ?></strong>
                                        </td>
                                        <td width="14%" align="center" valign="middle">
                                            <strong><?php echo countConsequence($ass_Id, 4, $con); ?></strong>
                                        </td>
                                        <td width="14%" align="center" valign="middle">
                                            <strong><?php echo countConsequence($ass_Id, 5, $con); ?></strong>
                                        </td>
                                        <td width="12%" align="center" valign="middle">
                                            <strong><?php echo $totalrisks; ?></strong>
                                        </td>
                                    </tr>
                                </table>
                                </div>
                                <div class="col-lg-5 col-12">
                                    <div class="table-header mt-4">Risk Totals:</div>
                                    <table width="100%" border="1" cellspacing="0" cellpadding="3">
                                        <tr>
                                            <td width="60%" align="left" valign="middle">&nbsp;</td>
                                            <td align="center" valign="middle" bgcolor="#EAEAEA"><strong>Total</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="60%" align="left" valign="middle">&nbsp;Extreme risks</td>
                                            <td align="center" valign="middle" bgcolor="#FF0000">
                                                <strong><?php echo countRisks($ass_Id, 4, $con); ?></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="60%" align="left" valign="middle">&nbsp;High risks</td>
                                            <td align="center" valign="middle" bgcolor="#FF9900">
                                                <strong><?php echo countRisks($ass_Id, 3, $con); ?></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="60%" align="left" valign="middle">&nbsp;Medium risks</td>
                                            <td align="center" valign="middle" bgcolor="#FFFF00">
                                                <strong><?php echo countRisks($ass_Id, 2, $con); ?></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="60%" align="left" valign="middle">&nbsp;Low risks</td>
                                            <td align="center" valign="middle" bgcolor="#00FF00">
                                                <strong><?php echo countRisks($ass_Id, 1, $con); ?></strong>
                                            </td>
                                        </tr>
                                    </table>

                                    <div class="table-header mt-4">Controls:</div>
                                    <table width="100%" border="1" cellspacing="0" cellpadding="3">
                                        <tr>
                                            <td width="60%" align="left" valign="middle">&nbsp;</td>
                                            <td align="center" valign="middle" bgcolor="#EAEAEA"><strong>Total</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="60%" align="left" valign="middle">&nbsp;Number of controls</td>
                                            <td align="center" valign="middle" bgcolor="#EAEAEA">
                                                <strong><?php echo countControls($ass_Id, $con); ?></strong>
                                            </td>
                                        </tr>
                                    </table>

                                    <div class="table-header mt-4">Treatments:</div>
                                    <table width="100%" border="1" cellspacing="0" cellpadding="3">
                                        <tr>
                                            <td width="60%" align="left" valign="middle">&nbsp;</td>
                                            <td align="center" valign="middle" bgcolor="#EAEAEA"><strong>Total</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="60%" align="left" valign="middle">&nbsp;Number of treatments</td>
                                            <td align="center" valign="middle" bgcolor="#EAEAEA">
                                                <strong><?php echo countTreatments($ass_Id, $con); ?></strong>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-lg-7"></div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <?php }else{ ?>
                <div class="card">
                    <div class="card-body" style="display:flex;justify-content:center;align-items:center;min-height:500px;">
                        <div>
                            <h3 style="display:flex;justify-content:center;align-items:center;width:100%;">Error 402!!</h3>
                            <div style="display:flex;justify-content:center;align-items:center;width:100%;font-weight:500;">Assessment Does Not Exist!!</div>
                            <div style="display:flex;justify-content:center;align-items:center;width:100%;font-size:15px;font-weight:400;">File May Have Been Moved or Deleted, Contact <?php echo $whois; ?> For More Information.</div>
                        </div>
                    </div>
                </div>
                <?php }}else{ ?>
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
    <style>
        textarea{
            min-height: 120px !important;
        }
        .bb{
            margin: 0px 5px;
        }
        .main-footer{
            margin-top: 15px !important;
        }
        .card{
            margin: 10px 0px;
            padding: 10px 0px;
        }
        .user-description + .user-description{
            margin-top: 10px;
        }
        .risk-desc {
        width: 100%;
        border-collapse: separate !important;
        }
        .risk-desc td {
        text-align: left;
        border: 2px solid #e4e6fc;
        border-radius: 0px 5px 5px 0px !important;
        padding: 10px 15px !important;
        width: 60%;
        font-weight: 400 !important;
        }
        td{
            font-weight: 400 !important;
        }
        .risk-desc th {
        text-align: right;
        border: 2px solid #e4e6fc;
        border-radius: 5px 0px 0px 5px !important;
        padding: 10px 15px !important;
        width: 40%;
        }
        table#table{
            border-radius: 5px !important;
        }
        .tbl_rotate {
            transform: rotate(-90.0deg);;
            -moz-transform: rotate(-90.0deg);
            -o-transform: rotate(-90.0deg);
            -webkit-transform: rotate(-90.0deg);
            filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083);
            -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083)";
        }
        .table-header{
            margin-bottom: 10px;
        }
    </style>
</body>

</html>