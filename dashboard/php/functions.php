<?php
include "connection.php";
date_default_timezone_set('America/New_York');
if(!isset($_SESSION))
{
    session_start();
}

function updateNumberReceipts($receipts_left){
    global $connection;
    $username = $_SESSION['username'];
    $query = "UPDATE users SET ";
    $query .= "receipts_left = $receipts_left ";
    $query .= "WHERE username = '$username' ";
    $result = mysqli_query($connection, $query);
    if(!$result){
        die('Query Failed' . mysqli_error($connection));
    }
       
}

function editBudget($budget_date, $budget){
    global $connection;
    $budget_date = strtotime($budget_date);
    $username = $_SESSION['username'];
    $query = "UPDATE users SET ";
    $query .= "budget_date = $budget_date, ";
    $query .= "budget = $budget ";
    $query .= "WHERE username = '$username' ";
    $result = mysqli_query($connection, $query);
    if(!$result){
        die('Query Failed' . mysqli_error($connection));
    }
    
    
}
function getBudgetData(){
    global $connection;
    $returnData = array();
    $username = $_SESSION['username'];
    $user = getUser($username);
    $total = 0;
    $startDate = $user['budget_date'];
    $budget = $user['budget'];
    if ($startDate != 0){
        $endDate = strtotime('+1 month', $startDate);
        $current = time();
        while($endDate < $current){
            $endDate = strtotime('+1 month', $endDate);
        }
        $startDate = strtotime('-1 month', $endDate);
        $query = "SELECT * FROM receipts WHERE dates BETWEEN $startDate AND $endDate AND username = '$username'";  // the following code returns an arrays with receipts between the times
        $result = mysqli_query($connection, $query);
        if(!$result){
            die('Query Failed' . mysqli_error($connection));
        }
        
        while($row = mysqli_fetch_assoc($result)){
            $total += $row['total'];
            
        }
    }
    
    array_push($returnData, $total);
    array_push($returnData, $budget);
    array_push($returnData, $startDate);
    return $returnData;
    
    
}

function getUser($username){
    global $connection;
    $query = "SELECT * FROM users WHERE username = '$username' ";
    $result = mysqli_query($connection, $query);
    if(!$result){
        die('Query Failed' . mysqli_error($connection));
    }
    return mysqli_fetch_assoc($result);
    
    
}

function addRefreshToken($username,$refresh_token){
    global $connection;
    $query = "UPDATE users SET ";
    $query .= "refresh_token = '$refresh_token' ";
    $query .= "WHERE username = '$username' ";
    $result = mysqli_query($connection, $query);
    if(!$result){
        die('Query Failed' . mysqli_error($connection));
    }
    
}


// Login functions
function createAccount($name,$username,$profile_pic) {
    global $connection;
    $_SESSION['first_login'] = 1;
    $name = mysqli_real_escape_string($connection, $name); // prevent special characters from messing up the query and protect against basic hacking
    $budget_date = strtotime('-1 month', time());
    $budget = 0;
    $receipts_left = 0;
    $query = "INSERT INTO users(refresh_token,name,username,profile_pic,budget_date,budget,receipts_left) ";
    $query .= "VALUES ('','$name','$username','$profile_pic',$budget_date,$budget,$receipts_left)";
    
    $result = mysqli_query($connection, $query);
    if(!$result){
        die('Query Failed' . mysqli_error($connection));
    }
    
}


/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

// Dashboard functions
function updateReceipt($id,$name,$total,$date){
    global $connection;
    $name = mysqli_real_escape_string($connection, $name); // protect against hacking with escaping characters
    $date = strtotime($date);
    $query = "UPDATE receipts SET ";
    $query .= "name = '$name', ";
    $query .= "total = $total, ";
    $query .= "dates = $date ";
    $query .= "WHERE id = $id ";
    
    $result = mysqli_query($connection, $query);
    if (!$result) {
        die("Query Failed" .mysqli_error($connection));
    }
    
}

function addReceipt($name,$total,$date,$email){
    global $connection;
    $name = mysqli_real_escape_string($connection, $name); // prevent special characters from messing up the query and protect against basic hacking
    $date = strtotime($date);
    $email = mysqli_real_escape_string($connection, $email); 
    $username = $_SESSION['username'];
    $query = "INSERT INTO receipts(username,email_id,name,total,dates,email) ";
    $query .= "VALUES ('$username','','$name',$total,$date,'$email')";
    $result = mysqli_query($connection, $query); //making it a variable to check if it works
    
    if(!$result){
        die('Query Failed' . mysqli_error($connection));
    }
    
}

