<?php
#vars
$sitee = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]/";
include 'variablesandfunctions.php';


#get device
$usr_agent = $_SERVER["HTTP_USER_AGENT"];
if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$usr_agent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($usr_agent,0,4))){
    $on_mobile = true;
}else{
    $on_mobile = false;
}

require $file_dir.'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

$userId = $_SESSION["userId"];
$userMail = $_SESSION["userMail"];
$paymentStatus = $_SESSION["userPaymentStatus"];
$u_datetime = $_SESSION["u_datetime"];
$u_expire = $_SESSION["u_expire"];
$company_name = $_SESSION["company_name"];
$role = $_SESSION["role"];
$company_id = $_SESSION["company_id"];

$user_payment_plan = 'basic';
#incase more payment plans gets created
switch ($user_payment_plan) {
    case 'basic':
        $usermaxusers = 20;
        $userplanName = 'Basic';
        break;
    
    default:
        $usermaxusers = 20;
        $userplanName = 'Basic';
        break;
}

$today = date("Y-m-d H:i:s");
$accnt_dir = null;

include 'admin__func.php';


#validate acc
$CheckIfUserExist = "SELECT * FROM users WHERE company_id = '$company_id'";
$UserExist = $con->query($CheckIfUserExist);
if ($UserExist->num_rows > 0) {
    $_data = $UserExist->fetch_assoc();
    $___paymentStatus = $_data['payment_status'];
    $__next_payment = date('Y-m-d H:i:s', strtotime($_data['u_expire']));
    
    $__expiration = daysAgo($today, $__next_payment);
        
        if ($__expiration['left'] == 'days' &&  $__expiration['timeCalc'] <= '10' ) {
                $payment_countdown = true;
                if ($__expiration['timeCalc'] <= '0' ) {
                    $user__expired = true;
                    if($___paymentStatus !== 'expired'){
                        $UpdateUserStatus = "UPDATE users SET payment_status = 'expired' WHERE company_id = '$company_id'";
                        $UserStatus = $con->query($UpdateUserStatus);
                    }
                    header('Location: /account/make-payment?expired=true');
                    exit();
                }
        }
}else{
    echo 'Error!!';
    exit();
}


#unread
$queryUnread = "SELECT COUNT(*) FROM notification WHERE c_id = '$company_id' AND status = 'unread'";       
$resultUnread = $con->query($queryUnread);

if ($resultUnread) {
    $rowUnread = $resultUnread->fetch_assoc();
    $unreadCount = $rowUnread['COUNT(*)'];
} else {
    $unreadCount = 0;
}

$queryUnread_t = "SELECT COUNT(*) FROM tickets WHERE c_id = '$company_id' AND status != 'closed'";       
$resultUnread_t = $con->query($queryUnread_t);

if ($resultUnread_t) {
    $rowUnread_t = $resultUnread_t->fetch_assoc();
    $unreadCount_t = $rowUnread_t['COUNT(*)'];
} else {
    $unreadCount_t = 0;
}



?>
