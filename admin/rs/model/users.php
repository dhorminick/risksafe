<?php

include_once("../config.php");
include_once("db.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';

class users
{


	public function __construct()
	{
	}


	public function addUser($email, $password, $name, $phone, $location, $company, $otp, $companyAddress, $city, $state, $postcode, $country)
	{

		$password_md5 = md5($password);
		$datetime = date("Y-m-d H:i:s");
		$expire = date("Y-m-d H:i:s", strtotime("+14 days"));

		$db = new db();
		$conn = $db->connect();

		$query = "INSERT INTO users (`iduser`, `superuserid`, `u_mail`, `u_password`, `u_name`, `u_phone`, `u_location`, `c_company`, `c_address`, `c_city`, `c_state`, `c_postcode`, `c_country`, `u_complete`, `u_otp`, `u_datetime`, `u_expire`, `role`,`user_loginstatus`)
              VALUES (NULL, 0, '$email', '$password_md5', '$name', '$phone', '$location', '$company', '$companyAddress', '$city', '$state', '$postcode', '$country', '0', '$otp', '$datetime', '$expire', 'client',0)";


		$result = mysqli_query($conn, $query);


		if ($result) {
			echo "working";
			$userId = mysqli_insert_id($conn);

			$query = "INSERT INTO `as_context`(`idcontext`, `cx_user`, `cx_objectives`, `cx_processes`, `cx_products`, `cx_projects`, `cx_systems`, `cx_relation`, `cx_internallosses`, `cx_externallosses`, `cx_competitors`, `cx_environment`, `cx_regulatory`) VALUES (Null,'$userId','','','','','','','','','','','')";

			$result = mysqli_query($conn, $query);

			if ($result) {
				$db->disconnect($conn);
				return true;
			} else {
				echo "Error11: " . mysqli_error($conn);
				exit();
			}
		} else {
			echo "Error: " . mysqli_error($conn);
		}
	}




	public function updateUser($id, $mail, $password, $name, $phone, $location, $company, $comaddress, $city, $state, $postcode, $country)
	{

		$db = new db;
		$conn = $db->connect();

		$password_md5 = md5($password);

		//check password
		$query = "SELECT * FROM users WHERE iduser=" . $id;
		$result = $conn->query($query);
		$usr = $result->fetch_assoc();

		if ($usr["u_password"] == $password_md5) {
			$query = "UPDATE users SET
					u_mail='" . $mail . "', 
					u_name='" . $name . "', 
					u_phone='" . $phone . "', 
					u_location='" . $location . "', 
					c_company='" . $company . "', 
					c_address='" . $comaddress . "', 
					c_city='" . $city . "', 
					c_state='" . $state . "', 
					c_postcode='" . $postcode . "', 
					u_complete=1, 
					c_country='" . $country . "' WHERE iduser=" . $id;
		} else {
			$query = "UPDATE users SET
					u_mail='" . $mail . "', 
					u_password='" . $password_md5 . "', 
					u_name='" . $name . "', 
					u_phone='" . $phone . "', 
					u_location='" . $location . "', 
					c_company='" . $company . "', 
					c_address='" . $comaddress . "', 
					c_city='" . $city . "', 
					c_state='" . $state . "', 
					c_postcode='" . $postcode . "', 
					u_complete=1, 
					c_country='" . $country . "' WHERE iduser=" . $id;
		}


		if ($conn->query($query)) {
			return true;
		} else {
			return false;
		}

		$db->disconnect($conn);
	}

	public function checkUser($mail)
	{

		$db = new db;
		$conn = $db->connect();

		//check username/mail
		$query = "SELECT * FROM users WHERE u_mail='" . $mail . "';";
		$result = $conn->query($query);

		if ($result->num_rows > 0) {
			return true;
		} else {
			return false;
		}

		$db->disconnect($conn);
	}

	// public function getUser($userid) {
	// 	$db = new db;
	// 	$conn = $db->connect();
	// 	$query = "SELECT * FROM users WHERE iduser=" . $userid . " LIMIT 0,1";

	// 	if ($result = $conn->query($query)) {
	// 		if ($row = $result->fetch_assoc()) {
	// 			// Check if $row is not null before applying stripslashes and htmlspecialchars
	// 			if (!is_null($row)) {
	// 				$filtered = array_map(function ($value) {
	// 					if (is_string($value)) {
	// 						return htmlspecialchars(stripslashes($value));
	// 					}
	// 					return $value;
	// 				}, $row);
	// 				return $filtered;
	// 			}
	// 		}
	// 	} else {
	// 		return false;
	// 	}

	// 	$db->disconnect($conn);
	// }
	public function getUser($userid)
	{
		$db = new db;
		$conn = $db->connect();
		$query = "SELECT * FROM users WHERE iduser=" . $userid . " LIMIT 0,1";

		if ($result = $conn->query($query)) {
			if ($row = $result->fetch_assoc()) {
				// Check if $row is not null before applying stripslashes and htmlspecialchars
				if (!is_null($row)) {
					//$filtered = array_map('htmlspecialchars', $row);
					//	$filtered = array_map('stripslashes', $filtered);
					return $row;
				}
			}
		} else {
			return false;
		}



		$db->disconnect($conn);
	}


	// public function loginUser($mail, $password) {

	// 	$db=new db;
	// 	$conn=$db->connect();
	// 	$password=md5($password);

	// 	$query="SELECT * FROM users WHERE u_mail='" . $mail . "' AND u_password='" . $password . "';";


	// 	if (trim($mail)<>"" and trim($password)<>"" and $result=$conn->query($query) and $result->num_rows>0) {
	// 		$row=$result->fetch_assoc();

	// 		$registrationDate = strtotime($row["u_datetime"]);
	// 		$currentDate = time();
	// 		$daysPassed = floor(($currentDate - $registrationDate) / (60 * 60 * 24));

	// 		if ($daysPassed >= 14 && $row['role'] !== 'superadmin') {
	// 			$_SESSION["logged"] = true;

	// 			$_SESSION["email"] = $row["u_mail"];
	// 			$_SESSION["name"] = $row["u_name"];
	// 			$_SESSION["userid"] = $row["iduser"];
	// 			header("Location: ../view/payment.php");
	// 			exit();
	// 		} else {
	// 			$_SESSION["logged"] = true;
	// 			$_SESSION["email"] = $row["u_mail"];
	// 			$_SESSION["name"] = $row["u_name"];
	// 			$_SESSION["userid"] = $row["iduser"];
	// 			//return true;
	// 			if($row["user_loginstatus"]=='0' ||$row["user_loginstatus"]=='NULL'){

	// 				$query11="UPDATE users set user_loginstatus='1' WHERE iduser='" . $row["iduser"] . "'";
	// 				if ($conn->query($query11)){


	// 					$this->sendNotificationEmailadmin($row["u_mail"]);
	// 					return true;

	// 				}else{
	// 					return false;	
	// 				}

	// 				$db->disconnect($conn);

	// 			}

	// 			return true;
	// 		}
	// 	} else {
	// 		$this->logoutUser();
	// 		return false;
	// 	}


	// $db->disconnect($conn);	

	// }
	public function canAccessAllPages()
	{
		if (isset($_SESSION["logged"]) && $_SESSION["logged"] == true) {
			$db = new db;
			$conn = $db->connect();

			$userId = $_SESSION["userid"];
			$query = "SELECT u_datetime FROM users WHERE iduser = $userId";
			$result = $conn->query($query);

			if ($result && $result->num_rows > 0) {
				$row = $result->fetch_assoc();
				$registrationDate = strtotime($row["u_datetime"]);
				$currentDate = time();
				$daysPassed = floor(($currentDate - $registrationDate) / (60 * 60 * 24));

				$db->disconnect($conn);

				return $daysPassed < 14;
			}

			$db->disconnect($conn);
		}

		return false;
	}
	public function logoutUser()
	{

		unset($_SESSION["logged"]);
		unset($_SESSION["email"]);
		unset($_SESSION["name"]);
		unset($_SESSION["userid"]);
		return true;
	}

	public function isLogged()
	{

		if (isset($_SESSION["logged"]) and $_SESSION["logged"] == true) {
			return true;
		} else {
			return false;
		}
	}

	public function updateContext($id, $objectives, $processes, $products, $projects, $systems, $relationships, $internallosses, $externallosses, $competitors, $environment, $regulatory)
	{

		$db = new db;
		$conn = $db->connect();

		$query = "UPDATE as_context SET
				cx_objectives='" . str_replace("'", "\'", $objectives) . "', 
				cx_processes='" . str_replace("'", "\'", $processes) . "', 
				cx_products='" . str_replace("'", "\'", $products) . "', 
				cx_projects='" . str_replace("'", "\'", $projects) . "', 
				cx_systems='" . str_replace("'", "\'", $systems) . "', 
				cx_relation='" . str_replace("'", "\'", $relationships) . "', 
				cx_internallosses='" . str_replace("'", "\'", $internallosses) . "', 
				cx_externallosses='" . str_replace("'", "\'", $externallosses) . "', 
				cx_competitors='" . str_replace("'", "\'", $competitors) . "', 
				cx_environment='" . str_replace("'", "\'", $environment) . "',
				cx_regulatory='" . str_replace("'", "\'", $regulatory) . "' WHERE idcontext=" . $id;


		if ($conn->query($query)) {
			return true;
		} else {
			return false;
		}

		$db->disconnect($conn);
	}

	public function getContext($userid)
	{

		$db = new db;
		$conn = $db->connect();
		$query = "SELECT * FROM as_context WHERE cx_user=" . $userid . " LIMIT 0,1";
		if ($result = $conn->query($query)) {
			if ($row = $result->fetch_assoc()) {
				$filtered = array_map('htmlspecialchars', array_map('stripslashes', $row));
				return $filtered;
			}
		} else {
			return false;
		}

		$db->disconnect($conn);
	}

	// update otp
	// update otp
	public function updateOtp($mail, $otp)
	{

		$db = new db;
		$conn = $db->connect();

		$query = "UPDATE users SET 
		u_otp='" . $otp . "' WHERE u_mail='$mail'";
		if ($conn->query($query)) {
			return true;
		} else {
			return false;
		}

		$db->disconnect($conn);
	}


	//update password
	//update password
	public function updateEmailUser($mail, $password)
	{

		$db = new db;
		$conn = $db->connect();

		$query = "UPDATE users SET 
	u_password='" . md5($password) . "' WHERE u_mail='" . $mail . "';";

		if ($conn->query($query)) {
			return true;
		} else {
			return false;
		}

		$db->disconnect($conn);
	}

	//list user


	// List applicable entries
	public function listUser($start, $length)
	{

		$db = new db();
		$conn = $db->connect();
		$currentUser = $_SESSION["userid"];

		$sql = "SELECT role FROM users WHERE iduser = '$currentUser'";
		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_assoc($result);
			$currentUserRole = $row['role'];

			$query = "";
			$countQuery = "";

			if ($currentUserRole == "superadmin") {
				$query = "SELECT * FROM users ORDER BY iduser DESC ";
				$countQuery = "SELECT COUNT(*)
				AS total_count FROM users";
			} else if ($currentUserRole == "client") {
				$query = "SELECT * FROM users   WHERE superuserid = '$currentUser'  ORDER BY iduser DESC LIMIT $start, $length";
				$countQuery = "SELECT COUNT(*)
				AS total_count FROM users WHERE superuserid = '$currentUser'";
			} else {
				header("Location: main.php");
				exit;
			}

			// Fetch the paginated list of users
			$result = $conn->query($query);

			if ($result !== false && $result->num_rows > 0) {
				$data = array();
				while ($row = $result->fetch_assoc()) {
					$data[] = $row;
				}

				// Fetch the total count of records based on the user's role
				$countResult = $conn->query($countQuery);
				$countRow = $countResult->fetch_assoc();
				$num_total = $countRow["total_count"];

				$db->disconnect($conn);
				return array("data" => $data, "num_total" => $num_total);
			} else {
				$db->disconnect($conn);
				return array("data" => array(), "num_total" => 0);
			}
		} else {
			echo "User not found.";
		}
	}


	public function addusers($email, $password, $name, $phone, $location, $company, $companyaddress, $city, $state, $postcode, $country)
	{


		$db = new db();
		$conn = $db->connect();
		$password_md5 = md5($password);
		$userId = $_SESSION["userid"];
		$role = ""; // Initialize the role variable

		// Check if the current user is a superadmin
		$query = "SELECT role FROM users WHERE iduser = '$userId'";
		$result = mysqli_query($conn, $query);

		if (mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_assoc($result);
			$role = $row['role'];

			// Check if the role is superadmin and the total number of users added by the superadmin is less than or equal to 20
			if ($role == "superadmin" || $role == "client") {

				$query = "SELECT COUNT(*) as userCount FROM users WHERE superuserid = '$userId'";
				$result = mysqli_query($conn, $query);

				if ($result && mysqli_num_rows($result) > 0) {
					$row = mysqli_fetch_assoc($result);
					$userCount = $row['userCount'];

					if ($userCount >= 20) {

						// Return false if the superadmin has already added 20 users
						//return false;
						header("Location: ../view/allusers.php?response=err_limitexceed");
						exit();
					}
				}

				// Check if the email already exists in the database
				$query = "SELECT * FROM users WHERE u_mail = '$email'";
				$result = mysqli_query($conn, $query);

				if (mysqli_num_rows($result) > 0) {
					// Return an error message if the email already exists
					return "Email already exists. Please choose a different email.";
				}

				// Insert the user into the database
				$query = "INSERT INTO `users`(`superuserid`, `u_mail`, `u_password`, `u_name`, `u_phone`, `u_location`, `c_company`, `c_address`, `c_city`, `c_state`, `c_postcode`, `c_country`, `u_complete`, `u_otp`, `u_datetime`, `u_expire`, `role`,`user_loginstatus`)
                VALUES ('$userId', '$email', '$password_md5', '$name', '$phone', '$location', '$company', '$companyaddress', '$city', '$state', '$postcode', '$country', '0', '', NOW(), NOW(), 'user',0)";

				$sql = mysqli_query($conn, $query);

				if ($sql) {

					$userId = mysqli_insert_id($conn);

					$query = "INSERT INTO `as_context`(`idcontext`, `cx_user`, `cx_objectives`, `cx_processes`, `cx_products`, `cx_projects`, `cx_systems`, `cx_relation`, `cx_internallosses`, `cx_externallosses`, `cx_competitors`, `cx_environment`, `cx_regulatory`) VALUES (Null,'$userId','','','','','','','','','','','')";

					$result = mysqli_query($conn, $query);

					if ($result) {
						$db->disconnect($conn);
						$this->sendmailnotifytoadminuser($email, $name, $password);

						return true;
					} else {
						echo "Error11: " . mysqli_error($conn);
						exit();
					}
				} else {
					return false;
				}
			} else {
				// Return false if the current user is not a superadmin
				return false;
			}
		} else {
			// Return false if the user is not found
			return false;
		}
	}


	//delete user

	public function deleteUser($id)
	{

		$db = new db();
		$conn = $db->connect();

		$query = "DELETE FROM users WHERE iduser=" . $id . ";";

		if ($conn->multi_query($query)) {
			$response = true;
		} else {
			$response = false;
		}

		$db->disconnect($conn);
		return $response;
	}

	//get user
	public function getUsers($id)
	{
		$db = new db();
		$conn = $db->connect();

		$query = "SELECT * FROM users WHERE iduser = " . $id;

		if ($result = $conn->query($query)) {
			$row = $result->fetch_assoc();
			$response = $row;
		} else {
			$response = false;
		}

		$db->disconnect($conn);
		return $response;
	}


	public function getAllUsersCount()
	{
		$db = new db();
		$conn = $db->connect();

		$query = "SELECT COUNT(*) as total_users FROM users"; // Count all users

		if ($result = $conn->query($query)) {
			$row = $result->fetch_assoc();
			$userCount = $row['total_users'];
		} else {
			$userCount = 0;
		}

		$db->disconnect($conn);
		return $userCount;
	}
	public function searchUsers($searchTerm)
	{
		$db = new db();
		$conn = $db->connect();

		// Perform a SQL query to fetch filtered user records
		$query = "SELECT * FROM users WHERE u_name LIKE '%$searchTerm%'";

		$result = $conn->query($query);

		if ($result !== false && $result->num_rows > 0) {
			$data = array();
			while ($row = $result->fetch_assoc()) {
				$data[] = $row;
			}

			$db->disconnect($conn);
			return array("data" => $data);
		} else {
			$db->disconnect($conn);
			return array("data" => array());
		}
	}



	//updated
	public function editusers($id, $email, $password, $name, $phone, $location, $company, $companyaddress, $city, $state, $postcode, $country)
	{
		$db = new db();
		$conn = $db->connect();
		$userId = $_SESSION["userid"];
		$password_md5 = md5($password);
		$query = "UPDATE users SET
                superuserid = '$userId',
                u_password = '$password_md5',
                u_name = '$name',
                u_phone = '$phone',
                u_location = '$location',
                c_company = '$company',
                c_address = '$companyaddress',
                c_city = '$city',
                c_state = '$state',
                c_postcode = '$postcode',
                c_country = '$country'
              WHERE iduser = '$id'";

		$sql = mysqli_query($conn, $query);

		if ($sql) {
			return true;
		} else {
			return false;
		}
	}


	public function loginUser($mail, $password)
	{

		$db = new db;
		$conn = $db->connect();
		// $password = md5($password);

		$query = "SELECT * FROM users WHERE u_mail='" . $mail . "' AND u_password='" . $password . "';";


		if (trim($mail) <> "" and trim($password) <> "" and $result = $conn->query($query) and $result->num_rows > 0) {

			$row = $result->fetch_assoc();
			// $registrationDate = strtotime($row["u_datetime"]);
			// $currentDate = time();

			// $daysPassed = floor(($currentDate - $registrationDate) / (60  60  24));

			$registrationDate = $row["u_datetime"];
			$expiryDate = $row["u_expire"];

			$date1 = $registrationDate;

			$date2 = $expiryDate;
			$date3 = date('Y-m-d H:i:s');
			//$date3 = date('Y-m-d H:i:s', strtotime("+17 day"));

			if ($row["role"] == 'superadmin') {
				$_SESSION["logged"] = true;
				$_SESSION["email"] = $row["u_mail"];
				$_SESSION["name"] = $row["u_name"];
				$_SESSION["userid"] = $row["iduser"];
				header("Location: ../view/userprofile.php");
				exit();
			} else {

				if ($date3 > $date2) {

					$_SESSION["logged"] = true;

					$_SESSION["email"] = $row["u_mail"];
					$_SESSION["name"] = $row["u_name"];
					$_SESSION["userid"] = $row["iduser"];
					header("Location: ../view/payment.php");
					exit();
				} else {
					$_SESSION["logged"] = true;
					$_SESSION["email"] = $row["u_mail"];
					$_SESSION["name"] = $row["u_name"];
					$_SESSION["userid"] = $row["iduser"];


					if ($row["user_loginstatus"] == '0') {

						$query11 = "UPDATE users set user_loginstatus='1' WHERE iduser='" . $row["iduser"] . "'";
						if ($conn->query($query11)) {


							$this->sendNotificationEmailadmin($row["u_mail"]);
							header("Location: ../view/userprofile.php");
							exit();
							return true;
						} else {
							return false;
						}

						$db->disconnect($conn);
					}
					header("Location: ../view/main.php");
					exit();

					return true;
				}
			}
		} else {
			$this->logoutUser();
			return false;
		}


		$db->disconnect($conn);
	}


	function sendNotificationEmailadmin($usermail)
	{

		$mail = new PHPMailer(true);
		// SMTP settings for Mailtrap
		$mail->isSMTP();
		$mail->Host = 'smtp-mail.outlook.com';
		$mail->Port = 587;
		$mail->SMTPAuth = true;
		$mail->Username = 'jay@risksafe.co'; // Replace with your Mailtrap username
		$mail->Password = 'Welcome901#@!'; // Replace with your Mailtrap password
		$mail->SMTPSecure = 'TLS';
		$subject = 'Login Successfull';
		// Email content
		$mail->setfrom('jay@risksafe.co', 'Risksafe');
		$mail->Subject = $subject;

		//implode(" ",$usermail);
		$mail->addAddress($usermail);

		$body = '<!DOCTYPE html>
	<html>
	
	<head>
		<meta charset="UTF-8">
		<title>Email Verification</title>
	</head>
	
	<body>
		<table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse;">
			<tr>
				<td align="center" bgcolor="#f9f9f9" style="padding: 40px 0 30px 0;">
					<img src="https://risksafe.co/img/logo.png" alt="RiskSafe" width="100">
				</td>
			</tr>
			<tr>
				<td align="center" bgcolor="#ffffff" style="padding: 40px 20px 40px 20px; font-family: Arial, sans-serif; font-size: 16px; line-height: 1.6; color: #666666;">
				<p>Dear ' . $usermail . ',</p>
				<p>Congratulations!

				You have successfully logged in to your account.
				
				 We are delighted to have you here. You now have access to all the features and resources available to our valued users.
				</p>
			
				If you need any assistance or have any questions while using our website, please do not hesitate to reach out to our support team. We are here to help you make the most of your experience and ensure your satisfaction.</p>
				</p>
					
					<p>Best regards,<br>Risksafe Team</p>
				</td>
			</tr>
			<tr>
				<td bgcolor="#f9f9f9" align="center" style="padding: 20px 0 30px 0; font-family: Arial, sans-serif; font-size: 12px; color: #666666;">
				<p>&copy; <?php echo date("Y"); ?>RiskSafe. All rights reserved.</p>

				</td>
			</tr>
		</table>
	</body>
	
	</html>';
		$mail->Body = $body;

		// Set the email body as HTML
		$mail->isHTML(true);

		if ($mail->send()) {
			//echo 'mailsent';
			return 1;
		} else {
			echo 'Mailer Error: ' . $mail->ErrorInfo;
		}
	}


	public function checkUserOTP($mail, $otp)
	{

		$db = new db;
		$conn = $db->connect();

		//check username/mail
		$query = "SELECT * FROM users WHERE u_mail='" . $mail . "' AND u_otp='" . $otp . "'";
		$result = $conn->query($query);

		if ($result->num_rows > 0) {
			return true;
		} else {
			return false;
		}

		$db->disconnect($conn);
	}
	public function Updatepasswordchange($pwd)
	{
		$db = new db;
		$conn = $db->connect();

		$currentUser = $_SESSION["userid"];
		$query = "UPDATE users set u_password='" . md5($pwd) . "' WHERE iduser='" . $currentUser . "'";
		$result = $conn->query($query);

		if ($result) {
			return 1;
		} else {
			return 2;
		}

		$db->disconnect($conn);
	}

	public function checkUseroldpassword($pwd)
	{
		$db = new db;
		$conn = $db->connect();

		$currentUser = $_SESSION["userid"];
		$query = "SELECT * FROM users WHERE iduser='" . $currentUser . "' AND u_password='" . md5($pwd) . "'";

		$result = $conn->query($query);

		if ($result->num_rows > 0) {
			return true;
		} else {
			return false;
		}

		$db->disconnect($conn);
	}


	function sendmailnotifytoadminuser($email, $name, $password_md5)
	{

		$mail = new PHPMailer(true);
		// SMTP settings for Mailtrap
		$mail->isSMTP();
		$mail->Host = 'smtp-mail.outlook.com';
		$mail->Port = 587;
		$mail->SMTPAuth = true;
		$mail->Username = 'jay@risksafe.co'; // Replace with your Mailtrap username
		$mail->Password = 'Welcome901#@!'; // Replace with your Mailtrap password
		$mail->SMTPSecure = 'tls';
		$subject = 'Your RiskSafe.co Account Details';
		// Email content
		$mail->setfrom('jay@risksafe.co', 'Risksafe Team');
		$mail->Subject = $subject;

		//implode(" ",$usermail);
		$mail->addAddress($email);

		$body = '<!DOCTYPE html>
	<html>
	
	<head>
		<meta charset="UTF-8">
		<title>Email Verification</title>
	</head>
	
	<body>
		<table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse;">
			<tr>
				<td align="center" bgcolor="#f9f9f9" style="padding: 40px 0 30px 0;">
					<img src="https://risksafe.co/img/logo.png" alt="RiskSafe" width="100">
				</td>
			</tr>
			<tr>
				<td align="center" bgcolor="#ffffff" style="padding: 40px 20px 40px 20px; font-family: Arial, sans-serif; font-size: 16px; line-height: 1.6; color: #666666;">
				<p>Dear ' . $name . ',</p>
				<p>
				We are excited to welcome you to RiskSafe.co, your one-stop platform for managing and mitigating various types of risks. Your account has been successfully created, and we are here to provide you with your login credentials to access your profile.
				</p>
				<p>
				*Username:* ' . $name . '
				<br>
				*Email:* ' . $email . '
				<br>
*Temporary Password:* ' . $password_md5 . '
				</p>
				<p>Please follow these steps to get started:</p>
<p>
<tr>
	<td>
	1. Visit the RiskSafe.co website at https://risksafe.co/
	</td>
</tr>
<tr>
2. Click on the "Login" button located at the top-right corner of the homepage.
</tr>
<tr>
3. Enter your username and the temporary password provided above.
</tr>
<tr>
4. Upon logging in, you will be prompted to change your password for security reasons. Please create a strong, unique password that you can remember.

</tr>

<p>
If you have any questions or encounter any issues during the login process, please feel free to reach out to our support team at mailto:support@risksafe.co We are here to assist you and ensure a smooth experience.
</p>
<p>

Thank you for choosing RiskSafe.co to manage your risks effectively. We look forward to helping you navigate the world of risk management and mitigation.
</p>
					
					<p>Best regards,<br>The RiskSafe.co Team</p>
				</td>
			</tr>
			<tr>
				<td bgcolor="#f9f9f9" align="center" style="padding: 20px 0 30px 0; font-family: Arial, sans-serif; font-size: 12px; color: #666666;">
					<p>&copy; <?php echo date("Y"); ?>RiskSafe. All rights reserved.</p>
				
				</td>
			</tr>
		</table>
	</body>
	
	</html>';
		$mail->Body = $body;

		// Set the email body as HTML
		$mail->isHTML(true);

		if ($mail->send()) {

			return 1;
		} else {
			echo 'Mailer Error: ' . $mail->ErrorInfo;
		}
	}
}
