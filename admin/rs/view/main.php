<?php
include_once("../controller/auth.php");
include_once("../config.php");
include("chart.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include_once("header.php"); ?>
  <script>

  </script>
  <style>
    .total-count {
      font-size: 30px;
    }

    .panel-info>.effective {
      background: rgb(0 153 255);
      color: white;
      /* background: linear-gradient(90deg, rgba(0, 46, 76, 1) 0%, rgba(0, 153, 255, 1) 35%, rgba(0, 107, 179, 1) 100%); */
    }

    .panel-footer>.effective {
      background: rgb(0 153 255);
      color: white;
      /* background: linear-gradient(90deg, rgba(0, 46, 76, 1) 0%, rgba(0, 153, 255, 1) 35%, rgba(0, 107, 179, 1) 100%); */
    }

    .panel-info>.effectivegrn,
    .panel-footer>.effectivegrn {
      background: rgb(27, 76, 0);
      color: white;
      /* background: linear-gradient(49deg, rgba(27, 76, 0, 1) 0%, rgba(0, 128, 0, 1) 35%, rgba(127, 179, 0, 1) 100%); */
    }

    .panel-footer>.effectivegrn:hover,
    .panel-footer>.effectivegrn:active {
      background: rgb(255 191 0);
      color: white;
      /* background: linear-gradient(49deg, rgba(2, 77, 2, 1) 0%, rgba(0, 128, 0, 1) 35%, rgba(92, 222, 92, 1) 100%); */
    }

    .panel-warning>.ambarhead,
    .panel-info>.ambarhead,
    .right_button>.ambarhead {
      background: rgb(255 191 0);
      color: white;
      /* background: linear-gradient(49deg, rgba(126, 74, 1, 1) 0%, rgba(236, 151, 31, 1) 35%, rgba(221, 134, 11, 1) 100%); */
    }

    .right_button>.ambarhead:hover,
    .right_button>.ambarhead:active {
      background: rgb(166, 108, 26);
      /* background: linear-gradient(49deg, rgba(166, 108, 26, 1) 0%, rgba(173, 107, 13, 1) 35%, rgba(233, 156, 47, 1) 100%); */
    }

    .panel-info>.redhead,
    .panel-footer>.redhead {
      background: rgb(255, 0, 0);
      color: white;
      /* background: linear-gradient(49deg, rgba(96, 0, 0, 1) 0%, rgba(230, 29, 29, 1) 35%, rgba(240, 128, 128, 1) 100%); */
    }

    .panel-footer>.redhead:hover,
    .panel-footer>.redhead:active {
      background: rgb(255, 0, 0);
      color: white;
      /* background: linear-gradient(49deg, rgba(152, 40, 40, 1) 0%, rgba(170, 6, 6, 1) 35%, rgba(182, 88, 88, 1) 100%); */
    }

    .panel-default>.silverhead,
    .right_button>.silverhead {
      background: rgb(255, 0, 0);
      color:#fff;
      /* background: linear-gradient(49deg, rgba(84, 103, 116, 1) 0%, rgba(145, 187, 215, 1) 35%, rgba(185, 222, 247, 1) 100%); */
    }

    .btn {
      border: none;
    }
    .panel{
      height: 300px;
      display: flex;
      flex-direction: column;
    }
    .panel-footer{
      margin-top: auto;
    }
    .panel-body div[role="progressbar"]{
      margin-bottom: 20px !important;
    }
  </style>
</head>

<body>
  <!-- header -->
  <div id="top-nav" class="navbar navbar-inverse navbar-static-top" style="background-color:#09F;border:0;">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
        <a class="navbar-brand" href="main.php" style="font-weight:900;color:#fff;"><?php echo APP_TITLE; ?></a>
      </div>
      <div class="navbar-collapse collapse">
        <ul class="nav navbar-nav navbar-right">
          <li class="dropdown"> <a class="dropdown-toggle" role="button" data-toggle="dropdown" href="#" style="font-weight:900;color:#fff;"><i class="glyphicon glyphicon-user"></i> Account <span class="caret"></span></a>
            <ul id="g-account-menu" class="dropdown-menu" role="menu">
              <?php include_once("menu_top.php"); ?>
            </ul>
          </li>
        </ul>
      </div>
    </div>
    <!-- /container -->
  </div>
  <!-- /Header -->

  <!-- Main -->
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-3 col-sm-12">
        <!-- Left column -->
        <?php include_once("menu.php"); ?>
        <!-- /col-3 -->
      </div>
      <div class="col-lg-9 col-sm-12">
        <div>
          <h1 class="page-header">Welcome to RiskSafe</h1>
        </div>
        <div class="row">
          <div class="col-lg-4 col-md-4">
            <div class="panel panel-info ">
              <div class="panel-heading medium effective">
                Risk assessment
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
                <a href="./newassessment.php" type="button" class="btn btn-md btn-info effective" btn="try_assessment">Try it now!</a>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-4">
            <div class="panel panel-default">
              <div class="panel-heading silverhead medium">
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
                <a href="./audit.php?action=add" type="button" class="btn btn-md btn-default silverhead" btn="try_monitoring">Try it now!</a>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-4">
            <div class="panel panel-warning">
              <div class="panel-heading ambarhead medium">
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
                <a href="./reports.php" type="button" class="btn btn-md ambarhead btn-warning" btn="try_reporting">Try it now!</a>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="card-header">
            <h1 class="page-header">Audit Controls</h1>
          </div>
          <div class="col-lg-4 col-md-4">
            <div class="panel panel-info">
              <div class="panel-heading effectivegrn medium">
                Effective
              </div>
              <div class="panel-body">
                <div role="progressbar" class="effective" aria-valuenow="<?php echo $effectivePercentage; ?>" aria-valuemin="0" aria-valuemax="100" style="--value: <?php echo $effectivePercentage; ?> "></div>
                <span>Effective Controls <?php echo $effectiveCount; ?></span>
                <span class="text-right">Total Controls <?php echo $totalCount; ?></span>
              </div>
              <div class="panel-footer">
                <a href="./audits.php" type="button" style="border:none" class="btn btn-md btn-info effectivegrn progress-btn" btn="try_reporting">View Details</a>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-4">
            <div class="panel panel-info">
              <div class="panel-heading redhead medium">
                Not effective
              </div>
              <div class="panel-body">
                <div role="progressbar" class="ineffective" aria-valuenow="<?php echo $ineffectivePercentage; ?>" aria-valuemin="0" aria-valuemax="100" style="--value: <?php echo $ineffectivePercentage; ?>"></div>
                <span>Not effective Controls <?php echo $ineffectiveCount; ?></span>
                <span class="text-right">Total Controls <?php echo $totalCount; ?></span>
              </div>
              <div class="panel-footer">
                <a href="./audits.php" type="button" style="border:none" class="btn btn-md redhead btn-info progress-btn" btn="try_reporting">View Details</a>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-4">
            <div class="panel panel-info">
              <div class="panel-heading ambarhead medium">
                Not Tested
              </div>
              <div class="panel-body">
                <div role="progressbar" class="nottested" aria-valuenow="<?php echo $notSelectedPercentages; ?>" aria-valuemin="0" aria-valuemax="100" style="--value: <?php echo $notSelectedPercentages; ?>"></div>
                <span>Not Tested Controls <?php echo $not_slectedCount; ?></span>
                <span class="text-right">Total Controls <?php echo $totalCount; ?></span>
              </div>
              <div class="panel-footer right_button">
                <a href="./audits.php" type="button" style="border:none" class="btn btn-md btn-info ambarhead progress-btn" btn="try_reporting">View Details</a>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="card-header">
            <h1 class="page-header">Incidents</h1>
          </div>
          <div class="col-lg-4 col-md-4">
            <div class="panel panel-info">
              <div class="panel-heading redhead medium">
                Overdue Incidents
              </div>
              <div class="panel-body">
                <div role="progressbar" class="overdueIncident" aria-valuenow="<?php echo $openPercentage; ?>" aria-valuemin="0" aria-valuemax="100" style="--value: <?php echo $openPercentage; ?>"></div>
                <span>Overdue Incidents <?php echo $OpenCount; ?></span>
                <span class="text-right">Total Incidents <?php echo $totalincidentsCount; ?></span>
              </div>
              <div class="panel-footer">
                <a href="./incidents.php" type="button" class="btn btn-md btn-info redhead progress-btn" btn="try_reporting">View Details</a>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-4">
            <div class="panel panel-info">
              <div class="panel-heading effectivegrn  medium">
                Closed Incidents
              </div>
              <div class="panel-body">
                <div role="progressbar" class="closeincident" aria-valuenow="<?php echo $closedPercentage; ?>" aria-valuemin="0" aria-valuemax="100" style="--value: <?php echo $closedPercentage; ?>"></div>
                <span>Closed Incidents <?php echo $CloseCount; ?></span>
                <span class="text-right">Total Incidents <?php echo $totalincidentsCount; ?></span>
              </div>
              <div class="panel-footer">
                <a href="./incidents.php" type="button" class="btn btn-md btn-info effectivegrn progress-btn" btn="try_reporting">View Details</a>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-4">
            <div class="panel panel-info">
              <div class="panel-heading ambarhead medium">
                In Progress Incidents
              </div>
              <div class="panel-body">
                <div role="progressbar" class="inprogress" aria-valuenow="<?php echo $progressPercentage; ?>" aria-valuemin="0" aria-valuemax="100" style="--value: <?php echo $progressPercentage; ?>"></div>
                <span>In Progress Incidents <?php echo $progressCount; ?></span>
                <span class="text-right">Total Incidents <?php echo $totalincidentsCount; ?></span>
              </div>
              <div class="panel-footer right_button">
                <a href="./incidents.php" type="button" class="btn btn-md btn-info ambarhead progress-btn" btn="try_reporting">View Details</a>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="card-header">
            <h1 class="page-header">Treatments</h1>
          </div>
          <div class="col-lg-4 col-md-4">
            <div class="panel panel-info">
              <div class="panel-heading redhead medium">
                Complete Treatments
              </div>
              <div class="panel-body">
                <div role="progressbar" class="CompleteTreatments" aria-valuenow="<?php echo $completePercentage; ?>" aria-valuemin="0" aria-valuemax="100" style="--value: <?php echo $completePercentage; ?>"></div>
                <span>Complete Treatments <?php echo $successCount; ?></span>
                <span class="text-right">Total Treatments <?php echo $totaltreCount; ?></span>
              </div>
              <div class="panel-footer">
                <a href="./treatments.php" type="button" class="btn btn-md redhead btn-info progress-btn" btn="try_reporting">View Details</a>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-4">
            <div class="panel panel-info">
              <div class="panel-heading effectivegrn medium">
                Closed Treatments
              </div>
              <div class="panel-body">
                <div role="progressbar" class="ClosedTreatments" aria-valuenow="<?php echo $cancelPercentage; ?>" aria-valuemin="0" aria-valuemax="100" style="--value: <?php echo $cancelPercentage; ?>"></div>
                <span>Closed Treatments <?php echo $CancelledCount; ?></span>
                <span class="text-right">Total Treatments <?php echo $totaltreCount; ?></span>
              </div>
              <div class="panel-footer">
                <a href="./treatments.php" type="button" class="btn btn-md btn-info effectivegrn progress-btn" btn="try_reporting">View Details</a>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-4">
            <div class="panel panel-info">
              <div class="panel-heading ambarhead medium">
                In Progress Treatments
              </div>
              <div class="panel-body">
                <div role="progressbar" class="InProgressTreatments" aria-valuenow="<?php echo $tprogressPercentage; ?>" aria-valuemin="0" aria-valuemax="100" style="--value: <?php echo $tprogressPercentage; ?>"></div>
                <span>In Progress Treatments <?php echo $progress_Count; ?></span>
                <span class="text-right">Total Treatments <?php echo $totaltreCount; ?></span>
              </div>
              <div class="panel-footer right_button">
                <a href="./treatments.php" type="button" class="btn btn-md btn-info ambarhead progress-btn" btn="try_reporting">View Details</a>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
              <div class="card-header">
                <h1 class="page-header">Compliance Standard</h1>
              </div>
              <div class="col-lg-4 col-md-4">
              <div class="panel panel-info">
                <div class="panel-heading redhead medium">
                Ineffective Compliance Standards
                </div>
                <div class="panel-body">
                  <div role="progressbar" class="CompleteTreatments" aria-valuenow="<?php echo $ineffectiveComplinacePercentage; ?>" aria-valuemin="0" aria-valuemax="100" style="--value: <?php echo $ineffectiveComplinacePercentage; ?>"></div>
                  <span>Ineffective <?php echo $ineffectiveComplianceCount; ?></span>
                  <span class="text-right">Total Compliance Standards <?php echo $totalComplianceCount; ?></span>
                </div>
                <div class="panel-footer">
                  <a href="./compliances.php" type="button" class="btn btn-md redhead btn-info progress-btn" btn="try_reporting">View Details</a>
                </div>
              </div>
              </div>
              <div class="col-lg-4 col-md-4">
              <div class="panel panel-info">
                <div class="panel-heading effectivegrn medium">
                Effective Compliance Standards
                </div>
                <div class="panel-body">
                  <div role="progressbar" class="ClosedTreatments" aria-valuenow="<?php echo $effectiveComplinacePercentage; ?>" aria-valuemin="0" aria-valuemax="100" style="--value: <?php echo $effectiveComplinacePercentage; ?>"></div>
                  <span>Effective <?php echo $effectivComplianceCount; ?></span>
                  <span class="text-right">Total Compliance Standards <?php echo $totalComplianceCount; ?></span>
                </div>
                <div class="panel-footer">
                  <a href="./compliances.php" type="button" class="btn btn-md btn-info effectivegrn progress-btn" btn="try_reporting">View Details</a>
                </div>
              </div>
            </div>
            <div class="col-lg-4 col-md-4">
            <div class="panel panel-info">
              <div class="panel-heading ambarhead medium">
              Overdue Policy Reviews
              </div>
              <div class="panel-body">
                <div role="progressbar" class="OverduePolicesReview" aria-valuenow="<?php echo $overduePoliciesPercentage; ?>" aria-valuemin="0" aria-valuemax="100" style="--value: <?php echo $overduePoliciesPercentage; ?>"></div>
                <span> Overdue  <?php echo $OverdueCount; ?></span>
                <span class="text-right">Total Applicable Policy <?php echo $totalPoliciesCount; ?></span>
              </div>
              <div class="panel-footer right_button">
                <a href="./applicables.php" type="button" class="btn btn-md btn-info ambarhead progress-btn" btn="try_reporting">View Details</a>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="card-header">
            <h1 class="page-header">Risk</h1>
          </div>
          <div class="col-lg-6 col-md-6">
            <div class="panel panel-info">
              <div class="panel-heading medium">
                Risk - by Priority
              </div>
              <div class="panel-body">
                <canvas id="incidentChart"></canvas>
              </div>
            </div>
          </div>
          <div class="col-lg-6 col-md-6">
            <div class="panel panel-info">
              <div class="panel-heading medium">
                Risk - by Priority
              </div>
              <div class="panel-body">
              <canvas id="myPieChart" width="400" height="400"></canvas>
              </div>
            </div>
          </div>
        </div>
        
        </div>

       
      </div>
    </div>
  


    <!-- /Main -->
    <?php include_once("footer.php"); ?>

    <?php include("chart.php"); ?>
</body>

</html>