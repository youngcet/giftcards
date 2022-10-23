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

        public function InsertGiftCard ($staff_id, $userid, $title, $description, $price, $qty, $color, $expiry_date)
		{
			$cardno = App\Custom\Utils::generateCode (16);
			$sql = $this->db_handler->prepareStatement (SELECT_GIRDCARD_BY_CARDNO);
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			$sql = $this->db_handler->executeStatement ([$cardno], 's');
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			while (! empty ($rows))
			{
				$cardno = App\Custom\Utils::generateCode (16);
				$sql = $this->db_handler->prepareStatement (SELECT_GIRDCARD_BY_CARDNO);
				if (App\Custom\Error::IsAnError ($sql))
				{
					return $sql;
				}

				$sql = $this->db_handler->executeStatement ([$cardno], 's');
				if (App\Custom\Error::IsAnError ($sql))
				{
					return $sql;
				}

				$rows = $this->db_handler->fetchAll();
			}

			$sql = $this->db_handler->prepareStatement (INSERT_STAFF_GIFTCARD);
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			$sql = $this->db_handler->executeStatement ([$staff_id, $userid, $title, $description, $price, $qty, $color, $cardno, $expiry_date], 'iisssisss');
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

		public function SelectSeller ($id)
		{
			$sql = $this->db_handler->prepareStatement (SELECT_SELLER);
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			$sql = $this->db_handler->executeStatement ([$id], 'i');
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}
			
			return $this->db_handler->fetchRow();
		}

		public function UpdateGiftCardSeller ($sellerid, $cardid)
		{
			$sql = $this->db_handler->prepareStatement (UPDATE_GITFTCARD_ASSIGNEE);
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			$sql = $this->db_handler->executeStatement ([$sellerid, $cardid], 'ii');
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}
			
			return 1;
		}

		public function InsertNotification ($sellerid, $title, $text)
		{
			$sql = $this->db_handler->prepareStatement (INSERT_NOTIFICATION);
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			$sql = $this->db_handler->executeStatement ([$sellerid, $title, $text], 'iss');
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}
			
			return 1;
		}

		public function SelectGiftCard ($id)
		{
			$sql = $this->db_handler->prepareStatement (SELECT_GIFTCARD);
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			$sql = $this->db_handler->executeStatement ([$id], 'i');
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}
			
			return $this->db_handler->fetchRow();
		}

		public function DeleteGiftCard ($cardid)
		{
			$sql = $this->db_handler->prepareStatement (DELETE_GIFTCARD);
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			$sql = $this->db_handler->executeStatement ([$cardid], 'i');
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}
			
			return 1;
		}
    }
?>