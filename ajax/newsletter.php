<?php

    include 'main.php';
    
    #get requests
	if (isset($_POST["newsletter"])) {
		
		$value = $_POST["newsletter"];
        $getArray = getToArray($value);
        if(isset($getArray['error']) || $getArray['error'] !== ''){
            $response = 'Error!!';
        }else{
            $email = sanitize__Plus($getArray['useremail']);
            $email = str_replace("%40","@",$email);
    
            $date = date("Y-m-d");
    
            $query = "INSERT INTO newcomers_newsletter (email, reg_date) VALUES ('$email', '$date')";
            $sql = mysqli_query($con, $query);
            if($sql){
                $response = 'Email Address Added Successfully!!';
            }else{
                $response = 'Error Adding Email Address!!';
            }
        }
				
		echo $response;
    }

?>