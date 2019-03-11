<?php if ( !isset( $_SESSION ) ) session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>確認画面</title>
<link href="css/common_custom.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
<style>
p{ font-size:1.2em;}
</style>
</head>
<body>
<?php
if ( !$_POST ) exit;

if ( !defined( "PHP_EOL" ) ) define( "PHP_EOL", "\r\n" );

///////////////////////////////////////////////////////////////////////////

// Simple Configuration Options

// Enter the email address that you want to emails to be sent to.
// Example $address = "joe.doe@yourdomain.com";
if(strpos($_SERVER['HTTP_HOST'], 'hakoren') === false){
    // motocle8
    $mail_list = [
        'business@motocle.com',
//        'sinchong1989@gmail.com'
    ];
} else {
    // hako-rentcar
    $mail_list = [
//        'sinchong19189@gmail.com',
        'mailform@motocle.com',
        'info@hakoren.com',
        'reservation-f@hakoren.com',
        'reservation-o@hakoren.com',
    ];
}

//$address2 = "mailform@motocle.com";
//$address3 = "4111_asa_ah@msd.biglobe.ne.jp";

// Twitter Direct Message notification control.
// Set $twitter_active to 0 to disable Twitter Notification
$twitter_active = 0;

// Get your consumer key and consumer secret from http://dev.twitter.com/apps/new
// Notes:
// Application Name: Jigowatt Contact Form
// Description: Jigowatt Contact Form Direct Messaging Funcionality
// Application Website: (your website address)
// Application Type: Browser
// Callback URL: (Blank)
// Default Access type: Read and Write
$twitter_user    = ""; // Your user name
$consumer_key    = "";
$consumer_secret = "";

// Access Token and Access Token Secret is under "My Access Token" (right menu).
$token           = "";
$secret          = "";

// END OF Simple Configuration Options

///////////////////////////////////////////////////////////////////////////
function escapeDate($post){
 $escapeArr = $post;
 foreach($escapeArr as $key => $val){
  if(!(is_array($val))){
   $val = htmlspecialchars($val, ENT_QUOTES, 'UTF-8');
   if (get_magic_quotes_gpc()) {
    $val = stripslashes($val);
   }
   $escapeArr[$key] = $val;
  }
 }
 return $escapeArr;
}
$postData = escapeDate($_POST);
$Email = $postData['email'];
//var_dump($postValues["store"]);die;

//////////////////////////////////////////////////////////////////////////
//
// Do not edit the following lines
//
//////////////////////////////////////////////////////////////////////////

$postValues = array();
foreach ( $_POST as $Name => $value ) {
	$postValues[$Name] = trim( $value );
}
extract( $postValues );


// Important Variables
$posted_verify = isset( $postValues['verify'] ) ? md5( $postValues['verify'] ) : '';
$session_verify = !empty($_SESSION['jigowatt']['ajax-extended-form']['verify']) ? $_SESSION['jigowatt']['ajax-extended-form']['verify'] : '';

$error = '';

///////////////////////////////////////////////////////////////////////////
//
// Begin verification process
//
// You may add or edit lines in here.
//
// To make a field not required, simply delete the entire if statement for that field.
//
///////////////////////////////////////////////////////////////////////////


////////////////////////
// Name field is required
if ( empty( $postValues["name"] ) ) {
	$error .= '<li>お名前は必須項目です。</li>';
}
////////////////////////


////////////////////////
// Email field is required
if ( empty( $Email ) ) {
	$error .= '<li>e-mailは必須項目です。</li>';
} elseif ( !isEmail( $Email ) ) {
	$error .= '<li>メールアドレスの形式が正しくありません。</li>';
}
////////////////////////
////////////////////////
// Message field is required
////////////////////////



//Phone field is required
/*
if ( empty( $postValues["phone"] ) ) {
	$error .= '<li>Telは必須項目です。</li>';
} elseif ( !is_numeric( $postValues["phone"] ) ) {
	$error .= '<li>電話番号の形式が正しくありません。</li>';
}
*/



