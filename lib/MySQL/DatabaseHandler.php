<?php
	
	require_once ("Database.php");

	class DatabaseHandler extends Database
	{
		private $_dbconn;
		private $_stmt;
		private $_results;
		private $_error;

		public function __construct()
		{
			$this->_dbconn = $this->connect();
			$this->_stmt = (object) array();
			$this->_error = $this->_results = array();
		}

		public function isConnected (): object
		{
			return $this->_dbconn;
		}
        
		public function prepareStatement ($sqlStatement): bool
		{
			if (! $this->isConnected() || ! $this->_stmt = $this->_dbconn->prepare ($sqlStatement))
			{
				return new App\Custom\Error (-1, $this->_dbconn->connect_error);
			}

			return 1;
		}

		public function executeStatement ($values, $valueType): bool
		{
		    if (! empty($values) && ! empty ($valueType)) $this->_stmt->bind_param ($valueType, ...$values);
		
			if (! $this->isConnected() || ! $this->_stmt->execute())
			{
				return new App\Custom\Error (-1, $this->_dbconn->connect_error);
			}

			$this->_results = $this->_stmt->get_result();
			$this->_stmt->close();

			return 1;

		}

		public function fetchRow(): array
		{
		    $results = array();
			if ($this->_results->num_rows === 0)
			{
				return $results;
			}
            
			return $this->_results->fetch_assoc();;
		}
		
		public function fetchAll()
		{
		    $results = array();
		    $results = $this->_results->fetch_all (MYSQLI_ASSOC);
		    mysqli_free_result ($this->_results);
		    
			return $results;
		}

		public function closeConnection(): void
		{
			if ($this->isConnected ())
			{
				$this->_dbconn->close();
			}
		}
	}
?>