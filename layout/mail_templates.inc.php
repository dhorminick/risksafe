<?php

function confirm_mail_tenplate($name, $otp, $web_link, $help){
$mail = '
<!DOCTYPE html>
<html style="font-family: Verdana, Geneva, sans-serif !important;display:flex;justify-content:center;align-items:center;padding:20px;color:black !important;">
  <head>
  </head>
  <body class="'.uniqid().'" style="font-family: Verdana, Geneva, sans-serif !important;width:100%;color:black !important;">
    <div
      class="main-container"
      style="
        max-width: 600px;
        border-radius: 10px;
        border-top: 10px solid #6777ef;
        border-left: 1px solid #6777ef;
        border-right: 1px solid #6777ef;
        border-bottom: 10px solid #6777ef;
        font-family: Verdana, Geneva, sans-serif !important;
        color:black !important;
      "
    >
      <div class="logo" style="width: 100%; text-align: center;">
        <a href="'.$web_link.'"
          ><img src="'.$web_link.'/assets/images/logo-edit.jpg?p='.uniqid().'" alt="RiskSafe" title="logo" width="auto" height="auto" style="margin: 10px 0px; height: 60px; text-align: center"
        /></a>
      </div>
      <div
        class="body"
        style="padding: 20px 15px 10px 15px; margin: 0px 0px 10px 0px"
      >
        <div class="body_body" style="font-size: 14px !important">
          <div style="margin-bottom: 20px">Hi '.ucwords($name).',</div>
          <div class="'.uniqid().'" style="margin-bottom: 5px">
            Welcome to RiskSafe! You are just one step away from unlocking
            all the amazing features we have in store for you.
          </div>
          <div style="margin-bottom: 5px">
            To ensure a seamless experience, we kindly ask you to confirm your
            email address. This quick step is crucial for enhancing your account
            security and enabling full access to our services.
          </div>
          <div class="'.uniqid().'">Please click the link below to confirm your email:</div>
          <div
            style="
              margin: 10px 0px;
              padding: 20px 0px;
              width: 100%;
              text-align: center;
            "
          >
            <a
              href="'.strtolower($otp).'"
              style="
                border-radius: 4px;
                padding: 10px 20px;
                font-size: 15px !important;
                background-color: #6777ef;
                color: white;
                cursor: pointer;
                text-decoration: none !important;
              "
              >Confirm Account</a
            >
          </div>
          <div style="margin-bottom: 5px">
            If you did not sign up for RiskSafe, you can safely ignore
            this email, and no further action will be required.
          </div>
          <div class="'.uniqid().'" style="margin-bottom: 5px">
            Should you have any questions or need assistance, feel free to reach
            out to our support team at
            <a href="mailto:'.$help.'"
              >'.$help.'</a
            >
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
';

return $mail;
}

