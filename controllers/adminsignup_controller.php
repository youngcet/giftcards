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

			// check if email exists in all tables (admin, staff, seller)
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

			// if email does not exists insert the record into the admin table
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