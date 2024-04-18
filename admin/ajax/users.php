<?php
    session_start();
    // include '../../layout/admin_config.php';
    include '../../layout/db.php';
    $company_id = $_SESSION["company_id"];
    include '../../layout/variablesandfunctions.php';
    function getToArray($array){
        $getArray = [];

        $convertArray = explode("&", $array);
        for ($i=0; $i < count($convertArray); $i++) { 
            $keyValue = explode('=', $convertArray[$i]);
            $getArray[$keyValue [0]] = $keyValue [1];
        }

        return $getArray;
    }
    if (isset($_POST['deleteUser'])) {
        $value = $_POST["deleteUser"];
        $getArray = getToArray($value);
        $email = sanitizePlus($getArray["email"]);
        $id = strtolower(sanitizePlus($getArray["id"]));

        $GetPrevPayment = "SELECT * FROM users WHERE company_id = '$company_id'";
        $PrevPayment = $con->query($GetPrevPayment);
        if ($PrevPayment->num_rows > 0) {
            $p_row = $PrevPayment->fetch_assoc();
            $details = $p_row['company_users'];
            $details = unserialize($details);
            $user_count = count($details);

            $isInArray = in_array_custom($id, $details) ? 'found' : 'notfound';
            if($isInArray === 'found'){
                for ($rowArray = 0; $rowArray < $user_count; $rowArray++) {
                    if($details[$rowArray]['id'] == $id){
                        $found = true;
                        $rowNumber = $rowArray;
                    }
                }
                if($found && $found == true){
                    unset($details[$rowNumber]);
                    $details = serialize($details);
                    #update
                    $GetPrevPayment = "UPDATE users SET company_users = '$details' WHERE company_id = '$company_id'";
                    $PrevPayment = $con->query($GetPrevPayment);
                    if ($PrevPayment) {
                        #success
                        echo '
                            <script src="assets/bundles/izitoast/js/iziToast.min.js"></script>
                            <script>
                            $(".toastr-0").click(function () {
                                iziToast.success({
                                    title: $(this).attr("title"),
                                    message: $(this).attr("message"),
                                    position: "topRight",
                                });
                            });
                            setTimeout(function () {
                                $(".res").removeClass("show");
                                $(".res").html("");
                            }, 2000);
                            </script>
                            <span class="toastr-0" title="Success:" message="User Deleted Successfully!!"></span>
                            <script>$(".toastr-0").click();</script>
                        ';
                    }else{
                        #error
                        echo '
                            <script src="assets/bundles/izitoast/js/iziToast.min.js"></script>
                            <script>
                            $(".toastr-error-0").click(function () {
                                iziToast.error({
                                    title: $(this).attr("title"),
                                    message: $(this).attr("message"),
                                    position: "topRight",
                                });
                            });
                            setTimeout(function () {
                                $(".res").removeClass("show");
                                $(".res").html("");
                            }, 2000);
                            </script>
                            <span class="toastr-error-0" title="Error 502:" message="Error Deleting User!!"></span>
                            <script>$(".toastr-error-0").click();</script>
                        ';
                    }
                }else{
                    // array_push($message, 'Error 402: User Not Found!!');
                    // $iderror = true;
                    echo '
                        <script src="assets/bundles/izitoast/js/iziToast.min.js"></script>
                        <script>
                        $(".toastr-error-1").click(function () {
                            iziToast.error({
                                title: $(this).attr("title"),
                                message: $(this).attr("message"),
                                position: "topRight",
                            });
                        });
                        setTimeout(function () {
                            $(".res").removeClass("show");
                            $(".res").html("");
                        }, 2000);
                        </script>
                        <span class="toastr-error-1" title="Error 402:" message="Error 402: User Not Found!!"></span>
                        <script>$(".toastr-error-1").click();</script>
                    ';
                    // $error_message = 'Error 402: User Not Found!!'; 
                }        
                  
            }else{
                // array_push($message, "Error 402: User Doesn't Exist!");
                // $iderror = true;
                // $error_message = "Error 402: User Doesn't Exist!!"; 
                echo '
                    <script src="assets/bundles/izitoast/js/iziToast.min.js"></script>
                    <script>
                    $(".toastr-error-2").click(function () {
                        iziToast.error({
                            title: $(this).attr("title"),
                            message: $(this).attr("message"),
                            position: "topRight",
                        });
                    });
                    setTimeout(function () {
                        $(".res").removeClass("show");
                        $(".res").html("");
                    }, 2000);
                    </script>
                    <span class="toastr-error-2" title="Error 402:" message="User Does Not Exist!!"></span>
                    <script>$(".toastr-error-2").click();</script>
                ';
                // var_dump($getArray);
                // var_dump($details);
            }
        }else{

        }
    }
    
    if (isset($_POST['deleteNotif'])) {
        $value = $_POST["deleteNotif"];
        $getArray = getToArray($value);
        $id = strtolower(sanitizePlus($getArray["id"]));

        $GetPrevPayment = "DELETE FROM notification WHERE c_id = '$company_id' AND crc32(id) = '$id'";
        $PrevPayment = $con->query($GetPrevPayment);
        if ($PrevPayment) {
            echo '
                            <script src="../../assets/bundles/izitoast/js/iziToast.min.js"></script>
                            <script>
                            $(".toastr-0").click(function () {
                                iziToast.success({
                                    title: $(this).attr("title"),
                                    message: $(this).attr("message"),
                                    position: "topRight",
                                });
                            });
                            setTimeout(function () {
                                $(".res").removeClass("show");
                                $(".res").html("");
                            }, 2000);
                            </script>
                            <span class="toastr-0" title="Success:" message="Notification Deleted Successfully!!"></span>
                            <script>$(".toastr-0").click();</script>
                        ';
        }else{
            echo '
                            <script src="../../assets/bundles/izitoast/js/iziToast.min.js"></script>
                            <script>
                            $(".toastr-error-0").click(function () {
                                iziToast.error({
                                    title: $(this).attr("title"),
                                    message: $(this).attr("message"),
                                    position: "topRight",
                                });
                            });
                            setTimeout(function () {
                                $(".res").removeClass("show");
                                $(".res").html("");
                            }, 2000);
                            </script>
                            <span class="toastr-error-0" title="Error 502:" message="Error Deleting Notification!!"></span>
                            <script>$(".toastr-error-0").click();</script>
                        ';
        }
    }
?>