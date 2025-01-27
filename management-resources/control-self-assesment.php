<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Control Self Assesment | Resources</title>

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
        <a href="/risksafe-resources" class="text-gray-500">Resources</a> |
        <a class="text-gray-500">Control Self Assessment</a>
      </div>
      <h3 class="mb-[20px] font-bold text-4xl">Control Self Assessment</h3>
      <div class="text-[14px] w-full sm:max-w-[600px]">
        <p>
          A management tool designed to help teams effectively achieve their
          objectives by managing their related risks in a collaborative process
          focusing on key business processes.
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
                <!-- Card Content -->
                <div class="p-6 sm:w-1/2">
                  <div class="card-header mb-4">
                    <h3
                      class="text-2xl sm:text-[30px] header-text text-primary"
                    >
                      Control Self Assessment
                    </h3>
                  </div>
                  <div class="card-body text-gray-700 leading-relaxed custom-p">
                    <p class="mb-4">
                      Control Self Assessment (CSA) is a management tool
                      designed to assist work teams to be more effective in
                      achieving their objectives and managing their related
                      risks.
                    </p>
                    <p class="mb-4">
                      CSA is a highly interactive and collaborative process that
                      focuses on processes and issues important to a business or
                      organization.
                    </p>
                    <p class="mb-4">
                      The CSA should include the people actually doing the work
                      - not just those managing a process.
                    </p>
                    <p class="mb-4">
                      Control measures implemented must be reviewed and, if
                      necessary, revised to make sure they work as planned.
                    </p>
                    <p class="mb-4">
                      There are situations where you must review your control
                      measures, including:
                    </p>
                    <ul class="list-disc pl-6 mb-4">
                      <li class="mb-2">
                        When the control measure is not effective, e.g., when an
                        incident occurs
                      </li>
                      <li class="mb-2">
                        Before a change that might lead to new or different
                        risks the control may not cover
                      </li>
                      <li class="mb-2">
                        If a new hazard or risk is identified
                      </li>
                      <li class="mb-2">
                        If consultation results indicate a review is necessary
                      </li>
                      <li>
                        If a Health and Safety Representative requests a review
                      </li>
                    </ul>
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
          <a href="common-risk-type" class="hover:underline">
            <p class="text-gray-700 mt-4 text-[17px]">Common Risk Types</p>
          </a>
        </div>

        <div
          class="flex-1 sm:ml-4 bg-white border border-gray-200 shadow-lg rounded-lg p-6 hover:shadow-xl transition-shadow duration-300 ease-in-out text-right"
        >
          <small class="text-sm font-semibold text-primary mb-2"
            >Next <i class="fa fa-caret-right"></i
          ></small>
          <a
            href="how-can-a-controls-assessment-help-your-business"
            class="hover:underline"
          >
            <p class="text-gray-700 mt-4 text-[17px]">
              How can a Controls Assessment help your business?
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
          >
            Get Started Now <i class="fa fa-caret-right ml-[5px]"></i>
          </a>
        </div>
      </div>
    </div>

    <?php include '../layout/footer.layout.php' ?>
  </body>
</html>