////////////////////////
// Comments field is required
//if ( empty( $comments ) ) {
//	$error .= '<li>You must enter a message to send.</li>';
//}
////////////////////////


////////////////////////
// Agree to terms checkbox is required
//if ( empty( $agree ) ) {
//	$error .= '<li>You must agree to our terms.</li>';
//}
////////////////////////


////////////////////////
// Verification code is required
/*
if ( $session_verify != $posted_verify ) {
	$error .= '<li>認証コードに誤りがあります。</li>';
}
*/
////////////////////////

if ( !empty($error) ) {
	echo '<div class="error_message">入力にエラーがあります。下記をご確認の上「戻る」ボタンにて修正をお願い致します。';
	echo '<ul class="error_messages li-error">' . $error . '</ul>';
	echo '<input type="button" value=" 前画面に戻る " onClick="history.back()"></div>';

	// Important to have return false in here.
	return false;

}

// Advanced Configuration Option.
// i.e. The standard subject will appear as, "You've been contacted by John Doe."

$e_subject = "【ハコレン】企業・団体向け問い合わせフォームが送信されました " . $name;

// Advanced Configuration Option.
// You can change this if you feel that you need to.
// Developers, you may wish to add more fields to the form, in which case you must be sure to add them here.
$msg_head1  = "※自動送信". PHP_EOL ."ハコレンHPから企業・団体向けお問い合わせフォームが送信されました。". PHP_EOL .
"下記の内容を確認してお客様の対応をお願いします。" . PHP_EOL . PHP_EOL;
$msg_head2 = "◇お問い合わせいただき誠にありがとうございます。◇". PHP_EOL .
"━━━━━━━━━━━━━━━━━━━━━━━━". PHP_EOL ."このメールは法人のお客様限定プランのお問い合わせフォームよりお問い合わせいただいた方へ自動送信しております。". PHP_EOL ."
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━". PHP_EOL ."以下の内容でお問い合わせを受け付けいたしました。". PHP_EOL ."改めて、担当より連絡をさせていただきます。". PHP_EOL . PHP_EOL .
"━━━━━━□■□　お問い合わせ内容　□■□━━━━━━". PHP_EOL . PHP_EOL;
$msg  = "企業・団体名: ". $postValues["company"] . PHP_EOL . PHP_EOL;
$msg .= "部署名: ". $postValues["branch"] . PHP_EOL . PHP_EOL;
$msg .= "お名前： ". $postValues["name"] . PHP_EOL . PHP_EOL;
$msg .= "ふりがな： ". $postValues["furi_name"] . PHP_EOL . PHP_EOL;
$msg .= "e-mail: ". $Email . PHP_EOL . PHP_EOL;
$msg .= "電話番号: ". $postValues["tel"] . PHP_EOL . PHP_EOL;
$msg .= "ご利用店: ". $postValues["radio_shop"] . PHP_EOL . PHP_EOL;
if(!empty($postValues["naha-class"])){
	$msg .= "ご検討中の車両クラス: ". $postValues["naha-class"] . PHP_EOL . PHP_EOL;
}else{
	$msg .= "ご検討中の車両クラス: ". $postValues["fukuoka-class"] . PHP_EOL . PHP_EOL;
}
$msg .= "ご希望開始日 : ". $postValues["start_date"] . PHP_EOL . PHP_EOL;
$msg .= "ご利用期間　: ". $postValues["period"] . PHP_EOL . PHP_EOL;
$msg .= "ご利用台数: ". $postValues["number"] . PHP_EOL . PHP_EOL;
$msg .= "お問合せ内容: ". $postValues["message"] . PHP_EOL . PHP_EOL;
$msg .= "-------------------------------------------------------------------------------------------" . PHP_EOL;
$msg_footer1 = "このメールは【ハコレンタカー】の企業・団体向けお問い合わせフォームから送信されました。";
$msg_footer2 = "※本メールを受信された日より、3営業日を過ぎても担当より連絡がない場合は、大変お手数ではございますが、下記記載の担当窓口へ直接ご連絡くださいますようお願い申し上げます。". PHP_EOL ."
※このメールにお心あたりのない場合は、誠に恐れ入りますが、下記お問い合わせ先へご連絡いただけますようお願いいたします。". PHP_EOL ."
■━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━". PHP_EOL ."
ハコレンタカー". PHP_EOL ."
福岡空港店　Tel. 092-260-9506". PHP_EOL ."
那覇空港店　Tel. 098-851-4291". PHP_EOL ."
営業時間： 9：00～19：30". PHP_EOL ."
メールアドレス：info@hakoren.com". PHP_EOL ."
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━■". PHP_EOL . PHP_EOL;

