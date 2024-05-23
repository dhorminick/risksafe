<?php
    session_start();
    $file_dir = '../../';
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'auth/sign-in?r=/account/notifications');
        exit();
    }
    $message = [];
    include $file_dir.'layout/db.php';
    include $file_dir.'layout/admin__config.php';
    
    function get_date($date){
        return date("l jS \of F, Y", strtotime($date));
    }
    
    if(isset($_GET['cleared'])){
        $GetPrevPayment = "DELETE FROM notification WHERE c_id = '$company_id'";
        $PrevPayment = $con->query($GetPrevPayment);
        if ($PrevPayment) {
            header('Location: notifications');
        }else{
            array_push($message, 'Error 502: Error Clearing Notifications!!');
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>(<?php echo $unreadCount; ?>) Unread Notification | <?php echo $siteEndTitle; ?></title>
  <?php require $file_dir.'layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/footer.custom.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/admin.custom.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/bundles/izitoast/css/iziToast.min.css">
</head>

<body>
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <?php require $file_dir.'layout/header.php' ?>
        <?php require $file_dir.'layout/sidebar_admin.php' ?>
        <!-- Main Content -->
        <div class="main-content">
            <section class="section">
            <div class="section-body">
                <div class="card" style="padding: 10px;">
                    <?php include $file_dir.'layout/alert.php'; ?>
                    <div class="card-header">
                        <h4 class='d-inline'>RiskSafe Notifications - (<?php echo $unreadCount; ?>)</h4>
                        <a class="btn btn-primary btn-icon icon-left header-a" href="?cleared"><i class="fas fa-check"></i> Clear All Notifications</a>
                    </div>
                    <?php
                        $CheckIfUserExist = "SELECT * FROM notification WHERE c_id = '$company_id'";
                        $UserExist = $con->query($CheckIfUserExist);
                        if ($UserExist->num_rows > 0) {
                            $hasNotification = true;
                        }else{
                            $hasNotification = false;
                        }
                        
                        if($hasNotification == true){ 
                    ?>
                    <div class="card-body">
                        <ul class="list-unstyled list-unstyled-border user-list" id="message-list">
                        <?php 
                            $CheckIfNotifExist = "SELECT * FROM notification WHERE c_id = '$company_id' ORDER BY n_datetime DESC";
                            $NotifExist = $con->query($CheckIfNotifExist);
                            while($datainfo = mysqli_fetch_assoc($NotifExist)){
                            
                            #var_dump($datainfo);
                            if($datainfo['role'] == 'admin'){
                                $n_notifier = '<strong>Admin</strong>';
                            }else{
                                $n_notifier = getUserDetailsWithId($company_id, $datainfo['n_sender'], $con);
                                $n_notifier = $n_notifier['name'];    
                            }
                            
                            // switch ($datainfo['n_case_custom']) {
                            //     case 'new-risk':
                            //         #$desc = 'A new risk assessment was created by '.$n_notifier.' on '.$datainfo['n_datetime'];
                            //         $desc = 'A new risk assessment was created by '.$n_notifier;
                            //         break;
                                
                            //     case 'new-audit':
                            //         #$desc = 'A new risk assessment was created by '.$n_notifier.' on '.$datainfo['n_datetime'];
                            //         $desc = 'A new audit of controls was created by '.$n_notifier;
                            //         break;
                                
                            //     case 'new-control':
                            //         #$desc = 'A new risk assessment was created by '.$n_notifier.' on '.$datainfo['n_datetime'];
                            //         $desc = 'A new custom control was created by '.$n_notifier;
                            //         break;
                                    
                            //     case 'new-treatment':
                            //         #$desc = 'A new risk assessment was created by '.$n_notifier.' on '.$datainfo['n_datetime'];
                            //         $desc = 'A new custom treatment was created by '.$n_notifier;
                            //         break;
                                
                            //     case 'new-compliance':
                            //         $desc = 'A new Compliance standard was created by '.$n_notifier;
                            //         break;
                    
                            //     case 'new-incident':
                            //         $desc = 'A new incident was created by '.$n_notifier;
                            //         break;
                                
                            //     case 'new-insurance':
                            //         $desc = 'A new insurance was created by '.$n_notifier;
                            //         break;
                                
                            //     case 'new-treatment':
                            //         $desc = 'A new incident was created by '.$n_notifier;
                            //         break;
                                    
                            //     case 'edit-risk':
                            //         $desc = 'A risk assessment was modified by '.$n_notifier;
                            //         break;
                                    
                            //     case 'edit-control':
                            //         $desc = 'A custom control was modified by '.$n_notifier;
                            //         break;
                                    
                            //     case 'edit-treatment':
                            //         $desc = 'A custom treatment was modified by '.$n_notifier;
                            //         break;
                                
                            //     case 'edit-compliance':
                            //         $desc = 'A Compliance standard was modified by '.$n_notifier;
                            //         break;
                    
                            //     case 'edit-incident':
                            //         $desc = 'An incident was modified by '.$n_notifier;
                            //         break;
                                
                            //     case 'edit-audit':
                            //         $desc = 'An audit of controls was modified by '.$n_notifier;
                            //         break;
                                
                            //     case 'edit-treatment':
                            //         $desc = 'An incident was modified by '.$n_notifier;
                            //         break;
                                
                            //     case 'edit-insurance':
                            //         $desc = 'An insurance was modified by '.$n_notifier;
                            //         break;
                                
                            //     default:
                            //         $desc = 'Error';
                            //         break;
                            // }
                            
                            $notificationAgo = timeAgo($datainfo['n_datetime'], $today);
                            
                            if ($NotifExist->num_rows > 0) {
                                
                        ?>
                          <li class="media">
                            <i class="mr-3 btn btn-primary btn-icon fas fa-bell" style='border-radius:50%;'></i>
                            <div class="media-body">
                              <div><span class="mt-0" style="font-weight:bold;font-size:15px !important;"><?php echo ucwords($datainfo['n_message']); ?></span> <div class="bullet"></div> <span class="text-small font-weight-bold"><?php echo $notificationAgo;?></span></div>
                              <div class="desc-notif" style="font-weight:400;font-size:14px !important;text-transform: lowercase;"><?php echo get_date($datainfo['n_datetime']); ?> by <?php echo $n_notifier; ?></div>
                            </div>
                            <div>
                                <a class="btn btn-primary btn-icon icon-left" href="/<?php echo $datainfo['link']; ?>">View <i class='fas fa-arrow-right'></i></a>
                                <button class="btn btn-icon btn-danger delete-notification" onclick='del(<?php echo crc32($datainfo['id']); ?>)'><i class="fas fa-trash-alt"></i></button>
                            </div>
                          </li><hr>
                          <?php }} ?>
                        </ul>
                    </div>
                    
                    <?php }else{ ?>
                    <div class="card-body" style="display: flex;justify-content:center;align-items:center;margin:30px;">Your Notifications List Is Empty!</div>
                    <?php } ?>
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
    <form id='del_notif'><input name='id' id='notif_id'></form>
    <?php require $file_dir.'layout/general_js.php' ?>
    <script src="<?php echo $file_dir; ?>assets/bundles/izitoast/js/iziToast.min.js"></script>
</body>
</html>
<style>
    .manual-header{
        font-size: 17px !important;
        margin-top: 20px !important;
        color: var(--custom-primary);
    }
    .card-header-h{
        color: var(--custom-primary);
    }
    .custom-p p{
        margin-bottom: 5px !important;
    }
    .desc-notif{
        margin: 5px 0px 10px 0px;
    }
</style>
<script>
  
        
        function del(value){
            var id = value;

            if (id && id !== null && id !== '') {
                $("#notif_id").val('');
                $("#notif_id").val(id);
                $("#del_notif").submit();
            } else {
                alert('Error 402!!');
                window.location.refresh;
                
            }
        }
        
        $("#del_notif").submit(function (event) {
            event.preventDefault();
            var formValues = $(this).serialize();

            $.post("../ajax/users", {
                deleteNotif: formValues,
            }).done(function (data) {
              //$(".res").html(data);
              $(".section-body").load(" .section-body > *");
              //$(".delete-notification").load(" .delete-notification > *");
            });
        });
</script>