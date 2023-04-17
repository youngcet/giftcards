<?php

    include ('../_inc/sql_queries.inc');

    require_once ('../lib/App/Custom/HTMLParser.php');
    require_once ('../lib/App/Constants.php');
    require_once ('../lib/App/Notification.php');
    require_once ('../lib/MySQL/DatabaseHandler.php');

    $htmlpage = 'pages/request.html';
    
    $data = array ();
    $data['cards'] = array();
    
    if (! isset ($_GET['group']) || ! isset ($_GET['id']) || ! isset ($_GET['n']) || ! isset ($_GET['e']))
    {
        die ('Something went wrong. Please try selecting the category again!');
    }

    $cardno = trim (strip_tags ($_GET['group']));
    $sellerid = trim (strip_tags ($_GET['id']));
    $guestname = trim (strip_tags ($_GET['n']));
    $guestemail = trim (strip_tags ($_GET['e']));

    $data['{request}'] = base64_encode (json_encode (array (
        'name' => $guestname,
        'email' => $guestemail,
        'card_number' => $cardno,
        'seller_id' => $sellerid
    )));

    if (empty ($cardno) || empty ($sellerid))
    {
        die ('Something went wrong. Please try selecting the category again!');
    }

    if (isset ($_GET['req']))
    {
        $title = trim (strip_tags ($_GET['req']));
        App\Notification::AddRequest (new DatabaseHandler(), $sellerid, "$guestname", "Requested $title gift card", $guestemail, $cardno, App\Constants::DEFAULT_USER_PROFILE_IMG);
    }

    $dbhandler = new DatabaseHandler();

    $sql = $dbhandler->prepareStatement (SELECT_SELLER_GIFTCARDS);
    $sql = $dbhandler->executeStatement ([$cardno, $sellerid], 'si');
    $records = $dbhandler->fetchAll();
    
    foreach ($records as $card)
    {
        if ($card['status'] === App\Constants::ACTIVE)
        {
            $data['cards'][] = array (
                '{card.title}' => $card['title'],
                '{card.description}' => $card['description'],
                '{card.price}' => $card['price'],
                '{card.expirydate}'=> $card['expiry_date'],
                '{card.number}' => $card['card_number'],
                '{card.id}' => $card['id'],
            );

            break;
        }
    }

    $dbhandler->closeConnection();

    if (! empty ($data['cards']))
    {
        $data['{card.title}'] = $data['cards'][0]['{card.title}'];
        $data['{card.description}'] = $data['cards'][0]['{card.description}'];
        $data['{card.price}'] = number_format ($data['cards'][0]['{card.price}'], 2);
        $data['{card.expirydate}'] = $data['cards'][0]['{card.expirydate}'];
        $data['{card.number}'] = $data['cards'][0]['{card.number}'];
        $data['{card.id}'] = $data['cards'][0]['{card.id}'];
    }

    $htmlparser = new App\Custom\HTMLParser (file_get_contents ($htmlpage), $data);
    $htmlstring = $htmlparser->GetSubstitutedString();
    if (App\Custom\Error::IsAnError ($htmlstring))
    {
        die ($htmlstring->GetError());
    }

    echo $htmlstring;
?>