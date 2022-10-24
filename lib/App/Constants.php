<?php

    namespace App;

    class Constants
    {
        const APP_TITLE = '';
        const ADMIN = 'admin';
        const STAFF = 'staff';
        const SELLER = 'seller';
        const SUCCESS = 'success';
        const GIFTCARD = 'Gift Card';
        const NOT_APPLICABLE = 'N/A';
        const USER_DELETED_MSG = 'User Deleted Successful!';
        const CARD_DELETED_MSG = 'Card Deleted Successful!';

        const NEW_STAFF = 'New Staff';
        
        const GOOD_AFTERNOON_MESSAGE = 'Good Afternoon';
        const GOOD_MORNING_MESSAGE = 'Good Morning';
        const NEW_USER_NOTIFICATION = 'New User Created!';
        const NEW_CARD_NOTIFICATION = 'New Card Created!';
        const CARD_SOLD_NOTIFICATION = 'Gift Card Redeemed!';
        const CARD_UPDATED_NOTIFICATION = 'Gift Cards Updated!';
        const REGISTRATION_SUCCESS_MSG = 'Registration Successful. You can sign in below!';
        const CHANGES_SAVED = 'Changes Saved!';
        const HTML_PAGES_DIR = 'src/html/';
        const USER_IMG_DIR = 'src/assets/images/users/';
        const DEFAULT_USER_PROFILE_IMG = 'src/assets/images/users/user.png';

        const ERROR_NOTIFICATION_HTML = '{error.notification}';
        const REGISTRATION_NOTIFICATION_HTML = '{registration.success}';
        const SUCCESS_NOTIFICATION_HTML = '{success.notification}';

        const EMAIL_ADDRESS_EXISTS = 'Email Address Exists. Please choose a different email address!';
        const LOGIN_FAILED_MSG = 'Email or Password Incorrect!';
        const GIFTCARDS_EXCEEDED = 'Quantity to Redeem is greater than available Gift Cards!';


        // status codes
        const REQUEST_OK = '200';
        const BAD_REQUEST = '400';
        const UNAUTHORIZED_REQUEST = '401';
        const FORBIDDEN_REQUEST = '403';
        const NOT_FOUND_REQUEST = '404';
        const METHOD_NOT_ALLOWED = '405';
        const NOT_ACCEPTABLE = '406';
        const INTERNAL_SERVER_ERROR = '500';

        const HTTP_METHOD_INVALID = 'HTTP request method not allowed';
        const HTTP_CONTENT_TYPE_INVALID = 'Invalid Content Type';
        const MISSING_PARAMETERS = 'Missing required parameters';
        const FORBIDDEN_REQUEST_ERROR = 'Forbidden Request - 403';
        const NOT_ACCEPTABLE_HEADERS = 'Missing required headers';
    }

?>