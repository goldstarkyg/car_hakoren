<?php

namespace Illuminate\Notifications\Channels;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Notifications\Notification;

class MailChannel
{
    /**
     * The mailer implementation.
     *
     * @var \Illuminate\Contracts\Mail\Mailer
     */
    protected $mailer;

    /**
     * The Markdown resolver callback.
     *
     * @var \Closure
     */
    protected $markdownResolver;

    /**
     * Create a new mail channel instance.
     *
     * @param  \Illuminate\Contracts\Mail\Mailer  $mailer
     * @return void
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        if (! $notifiable->routeNotificationFor('mail')) {
            return;
        }
        $message = $notification->toMail($notifiable);
        if ($message instanceof Mailable) {
            return $message->send($this->mailer);
        }
        //echo "sadasdasdasdasd";return;
        date_default_timezone_set("Asia/Tokyo");
        if (strpos(get_class($notification), 'ResetPassword') !== false ) {
            $datetime = date('Y年n月j日　H時i分', strtotime(date('Y-m-d H:i:s').'+1 hour'));
            $datetime1 = date('H時i分s秒', strtotime(date('Y-m-d H:i:s').'+1 hour'));
            $message->introLines[0] = $notifiable->last_name.$notifiable->first_name.'様<br><br>
                平素は【ハコレンタカー】をご利用いただき誠にありがとうございます。<br><br>
                下記URLから再設定処理を行いますのでアクセスしてください。<br><br>
                ■パスワード再設定URL<br><br>';
            $message->outroLines[0] = '<font style="font-size:15px;">有効期限：　'.$datetime.'まで<br>
                ---------------------------------------------<br>
                本メールは自動的に作成し、配信しております。<br>
                本メールへ返信いただきましても、お返事致し兼ねますのでご注意ください。<br>
                =============================================<br>
                ハコレンタカー・サポートセンター 　TEL : 0120-345-724 　URL : https://www.motocle8.com<br>
                =============================================</font>';
            $message->actionText = ' パスワード再設定';
        }
        if (strpos(get_class($notification), 'ResetPassword') !== false ) {

            $datetime = date('Y年n月j日　H時i分', strtotime(date('Y-m-d H:i:s').'+1 hour'));
            $pwdreset = \DB::table('password_resets')->where('email', $notifiable->email)->first();
            if(!empty($pwdreset)){
                $resettoken = base64_encode($pwdreset->token);
                $url = $message->actionUrl;
                if(strpos($_SERVER['HTTP_HOST'], 'hakoren') === false){
                    $url = str_replace('localhost', 'www.motocle8.com', $url);
                    $domain = 'www.motocle8.com';//$_SERVER['HTTP_HOST'];
                } else {
                    $url = str_replace('localhost', 'www.hakoren.com', $url);
                    $domain = 'www.hakoren.com';//$_SERVER['HTTP_HOST'];
                }
                $contact_phone = '福岡空港店 092-260-9506 那覇空港店 098-851-4291';

                $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
                $protocol = "https://";

                $data = array();
                $subject = '【 ハコレンタカー・サポートセンター 】パスワード再設定のご案内';
//                $content1 = $this->buildView($message);
//                $content = $content1['html']->toHtml();
                $content = $notifiable->last_name.$notifiable->first_name.'様<br><br>
                平素は【ハコレンタカー】をご利用いただき誠にありがとうございます。<br><br>
                下記URLから再設定処理を行いますのでアクセスしてください。<br><br>
                ■パスワード再設定URL<br><br>';
                $content .= '<a href="'.$url.'" style="background: #3097d1;color: white;padding: 10px;border-radius: 3px;text-decoration: none;font-size: 15px;">パスワード再設定</a><br><br>';
                $content .= '<p><font style="font-size:15px;">有効期限：　'.$datetime.'まで<br>
                ---------------------------------------------<br>
                本メールは自動的に作成し、配信しております。<br>
                本メールへ返信いただきましても、お返事致し兼ねますのでご注意ください。<br>
                =============================================<br>
                ハコレンタカー・サポートセンター 　TEL : '.$contact_phone.' 　URL : https://'.$domain.'<br>
                =============================================</font></p>';

                $data1 = array('content'=>$content, 'subject'=>$subject, 'fromname'=>"ハコレンタカー・サポートセンター", 'email'=>$notifiable->email);
                $data[] = $data1;
                $finaldata = array('data'=>json_encode($data, JSON_UNESCAPED_UNICODE));
                try {
                    $ch = array();
                    $mh = curl_multi_init();
                    $ch[0] = curl_init();

                    // set URL and other appropriate options
                    curl_setopt($ch[0], CURLOPT_URL, $protocol.$domain . "/mail/vaccine/medkenmail.php");
                    curl_setopt($ch[0], CURLOPT_HEADER, 0);
                    curl_setopt($ch[0], CURLOPT_POST, true);
                    curl_setopt($ch[0], CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch[0], CURLOPT_FOLLOWLOCATION, true);
                    curl_setopt($ch[0], CURLOPT_CAINFO, '/etc/httpd/conf/server.pem');
                    curl_setopt($ch[0], CURLOPT_USERPWD, 'motocle:m123');
                    curl_setopt($ch[0], CURLOPT_POST, true);
                    curl_setopt($ch[0], CURLOPT_POSTFIELDS, $finaldata);
                    curl_setopt($ch[0], CURLOPT_SSL_VERIFYPEER, 0);
                    curl_multi_add_handle($mh, $ch[0]);
                    $active = null;
                    do {
                        $mrc = curl_multi_exec($mh, $active);
                    } while ($mrc == CURLM_CALL_MULTI_PERFORM);

                    while ($active && $mrc == CURLM_OK) {
                        // add this line
                        while (curl_multi_exec($mh, $active) === CURLM_CALL_MULTI_PERFORM) ;

                        if (curl_multi_select($mh) != -1) {
                            do {
                                $mrc = curl_multi_exec($mh, $active);
                                if ($mrc == CURLM_OK) {
                                }
                            } while ($mrc == CURLM_CALL_MULTI_PERFORM);
                        }
                    }
                    //close the handles
                    curl_multi_remove_handle($mh, $ch[0]);
                    curl_multi_close($mh);
                    return 1;
                }catch(Exception $e){
                }
            }
        }else{
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
            $protocol = "https://";
            if(strpos($_SERVER['HTTP_HOST'], 'hakoren') === false){
                $domain = 'www.motocle8.com';//$_SERVER['HTTP_HOST'];
            } else {
                $domain = 'www.hakoren.com';//$_SERVER['HTTP_HOST'];
            }
            $data = array();
            $subject = 'ハコレンタカー';
            $content1 = $this->buildView($message);
            $content = $content1['html']->toHtml();
            $data1 = array('content'=>$content, 'subject'=>$subject, 'fromname'=>"ハコレンタカー・サポートセンター", 'email'=>$notifiable->email);
            $data[] = $data1;
            $finaldata = array('data'=>json_encode($data, JSON_UNESCAPED_UNICODE));
            try {
                $ch = array();
                $mh = curl_multi_init();
                $ch[0] = curl_init();

                // set URL and other appropriate options
                curl_setopt($ch[0], CURLOPT_URL, $protocol.$domain . "/mail/vaccine/medkenmail.php");
                curl_setopt($ch[0], CURLOPT_HEADER, 0);
                curl_setopt($ch[0], CURLOPT_POST, true);
                curl_setopt($ch[0], CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch[0], CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch[0], CURLOPT_CAINFO, '/etc/httpd/conf/server.pem');
                curl_setopt($ch[0], CURLOPT_USERPWD, 'motocle:m123');
                curl_setopt($ch[0], CURLOPT_POST, true);
                curl_setopt($ch[0], CURLOPT_POSTFIELDS, $finaldata);
                curl_setopt($ch[0], CURLOPT_SSL_VERIFYPEER, 0);
                curl_multi_add_handle($mh, $ch[0]);
                $active = null;
                do {
                    $mrc = curl_multi_exec($mh, $active);
                } while ($mrc == CURLM_CALL_MULTI_PERFORM);

                while ($active && $mrc == CURLM_OK) {
                    // add this line
                    while (curl_multi_exec($mh, $active) === CURLM_CALL_MULTI_PERFORM) ;

                    if (curl_multi_select($mh) != -1) {
                        do {
                            $mrc = curl_multi_exec($mh, $active);
                            if ($mrc == CURLM_OK) {
                            }
                        } while ($mrc == CURLM_CALL_MULTI_PERFORM);
                    }
                }
                //close the handles
                curl_multi_remove_handle($mh, $ch[0]);
                curl_multi_close($mh);
                return 1;
            }catch(Exception $e){
            }
        }


        return 0;

    }
    /*
    public function send($notifiable, Notification $notification)
    {
        if (! $notifiable->routeNotificationFor('mail')) {
            return;
        }
        $message = $notification->toMail($notifiable);
        if ($message instanceof Mailable) {
            return $message->send($this->mailer);
        }

        date_default_timezone_set("Asia/Tokyo");
        if (strpos(get_class($notification), 'ResetPassword') !== false ) {
            $datetime = date('Y年n月j日　H時i分', strtotime(date('Y-m-d H:i:s').'+1 hour'));
            $datetime1 = date('H時i分s秒', strtotime(date('Y-m-d H:i:s').'+1 hour'));
            $message->introLines[0] = ''.$notifiable->last_name.$notifiable->first_name.'様<br><br>
                平素は【ハコレンタカー】をご利用いただき誠にありがとうございます。<br><br>
                下記URLから再設定処理を行いますのでアクセスしてください。<br><br>
                ■パスワード再設定URL<br><br>';
            $message->outroLines[0] = '<font style="font-size:15px;">有効期限：　'.$datetime.'まで<br>
                ---------------------------------------------<br>
                本メールは自動的に作成し、配信しております。<br>
                本メールへ返信いただきましても、お返事致し兼ねますのでご注意ください。<br>
                =============================================<br>
                ハコレンタカー・サポートセンター 　TEL : 0120-345-724 　URL : https://www.motocle8.com<br>
                =============================================</font>';
            $message->actionText = 'パスワード再設定';
        }
        if (strpos(get_class($notification), 'ResetPassword') !== false ) {

            $datetime = date('Y年n月j日　H時i分', strtotime(date('Y-m-d H:i:s').'+1 hour'));
            $pwdreset = \DB::table('password_resets')->where('email', $notifiable->email)->first();
            if(!empty($pwdreset)){
                $resettoken = base64_encode($pwdreset->token);
                //$url = url(config('app.url').route('password.reset', $resettoken, false));
                $url = $message->actionUrl;
                $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
                $protocol = "https://";
//                $domain = 'www.vaccine-lab.jp';//$_SERVER['HTTP_HOST'];
                $domain = 'www.motocle8.com';
                $data = array();
                $subject = '【 ハコレンタカー・サポートセンター 】パスワード再設定のご案内';

                $content1 = $this->buildView($message);
                $content = $content1['html']->toHtml();
                $data1 = array('content'=>$content, 'subject'=>$subject, 'fromname'=>"ハコレンタカー・サポートセンター", 'email'=>$notifiable->email);
                $data[] = $data1;
                $finaldata = array('data'=>json_encode($data, JSON_UNESCAPED_UNICODE));
                try {
                    $ch = array();
                    $mh = curl_multi_init();
                    $ch[0] = curl_init();

                    // set URL and other appropriate options
                    curl_setopt($ch[0], CURLOPT_URL, $protocol.$domain . "/mail/vaccine/medkenmail.php");
//                    curl_setopt($ch[0], CURLOPT_URL, url('/') . "/mail/vaccine/medkenmail.php");
                    curl_setopt($ch[0], CURLOPT_HEADER, 0);
                    curl_setopt($ch[0], CURLOPT_POST, true);
                    curl_setopt($ch[0], CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch[0], CURLOPT_FOLLOWLOCATION, true);
                    curl_setopt($ch[0], CURLOPT_CAINFO, '/etc/httpd/conf/server.pem');
                    curl_setopt($ch[0], CURLOPT_USERPWD, 'motocle:m123');
                    curl_setopt($ch[0], CURLOPT_POST, true);
                    curl_setopt($ch[0], CURLOPT_POSTFIELDS, $finaldata);
                    curl_setopt($ch[0], CURLOPT_SSL_VERIFYPEER, 0);
                    curl_multi_add_handle($mh, $ch[0]);
                    $active = null;
                    do {
                        $mrc = curl_multi_exec($mh, $active);
                    } while ($mrc == CURLM_CALL_MULTI_PERFORM);

                    while ($active && $mrc == CURLM_OK) {
                        // add this line
                        while (curl_multi_exec($mh, $active) === CURLM_CALL_MULTI_PERFORM) ;

                        if (curl_multi_select($mh) != -1) {
                            do {
                                $mrc = curl_multi_exec($mh, $active);
                                if ($mrc == CURLM_OK) {
                                }
                            } while ($mrc == CURLM_CALL_MULTI_PERFORM);
                        }
                    }
                    //close the handles
                    curl_multi_remove_handle($mh, $ch[0]);
                    curl_multi_close($mh);
                    return 1;
                }catch(Exception $e){
                }
            }
        }else{
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
            $protocol = "https://";
//            $domain = 'www.vaccine-lab.jp';//$_SERVER['HTTP_HOST'];
            $domain = 'www.motocle8.com';
            $data = array();
            $subject = 'ハコレン';
            $content1 = $this->buildView($message);
            $content = $content1['html']->toHtml();
            $data1 = array('content'=>$content, 'subject'=>$subject, 'fromname'=>"ハコレンタカー・サポートセンター", 'email'=>$notifiable->email);
            $data[] = $data1;
            $finaldata = array('data'=>json_encode($data, JSON_UNESCAPED_UNICODE));
            try {
                $ch = array();
                $mh = curl_multi_init();
                $ch[0] = curl_init();

                // set URL and other appropriate options
                curl_setopt($ch[0], CURLOPT_URL, $protocol.$domain . "/mail/vaccine/medkenmail.php");
//                curl_setopt($ch[0], CURLOPT_URL, url('/') . "/mail/vaccine/medkenmail.php");
                curl_setopt($ch[0], CURLOPT_HEADER, 0);
                curl_setopt($ch[0], CURLOPT_POST, true);
                curl_setopt($ch[0], CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch[0], CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch[0], CURLOPT_CAINFO, '/etc/httpd/conf/server.pem');
                curl_setopt($ch[0], CURLOPT_USERPWD, 'motocle:m123');
                curl_setopt($ch[0], CURLOPT_POST, true);
                curl_setopt($ch[0], CURLOPT_POSTFIELDS, $finaldata);
                curl_setopt($ch[0], CURLOPT_SSL_VERIFYPEER, 0);
                curl_multi_add_handle($mh, $ch[0]);
                $active = null;
                do {
                    $mrc = curl_multi_exec($mh, $active);
                } while ($mrc == CURLM_CALL_MULTI_PERFORM);

                while ($active && $mrc == CURLM_OK) {
                    // add this line
                    while (curl_multi_exec($mh, $active) === CURLM_CALL_MULTI_PERFORM) ;

                    if (curl_multi_select($mh) != -1) {
                        do {
                            $mrc = curl_multi_exec($mh, $active);
                            if ($mrc == CURLM_OK) {
                            }
                        } while ($mrc == CURLM_CALL_MULTI_PERFORM);
                    }
                }
                //close the handles
                curl_multi_remove_handle($mh, $ch[0]);
                curl_multi_close($mh);
                return 1;
            }catch(Exception $e){
            }
        }


        return 0;
    }
    */
//    public function send($notifiable, Notification $notification)
//    {
//        if (! $notifiable->routeNotificationFor('mail')) {
//            return;
//        }
//
//        $message = $notification->toMail($notifiable);
//
//        if ($message instanceof Mailable) {
//            return $message->send($this->mailer);
//        }
//
//        $this->mailer->send($this->buildView($message), $message->data(), function ($mailMessage) use ($notifiable, $notification, $message) {
//            $this->buildMessage($mailMessage, $notifiable, $notification, $message);
//        });
//    }

