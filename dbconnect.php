 <?php
 
	if (!class_exists('Database')) {
		
			class Database
			{
				var $host   		= ""; 	//database server
				var $user     	= ""; 	//database login
				var $pass     	= ""; 	//database password
				var $database 	= ""; 	//database name
		
				public $link;
				
				function __construct($host, $user, $pass, $database)
		        {
					$this->host = $host;
					$this->user = $user;
					$this->pass = $pass;
					$this->database = $database;
		        }
		
				public function connect()
				{
					$this->link = new mysqli($this->host, $this->user, $this->pass, $this->database);
					if (mysqli_connect_error())
					{
						echo mysqli_connect_error();
						exit();
					}
					else
						return $this->link;
				}
			}
			
	}
	
 ?>