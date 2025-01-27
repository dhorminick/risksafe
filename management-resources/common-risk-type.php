<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Common Risk Types | Resources</title>

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
    <?php include '../layout/header.layout.php' ?>

    <div class="mt-[55px]"></div>

    <div
      class="flex flex-col px-[20px] py-[100px] sm:py-[100px] sm:px-[50px] mb-[20px] bg-gray-100"
    >
      <div class="text-[13px] text-gray-500">
        <a href="/" class="bb">Home</a> |
        <a href="/risksafe-resources" class="bb">Resources</a> |
        <span class="text-gray-500">Common Risk Types</span>
      </div>
      <h3 class="mb-[20px] font-bold text-4xl">RiskSafe Resources</h3>
      <div class="text-[14px] w-full sm:max-w-[600px]">
        <p>
          Free resources made by our experts at RiskSafe on Risk Assessments,
          Management, Treatments, and Maintenance for different possible
          outcomes and scenarios.
        </p>
      </div>
    </div>

    <div class="bg-white px-[20px] sm:mx-[30px] mx-0 mt-[-40px]">
      <div class="main-content py-8">
        <section class="section">
          <div class="container mx-auto">
            <div class="section-body">
              <!-- Card -->
              <div
                class="p-[20px] card flex flex-col sm:flex-row bg-white shadow-lg border border-gray-200 rounded-lg overflow-hidden hover:shadow-2xl transition-shadow duration-300 ease-in-out"
              >
                <!-- <div
                  class="h-56 sm:h-auto sm:w-1/2 bg-no-repeat bg-cover bg-center object-cover object-center"
                  style="
                    background-image: url('../assets/images/resources/Types-of-Risk-in-Insurance.webp');
                  "
                ></div> -->
                <img
                  src="../assets/images/resources/Types-of-Risk-in-Insurance.webp"
                  class="w-full sm:max-w-[500px] h-auto sm:h-[500px] object-cover hidden"
                />

                <!-- Card Content -->
                <div class="p-6">
                  <div class="card-header mb-4">
                    <h3
                      class="text-2xl sm:text-[30px] header-text text-primary"
                    >
                      Common Risk Types
                    </h3>
                  </div>
                  <div class="card-body text-gray-700 leading-relaxed">
                    <p class="mb-4">
                      The level and type of risk that you need to consider will
                      vary with the type of business you operate. However, there
                      are some common categories which you can use to guide your
                      thinking and the development of your risk management plan.
                    </p>
                    <p class="mb-4">
                      The following lists the official Basel II defined 7 risk
                      event types with examples for each category:
                    </p>
                    <ul class="list-disc pl-6 mb-4">
                      <li class="mb-2">
                        <strong>Internal Fraud</strong> - misappropriation of
                        assets, tax evasion, intentional mismarking of
                        positions, bribery
                      </li>
                      <li class="mb-2">
                        <strong>External Fraud</strong> - theft of information,
                        hacking damage, third-party theft and forgery
                      </li>
                      <li class="mb-2">
                        <strong
                          >Employment Practices and Workplace Safety</strong
                        >
                        - discrimination, workers compensation, employee health
                        and safety
                      </li>
                      <li class="mb-2">
                        <strong
                          >Clients, Products, and Business Practice</strong
                        >
                        - market manipulation, antitrust, improper trade,
                        product defects, fiduciary breaches, account churning
                      </li>
                      <li class="mb-2">
                        <strong>Damage to Physical Assets</strong> - natural
                        disasters, terrorism, vandalism
                      </li>
                      <li class="mb-2">
                        <strong
                          >Business Disruption and Systems Failures</strong
                        >
                        - utility disruptions, software failures, hardware
                        failures
                      </li>
                      <li>
                        <strong
                          >Execution, Delivery, and Process Management</strong
                        >
                        - data entry errors, accounting errors, failed mandatory
                        reporting, negligent loss of client assets
                      </li>
                    </ul>
                    <p class="mb-4">
                      Operational risk management is the oversight of loss
                      resulting from inadequate or failed internal processes;
                      systems; people; or external events.
                    </p>
                  </div>
                </div>
              </div>
              <!-- End of Card -->
            </div>
          </div>
        </section>
      </div>
    </div>

    <div class="sm:mx-[50px] mx-[20px] mt-[-40px] mb-10">
      <div class="flex flex-col sm:flex-row justify-between">
        <div
          class="flex-1 sm:mr-4 bg-white border border-gray-200 shadow-lg rounded-lg p-6 mb-4 sm:mb-0 hover:shadow-xl transition-shadow duration-300 ease-in-out"
        >
          <small class="text-sm font-semibold text-primary mb-2"
            ><i class="fa fa-caret-left"></i> Previous</small
          >
          <a href="conducting-risk-assestment" class="hover:underline">
            <p class="text-gray-700 mt-4 text-[17px]">
              Conducting a Risk Assessment
            </p>
          </a>
        </div>

        <div
          class="flex-1 sm:ml-4 bg-white border border-gray-200 shadow-lg rounded-lg p-6 hover:shadow-xl transition-shadow duration-300 ease-in-out text-right"
        >
          <small class="text-sm font-semibold text-primary mb-2"
            >Next <i class="fa fa-caret-right"></i
          ></small>
          <a href="control-self-assesment" class="hover:underline">
            <p class="text-gray-700 mt-4 text-[17px]">
              Control Self Assessment (CSA)
            </p>
          </a>
        </div>
      </div>
    </div>

    <div class="flex justify-center items-center">
      <div
        class="cta w-full flex flex-col rounded-[10px] gap-[20px] p-[50px] justify-center items-center sm:w-[80%]"
      >
        <h1 class="font-bold text-[30px] text-center">
          Ready to Take Control of Your Business Risks?
        </h1>
        <div class="sm:w-[500px] w-full text-center">
          Join countless businesses in managing their risks with expert support
          from RiskSafe. Get started today and protect your business for
          tomorrow.
        </div>
        <div class="mt-[10px]">
          <a
            href="/auth/sign-up"
            class="btn btn-secondary flex items-center justify-center"
            >Get Started Now <i class="fa fa-caret-right ml-[5px]"></i
          ></a>
        </div>
      </div>
    </div>

    <?php include '../layout/footer.layout.php' ?>
  </body>
</html>
