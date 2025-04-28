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
        <ul class="list-disc list-inside pl-[10px] pt-[10px]">
            <li>No implementation fees </li>
            <li>Simple pricing without blowing your budget</li>
            <li>Cloud-based and secure </li>
            <li>Support from actual GRC experts </li>
        </ul>
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
                <p>Whether or not you have prior experience in risk management, RiskSafe is designed to guide you through the process with ease. Our platform comes equipped with predefined data sets and tools, enabling you to quickly understand and implement effective risk management strategies. </p>
                <p>RiskSafe Automated monitoring solution saves us from doing manual testing and all the co-ordination headaches. We’ve spent 15+ years inside compliance, risk, and audit teams—so we built RiskSafe to solve the real pain points we faced. It's practical, flexible, and powerful enough to scale with your business. </p>
                
                <p class='mt-[20px]'>“RiskSafe helped us streamline compliance and save over 20 hours a month on manual tasks."<strong> – CEO of Edfin, Fintech, Taf Shamano</strong> </p>
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
    
    <div class="px-[30px] sm:px-[100px] py-16">
        <section class="bg-white py-16 px-4 sm:px-10 lg:px-20">
          <div class="max-w-7xl mx-auto text-center">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-800 mb-4">Tailored Risk & Compliance Features</h2>
            <p class="text-gray-500 mb-12 max-w-3xl mx-auto">A comprehensive suite of tools for proactive risk management, compliance, and resilience.</p>
        
            <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-3">
              <div class="bg-gray-50 p-6 rounded-2xl shadow-sm hover:shadow-md transition">
                <h3 class="font-semibold text-lg text-gray-700 mb-2">Tailored Risk Profiles</h3>
                <p class="text-gray-500">Industry-based risks and heat maps for precision targeting.</p>
              </div>
        
              <div class="bg-gray-50 p-6 rounded-2xl shadow-sm hover:shadow-md transition">
                <h3 class="font-semibold text-lg text-gray-700 mb-2">Industry-Based Compliance</h3>
                <p class="text-gray-500">Preloaded obligations by sector—plus custom frameworks.</p>
              </div>
        
              <div class="bg-gray-50 p-6 rounded-2xl shadow-sm hover:shadow-md transition">
                <h3 class="font-semibold text-lg text-gray-700 mb-2">Incident Management</h3>
                <p class="text-gray-500">Log, track and resolve issues with full audit trails.</p>
              </div>
        
              <div class="bg-gray-50 p-6 rounded-2xl shadow-sm hover:shadow-md transition">
                <h3 class="font-semibold text-lg text-gray-700 mb-2">Control Management</h3>
                <p class="text-gray-500">Design and test controls, assign owners, and automate attestations.</p>
              </div>
        
              <div class="bg-gray-50 p-6 rounded-2xl shadow-sm hover:shadow-md transition">
                <h3 class="font-semibold text-lg text-gray-700 mb-2">Treatment Plans</h3>
                <p class="text-gray-500">Assign and monitor mitigation actions across teams.</p>
              </div>
        
              <div class="bg-gray-50 p-6 rounded-2xl shadow-sm hover:shadow-md transition">
                <h3 class="font-semibold text-lg text-gray-700 mb-2">Key Risk Indicators (KRIs)</h3>
                <p class="text-gray-500">Track early warning signs with configurable thresholds.</p>
              </div>
        
              <div class="bg-gray-50 p-6 rounded-2xl shadow-sm hover:shadow-md transition">
                <h3 class="font-semibold text-lg text-gray-700 mb-2">Business Continuity Planning</h3>
                <p class="text-gray-500">Maintain operational resilience with plan templates and testing workflows.</p>
              </div>
        
              <div class="bg-gray-50 p-6 rounded-2xl shadow-sm hover:shadow-md transition">
                <h3 class="font-semibold text-lg text-gray-700 mb-2">Policy & Process Management</h3>
                <p class="text-gray-500">Link policies to risks, obligations, and controls for full traceability.</p>
              </div>
        
              <div class="bg-gray-50 p-6 rounded-2xl shadow-sm hover:shadow-md transition">
                <h3 class="font-semibold text-lg text-gray-700 mb-2">Dashboards & Reporting</h3>
                <p class="text-gray-500">Real-time reporting for executives.</p>
              </div>
            </div>
          </div>
        </section>
    </div>
    
    <div class="px-[30px] sm:px-[100px] py-16 mt-[20px]">
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-[30px] py-[50px]">
        <div class="flex items-center justify-center">
           <img src="/assets/images/home/when.jpeg" class="w-full h-[400px] object-cover rounded-[5px] object-center" />
        </div>
        <div class="flex items-center">
          <div>
            <h1 class="font-bold text-3xl mb-[20px]">Introducing 20X improvement with our Compliance AI Agent</h1>
            <h3 class="mb-[10px]">Compliance professionals struggle with:</h3>
            <ul class="list-disc list-inside pl-[10px]">
              <li><strong>Regulatory Complexity:</strong> Keeping up with changing laws.</li>
              <li><strong>Manual Processes:</strong> Time-consuming audits, policy drafting, and risk assessments.</li>
              <li><strong>False Positives in Monitoring:</strong> High noise in transaction monitoring &amp; employee surveillance.</li>
              <li><strong>Documentation Burden:</strong> Managing policies, training, and evidence for audits.</li>
              <li><strong>Fraud &amp; Insider Risk:</strong> Detecting anomalies in financial or operational data.</li>
            </ul>
            
            <div class='pt-[30px]'>Join the waitlist and be one of the first to try it out!</div>
            <a class="mt-[10px] btn btn-primary w-[max-content] flex justify-center items-center gap-[5px]" href="/waitlist/#join">Join Waitlist</a>
            <!--<div class='justify-end flex'>-->
            <!--</div>-->
          </div>
        </div>
      </div>
    </div>
    
    <div class="px-[30px] sm:px-[100px] py-16 mt-[20px] !hidden">
        <section class="bg-white py-20 px-6 sm:px-10 lg:px-24">
          <div class="max-w-7xl mx-auto space-y-20">
            <!-- Hero Text -->
            <div class="text-center">
              <h2 class="text-4xl font-bold text-gray-800">Why RiskSafe?</h2>
              <p class="mt-4 text-lg text-gray-500 max-w-3xl mx-auto">
                RiskSafe simplifies operations, scales forms, and ensures data accuracy for better decision-making.
              </p>
            </div>
        
            <!-- Highlights Section -->
            <div class="grid gap-10 lg:grid-cols-3 sm:grid-cols-2">
              <div class="p-6 rounded-2xl shadow-md border hover:shadow-lg transition bg-gradient-to-br from-white to-gray-50">
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No More Disconnected Tools</h3>
                <p class="text-gray-500">No spreadsheets. No $100K+ licenses. RiskSafe unifies all GRC functions so your team can focus on growth and protection.</p>
              </div>
        
              <div class="p-6 rounded-2xl shadow-md border hover:shadow-lg transition bg-gradient-to-br from-white to-gray-50">
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Navigate with Ease</h3>
                <p class="text-gray-500">Our intuitive interface ensures visibility and control over risks, obligations, incidents, controls, and policies.</p>
              </div>
        
              <div class="p-6 rounded-2xl shadow-md border hover:shadow-lg transition bg-gradient-to-br from-white to-gray-50">
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Flexible Form System</h3>
                <p class="text-gray-500">Adaptable modules let you customize and scale forms easily to fit every workflow.</p>
              </div>
        
              <div class="p-6 rounded-2xl shadow-md border hover:shadow-lg transition bg-gradient-to-br from-white to-gray-50">
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Link Across Modules</h3>
                <p class="text-gray-500">Input and connect Risk, Compliance, Controls, KRIs, and more to streamline your risk strategy.</p>
              </div>
        
              <div class="p-6 rounded-2xl shadow-md border hover:shadow-lg transition bg-gradient-to-br from-white to-gray-50">
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Efficient Data Management</h3>
                <p class="text-gray-500">Organize and present data with precision. From heat maps to reports—make decisions backed by real-time insight.</p>
              </div>
        
              <div class="p-6 rounded-2xl shadow-md border hover:shadow-lg transition bg-gradient-to-br from-white to-gray-50">
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Built for Risk Officers</h3>
                <p class="text-gray-500">Trusted by financial, healthcare, and professional service teams. Built by compliance experts for real-world needs.</p>
              </div>
            </div>
          </div>
        </section>
    </div>

    <div class="mt-[20px] hidden">
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
            
            <div class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-lg transition-all">
        <div class="p-8">
          <!-- Example Icon: Shield -->
          <svg class="w-8 h-8 text-blue-500 mb-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6l8 4-8 4-8-4 8-4z" /></svg>
          <h3 class="text-gray-800 text-xl font-semibold mb-3">Tailored Risk Profiles</h3>
          <p class="text-gray-500 text-sm leading-relaxed">
            Risk profiles that adapt to your business landscape and risk appetite.
          </p>
        </div>
      </div>

      <!-- More Feature Cards -->
      <div class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-lg transition-all">
        <div class="p-8">
          <svg class="w-8 h-8 text-blue-500 mb-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3v1.5m4.5-1.5v1.5m3.72 2.47l1.06 1.06M4.97 6.97l1.06 1.06m12.02 7.92l1.06 1.06M4.97 17.03l1.06-1.06M3 12h1.5m15 0h1.5m-4.22 4.22A7.5 7.5 0 1112 4.5a7.5 7.5 0 018.28 8.28z" /></svg>
          <h3 class="text-gray-800 text-xl font-semibold mb-3">Industry-Based Compliance</h3>
          <p class="text-gray-500 text-sm leading-relaxed">
            Preloaded obligations tailored to your sector. Build your custom frameworks.
          </p>
        </div>
      </div>

      <div class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-lg transition-all">
        <div class="p-8">
          <svg class="w-8 h-8 text-blue-500 mb-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-6a3 3 0 013-3h3m3 3l-3-3m3 3l-3 3" /></svg>
          <h3 class="text-gray-800 text-xl font-semibold mb-3">Incident Management</h3>
          <p class="text-gray-500 text-sm leading-relaxed">
            Log, track and resolve issues with full audit trails built-in.
          </p>
        </div>
      </div>

      <div class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-lg transition-all">
        <div class="p-8">
          <svg class="w-8 h-8 text-blue-500 mb-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18" /></svg>
          <h3 class="text-gray-800 text-xl font-semibold mb-3">Control Management</h3>
          <p class="text-gray-500 text-sm leading-relaxed">
            Design and test controls, assign owners, and automate attestations.
          </p>
        </div>
      </div>

      <div class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-lg transition-all">
        <div class="p-8">
          <svg class="w-8 h-8 text-blue-500 mb-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6M5 8h14" /></svg>
          <h3 class="text-gray-800 text-xl font-semibold mb-3">Treatment Plans</h3>
          <p class="text-gray-500 text-sm leading-relaxed">
            Assign and monitor mitigation actions across teams with ease.
          </p>
        </div>
      </div>

      <div class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-lg transition-all">
        <div class="p-8">
          <svg class="w-8 h-8 text-blue-500 mb-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 11V6a1 1 0 012 0v5h4a1 1 0 010 2h-4v5a1 1 0 01-2 0v-5H7a1 1 0 010-2h4z" /></svg>
          <h3 class="text-gray-800 text-xl font-semibold mb-3">Key Risk Indicators (KRIs)</h3>
          <p class="text-gray-500 text-sm leading-relaxed">
            Track early warning signs with configurable thresholds.
          </p>
        </div>
      </div>

      <div class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-lg transition-all">
        <div class="p-8">
          <svg class="w-8 h-8 text-blue-500 mb-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7" /></svg>
          <h3 class="text-gray-800 text-xl font-semibold mb-3">Business Continuity Planning</h3>
          <p class="text-gray-500 text-sm leading-relaxed">
            Maintain operational resilience with plan templates and testing workflows.
          </p>
        </div>
      </div>

      <div class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-lg transition-all">
        <div class="p-8">
          <svg class="w-8 h-8 text-blue-500 mb-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6M9 16h6M9 8h6" /></svg>
          <h3 class="text-gray-800 text-xl font-semibold mb-3">Policy & Process Management</h3>
          <p class="text-gray-500 text-sm leading-relaxed">
            Link policies to risks, obligations, and controls for full traceability.
          </p>
        </div>
      </div>

      <div class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-lg transition-all">
        <div class="p-8">
          <svg class="w-8 h-8 text-blue-500 mb-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" /></svg>
          <h3 class="text-gray-800 text-xl font-semibold mb-3">Dashboards & Reporting</h3>
          <p class="text-gray-500 text-sm leading-relaxed">
            Real-time reporting for executives and management.
          </p>
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
          Built for Compliance and Risk Officers
        </h1>
        
         <div class="sm:w-[500px] w-full text-center">
              <p>
            Trusted by leading teams in Financial Services, Healthcare, Accounting and more. Designed by compliance experts with over a decade of experience.
          </p>
        </div> 
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
