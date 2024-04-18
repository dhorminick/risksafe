<?php 
    #must include below admin config and db.php
    $GetUserDetails = "SELECT * FROM users WHERE company_id = '$company_id'";
    $User = $con->query($GetUserDetails);
    if ($User->num_rows > 0) {	
        $row = $User->fetch_assoc();
        
        if ($role == 'admin') {
            $details = $row['user_details'];
            $details = unserialize($details);
            $name = ucwords($details['fullname']);
        } else if($role == 'user'){
            $company_users = $row['company_users'];

            $company_users = unserialize($company_users);

            $isInArray = in_array_custom($userMail, $company_users) ? 'found' : 'notfound';
            if($isInArray === 'found'){
                for ($rowArray = 0; $rowArray < 3; $rowArray++) {
                    // echo 'row - '.$row.', email = '.$arrayAll[$row]['email'].', password = '.$arrayAll[$row]['password'];
                    if($arrayAll[$rowArray]['email'] == $email && $arrayAll[$rowArray]['password'] == $password){
                        $found = true;
                        $rowNumber = $rowArray;
                    }
                }
                if($found && $found == true){
                    $name = $company_users[$rowNumber]['fullname'];
                }else{
                    echo 'Error 03!';
                    exit();
                }          
            }else{
                echo 'Error 02!';
                exit();
            }
        }else{
            echo 'Error 01!';
            exit();
        }
    } else {
        echo 'Error!!';
        exit();
    }

?>