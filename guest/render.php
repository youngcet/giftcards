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

    $card = json_decode (base64_decode ($id));
    
?>