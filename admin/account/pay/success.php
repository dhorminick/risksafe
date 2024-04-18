<?php
    session_start();
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        
    $file_dir = '../../../';
    require_once $file_dir.'layout/stripe__config.php';
    require_once $file_dir.'layout/db.php';
    
    if(isset($_GET['e']) && isset($_GET['e']) !== null && isset($_GET['t']) && isset($_GET['t']) !== null){
        $company = $_SESSION['company_id'];
        
        $e = __stripe_sanitizePlus($_GET['e']);
        $t = __stripe_sanitizePlus($_GET['t']);

        $update_txn = updateTXN($con, $e, $t, $company, $company);
        
        switch ($update_txn) {
                case 'true':
                    $response = 'Thank you for your payment!! Your transaction has been successfully completed.';
                    $update_txn_bool = true;
                    break;
                case 'error':
                    $response = 'Error';
                    $update_txn_bool = false;
                    break;
                case 'false':
                    $response = 'Thank you for your payment!! Your transaction has been successfully completed.';
                    $update_txn_bool = true;
                    break;
                default:
                    $response = 'Error!!';
                    $update_txn_bool = false;
                    break;
        }
        
        $e_link = '/admin/account/payments';
        
?>
<html>
    <head>
        <title>Pay | RiskSafe - Risk Assessment And Management</title>
        <?php include $file_dir.'layout/general_css'; ?>
        <link rel="stylesheet" type="text/css" href="<?php echo $file_dir; ?>assets/css/pay.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
         <div class='__main'>
             <?php if($update_txn_bool == true){ ?>
             <div class='__msg'>
                 <div class='__msg_icon'>
                     <i class='fa fa-check'></i>
                 </div>
                 <div class='__msg_title'>
                     <h1>Payment Successful!!</h1>
                 </div>
                 <div class='__msg_txt'>
                     <div style='margin-bottom:5px;'><?php echo $response; ?></div>
                     <div>Redirecting To Admin In <span id='count'>10</span> second(s)</div>
                 </div>
                 <div class='__msg_btn'>
                     <a href='/admin/'><button><i class='fa fa-arrow-left'></i> Back To Admin Dashboard</button></a>
                 </div>
             </div>
             <?php }else{ ?>
             <?php $e_link = '/contact-us?err=payment_validation'; ?>
             <div class='__msg'>
                 <div class='__msg_icon'>
                     <i class='fa fa-cancel'></i>
                 </div>
                 <div class='__msg_title'>
                     <h1>Payment Error!!</h1>
                 </div>
                 <div class='__msg_txt'>
                     <div style='margin-bottom:5px;'>Error Validating Payment!! Our admins have been notified, but you can contact our support admin @ <a style='color:var(--primary);margin-bottom:2px;border-bottom:1px solid var(--primary);' href='mailto:support@newcomersunion.com'>support@newcomersunion.com</a> to give us more information about the error. </div>
                     <div>Redirecting To Our Contact Page In <span id='count'>10</span> second(s)</div>
                 </div>
                 <div class='__msg_btn'>
                     <a href='/admin/'><button><i class='fa fa-arrow-left'></i> Back To Admin Dashboard</button></a>
                 </div>
             </div>
             <?php } ?>
         </div>
    </body> 
</html>
<script>
    function countdown() {
    var i = document.getElementById('count');
    if (parseInt(i.innerHTML)<=0) {
        location.href = '<?php echo $e_link; ?>';
    }
    if (parseInt(i.innerHTML)!=0) {
        i.innerHTML = parseInt(i.innerHTML)-1;
    }
}
setInterval(function(){ countdown(); },1000);
</script>
<?php }else{ ?>
<?php $file_dir = '../../'; ?>
<html style='background-color:black;color:white;'>
    <head><?php include $file_dir.'layout/general_css'; ?></head>
    <body style='background-color:black;color:white;font-family: "font-2";font-size:13px;'>{"message":"Access Denied!!" , "Error":"Missing Parameter!!"}</body>
</html>
<?php } ?>
<?php }else{ ?>
<?php $file_dir = '../../'; ?>
<html style='background-color:black;color:white;'>
    <head><?php include $file_dir.'layout/general_css'; ?></head>
    <body style='background-color:black;color:white;font-family: "font-2";font-size:13px;'>{"message":"Access Denied!!" , "Error":"Session Error, Login To Continue!!"}</body>
</html>
<?php } ?>