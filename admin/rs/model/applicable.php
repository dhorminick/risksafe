<?php

include_once("db.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require '../../vendor/autoload.php';
class Applicable {
  
  // Add applicable entry
  public function addApplicable($policyTitle, $policyNumber, $policyDescription, $policyEffectiveDate, $policyReviewDate, $applicability, $policyRequirements, $complianceResponsibility, $relatedDocuments, $policyApproval, $policyReviewRevisionHistory, $policyAcknowledgment) {
    $db = new db();
    $conn = $db->connect();
    
    $userId = $_SESSION["userid"];
    $query = "INSERT INTO policyfields (policy_user_id, PolicyTitle, PolicyNumber, PolicyDescription, PolicyEffectiveDate, PolicyReviewDate, Applicability, PolicyRequirements, ComplianceResponsibility, RelatedDocuments, PolicyApproval, PolicyReviewRevisionHistory, PolicyAcknowledgment) VALUES (?, ?, ?, ?,?,?, ?,?,?,?,?,?,?)";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("issssssssssss",$userId, $policyTitle, $policyNumber, $policyDescription, $policyEffectiveDate,$policyReviewDate, $applicability, $policyRequirements,$complianceResponsibility, $relatedDocuments, $policyApproval, $policyReviewRevisionHistory, $policyAcknowledgment);
    if($stmt->execute()){
      $response =$conn->insert_id;
    }else{
      $response =false;
    }
    $stmt->close();
    $db->disconnect($conn);
    return $response;

  }
  
  // List applicable entries
  public function listApplicable($start, $length) {
    $db = new db();
    $conn = $db->connect();
    $userId = $_SESSION["userid"];
    // Write your query to fetch the applicable entries from the database
    $query = "SELECT * FROM policyfields WHERE policy_user_id = '$userId' LIMIT $start, $length";
    $result = $conn->query($query);
    $data = [];
    while ($row = $result->fetch_assoc()) {
      $data[] = $row;
    }
    $db->disconnect($conn);
    return $data;
  }

  // EDIT APPLICABLE
public function editApplicable(
    $id,
    $policyTitle,
    $policyNumber,
    $policyDescription,
    $policyEffectiveDate,
    $policyReviewDate,
    $applicability,
    $policyRequirements,
    $complianceResponsibility,
    $relatedDocuments,
    $policyApproval,
    $policyReviewRevisionHistory,
    $policyAcknowledgment
  ) {
    $db = new db();
    $conn = $db->connect();
    
    // Write your query to update the applicable entry in the database
    $query = "UPDATE policyfields SET PolicyTitle = ?, PolicyNumber = ?, PolicyDescription = ?, PolicyEffectiveDate = ?, PolicyReviewDate = ?, Applicability = ?, PolicyRequirements = ?, ComplianceResponsibility = ?, RelatedDocuments = ?, PolicyApproval = ?, PolicyReviewRevisionHistory = ?, PolicyAcknowledgment = ? WHERE id = ?";


    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssssssssi",$policyTitle,$policyNumber,$policyDescription,$policyEffectiveDate,$policyReviewDate,$applicability,$policyRequirements,$complianceResponsibility,$relatedDocuments,$policyApproval,$policyReviewRevisionHistory,$policyAcknowledgment,$id);

    if($stmt->execute()){
      $response= true;
    }else{
      $response = false;
    }
    
   $stmt->close();
   $db->disconnect($conn);
   return $response;
  }
  
  
  //delete applicable
  public function deleteApplicable($id) {
	
    $db=new db();
    $conn=$db->connect();
    
    $query="DELETE FROM policyfields WHERE id=".$id.";";
    
    if ($conn->multi_query($query)) {
        $response=true;
    } else {
        $response=false;	
    }
    
    $db->disconnect($conn);
    return $response;
    
}

//get apply
public function getApplicable($id) {
    $db = new db();
    $conn = $db->connect();

    $query = "SELECT * FROM policyfields WHERE id = " . $id;

    if ($result = $conn->query($query)) {
        $row = $result->fetch_assoc();
        $response = $row;
    } else {
        $response = false;
    }

    $db->disconnect($conn);
    return $response;
}





public function chronjobpolicyreviewdate(){

  $db = new db();
			$conn = $db->connect();
			$currentdate=date('Y-m-d');

			   $query = "SELECT policyfields.*,users.iduser,users.u_mail FROM policyfields INNER JOIN users ON policyfields.policy_user_id = users.iduser where policyfields.PolicyReviewDate='$currentdate'";

			  if ($result = $conn->query($query)){
				while ($row = $result->fetch_assoc()) {
					$usermail[] = $row['u_mail'];
					$response = true;
         
				}
        

			}else{

				$response = false;

			}

      if($response==1){
        $this->sendNotificationEmailuser($usermail);
      }else{
        header("Location: ../view/applicables.php?status=0");
      }

     
		}


		public function sendNotificationEmailuser($usermail) {

				$mail = new PHPMailer(true);
				// SMTP settings for Mailtrap
				$mail->isSMTP();
        $mail->Host = 'smtp-mail.outlook.com';
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        $mail->Username = 'jay@risksafe.co'; // Replace with your Mailtrap username
        $mail->Password = 'Welcome901#@!'; // Replace with your Mailtrap password
        $mail->SMTPSecure = 'tls';
        $subject='Policy review due date';
        // Email content
        $mail->setfrom('jay@risksafe.co', 'Risksafe Team');
				//$mailto:mail->addaddress('shwetachauhan035@gmail.com');
				//$mailto:mail->addaddress('shwetachauhan035@gmail.com');
				$mail->Subject = $subject;

				//implode(" ",$usermail);
	
				foreach ($usermail as $recipientEmail) {
					$mail->addAddress($recipientEmail);
				}
				$body='<!DOCTYPE html>
				<html>
				
				<head>
					<meta charset="UTF-8">
					<title>Policy Review date</title>
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
								<p>Dear User,</p>
								<p>Your Policy review date is stop by tommorrow .</p>
								
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
				
        header("Location: ../view/applicables.php?status=1");
				exit();  
				//return 1;
				} else {
				echo 'Mailer Error: ' . $mail->ErrorInfo;
				}
					
				
			}




		




}
?>
