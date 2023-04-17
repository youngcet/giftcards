<?php

	class IndexController
	{
		private $model;

		function __construct($model)
		{
			$this->model = $model;

			$this->db_handler = new DatabaseHandler();
		}

		public function UpdateUserPassword ($role, $pwd, $id)
		{
			$pwd = App\Custom\Utils::EncryptStringMd5 ($pwd);

			if ($role == App\Constants::ADMIN) $sql = $this->db_handler->prepareStatement (UPDATE_ADMIN_PWD);
			if ($role == App\Constants::STAFF) $sql = $this->db_handler->prepareStatement (UPDATE_STAFF_PWD);
			if ($role == App\Constants::SELLER) $sql = $this->db_handler->prepareStatement (UPDATE_SELLER_PWD);

			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			$sql = $this->db_handler->executeStatement ([$pwd, $id], 'si');
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			return 1;
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

		public function UpdateMainCardNumberExist ($currentcardnumber, $cardnumber, $cardid, $sellerid)
		{
			$cardexists = $this->isMainCardNumberExist ($sellerid, $cardnumber);
			if (App\Custom\Error::IsAnError ($cardexists))
			{
				return $cardexists;
			}

			if ($cardexists)
			{
				return new App\Custom\Error (-1, App\Constants::CARD_NUMBER_EXISTS);
			}

			$sql = $this->db_handler->prepareStatement (UPDATE_MAIN_GIFTCARD_CARDNO);
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			$sql = $this->db_handler->executeStatement ([$cardnumber, $cardid, $sellerid], 'sii');
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			$sql = $this->db_handler->prepareStatement (UPDATE_SELLER_MAIN_GIFTCARDNO);
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			$sql = $this->db_handler->executeStatement ([$cardnumber, $currentcardnumber, $sellerid], 'ssi');
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			return 1;
		}

		public function isMainCardNumberExist ($id, $cardid)
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
			
			$rows = $this->db_handler->fetchRow();

			return (! empty ($rows)) ? true : false;
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

		public function CreateUser ($role, $name, $email, $password, $id)
		{
			$fullname = explode (' ', $name);
			$fname = trim ($fullname[0]);
			$lname = trim ($fullname[1]);

			$pwd = App\Custom\Utils::EncryptStringMd5 ($password);

			$dbhandler = new DatabaseHandler();

			$selectadminuser = $dbhandler->prepareStatement (CHECK_IF_EMAIL_EXISTS);
			
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
				if ($role == App\Constants::ADMIN) $insertadminuser = $dbhandler->prepareStatement (INSERT_STAFF_MEMBER);
				if ($role == App\Constants::STAFF) $insertadminuser = $dbhandler->prepareStatement (INSERT_SELLER_MEMBER);

				if (App\Custom\Error::IsAnError ($insertadminuser))
				{
					return $insertadminuser;
				}

				$insertadminuser = $dbhandler->executeStatement ([$id, $fname, $lname, $email, $pwd], 'issss');
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

		public function GetAllStaffGiftCards ($id)
		{
			$sql = $this->db_handler->prepareStatement (SELECT_ALL_STAFF_GIFTCARDS);
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			$sql = $this->db_handler->executeStatement ([$id], 'i');
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			$record = $this->db_handler->fetchRow();
			
			return $record;
		}

		public function UpdateUserProfileImg ($role, $img, $id)
		{
			if ($role == App\Constants::ADMIN) $sql = $this->db_handler->prepareStatement (UPDATE_ADMIN_PROFILE_IMG);
			if ($role == App\Constants::STAFF) $sql = $this->db_handler->prepareStatement (UPDATE_STAFF_PROFILE_IMG);
			if ($role == App\Constants::SELLER) $sql = $this->db_handler->prepareStatement (UPDATE_SELLER_PROFILE_IMG);

			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			$sql = $this->db_handler->executeStatement ([$img, $id], 'si');
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}
			
			return 1;
		}

		public function SelectUserInfo ($role, $id)
		{
			if ($role == App\Constants::ADMIN) $sql = $this->db_handler->prepareStatement (SELECT_ADMIN_USER_BY_ID);
			if ($role == App\Constants::STAFF) $sql = $this->db_handler->prepareStatement (SELECT_STAFF);
			if ($role == App\Constants::SELLER) $sql = $this->db_handler->prepareStatement (SELECT_SELLER);

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

		public function GetAllSellers ($id)
		{
			$allsellers = $this->db_handler->prepareStatement (SELECT_ALL_SELLERS);
			if (App\Custom\Error::IsAnError ($allsellers))
			{
				return $allsellers;
			}

			$allsellers = $this->db_handler->executeStatement ([$id], 'i');
			if (App\Custom\Error::IsAnError ($allsellers))
			{
				return $allsellers;
			}

			$record = $this->db_handler->fetchAll();
		   
			return $record;
		}

		public function GetTotalSellerEarnings ($id)
		{
			$sql = $this->db_handler->prepareStatement (SELECT_ALL_SELLER_EARNINGS);
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			$sql = $this->db_handler->executeStatement ([$id], 'i');
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			$record = $this->db_handler->fetchRow();

			return $record;
		}

		public function SelectSellerGiftCard ($cardid, $id)
		{
			$sql = $this->db_handler->prepareStatement (SELECT_NON_REDEEMED_CARDS);
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			$sql = $this->db_handler->executeStatement ([$cardid, $id], 'si');
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}
			
			return $this->db_handler->fetchRow();
		}

		public function GetTotalSellerGiftCards ($id)
		{
			$sql = $this->db_handler->prepareStatement (SELECT_ALL_SELLER_GIFTCARDS_TOTAL);
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			$sql = $this->db_handler->executeStatement ([$id], 'i');
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			$record = $this->db_handler->fetchRow();

			return $record;
		}

		public function GetAllSellerGiftCards ($id)
		{
			$sql = $this->db_handler->prepareStatement (SELECT_ALL_SELLER_GIFTCARDS_TOTAL);
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			$sql = $this->db_handler->executeStatement ([$id], 'i');
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			$record = $this->db_handler->fetchRow();

			return $record;
		}

		public function GetGiftCards ($id)
		{
			$sql = $this->db_handler->prepareStatement (SELECT_ALL_MY_GIFTCARDS);
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			$sql = $this->db_handler->executeStatement ([$id], 'i');
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			$record = $this->db_handler->fetchRow();

			return $record;
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

		public function CreateCard ($title, $desc, $price, $id)
		{
			$sql = $this->db_handler->prepareStatement (INSERT_GIFTCARD);
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			$sql = $this->db_handler->executeStatement ([$id, $title, $desc, $price, 'active'], 'issis');
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}
			
			$sql = $this->db_handler->prepareStatement (UPDATE_GIFTCARDS_QTY);
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			$sql = $this->db_handler->executeStatement ([$id], 'i');
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			return 1;
		}

		public function RedeemCard ($id, $redeem)
		{
			$sql = $this->db_handler->prepareStatement (SELL_GIFTCARD);
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			$sql = $this->db_handler->executeStatement ([$redeem, $id], 'ii');
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}
			
			// $sql = $this->db_handler->prepareStatement (UPDATE_GIFTCARD_TO_SOLD);
			// if (App\Custom\Error::IsAnError ($sql))
			// {
			// 	return $sql;
			// }

			// $sql = $this->db_handler->executeStatement ([$price, $sellerid], 'ii');
			// if (App\Custom\Error::IsAnError ($sql))
			// {
			// 	return $sql;
			// }

			return 1;
		}

		public function UpdateSellerGiftCards ($sellerid, $staffid, $qty)
		{
			$sql = $this->db_handler->prepareStatement (SELECT_ALL_SELLER_GIFTCARDS);
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			$sql = $this->db_handler->executeStatement ([$sellerid], 'i');
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			$record = $this->db_handler->fetchRow();
			$sql = (empty ($record)) ? $this->db_handler->prepareStatement (INSERT_GIFTCARDS_FROM_SELLER): $this->db_handler->prepareStatement (UPDATE_GIFTCARDS_FROM_SELLER);
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			$sql = (empty ($record)) ? $this->db_handler->executeStatement ([$sellerid, $staffid, $qty], 'iii')
				: $this->db_handler->executeStatement ([$qty, $sellerid], 'ii');
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			return 1;
		}

		public function SellerGiftCards ($id)
		{
			$sql = $this->db_handler->prepareStatement (SELECT_ALL_SELLER_GIFTCARDS);
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

		public function RedeemQuestRequestCard ($qty_sold, $qty, $maincardnumber, $cardid, $userid)
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

		public function InsertGuestUserRequest ($seller_id, $card_number, $guestname, $email, $data)
		{
			$sql = $this->db_handler->prepareStatement (INSERT_REQUEST);
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			$sql = $this->db_handler->executeStatement ([$seller_id, $card_number, $guestname, $email, $data], 'issss');
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}
			
			return 1;
		}

		public function DeleteUser ($type, $id)
		{
			if ($type == App\Constants::STAFF) $query = DELETE_STAFF;
			if ($type == App\Constants::SELLER) $query = DELETE_SELLER;

			$sql = $this->db_handler->prepareStatement ($query);
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			$sql = $this->db_handler->executeStatement ([$id], 'i');
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}
			
			return 1;
		}

		public function DeleteNotification ($id)
		{
			$sql = $this->db_handler->prepareStatement (DELETE_NOTIFICATION);
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}

			$sql = $this->db_handler->executeStatement ([$id], 'i');
			if (App\Custom\Error::IsAnError ($sql))
			{
				return $sql;
			}
			
			return 1;
		}
	}
?>