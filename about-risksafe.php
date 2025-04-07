<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>About RiskSafe</title>

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

    <div
      class="flex flex-col px-[20px] py-[100px] sm:py-[100px] sm:px-[50px] mb-[20px] bg-gray-100"
    >
      <div class="text-[13px] text-gray-500">
        <a href="/" class="bb">Home</a> | About RiskSafe
      </div>
      <h3 class="mb-[20px] font-bold text-4xl">About RiskSafe</h3>
      <div class="text-[14px] w-full sm:max-w-[600px]">
        Why do so many Small to Medium Size businesses and Startup" fail around the world... Poor Risk Management
      </div>
    </div>

    <div class="flex flex-col sm:flex-row justify-between py-[50px]">
      <div class="w-full sm:w-[60%] px-[20px] sm:px-[50px]">
        <h3 class="text-gray-800 text-3xl font-extrabold mb-[20px]">
          What RiskSafe aims to achieve?
        </h3>
        <div class="flex flex-col gap-[15px]">
          <p>
            Why do so many startups, small businesses fail around the world...
            Poor Risk Management.
          </p>
          <p>It could due to:</p>
          <div class="ml-[20px]">
            <ul class="list-disc">
              <li>
                Lack of understanding of regulatory requirements such as AML
              </li>
              <li>Lack of Fraud risk mitigation</li>
              <li>Non adherence to Health & Safety requirements</li>
              <li>In adequate management of Strategic risks</li>
            </ul>
          </div>
          <p>
            Entrepreneurs by nature are risk takers. But the most successful
            risk takers protect their bottom line risk above all else.
          </p>
          <p>
            The freedom of being your own boss comes with added responsibilities
            like; ‘How do I protect myself when things go wrong in business?’
          </p>
          <p>
            RiskSafe helps entrepreneurs understand their business risks and
            help them move forward with confidence.
          </p>
          <p>
            Forget the paper forms - our tools will help you understand which
            risks to manage so you can focus on doing what you love.
          </p>
          <p>
            Business growth requires a solid foundation and a solid foundation
            is built on a powerful risk-management plan.
          </p>
        </div>
      </div>
      <div
        class="w-full sm:w-[40%] hidden sm:block mt-[50px] sm:mt-0 mr-[50px]"
      >
        <img
          src="https://assets-us-01.kc-usercontent.com/56ac0847-da43-0017-b8aa-e0522019cff9/b303e764-5ef5-4c00-b057-0b1393a7bbd6/broker-feeds-min.png"
        />
      </div>
    </div>

    <div class="flex justify-center items-center">
      <div
        class="cta w-full flex flex-col rounded-[10px] gap-[20px] p-[50px] justify-center items-center sm:w-[80%]"
      >
        <h1 class="font-bold text-[30px] text-center">Let's Work Together</h1>
        <div class="sm:w-[500px] w-full text-center">
          Elevate your audit, risk, sustainability, and compliance teams with
          the intelligent, collaborative, connected risk management platform
        </div>
        <div class="mt-[10px]">
          <a href='/book-demo' class="btn btn-secondary nh">Book A Demo Now!</a>
        </div>
      </div>
    </div>

    <?php include 'layout/footer.layout.php' ?>
  </body>
</html>
