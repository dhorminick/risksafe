<?php

function confirm_mail_tenplate($name, $otp, $page_fb, $page_ig, $page_yt, $page_x, $siteMainLocation, $page_wt, $web_link, $help){
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
      <div
        style="
          width: 100%;
          text-align: center;
          background-color: #f5f5f5;
          border-radius: 0px 0px 10px 10px;
          padding: 30px 0px;
          font-size: 13px;
        "
      >
        <div class="'.uniqid().'">Connect With Us:</div>
        <div
          style="
            
            margin: 10px 0px 20px 0px;
            width:100% !important;
            text-align:center !important;
          "
        >
          <a
            href="'.$page_fb.'"
            target="_blank"
            style="
              margin-right: 5px !important;
              background-color: #f5f5f5;
              float: none;
              display: inline-table;
              width: 38px;
            "
            rel="noopener noreferrer"
          >
            <img
              src="'.$web_link.'/assets/images/mail/facebook.png?p='.uniqid().'"
              style="border-radius: 3px; display: block"
              alt="social"
              title="social"
              height="20"
              width="20"
            />
          </a>

          <a
            href="'.$page_wt.'"
            target="_blank"
            style="
              margin-right: 5px !important;
              background-color: #f5f5f5;
              float: none;
              display: inline-table;
              width: 38px;
            "
            rel="noopener noreferrer"
          >
            <img
              src="'.$web_link.'/assets/images/mail/whatsapp.png?p='.uniqid().'"
              style="border-radius: 3px; display: block"
              alt="social"
              title="social"
              height="20"
              width="20"
            />
          </a>

          <a
            href="'.$page_ig.'"
            target="_blank"
            style="
              margin-right: 5px !important;
              background-color: #f5f5f5;
              float: none;
              display: inline-table;
              width: 38px;
            "
            rel="noopener noreferrer"
          >
            <img
              src="'.$web_link.'/assets/images/mail/instagram.png?p='.uniqid().'"
              style="border-radius: 3px; display: block"
              alt="social"
              title="social"
              height="20"
              width="20"
            />
          </a>

          <a
            href="'.$page_yt.'"
            target="_blank"
            style="
              background-color: #f5f5f5;
              float: none;
              display: inline-table;
              width: 38px;
            "
            rel="noopener noreferrer"
          >
            <img
              src="'.$web_link.'/assets/images/mail/youtube.png?p='.uniqid().'"
              style="border-radius: 3px; display: block"
              alt="social"
              title="social"
              height="20"
              width="20"
            />
          </a>

          <a
            href="'.$page_x.'"
            target="_blank"
            style="
              background-color: #f5f5f5;
              float: none;
              display: inline-table;
              width: 38px;
            "
            rel="noopener noreferrer"
          >
            <img
              src="'.$web_link.'/assets/images/mail/twitter.png?p='.uniqid().'"
              style="border-radius: 3px; display: block"
              alt="social"
              title="social"
              height="20"
              width="20"
            />
          </a>
        </div>
        <div>'.$siteMainLocation.'</div>
        <div style="display:none !important;">'.uniqid().'</div>
      </div>
    </div>
  </body>
</html>
';

return $mail;
}

function reset_password_tenplate($name, $otp, $page_fb, $page_ig, $page_yt, $page_x, $siteMainLocation, $page_wt, $site, $help){
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
      <div
        style="
          width: 100%;
          text-align: center;
          background-color: #f5f5f5;
          border-radius: 0px 0px 10px 10px;
          padding: 30px 0px;
          font-size: 13px;
        "
      >
        <div class="'.uniqid().'">Connect With Us:</div>
        <div
          style="
            
            margin: 10px 0px 20px 0px;
            width:100% !important;
            text-align:center !important;
          "
        >
          <a
            href="'.$page_fb.'"
            target="_blank"
            style="
              margin-right: 5px !important;
              background-color: #f5f5f5;
              float: none;
              display: inline-table;
              width: 38px;
            "
            rel="noopener noreferrer"
          >
            <img
              src="'.$site.'assets/images/mail/facebook.png?p='.uniqid().'"
              style="border-radius: 3px; display: block"
              alt="social"
              title="social"
              height="20"
              width="20"
            />
          </a>

          <a
            href="'.$page_wt.'"
            target="_blank"
            style="
              margin-right: 5px !important;
              background-color: #f5f5f5;
              float: none;
              display: inline-table;
              width: 38px;
            "
            rel="noopener noreferrer"
          >
            <img
              src="'.$site.'assets/images/mail/whatsapp.png?p='.uniqid().'"
              style="border-radius: 3px; display: block"
              alt="social"
              title="social"
              height="20"
              width="20"
            />
          </a>

          <a
            href="'.$page_ig.'"
            target="_blank"
            style="
              margin-right: 5px !important;
              background-color: #f5f5f5;
              float: none;
              display: inline-table;
              width: 38px;
            "
            rel="noopener noreferrer"
          >
            <img
              src="'.$site.'assets/images/mail/instagram.png?p='.uniqid().'"
              style="border-radius: 3px; display: block"
              alt="social"
              title="social"
              height="20"
              width="20"
            />
          </a>

          <a
            href="'.$page_yt.'"
            target="_blank"
            style="
              background-color: #f5f5f5;
              float: none;
              display: inline-table;
              width: 38px;
            "
            rel="noopener noreferrer"
          >
            <img
              src="'.$site.'assets/images/mail/youtube.png?p='.uniqid().'"
              style="border-radius: 3px; display: block"
              alt="social"
              title="social"
              height="20"
              width="20"
            />
          </a>

          <a
            href="'.$page_x.'"
            target="_blank"
            style="
              background-color: #f5f5f5;
              float: none;
              display: inline-table;
              width: 38px;
            "
            rel="noopener noreferrer"
          >
            <img
              src="'.$site.'assets/images/mail/twitter.png?p='.uniqid().'"
              style="border-radius: 3px; display: block"
              alt="social"
              title="social"
              height="20"
              width="20"
            />
          </a>
        </div>
        <div>'.$siteMainLocation.'</div>
        <div style="display:none !important;">'.uniqid().'</div>
      </div>
    </div>
  </body>
</html>
';

return $mail;
}
?>