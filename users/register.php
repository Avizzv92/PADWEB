<?php
	include("../dbConnection.php");
    session_start();

    //Get the post data for this new user
	$username = $_POST['username'];
    $pwdPreHash = $_POST['password'];
	$pwd = password_hash($pwdPreHash, PASSWORD_BCRYPT);//Encrypt the password using BCrypt 
	$confirm = password_hash($_POST['confirm'], PASSWORD_BCRYPT);

	$dbConnection = database_connection();
		
    //Search the users table to see if the desired username is already there.
	$userSelect = $dbConnection->prepare("SELECT username FROM users WHERE username = :username");
	$userSelect->bindParam(':username', $username);
	$userSelect->execute();
	$userSearchCount = $userSelect->rowCount();
	
	$success = false;
    $error_msg = "Error: ";

    // Make sure all the fields are correct, if not assign the correct message.
    if($userSearchCount > 0) {
        $error_msg = "Username is taken.";
    } else if (strlen($username) <= 0 || strlen($username) > 35 || strlen($pwdPreHash) <= 0 || strlen($pwdPreHash) > 50) {
        $error_msg = "All fields are required and must not exceed their designated size limits.";
    } else if ($pwdPreHash != $_POST['confirm']) {
        $error_msg = "Passwords did not match.";
    } else {
        $success = true;
    }
    
    // If everything the user has given is correct, put it in the table
   	if($success){   		
		$statement = $dbConnection->prepare("INSERT INTO users (username, password) values ( :username, :password)");
		$statement->bindParam(':username', $username);
		$statement->bindParam(':password', $pwd);
        $statement->execute();        
	}

    //Go to the right page depending on the success or failure of the request. 
    if($success) { 
        header('Location: ' . "../index.php");
   	} else {
        $_SESSION['error_msg'] = $error_msg;
        header('Location: ' . $_SERVER["HTTP_REFERER"]);
    }
?>