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
    require ('../lib/App/Custom/Error.php');
    require ('../lib/MySQL/DatabaseHandler.php');
    
    $results = array();
    $requiredfields = array ('seller_id', 'qty');

    $dbhandler = new DatabaseHandler();
    
    $postData = json_decode (file_get_contents ("php://input"), true);
    if (empty ($postData))
    {
        http_response_code (App\Constants::BAD_REQUEST);
        exit (json_encode (array ("error" => App\Constants::MISSING_PARAMETERS, 'code' => App\Constants::BAD_REQUEST)));
    }
    
    foreach ($requiredfields as $key)
    {
        if (! array_key_exists ($key, $postData) || empty ($postData[$key]))
		{
		    http_response_code (App\Constants::BAD_REQUEST);
            exit (json_encode (array ("error" => App\Constants::MISSING_PARAMETERS, 'code' => App\Constants::BAD_REQUEST)));
		}
    }

    $sql = $dbhandler->prepareStatement (SELECT_ALL_SELLER_GIFTCARDS);
    if (App\Custom\Error::IsAnError ($sql))
    {
        http_response_code (App\Constants::BAD_REQUEST);
        exit (json_encode (array ("error" => $sql->GetError(), 'code' => $sql->GetErrorCode())));
    }

    $sql = $dbhandler->executeStatement ([$postData['seller_id']], 'i');
    if (App\Custom\Error::IsAnError ($sql))
    {
        http_response_code (App\Constants::BAD_REQUEST);
        exit (json_encode (array ("error" => $sql->GetError(), 'code' => $sql->GetErrorCode())));
    }

    $record = $dbhandler->fetchRow();
    $sql = (empty ($record)) ? $dbhandler->prepareStatement (INSERT_GIFTCARDS_FROM_SELLER): $dbhandler->prepareStatement (UPDATE_GIFTCARDS_FROM_SELLER);
    if (App\Custom\Error::IsAnError ($sql))
    {
        http_response_code (App\Constants::BAD_REQUEST);
        exit (json_encode (array ("error" => $sql->GetError(), 'code' => $sql->GetErrorCode())));
    }

    $sql = (empty ($record)) ? $dbhandler->executeStatement ([$postData['seller_id'], $postData['staff_id'], $postData['qty']], 'iii')
        : $dbhandler->executeStatement ([$postData['qty'], $postData['seller_id']], 'ii');
    if (App\Custom\Error::IsAnError ($sql))
    {
        http_response_code (App\Constants::BAD_REQUEST);
        exit (json_encode (array ("error" => $sql->GetError(), 'code' => $sql->GetErrorCode())));
    }

    $dbhandler->closeConnection();

    http_response_code (App\Constants::REQUEST_OK);
    exit (json_encode (array ('results' => App\Constants::SUCCESS)));
?>