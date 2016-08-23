<?php
include "connection.php";
include "read_receipts.php";
include "functions.php";
// session started in read_receipts.php
set_time_limit(0);
$receiptsArray = getReceipts();
global $connection;
$length = count($receiptsArray);
for ($i=0; $i<$length - 1; $i++){
    $username = $_SESSION['username'];
    $email_id = $receiptsArray[$i][0];
    $name = $receiptsArray[$i][1];
    //echo $name;
    if(stripos($name,'<') !== false){ // removes the email address in the senders name
        $num = stripos($name,'<') - 1;
        $name = substr($name, 0, $num);
    }
    $name = str_replace("'","",$name); // prevents single quotes from wreaking havoc
    $name = mysqli_real_escape_string($connection, $name); // prevent special characters from messing up the query and protect against basic hacking
    $total = $receiptsArray[$i][2];
    $date = $receiptsArray[$i][3];
    $email = $receiptsArray[$i][4];
    $email = mysqli_real_escape_string($connection, $email); // prevent special characters from messing up the query and protect against basic hacking
    $query = "INSERT INTO receipts(username,email_id,name,total,dates,email) ";
    $query .= "VALUES ('$username','$email_id','$name',$total,$date,'$email')";
    $result = mysqli_query($connection, $query); //making it a variable to check if it works
    
    if(!$result){
        die('Query Failed ' . mysqli_error($connection));
    }
    
}

updateNumberReceipts($receiptsArray[$length-1]);
$numReceipts = $receiptsArray[$length-1];

if ($numReceipts == 0 || $_SESSION['numload'] == 17){
    $_SESSION['numload'] = 1;
    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/receipt_database/dashboard/pages/index.php');
}
else {
    $_SESSION['numload'] = $_SESSION['numload'] + 1;
    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/receipt_database/dashboard/php/refresh.php');
}









?>