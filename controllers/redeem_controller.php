<?php

	class RedeemController
	{
		private $model;

		function __construct ($model)
		{
			$this->model = $model;

            $this->db_handler = new DatabaseHandler();
		}

        public function SelectNotifications ($id)
		{
			$sql = $this->db_handler->prepareStatement (SELECT_NOTIFICATIONS);
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

        public function GetGiftCards ($cardno)
		{
			$sql = $this->db_handler->prepareStatement (SELECT_GIFTCARD_BY_CARDNO);
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			$sql = $this->db_handler->executeStatement ([$cardno], 's');
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			return $this->db_handler->fetchRow();
		}

        public function RedeemGiftCard ($qty, $id)
		{
			$sql = $this->db_handler->prepareStatement (SELL_GIFTCARD);
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			$sql = $this->db_handler->executeStatement ([$qty, $id], 'si');
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			return 1;
		}
    }
?>