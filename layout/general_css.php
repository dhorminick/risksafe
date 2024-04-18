    <?php
        if ($file_dir == '' || $file_dir == null || !$file_dir) {
            $file_dir = null;
        } else {
            $file_dir = $file_dir;
        }
        
    ?>
    <meta http-equiv="expires" content="Sun, 01 Jan 2014 00:00:00 GMT"/>
    <meta http-equiv="pragma" content="no-cache" />
    
    <link rel="preload" href="<?php echo $file_dir; ?>assets/css/app.min.css" as="style">
    <link rel="preload" href="<?php echo $file_dir; ?>assets/css/style.css" as="style">
    <link rel="preload" href="<?php echo $file_dir; ?>assets/css/components.css" as="style">
    <link rel="preload" href="<?php echo $file_dir; ?>assets/css/custom.css" as="style">
    <link rel="preload" href="<?php echo $file_dir; ?>assets/css/index.custom.css" as="style">
    
    <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/app.min.css">
    <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/style.css">
    <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/components.css">
    <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/custom.css">
    <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/index.custom.css">
    
    <link rel='shortcut icon' type='image/x-icon' href='<?php echo $file_dir; ?>assets/favicon/favicon.ico' />