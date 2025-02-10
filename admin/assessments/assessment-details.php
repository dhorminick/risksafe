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

    switch ($role) {
        case 'user':
            $whois = 'Your Admin';
            break;
        
        case 'admin':
            $whois = 'RiskSafe <a href="/contact-us?issue=missing-assessment" class="bb">Tech Support</a>';
            break;
        
        default:
            $whois = 'error';
            break;
    }
    
    if (isset($_POST['delete-data']) && isset($_POST['data-id'])){
        // $type = sanitizePlus($_POST['data-type']);
        $id = sanitizePlus($_POST['data-id']);

        if (!$id || $id == null || $id == '') {
            array_push($message, 'Error While Deleting Data: Missing Parameters!!');
        } else {
            $query="DELETE FROM as_assessment_new WHERE risk_id = '$id' AND c_id = '$company_id'";
            $dataDeleted = $con->query($query);
                    
            if ($dataDeleted) {
                array_push($message, 'Risk Deleted Successfully!!');
            }else{
                array_push($message, 'Error 502: Error Deleting Risk!!');
            }
        }
        
    }
    
    if (isset($_GET['id']) && isset($_GET['id']) !== "") {

        $ass_Id = sanitizePlus($_GET['id']);
        $toDisplay = true;
        #confirm assessment
        $CheckIfAssessmentExist = "SELECT * FROM as_assessment LEFT JOIN as_types ON as_assessment.as_type  = as_types.idtype WHERE as_id = '$ass_Id'";
        $AssessmentExist = $con->query($CheckIfAssessmentExist);
        if ($AssessmentExist->num_rows > 0) {	
            $ass_exist = true;	
			$assessment_details = $AssessmentExist->fetch_assoc();
			$assess_id = $ass_Id;
			
            $user = $assessment_details['as_user'];
            // $type = $assessment_details['as_type'];
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
            $riskType = $assessment_details['industry'];
            
            $editLink = "edit-assessment?id=".$ass_Id;
            
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

            $totalrisks = rowCountTotal($con, "as_assessment_new", "assessment", $ass_Id, "c_id", $company_id);
            
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
  <title>Assessment Details | <?php echo $siteEndTitle; ?></title>
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
                <?php if ($toDisplay == true) { ?>
                <?php if ($ass_exist == true) { ?>
                <div class="card">
                    <div class="card-header">
                        <h3 class="subtitle d-inline">Assessment Details:</h3>
                        <a class="btn btn-primary btn-icon icon-left header-a" href="<?php echo $editLink; ?>"><i class="fas fa-pen"></i> Edit</a>
                    </div>
                    <div class="card-body">
                        <div class="row section-rows customs">
                            <div class="user-description col-12 col-lg-12">
                                <label>Assessment Industry :</label>
                                <div class="description-text"><?php echo ucwords(getIndustryTitle($riskType, $con)); ?></div>
                            </div>
                            
                            <hr>
                            <div class="user-description col-12">
                                <label>Assessment Team :</label>
                                <div class="description-text"><?php echo $team; ?></div>
                            </div>
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
                            <div class="user-description col-12 col-lg-4">
                                <label>Assessor :</label>
                                <div class="description-text"><?php echo $assessor; ?></div>
                            </div>
                            <div class="user-description col-12 col-lg-4">
                                <label>Assessment Approval :</label>
                                <div class="description-text"><?php echo $approval; ?></div>
                            </div>
                            <div class="user-description col-12 col-lg-4">
                                <label>Issued On :</label>
                                <div class="description-text"><?php echo $date; ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="card-header">
                        <h3 class="subtitle d-inline">Covered Risks:</h3>
                        <a class="btn btn-primary btn-icon icon-left header-a" href="add-risks?id=<?php echo $ass_Id; ?>"><i class="fas fa-plus"></i> Add Risks</a>
                    </div>
                    <div class="card-body">
                        
                        <?php
                            $CheckIfAssessmentExist = "SELECT * FROM as_assessment_new WHERE assessment = '$ass_Id' ORDER BY id";
                            $AssessmentExist = $con->query($CheckIfAssessmentExist);
                            if ($AssessmentExist->num_rows > 0) { $ass_has_data = true; $i = 0;
                        ?>
                        <table class="table table-striped table-bordered table-hover" id="table">
                            <tr>
                                <th>S/N</th>
                                <th>Risk</th>
                                <th>Risk Hazard</th>
                                <th style='width:20%;'>...</th>
                            </tr>
                        <?php 
                            while($item = $AssessmentExist->fetch_assoc()){ 
                                $i++;
                                $viewLink = 'risks?id='.$item["risk_id"].'"';
                                $editLink = 'edit-risks?id='.$item["risk_id"].'"';
                                $deleteLink = 'javascript:void(0);" class="delete action-icons btn btn-danger btn-action mr-1" data-toggle="modal" data-target="#deleteModal" data-type="risks" data-id="'.$item["risk_id"];
                        ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php if($item['risk_type'] == 'custom'){echo ucwords(getCustomRisks_New($item['risk'], $con));}else{ echo ucwords(getRisks_New($item['risk'], $con)); }; ?></td>
                                <td><?php if($item['risk_type'] == 'custom'){echo ucwords($item['sub_risk']);}else{ echo ucwords(getHazards_New($item['risk'], $item['sub_risk'], $con)); }; ?></td>
                                <td>
                                    <a href="<?php echo $viewLink; ?>" class="action-icons btn btn-primary btn-action mr-1"><i class="fas fa-eye"></i></a>
                                    <a href="<?php echo $editLink; ?>" class="action-icons btn btn-info btn-action mr-1"><i class="fas fa-edit"></i></a>
                                    <a href="<?php echo $deleteLink; ?>"><i class="fas fa-trash-alt"></i></a>
                                </td>
                            </tr>
                        <?php } ?>
                        </table>
                        <?php }else{ $ass_has_data = false; ?>
                        
                        <div style="width:100%;min-height:400px;display:flex;justify-content:center;align-items:center;">
                            <div style="text-align: center;"> 
                                <h3>Empty Data!!</h3>
                                No Risk Covered For This Assessment Yet,
                                <p><a href="add-risks?id=<?php echo $ass_Id; ?>" class="btn btn-primary btn-icon icon-left mt-2"><i class="fas fa-plus"></i> Add Risks</a></p>
                            </div>
                        </div>
                        
                        <?php } ?>
                        
                        <?php if($ass_has_data == true){ ?>
                        <div class="card-header"><h4>Risk Chart</h4></div>
                            <div class="card-body">
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
                                            <td width="3%" rowspan="6" align="center" valign="middle" class="tbl_rotate" style='transform: rotate(-90deg);'><strong>Likelihood</strong></td>
                                            <td width="14%" align="center" valign="middle" bgcolor="#EAEAEA">Almost
                                                certain</td>
                                            <td width="14%" align="center" valign="middle" class='c-b' bgcolor="#FFFF00">
                                                <?php echo countChart_New($assess_id, $company_id, 1, 1, $con); ?></td>
                                            <td width="14%" align="center" valign="middle" class='c-w' bgcolor="#FF9900">
                                                <?php echo countChart_New($assess_id, $company_id, 1, 2, $con); ?></td>
                                            <td width="14%" align="center" valign="middle" class='c-w' bgcolor="#FF0000">
                                                <?php echo countChart_New($assess_id, $company_id, 1, 3, $con); ?></td>
                                            <td width="14%" align="center" valign="middle" class='c-w' bgcolor="#FF0000">
                                                <?php echo countChart_New($assess_id, $company_id, 1, 4, $con); ?></td>
                                            <td width="14%" align="center" valign="middle" class='c-w' bgcolor="#FF0000">
                                                <?php echo countChart_New($assess_id, $company_id, 1, 5, $con); ?></td>
                                            <td width="12%" align="center" valign="middle">
                                                <strong><?php echo countLikelihood_New($assess_id, $company_id, 1, $con); ?></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="14%" align="center" valign="middle" bgcolor="#EAEAEA">Likely
                                            </td>
                                            <td width="14%" align="center" valign="middle" class='c-b' bgcolor="#FFFF00">
                                                <?php echo countChart_New($assess_id, $company_id, 2, 1, $con); ?></td>
                                            <td width="14%" align="center" valign="middle" class='c-w' bgcolor="#FF9900">
                                                <?php echo countChart_New($assess_id, $company_id, 2, 2, $con); ?></td>
                                            <td width="14%" align="center" valign="middle" class='c-w' bgcolor="#FF9900">
                                                <?php echo countChart_New($assess_id, $company_id, 2, 3, $con); ?></td>
                                            <td width="14%" align="center" valign="middle" class='c-w' bgcolor="#FF0000">
                                                <?php echo countChart_New($assess_id, $company_id, 2, 4, $con); ?></td>
                                            <td width="14%" align="center" valign="middle" class='c-w' bgcolor="#FF0000">
                                                <?php echo countChart_New($assess_id, $company_id, 2, 5, $con); ?></td>
                                            <td width="12%" align="center" valign="middle">
                                                <strong><?php echo countLikelihood_New($assess_id, $company_id, 2, $con); ?></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="14%" align="center" valign="middle" bgcolor="#EAEAEA">Possible
                                            </td>
                                            <td width="14%" align="center" valign="middle" class='c-w' bgcolor="#00FF00">
                                                <?php echo countChart_New($assess_id, $company_id, 3, 1, $con); ?></td>
                                            <td width="14%" align="center" valign="middle" class='c-b' bgcolor="#FFFF00">
                                                <?php echo countChart_New($assess_id, $company_id, 3, 2, $con); ?></td>
                                            <td width="14%" align="center" valign="middle" class='c-w' bgcolor="#FF9900">
                                                <?php echo countChart_New($assess_id, $company_id, 3, 3, $con); ?></td>
                                            <td width="14%" align="center" valign="middle" class='c-w' bgcolor="#FF9900">
                                                <?php echo countChart_New($assess_id, $company_id, 3, 4, $con); ?></td>
                                            <td width="14%" align="center" valign="middle" class='c-w' bgcolor="#FF0000">
                                                <?php echo countChart_New($assess_id, $company_id, 3, 5, $con); ?></td>
                                            <td width="12%" align="center" valign="middle">
                                                <strong><?php echo countLikelihood_New($assess_id, $company_id, 3, $con); ?></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="14%" align="center" valign="middle" bgcolor="#EAEAEA">Unlikely
                                            </td>
                                            <td width="14%" align="center" valign="middle" class='c-w' bgcolor="#00FF00">
                                                <?php echo countChart_New($assess_id, $company_id, 4, 1, $con); ?></td>
                                            <td width="14%" align="center" valign="middle" class='c-w' bgcolor="#00FF00">
                                                <?php echo countChart_New($assess_id, $company_id, 4, 2, $con); ?></td>
                                            <td width="14%" align="center" valign="middle" class='c-b' bgcolor="#FFFF00">
                                                <?php echo countChart_New($assess_id, $company_id, 4, 3, $con); ?></td>
                                            <td width="14%" align="center" valign="middle" class='c-b' bgcolor="#FFFF00">
                                                <?php echo countChart_New($assess_id, $company_id, 4, 4, $con); ?></td>
                                            <td width="14%" align="center" valign="middle" class='c-w' bgcolor="#FF9900">
                                                <?php echo countChart_New($assess_id, $company_id, 4, 5, $con); ?></td>
                                            <td width="12%" align="center" valign="middle">
                                                <strong><?php echo countLikelihood_New($assess_id, $company_id, 4, $con); ?></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="14%" align="center" valign="middle" bgcolor="#EAEAEA">Rare</td>
                                            <td width="14%" align="center" valign="middle" class='c-w' bgcolor="#00FF00">
                                                <?php echo countChart_New($assess_id, $company_id, 5, 1, $con); ?></td>
                                            <td width="14%" align="center" valign="middle" class='c-w' bgcolor="#00FF00">
                                                <?php echo countChart_New($assess_id, $company_id, 5, 2, $con); ?></td>
                                            <td width="14%" align="center" valign="middle" class='c-w' bgcolor="#00FF00">
                                                <?php echo countChart_New($assess_id, $company_id, 5, 3, $con); ?></td>
                                            <td width="14%" align="center" valign="middle" class='c-b' bgcolor="#FFFF00">
                                                <?php echo countChart_New($assess_id, $company_id, 5, 4, $con); ?></td>
                                            <td width="14%" align="center" valign="middle" class='c-b' bgcolor="#FFFF00">
                                                <?php echo countChart_New($assess_id, $company_id, 5, 5, $con); ?></td>
                                            <td width="12%" align="center" valign="middle">
                                                <strong><?php echo countLikelihood_New($assess_id, $company_id, 5, $con); ?></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="14%" align="center" valign="middle" bgcolor="#EAEAEA">
                                                <strong>Totals</strong>
                                            </td>
                                            <td width="14%" align="center" valign="middle">
                                                <strong><?php echo countConsequence_New($assess_id, $company_id, 1, $con); ?></strong>
                                            </td>
                                            <td width="14%" align="center" valign="middle">
                                                <strong><?php echo countConsequence_New($assess_id, $company_id, 2, $con); ?></strong>
                                            </td>
                                            <td width="14%" align="center" valign="middle">
                                                <strong><?php echo countConsequence_New($assess_id, $company_id, 3, $con); ?></strong>
                                            </td>
                                            <td width="14%" align="center" valign="middle">
                                                <strong><?php echo countConsequence_New($assess_id, $company_id, 4, $con); ?></strong>
                                            </td>
                                            <td width="14%" align="center" valign="middle">
                                                <strong><?php echo countConsequence_New($assess_id, $company_id, 5, $con); ?></strong>
                                            </td>
                                            <td width="12%" align="center" valign="middle">
                                                <strong><?php echo $totalrisks; ?></strong>
                                            </td>
                                        </tr>
                                </table>
                            </div>
                        
                            <div class="card-header"><h4>Risk totals</h4></div>
                            <div class="card-body">
                                <table width="30%" border="1" cellspacing="0" cellpadding="3">
                                        <tr>
                                            <td width="60%" align="left" valign="middle">&nbsp;</td>
                                            <td align="center" valign="middle" bgcolor="#EAEAEA"><strong>Total</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="60%" align="left" valign="middle">&nbsp;Extreme risks</td>
                                            <td align="center" valign="middle" bgcolor="#FF0000" class='c-w'>
                                                <strong><?php echo countRisks_New($assess_id, $company_id, 4, $con); ?></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="60%" align="left" valign="middle">&nbsp;High risks</td>
                                            <td align="center" valign="middle" bgcolor="#FF9900" class='c-w'>
                                                <strong><?php echo countRisks_New($assess_id, $company_id, 3, $con); ?></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="60%" align="left" valign="middle">&nbsp;Medium risks</td>
                                            <td align="center" valign="middle c-b" bgcolor="#FFFF00" class='c-b'>
                                                <strong><?php echo countRisks_New($assess_id, $company_id, 2, $con); ?></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="60%" align="left" valign="middle">&nbsp;Low risks</td>
                                            <td align="center" valign="middle" bgcolor="#00FF00" class='c-w'>
                                                <strong><?php echo countRisks_New($assess_id, $company_id, 1, $con); ?></strong>
                                            </td>
                                        </tr>
                                    </table>
                            </div>
                        <?php }else{} ?>
                                
                </div><!-- close -->
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
        
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Confirm Action</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="font-weight: 400;">
                    Are You Sure You Want To Delete This Risk?
                </div>
                <div class="modal-footer bg-whitesmoke">
                    <form id='deleteData' style="width:100%;" method='post' action=''>
                        <input type="hidden" name="data-id" id="data-id" required>
                        <input type="hidden" name="data-type" id="data-type" required>
                        <button type="submit" class="btn btn-primary btn-icon icon-left" name="delete-data" style="width:100%;"><i class="fas fa-trash-alt"></i> Delete <span class="view-type" style="text-transform: capitalize;"></span></button>
                    </form>
                </div>
            </div>
          </div>
    </div>
        <?php #require '../../layout/delete_data.php' ?>
        <?php require '../../layout/footer.php' ?>
        </footer>
        </div>
    </div>
    <?php require '../../layout/general_js.php' ?>
    <script src="<?php echo $file_dir; ?>assets/bundles/prism/prism.js"></script>
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
        .title_text{
            
        }
        table#table{
            border-radius: 5px !important;
        }
        .c-w{
            color:black !important;
            font-weight:bolder;
        }
        .c-b{
            color:black !important;
            font-weight:bolder;
        }
    </style>
    <script>
		 $(".delete").click(function(e) { 
            var id = $(this).attr('data-id');
            var type = $(this).attr('data-type');
            if (id == '' || !id || id == null || type == '' || !type || type == null) {
                alert('Error 402!!');
                //refresh
                //window.location.assign("audits");
            } else {
                $("#data-id").val();
                $("#data-id").val(id);
                $("#data-type").val();
                $("#data-type").val(type);
                $("#view-id").html();
                $("#view-id").html(id);
                $(".view-type").html();
                $(".view-type").html(type);
            }
        });
	</script>
</body>

</html>