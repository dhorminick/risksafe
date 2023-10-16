    <?php
        if ($file_dir != '' || $file_dir != null) {
            $file_dir = $file_dir;
        } else {
            $file_dir = null;
        }
        
    ?>
    <div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
            <div class="sidebar-brand">
            <a href="../admin/index.html"> <img alt="image" src="<?php echo $file_dir; ?>assets/img/logo.png" class="header-logo" /> <span
                class="logo-name">Otika</span>
            </a>
            </div>
            <ul class="sidebar-menu">
            <li class="menu-header">Main</li>
            <li class="dropdown">
                <a href="../admin/index.html" class="nav-link"><i data-feather="monitor"></i><span>Dashboard</span></a>
            </li>
        
            <li class="menu-header">Assessments</li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                    data-feather="shopping-bag"></i><span>Assessments</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="../admin/assessments/new-assessment.php">New Risk Assessment</a></li>
                    <li><a class="nav-link" href="../admin/assessments/all.php">All Risk Assessments</a></li>
                    <li><a class="nav-link" href="../admin/assessments/anti-money-laundering.php">Anti Money Laundering</a></li>
                </ul>
            </li>

            <li class="menu-header">Compliance</li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                    data-feather="shopping-bag"></i><span>Compliance Standards</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="../admin/compliances/all.php">All Compliances</a></li>
                    <li><a class="nav-link" href="../admin/compliances/applicable-policy.php">Applicable Policy</a></li>
                    <li><a class="nav-link" href="../admin/compliances/applicable-procedure.php">Applicable Procedure</a></li>
                </ul>
            </li>

            <li class="menu-header">Monitoring</li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                    data-feather="shopping-bag"></i><span>Monitoring</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="../admin/monitoring/new-audit.php">Create New Audit</a></li>
                    <li><a class="nav-link" href="../admin/monitoring/audits.php">All Audits of Control</a></li>
                    <li><a class="nav-link" href="../admin/monitoring/new-treatment.php">Create New Treatment</a></li>
                    <li><a class="nav-link" href="../admin/monitoring/treatments.php">All Treatments</a></li>
                </ul>
            </li>

            <li class="menu-header">Business</li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                    data-feather="shopping-bag"></i><span>Business Continuity</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="../admin/business/incidents.php">Incidents</a></li>
                    <li><a class="nav-link" href="../admin/business/business-impact-analysis-report.php">Business Impact Analysis</a></li>
                    <li><a class="nav-link" href="../admin/business/insurances.php">Insurances</a></li>
                </ul>
            </li>

            <li class="menu-header">Reports</li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                    data-feather="shopping-bag"></i><span>Business Continuity</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="../admin/reports/risk-reports.php">Risk Reports</a></li>
                    <li><a class="nav-link" href="../admin/reports/controls-dashboard.php">Controls Dashboard</a></li>
                    <li><a class="nav-link" href="../admin/reports/incident-report.php">Incident Report</a></li>
                    <li><a class="nav-link" href="../admin/reports/business-impact-analysis-report.php">Business Impact Analysis</a></li>
                    <li><a class="nav-link" href="../admin/reports/treatment-status-report.php">Treatment Status Report</a></li>
                </ul>
            </li>

            <li class="menu-header">Account</li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                    data-feather="shopping-bag"></i><span>My Account</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="../admin/account/profile.php">Profile</a></li>
                    <li><a class="nav-link" href="../admin/account/payments.php">Payments</a></li>
                    <li><a class="nav-link" href="../admin/account/instructions.php">RiskSafe Help</a></li>
                    <li><a class="nav-link" href="../admin/account/business-context.php">Business Context</a></li>
                    <li><a class="nav-link" href="../admin/account/users.php">All Users</a></li>
                </ul>
            </li>

            <li class="active"><a class="nav-link" href="../admin/blank.html"><i data-feather="file"></i><span>Log Out</span></a></li>
            
            </ul>
        </aside>
    </div>