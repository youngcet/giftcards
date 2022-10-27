<?php

    include ('../_inc/sql_queries.inc');

    require_once ('../lib/App/Custom/HTMLParser.php');
    require_once ('../lib/App/Constants.php');
    require_once ('../lib/App/Notification.php');
    require_once ('../lib/MySQL/DatabaseHandler.php');

    $htmlpage = 'pages/giftcard.html';

    if (! isset ($_GET['id']))
    {
        die ('This page can not be displayed!');
    }

    $id = trim (strip_tags ($_GET['id']));
    if (empty ($id))  die ('This page can not be displayed!');

    $card = json_decode (base64_decode ($id), true);
    if (empty ($card))  die ('This page can not be displayed!');
    
    $data['{card.title}'] = $card['title'];
    $data['{card.description}'] = $card['description'];
    $data['{card.price}'] = number_format ($card['price'], 2);
    $data['{card.expirydate}'] = $card['expiry_date'];
    $data['{card.number}'] = $card['card_number'];
    $data['{card.id}'] = $card['id'];

    $htmlparser = new App\Custom\HTMLParser (file_get_contents ($htmlpage), $data);
    $htmlstring = $htmlparser->GetSubstitutedString();
    if (App\Custom\Error::IsAnError ($htmlstring))
    {
        die ($htmlstring->GetError());
    }

    echo $htmlstring;
?>