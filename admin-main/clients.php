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
        $id = sanitizePlus($_GET['id']);

        if(isset($_POST['v_user']) && isset($_POST['v_c_id']) !== ""){
            $v_id = sanitizePlus($_POST['v_c_id']);
            
            $query = "UPDATE users SET u_complete = 'true' WHERE company_id = '$v_id'";
            $u = $con->query($query);
        }
        
        $info = getCompanyDetails($id, $con);
        if ($info == 'error') {
            $exists = false;
        } else {
            $exists = true;
            $details = unserialize($info['company_details']);
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
                    <?php if ($exists == true) { ?>
                    <div class="card">
                        <div class="card-header" style='display:flex;justify-content:space-between;'>
                            <h3>Client Details</h3>
                            <div class="card-header-form" style='display:flex;'>
                                <?php if($info['u_complete'] !== 'true'){ ?>
                                <form action='' method='post' style='margin-right:10px;'>
                                    <input type='hidden' name='v_c_id' value='<?php echo $info['company_id']; ?>' />
                                    <button class="btn btn-secondary" name='v_user'>Validate User</button>
                                </form>
                                <?php } ?>
                                <a href="../" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Back</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row section-rows customs">
                                <div class="user-description col-12 col-lg-9">
                                    <label>Company Name :</label>
                                    <div class="description-text"><?php echo $details['company_name']; ?></div>
                                </div>
                                <div class="user-description col-12 col-lg-3">
                                    <label>Company ID :</label>
                                    <div class="description-text"><?php echo $info['company_id']; ?></div>
                                </div>
                                <div class="user-description col-12 col-lg-9">
                                    <label>Company Admin Email Address :</label>
                                    <div class="description-text">
                                        <a class="bb" href="mailto:<?php echo $info['u_mail']; ?>" target="_blank">
                                            <?php echo $info['u_mail']; ?>
                                        </a>
                                    </div>
                                </div>
                                <div class="user-description col-12 col-lg-3">
                                    <label>Company User Count :</label>
                                    <div class="description-text"><?php echo count(unserialize($info['company_users'])); ?> Users Created</div>
                                </div>
                                <div class="user-description col-12 col-lg-6">
                                    <label>Company Payment Plan :</label>
                                    <div class="description-text"><?php echo getStatus($info['payment_status']).' '.getStatusDuration($info['payment_duration']); ?></div>
                                </div>
                                <div class="user-description col-12 col-lg-3">
                                    <label>Company Last Payment :</label>
                                    <div class="description-text"><?php echo date_format(date_create_from_format("Y-m-d H:i:s", $info['u_datetime']), "dS F, Y"); ?></div>
                                </div>
                                <div class="user-description col-12 col-lg-3">
                                    <label>Company Expires On :</label>
                                    <div class="description-text"><?php echo date_format(date_create_from_format("Y-m-d H:i:s", $info['u_expire']), "dS F, Y"); ?></div>
                                </div>
                                <hr>
                                <div class="user-description col-12">
                                    <label>Company Address :</label>
                                    <div class="description-text"><?php if($details['company_address'] == '' || $details['company_address'] == null){echo 'Not Set!';}else{echo $details['company_address'];} ?></div>
                                </div>
                                <div class="user-description col-12 col-lg-6">
                                    <label>Company Country :</label>
                                    <div class="description-text"><?php if($details['company_country'] == '' || $details['company_country'] == null){echo 'Not Set!';}else{echo $details['company_country'];} ?></div>
                                </div>
                                <div class="user-description col-12 col-lg-3">
                                    <label>Company State :</label>
                                    <div class="description-text"><?php if($details['company_state'] == '' || $details['company_state'] == null){echo 'Not Set!';}else{echo $details['company_state'];} ?></div>
                                </div>
                                <div class="user-description col-12 col-lg-3">
                                    <label>Company City :</label>
                                    <div class="description-text"><?php if($details['company_city'] == '' || $details['company_city'] == null){echo 'Not Set!';}else{echo $details['company_city'];} ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } else { ?>
                    <?php } ?>
                    <?php } else { ?>
                    <div class="row">
                      <div class="col-12">
                        <div class="card">
                          <div class="card-header">
                            <h4>Recently Registered Clients:</h4>
                            <div class="card-header-form">
                              <a href="clients" class="btn btn-primary">View All Clients <i class="fas fa-arrow-right"></i></a>
                            </div>
                          </div>
                          <div class="card-body p-0">
                            <div class="table-responsive" style="padding:10px 20px !important;">
                              <table class="table table-striped">
                                <tr>
                                  <th class="p-0 text-center">#</th>
                                  <th>Company</th>
                                  <th>Created On</th>
                                  <th>Payment Plan</th>
                                  <th>Action</th>
                                </tr>
                              <?php 
                                  $list_users = listUsers('null', $con); 
                                  if ($list_users !== false){
                                  $t_i = -1;
                                  foreach ($list_users as $items) { $t_i++;
                                  $c_details = getCompanyDetails($items['company_id'], $con);
                                  $company = unserialize($c_details['company_details']);
                                  $pay_plan = strtolower($items['payment_status']);
                              ?>
                              <tr style="font-weight: 400;">
                                <td class="p-0 text-center">#</td>
                                <td><?php echo $company['company_name'] ?></td>
                                <td><?php echo date_format(date_create_from_format("Y-m-d H:i:s", $c_details['created_on']), "m-d-Y"); ?></td>
                                <td><div><?php echo getStatus($pay_plan).' - Expires: '.date_format(date_create_from_format("Y-m-d H:i:s", $c_details['u_expire']), "m-d-Y"); ?></div></td>
                                <td><a href="clients?id=<?php echo strtolower($items['company_id']); ?>" class="btn btn-outline-primary">View Client Details</a></td>
                              </tr>
                              <?php }}else{ ?>
                              <tr class="empty_div"><td class="p-0 text-center">#</td><td>No Registered Client!!</td></tr>
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