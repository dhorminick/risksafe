<?php

// include_once dirname(__FILE__) . '/../Classes/vendor/phpmailer/phpmailer/src/PHPMailer.php';
// require_once dirname(__FILE__) . '/../Classes/vendor/phpmailer/phpmailer/src/SMTP.php';
include_once("../config.php");
include_once("../model/db.php");
include_once("../model/users.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
//Load composer's autoloader
//require_once '/var/www/html/risksafe/vendor/autoload.php';
// require 'vendor/autoload.php';

$user = new users;

if (isset($_POST["action"]) && $_POST["action"] == "signup") {
    if (!$user->checkUser(sanitizePlus($_POST["email"]))) {
        $email = sanitizePlus($_POST["email"]);
        $password = sanitizePlus($_POST["password"]);
        $name = sanitizePlus($_POST["name"]);
        $company = sanitizePlus($_POST["company"]);

        if ($user->addUser($email, $password, $name, '', '', $company, '', '', '', '', '', '')) {
            $user->loginUser($email, $password);
            header("Location: ../view/userprofile.php?action=edit");
            exit();
        } else {
            header("Location: ../../index.php?response=error#sg");
            exit();
        }
    } else {
        header("Location: ../../index.php?response=err_mail#sg");
        exit();
    }
}




//UPDATE USER
if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "profile") {

	if ($_REQUEST["email_old"] == $_REQUEST["email"] or !$user->checkUser(sanitizePlus($_REQUEST["email"]))) {
		if ($user->updateUser(
			sanitizePlus($_REQUEST["id"]), 
			sanitizePlus($_REQUEST["email"]), 
			sanitizePlus($_REQUEST["password"]), 
			sanitizePlus($_REQUEST["name"]), 
			sanitizePlus($_REQUEST["phone"]), 
			sanitizePlus($_REQUEST["location"]), 
			sanitizePlus($_REQUEST["company"]), 
			sanitizePlus($_REQUEST["companyaddress"]), 
			sanitizePlus($_REQUEST["city"]), 
			sanitizePlus($_REQUEST["state"]), 
			sanitizePlus($_REQUEST["postcode"]), 
			sanitizePlus($_REQUEST["country"]))) {
			header("Location: ../view/userprofile.php?action=edit&response=true&id=" . $_REQUEST["id"]);
		} else {
			header("Location: ../view/userprofile.php?response=err");
		}
	} else {
		header("Location: ../view/userprofile.php?response=err_mail");
	}
}

//LOGOUT
if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "logout") {

	$user->logoutUser();
	header("Location: ../view/login.php");
}

//LOGIN
if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "login") {

	if ($user->loginUser(
		$_REQUEST["email"],
	 	$_REQUEST["password"]
	)) {
		header("Location: ../view/main.php");
		exit;
	} else {
		header("Location: ../view/login.php?login=false");
		exit;
	}
}

//UPDATE CONTEXT
if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "context") {

	if ($user->updateContext(
		sanitizePlus($_REQUEST["id"]), 
		sanitizePlus($_REQUEST["objectives"]), 
		sanitizePlus($_REQUEST["processes"]), 
		sanitizePlus($_REQUEST["products"]), 
		sanitizePlus($_REQUEST["projects"]), 
		sanitizePlus($_REQUEST["systems"]), 
		sanitizePlus($_REQUEST["relationships"]), 
		sanitizePlus($_REQUEST["internallosses"]), 
		sanitizePlus($_REQUEST["externallosses"]), 
		sanitizePlus($_REQUEST["competitors"]), 
		sanitizePlus($_REQUEST["environment"]), 
		sanitizePlus($_REQUEST["regulatory"])
	)) {
		header("Location: ../view/businesscontext.php?response=true");
	} else {
		header("Location: ../view/businesscontext.php?response=err");
	}
}

