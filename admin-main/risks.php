<?php
    session_start();
    $file_dir = '../';

    if (isset($_SESSION["AdminloggedIn"]) == true || isset($_SESSION["AdminloggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: login');
    }
  
    $message = [];
    include $file_dir.'layout/db.php';
    include 'ajax/admin.php';
    include $file_dir.'layout/superadmin_config.php';

    if (isset($_GET['id']) && isset($_GET['id']) !== "") {
        $toDisplay = true;
        $id = strtolower(sanitizePlus($_GET['id']));
        
        $CheckIfAssessmentExist = "SELECT * FROM as_newrisk WHERE industry = '$id'";
        $AssessmentExist = $con->query($CheckIfAssessmentExist);
        if ($AssessmentExist->num_rows > 0) {
        //  $info = $AssessmentExist->fetch_assoc();
        }else{
            echo '<h1>ID Does Not Exist</h1>';
            exit();
        }
        
        
    }else{
        $toDisplay = false;
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>RiskSafe Clients | <?php echo $siteEndTitle; ?></title>
  <?php require $file_dir.'layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/admin.custom.css">
</head>

<body>
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <?php require $file_dir.'layout/admin_header.php' ?>
        <?php require $file_dir.'layout/sidebar_admin_admin.php' ?>
        <!-- Main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-body">
                    <?php if ($toDisplay == true) { ?>
                    <div class="row">
                      <div class="col-12">
                        <div class="card">
                          <div class="card-header">
                            <h4>Select Risk:</h4>
                            <div class="card-header-form">
                              <a href="/risks" class="btn btn-primary">Back <i class="fas fa-arrow-right"></i></a>
                            </div>
                          </div>
                          <div class="card-body p-0">
                            <div class="table-responsive" style="padding:10px 20px !important;">
                              <table class="table table-striped">
                                <tr>
                                  <th class="p-0 text-center">S/N</th>
                                  <th style='width:70%;'>Risk Title</th>
                                  <th>Action</th>
                                </tr>
                              <?php 
                                  $CheckIfAssessmentsExist = "SELECT * FROM as_newrisk WHERE industry = '$id'";
                                    $AssessmentsExist = $con->query($CheckIfAssessmentsExist);
                                    if ($AssessmentsExist->num_rows > 0) { $u = 0;
                                     while($info = $AssessmentsExist->fetch_assoc()){ $u++;
                              ?>
                              <tr style="font-weight: 400;">
                                <td class="p-0 text-center"><?php echo $u; ?></td>
                                <td><?php echo ucwords($info['title']); ?></td>
                                <td>
                                    <a href="upload-sub-risk?id=<?php echo strtolower($info['risk_id']); ?>" target='_blank' class="btn btn-outline-primary"><i class='fa fa-plus'></i> Add</a>
                                    <a href="update-old?id=<?php echo strtolower($info['risk_id']); ?>" class="btn btn-primary"><i class='fa fa-pen'></i> Update</a>
                                    <a href="update-control?id=<?php echo strtolower($info['risk_id']); ?>" class="btn btn-outline-secondary"><i class='fa fa-pen'></i> Update Control</a>
                                </td>
                              </tr>
                              <?php }}else{ ?>
                              <tr class="empty_div"><td class="p-0 text-center">#</td><td>No Risks Registered Yet!!</td></tr>
                              <?php } ?>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php } else { ?>
                    <div class="row">
                      <div class="col-12">
                        <div class="card">
                          <div class="card-header">
                            <h4>Select Risk Industry:</h4>
                            <div class="card-header-form">
                              <a href="/" class="btn btn-primary">Back <i class="fas fa-arrow-right"></i></a>
                            </div>
                          </div>
                          <div class="card-body p-0">
                            <div class="table-responsive" style="padding:10px 20px !important;">
                              <table class="table table-striped">
                                <tr>
                                  <th class="p-0 text-center">S/N</th>
                                  <th style='width:80%;'>Industry Title</th>
                                  <th>Action</th>
                                </tr>
                              <?php 
                                  $CheckIfAssessmentsExist = "SELECT * FROM as_newrisk_industry";
                                    $AssessmentsExist = $con->query($CheckIfAssessmentsExist);
                                    if ($AssessmentsExist->num_rows > 0) { $u = 0;
                                     while($info = $AssessmentsExist->fetch_assoc()){ $u++;
                              ?>
                              <tr style="font-weight: 400;">
                                <td class="p-0 text-center"><?php echo $u; ?></td>
                                <td><?php echo ucwords($info['title']); ?></td>
                                <td><a href="risks?id=<?php echo strtolower($info['industry_id']); ?>" class="btn btn-outline-primary">Upload Sub Risk</a></td>
                              </tr>
                              <?php }}else{ ?>
                              <tr class="empty_div"><td class="p-0 text-center">#</td><td>No Industry Registered Yet!!</td></tr>
                              <?php } ?>
                              </table>
                            </div>
                          </div>
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
    <style>
        .card{
          padding: 10px;
        }
        .user-description{
            margin-bottom: 10px;
        }
        hr{
            border: 1px solid white !important;
            display: block !important;
        }
        .main-footer{
            margin-top: 0px !important;
        }
    </style>
</body>
</html>