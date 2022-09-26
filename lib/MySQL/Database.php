<?php

	class Database
	{
		private $servername;
		private $username;
		private $password;
		private $dbname;

		protected function connect()
		{
			$this->servername = "localhost";	# host
            $this->username = "root";			# user
            $this->password = "";				# password
            $this->dbname = "giftcards";		# database name

			$conn = new mysqli ($this->servername, $this->username, $this->password, $this->dbname);

			if ($conn->connect_errno)
			{
				return 0;
			}

			return $conn;
		}

		protected function close()
		{
			mysqli_close ($this->connect());
		}
	}
?>