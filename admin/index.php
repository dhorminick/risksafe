<?php
    session_start();
    $file_dir = '../';
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: /login');
        exit();
    }
    $message = [];
    include '../layout/db.php';
    include '../layout/admin__config.php';
    $accnt_dir = "admin/";
    require 'ajax/index.php';
    
    require 'ajax/_index.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title><?php echo ucwords($company_name); ?> | Admin | <?php echo $siteEndTitle; ?></title>
  <?php require '../layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/admin.custom.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/bundles/izitoast/css/iziToast.min.css">
  
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <?php require '../layout/header.php' ?>
        <?php require '../layout/sidebar_admin.php' ?>
        <!-- Main Content -->
        <div class="main-content">
          
            <section class="section">
            <div class="section-body">
                <!-- <div class="card custom-card" style="margin-top: 25px;"> -->
                <div class="card custom-card">
                  <div class="card-body">
                    <section class="header-section" style="height: 200px;">
                      <div class="">
                        <h1 class="h">RiskSafe - Admin Panel</h1>
                        <div class="header-text">
                          <div>Countless Tools for Conducting Risk Assessments, Auditing Controls, Tracking Incidents, Creating Treatment Plans and Manage Compliance risks at your Immediate disposal.</div>
                        </div>
                      </div>
                    </section>
                    <div class="row" style="margin-top: 25px;">
                      <div class="col-lg-4 col-detail-custom col-md-4">
                        <div class="panel panel-info ">
                          <div class="panel-heading medium effectivegrn primary">
                            Risk Assessment
                          </div>
                          <div class="panel-body">
                            Conduct a Risk Assessment for your organization easily.
                            <ul>
                              <li>Document Key Risks relevant to your organization's processes.</li>
                              <li>Understand the severity of risks according to the risk matrix.</li>
                              <li>Create Treatment Plans to reduce risks.</li>
                            </ul>
                          </div>
                          <div class="panel-footer right_button">
                            <a href="assessments/new-assessment" type="button" class="btn btn-md btn-det-custom effective primary btn-icon icon-left" btn="try_assessment"> <i class="fas fa-plus"></i> Create Assessment</a>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-4 col-detail-custom col-md-4">
                        <div class="panel panel-default">
                          <div class="panel-heading redhead medium primary">
                            Monitoring
                          </div>
                          <div class="panel-body">
                            Monitor your Controls through the Audit module.
                            <ul>
                              <li>Record your current Controls to reduce your current risks.</li>
                              <li>Create an Audit of Controls.</li>
                              <li>Assess the effectiveness of Controls.</li>
                            </ul>
                          </div>
                          <div class="panel-footer right_button">
                            <a href="monitoring/new-audit" type="button" class="btn btn-md btn-det-custom redhead primary btn-icon icon-left" btn="try_monitoring"><i class="fas fa-plus"></i> Create Audit</a>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-4 col-detail-custom col-md-4">
                        <div class="panel panel-warning">
                          <div class="panel-heading ambarhead medium primary">
                            Reporting
                          </div>
                          <div class="panel-body">
                            Record Incidents and create a Business Impact Analysis.
                            <ul>
                              <li>Log your Incidents using our Incidents Register.</li>
                              <li>Create a Business Impact Analysis to record your contingencies.</li>
                              <li>Extract Risk Assessments, Controls, Treatments and Incidents on Excel.</li>
                            </ul>
                          </div>
                          <div class="panel-footer right_button">
                            <a href="report/risk-reports" type="button" class="btn btn-md btn-det-custom ambarhead primary btn-icon icon-left" btn="try_reporting">View Reports</a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                
                <?php $auditSummary = getAuditData($company_id, $con); ?>
                <div class="card custom-card">
                    <div class="card-header">
                        <h3 class="!text-[20px]">Audits</h3>
                    </div>
                    <div class="card-body">
                        <div class='row'>
                            <div class='col-12 col-lg-6'>
                                <canvas id="auditChart"></canvas>
                            </div>
                            <div class='col-12 col-lg-6 audit-stats'>
                                <div class='grid grid-cols-2 gap-[10px]'>
                                    <div class='card-c shadow-md'>
                                        <div>
                                            <label>% Effective</label>
                                            <?php echo calcPercentage($auditSummary['effective'], $auditSummary['sum']); ?>
                                        </div>
                                    </div>
                                    <div class='card-c shadow-md'>
                                        <div>
                                            <label>Effective</label>
                                            <?php echo $auditSummary['effective']; ?>
                                        </div>
                                    </div>
                                    <div class='card-c shadow-md'>
                                        <div>
                                            <label>Ineffective</label>
                                            <?php echo $auditSummary['ineffective']; ?>
                                        </div>
                                    </div>
                                    <div class='card-c shadow-md'>
                                        <div>
                                            <label>Unassessed</label>
                                            <?php echo $auditSummary['unassessed']; ?>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class='card-c shadow-md mt-[10px]'>
                                    <div>
                                        <label>Total Audits</label>
                                        <?php echo $auditSummary['sum']; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php $riskSummary = getRisksData($company_id, $con); ?>
                <div class="card custom-card">
                    <div class="card-header">
                        <h3 class="!text-[20px]">Risks Management</h3>
                    </div>
                    <div class="card-body">
                        <div class='row risk-stats'>
                            <div class='col-12 col-lg-4'>
                                <div>Risk Summary:</div>
                                <table class='stats '>
                                    <thead>
                                        <tr>
                                            <th class='left'>Risk Rating</th>
                                            <th>Total</th>
                                            <th>%</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class='left'>
                                                <span class="stat bg-[red]"> Very High</span>
                                            </td>
                                            <td><?php echo $riskSummary['veryHigh']; ?></td>
                                            <td><?php echo calcPercentage($riskSummary['veryHigh'], $riskSummary['sum']); ?>%</td>
                                        </tr>
                                        <tr>
                                            <td class='left'>
                                                <span class="stat bg-[orange]"> High</span>
                                            </td>
                                            <td><?php echo $riskSummary['high']; ?></td>
                                            <td><?php echo calcPercentage($riskSummary['high'], $riskSummary['sum']); ?>%</</td>
                                        </tr>
                                        <tr>
                                            <td class='left'>
                                                <span class="stat bg-[yellow]"> Medium</span>
                                            </td>
                                            <td><?php echo $riskSummary['medium']; ?></td>
                                            <td><?php echo calcPercentage($riskSummary['medium'], $riskSummary['sum']); ?>%</</td>
                                        </tr>
                                        <tr>
                                            <td class='left'>
                                                <span class="stat bg-[green]"> Low</span>
                                            </td>
                                            <td><?php echo $riskSummary['low']; ?></td>
                                            <td><?php echo calcPercentage($riskSummary['low'], $riskSummary['sum']); ?>%</</td>
                                        </tr>
                                        <tr>
                                            <td class='left'>Total</td>
                                            <td><?php echo $riskSummary['sum']; ?></td>
                                            <td>100%</</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class='col-12 col-lg-8'>
                                <div>Risk Metrics:</div>
                                <table class='' width="100%" border="1" cellspacing="0" cellpadding="3">
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
                                                <?php echo countDataChart($company_id, 1, 1, $con); ?></td>
                                            <td width="14%" align="center" valign="middle" class='c-w' bgcolor="#FF9900">
                                                <?php echo countDataChart($company_id, 1, 2, $con); ?></td>
                                            <td width="14%" align="center" valign="middle" class='c-w' bgcolor="#FF0000">
                                                <?php echo countDataChart($company_id, 1, 3, $con); ?></td>
                                            <td width="14%" align="center" valign="middle" class='c-w' bgcolor="#FF0000">
                                                <?php echo countDataChart($company_id, 1, 4, $con); ?></td>
                                            <td width="14%" align="center" valign="middle" class='c-w' bgcolor="#FF0000">
                                                <?php echo countDataChart($company_id, 1, 5, $con); ?></td>
                                            <td width="12%" align="center" valign="middle">
                                                <strong><?php echo countDataLikelihood($company_id, 1, $con); ?></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="14%" align="center" valign="middle" bgcolor="#EAEAEA">Likely
                                            </td>
                                            <td width="14%" align="center" valign="middle" class='c-b' bgcolor="#FFFF00">
                                                <?php echo countDataChart($company_id, 2, 1, $con); ?></td>
                                            <td width="14%" align="center" valign="middle" class='c-w' bgcolor="#FF9900">
                                                <?php echo countDataChart($company_id, 2, 2, $con); ?></td>
                                            <td width="14%" align="center" valign="middle" class='c-w' bgcolor="#FF9900">
                                                <?php echo countDataChart($company_id, 2, 3, $con); ?></td>
                                            <td width="14%" align="center" valign="middle" class='c-w' bgcolor="#FF0000">
                                                <?php echo countDataChart($company_id, 2, 4, $con); ?></td>
                                            <td width="14%" align="center" valign="middle" class='c-w' bgcolor="#FF0000">
                                                <?php echo countDataChart($company_id, 2, 5, $con); ?></td>
                                            <td width="12%" align="center" valign="middle">
                                                <strong><?php echo countDataLikelihood($company_id, 2, $con); ?></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="14%" align="center" valign="middle" bgcolor="#EAEAEA">Possible
                                            </td>
                                            <td width="14%" align="center" valign="middle" class='c-w' bgcolor="#00FF00">
                                                <?php echo countDataChart($company_id, 3, 1, $con); ?></td>
                                            <td width="14%" align="center" valign="middle" class='c-b' bgcolor="#FFFF00">
                                                <?php echo countDataChart($company_id, 3, 2, $con); ?></td>
                                            <td width="14%" align="center" valign="middle" class='c-w' bgcolor="#FF9900">
                                                <?php echo countDataChart($company_id, 3, 3, $con); ?></td>
                                            <td width="14%" align="center" valign="middle" class='c-w' bgcolor="#FF9900">
                                                <?php echo countDataChart($company_id, 3, 4, $con); ?></td>
                                            <td width="14%" align="center" valign="middle" class='c-w' bgcolor="#FF0000">
                                                <?php echo countDataChart($company_id, 3, 5, $con); ?></td>
                                            <td width="12%" align="center" valign="middle">
                                                <strong><?php echo countDataLikelihood($company_id, 3, $con); ?></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="14%" align="center" valign="middle" bgcolor="#EAEAEA">Unlikely
                                            </td>
                                            <td width="14%" align="center" valign="middle" class='c-w' bgcolor="#00FF00">
                                                <?php echo countDataChart($company_id, 4, 1, $con); ?></td>
                                            <td width="14%" align="center" valign="middle" class='c-w' bgcolor="#00FF00">
                                                <?php echo countDataChart($company_id, 4, 2, $con); ?></td>
                                            <td width="14%" align="center" valign="middle" class='c-b' bgcolor="#FFFF00">
                                                <?php echo countDataChart($company_id, 4, 3, $con); ?></td>
                                            <td width="14%" align="center" valign="middle" class='c-b' bgcolor="#FFFF00">
                                                <?php echo countDataChart($company_id, 4, 4, $con); ?></td>
                                            <td width="14%" align="center" valign="middle" class='c-w' bgcolor="#FF9900">
                                                <?php echo countDataChart($company_id, 4, 5, $con); ?></td>
                                            <td width="12%" align="center" valign="middle">
                                                <strong><?php echo countDataLikelihood($company_id, 4, $con); ?></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="14%" align="center" valign="middle" bgcolor="#EAEAEA">Rare</td>
                                            <td width="14%" align="center" valign="middle" class='c-w' bgcolor="#00FF00">
                                                <?php echo countDataChart($company_id, 5, 1, $con); ?></td>
                                            <td width="14%" align="center" valign="middle" class='c-w' bgcolor="#00FF00">
                                                <?php echo countDataChart($company_id, 5, 2, $con); ?></td>
                                            <td width="14%" align="center" valign="middle" class='c-w' bgcolor="#00FF00">
                                                <?php echo countDataChart($company_id, 5, 3, $con); ?></td>
                                            <td width="14%" align="center" valign="middle" class='c-b' bgcolor="#FFFF00">
                                                <?php echo countDataChart($company_id, 5, 4, $con); ?></td>
                                            <td width="14%" align="center" valign="middle" class='c-b' bgcolor="#FFFF00">
                                                <?php echo countDataChart($company_id, 5, 5, $con); ?></td>
                                            <td width="12%" align="center" valign="middle">
                                                <strong><?php echo countDataLikelihood($company_id, 5, $con); ?></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="14%" align="center" valign="middle" bgcolor="#EAEAEA">
                                                <strong>Totals</strong>
                                            </td>
                                            <td width="14%" align="center" valign="middle">
                                                <strong><?php echo countDataConsequence($company_id, 1, $con); ?></strong>
                                            </td>
                                            <td width="14%" align="center" valign="middle">
                                                <strong><?php echo countDataConsequence($company_id, 2, $con); ?></strong>
                                            </td>
                                            <td width="14%" align="center" valign="middle">
                                                <strong><?php echo countDataConsequence($company_id, 3, $con); ?></strong>
                                            </td>
                                            <td width="14%" align="center" valign="middle">
                                                <strong><?php echo countDataConsequence($company_id, 4, $con); ?></strong>
                                            </td>
                                            <td width="14%" align="center" valign="middle">
                                                <strong><?php echo countDataConsequence($company_id, 5, $con); ?></strong>
                                            </td>
                                            <td width="12%" align="center" valign="middle">
                                                <strong><?php echo $riskSummary['sum']; ?></strong>
                                            </td>
                                        </tr>
                                </table>
                            </div>
                            <div class='col-12 mt-[50px]'>
                                <div>Top Risks:</div>
                                <?php if($riskSummary['toprisks'] !== false){ ?>
                                <table class='stats'>
                                    <thead>
                                        <tr>
                                            <th class='left'>UID</th>
                                            <th class='left'>Risk Category</th>
                                            <th class='left w-[40%]'>Risk</th>
                                            <th>Impact</th>
                                            <th>Likelihood</th>
                                            <th>Inherent Risk Level</th>
                                            <th>Residual Risk Level</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($riskSummary['toprisks'] as $item){ ?>
                                        <tr>
                                            <td class='left'><?php echo 'RO-'.strtoupper($item['id']); ?></td>
                                            <td class='left'><?php echo __getIndustryTitle($item['industry'], $con) ?></td>
                                            <td class='left'><?php echo getRiskTitle($item['risk_type'], $item['risk'], $con); ?></td>
                                            <td><?php echo round($item['consequence'], 2); ?></td>
                                            <td><?php echo round($item['likelihood'], 2); ?></td>
                                            <td><?php echo _getRating($item['rating']); ?></td>
                                            <td><?php echo _getRating($item['rating_residual']); ?></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <?php }else{ ?>
                                <div>
                                    No risks registered yet!
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                
              
                <!-- incidents -->
                <div class="card custom-card">
                  <div class="card-header">
                    <h3 class="d-inline">Incidents - (<?php echo $totaltreCount; ?>)</h3>
                    <a class="btn btn-primary btn-icon icon-left header-a" href="business/incidents"><i class="fas fa-arrow-left"></i> View All</a>
                  </div>
                  <div class="card-body">
                    <div class="row">        
                      <div class="col-lg-4 col-md-4">
                        <div class="panel panel-info">
                          <div class="panel-heading redhead medium">
                            Overdue Incidents
                          </div>
                          <div class="panel-body det">
                            <div role="progressbar" class="overdueIncident" aria-valuenow="<?php echo $openPercentage; ?>" aria-valuemin="0" aria-valuemax="100" style="--value: <?php echo $openPercentage; ?>"></div>
                            <span>Overdue Incidents - (<?php echo $OpenCount; ?>)</span>
                          </div>
                          <div class="panel-footer">
                            <a href="./business/incidents" type="button" class="btn btn-md btn-det-custom redhead progress-btn" btn="try_reporting">View Details</a>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-4 col-md-4">
                        <div class="panel panel-info">
                          <div class="panel-heading effectivegrn  medium">
                            Closed Incidents
                          </div>
                          <div class="panel-body det">
                            <div role="progressbar" class="closeincident" aria-valuenow="<?php echo $closedPercentage; ?>" aria-valuemin="0" aria-valuemax="100" style="--value: <?php echo $closedPercentage; ?>"></div>
                            <span>Closed Incidents - (<?php echo $CloseCount; ?>)</span>
                          </div>
                          <div class="panel-footer">
                            <a href="./business/incidents" type="button" class="btn btn-md btn-det-custom effectivegrn progress-btn" btn="try_reporting">View Details</a>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-4 col-md-4">
                        <div class="panel panel-info">
                          <div class="panel-heading ambarhead medium">
                            In Progress Incidents
                          </div>
                          <div class="panel-body det">
                            <div role="progressbar" class="inprogress" aria-valuenow="<?php echo $progressPercentage; ?>" aria-valuemin="0" aria-valuemax="100" style="--value: <?php echo $progressPercentage; ?>"></div>
                            <span>In Progress Incidents - (<?php echo $progressCount; ?>)</span>
                          </div>
                          <div class="panel-footer right_button">
                            <a href="./business/incidents" type="button" class="btn btn-md btn-det-custom ambarhead progress-btn" btn="try_reporting">View Details</a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                
                
                <!-- treatments -->
                <div class="card custom-card">
                  <div class="card-header">
                    <h3 class="d-inline">Treatments - (<?php echo $totaltreCount; ?>)</h3>
                    <a class="btn btn-primary btn-icon icon-left header-a" href="monitoring/treatments"><i class="fas fa-arrow-left"></i> View All</a>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-lg-4 col-md-4">
                        <div class="panel panel-info">
                          <div class="panel-heading redhead medium">
                            Complete Treatments
                          </div>
                          <div class="panel-body det">
                            <div role="progressbar" class="CompleteTreatments" aria-valuenow="<?php echo $completePercentage; ?>" aria-valuemin="0" aria-valuemax="100" style="--value: <?php echo $completePercentage; ?>"></div>
                            <span>Complete Treatments - (<?php echo $successCount; ?>)</span>
                          </div>
                          <div class="panel-footer">
                            <a href="./monitoring/treatments" type="button" class="btn btn-md redhead btn-det-custom progress-btn" btn="try_reporting">View Details</a>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-4 col-md-4">
                        <div class="panel panel-info">
                          <div class="panel-heading effectivegrn medium">
                            Closed Treatments
                          </div>
                          <div class="panel-body det">
                            <div role="progressbar" class="ClosedTreatments" aria-valuenow="<?php echo $cancelPercentage; ?>" aria-valuemin="0" aria-valuemax="100" style="--value: <?php echo $cancelPercentage; ?>"></div>
                            <span>Closed Treatments - (<?php echo $CancelledCount; ?>)</span>
                          </div>
                          <div class="panel-footer">
                            <a href="./monitoring/treatments" type="button" class="btn btn-md btn-det-custom effectivegrn progress-btn" btn="try_reporting">View Details</a>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-4 col-md-4">
                        <div class="panel panel-info">
                          <div class="panel-heading ambarhead medium">
                            In Progress Treatments
                          </div>
                          <div class="panel-body det">
                            <div role="progressbar" class="InProgressTreatments" aria-valuenow="<?php echo $tprogressPercentage; ?>" aria-valuemin="0" aria-valuemax="100" style="--value: <?php echo $tprogressPercentage; ?>"></div>
                            <span>In Progress Treatments - (<?php echo $progress_Count; ?>)</span>
                          </div>
                          <div class="panel-footer right_button">
                            <a href="./monitoring/treatments" type="button" class="btn btn-md btn-det-custom ambarhead progress-btn" btn="try_reporting">View Details</a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                
                
                <!-- compliance -->
                <div class="card custom-card">
                  <div class="card-header">
                    <h3 class="d-inline">Compliance Standard - (<?php echo $totalComplianceCount; ?>)</h3>
                    <a class="btn btn-primary btn-icon icon-left header-a" href="compliances/all.php"><i class="fas fa-arrow-left"></i> View All</a>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-lg-4 col-md-4">
                        <div class="panel panel-info">
                          <div class="panel-heading redhead medium"> Ineffective Compliance Standards </div>
                          <div class="panel-body det">
                            <div role="progressbar" class="CompleteTreatments" aria-valuenow="<?php echo $ineffectiveComplinacePercentage; ?>" aria-valuemin="0" aria-valuemax="100" style="--value: <?php echo $ineffectiveComplinacePercentage; ?>"></div>
                            <span>Ineffective Compliance Standards - (<?php echo $ineffectiveComplianceCount; ?>)</span>
                          </div>
                          <div class="panel-footer">
                            <a href="./compliances/all" type="button" class="btn btn-md redhead btn-det-custom progress-btn" btn="try_reporting">View Details</a>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-4 col-md-4">
                        <div class="panel panel-info">
                          <div class="panel-heading effectivegrn medium">
                          Effective Compliance Standards
                          </div>
                          <div class="panel-body det">
                            <div role="progressbar" class="ClosedTreatments" aria-valuenow="<?php echo $effectiveComplinacePercentage; ?>" aria-valuemin="0" aria-valuemax="100" style="--value: <?php echo $effectiveComplinacePercentage; ?>"></div>
                            <span>Effective Compliance Standards - (<?php echo $effectivComplianceCount; ?>)</span>
                          </div>
                          <div class="panel-footer">
                            <a href="./compliances/all" type="button" class="btn btn-md btn-det-custom effectivegrn progress-btn" btn="try_reporting">View Details</a>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-4 col-md-4">
                        <div class="panel panel-info">
                          <div class="panel-heading ambarhead medium">
                          Overdue Policy Reviews - (<?php echo $totalPoliciesCount; ?>)
                          </div>
                          <div class="panel-body det">
                            <div role="progressbar" class="OverduePolicesReview" aria-valuenow="<?php echo $overduePoliciesPercentage; ?>" aria-valuemin="0" aria-valuemax="100" style="--value: <?php echo $overduePoliciesPercentage; ?>"></div>
                            <span> Overdue Reviews - (<?php echo $OverdueCount; ?>)</span>
                          </div>
                          <div class="panel-footer right_button">
                            <a href="./compliances/applicable-policy" type="button" class="btn btn-md btn-det-custom ambarhead progress-btn" btn="try_reporting">View Details</a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Users -->
                <div class="card">
                    <div class="card-body">
                        <?php include '../layout/alert.php'; ?>
                        <div class="card-header">
                            <h3 class="d-inline center-sm">Company Registered Users</h3>
                            <div class="header-a" style='display:flex;'>
                            <?php if($role == 'admin') { ?>
                            <div class="hide-sm"><a href="account/users?add=user" class="btn btn-primary btn-icon icon-left"><i class="fas fa-plus-circle"></i> Add User</a></div>
                            <?php }else{ ?>
                            <div class="hide-sm"><button class="btn btn-primary btn-icon icon-left notAllowed" data-toggle="modal" data-target="#notAllowed"><i class="fas fa-plus-circle"></i> Add User</button></div>
                            <?php } ?>
                            <div class="hide-sm" style='margin-left:10px;'><a href="account/users" class="btn btn-outline-primary btn-icon icon-right">Manage All User <i class="fas fa-arrow-right"></i></a></div>
                            </div>
                        </div>
                        <div class="card-body" id="users">
                            <?php 
                                $GetPrevPayment = "SELECT * FROM users WHERE company_id = '$company_id' ORDER BY iduser DESC LIMIT 10";
                                $PrevPayment = $con->query($GetPrevPayment);
                                if ($PrevPayment->num_rows > 0) {
                                    $p_row = $PrevPayment->fetch_assoc();
                                    $detailss = $p_row['company_users'];
                                    $details = unserialize($detailss);
                            ?>
                            <table class="payment-data">
                                <tr>
                                    <th style="width: 5%;">S/N</th>
                                    <th>Email</th>
                                    <th>Full Name</th>
                                    <th>Role</th>
                                </tr>
                            <?php if ($detailss === 'a:0:{}') { ?> 
                            </table> <div class="empty-table" style='margin-top:10px;'>No User Registered Yet!!</div> 
                            <?php }else{ ?>
                            <?php $o = 0; foreach ($details as $datta){$o++; ?>
                                <tr>
                                    <td><?php echo $o; ?></td>
                                    <td><?php echo $datta['email']; ?></td>
                                    <td><?php echo ucwords($datta['fullname']); ?></td>
                                    <td><?php echo ucwords($datta['role']); ?></td>
                                </tr>
                            <?php } ?>
                            </table> <?php } ?>
                            <?php }else{ ?>
                            <div class="empty-table">Error</div>
                            <?php } ?>
                            <?php if($role == 'admin') { ?>
                            <div class="pay-td show-sm"><a href="account/users?add=user" class="btn btn-primary btn-icon icon-left"><i class="fas fa-plus-circle"></i> Add User</a></div>
                            <?php }else{ ?>
                            <div class="pay-td show-sm"><button class="btn btn-primary btn-icon icon-left notAllowed" data-toggle="modal" data-target="#notAllowed"><i class="fas fa-plus-circle"></i> Add User</button></div>
                            <?php } ?>
                            
                        </div>  
                    </div>
                </div>
            </div>
            </section>
        </div>
        <?php if($role == 'admin') { ?>
        <!-- basic modal -->
        <div class="modal fade" id="deleteUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
          aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Confirm Delete:</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" style="text-align:center;">
                Are You Sure You Want To Delete User:
                <p id="userEmail" style="width: 100%;text-align:center;"></p>
                <form id="del">
                    <input type="hidden" name="email" id="formEmail" value="">
                    <input type="hidden" name="id" id="formId" value="">
                    <button class="btn btn-primary btn-icon icon-left btn-delete-user"><i class="fas fa-trash"></i> Delete User</button>
                </form>
              </div>
            </div>
          </div>
        </div>
        <?php }else{ ?>
        <div class="modal fade" id="notAllowed" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
          aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Access Denied!!</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                Only Admins Are Allowed To Create New Users.
              </div>
            </div>
          </div>
        </div>
        <?php } ?>
        <?php require '../layout/footer.php' ?>
        </footer>
        <div class="res"></div>
        </div>
    </div>
    <?php require '../layout/general_js.php' ?>
    <script src="<?php echo $file_dir; ?>assets/bundles/chartjs/chart.min.js"></script>
    <script>
      var ctx = document.getElementById("riskChart").getContext('2d');
      var myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
          datasets: [{
            data: [ <?php echo $chartData; ?> ],
            backgroundColor: [ <?php echo $chartColour; ?> ],
            label: 'Dataset 1'
          }],
          labels: [<?php echo $chartLabel; ?> ],
        },
        options: {
          responsive: true,
          legend: {
            position: 'none',
          },
        }
      });

      var ctx = document.getElementById('myPieChart').getContext('2d');
      var myPieChart = new Chart(ctx, {
        type: 'pie',
        data: {
          labels: <?php echo json_encode($labels); ?>,
          datasets: [{
            data: <?php echo json_encode($data); ?>,
            backgroundColor: [
              'red',
              'blue',
              'green',
              'yellow',
              'purple',
              'orange',
              'pink'
            ]
          }]
        },
        options: {
          responsive: true,
          legend: {
            position: 'bottom',
          },
        }
      });
      
      var ctx = document.getElementById('auditChart').getContext('2d');
      var myPieChart = new Chart(ctx, {
        type: 'pie',
        data:  {
          labels: [
            'Ineffective',
            'Effective',
            'Unassessed'
          ],
          datasets: [{
            label: 'Audit Summary',
            data: <?php echo json_encode($auditSummary['chart']); ?>,
            backgroundColor: [
              'rgb(255, 99, 132)',
              'rgb(54, 162, 235)',
              'rgb(255, 205, 86)'
            ],
            hoverOffset: 4
          }]
        },
        options: {
          responsive: true,
          legend: {
            position: 'right',
          },
        }
      });
      
      
    </script>
    
    <!-- Page Specific JS File -->
    <script src="<?php echo $file_dir; ?>assets/js/admin.custom.js"></script>
    <script src="<?php echo $file_dir; ?>assets/bundles/izitoast/js/iziToast.min.js"></script>
    <?php if($role == 'admin') { ?>
    <script>
        $("#delete-user").click(function () {
            var user = $("#delete-user").attr('user');
            var email = $("#delete-user").attr('email');

            if (email && user && email != null && user != null || email && user && email != '' && user != '') {
                $("#userEmail").text(email);
                $("#formEmail").val(email);
                $("#formId").val(user);
            } else {
                alert('Error 402');
                window.location.refresh;
                
            }
        });
        $("#del").submit(function (event) {
            event.preventDefault();
            var formValues = $(this).serialize();

            $.post("ajax/users", {
                deleteUser: formValues,
            }).done(function (data) {
              $(".close").click();
              $(".res").addClass('show');
              $(".res").html(data);
              $("#users").load(" #users > *");
            });
        });
    </script>
    <?php } ?>
    <style lang='scss'>
      .custom-card{
        padding: 10px;
      }
      .pay-td a{
        width: 100%;
      }
      .btn.btn-primary.btn-icon.icon-left.header-a{
        background-color: black;
      }
      .btn.btn-primary.btn-icon.icon-left.header-a:hover{
        background-color: black !important;
        opacity: 0.8;
      }
      
      .stat{
          padding: 5px 7px;
          font-size: 13px;
          border-radius: 10px;
          color:white;
          font-weight:bold;
      }
      
      table.stats {
          th, td {
              padding: 7px;
          }
          th:not(.left), td:not(.left){
              text-align: center;
          }
      }
      .risk-stats{
          table, th, td {
              border: 1px solid black;
            }
            table{
                margin-top:10px;
                width:100%;
                min-height:270px;
                border-radius:10px;
            }
      }
      .txt-red{
          color: red;
      }
      .txt-orange{
          color: orange;
      }
      .txt-yellow{
          color: yellow;
      }
      .txt-green{
          color: green;
      }
      .audit-stats{
          
          
          .card-c{
              background-color: white;
              border-radius:5px;
              min-height: 100px;          
              display:flex;
              flex-direction: column;
              justify-content:center;
              align-items:center;
              padding:20px;
              width:100%;
              
              div{
                display:flex;
                flex-direction: column; 
                font-weight:normal !important;
                text-align:center;
                
                label{
                   font-weight:bolder !important; 
                }
              }
          }
      }
      
    </style>
</body>
</html>