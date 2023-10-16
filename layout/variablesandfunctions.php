<?php 
$adminEmail = 'etiketochukwu@gmail.com';
$reportEmail = 'etiketochukwu@gmail.com';
$paymentMail = 'payments@sellit.com';
$signUpSender = 'noreply@sellit.com';
$incorrectDetailsMail = 'error@sellit.com';
$signUpSender = 'jay@risksafe.co';

function secure_random_string($length) { 
    $random_string = ''; 
    for($i = 0; $i < $length; $i++) { 
        $number = random_int(0, 36);  
        $character = base_convert($number, 10, 36);
        $random_string .= $character; 
    } 
            
    return $random_string;
}


function weirdlyEncode($data){
  $data = md5(crc32(md5(crc32(md5(crc32(md5($data)))))));
  return $data;
}

function sanitizePlus($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = strip_tags($data);
  $data = htmlspecialchars($data);
  return $data;
}

function dataExists($data, $returnError, $returnArray, $errorCountArray){
  if ($data != null || $data != '' || !$data) {
    return array_push($returnArray, $returnError);
      // $errorCountArray = true;
  }
}

// if (isset($_SESSION["email"]) != '' || isset($_SESSION["email"]) != null) {
//   $userEmail = $_SESSION['email'];
// } else {
//   $userEmail = 'etiketochukwu@gmail.com';
// }

// if (isset($_SESSION["phone"]) != '' || isset($_SESSION["phone"]) != null) {
//   $userPhone = $_SESSION['phone'];
// } else {
//   $userPhone = '09076876557';
// }

// if (isset($_SESSION["id"]) != '' || isset($_SESSION["id"]) != null) {
//   $userId = $_SESSION['id'];
// } else {
//   $userId = 'user103908274902';
// }


function errorExists($data, $errorCounter){
  if ($data == null || $data == '' || !$data) {
    $errorCounter = true;
    return $errorCounter;
  }else if ($data != null || $data != '') {
    $errorCounter = false;
    return $errorCounter;
  }
}

#date("Y-m-d H:i:s")
function timeAgo ($oldTime, $newTime) {
	$timeCalc = strtotime($newTime) - strtotime($oldTime);
	if ($timeCalc >= (60*60*24*30*12*2)){
		$timeCalc = intval($timeCalc/60/60/24/30/12) . " years ago";
	}else if ($timeCalc >= (60*60*24*30*12)){
		$timeCalc = intval($timeCalc/60/60/24/30/12) . " year ago";
	}else if ($timeCalc >= (60*60*24*30*2)){
		$timeCalc = intval($timeCalc/60/60/24/30) . " months ago";
	}else if ($timeCalc >= (60*60*24*30)){
		$timeCalc = intval($timeCalc/60/60/24/30) . " month ago";
	}else if ($timeCalc >= (60*60*24*2)){
		$timeCalc = intval($timeCalc/60/60/24) . " days ago";
	}else if ($timeCalc >= (60*60*24)){
		$timeCalc = " Yesterday";
	}else if ($timeCalc >= (60*60*2)){
		$timeCalc = intval($timeCalc/60/60) . " hours ago";
	}else if ($timeCalc >= (60*60)){
		$timeCalc = intval($timeCalc/60/60) . " hour ago";
	}else if ($timeCalc >= 60*2){
		$timeCalc = intval($timeCalc/60) . " minutes ago";
	}else if ($timeCalc >= 60){
		$timeCalc = intval($timeCalc/60) . " minute ago";
	}else if ($timeCalc > 0){
		$timeCalc .= " seconds ago";
	}
	return $timeCalc;
}
?>