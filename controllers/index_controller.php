<?php

    class IndexController
    {
        private $model;

        function __construct($model)
        {
            $this->model = $model;

            $this->db_handler = new DatabaseHandler();
        }

        public function GetAllStaff ($adminid)
        {
            $allstaff = $this->db_handler->prepareStatement (SELECT_ALL_STAFF);
            if (App\Custom\Error::IsAnError ($allstaff))
            {
                return $allstaff;
            }

            $allstaff = $this->db_handler->executeStatement ([$adminid], 'i');
            if (App\Custom\Error::IsAnError ($allstaff))
            {
                return $allstaff;
            }

            $record = $this->db_handler->fetchAll();

            return $record;
        }

        public function GetTotalEarnings ($adminid)
        {
            $sql = $this->db_handler->prepareStatement (SELECT_ALL_EARNINGS);
            if (App\Custom\Error::IsAnError ($sql))
            {
                return $sql;
            }

            $sql = $this->db_handler->executeStatement ([$adminid], 'i');
            if (App\Custom\Error::IsAnError ($sql))
            {
                return $sql;
            }

            $record = $this->db_handler->fetchAll();

            return $record;
        }

        public function GetTotalGiftCards ($adminid)
        {
            $sql = $this->db_handler->prepareStatement (SELECT_ALL_GIFTCARDS);
            if (App\Custom\Error::IsAnError ($sql))
            {
                return $sql;
            }

            $sql = $this->db_handler->executeStatement ([$adminid], 'i');
            if (App\Custom\Error::IsAnError ($sql))
            {
                return $sql;
            }

            $record = $this->db_handler->fetchAll();

            return $record;
        }

        public function CreateUser ($role, $name, $email, $password, $admin_id)
        {
            $fullname = explode (' ', $name);
            $fname = trim ($fullname[0]);
            $lname = trim ($fullname[1]);

            $pwd = App\Custom\Utils::EncryptStringMd5 ($password);

            $dbhandler = new DatabaseHandler();

            if ($role == App\Constants::ADMIN) $selectadminuser = $dbhandler->prepareStatement (SELECT_STAFF_BY_EMAIL_ADMIN);
            
            if (App\Custom\Error::IsAnError ($selectadminuser))
            {
                return $selectadminuser;
            }

            $selectadminuser = $dbhandler->executeStatement ([$email, $admin_id], 'si');
            if (App\Custom\Error::IsAnError ($selectadminuser))
            {
                return $selectadminuser;
            }

            $record = $dbhandler->fetchAll();
            if (empty ($record))
            {
                $insertadminuser = $dbhandler->prepareStatement (INSERT_STAFF_MEMBER);
                if (App\Custom\Error::IsAnError ($insertadminuser))
                {
                    return $insertadminuser;
                }

                $insertadminuser = $dbhandler->executeStatement ([$admin_id, $fname, $lname, $email, $pwd], 'issss');
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