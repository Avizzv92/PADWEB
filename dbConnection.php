<?php
    //Create a database connection
	function database_connection()
	{
		try{
            //Test Enviroment
            $host = "localhost";//Host
            $port = "3306";//Port
            $db = "pad";//Database
            $username = "root";//Database username
            $password = "root";//Database password
            
            //Create the connection
            $conn = new PDO("mysql:host=".$host.";port=".$port.";dbname=".$db, $username, $password); 

		} catch(PDOException $e) {
			echo "Database Connection failed: " . $e->getMessage();
		}
		
		return $conn;
	} 
?>