    /**
     * Build the notification's view.
     *
     * @param  \Illuminate\Notifications\Messages\MailMessage  $message
     * @return void
     */
    protected function buildView($message)
    {
        if ($message->view) {
            return $message->view;
        }

        $markdown = call_user_func($this->markdownResolver);

        return [
            'html' => $markdown->render($message->markdown, $message->data()),
            'text' => $markdown->renderText($message->markdown, $message->data()),
        ];
    }

    /**
     * Build the mail message.
     *
     * @param  \Illuminate\Mail\Message  $mailMessage
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @param  \Illuminate\Notifications\Messages\MailMessage  $message
     * @return void
     */
    protected function buildMessage($mailMessage, $notifiable, $notification, $message)
    {
        $this->addressMessage($mailMessage, $notifiable, $message);

        $mailMessage->subject($message->subject ?: Str::title(
            Str::snake(class_basename($notification), ' ')
        ));

        $this->addAttachments($mailMessage, $message);

        if (! is_null($message->priority)) {
            $mailMessage->setPriority($message->priority);
        }
    }

    /**
     * Address the mail message.
     *
     * @param  \Illuminate\Mail\Message  $mailMessage
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Messages\MailMessage  $message
     * @return void
     */
    protected function addressMessage($mailMessage, $notifiable, $message)
    {
        $this->addSender($mailMessage, $message);

        $mailMessage->to($this->getRecipients($notifiable, $message));

        if ($message->cc) {
            $mailMessage->cc($message->cc[0], Arr::get($message->cc, 1));
        }

        if ($message->bcc) {
            $mailMessage->bcc($message->bcc[0], Arr::get($message->bcc, 1));
        }
    }

