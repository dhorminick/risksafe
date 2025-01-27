<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RiskSafe Authentication Help</title>

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
  <body class="flex flex-col gap-[0px]">
    <?php include 'layout/header.layout.php' ?>

    <div class="mt-[85px]"></div>

    <div class="px-[30px] sm:px-[100px] py-16 bg-[var(--light-blue)] _main">
        <h4 class="font-bold text-[30px]">Troubleshooting Email Authentication and Sign-In Errors</h4>
        <p>Encountering issues with email authentication or sign-in can be frustrating, but rest assured, 
        we're here to help! Below are some common errors you might encounter and steps to resolve them:</p>
        
        <div id='incorrect_data'>
            <h6>1. Incorrect Email or Password:</h6>
            <p>
                <ul>
                    <li>Double-check that you've entered your email address and password correctly. Remember that passwords are case-sensitive.</li>
                    <li>If you've forgotten your password, use the <a href='reset-password' class='bb'>Forgot Password</a> link to reset it. Follow the instructions sent to your email to create a new password.</li>
                </ul>
            </p>
        </div>
        
        <div id='email_not_recieved'>
            <h6>2. Email Not Received:</h6>
            <p>
                <ul>
                    <li>If you're not receiving the email verification or password reset email, check your spam or junk folder. Sometimes these emails can be filtered there.</li>
                    <li>Ensure that you've entered the correct email address during sign-up or password reset.</li>
                </ul>
            </p>
        </div>
        
        <div id='email_in_use'>
            <h6>3. Email Already in Use:</h6>
            <p>
                <ul>
                    <li>If you're receiving a message saying that your email is already in use, it's possible that you've already registered an account with that email. Try signing in instead of signing up.</li>
                    <li>If you're unable to remember your password, use the <a href='reset-password' class='bb'>Forgot Password</a> option to reset it.</li>
                </ul>
            </p>
        </div>
        
        <div id='account_blocked'>
            <h6>4. Account Deactivated or Suspended:</h6>
            <p>
                <ul>
                    <li>If your account has been deactivated or suspended, reach out to our <a href='mailto:support@risksafe.co' class='bb'>support team</a> for assistance. We'll investigate the issue and provide guidance on how to proceed.</li>
                </ul>
            </p>
        </div>
        
        <div id='failed_attemps'>
            <h6>5. Account Locked Due to Multiple Failed Attempts:</h6>
            <p>
                <ul>
                    <li>For security reasons, your account may be temporarily locked after multiple failed sign-in attempts. Wait for a few minutes and try signing in again.</li>
                    <li>If you continue to experience issues, use the <a href='reset-password' class='bb'>Forgot Password</a> option to reset your password and regain access to your account.</li>
                </ul>
            </p>
        </div>
        
        <div id='expired_link'>
            <h6>6. Verification Link Expired:</h6>
            <p>
                <ul>
                    <li>Verification links are typically time-sensitive. If the link has expired, try resending the verification email from the sign-in page.</li>
                    <li>If you're still unable to verify your email, contact our <a href='mailto:support@risksafe.co' class='bb'>support team</a> for assistance.</li>
                </ul>
            </p>
        </div>
        
        <h6>Still Need Help?</h6>
        
        <p>If you've tried the steps above and are still experiencing issues, don't hesitate to reach out to our support team at <a href='mailto:support@risksafe.co' class='bb'>support@risksafe.co</a>. 
        Be sure to provide as much detail as possible about the problem you're encountering, including any error messages you've received.</p>
        
        <p>We're committed to helping you resolve any email authentication or sign-in issues promptly so you can get back to enjoying our platform hassle-free!</p>
            
        
    </div>

    <!-- footer -->
    <?php include 'layout/footer.layout.php' ?>
    <style lang="scss">
        ._main {
            div{
                margin-bottom: 20px;
            }
            h6{
                font-weight: bolder;
                margin-bottom: 10px;
            }
        }
    </style>
  </body>
</html>