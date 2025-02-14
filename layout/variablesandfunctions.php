<?php 
$adminEmail = 'jay@risksafe.co';
$reportEmail = 'jay@risksafe.co';
$paymentMail = 'payments@sellit.com';
$signUpSender = 'jay@risksafe.co';
$resetPassSender = 'jay@risksafe.co';
$resetPassHelp = 'support@risksafe.co';
$signUpHelp = 'jay@risksafe.co';
$incorrectDetailsMail = 'jay@risksafe.co';
$adminemailaddr = 'jay@risksafe.co';
$siteEndTitle = 'RiskSafe - Risk Assessment';
$website__ = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]/";
$site__ = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]/";
$siteMainLocation = 'Address, City, State, Country.';

$page_fb = 'https://facebook.com/risksafe';
    $page_ig = 'https://instagram.com/risksafe';
    $page_x = 'https://twitter.com/risksafe';
    $page_ln = 'https://linkedin.com/risksafe';
    $page_wt = 'https://whatsapp.com/';
    $page_yt = 'https://youtube.com/';
    
// $signUpSender = 'jay@risksafe.co';

function secure_random_string($length) { 
    $random_string = ''; 
    for($i = 0; $i < $length; $i++) { 
        $number = random_int(0, 36);  
        $character = base_convert($number, 10, 36);
        $random_string .= $character; 
    } 
            
    return $random_string;
}

function count_Data($con, $table, $var, $res){
        $query="SELECT * FROM $table WHERE $var = '$res'";
        $result=$con->query($query);
		if ($result->num_rows > 0) {
			return $result->num_rows;
		} else {
			return '0';	
		}
    }
    
    function count_Data_M($con, $table, $var, $res, $and, $id){
        $query="SELECT * FROM $table WHERE $var = '$res' AND $and = '$id'";
        $result=$con->query($query);
		if ($result->num_rows > 0) {
			return $result->num_rows;
		} else {
			return '0';	
		}
    }
    
    function count_Notif($con, $table, $var, $res, $s_var, $s_res){
        $query="SELECT * FROM $table WHERE $var = '$res' AND $s_var = '$s_res'";
        $result=$con->query($query);
		if ($result->num_rows > 0) {
			return $result->num_rows;
		} else {
			return '0';	
		}
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
	}else if ($timeCalc == 0){
		$timeCalc = "0 seconds ago";
	}
	return $timeCalc;
}

function in_array_custom($needle, $haystack, $strict = true){
  foreach ($haystack as $items){
      if (($strict ? $items === $needle : $items == $needle) || (is_array($items) && in_array_custom($needle, $items, $strict))){
          return true;
      }
  }
  
  return false;
}
?>