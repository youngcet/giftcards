<?php

    class LoginController
    {
        private $model;

        function __construct($model)
        {
            $this->model = $model;
            $this->_userdata = [];
        }

        public function loginUser ($email, $pwd)
        {
            $pwd = App\Custom\Utils::EncryptStringMd5 ($pwd);

            $dbhandler = new DatabaseHandler();
            $selectuser = $dbhandler->prepareStatement (CHECK_USER);
            if (App\Custom\Error::IsAnError ($selectuser))
            {
                return $selectuser;
            }

            $selectuser = $dbhandler->executeStatement ([$email, $pwd], 'ss');
            if (App\Custom\Error::IsAnError ($selectuser))
            {
                return $selectuser;
            }

            $record = $dbhandler->fetchAll();
            if (empty ($record))
            {
                return new App\Custom\Error (-1, App\Constants::LOGIN_FAILED_MSG);
            }

            $this->_userdata = $record;

            $dbhandler->closeConnection();

            return 1;
        }

        public function GetUserData()
        {
            return $this->_userdata;
        }
    }
?>