<?php

    namespace App;

    class Constants
    {
        const APP_TITLE = '';
        const ADMIN = 'admin';
        const STAFF = 'staff';
        const NEW_STAFF = 'New Staff';
        
        const NEW_USER_NOTIFICATION = 'New User Created!';
        const REGISTRATION_SUCCESS_MSG = 'Registration Successful. You can sign in below!';
        const HTML_PAGES_DIR = 'src/html/';

        const ERROR_NOTIFICATION_HTML = '{error.notification}';
        const REGISTRATION_NOTIFICATION_HTML = '{registration.success}';
        const SUCCESS_NOTIFICATION_HTML = '{success.notification}';

        const EMAIL_ADDRESS_EXISTS = 'Email Address Exists. Please choose a different email address!';
        const LOGIN_FAILED_MSG = 'Email or Password Incorrect!';
    }

?>