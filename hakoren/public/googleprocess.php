<?php
//refer this site: https://ctrlq.org/code/20363-google-api-php-oauth2-example
//refer this link https://stackoverflow.com/questions/39314833/google-api-client-refresh-token-must-be-passed-in-or-set-as-part-of-setaccessto
$page = '';
$passed = array();
$failed = array();
if(isset($_POST['page'])){
    $page= $_POST['page'];
};
if(isset($_GET['page'])){
    $page= $_GET['page'];
};
if(isset($_POST['passed'])){
    $passed= $_POST['passed'];
};
if(isset($_GET['passed'])){
    $passed= $_GET['passed'];
};

if(isset($_POST['failed'])){
    $failed= $_POST['failed'];
};
if(isset($_GET['passed'])){
    $failed= $_GET['failed'];
};
//$page= $_GET['page'];


require_once __DIR__ . '/googlespread/vendor/autoload.php';
session_start();
date_default_timezone_set('Asia/Tokyo');
unset($_SESSION['accessToken']);
$REDIRECT_URI = "";
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    $REDIRECT_URI = 'http://localhost/googleprocess.php';
} else {
    //$REDIRECT_URI = 'http://motocle7.sakura.ne.jp/projects/rentcar/hakoren/public/googleprocess.php';
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $domain = $_SERVER['HTTP_HOST'];
    $public_path = $protocol.$domain;
    //$REDIRECT_URI = 'https://motocle8.com/googleprocess.php';
    $REDIRECT_URI = $public_path."/googleprocess.php";;
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
    $service = new Google_Service_Sheets($client);
    $spreadsheetId = '1saI6LwZ997p_c_F2Jd4lQNLQ3zwUVDSP79BjLmLhQtM';
    //get row of target source
    $target_range = '取込履歴!A2:AL'; //history sheet
    //$target_range = 'Motocle - 取込履歴 ー複製!A2:AL'; //main sheet
    $target_response = $service->spreadsheets_values->get($spreadsheetId, $target_range);
    $target_values = $target_response->getValues();
    $target_count = count($target_values)+2;
    //get row of target error source
    $target_error_range = '×取込失敗!A2:AM'; //error sheet
    //$target_error_range = 'Motocle - ×取込失敗 - 複製!A2:AM'; //main sheet
    $target_error_response = $service->spreadsheets_values->get($spreadsheetId, $target_error_range);
    $target_error_values = $target_error_response->getValues();
    $target_error_count = count($target_error_values)+2;


    $range = '取込シート!A2:AL'; //input main sheet
    //$range = 'Motocle - 取込シート - 複製!A2:AL'; //main sheet

    $response = $service->spreadsheets_values->get($spreadsheetId, $range);
    $values = $response->getValues();


    if(count($values) > 0) {
        if($page == "load") {
            echo json_encode($values);
        }
        if($page == 'move_pass') {
            //copy to another sheet for passed.
            $body = new Google_Service_Sheets_ValueRange([
                //'values' => $values
                'values' => json_decode($passed)
            ]);
            //$valueInputOption = 'RAW';
            $valueInputOption = 'USER_ENTERED';
            $params = [
                'valueInputOption' => $valueInputOption
            ];
            $new_range = '取込履歴!A'.$target_count.':AL';//target history sheet to move
            //$new_range = 'Motocle - 取込履歴 ー複製!A'.$target_count.':AL';//target sheet to move
            $result = $service->spreadsheets_values->append($spreadsheetId, $new_range,
                $body, $params);
            $result->getUpdates()->getUpdatedCells();
        }
        if($page == 'move_fail') {
            //copy to another sheet for failed
            $failedbody = new Google_Service_Sheets_ValueRange([
                'values' => json_decode($failed)
            ]);
            //$valueInputOption = 'RAW';
            $valueInputOption = 'USER_ENTERED';
            $params = [
                'valueInputOption' => $valueInputOption
            ];
            $failed_range = '×取込失敗!A'.$target_error_count.':AM';//target error sheet to move for failed
            //$failed_range = 'Motocle - ×取込失敗 - 複製!A'.$target_error_count.':AM';//target sheet to move for failed
            $failed_result = $service->spreadsheets_values->append($spreadsheetId, $failed_range,
                $failedbody, $params);
            $failed_result->getUpdates()->getUpdatedCells();

        }
        if($page == 'delete') {
            /*clear sheet*/
            $requestBody = new Google_Service_Sheets_ClearValuesRequest();
            $response = $service->spreadsheets_values->clear($spreadsheetId, $range, $requestBody);
        }
    }

}
?>