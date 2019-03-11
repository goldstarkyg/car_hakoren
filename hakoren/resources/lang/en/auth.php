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

    'failed' => 'please enter correct email address.',
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
    'someProblems'      => 'Failed to change',
    'email'             => 'Email',
    'password'          => 'Password',
    'PassordRequire'    => ' please enter correct password.',
    'rememberMe'        => 'Remember your password',
    'login'             => 'Login',
    'forgot'            => 'Reset your password',
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
    'fNameRequired'     => 'Please enter name.',
    'lNameRequired'     => 'Please enter last name.',
    'furfNameRequired'  => 'Please enter name',
    'furlNameRequired'  => 'Please enter last name',
    'phoneRequired'     => 'Please enter phone number.',
    'emailRequired'     => 'please enter correct email address.',
    'emailInvalid'      => 'please enter correct email address.',
    'emailUnique'       => 'It is already registered e-mail address.',
    'passwordRequired'  => 'please enter correct password.',
    'PasswordMin'       => 'Please set the password as 6 to 20 characters.',
    'PasswordMax'       => 'Password must be between 6 and 20 characters.',
    'passwordnull'      => 'Please enter password.',
    'passwordConfirmed' => 'please enter correct password.',
    'captchaRequire'    => 'Captcha is required',
    'CaptchaWrong'      => 'Wrong captcha, please try again.',
    'roleRequired'      => 'Please set permissions.',

    //user group
    'groupnameRequired' => 'Insert Group Name',
    'groupnameUnique'   => 'Duplicated Group Name',
    'groupaliasRequired'=> 'Insert Group Alias',
    'createUserGroup'   => 'User Group created.',
    'updateError'       => 'Can not change',
    'erroraddusergroup' => 'Insert User Group ',

];
