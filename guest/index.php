<?php

    include ('../_inc/sql_queries.inc');

    require_once ('../lib/App/Custom/HTMLParser.php');
    require_once ('../lib/App/Constants.php');
    require_once ('../lib/MySQL/DatabaseHandler.php');

    $htmlpage = 'pages/test.html';
    
    $data = array ();
    $data['cards'] = array();
    
    $dbhandler = new DatabaseHandler();

    $sql = $dbhandler->prepareStatement (SELECT_ALL_CARDS_FOR_GUESTS);
    $sql = $dbhandler->executeStatement ([], '');
    $records = $dbhandler->fetchAll();
   
    foreach ($records as $card)
    {
        $data['cards'][] = array (
                '{card.title}' => $card['title'],
                '{card.description}' => $card['description'],
                '{card.price}' => $card['price'],
                '{card.expirydate}'=> $card['expiry_date'],
                '{card.number}' => $card['card_number']
            );
    }

    $dbhandler->closeConnection();

    $htmlparser = new App\Custom\HTMLParser (file_get_contents ($htmlpage), $data);
    $htmlstring = $htmlparser->GetSubstitutedString();
    if (App\Custom\Error::IsAnError ($htmlstring))
    {
        die ($htmlstring->GetError());
    }

    echo $htmlstring;
?>