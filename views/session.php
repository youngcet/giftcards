<?php
    session_start();

    require ('autoloader.php');

    if (! isset ($_SESSION['userid']))
    {
        header ('Location: login');
        die();
    }

    function getUserRole()
    {
        if (! isset ($_SESSION['role']))
        {
            $dbhandler = new DatabaseHandler();
            $checkuser = $dbhandler->prepareStatement (SELECT_ADMIN_USER_BY_ID);
            if (App\Custom\Error::IsAnError ($checkuser))
            {
                return $checkuser;
            }

            $checkuser = $dbhandler->executeStatement ([$_SESSION['userid']], 's');
            if (App\Custom\Error::IsAnError ($checkuser))
            {
                return $checkuser;
            }

            $record = $dbhandler->fetchAll();
            $_SESSION['role'] = (! empty ($record)) ? App\Constants::ADMIN : App\Constants::STAFF;
            $dbhandler->closeConnection();
        }

        return $_SESSION['role'];
    }
?>