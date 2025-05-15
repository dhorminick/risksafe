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
    require $file_dir.'layout/mail.php';
    
    $nameChecker = false;
    $emailChecker = false;
	$passwordChecker = false;
	$companyChecker = false;

    if (isset($_POST['sign-up']) && $_POST['__c__'] == '') {
        if($signedIn == true){
            array_push($message, "User Already Signed In!!");
        }else{
            $email = sanitizePlus($_POST["email"]);
            $password = sanitizePlus($_POST["password"]);
            $name = sanitizePlus($_POST["fullname"]);
            $company = sanitizePlus($_POST["company"]);
            $u_id = secure_random_string(20);
            $datetime = date("Y-m-d H:i:s");
    		$expire = date("Y-m-d H:i:s", strtotime("+14 days"));
    
            #verify if params have values
            $nameChecker = errorExists($name, $nameChecker);
            $emailChecker = errorExists($email, $emailChecker);
            $passwordChecker = errorExists($password, $passwordChecker);
    		$companyChecker = errorExists($company, $companyChecker);
    
            if ($nameChecker == true || $emailChecker == true || $passwordChecker == true || $companyChecker == true) {
                array_push($message, "Error 402: Incomplete Parameters!!");
            } else {
                $password = weirdlyEncode($password);
                $company_details = array(
                    'company_name' => $company, 
                    'company_address' => '', 
                    'company_city' => '', 
                    'company_state' => '', 
                    'company_postcode' => '', 
                    'company_country' => '', 
                );
                $company_details = serialize($company_details);
                $user_details = array(
                    'fullname' => $name,
                    'phone' => '',
                    'location' => '', 
                );
                $user_details = serialize($user_details);
                $CheckIfUserExist = "SELECT * FROM users WHERE u_mail = '$email' LIMIT 1";
                $UserExist = $con->query($CheckIfUserExist);
                if ($UserExist->num_rows > 0) {
                    array_push($message, "Error 402: Email Already Exists!!");
                }else{
                    $otp = secure_random_string(20);
                    $company_id = secure_random_string(10);
                    $company_id = strtoupper($company_id);
                    $confirmation_link = $site__.'/auth/authenticate?e='.weirdlyEncode($email).'&auth='.$otp;
                    
                    #mail
                    $mailSubject = 'RiskSafe - Confirm Account One-Time Password (OTP)';
                    $mailRecipient = $email;
                    $mailSender = $signUpSender;
                    $mail = _reg($mailSender, $mailRecipient, $mailSubject, $confirmation_link, $name, $site__, $signUpHelp);
                    
                    // if ($mail['sent'] === 'true' && $mail['error'] === 'none') {
                    //     #add user
                    //     $createNewUser = "INSERT INTO users (`superuserid`, `u_mail`, `u_password`, `u_name`, `u_phone`, `u_location`, `c_company`, `c_address`, `c_city`, `c_state`, `c_postcode`, `c_country`, `u_complete`, `u_otp`, `u_datetime`, `u_expire`, `role`,`user_loginstatus`, `u_id`, `company_users`, `company_id`, `company_details`, `user_details`, `payment_status`, `payment_duration`)
                    //       VALUES (0, '$email', '$password', '$name', '', '', '$company', '', '', '', '', '', 'true', '$otp', '$datetime', '$expire', 'admin', 0, '$u_id', 'a:0:{}', '$company_id', '$company_details', '$user_details', 'free', 'trial')";
                    //     $userCreated = $con->query($createNewUser);
                    //     if ($userCreated) {
                    //         array_push($message, 'Account Details Registered Successfully, Login To "'.$email.'" To Authenticate The Account!! ');
                    //     }else{
                    //         array_push($message, "Error 502: Server Error!! Contact Our Support Team For More Info.");
                    //     }
                    // } else {
                    //     array_push($message, "Error 502: Error Sending OTP!! ".$mail['error']);
                    // }

                    $createNewUser = "INSERT INTO users (`superuserid`, `u_mail`, `u_password`, `u_name`, `u_phone`, `u_location`, `c_company`, `c_address`, `c_city`, `c_state`, `c_postcode`, `c_country`, `u_complete`, `u_otp`, `u_datetime`, `u_expire`, `role`,`user_loginstatus`, `u_id`, `company_users`, `company_id`, `company_details`, `user_details`, `payment_status`, `payment_duration`)
                          VALUES (0, '$email', '$password', '$name', '', '', '$company', '', '', '', '', '', 'true', '$otp', '$datetime', '$expire', 'admin', 0, '$u_id', 'a:0:{}', '$company_id', '$company_details', '$user_details', 'free', 'trial')";
                        $userCreated = $con->query($createNewUser);
                        if ($userCreated) {
                            array_push($message, 'Account Details Registered Successfully, Login To "'.$email.'" To Authenticate The Account!! ');
                        }else{
                            array_push($message, "Error 502: Server Error!! Contact Our Support Team For More Info.");
                        }
                    
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Create Account | <?php echo $siteEndTitle; ?></title>

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
        class="min-h-screen flex flex-col items-center justify-center py-6 px-4"
      >
        <div class='mb-[10px] w-full'><?php include $file_dir.'layout/new_alert.php'; ?></div>
        <div
          class="grid md:grid-cols-2 items-center gap-[0px] max-w-7xl w-full"
        >
          <div
            class="border border-gray-300 rounded-lg p-6 shadow-[0_2px_22px_-4px_rgba(93,96,127,0.2)] max-md:mx-auto"
          >
            <form class="space-y-4" method='post'>
              <input type="hidden" name="__c__">
              <div class="mb-8">
                <h3 class="text-gray-800 text-3xl font-extrabold">
                  Create Account
                </h3>
                <p class="text-gray-500 text-sm mt-4 leading-relaxed">
                  Sign up today and unlock tailored risk assessments that
                  empower you to safeguard your future.
                </p>
              </div>

              <div class="flex flex-col sm:flex-row justify-between gap-[20px]">
                <div class="w-full sm:w-[50%]">
                  <label class="text-gray-800 text-sm mb-2 block"
                    >Fullname:</label
                  >
                  <div class="relative flex items-center">
                    <input
                      name="fullname"
                      type="text"
                      required
                      class="w-full text-sm text-gray-800 border border-gray-300 px-4 py-3 rounded-lg outline-blue-600"
                      placeholder="Enter fullname..."
                    />
                  </div>
                </div>

                <div class="w-full sm:w-[50%]">
                  <label class="text-gray-800 text-sm mb-2 block"
                    >Company:</label
                  >
                  <div class="relative flex items-center">
                    <input
                      name="company"
                      type="text"
                      required
                      class="w-full text-sm text-gray-800 border border-gray-300 px-4 py-3 rounded-lg outline-blue-600"
                      placeholder="Enter Company..."
                    />
                  </div>
                </div>
              </div>

              <div>
                <label class="text-gray-800 text-sm mb-2 block"
                  >Email Address:</label
                >
                <div class="relative flex items-center">
                  <input
                    name="email"
                    type="email"
                    required
                    class="w-full text-sm text-gray-800 border border-gray-300 px-4 py-3 rounded-lg outline-blue-600"
                    placeholder="Enter email address..."
                  />
                </div>
              </div>

              <div class="flex flex-col sm:flex-row justify-between gap-[20px]">
                <div class="w-full sm:w-[50%]">
                  <label for="password" class="text-gray-800 text-sm mb-2 block"
                    >Password:</label
                  >
                  <div class="relative flex items-center">
                    <input
                      name="password"
                      id="password"
                      type="password"
                      required
                      class="w-full text-sm text-gray-800 border border-gray-300 px-4 py-3 rounded-lg outline-blue-600"
                      placeholder="Enter password"
                    />
                  </div>
                </div>

                <div class="w-full sm:w-[50%]">
                  <label
                    for="confirm-password"
                    class="text-gray-800 text-sm mb-2 block"
                    >Confirm Password:</label
                  >
                  <div class="relative flex items-center">
                    <input
                      name="confirm-password"
                      id="confirm-password"
                      type="password"
                      required
                      class="w-full text-sm text-gray-800 border border-gray-300 px-4 py-3 rounded-lg outline-blue-600"
                      placeholder="Re-enter password"
                    />
                  </div>
                </div>
              </div>

              <div class="!mt-8">
                <button
                  name='sign-up'
                  class="w-full btn btn-primary"
                >
                  Create Account
                </button>

                <div class="space-x-6 flex justify-center mt-6">
                  <button
                    type="button"
                    class="btn w-full btn-secondary"
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="32px"
                      class="inline hidden"
                      viewBox="0 0 512 512"
                    >
                      <path
                        fill="#fbbd00"
                        d="M120 256c0-25.367 6.989-49.13 19.131-69.477v-86.308H52.823C18.568 144.703 0 198.922 0 256s18.568 111.297 52.823 155.785h86.308v-86.308C126.989 305.13 120 281.367 120 256z"
                        data-original="#fbbd00"
                      />
                      <path
                        fill="#0f9d58"
                        d="m256 392-60 60 60 60c57.079 0 111.297-18.568 155.785-52.823v-86.216h-86.216C305.044 385.147 281.181 392 256 392z"
                        data-original="#0f9d58"
                      />
                      <path
                        fill="#31aa52"
                        d="m139.131 325.477-86.308 86.308a260.085 260.085 0 0 0 22.158 25.235C123.333 485.371 187.62 512 256 512V392c-49.624 0-93.117-26.72-116.869-66.523z"
                        data-original="#31aa52"
                      />
                      <path
                        fill="#3c79e6"
                        d="M512 256a258.24 258.24 0 0 0-4.192-46.377l-2.251-12.299H256v120h121.452a135.385 135.385 0 0 1-51.884 55.638l86.216 86.216a260.085 260.085 0 0 0 25.235-22.158C485.371 388.667 512 324.38 512 256z"
                        data-original="#3c79e6"
                      />
                      <path
                        fill="#cf2d48"
                        d="m352.167 159.833 10.606 10.606 84.853-84.852-10.606-10.606C388.668 26.629 324.381 0 256 0l-60 60 60 60c36.326 0 70.479 14.146 96.167 39.833z"
                        data-original="#cf2d48"
                      />
                      <path
                        fill="#eb4132"
                        d="M256 120V0C187.62 0 123.333 26.629 74.98 74.98a259.849 259.849 0 0 0-22.158 25.235l86.308 86.308C162.883 146.72 206.376 120 256 120z"
                        data-original="#eb4132"
                      />
                    </svg>

                    Sign up with google
                  </button>
                </div>
              </div>

              <p class="text-sm !mt-8 text-center text-gray-800">
                Already have an account?
                <a
                  href="sign-in"
                  class="text-blue-600 font-semibold hover:underline ml-1 whitespace-nowrap"
                  >Sign In</a
                >
              </p>
            </form>
          </div>
          <div class="lg:h-[400px] md:h-[300px] max-md:mt-8">
            <img
              src="/assets/images/auth/login-image.webp"
              class="w-full h-full max-md:w-4/5 mx-auto block object-cover"
              alt="Sign Up Image"
            />
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
