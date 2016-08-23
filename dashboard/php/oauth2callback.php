<?php
require_once '../google-api-php-client/src/Google/autoload.php';
include 'functions.php';

$client = new Google_Client();
$client->setAuthConfigFile('client_secrets.json');
$client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/receipt_database/dashboard/php/oauth2callback.php');
$client->setScopes(array(
'https://mail.google.com/'
));
$client->setAccessType('offline');
// if ($_SESSION['script_function'] == 0){
//     $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/receipt_database/dashboard/php/index.php';
// }
// else {
    $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/receipt_database/dashboard/php/refresh.php';
//}
$username = $_SESSION['username'];
$user = getUser($username);
if($user['refresh_token'] == null){
    if (!isset($_GET['code'])) {
        $auth_url = $client->createAuthUrl();
        header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
    } else {
        //echo $_GET['code'];
        $client->authenticate($_GET['code']);
        $_SESSION['access_token'] = $client->getAccessToken();
        $google_token = json_decode($_SESSION['access_token']);
        //print_r($google_token);
        $refresh_token = $google_token->refresh_token;
        addRefreshToken($username,$refresh_token);
        header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
    }
    
}
else {
    $refresh_token = $user['refresh_token'];
    $client->refreshToken($refresh_token);
    $_SESSION['access_token']= $client->getAccessToken();
    //print_r($_SESSION['access_token']);
    header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}





?>