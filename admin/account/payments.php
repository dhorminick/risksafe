<?php
    session_start();
    $file_dir = '../../';
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'login?r=/account/payments');
        exit();
    }
    $message = [];
    $user__expired = false;
    $payment_countdown = false;
    include '../../layout/db.php';
    include '../../layout/admin_config.php';
    
    $CheckIfUserExist = "SELECT * FROM users WHERE company_id = '$company_id'";
    $UserExist = $con->query($CheckIfUserExist);
    if ($UserExist->num_rows > 0) {
        $datainfo = $UserExist->fetch_assoc();
        $paymentDuration = $datainfo['payment_duration'];
        $___paymentStatus = $datainfo['payment_status'];
        switch ($paymentDuration) {
            case 'trial':
                $plan = 'Free Trial';
                break;
            case 'annual':
                $plan = 'Annual Payment';
                break;
            case 'biannual':
                $plan = 'Bi-Annual Payment';
                break;
            case 'monthly':
                $plan = 'Monthly Payment';
                break;
            default:
                $plan = 'Error';
                break;
        }
        
        $last_payment = date_create_from_format("Y-m-d H:i:s", $datainfo['u_datetime']);
        $__last_payment = date('Y-m-d H:i:s', strtotime($datainfo['u_datetime']));
        $__reg_date = date("dS F, Y", strtotime($datainfo['u_datetime']));
        
        $next_payment = date_create_from_format("Y-m-d H:i:s", $datainfo['u_expire']);
        $__next_payment = date('Y-m-d H:i:s', strtotime($datainfo['u_expire']));
        $__expire_date = date("dS F, Y", strtotime($datainfo['u_expire']));
        
        $today = date("Y-m-d H:i:s");
        
        if($today >= $__next_payment){
            $user__expired = true;
        }else{
            $user__expired = false;
        }
        
        $__expiration = daysAgo($today, $__next_payment);
        
        if ($__expiration['left'] == 'days' &&  $__expiration['timeCalc'] <= '10' ) {
                $payment_countdown = true;
                if ($__expiration['timeCalc'] <= '0' ) {
                    $user__expired = true;
                    if($___paymentStatus !== 'expired'){
                        $UpdateUserStatus = "UPDATE users SET payment_status = 'expired' WHERE company_id = '$company_id'";
                        $UserStatus = $con->query($UpdateUserStatus);
                    }
                    header('Location: /account/make-payment.php?expired=true');
                    exit();
                }
        }
        
        
        $reg_date = date_create_from_format("Y-m-d H:i:s", $u_datetime);
        $reg_date = date_format($reg_date,"dS F, Y");

        $expire_date = date_create_from_format("Y-m-d H:i:s",$u_expire);
        $expire_date = date_format($expire_date,"dS F, Y");
        
        
        #$expiration = daysAgo($u_datetime, $u_expire);

        $expiration = daysAgo($today, $u_expire);
        
        $__paymentStatus = $_SESSION["userPaymentStatus"];
    }else{
        echo 'Error 01!';
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Payments | <?php echo $siteEndTitle; ?></title>
  <?php require '../../layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/admin.custom.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/bundles/prism/prism.css">
</head>

<body>
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <?php require '../../layout/header.php' ?>
        <?php require '../../layout/sidebar_admin.php' ?>
        <!-- Main Content -->
        <div class="main-content">
            <section class="section">
            <div class="section-body">
                <div class="card">
                    <?php include '../../layout/alert.php'; ?>
                    <div class="card-body">
                        <div class="card-header">
                            <h3 class="d-inline">Payment Information</h3>
                            <div class="header-a hide-sm"><button class="btn btn-primary btn-icon icon-left" data-toggle="modal" data-target="#paymentPlan"><i class="fas fa-plus-circle"></i> Create Payment</button></div>
                        </div>
                        <div class="card-body">
                            <?php if($user__expired === true){ ?>
                                <div class="payment-alert">
                                    You RiskSafe Payment Plan Has Expired.
                                </div>
                            <?php }else{ ?>
                                <?php if($payment_countdown === true){ ?>
                                    <div class="payment-alert note">
                                        <?php echo 'NOTICE: You Have '.ucwords($__expiration['timeCalc']).' '.ucwords($__expiration['left']).' Left On Your RiskSafe Payment Plan.'; ?>
                                    </div>
                                <?php }else{} ?>
                            <?php } ?>
                            <table class="payment-desc">
                                <tr>
                                    <th>Payment Plan: </th>
                                    <td><?php echo $plan; ?></td>
                                </tr>
                                <tr>
                                    <th>Payment Status: </th>
                                    <td><?php echo ucwords($__paymentStatus); ?></td>
                                </tr>
                                <tr>
                                    <th><?php if($__paymentStatus == 'free'){ ?> Registration Date: <?php }else{ ?>Last Payment: <?php } ?></th>
                                    <td><?php echo $__reg_date; ?></td>
                                </tr>
                                <tr>
                                    <th>Expires: </th>
                                    <td>
                                        <?php echo $__expire_date; ?> <span class="show-sm"></span> (<span style="font-size: 14px;"><?php echo ucwords($__expiration['timeCalc']).' '.ucwords($__expiration['left']); ?> Left</span>)
                                    </td>
                                </tr>
                            </table>
                            <div class="pay-td show-sm"><button class="btn btn-primary btn-icon icon-left" data-toggle="modal" data-target="#paymentPlan"><i class="fas fa-plus-circle"></i> Make Payment</button></div>
                        </div>
                        <div class="card-body"></div>
                        <div class="card-header">
                            <h3 class="card-header-h">Previous Payments Details</h3>
                        </div>
                        <div class="card-body">
                            <?php 
                                $GetPrevPayment = "SELECT * FROM payments WHERE company = '$company_id' LIMIT 5";
                                $PrevPayment = $con->query($GetPrevPayment);
                                if ($PrevPayment->num_rows > 0) {
                                    $p_row = $PrevPayment->fetch_assoc();
                                    $details = $p_row['details'];
                                    $details = unserialize($details);
                            ?>
                            <table class="payment-data">
                                <tr>
                                    <th>#</th>
                                    <th>Email</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                </tr>
                            <?php while($p_row = $PrevPayment->fetch_assoc()){ ?>
                                <tr>
                                    <td>#</td>
                                </tr>
                                <tr>
                                    <td><?php echo $details['email']; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo $details['amount']; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo $details['date']; ?></td>
                                </tr>
                            <?php } ?>
                            </table>
                            <?php }else{ ?>
                            <div class="empty-table">No Data To Show!!</div>
                            <?php } ?>
                            
                        </div>      
                    </div>
                </div>
            </div>
            </section>
        </div>
        <!-- basic modal -->
        <div class="modal fade" id="paymentPlan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
          aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Select Plan:</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="row">
                    <?php 
                        $GetPayment = "SELECT * FROM payment_config";
                        $p_config = $con->query($GetPayment);
                        if ($p_config->num_rows > 0) {
                           while($p___config = $p_config->fetch_assoc()){
                    ?>
                    <div class="col-lg-4 col-12">
                        <div class='price'>$<?php echo $p___config['price']; if($p___config['off'] !== '0'){echo ' ( -'.$p___config['off'].'% )';} ?></div>
                        <button class="btn btn-primary btn-plan" data-id="<?php echo $p___config['pay_id']; ?>">Monthly</button>
                    </div>
                    <?php }}else{ ?>
                    <div class="col-lg-4 col-12">
                        ERROR 502: Error Validating Payment!!
                    </div>
                    <?php } ?>
                    
                    <form method='post' action='pay/checkout' style='display:none;'>
                        <input type='hidden' value='' name='e_id' id='e_id' />
                        <button name='pay' id='__pay__' type='submit'>pay</button>
                    </form>
                    
                    <!--<div class="col-lg-4 col-12">-->
                    <!--    <div class='price'>$550 ( -6.5% )</div>-->
                    <!--    <a class="btn btn-primary btn-plan" href="make-payment?plan=annual">Annually</a>-->
                    <!--</div>-->
                    <!--<div class="col-lg-4 col-12">-->
                    <!--    <div class='price'>$1100 ( -6.5% )</div>-->
                    <!--    <a class="btn btn-primary btn-plan" href="make-payment?plan=bi-annual">Bi-Annual</a>-->
                    <!--</div>-->
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php require '../../layout/footer.php' ?>
        </footer>
        </div>
    </div>
    <?php require '../../layout/general_js.php' ?>
    <script src="<?php echo $file_dir; ?>assets/bundles/prism/prism.js"></script>
</body>
</html>
<style>
    .main-footer {
        margin-top: -15px;
    }
    .note {
    border-left: 7px solid var(--custom-primary);
    background-color: var(--card-border);
    color: black;
    padding: 10px;
    margin: 0px 0px 20px 0px;
    border-radius: 0px 5px 5px 0px;
}
.price{
    text-align:center;
    margin-bottom:10px;
    font-weight:400;
}
</style>
<script>
    $(".btn-plan").click(function(e) { 
        $("#e_id").val('');
            var id = $(this).attr('data-id');
            if (id == '' || !id || id == null) {
                alert('Error 402!!');
                window.location.reload(true);
            } else {
                $("#e_id").val(id);
                $("#__pay__").click();
            }
    });
</script>