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
            $parkingSpotSelect = $dbConnection->prepare("SELECT L.parking_spot_id, L.datetime, L.isOccupied FROM occupancy_log L LEFT JOIN occupancy_log R ON L.parking_spot_id = R.parking_spot_id AND L.datetime < R.datetime WHERE isnull (R.parking_spot_id) AND L.parking_lot_id = :id");
            $parkingSpotSelect->bindParam(':id', $_GET['id']);
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
    
    <?php
        $parkingOccupancyAvgMon = $dbConnection->prepare("SELECT AVG(isOccupied) as avg FROM occupancy_log WHERE DAYOFWEEK(datetime) = 2 AND parking_lot_id = :id");
        $parkingOccupancyAvgMon->bindParam(':id', $_GET['id']);
        $parkingOccupancyAvgMon->execute();
        $returnedRowMon = $parkingOccupancyAvgMon -> fetch();
        $avgForMon = $returnedRowMon["avg"] == null ? 0 : $returnedRowMon["avg"];
            
        $parkingOccupancyAvgTue = $dbConnection->prepare("SELECT AVG(isOccupied) as avg FROM occupancy_log WHERE DAYOFWEEK(datetime) = 3 AND parking_lot_id = :id");
        $parkingOccupancyAvgTue->bindParam(':id', $_GET['id']);
        $parkingOccupancyAvgTue->execute();
        $returnedRowTue = $parkingOccupancyAvgMon -> fetch();
        $avgForTue =  $returnedRowTue["avg"] == null ? 0 : $returnedRowTue["avg"];

        $parkingOccupancyAvgWed = $dbConnection->prepare("SELECT AVG(isOccupied) as avg FROM occupancy_log WHERE DAYOFWEEK(datetime) = 4 AND parking_lot_id = :id");
        $parkingOccupancyAvgWed->bindParam(':id', $_GET['id']);
        $parkingOccupancyAvgWed->execute();
        $returnedRowWed = $parkingOccupancyAvgWed -> fetch();
        $avgForWed =  $returnedRowWed["avg"] == null ? 0 : $returnedRowWed["avg"];

        $parkingOccupancyAvgThu = $dbConnection->prepare("SELECT AVG(isOccupied) as avg FROM occupancy_log WHERE DAYOFWEEK(datetime) = 5 AND parking_lot_id = :id");
        $parkingOccupancyAvgThu->bindParam(':id', $_GET['id']);
        $parkingOccupancyAvgThu->execute();
        $returnedRowThu = $parkingOccupancyAvgThu -> fetch();
        $avgForThu =  $returnedRowThu["avg"] == null ? 0 : $returnedRowThu["avg"];

        $parkingOccupancyAvgFri = $dbConnection->prepare("SELECT AVG(isOccupied) as avg FROM occupancy_log WHERE DAYOFWEEK(datetime) = 6 AND parking_lot_id = :id");
        $parkingOccupancyAvgFri->bindParam(':id', $_GET['id']);
        $parkingOccupancyAvgFri->execute();
        $returnedRowFri = $parkingOccupancyAvgFri -> fetch();
        $avgForFri =  $returnedRowFri["avg"] == null ? 0 : $returnedRowFri["avg"];

        $parkingOccupancyAvgSat = $dbConnection->prepare("SELECT AVG(isOccupied) as avg FROM occupancy_log WHERE DAYOFWEEK(datetime) = 7 AND parking_lot_id = :id");
        $parkingOccupancyAvgSat->bindParam(':id', $_GET['id']);
        $parkingOccupancyAvgSat->execute();
        $returnedRowSat = $parkingOccupancyAvgSat -> fetch();
        $avgForSat =  $returnedRowSat["avg"] == null ? 0 : $returnedRowSat["avg"];

        $parkingOccupancyAvgSun = $dbConnection->prepare("SELECT AVG(isOccupied) as avg FROM occupancy_log WHERE DAYOFWEEK(datetime) = 1 AND parking_lot_id = :id");
        $parkingOccupancyAvgSun->bindParam(':id', $_GET['id']);
        $parkingOccupancyAvgSun->execute();
        $returnedRowSun = $parkingOccupancyAvgSun -> fetch();
        $avgForSun =  $returnedRowSun["avg"] == null ? 0 : $returnedRowSun["avg"];
    ?>
    
    <br>
    
    <?php
        function classForAverage($avg) {
            if($avg <= .3) {
                return "green_td";
            } else if ($avg > .3 && $avg <= .6) {
                return "yellow_td";
            } else if ($avg > .6) {
                return "red_td";
            }
        }
    ?>
    
    <h3> Historical Usage Level by Day of Week</h3>
    
    <table>
        <tr>
            <th>Day of Week</th>
            <th>Usage Level*</th>
        </tr>
        <tr>
            <td>Monday</td>            
            <?php
                echo "<td class=\"".classForAverage($avgForMon)."\">";
                echo $avgForMon;
                echo "</td>";  
            ?>
        </tr>
        <tr>
            <td>Tuesday</td>
            <?php
                echo "<td class=\"".classForAverage($avgForTue)."\">";
                echo $avgForTue;
                echo "</td>";  
            ?>
        </tr>
        <tr>
            <td>Wednesday</td>
            <?php
                echo "<td class=\"".classForAverage($avgForWed)."\">";
                echo $avgForWed;
                echo "</td>";  
            ?>
        </tr>
        <tr>
            <td>Thursday</td>
            <?php
                echo "<td class=\"".classForAverage($avgForThu)."\">";
                echo $avgForThu;
                echo "</td>";  
            ?>
        </tr>
        <tr>
            <td>Friday</td>
            <?php
                echo "<td class=\"".classForAverage($avgForFri)."\">";
                echo $avgForFri;
                echo "</td>";  
            ?>
        </tr>
        <tr>
            <td>Saturday</td>
            <?php
                echo "<td class=\"".classForAverage($avgForSat)."\">";
                echo $avgForSat;
                echo "</td>";  
            ?>
        </tr>
        <tr>
            <td>Sunday</td>
            <?php
                echo "<td class=\"".classForAverage($avgForSun)."\">";
                echo $avgForSun;
                echo "</td>";  
            ?>
        </tr>
    </table>
    
    <h5>*Usage Level is calculated by averaging the occupancy (1 or 0) of all of the logged data for a given day of the week.</h5>

    <footer>Designed by: Aaron Vizzini & Wu Weibo</footer>
</html>