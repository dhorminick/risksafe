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
    
    $loginCounter = false;
    $passwordCounter = false;
    $companyCounter = false;

    if (isset($_POST["sign-in"])) {
        
        if($signedIn === true){
            array_push($message, "User Already Signed In!!");
            header('refresh:2;url= /admin/');
        }else{
            $login = sanitizePlus($_POST["email"]);
            $password = sanitizePlus($_POST["password"]);
            $__c__ = sanitizePlus($_POST["__c__"]);
        
            if($__c__ == '' || $__c__ == null){
                #verify if params have values
                $loginCounter = errorExists($login, $loginCounter);
                $passwordCounter = errorExists($password, $passwordCounter);
        
                if ($loginCounter == true || $passwordCounter == true) {
                    array_push($message, "Error 402: Incomplete Parameters!!");
                } else {
                    $password = weirdlyEncode($password);
            
                    $CheckIfUserExist = "SELECT * FROM users WHERE u_mail = '$login' AND u_password = '$password' LIMIT 1";
                    $UserExist = $con->query($CheckIfUserExist);
                    if ($UserExist->num_rows > 0) {
                        $row = $UserExist->fetch_assoc();
                        $db_userid = $row['u_id'];
                        $db_databaseid = $row['iduser'];
                        $db_useremail = $row["u_mail"];
                        $db_usercomplete = $row["u_complete"];
                        $db_userphone = $row["u_phone"];
                        $db_userusername = $row["u_name"];
                        $db_userpaymentstatus = $row["payment_status"];
                        $db_userpaymentduration = $row["payment_duration"];
                        $company_mail = $row["u_mail"];
        
                        if ($db_usercomplete == 'true') {
                            
                            $_SESSION["loggedIn"] = true;
                            $_SESSION["userId"] = $db_userid;
                            $_SESSION["userDatabaseId"] = $db_databaseid;
                            $_SESSION["userMail"] = $db_useremail;
                            $_SESSION["userPaymentStatus"] = $db_userpaymentstatus;
                            $_SESSION["u_datetime"]= $row["u_datetime"];
                            $_SESSION["u_expire"]= $row["u_expire"];
                            $_SESSION["role"]= 'admin';
                            $_SESSION["u_name"]= $row["u_name"];
                            $_SESSION["company_id"]= $row["company_id"];
                            $_SESSION["company_name"]= $row["c_company"];
                            $_SESSION['admin_mail'] = $company_mail;
                            $_SESSION['risk_industry'] = $row['risk_industry'];
                
                            array_push($message, "Sign In Succesfully!!");
            
                            if (isset($_GET["r"])) {
                                $prevUrl = $_GET["r"];
                                header('refresh:2;url= /admin'.$prevUrl);
                            }else{
                                header('refresh:2;url= /admin/');
                            }
                            
                        } else {
                            array_push($message, 'Error 402: Account Not Authenticated, Log In To "'.$db_useremail.'" To Activate Your RiskSafe Account!!');
                        }
                    }else{
                        array_push($message, "Error 402: Invalid Credentials!!");
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
    <title>Sign In | <?php echo $siteEndTitle; ?></title>

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

        <div class="grid md:grid-cols-2 items-center gap-4 max-w-6xl w-full">
          <div
            class="border border-gray-300 rounded-lg p-6 max-w-md shadow-[0_2px_22px_-4px_rgba(93,96,127,0.2)] max-md:mx-auto"
          >
            <form class="space-y-4" method='post'>
              <input type="hidden" name="__c__">

              <div class="mb-8">
                <h3 class="text-gray-800 text-3xl font-extrabold">Sign in</h3>
                <p class="text-gray-500 text-sm mt-4 leading-relaxed">
                  Sign in to your account and explore a world of possibilities.
                  Your journey begins here.
                </p>
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
                    placeholder="Enter Email Address"
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
              <div>
                <label class="text-gray-800 text-sm mb-2 block">Password</label>
                <div class="relative flex items-center">
                  <input
                    name="password"
                    type="password"
                    required
                    class="w-full text-sm text-gray-800 border border-gray-300 px-4 py-3 rounded-lg outline-blue-600"
                    placeholder="Enter password"
                  />
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="#bbb"
                    stroke="#bbb"
                    class="w-[18px] h-[18px] absolute right-4 cursor-pointer"
                    viewBox="0 0 128 128"
                  >
                    <path
                      d="M64 104C22.127 104 1.367 67.496.504 65.943a4 4 0 0 1 0-3.887C1.367 60.504 22.127 24 64 24s62.633 36.504 63.496 38.057a4 4 0 0 1 0 3.887C126.633 67.496 105.873 104 64 104zM8.707 63.994C13.465 71.205 32.146 96 64 96c31.955 0 50.553-24.775 55.293-31.994C114.535 56.795 95.854 32 64 32 32.045 32 13.447 56.775 8.707 63.994zM64 88c-13.234 0-24-10.766-24-24s10.766-24 24-24 24 10.766 24 24-10.766 24-24 24zm0-40c-8.822 0-16 7.178-16 16s7.178 16 16 16 16-7.178 16-16-7.178-16-16-16z"
                      data-original="#000000"
                    ></path>
                  </svg>
                </div>
              </div>

              <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center">
                  <input
                    id="remember-me"
                    name="remember-me"
                    type="checkbox"
                    class="h-4 w-4 shrink-0 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                  />
                  <label
                    for="remember-me"
                    class="ml-3 block text-sm text-gray-800"
                  >
                    Remember me
                  </label>
                </div>

                <div class="text-sm">
                  <a
                    href="reset-password"
                    class="text-blue-600 hover:underline font-semibold"
                  >
                    Forgot your password?
                  </a>
                </div>
              </div>

              <div class="!mt-8">
                <button
                  name='sign-in'
                  class="w-full btn btn-primary"
                >
                  Log in
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

                    Sign in with google
                  </button>
                </div>
              </div>

              <p class="text-sm !mt-8 text-center text-gray-800">
                Don't have an account
                <a
                  href="sign-up"
                  class="text-blue-600 font-semibold hover:underline ml-1 whitespace-nowrap"
                  >Register here</a
                >
              </p>
            </form>
          </div>
          <div class="lg:h-[400px] md:h-[300px] max-md:mt-8">
            <img
              src="/assets/images/auth/login-image.webp"
              class="w-full h-full max-md:w-4/5 mx-auto block object-cover"
              alt="Sign In Image"
            />
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
