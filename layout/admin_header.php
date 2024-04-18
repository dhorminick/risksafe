<?php
    $linkedin = 'https://www.linkedin.com/company/risksafe-co/';
    $facebook = 'https://www.facebook.com/RiskSafeHQ';
    $twitter = '';
    
    // $alert_expire_date = date_create_from_format("Y-m-d H:i:s",$u_expire);
    // $alert_expire_date = date_format($alert_expire_date,"dS F, Y");
        
    // $alert_today = date("Y-m-d H:i:s");
    // $alert_todaysDate = date_format($alert_today, "dS F, Y");
    
    // $alert_expiration = daysAgo($alert_today, $u_expire);
    
    // if ($alert_expiration['left'] == 'days' &&  $alert_expiration['timeCalc'] <= '10' ) {
    //     $alertExpire = true;
    // }
?>
<div class="search_cover"></div>
    <nav class="navbar navbar-expand-lg main-navbar sticky">
        <div class="form-inline mr-auto">
          <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg
									collapse-btn"> <i data-feather="align-justify"></i></a></li>
            
            <li>
              <div class="form-inline mr-auto">
                <div class="search-element">
                  <input class="form-control" type="search" placeholder="Search" aria-label="Search" data-width="400" id='fnd939sn'>
                </div>
                <button class="btn" type="button">
                    <i class="fas fa-search"></i>
                </button>
              </div>
              <div class="search-main"></div>
              <form id='search'> <input type='hidden' name='q' id='q'> </form>
            </li>
          </ul>
        </div>
        <ul class="navbar-nav navbar-right">
          <li class="dropdown dropdown-list-toggle"><a href="tickets"
              class="nav-link message-toggle nav-link-lg"><i data-feather="mail" class="bell"></i>
              <span class="badge headerBadge1"><?php echo count_Data($con, 'tickets', 'status', 'open'); ?></span>
            </a>
          </li>
          
          <li class="dropdown dropdown-list-toggle"><a href="#" data-toggle="dropdown"
              class="nav-link message-toggle nav-link-lg"><i data-feather="bell" class="bell"></i>
              <span class="badge headerBadge1"><?php echo count_Data($con, 'notification_admin', 'status', 'unread'); ?></span>
            </a>
            <?php
              $CheckIfUserExist = "SELECT * FROM notification_admin WHERE status = 'unread'";
              $UserExist = $con->query($CheckIfUserExist);
              if ($UserExist->num_rows > 0) {
                  $hasNotification = true;
                  #$datainfo = $UserExist->fetch_assoc();
                  // echo count($datainfo);
              }else{
                $hasNotification = false;
              }
            ?>
            <div class="dropdown-menu dropdown-list dropdown-menu-right pulDown">
              <div class="dropdown-header">
                Notifications
                <div class="float-right">
                  <?php if($hasNotification == true){ ?><a href="#">Mark All As Read</a> <?php } ?>
                </div>
              </div>
              <div class="dropdown-list-content notif dropdown-list-icons" style="overflow-y:auto;">
                <?php if($hasNotification == true){ ?>
                <?php 
                    $CheckIfNotifExist = "SELECT * FROM notification_admin WHERE status = 'unread' ORDER BY datetime DESC";
                    $NotifExist = $con->query($CheckIfNotifExist);
                    while($datainfo = mysqli_fetch_assoc($NotifExist)){
                    if ($NotifExist->num_rows > 0) {
                ?>
                <a href="javascript:void(0);" class="dropdown-item dropdown-item-unread">
                  <span class="dropdown-item-icon bg-primary text-white"> 
                    <i class="fas fa-bell"></i>
                  </span> 
                  <span class="dropdown-item-desc"> 
                    <span class="d_mess"><?php echo $datainfo['message']; ?> </span>
                    <span class="time"><?php echo timeAgo($datainfo['datetime'], $today); ?></span>
                  </span>
                </a> 
                <?php }} ?>
                <?php }else{ ?>
                <style>
                  .dropdown-list .dropdown-list-content.notif {
                      height: 100px;
                  }
                  .no-notif{
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    margin: 10px;
                  }
                </style>
                <div class="no-notif">No New Notifications!</div>
                <?php } ?>
                
              </div>
              <?php if($hasNotification == true){ ?>
              <div class="dropdown-footer text-center">
                <a href="javascript:void(0)">View All <i class="fas fa-chevron-right"></i></a>
              </div>
              <?php } ?>
            </div>
          </li>
        </ul>
    </nav>
    