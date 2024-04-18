<?php
    session_start();
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        
    $file_dir = '../../../';
    require_once $file_dir.'layout/stripe__config.php';
    require_once $file_dir.'layout/db.php';
    
    if(isset($_GET['e']) && isset($_GET['e']) !== null && isset($_GET['t']) && isset($_GET['t']) !== null){
        $usr = $_SESSION['userMail'];
        $e = __stripe_sanitizePlus($_GET['e']);
        $t = __stripe_sanitizePlus($_GET['t']);
        $calcel_txn = cancelTxn($con, $e, $usr, $t);
        
        switch ($calcel_txn) {
                case 'true':
                    $response = 'Payment Cancelled Successfully!!';
                    break;
                case 'not_exist':
                    $response = 'Error 402: Transaction Does Not Exist!!';
                    break;
                case 'false':
                    $response = 'Payment Cancelled Successfully!!';
                    break;
                default:
                    $response = 'Error!!';
                    break;
            }
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
             <div class='__msg'>
                 <div class='__msg_icon'>
                     <i class='fa fa-check'></i>
                 </div>
                 <div class='__msg_title'>
                     <h1>Payment Aborted!!</h1>
                 </div>
                 <div class='__msg_txt'>
                     <div style='margin-bottom:5px;'><?php echo $response; ?></div>
                     <div>Redirecting Back To Admin Panel In <span id='count'>10</span> second(s)</div>
                 </div>
                 <div class='__msg_btn'>
                     <a href='/admin/'><button><i class='fa fa-arrow-left'></i> Back To Admin Dashboard</button></a>
                 </div>
             </div>
         </div>
    </body> 
</html>
<script>
    function countdown() {
    var i = document.getElementById('count');
    if (parseInt(i.innerHTML)<=0) {
        location.href = '/admin/account/payments';
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