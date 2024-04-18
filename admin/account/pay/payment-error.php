<?php $file_dir = '../../'; ?>
<?php if(isset($_GET['e']) && isset($_GET['e']) !== null){ ?>
<?php if($_GET['e'] == 'e2b98f4b-631c-4e7e'){ ?>
<html style='background-color:black;color:white;font-family:Rubik;'>
    <head><?php include $file_dir.'layout/general_css'; ?></head>
    <body style='background-color:black;color:white;font-family:Rubik;font-size:13px;'>{"message":"Access Denied!!" , "Error":"Transaction Could Not Initialize!!"}</body>
</html>
<?php }else if($_GET['e'] == '7a9f21e4-cdda-4a7d'){ ?>
<html style='background-color:black;color:white;font-family:Rubik;'>
    <head><?php include $file_dir.'layout/general_css'; ?></head>
    <body style='background-color:black;color:white;font-family:Rubik;font-size:13px;'>{"message":"Access Denied!!" , "Error":"Event Error!!"}</body>
</html>
<?php }else if($_GET['e'] == 'a0c3358d-8aef-4f15'){ ?>
<html style='background-color:black;color:white;font-family:Rubik;'>
    <head><?php include $file_dir.'layout/general_css'; ?></head>
    <body style='background-color:black;color:white;font-family:Rubik;font-size:13px;'>{"message":"Access Denied!!" , "Error":"Session Error, Login To Continue!!"}</body>
</html>
<?php }else{ ?>
<html style='background-color:black;color:white;font-family:Rubik;'>
    <head><?php include $file_dir.'layout/general_css'; ?></head>
    <body style='background-color:black;color:white;font-family:Rubik;font-size:13px;'>{"message":"Access Denied!!" , "Error":"Invalid!!"}</body>
</html>
<?php } ?>
<?php }else{ ?>
<html style='background-color:black;color:white;font-family:Rubik;'>
    <head><?php include $file_dir.'layout/general_css'; ?></head>
    <body style='background-color:black;color:white;font-family:Rubik;font-size:13px;'>{"message":"Access Denied!!" , "Error":"Error!!"}</body>
</html>
<?php } ?>