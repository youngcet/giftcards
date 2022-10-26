<?php

    class ListController
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

        public function GetCreatedGiftCards ($id)
		{
			$sql = $this->db_handler->prepareStatement (SELECT_ALL_MY_CREATED_GIFTCARDS);
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

        public function SelectGiftCards ($cardid, $id)
		{
			$sql = $this->db_handler->prepareStatement (SELECT_SELLER_GIFTCARDS);
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			$sql = $this->db_handler->executeStatement ([$cardid, $id], 'si');
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}
			
			return $this->db_handler->fetchAll();
		}

        public function SelectMainGiftCards ($id, $cardid)
		{
			$sql = $this->db_handler->prepareStatement (SELECT_MAIN_GIRDCARD_BY_CARDNO);
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			$sql = $this->db_handler->executeStatement ([$id, $cardid], 'is');
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}
			
			return $this->db_handler->fetchRow();
		}

        public function RedeemMainCard ($qty_sold, $qty, $cardnumber, $userid)
		{
			$sql = $this->db_handler->prepareStatement (REDEEM_MAIN_GIFTCARD);
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			$sql = $this->db_handler->executeStatement ([$qty_sold, $qty, $cardnumber, $userid], 'iisi');
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}
			
			return 1;
		}

        public function RedeemCard ($qty_sold, $qty, $maincardnumber, $cardid, $userid)
		{
			$sql = $this->db_handler->prepareStatement (REDEEM_CARD);
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			$sql = $this->db_handler->executeStatement ([$cardid, $userid], 'ii');
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

            $sql = $this->RedeemMainCard ($qty_sold, $qty, $maincardnumber, $userid);
            if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}
			
			return 1;
		}

        public function UpdateGiftCardNumber ($maincardnumber, $cardno, $cardid, $sellerid)
		{
            $iscardnumberexists = $this->isCardNumberExists ($maincardnumber, $cardno, $sellerid);
            if (App\Custom\Error::IsAnError ($iscardnumberexists))
			{
				return $iscardnumberexists;
			}

            if ($iscardnumberexists)
            {
                return new App\Custom\Error (-1, App\Constants::CARD_NUMBER_EXISTS);
            }

			$sql = $this->db_handler->prepareStatement (UPDATE_GIFTCARD_CARDNO);
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			$sql = $this->db_handler->executeStatement ([$cardno, $cardid, $sellerid], 'sii');
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}
			
			return 1;
		}

        public function isCardNumberExists ($cardid, $cardnumber, $sellerid)
		{
			$sql = $this->db_handler->prepareStatement (CHECK_IF_GIFTCARD_NUMBER_EXISTS);
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			$sql = $this->db_handler->executeStatement ([$cardid, $cardnumber, $sellerid], 'isi');
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}
			
			return (! empty ($this->db_handler->fetchRow())) ? true : false;
		}
    }
?>