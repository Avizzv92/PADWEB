<!DOCTYPE html>
<html>
    <head>
    <meta charset="utf-8">
        <title>Parking Availability Detection</title>
        <link rel="stylesheet" href="styles.css">
        <script src="jquery-2.1.4.js"></script>
        <script src="scripts.js"></script>
        
        <!-- Refresh Page Every 30 Seconds -->
        <meta http-equiv="refresh" content="30" >
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
    
    <h2> Information for: <?php echo $parkingLot["location"]; ?> <a href="index.php">(Change)</a></h2>  
    
        <?php
            $parkingSpotSelect = $dbConnection->prepare("SELECT L.parking_spot_id, L.datetime, L.isOccupied FROM occupancy_log L LEFT JOIN occupancy_log R ON L.parking_spot_id = R.parking_spot_id AND L.datetime < R.datetime WHERE isnull (R.parking_spot_id) AND L.parking_lot_id = :id");
            $parkingSpotSelect->bindParam(':id', $_GET['id']);
            $parkingSpotSelect->execute();
            $parkingSpotRows = $parkingSpotSelect->fetchAll(PDO::FETCH_ASSOC);
        ?>
        
        <br>
        <h3> Latest Information From: <?php echo $parkingSpotRows[0]["datetime"];?></h3>
        
        <center><a href="<?php echo "images/logImg_".$_GET['id'].".png" ?>"><img class="logImg" src="<?php echo "images/logImg_".$_GET['id'].".png" ?>"/></a></center>

    <table>
        <tr>
            <th>Detailed Information</th>
        </tr>
        <?php
            $totalSpots = count($parkingSpotRows);
            $totalOccupied = 0;

            foreach ($parkingSpotRows as $row) { if($row["isOccupied"] == "1"){$totalOccupied++;}}

            echo "<tr><td>Total Spots: ".$totalSpots."</td></tr>";
            echo "<tr><td>Spots Occupied: ".$totalOccupied."</td></tr>";
            echo "<tr><td>Spots Free: ".($totalSpots - $totalOccupied)."</td></tr>";
            echo "<tr><td>Utilization Percentage: ".($totalOccupied/$totalSpots*100)."%</td></tr>";
        ?>
    </table>
    
    <table>
        <tr>
            <th>Parking Spot ID</th>
            <th>Currently Occupied</th>
        </tr>
        <?php
            foreach ($parkingSpotRows as $row) {
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
        $returnedRowTue = $parkingOccupancyAvgTue -> fetch();
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
    
    <h3>Historical Usage Level by Hour of Day</h3>
    
    <table>
        <tr>
            <th>Hour of Day</th>
            <th>Usage Level*</th>
        </tr>
        
        <?php
            for($i = 0; $i < 24; $i++) {
                echo "<tr>";
                    echo "<td>";
                        echo ($i)."h";
                    echo "</td>";
                
                    $parkingOccupancyAvgByHr = $dbConnection->prepare("SELECT AVG(isOccupied) as avg FROM occupancy_log WHERE HOUR(datetime) = :hr AND parking_lot_id = :id");
                    $parkingOccupancyAvgByHr->bindParam(':id', $_GET['id']);
                    $parkingOccupancyAvgByHr->bindParam(':hr', $i);
                    $parkingOccupancyAvgByHr->execute();
                    $returnedRowHr = $parkingOccupancyAvgByHr -> fetch();
                    $avgForHr =  $returnedRowHr["avg"] == null ? 0 : $returnedRowHr["avg"];
                    
                    echo "<td class=\"".classForAverage($avgForHr)."\">".$avgForHr."</td>";
                echo "</tr>";
            }
        ?>
    </table>
    
    <h5>*Usage Level is calculated by averaging the occupancy (1 or 0) of all of the logged data (within the past 365 days) for a given time period.</h5>

    <footer>Designed by: Aaron Vizzini & Wu Weibo</footer>
</html>