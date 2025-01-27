<?php
	include '../layout/db.php';

    function secure_random_string($length) { 
        $random_string = ''; 
        for($i = 0; $i < $length; $i++) { 
            $number = random_int(0, 36);  
            $character = base_convert($number, 10, 36);
            $random_string .= $character; 
        } 
                
        return $random_string;
    }

    function getToArray($array){
		$getArray = [];

		$convertArray = explode("&", $array);
		for ($i=0; $i < count($convertArray); $i++) { 
			$keyValue = explode('=', $convertArray[$i]);
			$getArray[$keyValue [0]] = $keyValue [1];
		}

		return $getArray;
	}

    function sanitize__Plus($data) {
			$data = trim($data);
			$data = stripslashes($data);
			$data = strip_tags($data);
			$data = htmlspecialchars($data);
			return $data;
		}

?>