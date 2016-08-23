<?php
require_once '../google-api-php-client/src/Google/autoload.php';
include "functions.php";
if (isset($_POST['id_token'])){
    $_SESSION['numload'] = 1; // a counter in order to limit the number of redirects in refresh.php
    $client = new Google_Client();
    $client->setAuthConfigFile('client_secrets.json');
    $client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/receipt_database/dashboard/php/oauth2callback.php');
    $client->setScopes(array(
    'https://mail.google.com/'
    ));
    $client->setAccessType('offline');
    $token = $_POST['id_token'];
    $ticket = $client->verifyIdToken($token);
    if ($ticket) {
        $data = $ticket->getAttributes();
        $username = $data['payload']['email']; // email address and username
        $_SESSION['username'] = $username; // logs in
        if (getUser($username) == null){
            $name = $data['payload']['name'];
            $profile_pic = $data['payload']['picture'];
            createAccount($name,$username,$profile_pic);
        }

    }
    
}



?>