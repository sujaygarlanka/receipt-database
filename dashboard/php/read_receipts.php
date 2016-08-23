<?php
require_once '../google-api-php-client/src/Google/autoload.php';
session_start();
function getReceipts (){
    // $_SESSION['script_function'] = 0;
    $client = new Google_Client();
    $client->setAuthConfigFile('client_secrets.json');
    $client->setScopes(array(
    'https://mail.google.com/'
    ));
    $client->setAccessType('offline');
    
    if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
        $client->setAccessToken($_SESSION['access_token']);
        if($client->isAccessTokenExpired()){
            header('Location: http://' . $_SERVER['HTTP_HOST'] . '/receipt_database/dashboard/php/oauth2callback.php');
        }
        
        // Get the API client and construct the service object.
        $service = new Google_Service_Script($client);
        $scriptId = '13DB7lLnhJPuWOsxN9qydYgx_jiTZX0STPfCo8iYgBsEJfPA0kdzZaKLH';
        
        // Create an execution request object.
        $request = new Google_Service_Script_ExecutionRequest();
        set_time_limit(0);
        $request->setFunction('test');
        $response = $service->scripts->run($scriptId, $request);
        $response = $response->getResponse();
        $response = $response['result'];
        //echo $response;
        return ($response);
        
        
    } else {
        $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/receipt_database/dashboard/php/oauth2callback.php';
        header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
    }
    
}

// function getNumberReceipts (){
//     $_SESSION['script_function'] = 1;
//     $client = new Google_Client();
//     $client->setAuthConfigFile('client_secrets.json');
//     $client->setScopes(array(
//     'https://mail.google.com/'
//     ));
//     $client->setAccessType('offline');
    
//     if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
//         $client->setAccessToken($_SESSION['access_token']);
//         if($client->isAccessTokenExpired()){
//             header('Location: http://' . $_SERVER['HTTP_HOST'] . '/receipt_database/dashboard/php/oauth2callback.php');
//         }
        
//         // Get the API client and construct the service object.
//         $service = new Google_Service_Script($client);
//         $scriptId = '13DB7lLnhJPuWOsxN9qydYgx_jiTZX0STPfCo8iYgBsEJfPA0kdzZaKLH';
        
//         // Create an execution request object.
//         $request = new Google_Service_Script_ExecutionRequest();
//         set_time_limit(0);
//         $request->setFunction('numberReceipts');
//         $response = $service->scripts->run($scriptId, $request);
//         $response = $response->getResponse();
//         $response = $response['result'];
//         //echo $response;
//         return ($response);
        
        
//     } else {
//         $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/receipt_database/dashboard/php/oauth2callback.php';
//         header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
//     }
    
// }

?>