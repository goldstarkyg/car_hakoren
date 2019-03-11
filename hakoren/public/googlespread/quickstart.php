<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once (__DIR__.'/vendor/autoload.php');

define('APPLICATION_NAME', 'Google Sheets API PHP Quickstart');
//define('CREDENTIALS_PATH', '~/.credentials/sheets.googleapis.com-php-quickstart.json');
define('CREDENTIALS_PATH', __DIR__.'/sheets_googleapis_com-php-quickstart.json');
define('CLIENT_SECRET_PATH', __DIR__.'/client_secret.json');
// If modifying these scopes, delete your previously saved credentials
// at ~/.credentials/sheets.googleapis.com-php-quickstart.json
/*define('SCOPES', implode(' ', array(
        Google_Service_Sheets::SPREADSHEETS_READONLY)
));*/
define('SCOPES', implode(' ', array(
        Google_Service_Sheets::SPREADSHEETS)
));

if (php_sapi_name() != 'cli') {
    //throw new Exception('This application must be run on the command line.');
}

/**
 * Returns an authorized API client.
 * @return Google_Client the authorized client object
 */
function getClient() {
    $client = new Google_Client();
    $client->setApplicationName(APPLICATION_NAME);
    $client->setScopes(SCOPES);
    $client->setAuthConfig(CLIENT_SECRET_PATH);
    $client->setAccessType('offline');

    // Load previously authorized credentials from a file.
    $credentialsPath = expandHomeDirectory(CREDENTIALS_PATH);
    if (file_exists($credentialsPath)) {
        $accessToken = json_decode(file_get_contents($credentialsPath), true);
    } else {
        // Request authorization from the user.
        $authUrl = $client->createAuthUrl();
        printf("Open the following link in your browser:\n%s\n", $authUrl);
        print 'Enter verification code: ';
        $authCode = trim(fgets(STDIN));

        // Exchange authorization code for an access token.
        $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

        // Store the credentials to disk.
        if(!file_exists(dirname($credentialsPath))) {
            mkdir(dirname($credentialsPath), 0700, true);
        }
        file_put_contents($credentialsPath, json_encode($accessToken));
        printf("Credentials saved to %s\n", $credentialsPath);
    }
    $client->setAccessToken($accessToken);

    // Refresh the token if it's expired.
    if ($client->isAccessTokenExpired()) {
        $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
    }
    return $client;
}

/**
 * Expands the home directory alias '~' to the full path.
 * @param string $path the path to expand.
 * @return string the expanded path.
 */
function expandHomeDirectory($path) {
    $homeDirectory = getenv('HOME');
    if (empty($homeDirectory)) {
        $homeDirectory = getenv('HOMEDRIVE') . getenv('HOMEPATH');
    }
    return str_replace('~', realpath($homeDirectory), $path);
}

// Get the API client and construct the service object.
$client = getClient();
$service = new Google_Service_Sheets($client);

// Prints the names and majors of students in a sample spreadsheet:
// https://docs.google.com/spreadsheets/d/1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms/edit
$spreadsheetId = '1saI6LwZ997p_c_F2Jd4lQNLQ3zwUVDSP79BjLmLhQtM';
$range = '201801-EN!A2:AK';
$response = $service->spreadsheets_values->get($spreadsheetId, $range);
$values = $response->getValues();
$data = array();
if (count($values) == 0) {
    print "No data found.\n";
} else {
    print "Name, Major:\n";
    //echo "Name  Major"."<br>";
    foreach ($values as $row) {
        // Print columns A and E, which correspond to indices 0 and 4.
        if(!empty($row)) {
            printf("%s, %s\n", $row[0], $row[5]);
            $ob = (object)array();
            $ob->submited_date  = $row[0];//A
            $ob->submited_time  = $row[1];//B
            $ob->booking_site   = $row[2];//C
            $ob->last_name      = $row[3];//D
            $ob->first_name     = $row[4];//E
            $ob->furi_last_name = $row[5];//F
            $ob->furi_first_name= $row[6];//G
            $ob->booking_site_id= $row[7];//H
            $ob->departing_date = $row[8];//I
            $ob->departing_time = $row[9];//J
            $ob->returning_date = $row[10];//K
            $ob->returning_time = $row[11];//L
            $ob->class_name     = $row[12];//M
            $ob->car_number     = $row[13];//N
            $ob->smoking        = $row[14];//O
            $ob->basic_price    = $row[15];//P
            $ob->insurance_1    = $row[16];//Q
            $ob->insurance_2    = $row[17];//R
            $ob->portal_site_price= $row[18];//S
            $ob->child_seat     = $row[19];//T
            $ob->baby_seat      = $row[20];//U
            $ob->junior_seat    = $row[21];//V
            $ob->etc_card       = $row[22];//W
            $ob->now_tire       = $row[23];//X
            $ob->airport        = $row[24];//Y
            $ob->online_payment = $row[25];//Z
            $ob->total_amount   = $row[26];//AA
            $ob->flight_name    = $row[27];//AB
            $ob->flight_number  = $row[28];//AC
            $ob->renting_location= $row[29];//AD
            $ob->passenger      = $row[30];//AE
            $ob->phone          = $row[31];//AF
            $ob->emergency_call = $row[32];//AG
            $ob->email          = $row[33];//AH
            $ob->staff          = $row[34];//AI
            $ob->memo           = $row[35];//AJ
            $ob->registered_time= $row[36];//AK
            array_push($data, $ob);
        }
    }
}


// add data and connect mysql
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hakoren";
$conn = new mysqli($servername, $username, $password, $dbname);
mysqli_set_charset($conn,"utf8");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = array();
foreach( $data as $row ) {
    // $sql[] = '("'.mysql_real_escape_string($row['text']).'", '.$row['category_id'].')';
}
/*$sql('INSERT INTO table (text, category) VALUES '.implode(',', $sql));
if ($conn->query($sql) === TRUE) {
    $last_id = $conn->insert_id;
    $last_id = str_pad($last_id, 6, '0', STR_PAD_LEFT);
} else {
    // echo "Error: " . $sql . "<br>" . $conn->error;
}*/
$conn->close();
//end database section
