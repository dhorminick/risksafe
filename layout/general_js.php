    <?php
        if ($file_dir == '' || $file_dir == null || !$file_dir) {
            $file_dir = null;
        } else {
            $file_dir = $file_dir;
        }
        
    ?>
    <link rel="preload" href="<?php echo $file_dir; ?>assets/js/app.min.js" as='script'>
    <link rel="preload" href="<?php echo $file_dir; ?>assets/js/scripts.js" as='script'>
    <link rel="preload" href="<?php echo $file_dir; ?>assets/js/custom.js" as='script'>
    
    <script src="<?php echo $file_dir; ?>assets/js/app.min.js"></script>
    <script src="<?php echo $file_dir; ?>assets/js/scripts.js"></script>
    <script src="<?php echo $file_dir; ?>assets/js/custom.js"></script>

    <script>
      $("#collapse-btn").on("click", function () {
        if ($("#collapse-i").attr('data-feather') == 'align-justify') {
          $("#collapse-i").attr('data-feather', 'x');
        } else {
          $("#collapse-i").attr('data-feather', 'align-justify');
        }
        
        $(".header-sm-links").toggle();
      });
    </script>