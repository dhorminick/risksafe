<?php

// include_once("../config.php");
 
class db {
	
	private $host;
	private $username;
	private $password;
	private $database;
	
	public function __construct(){
		
		$this->host='localhost';
		$this->username='root';//'master';
		$this->password= '';//'Security#2023$';//'Kni7123414' //'Security#2023$';
		$this->database='risksafe';				
	}

	public function connect() {
	
		if ($conn = new mysqli($this->host, $this->username, $this->password, $this->database)) {
			$ok=true;
			$conn->set_charset("utf8");
		} else {
			$ok=false;	
		}
		
		if ($ok) {
			return $conn;
		} else {
			return false;	
		}
		
	}
	
	public function disconnect($conn) {
	
		if ($conn->close()) {
			return true;
		} else {
			return false;
		}
	
		
	}
	
	public function rowCount($conn, $table, $cond, $value) {
	
		if (trim($cond)<>"") {
			$query="SELECT * FROM " . $table . " WHERE " . $cond . "=" . $value;
		} else {
			$query="SELECT * FROM " . $table;
		}
		if ($result=$conn->query($query)) {
			return $result->num_rows;
		} else {
			return false;	
		}
	
		
	}
	
}

class encrypt {
	
	private $keyy;
	private $iv;
	
	public function __construct(){
		$this->keyy="dkJI893hnjkK8db30dNJjd3kdkjDJbjkr3fdf";
		$this->iv="jkKUHAUD823dnskd39DANlk3scfjnp39nfdslf";
	}
	
	public function encrypt_decrypt($action, $string) {
		$output = false;
	
		$encrypt_method = "AES-256-CBC";
	
		// hash
		$keyy = hash('md5', $this->keyy);
		
		// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
		$iv = substr(hash('md5', $this->iv), 0, 16);
	
		if( $action == 'encrypt' ) {
			$output = openssl_encrypt($string, $encrypt_method, $keyy, 0, $iv);
			$output = base64_encode($output);
		}
		else if( $action == 'decrypt' ){
			if (trim($string)<>"") {
				$output = openssl_decrypt(base64_decode($string), $encrypt_method, $keyy, 0, $iv);
			} else {
				$output="";	
			}
		}
	
		return $output;
	}	
	
}

?>