$msg1 = $msg_head1 . $msg . $msg_footer1;
$msg2 = $msg_head2 . $msg . $msg_footer2;


if ( $twitter_active == 1 ) {

	$twitter_msg = $Name . " - " . $comments . ". You can contact " . $お名前 . " via email, " . $email ." or via phone " . $phone . ".";
	twittermessage( $twitter_user, $twitter_msg, $consumer_key, $consumer_secret, $token, $secret );

}

$msg1 = wordwrap( $msg1, 70 );
$msg2 = wordwrap( $msg2, 70 );
$headers_from1  = "From: $Email" . PHP_EOL;
$headers_reply1 = "Reply-To: $Email" . PHP_EOL;
$headers_from2  = "From: $Email" . PHP_EOL;
$headers_reply2 = "Reply-To: $Email" . PHP_EOL;
$headers = "MIME-Version: 1.0" . PHP_EOL;
$headers .= "Content-type: text/plain; charset=utf-8" . PHP_EOL;
$headers .= "Content-Transfer-Encoding: quoted-printable" . PHP_EOL;
$headers1 = $headers_from1 . $headers_reply1 . $headers;
$headers2 = $headers_from2 . $headers_reply2 . $headers;
$flag = true;
foreach($mail_list as $address) {
    $flag = $flag && mail( $address, $e_subject, $msg1, $headers1 );
}

if ( $flag ) {
mail( $Email, $e_subject, $msg2, $headers2 );
//mail( $address2, $e_subject, $msg, $headers );
//mail( $address3, $e_subject, $msg, $headers );
?>
<div class="container">
	<div class="row">
		<div id="success_page li-error" class=" col-md-6 col-md-offset-3 col-sm-10 col-sm-offset-1 col-xs-12" style="border:solid 1px #888; margin-top:100px;">
			<h1>送信完了</h1>	
			<p>この度はお問い合わせメールをお送りいただきありがとうございます。<br />
				お問合せ内容を確認後、担当者よりご連絡をさせていただきます。<br>今しばらくお待ちくださいますようよろしくお願い申し上げます。
			</p>
			<p>
				<a href="/">トップページへ戻る</a>
			</p>
		</div>
	</div>
</div>

	
<?php
// Important to have return false in here.
	return false;

}


///////////////////////////////////////////////////////////////////////////
//
// Do not edit below this line
//
///////////////////////////////////////////////////////////////////////////
echo 'ERROR! Please confirm PHP mail() is enabled.';
return false;

function twittermessage( $user, $message, $consumer_key, $consumer_secret, $token, $secret ) { // Twitter Direct Message function, do not edit.

	require_once 'twitter/EpiCurl.php';
	require_once 'twitter/EpiOAuth.php';
	require_once 'twitter/EpiTwitter.php';

	$Twitter = new EpiTwitter( $consumer_key, $consumer_secret );
	$Twitter->setToken( $token, $secret );

	$direct_message = $Twitter->post_direct_messagesNew( array( 'user' => $user, 'text' => $message ) );
	$tweet_info = $direct_message->responseText;

}

function isEmail( $email ) { // Email address verification, do not edit.

	return preg_match( "/^[-_.[:alnum:]]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|me|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i", $email );

}
?>
</body>
</html>