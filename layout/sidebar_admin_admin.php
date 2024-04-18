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
            <img src="<?php echo $file_dir; ?>assets/images/logo-edit.jpg" class='logo' alt="LOGO*">
            <a href="<?php echo $accnt_dir; ?>"> 
            </a>
            </div>
            <ul class="sidebar-menu">
            
            <li class="menu-header">Account</li>
            <li><a class="nav-link" href="<?php echo $accnt_dir; ?>/admin-main/"><i data-feather="home"></i><span>Dashboard</span></a></li>
            <li><a class="nav-link" href="<?php echo $accnt_dir; ?>profile"><i data-feather="settings"></i><span>Profile</span></a></li>
            

            <li class="menu-header">Clients</li>
            <li><a class="nav-link" href="<?php echo $accnt_dir; ?>clients"><i data-feather="list"></i><span>Clients List</span></a></li>
            <li><a class="nav-link" href="<?php echo $accnt_dir; ?>clients"><i data-feather="zoom-in"></i><span>Search Clients</span></a></li>
            <li><a class="nav-link" href="<?php echo $accnt_dir; ?>import_csv"><i data-feather="zoom-in"></i><span>Import Data (CSV)</span></a></li>
            <li><a class="nav-link" href="<?php echo $accnt_dir; ?>import_excel"><i data-feather="zoom-in"></i><span>Import Data (Excel)</span></a></li>
            
            <li class="menu-header">Mailing</li>
            <li><a class="nav-link" href="<?php echo $accnt_dir; ?>tickets"><i data-feather="alert-triangle"></i><span>Open Tickets</span></a></li>
            <li><a class="nav-link" href="<?php echo $accnt_dir; ?>tickets"><i data-feather="zoom-in"></i><span>Search Tickets</span></a></li>
            <li><a class="nav-link" href="<?php echo $accnt_dir; ?>mail"><i data-feather="mail"></i><span>Send Mail</span></a></li>
            <li><a class="nav-link" href="<?php echo $accnt_dir; ?>mail-all"><i data-feather="mail"></i><span>Send Broadcast Mail</span></a></li>
            
            
            </ul>
        </aside>
    </div>