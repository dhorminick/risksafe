    <?php
        if ($file_dir != '' || $file_dir != null) {
            $file_dir = $file_dir;
        } else {
            $file_dir = null;
        }
        if ($accnt_dir != '' || $accnt_dir != null) {
            $accnt_dir = $accnt_dir;
        } else {
            $accnt_dir = null;
        }
        
    ?>
    <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/admin.custom.css">
    <div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
            <div class="sidebar-brand">
            <!-- <a href="../index"> <img alt="image" src="<?php echo $file_dir; ?>assets/images/logo-edit.jpg" class="header-logo" /> <span
                class="logo-name">Otika</span>
            </a> -->
            <a href='/'><img src="<?php echo $file_dir; ?>assets/images/logo-edit.jpg" class='logo' alt="LOGO*"></a>
            <a href="../index"> 
                <!-- <img alt="image" src="<?php echo $file_dir; ?>assets/images/logo-edit.jpg" class="header-logo " /> -->
                <!-- <span class="logo-name logo"><img alt="image" src="<?php echo $file_dir; ?>assets/images/logo-edit.jpg" class="header-logo" /></span> -->
            </a>
            </div>
            <ul class="sidebar-menu">
            
            <li class="menu-header">Account</li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                    data-feather="shopping-bag"></i><span>My Account</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="../<?php echo $accnt_dir; ?>">Dashboard</a></li>
                    <li><a class="nav-link" href="../<?php echo $accnt_dir; ?>account/profile">Profile</a></li>
                    <li><a class="nav-link" href="../<?php echo $accnt_dir; ?>account/payments">Payments</a></li>
                    <li><a class="nav-link" href="../<?php echo $accnt_dir; ?>account/instructions">RiskSafe Help</a></li>
                    <li><a class="nav-link" href="../<?php echo $accnt_dir; ?>account/business-context">Business Context</a></li>
                    <li><a class="nav-link" href="../<?php echo $accnt_dir; ?>account/users">All Users</a></li>
                </ul>
            </li>
        
            <li class="menu-header">Assessments</li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                    data-feather="shopping-bag"></i><span>Assessments</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="../<?php echo $accnt_dir; ?>assessments/new-assessment">New Risk Assessment</a></li>
                    <li><a class="nav-link" href="../<?php echo $accnt_dir; ?>assessments/all">All Risk Assessments</a></li>
                    <li><a class="nav-link" href="../<?php echo $accnt_dir; ?>assessments/new-assessment?type=aml">Anti Money Laundering</a></li>
                    <!--<li><a class="nav-link" href="../<?php echo $accnt_dir; ?>assessments/anti-money-laundering">Anti Money Laundering</a></li>-->
                </ul>
            </li>

            <li class="menu-header">Compliance</li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                    data-feather="shopping-bag"></i><span>Compliance Standards</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="../<?php echo $accnt_dir; ?>compliances/all">All Compliances</a></li>
                    <li><a class="nav-link" href="../<?php echo $accnt_dir; ?>compliances/applicable-policy">Applicable Policy</a></li>
                    <li><a class="nav-link" href="../<?php echo $accnt_dir; ?>compliances/applicable-procedure">Applicable Procedure</a></li>
                </ul>
            </li>

            <li class="menu-header">Monitoring</li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                    data-feather="shopping-bag"></i><span>Monitoring</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="../<?php echo $accnt_dir; ?>monitoring/new-audit">Create New Audit</a></li>
                    <li><a class="nav-link" href="../<?php echo $accnt_dir; ?>monitoring/audits">All Audits of Control</a></li>
                    <li><a class="nav-link" href="../<?php echo $accnt_dir; ?>monitoring/new-treatment">Create New Treatment</a></li>
                    <li><a class="nav-link" href="../<?php echo $accnt_dir; ?>monitoring/treatments">All Treatments</a></li>
                </ul>
            </li>

            <li class="menu-header">Business</li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                    data-feather="shopping-bag"></i><span>Incident Management</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="../<?php echo $accnt_dir; ?>business/incidents">Incidents</a></li>
                    <li><a class="nav-link" href="../<?php echo $accnt_dir; ?>business/bia">Business Impact Analysis</a></li>
                    <li><a class="nav-link" href="../<?php echo $accnt_dir; ?>business/insurances">Insurances</a></li>
                </ul>
            </li>
            
            <li class="menu-header">Customs</li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                    data-feather="shopping-bag"></i><span>Customs</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="../<?php echo $accnt_dir; ?>customs/new-control">Create Custom Controls</a></li>
                    <li><a class="nav-link" href="../<?php echo $accnt_dir; ?>customs/controls">All Custom Controls</a></li>
                    <li><a class="nav-link" href="../<?php echo $accnt_dir; ?>customs/new-treatment">Create Customs Treatment</a></li>
                    <li><a class="nav-link" href="../<?php echo $accnt_dir; ?>customs/treatments">All Customs Treatments</a></li>
                </ul>
            </li>

            <li class="menu-header">Reports</li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                    data-feather="shopping-bag"></i><span>RiskSafe Reports</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="../<?php echo $accnt_dir; ?>reports/risk-reports">Risk Reports</a></li>
                    <li><a class="nav-link" href="../<?php echo $accnt_dir; ?>reports/control-reports">Controls Report</a></li>
                    <li><a class="nav-link" href="../<?php echo $accnt_dir; ?>reports/incident-report">Incident Report</a></li>
                    <li><a class="nav-link" href="../<?php echo $accnt_dir; ?>reports/business-impact-analysis-report">BIA Reports</a></li>
                    <li><a class="nav-link" href="../<?php echo $accnt_dir; ?>reports/treatment-report">Treatments Report</a></li>
                    <li><a class="nav-link" href="../<?php echo $accnt_dir; ?>reports/policy-report">Policy Report</a></li>
                </ul>
            </li>

            <li class="menu-header">Logout</li>
            <li class="active">
                <a class="nav-link" href="/logout"><i data-feather="file"></i><span>Log Out</span></a>
            </li>
            
            </ul>
        </aside>
    </div>