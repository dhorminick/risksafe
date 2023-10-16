<?php
session_start();

if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
    $signedIn = true;
} else {
    header('../../login.php?r=');
}

$userId = $_SESSION["userId"];
$userMail = $_SESSION["userMail"];
$paymentStatus = $_SESSION["userPaymentStatus"];
$u_datetime = $_SESSION["u_datetime"];
$u_expire = $_SESSION["u_expire"];

$today = date("Y-m-d H:i:s");
$timeSpent = daysAgo($u_expire, $today);

$role = $_SESSION["role"];
$company_id = $_SESSION["company_id"];

$include_dir = '../rs/';

if($paymentStatus == 'expired'){
    header('Location: /account/make-payment.php');
}else if($paymentStatus == 'trial'){
        if ($timeSpent['left'] == 'days' &&  $timeSpent['timeCalc'] <= '10' ) {
            array_push($message, 'You Have '.ucwords($timeSpent['timeCalc']).' Left');
            if ($timeSpent['left'] == 'days' &&  $timeSpent['timeCalc'] == '0' ) {
                // echo 'You Have '.ucwords($timeSpent['timeCalc']).' Left';
                header('Location: /account/make-payment.php?expired=true');
            }else{}
        } else {
            array_push($message, 'You Have '.ucwords($timeSpent['timeCalc']).' Left');
        }
}else{
    if ($payment_duration == 'annual') {
        $dateToExpire = date("Y-m-d H:i:s", strtotime("+365 days"));
        if ($u_expire >= $dateToExpire) {
            $UpdateUserStatus = "UPDATE users WHERE payment_status = 'expired' WHERE iduser = '$userId' AND u_mail = '$userMail'";
            $UserStatus = $con->query($UpdateUserStatus);
            if ($UserStatus) {

            }else{

            }
        } else {}
    } else if ($payment_duration == 'monthly') {
        $dateToExpire = date("Y-m-d H:i:s", strtotime("+30 days"));
        if ($u_expire >= $dateToExpire) {
            $UpdateUserStatus = "UPDATE users WHERE payment_status = 'expired' WHERE iduser = '$userId' AND u_mail = '$userMail'";
            $UserStatus = $con->query($UpdateUserStatus);
            if ($UserStatus) {
                
            }else{

            }
        } else {}
    } else if ($payment_duration == 'bi-annual') {
        $dateToExpire = date("Y-m-d H:i:s", strtotime("+730 days"));
        if ($u_expire >= $dateToExpire) {
            $UpdateUserStatus = "UPDATE users WHERE payment_status = 'expired' WHERE iduser = '$userId' AND u_mail = '$userMail'";
            $UserStatus = $con->query($UpdateUserStatus);
            if ($UserStatus) {
                
            }else{

            }
        } else {}
    } else {
        header('/404');
    }
}

require '../../vendor/autoload.php';

function daysAgo ($oldTime, $newTime) {
	$timeCalc = strtotime($newTime) - strtotime($oldTime);
	if ($timeCalc >= (60*60*24*2)){
        $left = 'days';
		$timeCalc = intval($timeCalc/60/60/24);
	}else if ($timeCalc >= (60*60*2)){
        $left = 'hours';
		$timeCalc = intval($timeCalc/60/60);
	}else if ($timeCalc >= (60*60)){
        $left = 'hour';
		$timeCalc = intval($timeCalc/60/60);
	}else if ($timeCalc >= 60*2){
        $left = 'minutes';
		$timeCalc = intval($timeCalc/60);
	}else if ($timeCalc >= 60){
        $left = 'minute';
		$timeCalc = intval($timeCalc/60);
	}else if ($timeCalc > 0){
        $left = 'seconds';
		$timeCalc .= " seconds";
	}
    $timeStamp = array(
        'timeCalc' => $timeCalc,
        'left' => $left
    );

	return $timeStamp;
}
?>