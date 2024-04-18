<?php
	$message = [];
	include 'layout/config.php';
	require('vendor/autoload.php');

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;

	include 'layout/variablesandfunctions.php';	
	include 'layout/db.php';	

	$message = [];
	$nameChecker = false;
    $emailChecker = false;
	$subjectChecker = false;
	$questionChecker = false;
	
	if (isset($_POST["contact"])) {

		$name = sanitizePlus($_POST["name"]);
		$email = sanitizePlus($_POST["email"]);
		$subject = sanitizePlus($_POST["subject"]);
		$question = sanitizePlus($_POST["question"]);
		$datetime = date("Y-m-d H:i:s");

		#verify if params have values
        $nameChecker = errorExists($name, $nameChecker);
        $emailChecker = errorExists($email, $emailChecker);
        $subjectChecker = errorExists($subject, $subjectChecker);
		$questionChecker = errorExists($question, $questionChecker);

		if ($nameChecker == true || $emailChecker == true || $subjectChecker == true || $questionChecker == true) {
            array_push($message, "Error 402: Incomplete Parameters!!");
        } else {
			$addContact = "INSERT INTO as_contact (`name`, `email`, `subject`, `question`,`read_status`,`date`)
				VALUES ('$name', '$email', '$subject', '$question','0','$datetime' )";
			$contactAdded = $con->query($addContact);
			if ($contactAdded) {
				$mail = new PHPMailer(false);
				$mail->isSMTP();
				$mail->Host = 'smtp.gmail.com';
				$mail->Port = 587;
				$mail->SMTPAuth = true;
				$mail->Username = 'jay@risksafe.co'; // Replace with your Mailtrap username
				$mail->Password = 'Welcome901#@!'; // Replace with your Mailtrap password
				//BBs35JSmbWjWfi+7/v+e03CcORG4h181ZvMVlpD+pDoo

				$mail->SMTPSecure = 'TLS';
				$mail->SMTPDebug = SMTP::DEBUG_OFF;
				$mail->Debugoutput = 'html';
				// Email content
				if ($name == null || $name == '') {
					$name = 'user';
				}

				$mail->setfrom($email, ucwords($name));
				$mail->addAddress('jay@risksafe.co');
				//$mailto:mail->addaddress('shwetachauhan035@gmail.com');
				$mail->Subject = 'Contact Mail';
				// Email body

				//$body=// Email message (HTML content)
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
									<p>Name: ' . $name . '</p>           
									<p>Email: ' . $email . '</p>           
									<p>Subject: ' . $subject . '</p>           
									<p>Question: ' . $question . '</p>           
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
					array_push($message, "Mail Sent Successfully!! Our Team Will Get Back To You As Soon As Possible.");
				} else {
					array_push($message, "Mail Error: ".$mail->ErrorInfo);
				}
			} else {
				array_push($message, "Error 502: Error Logging Form Details!!");
			}
		}
	}

	if (isset($_POST["mailUs"])){
		function getToArrayCustom($array){
			$getArray = [];

			$convertArray = explode("&", $array);
			for ($i=0; $i < count($convertArray); $i++) { 
				$keyValue = explode('=', $convertArray[$i]);
				$getArray[$keyValue [0]] = $keyValue [1];
			}

			return $getArray;
		}
		$getArray = getToArrayCustom($_POST["mailUs"]);
		$contactEmail = sanitizePlus($getArray['popup-email']);
		if ($getArray['popup-name'] || $getArray['popup-name'] != '' || $getArray['popup-name'] != null) {
			$contactName = sanitizePlus($getArray['popup-name']);
		} else {
			$contactName = 'RiskSafe User';
		}
		$contactMessage = sanitizePlus($getArray['popup-message']);
		$contactSubject = 'Pop Up - Contact Form';

		include_once 'layout/mail_custom.php';
		$sent = mailUserCustom($contactSubject, $contactMessage, $adminemailaddr, $contactEmail);

		if ($sent['sent'] == 'true') {
			echo '
				<script src="assets/bundles/izitoast/js/iziToast.min.js"></script>
				<script>
				$(".toastr-error-1").click(function () {
					iziToast.success({
						title: $(this).attr("title"),
						message: $(this).attr("message"),
						position: "topRight",
					});
				});
				setTimeout(function () {
					$(".res").removeClass("show");
					$(".res").html("");
				}, 2000);
				</script>
				<span class="toastr-error-1" title="Error 402:" message="Success: Mail Sent Successfully!!"></span>
				<script>$(".toastr-error-1").click();</script>
			';
		} else {
			echo '
				<script src="assets/bundles/izitoast/js/iziToast.min.js"></script>
				<script>
				$(".toastr-error-5").click(function () {
					iziToast.success({
						title: $(this).attr("title"),
						message: $(this).attr("message"),
						position: "topRight",
					});
				});
				setTimeout(function () {
					$(".res").removeClass("show");
					$(".res").html("");
				}, 2000);
				</script>
				<span class="toastr-error-5" title="Error 402:" message="Error: '.$sent['error'].'"></span>
				<script>$(".toastr-error-5").click();</script>
			';
		}
		
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Contact Us | <?php echo APP_TITLE; ?></title>
  <?php require 'layout/general_css.php' ?>
  <link rel="stylesheet" href="assets/css/index.custom.css">
  <link rel="stylesheet" href="assets/css/main.css">
</head>

<body>
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <?php require 'layout/header_main.php' ?>
        <!-- Main Content -->
        <div class="main-content">
            <div class="intro-header custom contact" style="display: none;">
                <div class="intro-message">
                    <h2>Contact Us</h2>
                    <div class="intro-breadcrumbs">
                        <a href="/" class="links">Home</a>
                        <a href="">About Us</a>
                    </div>
                </div>
            </div>
			<section class="header-section">
				<div class="">
					<div class="intro-breadcrumbs custom">
                        <a href="/" class="bb">Home</a>
                        <a href="">Contact</a>
                    </div>
					<h1 class="h">How can we help?</h1>
					<div class="header-text">
						<div>Have an issue, report or inquiry, send us a mail now and our team will get back to you as soon as possible.</div>
					</div>
				</div>
			</section>
            <section class="section" style="margin-top: 25px !important;">
            <div class="section-body card">
                <!-- <div class="card-header" style="margin-bottom:40px;"> 
					<div style="width: 100%;">
						<h2 class="section-text-heading">How can we help?</h2> 
						<p style="width: 100%;text-align:center;margin:-15px 0px 0px 0px;font-weight:400;">Have an issue, report or inquiry, send us a mail now and our team will get back to you as soon as possible.</p>
					</div>
                </div> -->
                <div class="card-body"> 
                    <div class="row pt-30">
						<?php include 'layout/alert.php'; ?>
						<div class="col-lg-8 col-12">
							<form class="form" method="post">
								<div class='row section-row'>
									<div class="form-group col-lg-6 col-12 pp0">
										<label>Full Name:</label>
										<input class="form-control" type="text" name="name"  required/>
									</div>
									<div class="form-group col-lg-6 col-12 n-">
										<label>Work Email:</label>
										<input class="form-control" type="email" name="email" required/>
									</div>
								</div>
								<div class="form-group">
									<label>Subject:</label>
									<input class="form-control" type="text" name="subject" required />
								</div>
								<div class="form-group">
									<label>Questions ? Feedback ? Trouble ? Let us know as much detail as you can.</label>
									<textarea class="form-control" name="question" required style="min-height: 100px;resize:none;"></textarea>
								</div>
								<div class="form-group">
									<button type="submit" class="btn btn-primary btn-icon icon-left" name="contact"><i class="far fa-paper-plane"></i> Send Message</button>
								</div>
							</form>
						</div>
						
						<div class="col-lg-4">
							<h3 class="page-header">Contact Us</h3>
							<hr>
							<label>Email :</label>
							<p><a class="bb" href="mailto:contact@risksafe.com">contact@risksafe.com</a></p>
							
							<!--<label>Phone :</label>-->
							<!--<p><a class="bb" href="tel:+61390051277">+613 9005 1277</a></p>-->

							<label>Address :</label>
							<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua</p>
						</div>
					</div>
                </div>
                <div class="card-footer"> 
                </div>                
            </div>
            </section>
        </div>
        <?php require 'layout/footer.php' ?>
        </footer>
        </div>
    </div>
    <?php require 'layout/general_js.php' ?>
</body>
<style>
    .section-body{
        padding: 20px;
    }
    .section-ul{
        margin-left: 0px !important;
        padding-left: 20px !important;
        color: inherit !important;
    }
    .section-text-heading{
        width:100%;
        text-align: center;
    }
    p.section-p.-p{
        margin-bottom: 25px !important;
    }
    p.section-p.-p strong{
        font-weight: bold !important;
    }
    .main-footer{
        margin-top: -25px;
    }
</style>
</html>
