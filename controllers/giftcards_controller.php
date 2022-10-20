<?php

    class GiftcardsController
    {
        private $model;

        function __construct ($model)
        {
            $this->model = $model;

            $this->db_handler = new DatabaseHandler();
        }

        public function GetAllGiftCards ($id)
		{
			$sql = $this->db_handler->prepareStatement (SELECT_ALL_GIFTCARDS_BY_STAFFID);
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			$sql = $this->db_handler->executeStatement ([$id], 'i');
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			$record = $this->db_handler->fetchAll();
			
			return $record;
		}

        public function InsertGiftCard ($staff_id, $title, $description, $price, $qty, $color, $expiry_date)
		{
			$sql = $this->db_handler->prepareStatement (INSERT_STAFF_GIFTCARD);
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			$sql = $this->db_handler->executeStatement ([$staff_id, $title, $description, $price, $qty, $color, $expiry_date], 'isssiss');
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}
			
			return 1;
		}

        public function SelectAllSellers ($id)
		{
			$sql = $this->db_handler->prepareStatement (SELECT_ALL_SELLERS);
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			$sql = $this->db_handler->executeStatement ([$id], 'i');
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}
			
			return $this->db_handler->fetchAll();
		}
    }
?>