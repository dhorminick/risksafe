<?php
    session_start();
    $file_dir = '../';

    if (isset($_SESSION["AdminloggedIn"]) == true || isset($_SESSION["AdminloggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: login');
        exit();
    }
  
    $message = [];
    include $file_dir.'layout/db.php';
    include 'ajax/admin.php';
    include $file_dir.'layout/superadmin_config.php';
    $a_info = adminData($con, $_SESSION["admin_id"]);
    if($a_info == 'error'){
        $a_info['name'] = 'Error!';
    }else{}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>RiskSafe Admin | <?php echo $siteEndTitle; ?></title>
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
                    <div class="row">
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <div class="card" style="height: 85% !important;">
                            <div class="card-statistic-4">
                              <div class="align-items-center justify-content-between">
                                <div class="row ">
                                    <div class="col-12">
                                        <h4>Welcome,</h4>
                                        <h5><?php echo ucwords($a_info['name']); ?></h5>
                                    </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <div class="card">
                            <div class="card-statistic-4">
                              <div class="align-items-center justify-content-between">
                                <div class="row">
                                  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                    <div class="card-content">
                                      <h5 class="font-15">Total Clients</h5>
                                      <h2 class="mb-3 font-18"><?php echo countData($con, 'users'); ?></h2>
                                    </div>
                                  </div>
                                  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                    <div class="banner-img">
                                      <img src="<?php echo $file_dir; ?>assets/img/banner/1.png" alt="">
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <div class="card">
                            <div class="card-statistic-4">
                              <div class="align-items-center justify-content-between">
                                <div class="row ">
                                  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                    <div class="card-content">
                                      <h5 class="font-15"> Open Tickets</h5>
                                      <h2 class="mb-3 font-18"><?php echo countOpen($con, 'tickets'); ?></h2>
                                    </div>
                                  </div>
                                  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                    <div class="banner-img">
                                      <img src="<?php echo $file_dir; ?>assets/img/banner/2.png" alt="">
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <div class="card">
                            <div class="card-statistic-4">
                              <div class="align-items-center justify-content-between">
                                <div class="row ">
                                  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                    <div class="card-content">
                                      <h5 class="font-15">Inquires</h5>
                                      <h2 class="mb-3 font-18"><?php echo countData($con, 'as_assessment'); ?></h2>
                                    </div>
                                  </div>
                                  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                    <div class="banner-img">
                                      <img src="<?php echo $file_dir; ?>assets/img/banner/3.png" alt="">
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12" style="display:none;">
                          <div class="card">
                            <div class="card-statistic-4">
                              <div class="align-items-center justify-content-between">
                                <div class="row ">
                                  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                    <div class="card-content">
                                      <h5 class="font-15">Revenue</h5>
                                      <h2 class="mb-3 font-18">$48,697</h2>
                                    </div>
                                  </div>
                                  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                    <div class="banner-img">
                                      <img src="<?php echo $file_dir; ?>assets/img/banner/4.png" alt="">
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                    </div>

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
                                  $list_users = listUsers(6, $con); 
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

                    <div class="row custom-row">
                        <div class="col-md-12 col-lg-12 col-xl-8">
                            <!-- Support tickets -->
                            <div class="card mh-a">
                              <div class="card-header">
                                <h4>Support Ticket</h4>
                                <div class="card-header-form">
                                  <?php if(countOpen($con, 'tickets') <= 4){$of = countOpen($con, 'tickets');}else{$of = '4';} echo '1 - '.$of.' of '.countOpen($con, 'tickets'); ?>
                                </div>
                              </div>
                              <div class="card-body">
                                <?php 
                                    $tickets = listTickets(4, $con); 
                                    if ($tickets !== false){
                                    $t_i = -1;
                                    foreach ($tickets as $items) { $t_i++;
                                    $priority = strtolower($items['priority']);
                                    switch ($priority) {
                                        case 'low':
                                            $class = 'badge badge-pill badge-success mb-1 float-right';
                                            break;
                                        
                                        case 'medium':
                                            $class = 'badge badge-pill badge-warning mb-1 float-right';
                                            break;
                                        
                                        case 'high':
                                            $class = 'badge badge-pill badge-danger mb-1 float-right';
                                            break;
                                        
                                        default:
                                            $class = 'badge badge-pill badge-primary mb-1 float-right';
                                            break;
                                    }

                                    #sanitize inputs
                                    $items['message'] = str_replace( '<p>' , ' ', $items['message'] );
                                    $items['message'] = str_replace( '</p>', ' ', $items['message']);
                                    $items['message'] = str_replace( '<b>' , ' ', $items['message'] );
                                    $items['message'] = str_replace( '</b>', ' ', $items['message'] );
                                    $items['message'] = str_replace( '<u>' , ' ', $items['message'] );
                                    $items['message'] = str_replace( '</u>', ' ', $items['message'] );
                                ?>
                                <div class="support-ticket media pb-1 mb-3">
                                  <img src="<?php echo $file_dir; ?>assets/img/user.png" class="user-img mr-2" alt="">
                                  <a href="tickets?id=<?php echo strtolower($items['ticket_id']); ?>" style="text-decoration: none !important;width:100% !important;">
                                  <div class="media-body ml-3">
                                    <div class="<?php echo $class; ?>"><?php echo ucwords($items['priority']); ?></div>
                                    <span class="font-weight-bold">#<?php echo strtoupper($items['ticket_id']); ?></span>
                                    <p class="my-1"><?php echo substr($items['subject'], 0, 120); ?>...</p>
                                    <small class="text-muted">
                                        Created by
                                        <span class="font-weight-bold font-13"><?php echo ucwords($items['id']); ?></span> 
                                        - 
                                        <span class="font-weight-bold font-13"><?php echo timeAgo($items['t_datetime_modified'], date("Y-m-d H:i:s")); ;?></span>
                                    </small>
                                  </div>
                                  </a>
                                </div>
                                <?php }}else{ ?>
                                <div class="empty_div">No Opened Tickets!!</div>
                                <?php } ?>
                              </div>
                              <div class="card-footer card-link text-center tm">
                                <?php if(countData($con, 'users') >= 1){ ?><a href="tickets" class="btn btn-primary btn-lg" style="width:100%;">View All</a><?php } ?>
                              </div>
                            </div>
                            <!-- Support tickets -->
                        </div>
                        <div class="col-md-12 col-lg-12 col-xl-4">
                            <div class="card mh-a">
                                <div class="card-header">
                                    <h4>Recent Notifications - (<?php echo countData($con, 'notification_admin'); ?>)</h4>
                                </div>
                                <div class="card-body" id="top-5-scroll">
                                    <ul class="list-unstyled list-unstyled-border user-list" id="message-list">
                                        <?php 
                                            #$query = "SELECT * FROM notification_admin WHERE status = 'unread' ORDER BY n_datetime DESC";	 
                                            $query = "SELECT * FROM notification_admin WHERE status = 'unread' ORDER BY datetime DESC LIMIT 4";	 
                                            $result=$con->query($query);
                                            if ($result->num_rows > 0) { while ($row=$result->fetch_assoc()) {
                                        ?>
                                        <li class="media">
                                            <a ><i class="fas fa-bell notif_bell mr-3"></i></a>
                                            <div class="media-body">
                                              <div class="mt-0 font-weight-bold text-small c_id"><?php echo strtoupper($row['company_id']); ?></div>
                                              <div style="font-weight:400;"><?php echo $row['message'] ?></div>
                                              <span class="font-weight-bold font-13 text-right"><?php echo timeAgo($row['datetime'], date("Y-m-d H:i:s")); ;?></span>
                                            </div>
                                        </li>
                                        <?php }}else{ ?>
                                        <div class="empty_div">No New Notification!</div>
                                        <?php } ?>
                                    </ul>
                                </div>
                                <div class="card-footer card-link text-center tm">
                                  <?php if(countData($con, 'notification_admin') >= 1){ ?><a href="javascript:void(0)" class="btn btn-outline-primary btn-lg" style="width:100%;">View All</a><?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <?php require $file_dir.'layout/footer.php' ?>
        </footer>
        </div>
    </div>
    <?php require $file_dir.'layout/general_js.php' ?>
    <style>
        textarea{
            min-height: 120px !important;
        }
        .card{
          padding: 10px;
        }
        .notif_bell{
          font-size: 20px !important;
          margin-left: 0px !important;
          color: var(--custom-primary);
        }
        .c_id{
          color: var(--custom-primary);
        }
        .mh-a{
          min-height: 550px !important;
          max-height: 550px !important;
        }
        .card-footer.card-link.text-center.tm{
          padding: 0px 25px 20px 25px !important;
          margin-top: -50px !important;
        }
    </style>
    <script>
      $(".sub-control").hide();
      $("#control-type").change(function(e) { 
        var riskValue = $("#control-type").val();
        if (riskValue == '0') {
            $(".sub-control").hide();
        } else {
            $("#get_subcontrol").val();
            $("#get_subcontrol").val(riskValue);
            $("#getSubControl").submit();
        }
      });
      $("#getSubControl").submit(function (event) {
        // alert('first first stop!');
        event.preventDefault();
  
        var formValues = $(this).serialize();
  
        $.post("../ajax/audits.php", {
            getSubControl: formValues,
        }).done(function (data) {
            // alert(data);
            $("#sub-control").html(data);
            $(".sub-control").show();
            setTimeout(function () {
                $("#getSubControl input").val('');
            }, 1000);
            
            // alert('second stop!');
        });
      });
      
    </script>
</body>
</html>