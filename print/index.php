<?php

    require_once ('../lib/App/Custom/HTMLParser.php');

    $data = array();

    $htmlparser = new App\Custom\HTMLParser (file_get_contents ('print.html'), $data);
    echo $htmlparser->GetSubstitutedString();
?>