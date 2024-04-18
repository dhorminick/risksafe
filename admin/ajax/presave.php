<?php
    
    
    function getToArray($array){
		$getArray = [];

		$convertArray = explode("&", $array);
		for ($i=0; $i < count($convertArray); $i++) { 
			$keyValue = explode('=', $convertArray[$i]);
			$getArray[$keyValue [0]] = $keyValue [1];
		}

		return $getArray;
	}
    
    if (isset($_POST["preSaveData"])) {
		include '../../layout/db.php';

		function sanitizePlus($data) {
			$data = trim($data);
			$data = stripslashes($data);
			$data = strip_tags($data);
			$data = htmlspecialchars($data);
			return $data;
		}

		function secure_random_string($length) { 
			$random_string = ''; 
			for($i = 0; $i < $length; $i++) { 
				$number = random_int(0, 36);  
				$character = base_convert($number, 10, 36);
				$random_string .= $character; 
			} 
					
			return $random_string;
		}

		$rand = secure_random_string(10);
		
		$value = $_POST["preSaveData"];
        $getArray = getToArray($value);
        
        if(isset($_SESSION["presave"]) !== '' || isset($_SESSION["presave"]) !== null){
            unset($_SESSION["presave"]);
            $_SESSION["presave"] = $getArray;
        }else{
            $_SESSION["presave"] = $getArray;
        }
        
        echo 'saved';
		
    }
    
?>