// Send Email for Get OTP
if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "reset") {
	$email = 'binarydata.jagroop@gmail.com';
	// validate email
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		// email is not valid, show error message
		echo "Invalid email address.";
	} else {
		// email is valid, generate OTP
		$otp = rand(1000, 9999);

		$to = $email;
		$subject = "One-Time Password (OTP)";
		$message = "Your one-time password is: $otp";
		$headers = "From:binarydata.sale@gmail.com";
		echo $otp;
		// mail($to, $subject, $message, $headers);
		// echo 'success';

		$mail = new PHPMailer();
		//Enable SMTP debugging
		$mail->SMTPDebug = 0;
		
		$mail->isSMTP();
		$mail->Host = 'smtp.gmail.com';
		$mail->SMTPAuth = true;
		$mail->Username = 'dev3.bdpl@gmail.com';
		$mail->Password = 'binarydata000';
		$mail->SMTPSecure = 'tls';
		$mail->Port = 587;
		$mail->setFrom('dev3.bdpl@gmail.com');
		$mail->addAddress('binarydata.jagroop@gmail.com');

		$mail->isHTML(true);
		$mail->Subject = 'RiskSafe- Reset Password One-Time Password (OTP)';
		$mail->Body = 'Your one-time password is:' . $otp;


		if ($mail->send()) {
			echo "OTP sent to your email.";
			header("Location: ../view/forgetpassword.php?email=$email?action=passwordupdate");
		} else {
			echo "Failed to send OTP via email.". $mail->ErrorInfo;
		};
			// header("Location: ../../index.php?response=err_mail#sg");	
		}
	}


// Send Email for Get OTP
// Send Email for Get OTP
if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "passwordupdate") {
	
	$req_email = $_REQUEST['email'];
	$email = rtrim($req_email, "?action=passwordupdate");
	$otp = $_POST['otp'];
	$password = $_POST['password'];
	$confirm_password = $_POST['confirm_password'];
	if ($user->checkUserOTP($email,$otp)){
		
		if ($password==$confirm_password){
			if ($user->updateEmailUser($email, $password)) {
				$user->loginUser($email, $password);
				header("Location: ../view/userprofile.php?action=edit");
			}
		}else{
			header("Location: ../view/forgetpassword.php?email=$req_email?response=errmsg");
		}
	} else {
	
		 header("Location: ../view/forgetpassword.php?email=$req_email?response=err");
		 

	}
}


//user list 
if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "list") {
    $start = isset($_REQUEST["start"]) ? intval($_REQUEST["start"]) : 0;
    $length = isset($_REQUEST["length"]) ? intval($_REQUEST["length"]) : 10;

    $db = new db;
    $conn = $db->connect();
    
    // Get the paginated list of users and total count from the listUser() method
    $result = $user->listUser($start, $length);
    $list = $result["data"];
    $num = $result["num_total"];

    $fulldata = array();
    $data = array();

    $fulldata["draw"] = isset($_REQUEST["draw"]) ? intval($_REQUEST["draw"]) : 1;
    $fulldata["recordsTotal"] = $num;
    $fulldata["recordsFiltered"] = $num;

    if ($list !== false) {
        foreach ($list as $item) {
            $response = array();
            $response["nr"] = $item["iduser"];
            $response["User Name"] = $item["u_name"];
            $response["User Email"] = $item["u_mail"];
            $response["Company"] = $item["c_company"];
            $response["Phone"] = $item["u_phone"];
            $response["Role"] = $item["role"];
            $response["link"] = '<div style="text-align: center;">
                <a title="Edit" class="btn btn-xs btn-primary" href="alluser.php?action=edit&id=' . $item["iduser"] . '"><i class="glyphicon glyphicon-pencil"></i></a>&nbsp;
                <a title="Delete" class="btn btn-xs btn-danger" href="javascript:del(\'' . $item["iduser"] . '\');"><i class="glyphicon glyphicon-trash"></i></a></div>';
            $data[] = array_values($response);
        }
    }

    $fulldata["data"] = $data;

    echo json_encode($fulldata);
    exit();
}
if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "searchbar") {
    $searchTerm = $_POST['search'];
	$count = $user->getAllUsersCount();
	$start = isset($_REQUEST["start"]) ? intval($_REQUEST["start"]) : 0;
    $length = isset($_REQUEST["length"]) ? intval($_REQUEST["length"]) :$count;
	$result = $user->listUser($start, $length); // Retrieve all users
    $list = $result["data"];
    
    foreach ($list as $user) {
        if (stripos($user['u_name'], $searchTerm) !== false) {
            echo '<tr>';
            echo '<td>' . $user['u_name'] . '</td>';
            echo '<td>' . $user['u_mail'] . '</td>';
            echo '<td>' . $user['c_company'] . '</td>';
            echo '<td>' . $user['u_phone'] . '</td>';
            echo '<td>' . $user['role'] . '</td>';
			echo '<td>' .	'<div style="text-align: center;">
                <a title="Edit" class="btn btn-xs btn-primary" href="alluser.php?action=edit&id=' . $user["iduser"] . '"><i class="glyphicon glyphicon-pencil"></i></a>&nbsp;
                <a title="Delete" class="btn btn-xs btn-danger" href="javascript:del(\'' . $user["iduser"] . '\');"><i class="glyphicon glyphicon-trash"></i></a></div>'. '</td>';
            // Other columns...
            echo '</tr>';
        }
    }
}
// if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "searchbar") {
//     $searchTerm = $_POST['search'];
//     $count = $user->getAllUsersCount();
      
