<?php
    session_start();
    $file_dir = '../';
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: /auth/sign-in');
        exit();
    }
    $message = [];
    include '../layout/db.php';
    include '../layout/admin__config.php';
    $accnt_dir = "admin/";
    require 'ajax/index.php';
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

                <div class="card custom-card">
                  <div class="card-header">
                    <h3 class="d-inline">Audit Controls - (<?php echo $totalCount; ?>)</h3>
                    <a class="btn btn-primary btn-icon icon-left header-a" href="monitoring/audits"><i class="fas fa-arrow-left"></i> View All</a>
                  </div>
                  <div class="card-body">
                    <div class="row">
                    <div class="col-lg-4 col-md-4">
                      <div class="panel panel-info">
                        <div class="panel-heading redhead medium">
                          Not effective
                        </div>
                        <div class="panel-body det">
                          <div role="progressbar" class="ineffective" aria-valuenow="<?php echo $ineffectivePercentage; ?>" aria-valuemin="0" aria-valuemax="100" style="--value: <?php echo $ineffectivePercentage; ?>"></div>
                          <span>Not effective Controls - (<?php echo $ineffectiveCount; ?>)</span>
                        </div>
                        <div class="panel-footer">
                          <a href="./audits.php" type="button" style="border:none" class="btn btn-md redhead btn-det-custom progress-btn" btn="try_reporting">View Details</a>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-4 col-md-4">
                      <div class="panel panel-info">
                        <div class="panel-heading effectivegrn medium">
                          Effective
                        </div>
                        <div class="panel-body det">
                          <div role="progressbar" class="effective" aria-valuenow="<?php echo $effectivePercentage; ?>" aria-valuemin="0" aria-valuemax="100" style="--value: <?php echo $effectivePercentage; ?> "></div>
                          <span>Effective Controls - (<?php echo $effectiveCount; ?>)</span>
                        </div>
                        <div class="panel-footer">
                          <a href="./audits.php" type="button" style="border:none" class="btn btn-md btn-det-custom effectivegrn progress-btn" btn="try_reporting">View Details</a>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-4 col-md-4">
                      <div class="panel panel-info">
                        <div class="panel-heading ambarhead medium">
                          Not Tested
                        </div>
                        <div class="panel-body det">
                          <div role="progressbar" class="nottested" aria-valuenow="<?php echo $notSelectedPercentages; ?>" aria-valuemin="0" aria-valuemax="100" style="--value: <?php echo $notSelectedPercentages; ?>"></div>
                          <span>Not Tested Controls - (<?php echo $not_slectedCount; ?>)</span>
                          </div>
                        <div class="panel-footer right_button">
                          <a href="./audits.php" type="button" style="border:none" class="btn btn-md btn-det-custom ambarhead progress-btn" btn="try_reporting">View Details</a>
                        </div>
                      </div>
                    </div>
                    </div>
                  </div>
                </div>

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
                            <a href="./incidents" type="button" class="btn btn-md btn-det-custom redhead progress-btn" btn="try_reporting">View Details</a>
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
                            <a href="./incidents" type="button" class="btn btn-md btn-det-custom effectivegrn progress-btn" btn="try_reporting">View Details</a>
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
                            <a href="./incidents" type="button" class="btn btn-md btn-det-custom ambarhead progress-btn" btn="try_reporting">View Details</a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

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
                            <a href="./treatments" type="button" class="btn btn-md redhead btn-det-custom progress-btn" btn="try_reporting">View Details</a>
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
                            <a href="./treatments" type="button" class="btn btn-md btn-det-custom effectivegrn progress-btn" btn="try_reporting">View Details</a>
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
                            <a href="./treatments" type="button" class="btn btn-md btn-det-custom ambarhead progress-btn" btn="try_reporting">View Details</a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

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
                            <a href="./compliances" type="button" class="btn btn-md redhead btn-det-custom progress-btn" btn="try_reporting">View Details</a>
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
                            <a href="./compliances" type="button" class="btn btn-md btn-det-custom effectivegrn progress-btn" btn="try_reporting">View Details</a>
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
                            <a href="./applicables" type="button" class="btn btn-md btn-det-custom ambarhead progress-btn" btn="try_reporting">View Details</a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="card custom-card">
                  <div class="card-header">
                    <h3 class="d-inline">Risk Charts</h1>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-lg-6 col-md-6">
                        <div class="panel panel-info">
                          <div class="panel-heading medium canva">
                            Risk Chart - By Priority
                          </div>
                          <div class="panel-body canva">
                            <canvas id="riskChart"></canvas>
                            <ul class="p-t-30 list-unstyled">
                              <li class="padding-5"><span><i class="fa fa-circle m-r-5 col-red"></i></span>Very High Risks<span
                                  class="float-right"><?php echo $vhighCount; ?>%</span></li>
                              <li class="padding-5"><span><i class="fa fa-circle m-r-5 col-orange"></i></span>High Risks<span
                                  class="float-right"><?php echo $highCount; ?>%</span></li>
                              <li class="padding-5"><span><i class="fa fa-circle m-r-5 col-yellow"></i></span>Medium Risks<span
                                  class="float-right"><?php echo $mediumCount; ?>%</span></li>
                                  <li class="padding-5"><span><i class="fa fa-circle m-r-5 col-green"></i></span>Low Risks<span
                                  class="float-right"><?php echo $lowCount; ?>%</span></li>
                            </ul>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6">
                        <div class="panel panel-info">
                          <div class="panel-heading medium canva">
                            Risk - by Priority
                          </div>
                          <div class="panel-body">
                            <?php if($data !== []) { ?>
                            <canvas id="myPieChart"></canvas>
                            <?php }else{ ?>
                            <div class="nodata">-- No Data To Show --</div>
                            <?php } ?>
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
        <?php require '../layout/user_footer.php' ?>
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
    <style>
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
      
    </style>
</body>
</html>