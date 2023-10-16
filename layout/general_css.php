    <?php
        if ($file_dir == '' || $file_dir == null || !$file_dir) {
            $file_dir = null;
        } else {
            $file_dir = $file_dir;
        }
        
    ?>
    <!-- General CSS Files -->
    <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/app.min.css">
    <!-- Template CSS -->
    <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/style.css">
    <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/components.css">
    <!-- Custom style CSS -->
    <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/custom.css">
    <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/index.custom.css">
    <!-- <link rel="stylesheet" href="assets/css/landing-page.css"> -->
    <link rel='shortcut icon' type='image/x-icon' href='<?php echo $file_dir; ?>assets/img/favicon.ico' />