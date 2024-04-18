<?php
    $linkedin = 'https://www.linkedin.com/company/risksafe-co/';
    $facebook = 'https://www.facebook.com/RiskSafeHQ';
    $twitter = '';
?>
    <nav class="navbar navbar-expand-lg main-navbar sticky">
        <div class="form-inline mr-auto custom">
          <ul class="navbar-nav mr-3">
            <!-- <li>RiskSafe - Risk Assessments</li> -->
            <li><a href='/'><img src="assets/images/logo-edit.jpg" class='logo' alt="LOGO*"></a></li>
          </ul>
        </div>
        <ul class="navbar-nav navbar-right breadcrumb header-lg">
            <li class="breadcrumb-item active"><a href="/">Home</a></li>
            <li class="breadcrumb-item"><a href="/about-us">About</a></li>
            <li class="breadcrumb-item"><a href="/resources">Resources</a></li>
            <li class="breadcrumb-item"><a href="/pricing">Pricing</a></li>
            <li class="breadcrumb-item"><a href="/contact-us">Contact Us</a></li>
            <li class="breadcrumb-item"><a href="/login">Account</a></li>
        </ul>
        <div class="header-sm">
          <ul class="navbar-nav navbar-right">
            <li><a href="#" class="nav-link nav-link-lg collapse-btn" id="collapse-btn"> <i id="collapse-i" data-feather="align-justify"></i></a></li>
          </ul>
        </div>
        <div class="header-sm-links">
          <div style="text-align: center;margin-bottom:10px;margin-top:-30px;"><img src="assets/images/logo-edit.jpg" class='logo' alt="LOGO*"></div>
          <ul>
            <li class=" active"><a href="/">Home</a></li>
            <li class=""><a href="/about-us">About</a></li>
            <li class=""><a href="/resources">Resources</a></li>
            <li class=""><a href="/pricing">Pricing</a></li>
            <li class=""><a href="/contact-us">Contact Us</a></li>
            <li class=""><a href="/login">Account</a></li>
          </ul>
        </div>
    </nav>
    <nav class="navbar navbar-expand-lg main-navbar sticky" style="display: none;">
        <div class="form-inline mr-auto">
          <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg
									collapse-btn"> <i data-feather="align-justify"></i></a></li>
            <li><a href="#" class="nav-link nav-link-lg fullscreen-btn">
                <i data-feather="maximize"></i>
              </a></li>
            <li>
              <form class="form-inline mr-auto">
                <div class="search-element">
                  <input class="form-control" type="search" placeholder="Search" aria-label="Search" data-width="200">
                  <button class="btn" type="submit">
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </form>
            </li>
          </ul>
        </div>
        <ul class="navbar-nav navbar-right breadcrumb">
            <li class="breadcrumb-item active"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">About</a></li>
            <li class="breadcrumb-item"><a href="#">Resources</a></li>
            <li class="breadcrumb-item"><a href="#">Products</a></li>
            <li class="breadcrumb-item"><a href="#">Contact Us</a></li>
            <li class="breadcrumb-item"><a href="#">Account</a></li>
        </ul>
    </nav>

    <style>
      @media (max-width: 767px) {
          .header-lg{
            display: none;
          }
          .header-sm{
            display: block !important;
          }
          .form-inline.mr-auto.custom{
            padding-left:0px !important;
          }
          .section-details {
              margin-left: 0px !important;
              margin-right: 0px !important;
          }
          .section-text.up{
            margin-top: -30px;
          }
        .section-headers {
            height: 300px;
        }
        .upsideup{
          
        }
        .show-sm{
          display: block;
        }
      }
      .card-header-h{
        text-align: center;
        width: 100%;
      }
      .custom-p p{
        margin-bottom: 5px !important;
      }
      .header-sm{
        display: none;
      }
      .form-inline.mr-auto.custom{
        padding-left:20px !important;
      }

      .btn{
        font-size: 15px !important;
      }
      .breadcrumb {
          background-color: inherit !important;
          margin-bottom: 0px !important;
      }
      .show-sm{
        display: none;
      }
      .header-sm-links {
        box-shadow: 0 4px 25px 0 rgba(0, 0, 0, 0.1);
        position: fixed;
        top: 0;
        height: 100%;
        width: 250px;
        background-color: #fff;
        z-index: 880;
        left: 0;
        padding-top: 40px;
        display: none;
      }
      
      .header-sm-links ul li{
        margin-bottom: 20px;
        width: 100%;
        text-align: center;
      }
      .header-sm-links ul{
        list-style-type: none;
        margin: 0;
        padding: 0;
      }
      
    </style>