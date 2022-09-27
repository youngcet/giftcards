<?php
    session_start();

    require ('autoloader.php');

    if (! isset ($_SESSION['userid']))
    {
        header ('Location: login');
        die();
    }
?>