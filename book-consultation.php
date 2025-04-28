<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Book Consultation | RiskSafe</title>

    <link rel="stylesheet" href="/assets/css/_style.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
      integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <script src="https://cdn.tailwindcss.com"></script>
    <link
      rel="shortcut icon"
      type="image/x-icon"
      href="/assets/favicon/favicon.ico"
    />
  </head>
  <body class="flex flex-col gap-[30px]">
    <?php include 'layout/header.layout.php' ?>

    <div class="mt-[55px]"></div>

    <div
      class="flex flex-col px-[20px] py-[100px] sm:py-[100px] sm:px-[50px] mb-[20px] bg-gray-100"
    >
      <div class="text-[13px] text-gray-500">
        <a href="/" class="bb">Home</a> | Consulting
      </div>
      <h3 class="mb-[20px] font-bold text-4xl">Book Consultation</h3>
      <div class="text-[14px] w-full sm:max-w-[600px] !hidden">
        Why do so many Small to Medium Size businesses and Startup" fail around
        the world... Poor Risk Management
      </div>
    </div>

    <div class="px-[30px] sm:px-[100px] py-16 mt-[20px]">
      <div class="grid grid-cols-1 sm:grid-cols-2">
        <div>
          <h3 class="text-gray-800 text-3xl font-extrabold mb-[20px]">
            A Team of Practitioners<br /> Not Just Consultants
          </h3>
          <p>
            Our team has worked inside banks, insurers, fintechs, and highly
            regulated industries. We know what works, what auditors expect, and
            how to get things done without overcomplicating your business.
          </p>

          <div class="mt-[20px] ml-[20px]">
            <ul class="list-disc">
              <li>15+ years' experience in Risk, Compliance, Audit</li>
              <li>
                AML/CTF, Data Protection & Cyber, Transformation Projects,
                Fraud, Conduct & Consumer Protection, Crypto, AI, Quality
                Assurance
              </li>
              <li>Fixed price and project-based options</li>
              <li>Available for short-term or ongoing support</li>
            </ul>

            <a href="https://calendly.com/j-kanahara77/risk-compliance-support" target="_blank" class="mt-[30px] btn btn-primary">Get Started</a>
            
          </div>
        </div>
        <div>
          <img
            class="w-full object-center object-cover max-h-[400px] rounded-[5px]"
            src="https://assets-us-01.kc-usercontent.com/56ac0847-da43-0017-b8aa-e0522019cff9/b303e764-5ef5-4c00-b057-0b1393a7bbd6/broker-feeds-min.png"
          />
        </div>
      </div>
    </div>

    <div class="px-[30px] sm:px-[100px] py-16">
      <h1 class="mt-[30px] text-3xl font-extrabold mb-[10px]">
        Tailored Services for Risk & Compliance Success
      </h1>
      <div>
        Whether you need support maturing your risk framework, setting a risk
        appetite, meeting regulatory obligations, designing controls to prevent
        failure or implementing RiskSafe —we’re here to help with tailored
        consulting from experienced professionals.
      </div>
      <div class="grid grid-cols-2 sm:grid-cols-3 gap-[10px] mt-[30px]">
        <div class="mm shadow-md border">
          <div class="header">Risk Framework Design & Review</div>
          <div>
            We assess, build, or uplift your enterprise risk management
            framework and appetite to align with ISO 31000, COSO, or your
            regulator’s expectations.
          </div>
        </div>
        <div class="mm shadow-md border">
          <div class="header">Compliance Obligations Management</div>
          <div>
            We map your regulatory obligations, industry codes, and internal
            policies into structured, trackable registers.
          </div>
        </div>
        <div class="mm shadow-md border">
          <div class="header">Control Frameworks & Testing</div>
          <div>
            Design effective control libraries, map to risks and obligations,
            and implement control testing and attestation programs.
          </div>
        </div>
        <div class="mm shadow-md border">
          <div class="header">Incident Management & Response</div>
          <div>
            Get help designing workflows, response playbooks, and reporting
            processes that meet audit and regulator standards.
          </div>
        </div>
        <div class="mm shadow-md border">
          <div class="header">Board & Executive Reporting</div>
          <div>
            Create powerful, automated dashboards and board-ready packs using
            your RiskSafe data.
          </div>
        </div>
        <div class="mm shadow-md border">
          <div class="header">GRC Implementation & Optimisation</div>
          <div>
            Roll out RiskSafe quickly and align it with your existing
            frameworks, policies, and governance structures.
          </div>
        </div>
      </div>
    </div>

    <?php include 'layout/footer.layout.php' ?>
  </body>
</html>
<style lang="scss">
  .mm {
    background-color: white;
    cursor: pointer;
    border-radius: 10px;
    padding: 20px;
    text-align: center;
    font-size: 90%;

    .header {
      font-weight: bold;
      font-size: 16px !important;
      margin-bottom: 5px;
    }
  }
</style>
