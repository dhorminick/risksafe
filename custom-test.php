<?php
    include 'layout/variablesandfunctions.php';
    // $array = array(array(
    //     'etiketochukwu@gmail.com' => array(
    //         'id' => '53f8a8j',
    //         'email' => 'etiketochukwu@gmail.com',
    //         'password' => 'test',

    //     ),
    //     'tochukwusamuel@gmail.com' => array(
    //         'id' => 'a4zr4a8j',
    //         'email' => 'tochukwusamuel@gmail.com',
    //         'password' => 'test2',

    //     ),
    //     'etiketochukwusamuel@gmail.com' => array(
    //         'id' => 'yatdt4s',
    //         'email' => 'etiketochukwusamuel@gmail.com',
    //         'password' => 'test3',

    //     ),
    // ));
    // $array = array(
    //     array(
    //         'id' => '53f8a8j',
    //         'email' => 'etiketochukwu@gmail.com',
    //         'password' => 'test',

    //     ),
    //     array(
    //         'id' => 'a4zr4a8j',
    //         'email' => 'tochukwusamuel@gmail.com',
    //         'password' => 'test2',

    //     ),
    //     array(
    //         'id' => 'yatdt4s',
    //         'email' => 'etiketochukwusamuel@gmail.com',
    //         'password' => 'test3',

    //     ),
    // );
    $array = [];
    $array1 = array(array(
            'id' => '53f8a8j',
            'email' => 'etiketochukwu@gmail.com',
            'password' => 'test',

    ));
    $array2 = array(array(
            'id' => 'a4zr4a8j',
            'email' => 'tochukwusamuel@gmail.com',
            'password' => 'test2',

    ));

    $array3 = array(array(
        'id' => 'yatdt4s',
        'email' => 'etiketochukwusamuel@gmail.com',
        'password' => 'test3',
        
    ));
    $arrayAll = array_merge($array1, $array2, $array3);
    // var_dump($arrayAll);
    // echo $arrayAll[0]['email'];

//     $cars = array
//   (
//   array("Volvo",22,18),
//   array("BMW",15,13),
//   array("Saab",5,2),
//   array("Land Rover",17,15)
//   );
    
// for ($row = 0; $row < 4; $row++) {
//   echo "<p><b>Row number $row</b></p>";
//   echo "<ul>";
//   for ($col = 0; $col < 3; $col++) {
//     echo "<li>".$cars[$row][$col]."</li>";
//   }
//   echo "</ul>";
// }
    
    function in_array_custom($needle, $haystack, $strict = true){
        foreach ($haystack as $items){
            if (($strict ? $items === $needle : $items == $needle) || (is_array($items) && in_array_custom($needle, $items, $strict))){
                return true;
            }
        }
    
        return false;
    }
    if (isset($_POST['signup'])) {
        $found = false;
        $email = $_POST['email'];
        $password = $_POST['password'];
        $isInArray = in_array_custom($email, $arrayAll) ? 'found' : 'notfound';
        if($isInArray === 'found'){
            for ($row = 0; $row < 3; $row++) {
                // echo 'row - '.$row.', email = '.$arrayAll[$row]['email'].', password = '.$arrayAll[$row]['password'];
                if($arrayAll[$row]['email'] == $email && $arrayAll[$row]['password'] == $password){
                    $found = true;
                    $rowNumber = $row;
                }
            }

            if($found && $found == true){
                echo 'found';
                echo $rowNumber;
            }else{
                echo 'invalid credentials';
            }          
        }else{
            echo 'not in array';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="post">
        <input type="text" name="email" placeholder="email">
        <input type="text" name="password" placeholder="password">
        <button type="submit" name="signup">sign in</button>
    </form>
</body>
</html>