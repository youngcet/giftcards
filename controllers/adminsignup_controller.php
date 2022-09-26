<?php

    class AdminsignupController
    {
        private $model;

        function __construct ($model)
        {
            $this->model = $model;
        }

        public function registerUser ($name, $email, $password)
        {
            $fullname = explode (' ', $name);
            $fname = trim ($fullname[0]);
            $lname = trim ($fullname[1]);

            $pwd = App\Custom\Utils::EncryptStringMd5 ($password);

            $dbhandler = new DatabaseHandler();
            $selectadminuser = $dbhandler->prepareStatement (SELECT_ADMIN_USER_BY_EMAIL);
            if (App\Custom\Error::IsAnError ($selectadminuser))
            {
                return $selectadminuser;
            }

            $selectadminuser = $dbhandler->executeStatement ([$email], 's');
            if (App\Custom\Error::IsAnError ($selectadminuser))
            {
                return $selectadminuser;
            }

            $record = $dbhandler->fetchAll();
            if (empty ($record))
            {
                $insertadminuser = $dbhandler->prepareStatement (INSERT_ADMIN_USER);
                if (App\Custom\Error::IsAnError ($insertadminuser))
                {
                    return $insertadminuser;
                }

                $insertadminuser = $dbhandler->executeStatement ([$fname, $lname, $email, $pwd], 'ssss');
                if (App\Custom\Error::IsAnError ($insertadminuser))
                {
                    return $insertadminuser;
                }
            }
            else
            {
                return new App\Custom\Error (-1, App\Constants::EMAIL_ADDRESS_EXISTS);
            }

            $dbhandler->closeConnection();
            
            return 1;
        }
    }
?>