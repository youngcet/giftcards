<?php

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header("Cache-Control: must-revalidate");
    $offset = 60 * 60 * 24 * 3;
    $ExpStr = "Expires: ". gmdate("D, d M Y H:i:s", time() + $offset) . "GMT";
    header($ExpStr);

    include ('../_inc/sql_queries.inc');

    require ('../lib/App/Constants.php');
    require ('../lib/App/Notification.php');
    require ('../lib/App/Custom/Error.php');
    require ('../lib/MySQL/DatabaseHandler.php');

    $postData = json_decode (file_get_contents ("php://input"), true);
    if (empty ($postData))
    {
        http_response_code (App\Constants::BAD_REQUEST);
        exit (json_encode (array ("error" => App\Constants::MISSING_PARAMETERS, 'code' => App\Constants::BAD_REQUEST)));
    }

    $postData['request'] = json_decode (base64_decode ($postData['request']));
    http_response_code (App\Constants::REQUEST_OK);
    exit (json_encode (array ('results' => App\Constants::SUCCESS, 'req' => $postData['request'])));
?>