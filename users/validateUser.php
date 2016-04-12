<?php
	include("../dbConnection.php");

	session_start();

    //Get the username
	$username = $_POST['username'];

    $dbConnection = database_connection();
		
    //Get user information for the username
	$statement = $dbConnection->prepare("SELECT id, username, password FROM users WHERE username = :username");
	$statement->bindParam(':username', $username);
	$statement->execute();
	$result = $statement->fetchAll();
    
    //Get the password
    $pwd = $result[0]['password'];

    //Verify the password, and if right, populate the session variable
    if(password_verify($_POST['password'], $pwd)) {  
        //Populate the session variable with information we use later.
   		$_SESSION['valUser'] = $username;
        $_SESSION['user_id'] = $result[0]['id'];
        $_SESSION['invalidLogin'] = false;
        header('Location: ' . $_SERVER["HTTP_REFERER"]);
   	} else {
        $_SESSION['invalidLogin'] = true;
        header('Location: ' . $_SERVER["HTTP_REFERER"]);
    }
?>