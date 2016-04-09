<!DOCTYPE html>
<html>
    <head>
    <meta charset="utf-8">
        <title>Parking Availability Detection</title>
        <link rel="stylesheet" href="styles.css">
        <script src="jquery-2.1.4.js"></script>
        <script src="scripts.js"></script>
    </head>
 
<body>
    <h1>Parking Availability Detection</h1>
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
    <h2> Information for: <?php echo $parkingLot["location"]; ?> </h2>
    
  
                       
    <table>
        <?php
            $parkingSpotSelect = $dbConnection->prepare("SELECT L.parking_spot_id, L.datetime, L.isOccupied FROM occupancy_log L LEFT JOIN occupancy_log R ON L.parking_spot_id = R.parking_spot_id AND L.datetime < R.datetime WHERE isnull (R.parking_spot_id)");
            $parkingSpotSelect->execute();
        ?>
        
        <h3> Latest Information From: <?php $row = $parkingSpotSelect->fetch(PDO::FETCH_ASSOC); echo $row["datetime"];?></h3>
        
        <tr>
            <th>Parking Spot ID</th>
            <th>Currently Occupied</th>
        </tr>
        <?php
            $parkingSpotSelect->execute();
            while ($row = $parkingSpotSelect->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>".$row["parking_spot_id"]."</td>";
                $isOccupied = $row["isOccupied"]  == "1" ? "YES" : "NO";
                echo "<td>".$isOccupied."</td>";
                echo "</tr>";
            }
        ?>
    </table>
    
    
    <footer>Designed by: Aaron Vizzini & Wu Weibo</footer>
</html>