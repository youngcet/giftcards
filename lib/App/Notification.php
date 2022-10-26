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

        public function Get ($db_handler, $id)
		{
			$sql = $db_handler->prepareStatement (SELECT_NOTIFICATIONS);
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