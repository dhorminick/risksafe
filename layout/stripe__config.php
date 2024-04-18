<?php
    $stripe_secret_key = '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';
    $site___ = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]";
    
    
    function __stripe_sanitizePlus($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = strip_tags($data);
      $data = htmlspecialchars($data);
      return $data;
    }
    
    #functions
    function getPaymentInfo($con, $id){
        $CheckIfEventExists = "SELECT * FROM payment_config WHERE pay_id = '$id' LIMIT 1";
        $EventExists = $con->query($CheckIfEventExists);
        if ($EventExists->num_rows > 0) {
            $row = $EventExists->fetch_assoc();
        }else{
            $row = 'error';   
        }
        
        return $row;
    }
    
    function createTxn($con, $e_id, $customer_email, $e_price, $t_id, $c){
        $date = date("Y-m-d");
        $createTransaction = "INSERT INTO payment_transactions 
                (`pay_id`, `txn_id`, `user`, `status`, `amount`, `create_date`, `c_id`) 
        VALUES ('$e_id', '$t_id', '$customer_email', 'pending', '$e_price', '$date', '$c')";
        $txnCreated = $con->query($createTransaction);
        if ($txnCreated) {
            $return  = 'true';            
        }else{
           $return  = 'false';
        }
        
        return $return;
    }
    
    function cancelTxn($con, $e_id, $usr, $t_id){
        $CheckIfEventExists = "SELECT * FROM payment_transactions WHERE txn_id = '$t_id' LIMIT 1";
        $EventExists = $con->query($CheckIfEventExists);
        if ($EventExists->num_rows > 0) {
            $delTxn = "DELETE from payment_transactions WHERE txn_id = '$t_id' AND user = '$usr' AND pay_id = '$e_id'";
            $txnDeleted = $con->query($delTxn);
            if ($txnDeleted) {
                $return  = 'true';            
            }else{
               $return  = 'false';
            }
        }else{
            $return = 'not_exist';   
        }
        
        
        return $return;
    }
    
    function secure_random_string_s($length) { 
        $random_string = ''; 
        for($i = 0; $i < $length; $i++) { 
            $number = random_int(0, 36);  
            $character = base_convert($number, 10, 36);
            $random_string .= $character; 
        } 
                
        return $random_string;
    }
    
    function in_array_customs($needle, $haystack, $strict = true){
      foreach ($haystack as $items){
          if (($strict ? $items === $needle : $items == $needle) || (is_array($items) && in_array_customs($needle, $items, $strict))){
              return true;
          }
      }
      
      return false;
    }
    
    function getUser($con, $t_id){
        $CheckIfEventExists = "SELECT * FROM payment_transactions WHERE txn_id = '$t_id' LIMIT 1";
        $EventExists = $con->query($CheckIfEventExists);
        if ($EventExists->num_rows > 0) {
            $row = $EventExists->fetch_assoc();
            $email = $row['user'];
        }else{
            $email = 'error';   
        }
        
        return $email;
    }
    
    function getE($con, $e_id){
        $CheckIfEventExists = "SELECT * FROM payment_config WHERE pay_id = '$e_id' LIMIT 1";
        $EventExists = $con->query($CheckIfEventExists);
        if ($EventExists->num_rows > 0) {
            $row = $EventExists->fetch_assoc();
            $link = $row['title'];
        }else{
            $link = 'error';   
        }
        
        return $link;
    }
    
    function updateUser($con, $c, $old_date, $new_date){
         $updateTxn = "UPDATE users SET u_datetime = '$old_date', u_expire = '$new_date' WHERE company_id = '$c'";
            $txnUpdated = $con->query($updateTxn);
            if ($txnUpdated) {
                $return  = 'true';
            }else{
                $return = 'false';
            }
            
            return $return;
    }
    
    function alertAdmin($con, $mes, $com_db, $id, $u_id){
        $date = date("Y-m-d");
        $com_id = secure_random_string_s(10);
        $createTransaction = "INSERT INTO admin_alert 
                (`com_id`, `msg`, `com_user`, `com_msg_id`, `com_db`, `com_date`) 
        VALUES ('$com_id', '$mes', '$u_id', '$id', '$com_db', '$date')";
        $txnCreated = $con->query($createTransaction);
    }
    
    function updateTXN($con, $id, $t_id, $e_id, $u_id){
        $CheckIfEventExists = "SELECT * FROM payment_transactions WHERE txn_id = '$t_id' AND c_id = '$e_id' AND pay_id = '$id' LIMIT 1";
        $EventExists = $con->query($CheckIfEventExists);
        if ($EventExists->num_rows > 0) {
            $row = $EventExists->fetch_assoc();
            $updateTxn = "UPDATE payment_transactions SET status = 'success' WHERE txn_id = '$t_id' AND user = '$e_id'";
            $txnUpdated = $con->query($updateTxn);
            if ($txnUpdated) {
                $return  = 'true';
                
                #update user
                $pay_title = getE($con, $id);
                if($pay_title == 'error'){
                    $mes = 'Error Fetching Payment Values - ID: pfi-asnf-chn';
                    alertAdmin($con, $mes, 'payment_transactions', $row['id'], $u_id);
                }else{
                    switch ($pay_title) {
                        case 'Monthly':
                            $add_date = date("Y-m-d H:i:s", strtotime("+30 days"));
                            break;
                        case 'Bi-Annually':
                            $add_date = date("Y-m-d H:i:s", strtotime("+730 days"));
                            break;
                        case 'Annually':
                            $add_date = date("Y-m-d H:i:s", strtotime("+365 days"));
                            break;
                        case 'Test':
                            $add_date = date("Y-m-d H:i:s", strtotime("+10 days"));
                            break;
                        default:
                            $add_date = date("Y-m-d H:i:s", strtotime("+1 days"));
                            break;
                    }
                    
                    $__today = date("Y-m-d H:i:s");
                    $update__usr = updateUser($con, $e_id, $__today, $add_date);
                    if($update__usr == 'false'){
                       #alert admin
                       $mes = 'Failed To Update User';
                       alertAdmin($con, $mes, 'payment_transactions', $row['id'], $e_id);
                    }
                }
            }else{
               $return  = 'false';
               #alert admin
               $mes = 'Transaction Status Failed To Update';
               alertAdmin($con, $mes, 'payment_transactions', $row['id'], $u_id);
            }
        }else{
            $return = 'error';   
        }
        
        return $return;
    }
    
?>