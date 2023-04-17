<?php

    namespace App;

    class Notification
    {
        public static function Add ($db_handler, $sellerid, $title, $text, $profileimg)
        {
            $sql = $db_handler->prepareStatement (INSERT_NOTIFICATION);
			if (! $sql)
			{
				return $sql;
			}

			$sql = $db_handler->executeStatement ([$sellerid, $title, $text, $profileimg], 'isss');
			if (! $sql)
			{
				return $sql;
			}
			
            $db_handler->closeConnection();

			return 1;
        }

        public static function AddRequest ($db_handler, $sellerid, $title, $text, $email, $card_number, $profileimg)
        {
            $sql = $db_handler->prepareStatement (INSERT_REQUEST_NOTIFICATION);
			if (! $sql)
			{
				return $sql;
			}

			$sql = $db_handler->executeStatement ([$sellerid, $title, $text, $email, $card_number, $profileimg], 'isssss');
			if (! $sql)
			{
				return $sql;
			}
			
            $db_handler->closeConnection();

			return 1;
        }

        public static function Get ($db_handler, $type, $id)
		{
			$sql = ($type == 'read') ? $db_handler->prepareStatement (SELECT_READ_NOTIFICATIONS) : $db_handler->prepareStatement (SELECT_NOTIFICATIONS);
			
            if (! $sql)
			{
				return $sql;
			}

			$sql = $db_handler->executeStatement ([$id], 'i');
			if (! $sql)
			{
				return $sql;
			}
			
			return $db_handler->fetchAll();
		}

        public static function GetRow ($db_handler, $id)
		{
			$sql = $db_handler->prepareStatement (SELECT_NOTIFICATIONS_BY_ID);
            if (! $sql)
			{
				return $sql;
			}

			$sql = $db_handler->executeStatement ([$id], 'i');
			if (! $sql)
			{
				return $sql;
			}
			
			return $db_handler->fetchRow();
		}

        public static function MarkAsRead ($db_handler, $id)
		{
			$sql = $db_handler->prepareStatement (MARK_AS_READ);
			if (! $sql)
			{
				return $sql;
			}

			$sql = $db_handler->executeStatement ([$id], 'i');
			if (! $sql)
			{
				return $sql;
			}
			
			return 1;
		}

        public static function MarkAllAsRead ($db_handler, $id)
		{
			$sql = $db_handler->prepareStatement (MARK_All_AS_READ);
			if (! $sql)
			{
				return $sql;
			}

			$sql = $db_handler->executeStatement ([$id], 'i');
			if (! $sql)
			{
				return $sql;
			}
			
			return 1;
		}

        public static function Delete ($db_handler, $id)
		{
			$sql = $db_handler->prepareStatement (DELETE_NOTIFICATION);
			if (! $sql)
			{
				return $sql;
			}

			$sql = $db_handler->executeStatement ([$id], 'i');
			if (! $sql)
			{
				return $sql;
			}
			
            $db_handler->closeConnection();

			return 1;
		}
    }
?>