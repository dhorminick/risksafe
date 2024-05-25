<?php
    session_start();
    $file_dir = '../../';

    if (isset($_SESSION["loggedIn"]) == true || isset($_SESSION["loggedIn"]) === true) {
        $signedIn = true;
    } else {
        header('Location: '.$file_dir.'auth/sign-in?r=/account/tickets');
        exit();
    }
  
    $message = [];
    include $file_dir.'layout/db.php';
    include $file_dir.'layout/admin__config.php';

    $ticket_id = secure_random_string(10);
    $u_name = ucwords($_SESSION['u_name']);

    if(isset($_POST["new-ticket"])){
        $priority = sanitizePlus($_POST['priority']);
        $category = sanitizePlus($_POST['category']);
        $subject = sanitizePlus($_POST['subject']);
        $ticket_created_on = date("Y-m-d H:i:s");
        $ticket_modified_on = date("Y-m-d H:i:s");

        if($subject == null || !$subject || $subject == ''){
            $subject = "New Ticket Created: ".strtoupper($ticket_id);
        }

        if($category == null || !$category || $category == ''){
            $category = "No Selected Category";
        }

        if($priority == null || !$priority || $priority == ''){
            $priority = "low";
        }

        $t_message = trim($_POST['message']);

        $t_message = str_replace( 'font-family' , 'error-style', $t_message );
        $t_message = str_replace( 'font-size' , 'error-style', $t_message );
        $t_message = str_replace( 'background-color' , 'error-style', $t_message );
        $t_message = str_replace( 'text-align' , 'error-style', $t_message );
        $t_message = str_replace( 'h1' , 'b', $t_message );
        $t_message = str_replace( 'h2' , 'b', $t_message );
        $t_message = str_replace( 'h3' , 'b', $t_message );
        $t_message = str_replace( 'h4' , 'b', $t_message );
        $t_message = str_replace( 'h5' , 'b', $t_message );
        $t_message = str_replace( 'h6' , 'b', $t_message );
        $t_message = str_replace( '<p>' , '%p%', $t_message );
        $t_message = str_replace( '\\' , '%\\%', $t_message );
        $t_message = str_replace( '</p>', '%/p%', $t_message );
        $t_message = str_replace( '<b>' , '%b%', $t_message );
        $t_message = str_replace( '</b>', '%/b%', $t_message );
        $t_message = str_replace( '<u>' , '%u%', $t_message );
        $t_message = str_replace( '</u>', '%/u%', $t_message );
        $t_message = str_replace( '\'' , '"', $t_message );

        $t_message = sanitizePlus($t_message);

        $tickets = [];
        $t__message = array(array(
            'name' => $u_name,
            'role' => 'client',
            'message' => $t_message,
            'date' => date("Y-m-d H:i:s")
        ));
        
        $t__message = array_merge($tickets, $t__message);
        $t__message = serialize($t__message);

        $t_date = date("Y-m-d H:i:s");
        
        if (!$t_message || $t_message == null || $t_message == '') {
            array_push($message, 'Error 402: Incomplete Data Parameters!!');
        } else {
            # code...
            $query = "INSERT INTO tickets (ticket_id, priority, category, subject, message, t_datetime_created, t_datetime_modified, c_id, status) VALUES ('$ticket_id', '$priority', '$category', '$subject', '$t__message', '$t_date', '$t_date', '$company_id', 'open')";
            if ($con->query($query)) {
                header('Location: tickets?id='.$ticket_id);
            } else {
                array_push($message, 'Error 502: Error Creating Ticket!!');
            }
        }		
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta
   content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Create Ticket | <?php echo $siteEndTitle; ?></title>
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
        <?php require $file_dir.'layout/header.php' ?>
        <?php require $file_dir.'layout/sidebar_admin.php' ?>
        <!-- Main
         Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-body">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="subtitle d-inline">Open New Ticket</h3>
                            <a class="btn btn-primary btn-icon icon-left header-a" href="new-ticket"><i class="fas fa-plus"></i> New Ticket</a>
                        </div>
                        <div class="card-body">
                            <?php require $file_dir.'layout/alert.php' ?>
                            <form method="post">
                                <div class="form-group">
                                    <label for="subject">Ticket Subject :</label>
                                    <input type="text" name="subject" class="form-control" placeholder="Ticket Subject:" required>
                                </div>

                                <div class="row custom-row">
                                    <div class="col-12 col-lg-5 form-group">
                                        <label for="priority">Ticket Priority :</label>
                                        <select name="priority" class="form-control" required>
                                            <option value="low" selected>Low</option>
                                            <option value="medium">Medium</option>
                                            <option value="high">High</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-lg-7 form-group">
                                        <label for="priority">Ticket Category :</label>
                                        <select name="category" class="form-control" required>
                                            <option value="sales" selected>Sales</option>
                                            <option value="technical">Technical</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="message">Ticket Message :</label>
                                    <textarea class="summernote-simple" name="message" required></textarea>
                                </div>
                                <div class="form-group">
                                    <button type="submit" name="new-ticket" class="btn btn-primary btn-icon icon-left">Create Ticket</button>
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
    <style>
        .card{
            padding:10px;
        }
        .note-editable.card-block {
            min-height: 200px !important;
            max-height: 250px !important;
            overflow: auto;
            padding: 10px !important;
        }
        .note-toolbar-wrapper {
            display: none !important;
        }
        .note-editable.card-block p {
            line-height: 5px !important;
        }
        .note-statusbar {
            display: none !important;
        }
        .note-editor.note-frame.card {
            width: 100% !important;
        }
    </style>
    <?php require $file_dir.'layout/general_js.php' ?>
    <script src="<?php echo $file_dir; ?>assets/bundles/summernote/summernote-bs4.js"></script>
</body>
</html>