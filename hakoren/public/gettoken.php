<?php
//refer this site: https://ctrlq.org/code/20363-google-api-php-oauth2-example
//refer this link https://stackoverflow.com/questions/39314833/google-api-client-refresh-token-must-be-passed-in-or-set-as-part-of-setaccessto
require_once __DIR__ . '/googlespread/vendor/autoload.php';
session_start();
date_default_timezone_set('Asia/Tokyo');
unset($_SESSION['accessToken']);
$REDIRECT_URI = "";
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    $REDIRECT_URI = 'http://localhost/gettoken.php';
} else {
    $REDIRECT_URI = 'http://motocle7.sakura.ne.jp/projects/rentcar/hakoren/public/gettoken.php';
}
$KEY_LOCATION = "";
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    $KEY_LOCATION = __DIR__ . '/client_secret_local.json';
} else {
    $KEY_LOCATION = __DIR__ . '/client_secret_server.json';
}

$TOKEN_FILE   = "token.txt";

$SCOPES =  implode(' ', array(Google_Service_Sheets::SPREADSHEETS));

$client = new Google_Client();
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    $client->setApplicationName("Google Sheets API Local");
} else {
    $client->setApplicationName("Google Sheets API Server"); // server
}

$client->setAuthConfig($KEY_LOCATION);

// Incremental authorization
$client->setIncludeGrantedScopes(true);

// Allow access to Google API when the user is not present.
$client->setRedirectUri($REDIRECT_URI);
$client->setAccessType('offline');
$client->setApprovalPrompt('force');

$client->setScopes($SCOPES);

if (isset($_GET['code']) && !empty($_GET['code'])) {
    try {
        // Exchange the one-time authorization code for an access token
        $accessToken = $client->fetchAccessTokenWithAuthCode($_GET['code']);

        // Save the access token and refresh token in local filesystem
        file_put_contents($TOKEN_FILE, json_encode($accessToken));

        $_SESSION['accessToken'] = $accessToken;
        header('Location: ' . filter_var($REDIRECT_URI, FILTER_SANITIZE_URL));
        exit();
    }
    catch (\Google_Service_Exception $e) {
        print_r($e);
    }
}

if (!isset($_SESSION['accessToken'])) {

    $token = @file_get_contents($TOKEN_FILE);

    if ($token == null) {

        // Generate a URL to request access from Google's OAuth 2.0 server:
        $authUrl = $client->createAuthUrl();
        // Redirect the user to Google's OAuth server
        header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
        exit();

    } else {

        $_SESSION['accessToken'] = json_decode($token, true);

    }
}

$client->setAccessToken($_SESSION['accessToken']);
/* Refresh token when expired */
if ($client->isAccessTokenExpired()) {
    // the new access token comes with a refresh token as well
    $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
    file_put_contents($TOKEN_FILE, json_encode($client->getAccessToken()));
}

if(!empty($_SESSION['accessToken'])&& !empty(@file_get_contents($TOKEN_FILE))) {
    echo "token_true";
}else {
    echo "token_false";
}
?>