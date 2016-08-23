<?php 
include "functions.php";

if (isset($_POST['name'])){
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    echo createAccount($name,$username,$password);
}


?>