function reset_password_tenplate($name, $otp, $site, $help){
$mail = '
<!DOCTYPE html>
<html style="font-family: Verdana, Geneva, sans-serif !important;display:flex;justify-content:center;align-items:center;padding:20px;color:black !important;">
  <head>
  </head>
  <body class="'.uniqid().'" style="font-family: Verdana, Geneva, sans-serif !important;width:100%;color:black !important;">
    <div
      class="main-container"
      style="
        max-width: 600px;
        border-radius: 10px;
        border-top: 10px solid #6777ef;
        border-left: 1px solid #6777ef;
        border-right: 1px solid #6777ef;
        border-bottom: 10px solid #6777ef;
        font-family: Verdana, Geneva, sans-serif !important;
        color:black !important;
      "
    >
      <div class="logo" style="width: 100%; text-align: center;">
        <a href="'.$site.'"
          ><img src="'.$site.'assets/images/logo-edit.jpg?p='.uniqid().'" alt="RiskSafe" title="logo" width="auto" height="auto" style="margin: 10px 0px; height: 60px; text-align: center"
        /></a>
      </div>
      <div
        class="body"
        style="padding: 20px 15px 10px 15px; margin: 0px 0px 10px 0px"
      >
        <div class="body_body" style="font-size: 14px !important">
          <div style="margin-bottom: 20px">Hi '.ucwords($name).',</div>
          <div class="'.uniqid().'" style="margin-bottom: 5px">
            Someone (hopefully you!) requested a password reset for your account. Click the link below to choose a new password.
          </div>
          <div class="'.uniqid().'">Please click the link below to confirm your email:</div>
          <div
            style="
              margin: 10px 0px;
              padding: 20px 0px;
              width: 100%;
              text-align: center;
            "
          >
            <a
              href="'.strtolower($otp).'"
              style="
                border-radius: 4px;
                padding: 10px 20px;
                font-size: 15px !important;
                background-color: #6777ef;
                color: white;
                cursor: pointer;
                text-decoration: none !important;
              "
              >Reset Account Password</a
            >
          </div>
          <div class="'.uniqid().'" style="margin-bottom: 5px">
            If you did not send a request to reset your RiskSafe account password, contact our support admin immediately @ <a href="mailto:'.$help.'"
              >'.$help.'</a
            >
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
';

return $mail;
}

function _confirm_mail_tenplate($name, $otp, $web_link, $help){
$mail = '
<!DOCTYPE html>
<html style="font-family: Verdana, Geneva, sans-serif !important;display:flex;justify-content:center;align-items:center;padding:20px;color:black !important;">
  <head>
  </head>
  <body class="'.uniqid().'" style="font-family: Verdana, Geneva, sans-serif !important;width:100%;color:black !important;">
    <div
      class="main-container"
      style="
        max-width: 600px;
        border-radius: 10px;
        border-top: 10px solid #6777ef;
        border-left: 1px solid #6777ef;
        border-right: 1px solid #6777ef;
        border-bottom: 10px solid #6777ef;
        font-family: Verdana, Geneva, sans-serif !important;
        color:black !important;
      "
    >
      <div class="logo" style="width: 100%; text-align: center;">
        <a href="'.$web_link.'"
          ><img src="'.$web_link.'/assets/images/logo-edit.jpg?p='.uniqid().'" alt="RiskSafe" title="logo" width="auto" height="auto" style="margin: 10px 0px; height: 60px; text-align: center"
        /></a>
      </div>
      <div
        class="body"
        style="padding: 20px 15px 10px 15px; margin: 0px 0px 10px 0px"
      >
        <div class="body_body" style="font-size: 14px !important">
          <div style="margin-bottom: 20px">Hi '.ucwords($name).',</div>
          <div class="'.uniqid().'" style="margin-bottom: 5px">
            Welcome to RiskSafe! You are just one step away from unlocking
            all the amazing features we have in store for you.
          </div>
          <div style="margin-bottom: 5px">
            To ensure a seamless experience, we kindly ask you to confirm your
            email address. This quick step is crucial for enhancing your account
            security and enabling full access to our services.
          </div>
          <div class="'.uniqid().'">Please click the link below to confirm your email:</div>
          <div
            style="
              margin: 10px 0px;
              padding: 20px 0px;
              width: 100%;
              text-align: center;
            "
          >
            <a
              href="'.strtolower($otp).'"
              style="
                border-radius: 4px;
                padding: 10px 20px;
                font-size: 15px !important;
                background-color: #6777ef;
                color: white;
                cursor: pointer;
                text-decoration: none !important;
              "
              >Confirm Account</a
            >
          </div>
          <div style="margin-bottom: 5px">
            If you did not sign up for RiskSafe, you can safely ignore
            this email, and no further action will be required.
          </div>
          <div class="'.uniqid().'" style="margin-bottom: 5px">
            Should you have any questions or need assistance, feel free to reach
            out to our support team at
            <a href="mailto:'.$help.'"
              >'.$help.'</a
            >.
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
';

return $mail;
}
?>