<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Terms of Service</title>

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

    <div>
      <div class="px-[30px] sm:px-[100px] py-16">
          <div class="custom-p">                            
            <p><b>How does RiskSafe work?</b></p>
            <p>Great question! RiskSafe is a platform for tracking work and paying 1099 workers. Work can be entered directly through the app and user interface (UI) or via an integration with your company's other sources of work data.</p> 
            <p>Work Types are the large tiles across the top that make up your company's incentive structure or ways to get paid. These can be things like Hours, Activities, Expenses, or anything else that you might get paid for. They can be unit-based, time-based, mileage-based, and monetary-based.</p> 
            <p class="end-p">Any way you pay workers or get paid can be accommodated. Need custom Work Types? Contact us about it!</p> 
  
            <p class="start-p"><b>How do I signup and conduct a risk assessment?</b></p>
            <p class="end-p">Simply click on <a class="bb" href="/auth/sign-up.php">‘Try RiskSafe’</a>, and in your general information when prompted and select the correct risk assessment that’s relevant to you (e.g. business risk assessment, heath &amp; safety risk assessment, etc.)</p>
  
        </div>
      </div>
    </div>



    <!-- footer -->
    <?php include 'layout/footer.layout.php' ?>
  </body>
</html>
<style>
    .end-p{
        margin-bottom: 15px !important;
    }
    .start-p{
        margin-top: 20px !important;
    }
</style>