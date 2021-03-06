<?php
	include("../dbConnection.php");

	session_start();

    //Must be logged into create a new tag
	if(isset($_SESSION['valUser'])) {
		//Description of this new tag
        $id = $_POST['id'];
        
		$dbConnection = database_connection();
		
        //Delete the logs related to this lot
        $statement = $dbConnection->prepare("DELETE FROM occupancy_log WHERE parking_lot_id = :id AND :id IN (SELECT id FROM parking_lot WHERE id = :id AND user_id = :user_id)");
		$statement->bindParam(':id', $id);
        $statement->bindParam(':user_id', $_SESSION['user_id']);
		$statement->execute();
        
        //Delete the parking spots for the lot
        $statement = $dbConnection->prepare("DELETE FROM parking_spot WHERE parking_lot_id = :id AND :id IN (SELECT id FROM parking_lot WHERE id = :id AND user_id = :user_id)");
		$statement->bindParam(':id', $id);
        $statement->bindParam(':user_id', $_SESSION['user_id']);
		$statement->execute();
        
        //Delete the lot itself
		$statement = $dbConnection->prepare("DELETE FROM parking_lot WHERE id = :id AND user_id = :user_id");
		$statement->bindParam(':id', $id);
        $statement->bindParam(':user_id', $_SESSION['user_id']);
		$statement->execute();
        
        header('Location: ' . "../admin.php");
    }
?>