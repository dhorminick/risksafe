<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RiskSafe Resources</title>

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
      <div class="">
        <div class="main-content min-h-screen">
          <!-- Intro Header Section -->
          <div class="intro-header hidden bg-gray-200 py-8">
            <div class="container mx-auto text-center">
              <h2 class="text-4xl font-semibold text-gray-800">
                Our Resources
              </h2>
              <div class="intro-breadcrumbs mt-4 text-sm">
                <a href="/" class="text-blue-500 hover:text-blue-700">Home</a> /
                <a href="#" class="text-gray-500">Our Resources</a>
              </div>
            </div>
          </div>

          <!-- Header Section -->
          <div
            class="flex flex-col px-[20px] py-[100px] sm:py-[100px] sm:px-[50px] mb-[20px] bg-gray-100"
          >
            <div class="text-[13px] text-gray-500">
              <a href="/" class="bb">Home</a> |
              <a href="#" class="text-gray-500">Our Resources</a>
            </div>
            <h3 class="mb-[20px] font-bold text-4xl">RiskSafe Resources</h3>
            <div class="text-[14px] w-full sm:max-w-[600px]">
              <p>
                Free resources in all shapes and kinds made by our experts at
                <span class="text-blue-600 font-semibold">RiskSafe</span> on
                Risk Assessments, Management, Treatments, and Maintenance for
                different possible outcomes and scenarios.
              </p>
            </div>
          </div>

          <!-- Section Body -->
          <section class="section py-8">
            <div class="w-full mx-auto">
              <div class="hidden bg-white shadow-lg rounded-lg mb-8">
                <div class="p-6">
                  <h2 class="text-2xl font-semibold text-gray-800 mb-4">
                    Risk Management
                  </h2>
                  <div class="flex flex-col lg:flex-row">
                    <div class="lg:w-2/3">
                      <p class="text-gray-600 mb-4">
                        When you remove risk, you increase the value of your
                        business. Success comes to those who quickly identify
                        and eliminate risks in the right order.
                      </p>
                      <p class="text-gray-600 mb-4">
                        The key question is "What's the most important
                        uncertainty?" and the answer should be targeted early.
                      </p>
                      <p class="text-gray-600 mb-4">
                        Risk management is all about identifying and mitigating
                        the uncertainties—especially the company killers.
                      </p>
                      <p class="text-gray-600 mb-4">
                        Insurance is just one element of Risk Management
                        strategies - policy that protects specific assets,
                        risks, or contingencies.
                      </p>
                      <p class="text-gray-600">
                        Completing a risk assessment will help you eliminate
                        costly problems and may also help reduce insurance
                        claims and premiums.
                      </p>
                    </div>
                    <div class="lg:w-1/3 hidden sm:block">
                      <img
                        src="assets/img/banner-bg-old.jpg"
                        class="w-full h-auto rounded-lg"
                        alt="Risk Management"
                      />
                    </div>
                  </div>
                </div>
              </div>

              <!--  Cards Section  -->
              <div
                class="bg-white w-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 z-10 px-[20px] sm:px-[50px] py-10"
              >
                <div
                  class="bg-white shadow-lg border border-gray-200 rounded-lg overflow-hidden hover:shadow-2xl transition-transform transform hover:scale-105 h-[400px] flex flex-col"
                >
                  <div
                    class="h-48 bg-cover bg-center"
                    style="
                      background-image: url('./assets/images/resources/What-is-a-Four-Factor-Breach-Risk-Assessment.png');
                    "
                  ></div>
                  <div class="p-6 flex flex-col justify-between flex-grow">
                    <div>
                      <h3 class="text-xl font-semibold mb-4">
                        Conducting a Risk Assessment
                      </h3>
                      <p class="text-gray-600 mb-4">
                        Describe the risk. What can happen? Consider how and why
                        it can happen...
                      </p>
                    </div>
                    <a
                      href="management-resources/conducting-risk-assestment.html"
                      class="text-blue-600 hover:text-blue-800 font-semibold hover:underline transition-colors"
                      >Read More</a
                    >
                  </div>
                </div>

                <div
                  class="bg-white shadow-lg border border-gray-200 rounded-lg overflow-hidden hover:shadow-2xl transition-transform transform hover:scale-105 h-[400px] flex flex-col"
                >
                  <div
                    class="h-48 bg-cover bg-center"
                    style="
                      background-image: url('./assets/images/resources/Types-of-Risk-in-Insurance.webp');
                    "
                  ></div>
                  <div class="p-6 flex flex-col justify-between flex-grow">
                    <div>
                      <h3 class="text-xl font-semibold mb-4">
                        Common Risk Types
                      </h3>
                      <p class="text-gray-600 mb-4">
                        The level and type of risk that you need to consider
                        will vary with the type of business...
                      </p>
                    </div>
                    <a
                      href="management-resources/common-risk-type.html"
                      class="text-blue-600 hover:text-blue-800 font-semibold hover:underline transition-colors"
                      >Read More</a
                    >
                  </div>
                </div>

                <div
                  class="bg-white shadow-lg border border-gray-200 rounded-lg overflow-hidden hover:shadow-2xl transition-transform transform hover:scale-105 h-[400px] flex flex-col"
                >
                  <div
                    class="h-48 bg-cover bg-center"
                    style="
                      background-image: url('./assets/images/resources/control-self-assessments-blog-20161123-k12532.jpg');
                    "
                  ></div>
                  <div class="p-6 flex flex-col justify-between flex-grow">
                    <div>
                      <h3 class="text-xl font-semibold mb-4">
                        Control Self Assessment (CSA)
                      </h3>
                      <p class="text-gray-600 mb-4">
                        CSA is a management tool designed to assist work teams
                        to be more effective...
                      </p>
                    </div>
                    <a
                      href="management-resources/control-self-assesment.html"
                      class="text-blue-600 hover:text-blue-800 font-semibold hover:underline transition-colors"
                      >Read More</a
                    >
                  </div>
                </div>

                <div
                  class="bg-white shadow-lg border border-gray-200 rounded-lg overflow-hidden hover:shadow-2xl transition-transform transform hover:scale-105 h-[400px] flex flex-col"
                >
                  <div
                    class="h-48 bg-cover bg-center"
                    style="
                      background-image: url('./assets/images/resources/Scytale_Blog-images-03-3-1-768x511.jpg.jpeg');
                    "
                  ></div>
                  <div class="p-6 flex flex-col justify-between flex-grow">
                    <div>
                      <h3 class="text-xl font-semibold mb-4">
                        How can a Controls Assessment help your business?
                      </h3>
                      <p class="text-gray-600 mb-4">
                        Stay ahead of the competition, comply with regulatory
                        requirements...
                      </p>
                    </div>
                    <a
                      href="management-resources/how-can-a-controls-assessment-help-your-business.html"
                      class="text-blue-600 hover:text-blue-800 font-semibold hover:underline transition-colors"
                      >Read More</a
                    >
                  </div>
                </div>

                <div
                  class="bg-white shadow-lg border border-gray-200 rounded-lg overflow-hidden hover:shadow-2xl transition-transform transform hover:scale-105 h-[400px] flex flex-col"
                >
                  <div
                    class="h-48 bg-cover bg-center"
                    style="
                      background-image: url('./assets/images/resources/examples-of-health-and-safety-risk-assessmennts-464007914.jpg');
                    "
                  ></div>
                  <div class="p-6 flex flex-col justify-between flex-grow">
                    <div>
                      <h3 class="text-xl font-semibold mb-4">
                        Workplace Health & Safety Risk Assessments
                      </h3>
                      <p class="text-gray-600 mb-4">
                        Every business owner should manage health & safety and
                        control risks...
                      </p>
                    </div>
                    <a
                      href="management-resources/workplace-health-and-safety-risk-assessments.html"
                      class="text-blue-600 hover:text-blue-800 font-semibold hover:underline transition-colors"
                      >Read More</a
                    >
                  </div>
                </div>

                <div
                  class="bg-white shadow-lg border border-gray-200 rounded-lg overflow-hidden hover:shadow-2xl transition-transform transform hover:scale-105 h-[400px] flex flex-col"
                >
                  <div
                    class="h-48 bg-cover bg-center"
                    style="
                      background-image: url('./assets/images/resources/what-insurance-does-a-community-group-need.jpg');
                    "
                  ></div>
                  <div class="p-6 flex flex-col justify-between flex-grow">
                    <div>
                      <h3 class="text-xl font-semibold mb-4">
                        Risk Management for Community & Charity Groups
                      </h3>
                      <p class="text-gray-600 mb-4">
                        The exact nature of risk will vary greatly depending on
                        the specific type of community work...
                      </p>
                    </div>
                    <a
                      href="management-resources/risk-management-for-community-&-charity-groups.html"
                      class="text-blue-600 hover:text-blue-800 font-semibold hover:underline transition-colors"
                      >Read More</a
                    >
                  </div>
                </div>

                <div
                  class="bg-white shadow-lg border border-gray-200 rounded-lg overflow-hidden hover:shadow-2xl transition-transform transform hover:scale-105 h-[400px] flex flex-col"
                >
                  <div
                    class="h-48 bg-cover bg-center"
                    style="
                      background-image: url('./assets/images/resources/7915_bigstock_Businessman_Collects_Wooden_Pu_302963389.jpg');
                    "
                  ></div>
                  <div class="p-6 flex flex-col justify-between flex-grow">
                    <div>
                      <h3 class="text-xl font-semibold mb-4">
                        Insurable Risks
                      </h3>
                      <p class="text-gray-600 mb-4">
                        The exact nature of risk will vary greatly depending on
                        the specific type of community work...
                      </p>
                    </div>
                    <a
                      href="management-resources/insurable-risks.html"
                      class="text-blue-600 hover:text-blue-800 font-semibold hover:underline transition-colors"
                      >Read More</a
                    >
                  </div>
                </div>

                <div
                  class="bg-white shadow-lg border border-gray-200 rounded-lg overflow-hidden hover:shadow-2xl transition-transform transform hover:scale-105 h-[400px] flex flex-col"
                >
                  <div
                    class="h-48 bg-cover bg-center"
                    style="
                      background-image: url('./assets/images/resources/shutterstock_14082997881.jpg');
                    "
                  ></div>
                  <div class="p-6 flex flex-col justify-between flex-grow">
                    <div>
                      <h3 class="text-xl font-semibold mb-4">
                        How to Reduce Your Insurance Premiums
                      </h3>
                      <p class="text-gray-600 mb-4">
                        The exact nature of risk will vary greatly depending on
                        the specific type of community work...
                      </p>
                    </div>
                    <a
                      href="management-resources/reduce-insurance-premuim.html"
                      class="text-blue-600 hover:text-blue-800 font-semibold hover:underline transition-colors"
                      >Read More</a
                    >
                  </div>
                </div>
              </div>
            </div>
          </section>
        </div>
      </div>
    </div>

    <?php include 'layout/footer.layout.php' ?>
  </body>
</html>
