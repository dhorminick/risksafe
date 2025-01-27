<?php
    session_start();
    
    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        $signedIn = false;
    }

    $message = [];
    
    $file_dir = '';
    
    include $file_dir.'layout/db.php';
    require $file_dir.'layout/variablesandfunctions.php'; 
    require $file_dir.'layout/mail.php';

    function _isEmpty($data){
      if(!$data || $data === null || $data === ''){
        return true;
      }

      return false;
    } 

    if (isset($_POST["book"])) {
      $name = sanitizePlus($_POST["name"]);
      $company = sanitizePlus($_POST["company"]);
      $country = sanitizePlus($_POST["country"]);
      $num = sanitizePlus($_POST["num"]);
      $email = sanitizePlus($_POST["email"]);
      $isregistered = sanitizePlus($_POST["isregistered"]);
      $_message = sanitizePlus($_POST["message"]);

      
      if(_isEmpty($email) || _isEmpty($company) || _isEmpty($name)){
        array_push($message, "Email address, name and company fields are required!");
      }else{
        $data = array(
          'name' => $name,
          'company' => $company,
          'country' => $country,
          'num' => $num,
          'email' => $email,
          'isregistered' => $isregistered,
          'message' => $_message,
          'url' => 'https://risksafe.co/'
        );
        $data = serialize($data);

        #send admin mail
        $mailSubject = 'RiskSafe - New Demo Booked';
        $mailRecipient = 'jay@risksafe.co';
        $mailSender = $email;
        $mail = _sendAdminMailForBookDemo($mailSender, $mailRecipient, $mailSubject, $data);
        
        if ($mail['sent'] === 'true' && $mail['error'] === 'none') {
          #create demo
          $createNewUser = "INSERT INTO demo (`email`, `data`, `status`) VALUES ('$email', '$data', 'pending')";
          $userCreated = $con->query($createNewUser);
          if ($userCreated) {
              array_push($message, 'Demo Request Submitted Successfully!');
          }else{
              array_push($message, "Error 502: Unable to register demo request!");
          }
        }else{
          array_push($message, "Error 502: Server Error!! Contact Our Support Team For More Info.");
        }
      }
    }
    
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Book a demo</title>

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
  <body class="flex flex-col gap-[30px]">
    <?php include 'layout/header.layout.php' ?>

    <div class="mt-[55px]"></div>

    <div>
      <div class="max-w-7xl max-lg:max-w-3xl mx-auto p-4">
          <div class='mb-[10px] w-full'><?php include $file_dir.'layout/new_alert.php'; ?></div>
        <div
          class="bg-white shadow-[0_2px_10px_-3px_rgba(6,81,237,0.3)] rounded p-8"
        >
          <h2 class="text-3xl text-gray-800 font-extrabold mb-[10px]">
            Request a demo
          </h2>
          <div class="text-[15px] mb-[20px]">
            Discover How RiskSafe Can Empower Your Company!
          </div>

          <div class="grid lg:grid-cols-2 items-start gap-12">
            <div class="flex flex-col gap-[20px]">
              <div>
                Book a personalized demo today and explore the features that
                make our platform a game-changer. Our experts will guide you
                through our intuitive tools, helping you understand how RiskSafe
                can mitigate risks and unlock new opportunities.
              </div>
              <div>
                <div class="font-bold text-[20px] mb-[10px]">
                  What to Expect in the Demo:
                </div>
                <div class="ml-[20px]">
                  <ul class="list-disc">
                    <li>A walkthrough of our platform’s key features</li>
                    <li>
                      Customized solutions tailored to your industry needs
                    </li>
                    <li>Live Q&A with our product experts</li>
                  </ul>
                </div>
              </div>
              <div>
                <div class="font-bold text-[20px] mb-[10px]">
                  Why choose RiskSafe:
                </div>
                <div class="ml-[20px]">
                  <ul class="list-disc">
                    <li>Predefined data sets for risk management made easy</li>
                    <li>Real-time analysis and proactive strategies</li>
                    <li>Trusted by thousands of users worldwide</li>
                  </ul>
                </div>
              </div>
              <div class="mt-[10px]">
                Fill out the form below, and one of our representatives will
                contact you to arrange a time that fits your schedule. Let’s
                start your journey to smarter, safer risk management!
              </div>
            </div>

            <form class="space-y-4" method="post">
              <div class="flex flex-col sm:flex-row justify-between gap-[20px]">
                <div class="w-full sm:w-[50%]">
                  <label class="text-gray-800 text-sm mb-2 block"
                    >Fullname:</label
                  >
                  <div class="flex items-center">
                    <input
                      name="name"
                      type="text"
                      required
                      class="w-full text-sm text-gray-800 border border-gray-300 px-4 py-3 rounded-lg outline-[var(--primary)]"
                      placeholder="Enter fullname..."
                    />
                  </div>
                </div>

                <div class="w-full sm:w-[50%]">
                  <label class="text-gray-800 text-sm mb-2 block"
                    >Company:</label
                  >
                  <div class="flex items-center">
                    <input
                      name="company"
                      type="text"
                      required
                      class="w-full text-sm text-gray-800 border border-gray-300 px-4 py-3 rounded-lg outline-[var(--primary)]"
                      placeholder="Enter Company..."
                    />
                  </div>
                </div>
              </div>

              <div>
                <label class="text-gray-800 text-sm mb-2 block"
                  >Email Address:</label
                >
                <div class="flex items-center">
                  <input
                    name="email"
                    type="email"
                    required
                    class="w-full text-sm text-gray-800 border border-gray-300 px-4 py-3 rounded-lg outline-[var(--primary)]"
                    placeholder="Enter email address..."
                  />
                </div>
              </div>

              <div class="flex flex-col sm:flex-row justify-between gap-[20px]">
                <div class="w-full sm:w-[50%]">
                  <label for="num" class="text-gray-800 text-sm mb-2 block"
                    >Number of Employees:</label
                  >
                  <div class="flex items-center">
                    <input
                      name="num"
                      id="num"
                      type="number"
                      min="1"
                      required
                      class="w-full text-sm text-gray-800 border border-gray-300 px-4 py-3 rounded-lg outline-[var(--primary)]"
                    />
                  </div>
                </div>

                <div class="w-full sm:w-[50%]">
                  <label
                    for="country"
                    class="text-gray-800 text-sm mb-2 block"
                    >Country:</label
                  >
                  <div class="flex items-center">
                    <input
                      name="country"
                      id="country"
                      type="text"
                      required
                      class="w-full text-sm text-gray-800 border border-gray-300 px-4 py-3 rounded-lg outline-[var(--primary)]"
                    />
                  </div>
                </div>
              </div>

              <div>
                <label for="registered" class="text-gray-800 text-sm mb-2 block"
                  >Is your firm registered:</label
                >
                <div class="flex items-center">
                  <select
                    id="registered"
                    name="isregistered"
                    required
                    class="w-full text-sm text-gray-800 border border-gray-300 px-4 py-3 rounded-lg outline-[var(--primary)]"
                  >
                    <option value="no" selected>No</option>
                    <option value="yes">Yes</option>
                  </select>
                </div>
              </div>

              <div>
                <label for="message"
                  >Please provide any additional information we should
                  know:</label
                >
                <textarea
                  rows="6"
                  name="message"
                  class="w-full text-gray-800 border border-gray-300 rounded px-6 text-sm pt-3 focus:bg-transparent focus:outline-[var(--primary)]"
                ></textarea>
              </div>

              <div class="!mt-8">
                <button
                  type="submit"
                  name="book"
                  class="w-full shadow-xl py-3 px-4 text-sm tracking-wide rounded-lg text-white bg-[var(--primary)] focus:outline-none"
                >
                  Book Demo
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <?php include 'layout/footer.layout.php' ?>
  </body>
  <style>
    label {
      font-size: 14px;
      margin-bottom: 5px;
    }
  </style>
</html>
