<?php

    include ('../_inc/sql_queries.inc');

    require_once ('../lib/App/Custom/HTMLParser.php');
    require_once ('../lib/App/Constants.php');
    require_once ('../lib/MySQL/DatabaseHandler.php');

    $htmlpage = 'pages/welcome.html';
    
    $data = array ();
    $data['cards'] = array();

    $data['{error}'] = '';
    
    $dbhandler = new DatabaseHandler();

    if (isset ($_POST['submit']))
    {
        $name = trim (strip_tags ($_POST['name']));
        $email = trim (strip_tags ($_POST['email']));
        $id = trim (strip_tags ($_POST['id']));

        $sql = $dbhandler->prepareStatement (SELECT_ALL_MY_CREATED_GIFTCARDS);
        $sql = $dbhandler->executeStatement ([$id], 'i');
        $records = $dbhandler->fetchAll();
    
        $data['{guest.name}'] = $name;
        $data['{guest.email}'] = $email;

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

        if (! empty ($data['cards'])) 
        {
            $htmlpage = 'pages/categories.html';
            $data['{seller.id}'] = $id;
        }
        else
        {
            $data['{error}'] = 'The seller ID provided has no cards!';
        }
    }

    $data['{enable.giftcard}'] = false;
    if (isset ($_GET['category']))
    {
        $cardno = trim (strip_tags ($_GET['category']));
        $sellerid = trim (strip_tags ($_GET['id']));
        $name = trim (strip_tags ($_GET['n']));
        $email = trim (strip_tags ($_GET['e']));

        if (! empty ($sellerid) && ! empty ($sellerid))
        {
            $data['{enable.giftcard}'] = true;
            $data['{seller.id}'] = $sellerid;
            $data['{card.number}'] = $cardno;
            $data['{guest.name}'] = $name;
            $data['{guest.email}'] = $email;
            $htmlpage = 'pages/categories.html';
        }
    }

    $htmlparser = new App\Custom\HTMLParser (file_get_contents ($htmlpage), $data);
    $htmlstring = $htmlparser->GetSubstitutedString();
    if (App\Custom\Error::IsAnError ($htmlstring))
    {
        die ($htmlstring->GetError());
    }

    $dbhandler->closeConnection();

    echo $htmlstring;
?>