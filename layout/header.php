<?php
    $linkedin = 'https://www.linkedin.com/company/risksafe-co/';
    $facebook = 'https://www.facebook.com/RiskSafeHQ';
    $twitter = '';
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
          <li class="dropdown dropdown-list-toggle"><a href="#" data-toggle="dropdown"
              class="nav-link message-toggle nav-link-lg"><i data-feather="mail" class="bell"></i>
              <span class="badge headerBadge1"><?php echo count_Data_M($con, 'tickets', 'status', 'open', 'c_id', $company_id); ?></span>
            </a>
            <?php
              $CheckIfUserExist = "SELECT * FROM tickets WHERE c_id = '$company_id'";
              $UserExist = $con->query($CheckIfUserExist);
              if ($UserExist->num_rows > 0) {
                  $hasTicket = true;
              }else{
                $hasTicket = false;
              }
            ?>
            <div class="dropdown-menu dropdown-list dropdown-menu-right pulDown">
              <div class="dropdown-header">
                Open Tickets
                <div class="float-right">
                  <?php if($hasTicket == true){ ?><a href="#">Mark All As Read</a> <?php } ?>
                </div>
              </div>
              <div class="dropdown-list-content notif t dropdown-list-icons" style="overflow-y:auto;">
                <?php if($hasTicket == true){ ?>
                <?php 
                    $CheckIfNotifExist = "SELECT * FROM tickets WHERE c_id = '$company_id' AND status != 'closed' ORDER BY t_datetime_modified DESC";
                    $NotifExist = $con->query($CheckIfNotifExist);
                    if ($NotifExist->num_rows > 0) {
                    while($datainfo = $NotifExist->fetch_assoc()){
                ?>
                <a href="/admin/account/tickets?id=<?php echo $datainfo['link']; ?>" class="dropdown-item dropdown-item-unread">
                  <span class="dropdown-item-icon bg-primary text-white"> 
                    <i class="fas fa-code"></i>
                  </span> 
                  <span class="dropdown-item-desc"> 
                    <span class="d_mess">Update On Ticket - <?php echo $datainfo['ticket_subject']; ?> </span>
                    <span class="time"><?php echo timeAgo($datainfo['ticket_modified_on'], $today); ?></span>
                  </span>
                </a> 
                <?php }} ?>
                <?php }else{ ?>
                <style>
                  .dropdown-list .dropdown-list-content.notif.t {
                      height: 100px;
                  }
                  .no-notif.t{
                    display: flex;
                    flex-direction:column;
                    justify-content: center;
                    align-items: center;
                    margin: 10px;
                  }
                  .no-notif.t div{
                    margin-top: 10px;
                  }
                </style>
                <div class="no-notif t">No Opened Tickets!<div><a href='/admin/account/new-ticket' class='btn btn-primary btn-icon icon-left' style='width:100%;'><i class='fas fa-plus'></i> Open New Ticket</a></div></div>
                <?php } ?>
                
              </div>
              <?php if($hasTicket == true){ ?>
              <div class="dropdown-footer text-center">
                <a href="/admin/account/tickets">View All <i class="fas fa-chevron-right"></i></a>
              </div>
              <?php } ?>
            </div>
          </li>
          
          <li class="dropdown dropdown-list-toggle"><a href="#" data-toggle="dropdown"
              class="nav-link message-toggle nav-link-lg"><i data-feather="bell" class="bell"></i>
              <span class="badge headerBadge1"><?php echo count_Notif($con, 'notification', 'status', 'unread', 'c_id', $company_id); ?></span>
            </a>
            <?php
              $CheckIfUserExist = "SELECT * FROM notification WHERE c_id = '$company_id'";
              $UserExist = $con->query($CheckIfUserExist);
              if ($UserExist->num_rows > 0) {
                  $hasNotification = true;
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
                    $CheckIfNotifExist = "SELECT * FROM notification WHERE c_id = '$company_id' AND status = 'unread' ORDER BY n_datetime DESC";
                    $NotifExist = $con->query($CheckIfNotifExist);
                    while($datainfo = mysqli_fetch_assoc($NotifExist)){
                    if ($NotifExist->num_rows > 0) {
                ?>
                <a href="/<?php echo $datainfo['link']; ?>" class="dropdown-item dropdown-item-unread">
                  <span class="dropdown-item-icon bg-primary text-white"> 
                    <i class="fas fa-bell"></i>
                  </span> 
                  <span class="dropdown-item-desc"> 
                    <span class="d_mess"><?php echo $datainfo['n_message']; ?> </span>
                    <span class="time"><?php echo timeAgo($datainfo['n_datetime'], $today); ?></span>
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
                <a href="/admin/account/notifications">View All <i class="fas fa-chevron-right"></i></a>
              </div>
              <?php } ?>
            </div>
          </li>
        </ul>
    </nav>
    