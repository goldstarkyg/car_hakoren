<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed' => '正しいログイン情報を入力してください',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',

    'adminfailed' => 'You can login only with user account.',
    'userfailed' => 'You can login only with admin account.',

    // Activation items
    'sentEmail'         => 'We have sent an email to :email.',
    'clickInEmail'      => 'Please click the link in it to activate your account.',
    'anEmailWasSent'    => 'An email was sent to :email on :date.',
    'clickHereResend'   => 'Click here to resend the email.',
    'successActivated'  => 'Success, your account has been activated.',
    'unsuccessful'      => 'Your account could not be activated; please try again.',
    'notCreated'        => 'Your account could not be created; please try again.',
    'tooManyEmails'     => 'Too many activation emails have been sent to :email. <br />Please try again in <span class="label label-danger">:hours hours</span>.',
    'regThanks'         => 'Thank you for registering, ',
    'invalidToken'      => 'Invalid activation token. ',
    'activationSent'    => 'Activation email sent. ',
    'alreadyActivated'  => 'Already activated. ',

    // Labels
    'whoops'            => ' ',
    'someProblems'      => '変更に失敗しました',
    'email'             => 'メールアドレス',
    'password'          => 'パスワード',
    'PassordRequire'    => 'パスワード確認は必須です',
    'rememberMe'        => 'パスワードを記憶する',
    'login'             => 'ログイン',
    'forgot'            => 'パスワードを再発行する',
    'forgot_message'    => 'Password Troubles?',
    'name'              => 'Username',
    'first_name'        => 'First Name',
    'last_name'         => 'Last Name',
    'confirmPassword'   => 'Confirm Password',
    'register'          => 'Register',

    // Placeholders
    'ph_name'           => 'Username',
    'ph_email'          => 'E-mail Address',
    'ph_firstname'      => 'First Name',
    'ph_lastname'       => 'Last Name',
    'ph_password'       => 'Password',
    'ph_password_conf'  => 'Confirm Password',

    // User flash messages
    'sendResetLink'     => 'Send Password Reset Link',
    'resetPassword'     => 'Reset Password',
    'loggedIn'          => 'You are logged in!',

    // email links
    'pleaseActivate'    => 'Please activate your account.',
    'clickHereReset'    => 'Click here to reset your password: ',
    'clickHereActivate' => 'Click here to activate your account: ',

    // Validators
    'userNameTaken'     => 'Username is taken',
    'userNameRequired'  => 'Username is required',
    'fNameRequired'     => '名前は必須です',
    'lNameRequired'     => '名字は必須です',
    'furfNameRequired'  => '名前は必須です',
    'furlNameRequired'  => '名字は必須です',
    'phoneRequired'     => 'phone numberは必須です',
    'emailRequired'     => 'メールアドレスは必須です',
    'emailInvalid'      => 'メールアドレスが無効です。',
    'emailUnique'       => '既に登録されているメールアドレスです。',
    'passwordRequired'  => 'パスワードは必須です',
    'PasswordMin'       => 'パスワードは6文字から20文字で設定してください。',
    'PasswordMax'       => 'パスワードは6文字から20文字で設定してください。',
    'passwordnull'      => 'パスワードが未入力です',
    'passwordConfirmed' => 'ご入力のパスワード情報が違います',
    'captchaRequire'    => 'Captcha is required',
    'CaptchaWrong'      => 'Wrong captcha, please try again.',
    'roleRequired'      => '権限を設定してください',

    //user group
    'groupnameRequired' => 'Insert Group Name',
    'groupnameUnique'   => 'Duplicated Group Name',
    'groupaliasRequired'=> 'Insert Group Alias',
    'createUserGroup'   => 'User Group created.',
    'updateError'       => 'Can not change',
    'erroraddusergroup' => 'Insert User Group ',

];