//     $result = $user->searchUsers($searchTerm); // Search for users
//     $list = $result["data"];
  

//     $fulldata = array();
//     $data = array();

//     $fulldata["draw"] = isset($_REQUEST["draw"]) ? intval($_REQUEST["draw"]) : 1;
   
   
// 	if ($list !== false) {
//         foreach ($list as $item) {
//             $response = array();
//             $response["nr"] = $item["iduser"];
//             $response["User Name"] = $item["u_name"];
//             $response["User Email"] = $item["u_mail"];
//             $response["Company"] = $item["c_company"];
//             $response["Phone"] = $item["u_phone"];
//             $response["Role"] = $item["role"];
//             $response["link"] = '<div style="text-align: center;">
//                 <a title="Edit" class="btn btn-xs btn-primary" href="alluser.php?action=edit&id=' . $item["iduser"] . '"><i class="glyphicon glyphicon-pencil"></i></a>&nbsp;
//                 <a title="Delete" class="btn btn-xs btn-danger" href="javascript:del(\'' . $item["iduser"] . '\');"><i class="glyphicon glyphicon-trash"></i></a></div>';
//             $data[] = array_values($response);
//         }
//     }

//     $fulldata["data"] = $data;

//     echo json_encode($fulldata);
//     exit();
// }






// //Add user
if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "add") {
	
	$email = isset($_REQUEST["email"]) ? $_REQUEST["email"] : "";
	$password = isset($_REQUEST["password"]) ? $_REQUEST["password"] : "";
	$name = isset($_REQUEST["name"]) ? $_REQUEST["name"] : "";
	$phone = isset($_REQUEST["phone"]) ? $_REQUEST["phone"] : "";
	$location = isset($_REQUEST["location"]) ? $_REQUEST["location"] : "";
	$company = isset($_REQUEST["company"]) ? $_REQUEST["company"] : "";
	$companyaddress = isset($_REQUEST["companyaddress"]) ? $_REQUEST["companyaddress"] : "";
	$city = isset($_REQUEST["city"]) ? $_REQUEST["city"] : "";
	$state = isset($_REQUEST["state"]) ? $_REQUEST["state"] : "";
	$postcode = isset($_REQUEST["postcode"]) ? $_REQUEST["postcode"] : "";
	$country = isset($_REQUEST["country"]) ? $_REQUEST["country"] : "";
  
	$result = $user->addusers(
	  $email,
	  $password,
	  $name,
	  $phone,
	  $location,
	  $company,
	  $companyaddress,
	  $city,
	  $state,
	  $postcode,
	  $country
	);
  
    if ($result) {
        header("Location: ../view/allusers.php");
      } 
  }
  // DELETE COMPLIANCE STANDARD
if (isset($_REQUEST["action"]) && $_REQUEST["action"]=="delete") {
		
	echo $user->deleteUser($_REQUEST["id"]);
	
}



