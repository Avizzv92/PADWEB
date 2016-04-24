<?php
	include("../dbConnection.php");

	session_start();

    //Must be logged into modify a parking spot
	if(isset($_SESSION['valUser'])) {
        $parkingLotID = $_POST["parkingLotID"];
        
        //The IDs of the spots being modified (JSON Array)
        $ids = json_decode($_POST["ids"]);
        //The Descriptions of the spots being modified (JSON Array)
        $descriptions = json_decode($_POST["descs"]);
        //The Thresholds of the spots being modified (JSON Array)
        $percentages = json_decode($_POST["percentages"]);
        
        $dbConnection = database_connection();
        
        //Save each parking spot that has been modified
        $i = -1;
        foreach($ids as $currID){
            $i++;
            $statement = $dbConnection->prepare("UPDATE parking_spot SET description = :description, threshold = :threshold WHERE parking_lot_id = :parkingLotID AND id = :id AND :parkingLotID IN (SELECT id FROM parking_lot WHERE id = :parkingLotID AND user_id = :user_id)");
            $statement->bindParam(':description', $descriptions[$i]);
            $statement->bindParam(':threshold', $percentages[$i]);
            $statement->bindParam(':parkingLotID', $parkingLotID);
            $statement->bindParam(':id', $currID);
            $statement->bindParam(':user_id', $_SESSION['user_id']);
            $statement->execute();
        }
    }
?>