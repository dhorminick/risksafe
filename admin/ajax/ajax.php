<?php
    function sendNotificationUser($company_id, $notification_message, $datetime, $notifier, $link, $conn){
        $query_details = "INSERT INTO notification (n_message, n_datetime, n_sender, link, c_id, status) VALUES ('$notification_message', '$datetime', '$notifier', '$link', '$company_id', 'unread')";
        $query_completed = $conn->query($query_details);
        if ($query_completed) {
            $notified = true;
            #mail user and admin
            include '../../layout/mail_custom.php';
            $company_mail = $_SESSION['admin_mail'];
            $return = notificationCustom($company_mail, $adminemailaddr, 'compliance');
            // $return2 = 


            if ($return['sent'] == 'true') {
                $userMailed = true;
            } else {
                $userMailed = false;
            }
            
        }else{
            $notified = false;
            $notified = false;
        }

        $returnArr = array(
        'user_mailed' => $userMailed,
        // 'admin_mailed' => $adminMailed,
        'notified' => $notified
        );
        
        return $returnArr;
    }
?>