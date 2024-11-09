<?php
    session_start();
    $file_dir = '../../';
    $inpage_dir = '../';

    if (isset($_SESSION["AdminloggedIn"]) == true || isset($_SESSION["AdminloggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: login');
    }
  
    $message = [];
    include $file_dir.'layout/db.php';
    include $inpage_dir.'ajax/admin.php';
    include $file_dir.'layout/superadmin_config.php';

    if(isset($_POST['new-module'])){
        $title = sanitizePlus($_POST['title']);
        if($title && $title != ''){
            $module_id = secure_random_string(10);
            $query = "INSERT INTO as_compliance (title, compliance_id) VALUES ('$title', '$module_id')";
            $inserted = $con->query($query);  
            if($inserted){
                array_push($message, 'Module Created Successfully!!');
            }else{
              array_push($message, 'Error 502: Error Creating Module!!');  
            }
        }else{
            array_push($message, 'Error 402: Missing Module Title!!');
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Create Module | <?php echo $siteEndTitle; ?></title>
  <?php require $file_dir.'layout/general_css.php' ?>
  <link rel="stylesheet" href="<?php echo $file_dir; ?>assets/css/admin.custom.css">
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
                    <div class="row">
                      <div class="col-12">
                        <?php require $file_dir.'layout/alert.php' ?>
                        <div class="card">
                          <div class="card-header">
                            <h4>New Module:</h4>
                            <div class="card-header-form">
                              <a href="index" class="btn btn-outline-primary"><i class="fas fa-arrow-left"></i> Back</a>
                            </div>
                          </div>
                          <div class="card-body">
                            <form method='post' style='width:100%;'>
                                <div class="form-group">
                                    <label for="t">Module Title:</label>
                                    <input id="t" type="text" name="title" class="form-control" required>
                                </div>
                                <div class="form-group text-right">
                                    <button type="submit" class="btn btn-lg btn-primary btn-icon icon-left" name='new-module'><i class='fa fa-plus'></i> Create Module</button>
                                </div>
                              </form>
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
            </section>
        </div>
        <?php require $file_dir.'layout/footer.php' ?>
        </footer>
        </div>
    </div>
    <?php require $file_dir.'layout/general_js.php' ?>
    <style>
        .card{
          padding: 10px;
        }
        .user-description{
            margin-bottom: 10px;
        }
        hr{
            border: 1px solid white !important;
            display: block !important;
        }
        .main-footer{
            margin-top: 0px !important;
        }
    </style>
</body>
</html>