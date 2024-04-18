<?php
    session_start();
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    
        $file_dir = '../../../';
        // Include the configuration file 
        require_once $file_dir.'layout/stripe__config.php';
        require_once $file_dir.'layout/db.php';
        
        // Include the Stripe PHP library 
        require_once 'stripe-php/init.php'; 
        
        if(isset($_POST['pay']) && isset($_POST['e_id']) !== null){
        $e_id = $_POST['e_id'];
        $e_info = getPaymentInfo($con, $e_id);
        if($e_info !== 'error'){
            $p_title = $e_info['description'];
            $p__id = $e_info['pay_id'];
            $p_price = $e_info['price'];
            $p_price = $p_price * 100;
            
            $customer_email = $_SESSION['userMail'];
            $company = $_SESSION['company_id'];
            $t_id = secure_random_string_s(10).'-'.secure_random_string_s(10).'-'.secure_random_string_s(10).'-'.secure_random_string_s(10).'-'.secure_random_string_s(10).'-'.secure_random_string_s(10);
            $txn = createTxn($con, $p__id, $customer_email, $p_price, $t_id, $company);
            if($txn !== 'false'){
                $txn_id = $t_id;
                \Stripe\Stripe::setApiKey($stripe_secret_key);
                $checkout_session = \Stripe\Checkout\Session::create([
                    "customer_email" => $customer_email,
                    "success_url" => $site___."admin/account/pay/success?t=".$txn_id."&e=".$p__id,
                    "cancel_url" => $site___."admin/account/pay/cancel?e=".$p__id."&t=".$txn_id,
                    "mode" => "payment",
                    "line_items" => [
                        [
                            "quantity" => 1,
                            "price_data" => [
                                "currency" => "usd",
                                "unit_amount" => $p_price,
                                "product_data" => [
                                    "name" => $p_title
                                ]
                            ]
                        ]
                    ]
                ]);
            
                http_response_code(303);
                header("Location: " . $checkout_session->url);
            }else{
                header("Location: payment-error?e=e2b98f4b-631c-4e7e");
                exit();
            }
            
            
        }else{
            header("Location: payment-error?e=7a9f21e4-cdda-4a7d");
            exit();
        }
        
        
        }
    } else {
        $signedIn = false;
        header("Location: payment-error?e=a0c3358d-8aef-4f15");
        exit();
        
    } 


?>