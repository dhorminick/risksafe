<?php
    session_start();
    $file_dir = '../';

    if (isset($_SESSION["AdminloggedIn"]) == true || isset($_SESSION["AdminloggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: login');
        exit();
    }
  
    $message = [];
    include $file_dir.'layout/db.php';
    include $file_dir.'layout/superadmin_config.php';

    if(isset($_POST["mail"])){
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta
   content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Send Mail | <?php echo $siteEndTitle; ?></title>
  <?php require $file_dir.'layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/admin.custom.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/bundles/summernote/summernote-bs4.css">
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/footer.custom.css">
</head>

<body>
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <?php require $file_dir.'layout/admin_header.php' ?>
        <?php require $file_dir.'layout/sidebar_admin_admin.php' ?>
        <!-- Main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-body">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="subtitle d-inline">Send Mail</h3>
                            <a class="btn btn-primary btn-icon icon-left header-a" href="/"><i class="fas fa-arrow-left"></i> Back</a>
                        </div>
                        <div class="card-body">
                            <?php require $file_dir.'layout/alert.php' ?>
                            <form method="post">
                                <div class="form-group">
                                    <label for="recipient">Mail Recipient :</label>
                                    <input type="text" name="recipient" class="form-control" placeholder="Mail Recipient:" required>
                                </div>

                                <div class="form-group">
                                    <label for="subject">Mail Subject :</label>
                                    <input type="text" name="subject" class="form-control" placeholder="Mail Subject:" required>
                                </div>

                                <div class="form-group">
                                    <label for="message">Mail Message :</label>
                                    <textarea class="summernote-simple" name="message" required></textarea>
                                </div>
                                <div class="form-group">
                                    <button type="submit" name="mail" class="btn btn-primary btn-icon icon-left">Send Mail</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </section>
        </div>
        </div>
        <?php require $file_dir.'layout/footer.php' ?>
        </footer>
        </div>
    </div>
    <?php require $file_dir.'layout/general_js.php' ?>
    <script src="<?php echo $file_dir; ?>assets/bundles/summernote/summernote-bs4.js"></script>
</body>
</html>