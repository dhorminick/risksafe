    <?php
        if ($file_dir == '' || $file_dir == null || !$file_dir) {
            $file_dir = null;
        } else {
            $file_dir = $file_dir;
        }
        
    ?>
    <!-- General JS Scripts -->
    <script src="<?php echo $file_dir; ?>assets/js/app.min.js"></script>
    <!-- JS Libraies -->
    <script src="<?php echo $file_dir; ?>assets/js/scripts.js"></script>
    <!-- Custom JS File -->
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