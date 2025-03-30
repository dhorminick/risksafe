<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RiskSafe | Risk Assessment & Management</title>

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

    <!-- 1 -->
    <div
      class="flex justify-between bg-[var(--light-blue)]"
      style="min-height: calc(100dvh - 90px)"
    >
      <div
        class="w-full sm:w-[50%] flex flex-col justify-start mt-[50px] sm:mt-0 sm:justify-center p-[30px] sm:p-[50px]"
      >
        <h1 class="font-bold text-[45px]">Risk Assessment Made Easy</h1>
        <div class="my-[20px] flex w-full gap-[10px] flex-col">
          <p>
            Conduct Risk Assessments, Audit of Controls, Track Incidents, Create
            Treatment Plans and Manage your Compliance Risks
          </p>
          <p>
            Built by Compliance Risk and Audit professionals with over a decade
            of experience
          </p>
        </div>
        <div class="mt-[20px]">
          <a href="/book-demo" class="btn btn-primary">Try RiskSafe</a>
          <a href="/auth/sign-in" class="btn btn-secondary">Get Started</a>
        </div>
      </div>
      <div class="hidden sm:block w-[50%]">
        <img
          class="h-[auto] w-full"
          src="/assets/images/home/risk-management-flow-chart-hand-sketching-red-marker-transparent-wipe-board-92790683-removebg-preview.png"
        />
      </div>
    </div>

    <!-- new 2: grid md:grid-cols-2 -->
    <div
      class="flex flex-col gap-[10px] px-[30px] sm:px-[100px] py-[50px] my-[20px]"
    >
      <div class="mb-[40px] text-center">
        <h2
          class="text-gray-800 text-[25px] sm:text-[30px] font-extrabold mb-[5px]"
        >
          Comprehensive Risk Management Suite
        </h2>
        <div class="text-[14px]">
          RiskSafe simplifies operations, scales forms, and ensures data
          accuracy for better decision-making.
        </div>
      </div>
      <div class="flex justify-between items-center gap-[10px]">
        <div class="w-full sm:w-[35%] flex flex-col gap-[10px] h-[max-content]">
          <div class="active image-setter" data-img="compliance_no_sidebar.png">
            <div class="head">Easy to Use Dashboard</div>
            <div class="desc">
              Navigate with ease. Our intuitive interface ensures you spend less
              time learning and more time achieving your goals, making
              management a breeze.
            </div>
          </div>
          <div class="image-setter" data-img="form.png">
            <div class="head">Flexible Form System</div>
            <div class="desc">
              Adaptable tools designed to customize and scale your forms
              effortlessly, meeting all your needs with powerful and flexible
              options.
            </div>
          </div>
          <div class="image-setter" data-img="risks.png">
            <div class="head">Efficient Data Management and Presentation</div>
            <div class="desc">
              Organize, store, and present data with precision. Implementing
              robust practices ensures accuracy, drives informed decisions, and
              streamlines operations.
            </div>
          </div>
        </div>
        <div class="hidden sm:block sm:w-[65%]">
          <div class="h-[max-content] w-full p-[20px]">
            <img
              src="/assets/images/home/compliance_no_sidebar.png"
              class="transition-opacity duration-500 ease-in-out"
              id="mainImage"
            />
          </div>
        </div>
      </div>
    </div>

    <!-- new 3 -->
    <div class="flex justify-between bg-[var(--light-blue)]">
      <div class="flex flex-col w-full sm:w-[50%] p-[30px] sm:p-[100px]">
        <h3 class="header-text !mb-[20px]">
          Built for Compliance and Risk Officers
        </h3>
        <div>
          One Platform for all of your Compliance Risk Management Monitoring.
        </div>
        <div class="mt-[5px]">
          We bring together automated assurance, risk assessments, audit,
          incident management and reporting.
        </div>
        <div class="mt-[5px]">
          Built by Risk & Compliance professionals with over a decade of
          experience.
        </div>
        <a
          href="/auth/sign-in"
          class="mt-[30px] btn btn-primary w-[max-content] flex justify-center items-center gap-[5px]"
          >Get Started <i class="fa fa-arrow-right"></i>
        </a>
      </div>
      <div class="w-[50%] hidden sm:flex justify-center">
        <img src="/assets/images/home/when.jpeg" class="" />
      </div>
    </div>

    <!-- 4 -->
    <div>
      <div class="bg-[var(--primary)] px-[30px] sm:px-[100px] py-16 font-sans">
        <div class="grid md:grid-cols-2 items-center gap-12 mx-auto">
          <div>
            <h1 class="text-4xl font-bold text-white">Why choose RiskSafe?</h1>
            <div
              class="mt-6 flex flex-col gap-[10px] text-sm text-white leading-relaxed"
            >
              <p>
                Whether or not you have prior experience in risk management,
                RiskSafe is designed to guide you through the process with ease.
              </p>
              <p>
                Our platform comes equipped with predefined data sets and tools,
                enabling you to quickly understand and implement effective risk
                management strategies.
              </p>
              <p>
                RiskSafe Automated monitoring solution saves us from doing
                manual testing and all the co-ordination headaches
              </p>
            </div>
          </div>
          <div class="grid sm:grid-cols-2 gap-6">
            <div
              class="bg-white flex flex-col items-center text-center rounded-md md:p-8 p-6"
            >
              <h3
                class="lg:text-5xl text-3xl font-extrabold text-[var(--primary)]"
              >
                3x
              </h3>
              <div class="mt-4">
                <p class="text-sm text-[var(--primary)]">
                  return on investment in a year
                </p>
              </div>
            </div>
            <div
              class="bg-white flex flex-col items-center text-center rounded-md md:p-8 p-6"
            >
              <h3
                class="lg:text-5xl text-3xl font-extrabold text-[var(--primary)]"
              >
                $220k
              </h3>
              <div class="mt-4">
                <p class="text-sm text-[var(--primary)]">
                  savings per year when using full suite of tools
                </p>
              </div>
            </div>
            <div
              class="bg-white flex flex-col items-center text-center rounded-md md:p-8 p-6"
            >
              <h3
                class="lg:text-5xl text-3xl font-extrabold text-[var(--primary)]"
              >
                3 FTE
              </h3>
              <div class="mt-4">
                <p class="text-sm text-[var(--primary)]">
                  reduced from compliance team
                </p>
              </div>
            </div>
            <div
              class="bg-white flex flex-col items-center text-center rounded-md md:p-8 p-6"
            >
              <h3
                class="lg:text-5xl text-3xl font-extrabold text-[var(--primary)]"
              >
                24/7
              </h3>
              <div class="mt-4">
                <p class="text-sm text-[var(--primary)]">Customer Support</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="px-[30px] sm:px-[100px] py-16 mt-[20px]">
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-[30px] py-[50px]">
        <div class="flex items-center min-h-[200px]">
          <div>
            <h1 class="font-bold text-4xl mb-[20px]">Introducing 20X improvement<br> with our Compliance AI Agent</h1>
            <div>Join the waitlist and be one of the first to try it out!</div>
            <a class="mt-[30px] btn btn-primary w-[max-content] flex justify-center items-center gap-[5px]" href="/waitlist/#join">Join</a>
          </div>
        </div>
        <div class="flex items-center">
          <div>
            <h3 class="mb-[20px] header-text">Compliance professionals struggle with:</h3>
            <ul class="list-disc list-inside pl-[10px]">
              <li><strong>Regulatory Complexity:</strong> Keeping up with changing laws.</li>
              <li><strong>Manual Processes:</strong> Time-consuming audits, policy drafting, and risk assessments.</li>
              <li><strong>False Positives in Monitoring:</strong> High noise in transaction monitoring & employee surveillance.</li>
              <li><strong>Documentation Burden:</strong> Managing policies, training, and evidence for audits.</li>
              <li><strong>Fraud & Insider Risk:</strong> Detecting anomalies in financial or operational data.</li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="mt-[20px]">
      <div class="bg-[var(--light-blue)] min-h-[500px]">
        <div class="py-16 px-4 mx-[30px] sm:mx-[50px]">
          <h2 class="text-gray-800 text-4xl font-extrabold text-center">
            What RiskSafe offers?
          </h2>
          <div class="mb-[40px] text-[14] text-center">
            With RiskSafe, you’re equipped with the tools to manage risks
            effectively and confidently
          </div>
          <div class="!hidden flex flex-col gap-[5px] mb-16">
            <p>
              When you remove risk, you increase the value of your business.
              Success comes to those who quickly identify and eliminate risks in
              the right order.
            </p>
          </div>

          <div
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-md:max-w-md mx-auto"
          >
            <div
              class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-lg transition-all"
            >
              <div class="p-8">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="#007bff"
                  class="w-8 mb-6"
                  viewBox="0 0 32 32"
                >
                  <path
                    d="M28.068 12h-.128a.934.934 0 0 1-.864-.6.924.924 0 0 1 .2-1.01l.091-.091a2.938 2.938 0 0 0 0-4.147l-1.511-1.51a2.935 2.935 0 0 0-4.146 0l-.091.091A.956.956 0 0 1 20 4.061v-.129A2.935 2.935 0 0 0 17.068 1h-2.136A2.935 2.935 0 0 0 12 3.932v.129a.956.956 0 0 1-1.614.668l-.086-.091a2.935 2.935 0 0 0-4.146 0l-1.516 1.51a2.938 2.938 0 0 0 0 4.147l.091.091a.935.935 0 0 1 .185 1.035.924.924 0 0 1-.854.579h-.128A2.935 2.935 0 0 0 1 14.932v2.136A2.935 2.935 0 0 0 3.932 20h.128a.934.934 0 0 1 .864.6.924.924 0 0 1-.2 1.01l-.091.091a2.938 2.938 0 0 0 0 4.147l1.51 1.509a2.934 2.934 0 0 0 4.147 0l.091-.091a.936.936 0 0 1 1.035-.185.922.922 0 0 1 .579.853v.129A2.935 2.935 0 0 0 14.932 31h2.136A2.935 2.935 0 0 0 20 28.068v-.129a.956.956 0 0 1 1.614-.668l.091.091a2.935 2.935 0 0 0 4.146 0l1.511-1.509a2.938 2.938 0 0 0 0-4.147l-.091-.091a.935.935 0 0 1-.185-1.035.924.924 0 0 1 .854-.58h.128A2.935 2.935 0 0 0 31 17.068v-2.136A2.935 2.935 0 0 0 28.068 12ZM29 17.068a.933.933 0 0 1-.932.932h-.128a2.956 2.956 0 0 0-2.083 5.028l.09.091a.934.934 0 0 1 0 1.319l-1.511 1.509a.932.932 0 0 1-1.318 0l-.09-.091A2.957 2.957 0 0 0 18 27.939v.129a.933.933 0 0 1-.932.932h-2.136a.933.933 0 0 1-.932-.932v-.129a2.951 2.951 0 0 0-5.028-2.082l-.091.091a.934.934 0 0 1-1.318 0l-1.51-1.509a.934.934 0 0 1 0-1.319l.091-.091A2.956 2.956 0 0 0 4.06 18h-.128A.933.933 0 0 1 3 17.068v-2.136A.933.933 0 0 1 3.932 14h.128a2.956 2.956 0 0 0 2.083-5.028l-.09-.091a.933.933 0 0 1 0-1.318l1.51-1.511a.932.932 0 0 1 1.318 0l.09.091A2.957 2.957 0 0 0 14 4.061v-.129A.933.933 0 0 1 14.932 3h2.136a.933.933 0 0 1 .932.932v.129a2.956 2.956 0 0 0 5.028 2.082l.091-.091a.932.932 0 0 1 1.318 0l1.51 1.511a.933.933 0 0 1 0 1.318l-.091.091A2.956 2.956 0 0 0 27.94 14h.128a.933.933 0 0 1 .932.932Z"
                    data-original="#000000"
                  />
                  <path
                    d="M16 9a7 7 0 1 0 7 7 7.008 7.008 0 0 0-7-7Zm0 12a5 5 0 1 1 5-5 5.006 5.006 0 0 1-5 5Z"
                    data-original="#000000"
                  />
                </svg>
                <h3 class="text-gray-800 text-xl font-semibold mb-3">
                  Data Management
                </h3>
                <div class="text-gray-500 text-sm leading-relaxed ml-[15px]">
                  <ul class="list-disc">
                    <li>
                      Streamline Compliance requirements, Risk Assessments,
                      Policies and Reporting
                    </li>
                    <li>
                      No more wasting time manually capturing risk and
                      compliance information across so many documents
                    </li>
                  </ul>
                </div>
              </div>
            </div>

            <div
              class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-lg transition-all"
            >
              <div class="p-8">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="#007bff"
                  class="w-8 mb-6"
                  viewBox="0 0 682.667 682.667"
                >
                  <defs>
                    <clipPath id="a" clipPathUnits="userSpaceOnUse">
                      <path d="M0 512h512V0H0Z" data-original="#000000" />
                    </clipPath>
                  </defs>
                  <g
                    fill="none"
                    stroke="#007bff"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-miterlimit="10"
                    stroke-width="40"
                    clip-path="url(#a)"
                    transform="matrix(1.33 0 0 -1.33 0 682.667)"
                  >
                    <path
                      d="M256 492 60 410.623v-98.925C60 183.674 137.469 68.38 256 20c118.53 48.38 196 163.674 196 291.698v98.925z"
                      data-original="#000000"
                    />
                    <path
                      d="M178 271.894 233.894 216 334 316.105"
                      data-original="#000000"
                    />
                  </g>
                </svg>
                <h3 class="text-gray-800 text-xl font-semibold mb-3">
                  Dashboard Overview
                </h3>
                <div class="text-gray-500 text-sm leading-relaxed ml-[15px]">
                  <ul class="list-disc">
                    <li>
                      Easily view risk position and compliance effectiveness on
                      a clear dashboard
                    </li>
                    <li>Capture all key risk information in one place</li>
                  </ul>
                </div>
              </div>
            </div>

            <div
              class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-lg transition-all"
            >
              <div class="p-8">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="#007bff"
                  class="w-8 mb-6"
                  viewBox="0 0 24 24"
                >
                  <g fill-rule="evenodd" clip-rule="evenodd">
                    <path
                      d="M17.03 8.97a.75.75 0 0 1 0 1.06l-4.2 4.2a.75.75 0 0 1-1.154-.114l-1.093-1.639L8.03 15.03a.75.75 0 0 1-1.06-1.06l3.2-3.2a.75.75 0 0 1 1.154.114l1.093 1.639L15.97 8.97a.75.75 0 0 1 1.06 0z"
                      data-original="#000000"
                    />
                    <path
                      d="M13.75 9.5a.75.75 0 0 1 .75-.75h2a.75.75 0 0 1 .75.75v2a.75.75 0 0 1-1.5 0v-1.25H14.5a.75.75 0 0 1-.75-.75z"
                      data-original="#000000"
                    />
                    <path
                      d="M3.095 3.095C4.429 1.76 6.426 1.25 9 1.25h6c2.574 0 4.57.51 5.905 1.845C22.24 4.429 22.75 6.426 22.75 9v6c0 2.574-.51 4.57-1.845 5.905C19.571 22.24 17.574 22.75 15 22.75H9c-2.574 0-4.57-.51-5.905-1.845C1.76 19.571 1.25 17.574 1.25 15V9c0-2.574.51-4.57 1.845-5.905zm1.06 1.06C3.24 5.071 2.75 6.574 2.75 9v6c0 2.426.49 3.93 1.405 4.845.916.915 2.419 1.405 4.845 1.405h6c2.426 0 3.93-.49 4.845-1.405.915-.916 1.405-2.419 1.405-4.845V9c0-2.426-.49-3.93-1.405-4.845C18.929 3.24 17.426 2.75 15 2.75H9c-2.426 0-3.93.49-4.845 1.405z"
                      data-original="#000000"
                    />
                  </g>
                </svg>
                <h3 class="text-gray-800 text-xl font-semibold mb-3">
                  Quality Re-assurance
                </h3>
                <div class="text-gray-500 text-sm leading-relaxed ml-[15px]">
                  <ul class="list-disc">
                    <li>
                      Demonstrate to customers and regulators you are running a
                      safe shop.
                    </li>
                    <li>
                      Get comfort that you are operating in a safe work
                      environment.
                    </li>
                  </ul>
                </div>
              </div>
            </div>

            <div
              class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-lg transition-all"
            >
              <div class="p-8">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="#007bff"
                  class="w-8 mb-6"
                  viewBox="0 0 32 32"
                >
                  <path
                    d="M28.068 12h-.128a.934.934 0 0 1-.864-.6.924.924 0 0 1 .2-1.01l.091-.091a2.938 2.938 0 0 0 0-4.147l-1.511-1.51a2.935 2.935 0 0 0-4.146 0l-.091.091A.956.956 0 0 1 20 4.061v-.129A2.935 2.935 0 0 0 17.068 1h-2.136A2.935 2.935 0 0 0 12 3.932v.129a.956.956 0 0 1-1.614.668l-.086-.091a2.935 2.935 0 0 0-4.146 0l-1.516 1.51a2.938 2.938 0 0 0 0 4.147l.091.091a.935.935 0 0 1 .185 1.035.924.924 0 0 1-.854.579h-.128A2.935 2.935 0 0 0 1 14.932v2.136A2.935 2.935 0 0 0 3.932 20h.128a.934.934 0 0 1 .864.6.924.924 0 0 1-.2 1.01l-.091.091a2.938 2.938 0 0 0 0 4.147l1.51 1.509a2.934 2.934 0 0 0 4.147 0l.091-.091a.936.936 0 0 1 1.035-.185.922.922 0 0 1 .579.853v.129A2.935 2.935 0 0 0 14.932 31h2.136A2.935 2.935 0 0 0 20 28.068v-.129a.956.956 0 0 1 1.614-.668l.091.091a2.935 2.935 0 0 0 4.146 0l1.511-1.509a2.938 2.938 0 0 0 0-4.147l-.091-.091a.935.935 0 0 1-.185-1.035.924.924 0 0 1 .854-.58h.128A2.935 2.935 0 0 0 31 17.068v-2.136A2.935 2.935 0 0 0 28.068 12ZM29 17.068a.933.933 0 0 1-.932.932h-.128a2.956 2.956 0 0 0-2.083 5.028l.09.091a.934.934 0 0 1 0 1.319l-1.511 1.509a.932.932 0 0 1-1.318 0l-.09-.091A2.957 2.957 0 0 0 18 27.939v.129a.933.933 0 0 1-.932.932h-2.136a.933.933 0 0 1-.932-.932v-.129a2.951 2.951 0 0 0-5.028-2.082l-.091.091a.934.934 0 0 1-1.318 0l-1.51-1.509a.934.934 0 0 1 0-1.319l.091-.091A2.956 2.956 0 0 0 4.06 18h-.128A.933.933 0 0 1 3 17.068v-2.136A.933.933 0 0 1 3.932 14h.128a2.956 2.956 0 0 0 2.083-5.028l-.09-.091a.933.933 0 0 1 0-1.318l1.51-1.511a.932.932 0 0 1 1.318 0l.09.091A2.957 2.957 0 0 0 14 4.061v-.129A.933.933 0 0 1 14.932 3h2.136a.933.933 0 0 1 .932.932v.129a2.956 2.956 0 0 0 5.028 2.082l.091-.091a.932.932 0 0 1 1.318 0l1.51 1.511a.933.933 0 0 1 0 1.318l-.091.091A2.956 2.956 0 0 0 27.94 14h.128a.933.933 0 0 1 .932.932Z"
                    data-original="#000000"
                  />
                  <path
                    d="M16 9a7 7 0 1 0 7 7 7.008 7.008 0 0 0-7-7Zm0 12a5 5 0 1 1 5-5 5.006 5.006 0 0 1-5 5Z"
                    data-original="#000000"
                  />
                </svg>
                <h3 class="text-gray-800 text-xl font-semibold mb-3">
                  Flexible Data Export Options
                </h3>
                <div class="text-gray-500 text-sm leading-relaxed ml-[15px]">
                  <ul class="list-disc">
                    <li>
                      Export your data in various formats including CSV, Excel
                      (XLS), and Excel (XLSX), ensuring compatibility with your
                      preferred software and systems.
                    </li>
                    <li>
                      Seamlessly convert and manage your data files, making it
                      easy to handle and analyze information across different
                      platforms.
                    </li>
                  </ul>
                </div>
              </div>
            </div>

            <div
              class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-lg transition-all"
            >
              <div class="p-8">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="#007bff"
                  class="w-8 mb-6"
                  viewBox="0 0 32 32"
                >
                  <path
                    d="M28.068 12h-.128a.934.934 0 0 1-.864-.6.924.924 0 0 1 .2-1.01l.091-.091a2.938 2.938 0 0 0 0-4.147l-1.511-1.51a2.935 2.935 0 0 0-4.146 0l-.091.091A.956.956 0 0 1 20 4.061v-.129A2.935 2.935 0 0 0 17.068 1h-2.136A2.935 2.935 0 0 0 12 3.932v.129a.956.956 0 0 1-1.614.668l-.086-.091a2.935 2.935 0 0 0-4.146 0l-1.516 1.51a2.938 2.938 0 0 0 0 4.147l.091.091a.935.935 0 0 1 .185 1.035.924.924 0 0 1-.854.579h-.128A2.935 2.935 0 0 0 1 14.932v2.136A2.935 2.935 0 0 0 3.932 20h.128a.934.934 0 0 1 .864.6.924.924 0 0 1-.2 1.01l-.091.091a2.938 2.938 0 0 0 0 4.147l1.51 1.509a2.934 2.934 0 0 0 4.147 0l.091-.091a.936.936 0 0 1 1.035-.185.922.922 0 0 1 .579.853v.129A2.935 2.935 0 0 0 14.932 31h2.136A2.935 2.935 0 0 0 20 28.068v-.129a.956.956 0 0 1 1.614-.668l.091.091a2.935 2.935 0 0 0 4.146 0l1.511-1.509a2.938 2.938 0 0 0 0-4.147l-.091-.091a.935.935 0 0 1-.185-1.035.924.924 0 0 1 .854-.58h.128A2.935 2.935 0 0 0 31 17.068v-2.136A2.935 2.935 0 0 0 28.068 12ZM29 17.068a.933.933 0 0 1-.932.932h-.128a2.956 2.956 0 0 0-2.083 5.028l.09.091a.934.934 0 0 1 0 1.319l-1.511 1.509a.932.932 0 0 1-1.318 0l-.09-.091A2.957 2.957 0 0 0 18 27.939v.129a.933.933 0 0 1-.932.932h-2.136a.933.933 0 0 1-.932-.932v-.129a2.951 2.951 0 0 0-5.028-2.082l-.091.091a.934.934 0 0 1-1.318 0l-1.51-1.509a.934.934 0 0 1 0-1.319l.091-.091A2.956 2.956 0 0 0 4.06 18h-.128A.933.933 0 0 1 3 17.068v-2.136A.933.933 0 0 1 3.932 14h.128a2.956 2.956 0 0 0 2.083-5.028l-.09-.091a.933.933 0 0 1 0-1.318l1.51-1.511a.932.932 0 0 1 1.318 0l.09.091A2.957 2.957 0 0 0 14 4.061v-.129A.933.933 0 0 1 14.932 3h2.136a.933.933 0 0 1 .932.932v.129a2.956 2.956 0 0 0 5.028 2.082l.091-.091a.932.932 0 0 1 1.318 0l1.51 1.511a.933.933 0 0 1 0 1.318l-.091.091A2.956 2.956 0 0 0 27.94 14h.128a.933.933 0 0 1 .932.932Z"
                    data-original="#000000"
                  />
                  <path
                    d="M16 9a7 7 0 1 0 7 7 7.008 7.008 0 0 0-7-7Zm0 12a5 5 0 1 1 5-5 5.006 5.006 0 0 1-5 5Z"
                    data-original="#000000"
                  />
                </svg>
                <h3 class="text-gray-800 text-xl font-semibold mb-3">
                  Customized & Recommended Data
                </h3>
                <div class="text-gray-500 text-sm leading-relaxed ml-[15px]">
                  <ul class="list-disc">
                    <li>
                      Utilize RiskSafe's expert-recommended controls and
                      treatments if you're new to risk management, ensuring you
                      follow best practices right from the start.
                    </li>
                    <li>
                      Create and integrate your custom data throughout the
                      dashboard for a tailored risk management strategy that
                      fits your specific needs.
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- 6 -->
    <div class="flex justify-center items-center z-[2] mt-[20px]">
      <div
        class="cta w-full flex flex-col rounded-[0] sm:rounded-[10px] gap-[20px] p-[30px] sm:p-[50px] justify-center items-center sm:w-[80%]"
      >
        <h1 class="font-bold sm:w-[700px] w-full text-[30px] text-center">
          Discover why RiskSafe is loved by Compliance Risk Officers around the
          world
        </h1>
        <!-- <div class="sm:w-[500px] w-full text-center">
          Discover why RiskSafe is loved by Compliance Risk Officers around the
          world!
        </div> -->
        <div class="mt-[10px] flex gap-[10px]">
          <a href="/book-demo" class="btn btn-secondary">Book Demo</a>
          <a
            href="/book-demo"
            class="btn btn-secondary gap-[5px] justify-center items-center"
            >Get Started <i class="fa fa-arrow-right"></i
          ></a>
        </div>
      </div>
    </div>

    <!-- footer -->
    <?php include 'layout/footer.layout.php' ?>
  </body>
</html>

<script>
  $(".image-setter").click(function (e) {
    var img = $(this).attr("data-img");
    $(".image-setter").removeClass("active");
    $("#mainImage").attr("src", `/assets/images/home/${img}`);
    $(this).addClass("active");
  });
</script>
<style lang="scss">
  img#mainImage {
    border-radius: 10px;
    /* border: 3px solid rgba(235, 235, 235, 0.5); */
    border: 1px solid var(--primary);
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    -webkit-user-drag: none;
    cursor: pointer;
  }
  .image-setter {
    border: 1px solid rgba(235, 235, 235, 0.5);
    border-left: 10px solid rgba(235, 235, 235, 0.5);
    border-radius: 5px;
    min-height: 200px;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    gap: 5px;
    padding: 30px;

    .head {
      font-size: 23px;
      font-weight: 700;
    }
    .desc {
      font-size: 13px;
      color: rgb(0, 0, 0, 0.7);
    }
    &.active {
      border-left: 10px solid var(--primary);
      background-color: #f7f9fc;

      .head {
        color: var(--primary);
      }
    }
  }

  .image-setter:not(.active):hover {
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
  }
</style>
