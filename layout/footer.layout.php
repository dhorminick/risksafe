<div>
      <footer
        class="shadow-md py-4 sm:py-[30px] z-1 px-4 sm:px-10 bg-white min-h-[70px] tracking-wide mt-[20px] border border-gray-300"
      >
        <div class="flex flex-col sm:flex-row justify-between mb-[30px]">
          <div class="about w-full sm:w-[50%]">
            <div class="font-bold mb-[5px]">About RiskSafe</div>
            <div class="text-[15px]">
              We provide comprehensive risk management services, from assessment
              and analysis to customized strategies, aimed at minimizing
              uncertainty and maximizing opportunities. Our proactive approach
              helps you stay ahead of threats and capitalize on emerging trends.
            </div>

            <div class="mt-[30px] sm:mb-[0px] mb-[30px]">
              <div id="newsletterResponse"></div>
              <form class="flex gap-[5px]" id="submitNewsletter">
                <input
                  name="useremail"
                  type="email"
                  required
                  placeholder="Sign up for newsletter"
                  class="flex-1 w-full text-sm text-gray-800 border border-gray-300 px-4 py-3 rounded-lg outline-[var(--primary)]"
                />
                <button
                  type="submit"
                  class="shadow-xl py-3 px-4 text-sm tracking-wide rounded-lg text-white bg-[var(--primary)] focus:outline-none"
                >
                  <i class="fa-solid fa-paper-plane"></i>
                </button>
              </form>
            </div>
          </div>
          <div
            class="about w-full mt-[10px] sm:mt-[0px] sm:w-[50%] flex flex-col sm:flex-row gap-[20px] sm:gap-[0px] sm:justify-around"
          >
            <div>
              <div class="font-bold mb-[5px]">Contact Us</div>
              <ul>
                <li>Email: jay@risksafe.co</li>
                <!-- <li>Phone: 0000-000-000</li> -->
                <li class="mt-[10px]">
                  <a class="bb" href="/risksafe-help">RiskSafe Help</a>
                </li>
              </ul>
            </div>

            <div>
              <div class="font-bold mb-[10px]">Company</div>
              <ul class="flex gap-[7px] flex-col">
                <li><a class="bb" href="/">Home</a></li>
                <li><a class="bb" href="/about-risksafe">About RiskSafe</a></li>
                <li><a class="bb" href="/contact-us">Contact Us</a></li>
                <li><a class="bb" href="/risksafe-resources">Resources</a></li>
                <li><a class="bb" href="/pricing">Pricing</a></li>
                <li class="mt-[10px]">
                  <a class="bb" href="/auth/sign-up">Get Started</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="flex gap-[10px] mb-[10px] w-full">
          Follow us on :
          <!-- <a href="https://facebook.com/risksafe" target="_blank">
            <i class="text-[20px] fa-brands fa-square-facebook"></i>
          </a> -->
          <a href="https://www.linkedin.com/company/risksafe-co/people/?viewAsMember=true" target="_blank"
            ><i class="text-[20px] fa-brands fa-linkedin"></i
          ></a>
        </div>
        <div
          class="flex justify-between border-[var(--primary)]-400 border-t-2 pt-[20px] pb-[-20px]"
        >
          <div>&copy; RiskSafe - <?php echo date("Y"); ?></div>
          <div class="flex sm:gap-3 gap-[0px] sm:flex-row flex-col">
            <a class="bb" href="/terms/terms-and-conditions.pdf">Terms of service</a>
            <a class="bb" href="/terms/privacy-policy.pdf">Privacy Policy</a>
            <a class="bb hidden sm:block" href="/risksafe-help">RiskSafe Help</a>
          </div>
        </div>
      </footer>
    </div>

    <script
  src="https://code.jquery.com/jquery-3.7.1.js"
  integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
  crossorigin="anonymous"
></script>
<script>
  var toggleOpen = document.getElementById("toggleOpen");
  var toggleClose = document.getElementById("toggleClose");
  var collapseMenu = document.getElementById("collapseMenu");

  function handleClick() {
    if (collapseMenu.style.display === "block") {
      collapseMenu.style.display = "none";
    } else {
      collapseMenu.style.display = "block";
    }
  }

  toggleOpen.addEventListener("click", handleClick);
  toggleClose.addEventListener("click", handleClick);

  $("#submitNewsletter").submit(function (event) {
    event.preventDefault();

    var formValues = $(this).serialize();
    $.post("/ajax/newsletter", {
      newsletter: formValues,
    }).done(function (data) {
      $("#newsletterResponse").html(data);

      setTimeout(function () {
        $("#submitNewsletter input").val("");
        $("#newsletterResponse").html("");
      }, 0);
    });
  });
</script>