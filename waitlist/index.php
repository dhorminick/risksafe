<?php
  session_start();

  $message = [];
  
  $file_dir = '../';
  
  include $file_dir.'layout/db.php';
  require $file_dir.'layout/variablesandfunctions.php';
  
  if (isset($_POST["submit"])) {
      $email = sanitizePlus($_POST["email"]);
      if(!$_POST["null"] || $_POST["null"] !== '' || $_POST["null"] !== null){
        array_push($message, "Error!");
        return;
      }
      // confirm if exists
      $createNewUser = "SELECT * FROM waitlist WHERE email = '$email'";
      $userCreated = $con->query($createNewUser);
      if($userCreated->num_rows > 0){
        array_push($message, "Email address already registered for waitlist!");
      }else{
        // add
        $createNewUser = "INSERT INTO waitlist (`email`) VALUES ('$email')";
        $userCreated = $con->query($createNewUser);
            if ($userCreated) {
                array_push($message, 'Waitlist Registered Successfully!');
            }else{
                array_push($message, "Error 502: Unable to register email address!");
            }
      }
  }

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RiskSafe - Risk Management & Compliance</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
      integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
      rel="stylesheet"
    />
  </head>
  <body class="bg-gray-100 text-gray-900 flex flex-col min-h-[100dvh]">
    <section class="py-[20px] border shadow-md">
      <div class="flex justify-between items-center">
        <a href="/" class="font-semibold text-[140%]">
          RISK
          <span
            class="bg-[var(--primary)] px-1 !py-0 rounded-[5px] font-semibold text-[100%] text-white"
            >SAFE</span
          >
        </a>
        <div class="flex items-center gap-[20px]">
          <a class="bb h-max" href="what-we-offer">What we offer</a>
          <a class="btn" target="_blank" href="https://risksafe.co/auth/sign-up"
            >Try Demo</a
          >
        </div>
      </div>
    </section>
    <section class="flex-1">
      <div class='mb-[10px] w-full'><?php include $file_dir.'layout/new_alert.php'; ?></div>

      <div class="grid sm:grid-cols-2 grid-cols-1">
        <div class="flex items-center">
          <div>
            <div class="text-[400%] leading-[70px] font-extrabold">
              Manage Risks, Compliance<br />
              Stay Secure!
            </div>
            <div class="mt-[20px]">
              Join the waitlist for RiskSafe – the all-in-one <br />platform for
              risk management, compliance, and auditing.
            </div>
            <form
              class="mt-[50px] sm:max-w-[400px] gap-[10px] w-full"
              id="join"
              method="post"
            >
              <label class="text-[80%] text-gray-600">Email Address:</label>
              <div class="flex gap-[10px] w-full items-center">
                <input
                  placeholder="Join the waitlist..."
                  type="email"
                  required
                  name="email"
                  class="w-full mt-1 mb-3 p-2 border border-gray-300 outline-none rounded-[5px] focus:ring-indigo-500 focus:border-indigo-500"
                />
                <input type="hidden" name="null" />
                <button class="btn w-max mt-[-8px]" type="submit" name="submit">Submit</button>
              </div>
            </form>
          </div>
        </div>
        <div class="flex justify-center items-center">
          <img
            src="risksafe-img-removebg.png"
            class="draggable-none transition-opacity duration-500 w-full h-[500px] rounded-[50px] object-cover object-center"
          />
        </div>
      </div>
    </section>
    <section class="py-[30px] border">
      <div class="grid grid-cols-1 gap-[20px] sm:grid-cols-2">
        <div class="flex gap-[10px] items-center">
          <a>
            <i class="fa-brands fa-facebook text-[20px]"></i>
          </a>
          <a>
            <i class="fa-brands fa-linkedin text-[20px]"></i>
          </a>
        </div>
        <div class="flex justify-end">
          <p>&copy; 2025 RiskSafe. All Rights Reserved.</p>
        </div>
      </div>
    </section>
  </body>
</html>
<style>
  * {
    font-family: "Poppins";
    font-size: 15px;
  }
  :root {
    --primary: #1c1c84;
  }
  .bb {
    /* padding-bottom: 1px; */
    color: var(--primary);
    border-bottom: 1px solid var(--primary);
  }
  .btn {
    padding: 10px 15px;
    border-radius: 5px;
    background-color: var(--primary);
    color: white;
  }
  section:not(.full) {
    padding-left: 100px;
    padding-right: 100px;
  }

  @media (max-width: 769px) {
    section:not(.full) {
      padding-left: 30px;
      padding-right: 30px;
    }
  }
</style>
