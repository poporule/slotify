<?php

    include("../../config.php");


    if(!isset($_POST['username'])){
        echo "erorr could not set username";
        exit();
    }


    if(!isset($_POST['oldPassword']) || !isset($_POST['newPassword1'])||!isset($_POST['newPassword2'])){
        echo "Not all password have been set";
        exit();
    }

    if($_POST['oldPassword'] =="" || $_POST['newPassword1']==""||$_POST['newPassword2']==""){
        echo "please fill in all field";
        exit();
    }



    $username= $_POST['usename'];
    $oldPassword= $_POST['oldPassword'];
    $newPassword1= $_POST['newPassword1'];
    $newPassword2= $_POST['newPassword2'];

    $oldMd5= md5($oldPassword);

    $passwordCheck = mysqli_query($con, "SELECT * FROM users WHERE username='$username' AND password = '$oldMd5'");
    

    if(mysqli_num_rows($passwordCheck) !=1){
        echo "password is incorrect";
        exit();
    }

    if($newPassword1 != $newPassword2){
        echo "your new passwords do not match";
        exit();
    }

    if(preg_match('/[^A-Za-z0-9]/',$newPassword1)){
        echo "Your password must only contain letters and 0r numbers";
        exit();
    }

    if(strlen($newPassword1) > 30 ||strlen($newPassword1) < 5){
        echo "password must be between 5 and 30 charaters";
        exit();
    }


    $newMd5= md5($newPassword1);

    $query= mysqli_query($con,"UPDATE users SET password = '$newMd5' WHERE username ='$username'");
    echo "Update successful";

?>