    /**
     * Add the "from" and "reply to" addresses to the message.
     *
     * @param  \Illuminate\Mail\Message  $mailMessage
     * @param  \Illuminate\Notifications\Messages\MailMessage  $message
     * @return void
     */
    protected function addSender($mailMessage, $message)
    {
        if (! empty($message->from)) {
            $mailMessage->from($message->from[0], Arr::get($message->from, 1));
        }

        if (! empty($message->replyTo)) {
            $mailMessage->replyTo($message->replyTo[0], Arr::get($message->replyTo, 1));
        }
    }

    /**
     * Get the recipients of the given message.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Messages\MailMessage  $message
     * @return mixed
     */
    protected function getRecipients($notifiable, $message)
    {
        if (is_string($recipients = $notifiable->routeNotificationFor('mail'))) {
            $recipients = [$recipients];
        }

        return collect($recipients)->map(function ($recipient) {
            return is_string($recipient) ? $recipient : $recipient->email;
        })->all();
    }

    /**
     * Add the attachments to the message.
     *
     * @param  \Illuminate\Mail\Message  $mailMessage
     * @param  \Illuminate\Notifications\Messages\MailMessage  $message
     * @return void
     */
    protected function addAttachments($mailMessage, $message)
    {
        foreach ($message->attachments as $attachment) {
            $mailMessage->attach($attachment['file'], $attachment['options']);
        }

        foreach ($message->rawAttachments as $attachment) {
            $mailMessage->attachData($attachment['data'], $attachment['name'], $attachment['options']);
        }
    }

    /**
     * Set the Markdown resolver callback.
     *
     * @param  \Closure  $callback
     * @return $this
     */
    public function setMarkdownResolver(Closure $callback)
    {
        $this->markdownResolver = $callback;

        return $this;
    }
}
