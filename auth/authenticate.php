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
    
    $authentication = false;
    $user_authenticated = false;
    $auth_complete = false;
    $invalid_params = false;
    $empty = false;

    $auth_response = array(
        'heading' => '',
        'text' => '',
        'link_url' => '',
        'link_text' => '',
        'showHelp' => false
    );
    
    if (isset($_GET['auth']) && isset($_GET['e'])) {
        $authentication = true;
        $empty = true;
        $auth = sanitizePlus($_GET['auth']);
        $e = sanitizePlus($_GET['e']);
        $CheckIfUserExist = "SELECT * FROM users WHERE md5(crc32(md5(crc32(md5(crc32(md5(u_mail))))))) = '$e' AND u_otp = '$auth' LIMIT 1";
        $UserExist = $con->query($CheckIfUserExist);
        if ($UserExist->num_rows > 0) {
            $row = $UserExist->fetch_assoc();
            $u_complete = $row['u_complete'];
            $u_id = $row['u_id'];
            $em = $row['u_mail'];
            if ($u_complete == '1') {
                $user_authenticated = true;

                $arrr = array(
                    'heading' => 'Error 402: Authentication Error',
                    'text' => 'User already authenticated, contact our support team @ <a class="bb" href=\'mailto:support@risksafe.co\'>support@risksafe.co</a> if you have any issue with our application!!',
                    'link_url' => '/',
                    'link_text' => '<i class=\'fas fa-arrow-left\'></i> Go Back Home', 
                    'showHelp' => false
                );
                $auth_response = $arrr;
            } else {
                $user_authenticated = false;

                $updateUser = "UPDATE users SET u_complete = 'true' WHERE u_mail = '$em' AND u_otp = '$auth' AND u_id = '$u_id' AND u_complete = 'false' LIMIT 1";
                $userUpdated = $con->query($updateUser);
                if($userUpdated){
                    $auth_complete = true;
                    $arrr = array(
                            'heading' => 'Authentication Successful',
                            'text' => '<div class="mb-[5px]"> Congratulations! Your Email Address is Verified, </div> <div>What\'s Next?</div> <ul> <li> <strong>Explore Your Account:</strong> Log in to your account to start exploring all the features and functionalities available to you. From personalizing your profile to engaging with our services, there\'s so much waiting for you. </li> <li> <strong>Stay Updated:</strong> Be the first to know about exciting updates, exclusive offers, and important announcements. Make sure to keep an eye on your inbox for our latest news and insights. </li> </ul>',
                            'link_url' => 'sign-in',
                            'link_text' => 'Sign In To Account <i class=\'fas fa-arrow-right\'></i>', 
                            'showHelp' => false,
                        );
                    $auth_response = $arrr;
                }else{
                    $auth_complete = false;
                    $arrr = array(
                        'heading' => 'Error 502: Authentication Error',
                        'text' => 'Unable to update user status to authenticated, contact our support @ <a class="bb" href=\'mailto:support@risksafe.co\'>support@risksafe.co</a> team for more information!!',
                        'link_url' => '/',
                        'link_text' => '<i class=\'fas fa-arrow-left\'></i> Go Back Home', 
                        'showHelp' => true,
                        'help_url' => 'help#auth',
                        'help_text' => 'Need Help <i class=\'fas fa-question\'></i>', 
                    );
                    $auth_response = $arrr;
                    
                }
            }
            
        }else{
            $invalid_params = true;
            $arrr = array(
                'heading' => 'Error 402: Invalid Parameters!!',
                'text' => 'An error occured during authentication processing!',
                'link_url' => '/',
                'link_text' => '<i class=\'fas fa-arrow-left\'></i> Go Back Home', 
                'showHelp' => true,
                'help_url' => 'help#auth',
                'help_text' => 'Need Help <i class=\'fas fa-question\'></i>', 
            );
            $auth_response = $arrr;
        }
    } else {
        $authentication = false;
        $arrr = array(
                'heading' => 'Error 402: Authentication Error!!',
                'text' => 'Authentication parameters error!!! Contact our support team at <a class="bb" href=\'mailto:support@risksafe.co\'>support@risksafe.co</a> if you have any issue with our application!!',
                'link_url' => '/',
                'link_text' => '<i class=\'fas fa-arrow-left\'></i> Go Back Home', 
                'showHelp' => false,
            );
        $auth_response = $arrr;
    }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Authenticate | <?php echo $siteEndTitle; ?></title>

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
        class="min-h-screen flex flex-col items-center pt-[50px] sm:pt-[100px] px-4 border border-red-800"
      >
        <div class="border sm:w-[80%] w-full border-gray-300 rounded-lg p-6 shadow-[0_2px_22px_-4px_rgba(93,96,127,0.2)] max-md:mx-auto">
          <div class="mb-8">
            <h3 class="text-gray-800 text-3xl font-extrabold">
              RiskSafe - Authentication
            </h3>
            <p class="text-gray-500 text-sm mt-4 leading-relaxed">
              Validate account!
            </p>
          </div>

          <div>
            <h2 class="text-[20px] font-bold mb-[20px]"><?php echo $auth_response['heading']; ?></h2>
            <div class="mb-[30px]"><?php echo $auth_response['text']; ?></div>
            <a href="<?php echo $auth_response['link_url']; ?>" class="btn btn-primary"><?php echo $auth_response['link_text']; ?></a>
            <?php if($auth_response['showHelp'] === true){ ?>
            <a href="<?php echo $auth_response['help_url']; ?>" class="btn btn-secondary mt-[20px]"><?php echo $auth_response['help_text']; ?></a>  
            <?php } ?>
          </div>
        </div>


      </div>
    </div>
  </body>
</html>
