<?php //This is a user only page
    session_start();
    if(!isset($_SESSION['valUser'])){
          header("Location: index.php");
        die();
    } 
?>

<!DOCTYPE html>

<html>
    <head>
    <meta charset="utf-8">
        <title>Parking Availability Detection</title>
        <link rel="stylesheet" href="styles.css">
        <script src="scripts/jquery-2.1.4.js"></script>
        <script src="scripts/main.js"></script>
    </head>
 
<body>
    <a id="headerLink" href="index.php"><h1>Parking Availability Detection</h1></a>
    <?php
            include("dbConnection.php");
            $dbConnection = database_connection();
            $countrySelect = $dbConnection->prepare("SELECT * FROM parking_lot");
            $countrySelect->execute();

            $statement = $dbConnection->prepare("SELECT * FROM parking_lot WHERE id = :id");
            $statement->bindParam(':id', $_GET['id']);
            $statement->execute();
            $parkingLot = $statement -> fetch();
    ?>
    
    <h2> Modify ROI thresholds for: <?php echo $parkingLot["location"]; ?> <a href="admin.php">(Change)</a></h2>  
    <?php include 'loginBox.php'; ?>
    <br>
    
    <center><a href="<?php echo "images/logImg_".$_GET['id'].".png" ?>"><img class="logImg" src="<?php echo "images/logImg_".$_GET['id'].".png" ?>"/></a></center>
    
    <h3>Parking Spots</h3>
    <center><button id="saveParkingLotsButton">Save Changes</button></center>
    <div class="disclaimer">You must restart the software for changes to take effect.</div>
    
    <table>
        <tr>
            <th>ID</th>
            <th>Description</th>
            <th>Threshold Percentage</th>
        </tr>
        <?php

            $parkingLotSelect = $dbConnection->prepare("SELECT * FROM parking_spot WHERE parking_lot_id = :id");
            $parkingLotSelect->bindParam(':id', $_GET['id']);
            $parkingLotSelect->execute();

            while ($row = $parkingLotSelect->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>".$row['id']."<input type='hidden' class='parkingSpotID' value='".$row['id']."'/></td>";
                echo "<td><input class='descriptionField' type='text' value='".$row['description']."' maxlength='3'/></td>";
                $percentage = $row['threshold'] * 100;
                echo "<td><input class='thresholdField' type='number' min='0' max='100' value='".$percentage."'/></td>";
                echo "</tr>";
            }
        ?>
    </table>
    
    <footer>Designed by: Aaron Vizzini & Wu Weibo</footer>
</html>