//UPDATE USER
if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "edit") {
    $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : "";
    $email = isset($_REQUEST["email"]) ? $_REQUEST["email"] : "";
    $password = isset($_REQUEST["password"]) ? $_REQUEST["password"] : "";
    $name = isset($_REQUEST["name"]) ? $_REQUEST["name"] : "";
    $phone = isset($_REQUEST["phone"]) ? $_REQUEST["phone"] : "";
    $location = isset($_REQUEST["location"]) ? $_REQUEST["location"] : "";
    $company = isset($_REQUEST["company"]) ? $_REQUEST["company"] : "";
    $companyaddress = isset($_REQUEST["companyaddress"]) ? $_REQUEST["companyaddress"] : "";
    $city = isset($_REQUEST["city"]) ? $_REQUEST["city"] : "";
    $state = isset($_REQUEST["state"]) ? $_REQUEST["state"] : "";
    $postcode = isset($_REQUEST["postcode"]) ? $_REQUEST["postcode"] : "";
    $country = isset($_REQUEST["country"]) ? $_REQUEST["country"] : "";

    $result = $user->editusers(
        $id,
        $email,
        $password,
        $name,
        $phone,
        $location,
        $company,
        $companyaddress,
        $city,
        $state,
        $postcode,
        $country
    );

    if ($result) {
        header("Location: ../view/alluser.php?action=edit&response=true&id=" . $_REQUEST["id"]);
        exit();
    } else {
        header("Location: ../view/alluser.php?response=err&id=". $_REQUEST["id"]);
        exit();
    }
}


if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "resetupdate"){
	$otp = rand(1000, 9999);

	$email =$_REQUEST['email'];
	
	$result = $user->updateOtp($email,$otp);

	$mail = new PHPMailer(true);
	// SMTP settings for Mailtrap
	$mail->isSMTP();
	$mail->Host = 'sandbox.smtp.mailtrap.io';
	$mail->Port = 2525;
	$mail->SMTPAuth = true;
	// $mail->Username = 'jay@risksafe.co'; // Replace with your Mailtrap username
	// $mail->Password = 'Welcome901#@!'; // Replace with your Mailtrap password
	$mail->Username = '7aa331c1243777'; // Replace with your Mailtrap username
	$mail->Password = '979d7e4cc02893'; // Replace with your Mailtrap password
	//BBs35JSmbWjWfi+7/v+e03CcORG4h181ZvMVlpD+pDoo
	
	$mail->SMTPSecure = 'TLS';
	$mail->SMTPDebug = SMTP::DEBUG_SERVER;
	$mail->Debugoutput = 'html';
	$subject='Your OTP password';
	// Email content
	
	$mail->setfrom('jay@risksafe.co', 'Risksafe');
	$mail->addAddress($email);
	//$mailto:mail->addaddress('shwetachauhan035@gmail.com');
	$mail->Subject = $subject;
	// Email body

	//$body=// Email message (HTML content)
	$body='<!DOCTYPE html>
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
					<p>Dear ,</p>
					<p>
					Your  OTP password is '.$otp.'; 
					
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
	$message = 'New Assessment Created';
	// Set the email body as HTML
	$mail->isHTML(true);

	if ($mail->send()) {
		//echo "OTP sent to your email.";
		// header("Location: ../view/forgetpassword.php?email=$email?action=passwordupdate");
		echo '<script type="text/javascript">window.location = "../view/forgetpassword.php?email='.$email.'?action=passwordupdate&success=1"</script>';
		exit;
			
	} else {
	echo 'Mailer Error: ' . $mail->ErrorInfo;
	}

	

}

if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "changepassword"){
	$oldpassword=$_REQUEST['old_pwd'];
	$newpassword=$_REQUEST['new_pwd'];
	$confirmpassword=$_REQUEST['con_pwd'];

	if ($user->checkUseroldpassword($oldpassword)){
		if($newpassword==$confirmpassword){
		 $result = $user->Updatepasswordchange($newpassword);

		if($result==1){
			header("Location: ../view/changepassword.php?response=true");
		}else{
			header("Location: ../view/changepassword.php?response=errsg");

		}

		}else{
			header("Location: ../view/changepassword.php?response=erpwdmatch");

		}


	}else{
		header("Location: ../view/changepassword.php?response=errchkpwd");


	}
	


}





