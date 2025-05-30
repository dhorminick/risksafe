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
    
    if (isset($_POST["sign-in"]) && isset($_POST["auth"]) == 'user') {
        
        $found = false;
        $auth = sanitizePlus($_POST["auth"]);
        $login = sanitizePlus($_POST["email"]);
        $password = sanitizePlus($_POST["password"]);
        $company = sanitizePlus($_POST["company"]);
        $__c__ = sanitizePlus($_POST["__c__"]);
    
        if($__c__ == '' || $__c__ == null){
            #verify if params have values
            $loginCounter = errorExists($login, $loginCounter);
            $passwordCounter = errorExists($password, $passwordCounter);
            $companyCounter = errorExists($company, $companyCounter);
    
            if ($loginCounter == true || $passwordCounter == true || $companyCounter == true) {
                array_push($message, "Error 402: Incomplete Parameters!!");
            } else {
                    $password = weirdlyEncode($password);
                    $company = strtoupper($company);
                    $CheckIfCompanyExist = "SELECT * FROM users WHERE company_id = '$company' LIMIT 1";
                    $CompanyExist = $con->query($CheckIfCompanyExist);
                    if ($CompanyExist->num_rows > 0) {
                        $row = $CompanyExist->fetch_assoc();
                        
                        $db_useremail = $row["u_mail"];
                        $db_databaseid = $row['iduser'];
                        $db_usercomplete = $row["u_complete"];
                        $db_userphone = $row["u_phone"];
                        $db_userusername = $row["u_name"];
                        $db_userpaymentstatus = $row["payment_status"];
                        $db_userpaymentduration = $row["payment_duration"];
                        $db_users = $row['company_users'];
                        $company_mail = $row['u_mail'];
        
                        $db_users = unserialize($db_users);
                        $db_count = count($db_users);
        
                        
        
                        $isInArray = in_array_custom($login, $db_users) ? 'found' : 'notfound';
                        if($isInArray === 'found'){
                            for ($rowArray = 0; $rowArray < $db_count; $rowArray++) {
                                // echo 'row - '.$row.', email = '.$db_users[$row]['email'].', password = '.$db_users[$row]['password'];
                                if($db_users[$rowArray]['email'] == $login && $db_users[$rowArray]['password'] == $password){
                                    $found = true;
                                    $rowNumber = $rowArray;
                                }
                            }
        
                            if($found && $found == true){
                                
                                $_SESSION["loggedIn"] = true;
                                $_SESSION["userId"] = $db_users[$rowNumber]['id'];
                                $_SESSION["userDatabaseId"] = $db_databaseid;
                                $_SESSION["userMail"] = $db_users[$rowNumber]['email'];
                                $_SESSION["u_name"] = $db_users[$rowNumber]['fullname'];
                                $_SESSION["userPaymentStatus"] = $db_userpaymentstatus;
                                $_SESSION["u_datetime"]= $row["u_datetime"];
                                $_SESSION["u_expire"]= $row["u_expire"];
                                $_SESSION["role"]= 'user';
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
                                
                            }else{
                                array_push($message, "Error 402: Email And Password Does Not Match!!");
                            }          
                        }else{
                            array_push($message, "Error 402: Account Does Not Exist!!");
                        }
        
                    }else{
                        array_push($message, "Error 402: Invalid Credentials - Company ID!!");
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
    <title>Sign in as a user | <?php echo $siteEndTitle; ?></title>

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
        class="min-h-screen flex fle-col items-center justify-center py-6 px-4"
      >
        <div class='mb-[10px] w-full'><?php include $file_dir.'layout/new_alert.php'; ?></div>

        <div class="grid md:grid-cols-2 items-center gap-4 max-w-6xl w-full">
          <div
            class="border border-gray-300 rounded-lg p-6 max-w-md shadow-[0_2px_22px_-4px_rgba(93,96,127,0.2)] max-md:mx-auto"
          >
            <form class="space-y-4" method='post'>
              <input type="hidden" name="auth" value="user">
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
                  >Company ID:</label
                >
                <div class="relative flex items-center">
                  <input
                    name="company"
                    type="text"
                    required
                    class="w-full text-sm text-gray-800 border border-gray-300 px-4 py-3 rounded-lg outline-blue-600"
                    placeholder="Enter Company ID"
                  />
                  
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

              </div>

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
