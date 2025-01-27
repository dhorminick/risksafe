<?php
    session_start();
    
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        $signedIn = false;
    }
    $message = [];
    
    $file_dir = '../';
    
    include $file_dir.'layout/db.php';
    require $file_dir.'layout/variablesandfunctions.php';
    include $file_dir.'layout/mail.php';
    
    $toReset = false;
    $reseted = false;
    $nl_reseted = false; 
    $a_reseted = false;   
    
    if(isset($_GET['auth']) && isset($_GET['u']) && $_GET['auth'] !== '' && $_GET['u'] !== '' && $_GET['auth'] !== null && $_GET['u'] !== null) {
        $authe = true;
        $e = sanitizePlus($_GET['u']);
        $auth = sanitizePlus($_GET['auth']);

        #select all mails
        $CheckIfUserExist = "SELECT * FROM users WHERE md5(crc32(md5(crc32(md5(crc32(md5(u_mail))))))) = '$e' AND u_password = '$auth' AND reset_pass = 'true' LIMIT 1";
        $UserExist = $con->query($CheckIfUserExist);
        if ($UserExist->num_rows > 0) {
            $toReset = true;
            $row = $UserExist->fetch_assoc();
            $em = $row['u_mail'];
        }else{
            $toReset = false;
            #error, user doesn't exist
            array_push($message, "Error 402: Authentication Token Doesn't Exist!!");
        }
    }else{
        $authe = false;
    }
    
    if (isset($_POST['reset-password'])) {
        $n_p = sanitizePlus($_POST['n_pass']);
        $c_n_p = sanitizePlus($_POST['c_n_pass']);
        $__c__ = sanitizePlus($_POST["__c__"]);
        $__em__ = sanitizePlus($_POST[" __em__"]);
        
        if($__c__ == '' || $__c__ == null){      
            if($n_p == $c_n_p && $n_p !== ''){
                #select all mails
                $CheckIfUserExist = "SELECT * FROM users WHERE crc32(md5(u_mail)) = '$__em__' AND reset_pass = 'true' LIMIT 1";
                $UserExist = $con->query($CheckIfUserExist);
                if ($UserExist->num_rows > 0) {
                    $row = $UserExist->fetch_assoc();
                    $e_mail = $row['u_mail'];
                    $p_word = $row['u_password'];
                    $n_p = weirdlyEncode($n_p);
                    $updateUser = "UPDATE users SET u_password = '$n_p', reset_pass = 'false' WHERE u_mail = '$e_mail' AND u_password = '$p_word' AND reset_pass = 'true' LIMIT 1";
                    $userUpdated = $con->query($updateUser);
                    if($userUpdated){
                        session_unset();
                        session_destroy();
                        header('Location: sign-in');
                        exit();
                    }else{
                        array_push($message, "Error 502: Error Reseting Passwords!!");
                    }
                }
            }else{
                array_push($message, "Error 402: New Passwords Don't Match!!");
            }
        }
    }
    if (isset($_POST['reset_l'])) {
        $session_email = $_SESSION["userMail"];
        $o_p = sanitizePlus($_POST['o_pass']);
        $n_p = sanitizePlus($_POST['n_pass']);
        $c_n_p = sanitizePlus($_POST['c_n_pass']);
        
        $__c__ = sanitizePlus($_POST["__c__"]);
        
        $o_p = weirdlyEncode($o_p);
        
        if($__c__ == '' || $__c__ == null){
            if($n_p == $c_n_p && $n_p !== ''){
                #select all mails
                $CheckIfUserExist = "SELECT * FROM users WHERE u_password = '$o_p' AND u_mail = '$session_email' LIMIT 1";
                $UserExist = $con->query($CheckIfUserExist);
                if ($UserExist->num_rows > 0) {
                    $row = $UserExist->fetch_assoc();
                    $activated = $row['u_complete'];
        
                    if ($activated == 'true') {
                        $n_p = weirdlyEncode($n_p);
                        $updateUser = "UPDATE users SET u_password = '$n_p' WHERE u_password = '$o_p' AND u_mail = '$session_email' AND u_complete = 'true' AND reset_pass = 'true' LIMIT 1";
                        $userUpdated = $con->query($updateUser);
                        if($userUpdated){
                            session_unset();
                            session_destroy();
                            header('Location: sign-in');
                            exit();
                        }else{
                            array_push($message, "Error 502: Error Reseting Passwords!!");
                        }
                    } else {
                        array_push($message, "Account Error: Email Address Needs To Be Verified Before Password Reset!!");
                    }
                }else{
                    array_push($message, "Error 402: Password Incorrect!!");
                }
            }else{
                array_push($message, "Error 402: New Passwords Don't Match!!");
            }
        }
        
    }
    if (isset($_POST['reset_nl'])) {
        $email = sanitizePlus($_POST['email']);
        $__c__ = sanitizePlus($_POST["__c__"]);
        
        if($__c__ == '' || $__c__ == null){
            #select all mails
            $CheckIfUserExist = "SELECT * FROM users WHERE u_mail = '$email'";
            $UserExist = $con->query($CheckIfUserExist);
            if ($UserExist->num_rows > 0) {
                $row = $UserExist->fetch_assoc();
                $email = $row['u_mail'];
                $password = $row['u_password'];
                $name = $row['u_name'];
                
                $reset_link = $website__.'auth/reset-password?u='.weirdlyEncode($email).'&auth='.$password;
                $subject = 'RiskSafe - Reset Account Password';
                $recipient = $email;
                $sender = $resetPassSender;
                $site = $website__;
                $help = $resetPassHelp;
                
                $_sentmail = _resetPass($sender, $recipient, $subject, $reset_link, $name, $site, $help);
                if ($_sentmail['sent'] == 'true' && $_sentmail['error'] == 'none') {
                    $updateUser = "UPDATE users SET reset_pass = 'true' WHERE u_mail = '$email' LIMIT 1";
                    $userUpdated = $con->query($updateUser);
                    
                    if($userUpdated){
                        array_push($message, "Reset Link Sent To '".strtolower($email)."' Successfully!!");
                    }else{
                        array_push($message, "Error 502: Error!!");
                    }
                    
                }else{
                    #error, mail unsuccessful
                    array_push($message, "Error 502: Error Sending Reset Link - ".$_sentmail['error']);
                }
            }else{
                array_push($message, "Error 402: Email Does Not Exist!!");
            }
        }
        
    }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reset Password | <?php echo $siteEndTitle; ?></title>

    <link rel="stylesheet" href="/assets/css/_style.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
      integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel='shortcut icon' type='image/x-icon' href='/assets/favicon/favicon.ico' />
  </head>
  <body>
    <div>
      <div
        class="min-h-screen w-full flex flex-col items-center pt-[50px] sm:pt-[100px] px-4"
      >
        <div class='mb-[10px] w-full'><?php include $file_dir.'layout/new_alert.php'; ?></div>
          <div class='sm:w-[60%] w-full'>
            <div class="mb-8">
                <h3 class="text-gray-800 text-3xl font-extrabold">
                    RiskSafe - Password Reset
                </h3>
                <p class="text-gray-500 text-sm mt-4 leading-relaxed">
                    Reset account!
                </p>
            </div>
          </div>
        <?php if($authe == true){ #if get auth ?>
            <?php if($toReset == true){ ?>
                <form class="space-y-4 sm:w-[60%] w-full" method="post">
                    <input type="hidden" name="__c__">
                    <input type="hidden" name="__em__" value='<?php echo crc32(md5($em)); ?>'>

                    <div>
                        <label for="n" class="text-gray-800 text-sm mb-2 block">New Password:</label>
                        <div class="relative flex items-center">
                            <input
                                id="n"
                                name="n_pass"
                                type="password"
                                required
                                class="w-full pass_input text-sm text-gray-800 border border-gray-300 px-4 py-3 rounded-lg outline-blue-600"
                                placeholder="Enter new password..."
                            />
                        </div>
                    </div>

                    <div>
                        <label for="c" class="text-gray-800 text-sm mb-2 block">Confirm New Password:</label>
                        <div class="relative flex items-center">
                            <input
                                id="c"
                                name="c_n_pass"
                                type="password"
                                required
                                class="w-full pass_input text-sm text-gray-800 border border-gray-300 px-4 py-3 rounded-lg outline-blue-600"
                                placeholder="Confirm new password"
                            />
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center">
                            <input
                                id="show_pass"
                                type="checkbox"
                                class="h-4 w-4 shrink-0 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            />
                            <label
                                for="show_pass"
                                class="ml-3 block text-sm text-gray-800"
                            >
                                Show Passwords
                            </label>
                        </div>

                        <div class="text-sm"></div>
                    </div>

                    <div class="!mt-8">
                        <button
                        name='reset-password'
                        class="btn btn-primary w-full"
                        >
                        Reset Account Password
                        </button>
                    </div>
                </form>
            <?php }else{ ?>
                <div class="border sm:w-[80%] w-full border-gray-300 rounded-lg p-6 shadow-[0_2px_22px_-4px_rgba(93,96,127,0.2)] sm:w-[60%] w-full">
                    <div>
                        <h2 class="text-[20px] font-bold mb-[20px]">Error 402: Authentication Error!!</h2>
                        <div class="mb-[30px]">An error occured while verifying reset details!</div>
                        <a href="/" class="btn btn-primary w-full"><i class='fas fa-arrow-left'></i> Go Back Home</a>
                    </div>
                </div>
            <?php } ?>
        <?php }else{ ?>
            <?php if($signedIn == true){ #if signed in ?>
                <form class="space-y-4 sm:w-[60%] w-full" method="post">
                    <input type="hidden" name="__c__">

                    <div>
                        <label for='o' class="text-gray-800 text-sm mb-2 block">Old Password:</label>
                        <div class="relative flex items-center">
                            <input
                                id='o'
                                name="o_pass"
                                type="password"
                                required
                                class="w-full pass_input text-sm text-gray-800 border border-gray-300 px-4 py-3 rounded-lg outline-blue-600"
                                placeholder="Enter old password..."
                            />
                        </div>
                    </div>

                    <div class='flex sm:flex-row flex-col gap-4 items-center'>
                        <div>
                            <label for="n" class="text-gray-800 text-sm mb-2 block">New Password:</label>
                            <div class="relative flex items-center">
                                <input
                                    id="n"
                                    name="n_pass"
                                    type="password"
                                    required
                                    class="w-full pass_input text-sm text-gray-800 border border-gray-300 px-4 py-3 rounded-lg outline-blue-600"
                                    placeholder="Enter new password..."
                                />
                            </div>
                        </div>
    
                        <div>
                            <label for="c" class="text-gray-800 text-sm mb-2 block">Confirm New Password:</label>
                            <div class="relative flex items-center">
                                <input
                                    id="c"
                                    name="c_n_pass"
                                    type="password"
                                    required
                                    class="w-full pass_input text-sm text-gray-800 border border-gray-300 px-4 py-3 rounded-lg outline-blue-600"
                                    placeholder="Confirm new password"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center">
                            <input
                                id="show_pass"
                                type="checkbox"
                                class="h-4 w-4 shrink-0 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            />
                            <label
                                for="show_pass"
                                class="ml-3 block text-sm text-gray-800"
                            >
                                Show Passwords
                            </label>
                        </div>

                        <div class="text-sm"></div>
                    </div>

                    <div class="!mt-8">
                        <button
                        name='reset_l'
                        class="btn btn-primary w-full"
                        >
                        Reset Account Password
                        </button>
                    </div>
                </form>
            <?php }else{ ?>
                <form class="space-y-4 sm:w-[60%] w-full" method='post'>
                    <input type="hidden" name="__c__">

                    <div>
                        <label class="text-gray-800 text-sm mb-2 block">Registered Email Address:</label>
                        <div class="relative flex items-center">
                        <input
                            name="email"
                            type="email"
                            required
                            class="w-full text-sm text-gray-800 border border-gray-300 px-4 py-3 rounded-lg outline-blue-600"
                            placeholder="Enter email address..."
                        />
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="#bbb"
                            stroke="#bbb"
                            class="w-[18px] h-[18px] absolute right-4"
                            viewBox="0 0 24 24"
                        >
                            <circle
                            cx="10"
                            cy="7"
                            r="6"
                            data-original="#000000"
                            ></circle>
                            <path
                            d="M14 15H6a5 5 0 0 0-5 5 3 3 0 0 0 3 3h12a3 3 0 0 0 3-3 5 5 0 0 0-5-5zm8-4h-2.59l.3-.29a1 1 0 0 0-1.42-1.42l-2 2a1 1 0 0 0 0 1.42l2 2a1 1 0 0 0 1.42 0 1 1 0 0 0 0-1.42l-.3-.29H22a1 1 0 0 0 0-2z"
                            data-original="#000000"
                            ></path>
                        </svg>
                        </div>
                    </div>

                    <div class="!mt-8">
                        <button
                        name='reset_nl'
                        class="btn btn-primary w-full"
                        >
                        Send Reset Link
                        </button>
                    </div>
                </form>
            <?php } ?>
        <?php } ?>

      </div>
    </div>
  </body>
</html>
<script>
    $("#show_pass").change(function (e) {
        if( $(this).is(":checked") ){
            $(".pass_input").attr('type') = 'text';
        }else{
            $(".pass_input").attr('type') = 'password';
        }
    });
</script>