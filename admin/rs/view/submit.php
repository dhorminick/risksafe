<?php
    include_once('../model/db.php');
  
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require '../../vendor/autoload.php';
  //  sk_test_8yTMfGjWta7zVzyhB6S3N2ws
 // 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
    \Stripe\Stripe::setApiKey('sk_test_8yTMfGjWta7zVzyhB6S3N2ws'); // Set your Stripe secret key

    $token = $_POST['stripeToken']; // Retrieve the token from the form submission
    $customerName = $_POST['name'];

    try {
        // Create a customer with the provided token and customer name
        $customer = \Stripe\Customer::create([
            'source' => $token,
            'name' => $customerName
        ]);

        // Create a charge with the customer and amount
        $charge = \Stripe\Charge::create([
            'amount' => 4900, // Amount in cents ($49)
            'currency' => 'usd',
            'description' => 'Payment info',
            'customer' => $customer->id
        ]);
        
        $db=new db;
            $conn=$db->connect();
           $user = $_SESSION['userid'];
       
        $sql = "INSERT INTO payments (user_id,transaction_id,customer,customer_name, amount, currency,status,clear_history,admin_clearhistory) VALUES ('$user','$charge->balance_transaction','$charge->customer','$customer->name', '$charge->amount', '$charge->currency','$charge->status',0,0)";
        $query = mysqli_query($conn,$sql);
        $last_id = $conn->insert_id;
        if($query){

          

       //  $month=$charge->payment_method_details->card->exp_month;
         $year=$charge->payment_method_details->card->exp_year;
         $month=$charge->payment_method_details->card->exp_month;

           $sql = "INSERT INTO  transaction (payment_id,transaction_id,payment_method, expiry_month, expiry_year,status) VALUES ('$last_id','$charge->balance_transaction','$charge->payment_method', '$month', '$year','$charge->status')";
            $query = mysqli_query($conn,$sql);

            $datetime = date("Y-m-d H:i:s");
            $expire = date("Y-m-d H:i:s", strtotime("+30 days"));
            $update="UPDATE users SET u_datetime = '$datetime', u_expire = '$expire' WHERE iduser='$user'";
            $res = mysqli_query($conn,$update);
            invoicemailsenttouser($user,$last_id);
            header("Location: success.php?customer_name=" . urlencode($customerName));
            exit;
        }
        echo "Payment not inserted";
        exit;
    } catch (\Stripe\Exception\CardErrorException $e) {
        // Payment failed
        $error = $e->getMessage();
        echo "Payment failed: " . $error;
    }

    function invoicemailsenttouser($user,$last_id){
        $db=new db;
        $conn=$db->connect();
       $user = $_SESSION['userid'];
        $query = "SELECT * FROM users WHERE iduser='$user'";

        if ($result=$conn->query($query)) {
			if ($row=$result->fetch_assoc()) {
				$email=$row['u_mail'];
			}
		} else {
			return false;	
		}
		
		$db->disconnect($conn);
       

        $usermail=$email;
        $mail = new PHPMailer(true);
        // SMTP settings for Mailtrap
        $mail->isSMTP();
        $mail->Host = 'smtp-mail.outlook.com';
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        $mail->Username = 'jay@risksafe.co'; // Replace with your Mailtrap username
        $mail->Password = 'Welcome901#@!'; // Replace with your Mailtrap password
        $mail->SMTPSecure = 'tls';
        $subject='Invoice Mail';
        // Email content
        $mail->setfrom('jay@risksafe.co', 'Risksafe Team');
       
        $mail->Subject = $subject;
    
        //implode(" ",$usermail);
        $mail->addAddress($usermail);
    $date=date('Y-M-d');
    $invoicenum=rand(10,100);

        $body='<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Invoice</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 0;
                }
                .container {
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                    border: 1px solid #ccc;
                }
                .header {
                    text-align: center;
                    margin-bottom: 20px;
                }
                .invoice-details {
                    margin-bottom: 20px;
                }
                .table {
                    width: 100%;
                    border-collapse: collapse;
                }
                .table th, .table td {
                    padding: 10px;
                    border-bottom: 1px solid #ccc;
                }
                .total {
                    text-align: right;
                    margin-top: 20px;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Invoice</h1>
                </div>
                <div class="invoice-details">
                    <p><strong>Invoice Number:</strong> INV-'.$invoicenum.'</p>
                    <p><strong>Invoice Date:</strong>'.$date.' </p>
               
                </div>
                <table class="table">
                    <thead>
                        <tr>
                          
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                          
                            <td>1</td>
                            <td>$49.00</td>
                            <td>$49.00</td>
                        </tr>
                     
                    </tbody>
                </table>
                <div class="total">
                    <p><strong>Total Amount:</strong> $49.00</p>
                </div>
                <p>Thank you Risk Safe Team!</p>
            </div>
        </body>
        </html>'
    ;
        $mail->Body = $body;
        
        // Set the email body as HTML
        $mail->isHTML(true);
    
        if ($mail->send()) {
        //echo 'mailsent';
        return 1;
        }else {
        echo 'Mailer Error: ' . $mail->ErrorInfo;
        }
        
     
    
        }
    ?>
