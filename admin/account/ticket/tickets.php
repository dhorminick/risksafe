<?php
    session_start();
    $file_dir = '../../';

    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'auth/sign-in?r=/admin/tickets');
        exit();
    }
  
    $message = [];
    include $file_dir.'layout/db.php';
    include $file_dir.'layout/admin__config.php';
    include '../ajax/ticket.php';

    $u_name = ucwords($_SESSION['u_name']);

    if (isset($_GET['id']) && isset($_GET['id']) !== "") {
        $toDisplay = true;
        $id = sanitizePlus($_GET['id']);
        
        if (isset($_POST['update-ticket'])) {
            $reply = trim($_POST['message']);
            $reply = str_replace( 'font-family' , 'error-style', $reply );
            $reply = str_replace( 'font-size' , 'error-style', $reply );
            $reply = str_replace( 'background-color' , 'error-style', $reply );
            $reply = str_replace( 'text-align' , 'error-style', $reply );
            $reply = str_replace( 'h1' , 'b', $reply );
            $reply = str_replace( 'h2' , 'b', $reply );
            $reply = str_replace( 'h3' , 'b', $reply );
            $reply = str_replace( 'h4' , 'b', $reply );
            $reply = str_replace( 'h5' , 'b', $reply );
            $reply = str_replace( 'h6' , 'b', $reply );
            $reply = str_replace( '<p>' , '%p%', $reply );
            $reply = str_replace( '\\' , '%\\%', $reply );
            $reply = str_replace( '</p>', '%/p%', $reply );
            $reply = str_replace( '<b>' , '%b%', $reply );
            $reply = str_replace( '</b>', '%/b%', $reply );
            $reply = str_replace( '<u>' , '%u%', $reply );
            $reply = str_replace( '</u>', '%/u%', $reply );
            $reply = str_replace( '\'' , '"', $reply );

            $reply = sanitizePlus($reply);
            $t_id = sanitizePlus($_POST['id']);
            $ticket_modified_on = date("Y-m-d H:i:s");

            $t__message = array(array(
                'name' => $u_name,
                'role' => 'client',
                'message' => $reply,
                'date' => date("Y-m-d H:i:s")
            ));

            $confirmTicket = getTicketDetails($id, $con);
            if ($confirmTicket == 'error') {
                array_push($message, 'Error 402: Ticket ID Error!!');
            }else{
                if (strtolower($t_id) == strtolower($id)) {
                    $confirmTicket = unserialize($confirmTicket['message']);
                    
                    $t__message = array_merge($confirmTicket, $t__message);
                    $t__message = serialize($t__message);

                    $query = "UPDATE tickets SET message = '$t__message', t_datetime_modified = '$ticket_modified_on' WHERE ticket_id = '$t_id' AND c_id = '$company_id'";
                    if ($con->query($query)) {
                        array_push($message, 'Ticket Updated Successfully!!');
                    } else {
                        array_push($message, 'Error 502: Error Updating Ticket!!');
                    }

                } else {
                    array_push($message, 'Error 402: Ticket ID Mismatch!!');
                }
                
            }
        }

        if (isset($_POST['reopen-ticket'])) {
            $reply = trim($_POST['message']);
            $reply = str_replace( 'font-family' , 'error-style', $reply );
            $reply = str_replace( 'font-size' , 'error-style', $reply );
            $reply = str_replace( 'background-color' , 'error-style', $reply );
            $reply = str_replace( 'text-align' , 'error-style', $reply );
            $reply = str_replace( 'h1' , 'b', $reply );
            $reply = str_replace( 'h2' , 'b', $reply );
            $reply = str_replace( 'h3' , 'b', $reply );
            $reply = str_replace( 'h4' , 'b', $reply );
            $reply = str_replace( 'h5' , 'b', $reply );
            $reply = str_replace( 'h6' , 'b', $reply );
            $reply = str_replace( '<p>' , '%p%', $reply );
            $reply = str_replace( '\\' , '%\\%', $reply );
            $reply = str_replace( '</p>', '%/p%', $reply );
            $reply = str_replace( '<b>' , '%b%', $reply );
            $reply = str_replace( '</b>', '%/b%', $reply );
            $reply = str_replace( '<u>' , '%u%', $reply );
            $reply = str_replace( '</u>', '%/u%', $reply );
            $reply = str_replace( '\'' , '"', $reply );

            $reply = sanitizePlus($reply);
            $t_id = sanitizePlus($_POST['id']);
            $ticket_modified_on = date("Y-m-d H:i:s");

            $t__message = array(array(
                'name' => $u_name,
                'role' => 'admin',
                'message' => $reply,
                'date' => date("Y-m-d H:i:s")
            ));

            $confirmTicket = getTicketDetails($id, $con);
            if ($confirmTicket == 'error') {
                array_push($message, 'Error 402: Ticket ID Error!!');
            }else{
                if (strtolower($t_id) == strtolower($id)) {
                    $confirmTicket = unserialize($confirmTicket['message']);
                    
                    $t__message = array_merge($confirmTicket, $t__message);
                    $t__message = serialize($t__message);

                    $query = "UPDATE tickets SET message = '$t__message', t_datetime_modified = '$ticket_modified_on', status = 'open' WHERE ticket_id = '$t_id' AND c_id = '$company_id'";
                    if ($con->query($query)) {
                        array_push($message, 'Ticket Updated Successfully!!');
                    } else {
                        array_push($message, 'Error 502: Error Updating Ticket!!');
                    }

                } else {
                    array_push($message, 'Error 402: Ticket ID Mismatch!!');
                }
                
            }
        }

        if (isset($_POST['close-ticket'])) {
            $t_id = sanitizePlus($_POST['close-id']);
            $ticket_modified_on = date("Y-m-d H:i:s");

            $confirmTicket = getTicketDetails($id, $con);
            if ($confirmTicket == 'error') {
                array_push($message, 'Error 402: Ticket ID Error!!');
            }else{
                if (strtolower($t_id) == strtolower($id)) {

                    $query = "UPDATE tickets SET status = 'closed', t_datetime_modified = '$ticket_modified_on' WHERE ticket_id = '$t_id' AND c_id = '$company_id'";
                    if ($con->query($query)) {
                        array_push($message, 'Ticket Closed Successfully!!');
                    } else {
                        array_push($message, 'Error 502: Error Updating Ticket!!');
                    }

                } else {
                    array_push($message, 'Error 402: Ticket ID Mismatch!!');
                }
                
            }
        }

        $info = getTicketDetails($id, $con);
        if ($info == 'error') {
            $exists = false;
        } else {
            $exists = true;
            $t_messages_unser = $info['message'];

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
  <title>Tickets | <?php echo $siteEndTitle; ?></title>
  <?php require $file_dir.'layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/admin.custom.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/bundles/summernote/summernote-bs4.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/bundles/prism/prism.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/ticket.custom.css">
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
                    <?php if ($toDisplay == true) { ?>
                    <?php if ($exists == true) { ?>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="d-inline">Ticket Details</h3>
                            <button class="btn btn-danger btn-icon icon-left header-a" id='close-ticket' data-id = '<?php echo strtoupper($id); ?>' data-toggle="modal" data-target=".bd-example-modal-sm">Close Ticket</button>
                        </div>
                        <div class="card-body">
                            <?php if ($t_messages_unser == '' || $t_messages_unser == 'a:0:{}') { ?>
                            <div class="error">Error Fetchin Data</div>
                            <?php } else { ?>
                            <div class="row section-rows customs">
                                <div class="user-description col-12 col-lg-4">
                                    <label>Ticket Status :</label>
                                    <div class="description-text"><?php echo ucwords($info['status']); ?></div>
                                </div>
                                <div class="user-description col-12 col-lg-4">
                                    <label>Ticket Created On :</label>
                                    <div class="description-text"><?php echo timeAgo($info['t_datetime_created'], date("Y-m-d H:i:s")); ?></div>
                                </div>
                                <div class="user-description col-12 col-lg-4">
                                    <label>Last Modified On :</label>
                                    <div class="description-text"><?php echo timeAgo($info['t_datetime_modified'], date("Y-m-d H:i:s")); ?></div>
                                </div>
                            </div>
                            <div class="o">
                            <?php 
                                $info = getTicketDetails($id, $con);
                                $msges = unserialize($info['message']);
                                foreach ($msges as $key => $ticket) {
                                    $ticket['message'] = str_replace( '%p%' , '<p>', $ticket['message'] );
                                    $ticket['message'] = str_replace( '%/p%', '</p>', $ticket['message']);
                                    $ticket['message'] = str_replace( '%b%' , '<b>', $ticket['message'] );
                                    $ticket['message'] = str_replace( '%/b%', '</b>', $ticket['message'] );
                                    $ticket['message'] = str_replace( '%u%' , '<u>', $ticket['message'] );
                                    $ticket['message'] = str_replace( '%/u%', '</u>', $ticket['message'] );
                                    $ticket['message'] = str_replace( '%\\%', '\\', $ticket['message'] );

                            ?>
                            <div class="m <?php if($ticket['role'] == 'client'){echo 'l';}else if($ticket['role'] == 'admin'){echo 'r';}else{echo 'Error!';} ?>">
                                <div class="tickets">
                                    <div class="ticket-content">
                                        <div class="ticket-header">
                                            <div class="ticket-sender-picture img-shadow"><img src="<?php echo $file_dir; ?>assets/img/user.png" alt="image" width="30" height="30"></div>
                                            <div class="ticket-detail">
                                                <div class="ticket-title">
                                                    <h6><?php echo ucwords($ticket['name']); ?></h6>
                                                </div>
                                                <div class="ticket-info">
                                                    <span><?php if($ticket['role'] == 'client'){echo 'CLIENT';}else if($ticket['role'] == 'admin'){echo 'ADMIN';}else{echo 'Error!';} ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ticket-description"><?php echo $ticket['message']; ?></div>
                                        <div class="ticket-datetime tr"> <?php echo timeAgo($ticket['date'], date("Y-m-d H:i:s")); ?> </div>
                                    </div>
                                </div>
                            </div>
                            <?php }#}} ?>
                            </div>
                            <hr>
                            <div class="response">
                                <form method="post" id='r_form'>
                                    <div class="form-group">
                                        <label for="message">Ticket Reply :</label>
                                        <textarea class="summernote-simple form-control" name="message" required></textarea>
                                    </div>
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                    <div class="form-group text-right">
                                        <?php if($info['status'] !== 'closed'){ ?>
                                        <button type="submit" name="update-ticket" class="btn btn-primary btn-icon icon-left btn-lg" style="width: 100%;"><i class="fas fa-paper-plane"></i> Reply Ticket</button>
                                        <?php }else{ ?>
                                        <button type="submit" id="s_btn" name="reopen-ticket"></button>
                                        <button type="button" data-toggle="modal" data-target=".bd-example-modal-sm-2" class="btn btn-primary btn-icon icon-left btn-lg" style="width: 100%;"><i class="fas fa-paper-plane"></i> Reply Ticket</button>
                                        <?php }?>   
                                    </div>
                                </form>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } else { ?>
                    <div class="card">
                        <div class="card-header">
                            <span class="d-inline"></span>
                            <a href="tickets" class="header-a btn btn-primary btn-icon icon-right"><i class="fas fa-arrow-left"></i> Back</a>
                            
                          </div>
                        <div class="card-body">
                            <div style="width:100%;min-height:400px;display:flex;justify-content:center;align-items:center;">
                                <div style="text-align: center;"> 
                                    <h3>Empty Data!!</h3>
                                    Ticket Parameters Does Not Exist!!
                                    <p class="mt-2"><a href="/help#data-error" class="bb">Help <i class="fas fa-question"></i></a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <style>.main-footer{ margin-top: -13px !important; }</style>
                    <?php } ?>
                    <?php } else { ?>
                    <div class="row">
                        <div class="col-12">
                            <!-- Support tickets -->
                            <div class="card">
                              <div class="card-header">
                                <h4>Support Ticket</h4>
                                <div class="card-header-form">
                                  <?php if(countOpen($con, 'tickets') <= 4){$of = countOpen($con, 'tickets');}else{$of = '4';} echo '1 - '.$of.' of '.countOpen($con, 'tickets'); ?>
                                </div>
                              </div>
                              <div class="card-body">
                                <?php 
                                    $tickets = listTickets('null', $con); 
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
                                    $items['n_message'] = str_replace( '<p>' , ' ', $items['n_message'] );
                                    $items['n_message'] = str_replace( '</p>', ' ', $items['n_message']);
                                    $items['n_message'] = str_replace( '<b>' , ' ', $items['n_message'] );
                                    $items['n_message'] = str_replace( '</b>', ' ', $items['n_message'] );
                                    $items['n_message'] = str_replace( '<u>' , ' ', $items['n_message'] );
                                    $items['n_message'] = str_replace( '</u>', ' ', $items['n_message'] );
                                ?>
                                <div class="support-ticket media pb-1 mb-3">
                                  <img src="<?php echo $file_dir; ?>assets/img/user.png" class="user-img mr-2" alt="">
                                  <a href="tickets?id=<?php echo strtolower($items['c_id']); ?>" style="text-decoration: none !important;">
                                  <div class="media-body ml-3">
                                    <div class="<?php echo $class; ?>"><?php echo ucwords($items['priority']); ?></div>
                                    <span class="font-weight-bold">#<?php echo strtoupper($items['c_id']); ?></span>
                                    <p class="my-1"><?php echo substr($items['n_message'], 0, 120); ?>...</p>
                                    <small class="text-muted">
                                        Created by
                                        <span class="font-weight-bold font-13"><?php echo ucwords($items['n_sender']); ?></span> 
                                        - 
                                        <span class="font-weight-bold font-13"><?php echo timeAgo($items['n_datetime'], date("Y-m-d H:i:s")); ;?></span>
                                    </small>
                                  </div>
                                  </a>
                                </div>
                                <?php }}else{ ?>
                                <div class="empty_div">No Opened Tickets!! <p><a href='new-ticket' class='btn btn-primary btn-icon icon-left'><i class='fas fa-plus'></i> Open New Ticket</a></p></div>
                                <?php } ?>
                              </div>
                              <div class="card-footer card-link text-center tm">
                                <?php if(countOpen($con, 'tickets') >= 1){ ?><a href="javascript:void(0)" class="btn btn-primary btn-lg" style="width:100%;">View All</a><?php } ?>
                              </div>
                            </div>
                            <!-- Support tickets -->
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </section>
        </div>
        <?php require $file_dir.'layout/footer.php' ?>
        </footer>
        </div>
        <!-- Small Modal -->
        <div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
          aria-hidden="true">
          <div class="modal-dialog modal-sm">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="mySmallModalLabel">Confirm Action</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div> Are you sure you want to close the ticket with ID: </div>
                <p style="margin-top: 10px;" id="view-id"></p>
                <form method="post">
                    <input type="hidden" name="close-id" id="close-id">
                    <button class="btn btn-primary btn-lg" name="close-ticket" style="width: 100%;">Close Ticket</button>                
                </form>
              </div>
            </div>
          </div>
        </div>

        <!-- Small Modal -->
        <div class="modal fade bd-example-modal-sm-2" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
          aria-hidden="true">
          <div class="modal-dialog modal-sm">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="mySmallModalLabel">Confirm Action</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div>Updating Ticket Details Will Set The Ticket Status To 'OPEN'. Are You Sure You Want To Re-Open The Ticket?</div>
                <button class="btn btn-primary btn-lg" id='re-open' style="width: 100%;margin-top: 10px;">Re-Open Ticket</button>
              </div>
            </div>
          </div>
        </div>
    </div>
    <?php require $file_dir.'layout/general_js.php' ?>
    <script src="<?php echo $file_dir; ?>assets/bundles/prism/prism.js"></script>
    <script src="<?php echo $file_dir; ?>assets/bundles/summernote/summernote-bs4.js"></script>
    <script>
        $('.note-editable.card-block').html('');
        
        $("#close-ticket").click(function(e) { 
            var data_id = $(this).attr('data-id');
            if (data_id == '' || !data_id || data_id == null) {
                alert('Error!');
            } else {
                $("#view-id").html(data_id);
                $("#close-id").val(data_id);
            }
        });

        $("#re-open").click(function(e) { 
            $("#s_btn").click();
        });
        
    </script>
</body>
</html>