function deleteReceipt($id){
    global $connection;
    $query = "DELETE FROM receipts ";
    $query .= "WHERE id = $id ";
    $result = mysqli_query($connection, $query); //making it a variable to check if it works
    
    if(!$result){
        die('Query Failed' . mysqli_error($connection));
    }
    
    
}

function readEmail($id){
    global $connection;
    $query = "SELECT * FROM receipts"; // should modify this code to use WHERE to match the ID
    
    $result = mysqli_query($connection, $query); //making it a variable to check if it works
    
    if(!$result){
        die('Query Failed' . mysqli_error($connection));
    }
    
    while($row = mysqli_fetch_assoc($result)){
        if (strcmp($row['id'], $id)==0){
            return $row['email'];
        }
    }
    return null;
}

function recentReceipts (){
    
    global $connection;
    $username = $_SESSION['username'];
    $query = "SELECT * FROM receipts WHERE username = '$username' ORDER BY dates DESC";  // the following code reads the 7 most recent receipts and returns an array with them
    $result = mysqli_query($connection, $query);
    if(!$result){
        die('Query Failed' . mysqli_error($connection));
    }
    $returnArray = array();
    for ($i=0; $i<7; $i++){
        $row = mysqli_fetch_assoc($result);
        array_push($returnArray,$row);
    }
    return $returnArray;
    
    
}

function dashBarGraph($type) {
    global $connection;
    $labels = array();
    $data = array();
    $xLabel = array(); // ann array for the x-axis labels, which is days, weeks, or months
    $beginOfTime = time();
    
    for($i=0; $i<7; $i++){
        
        if ($type == 0){ // the -1 are to prevent 12:00 am as counting for two days
            $beginOfTime = strtotime('Midnight', $beginOfTime);
            $endOfTime = strtotime('Tomorrow', $beginOfTime) - 1;
            
        }
        else if ($type == 1){
            $beginOfTime = strtotime('Last Monday', $beginOfTime);
            $endOfTime = strtotime('Next Monday', $beginOfTime) - 1;
        }
        
        else {
            $beginOfTime = strtotime(date('01-m-Y', $beginOfTime));
            $endOfTime  = strtotime(date('t-m-Y', $beginOfTime));
            $endOfTime = $endOfTime + 60 * 60 * 24 - 1; // gets 12 am of the last day of the month
        }
        
        
        $username = $_SESSION['username'];
        $query = "SELECT * FROM receipts WHERE dates BETWEEN $beginOfTime AND $endOfTime AND username = '$username'";  // the following code returns an arrays with receipts between the times
        $result = mysqli_query($connection, $query);
        if(!$result){
            die('Query Failed' . mysqli_error($connection));
        }
        
        $temp = array();
        while($row = mysqli_fetch_assoc($result)){
            $name = addslashes($row['name']); // adds slashes to escape quotes when this data is echoed into javascript
            $total = $row['total'];
            if (array_key_exists($name, $temp)){ // checks to see if receipts from the same company are there for the same month
                $temp[$name] = $temp[$name] + $total; // adds totals of receipts from same company together
            }
            else {
                $temp[$name] = $total; // adds a new company with its total
            }
            
            array_push($labels,$name); // adds company labels to labels array
        }
        if ($type == 0){
            array_push($xLabel, date('D',$beginOfTime));
        }
        else if ($type == 1){
            $str = date('m/d',$beginOfTime) .'-'.date('m/d', $endOfTime);
            array_push($xLabel, $str);
        }
        else {
            array_push($xLabel, date('M',$beginOfTime)); // adds labels for months into months array
        }
        
        array_push($data, $temp);
        $beginOfTime  = $beginOfTime -  1000; // shifts timeframe back a week or month based on user selection and searches for recentReceipts
        // in this timeframe in the next loop
        
        
    }
    
    $labels = array_unique($labels);
    $labels = array_reverse($labels);
    $labelString = '';
    foreach ($labels as $label){
        $labelString .= "'{$label}',";
    }
    $data = array_reverse($data);
    $xLabel = array_reverse($xLabel);
    $data = convertToDatatable($data,$xLabel);
    array_push($data, $labelString);
    return $data;
    
}


function convertToDatatable ($data,$xLabel){
    
    $returnData = array();
    for($i=0; $i<count($data); $i++){
        $dataFormat = "y: '{$xLabel[$i]}'";
        $temp = $data[$i];
        foreach (array_keys($data[$i]) as $key){
            $dataFormat .= ", '{$key}':{$temp[$key]}";
        }
        array_push($returnData, $dataFormat);
    }
    //print_r($returnData);
    return $returnData;
    
    
    
}

?>