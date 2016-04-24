<!DOCTYPE html>
<html>
    <head>
    <meta charset="utf-8">
        <title>Parking Availability Detection</title>
        <link rel="stylesheet" href="styles.css">
        <script src="scripts/Chart.min.js"></script>
        <script src="scripts/jquery-2.1.4.js"></script>
        <script src="scripts/main.js"></script>
        
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
            $parkingSpotSelect = $dbConnection->prepare("SELECT L.parking_spot_id, L.datetime, L.isOccupied, L.parking_spot_desc FROM occupancy_log L LEFT JOIN occupancy_log R ON L.parking_spot_id = R.parking_spot_id AND L.datetime < R.datetime WHERE isnull (R.parking_spot_id) AND L.parking_lot_id = :id");
            $parkingSpotSelect->bindParam(':id', $_GET['id']);
            $parkingSpotSelect->execute();
            $parkingSpotRows = $parkingSpotSelect->fetchAll(PDO::FETCH_ASSOC);
        ?>
        
        <br>
        <h3> Latest Information From: <?php echo $parkingSpotRows[0]["datetime"];?></h3>
        <div class="disclaimer">Page refreshes every 30 seconds.</div>
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
            echo "<tr><td>Utilization Percentage: ".number_format(($totalOccupied/$totalSpots*100), 2, '.', '')."%</td></tr>";
        ?>
    </table>
    
    <table>
        <tr>
            <th>Parking Spot</th>
            <th>Currently Occupied</th>
        </tr>
        <?php
            foreach ($parkingSpotRows as $row) {
                echo "<tr>";
                echo "<td>".$row["parking_spot_desc"]."</td>";
                $isOccupied = $row["isOccupied"]  == "1" ? "YES" : "NO";
                echo "<td>".$isOccupied."</td>";
                echo "</tr>";
            }
        ?>
    </table>
    
    <?php
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];
        
        if(strlen($startDate) == 0) {
            $time = strtotime("-1 year", time());
            $startDate = date('Y-m-d', $time);
        }

        if(strlen($endDate) == 0) {
            $endDate = date('Y-m-d');
        }

        $startDate = $startDate." 00:00:00";
        $endDate = $endDate." 23:59:59";

        $parkingSpotID = strlen($_GET['parkingSpotID']) == 0 ? "all" : $_GET['parkingSpotID'];
        $parkingSpotIDClause = $parkingSpotID == "all" ? "" : " AND parking_spot_id = ".$parkingSpotID;
            
        $parkingOccupancyAvgMon = $dbConnection->prepare("SELECT AVG(isOccupied) as avg FROM occupancy_log WHERE DAYOFWEEK(datetime) = 2 AND parking_lot_id = :id AND (datetime <= :endDate AND datetime >= :startDate)".$parkingSpotIDClause);
        $parkingOccupancyAvgMon->bindParam(':id', $_GET['id']);
        $parkingOccupancyAvgMon->bindParam(':startDate', $startDate);
        $parkingOccupancyAvgMon->bindParam(':endDate', $endDate);
        $parkingOccupancyAvgMon->execute();
        $returnedRowMon = $parkingOccupancyAvgMon -> fetch();
        $avgForMon = $returnedRowMon["avg"] == null ? 0 : $returnedRowMon["avg"];
            
        $parkingOccupancyAvgTue = $dbConnection->prepare("SELECT AVG(isOccupied) as avg FROM occupancy_log WHERE DAYOFWEEK(datetime) = 3 AND parking_lot_id = :id AND (datetime <= :endDate AND datetime >= :startDate)".$parkingSpotIDClause);
        $parkingOccupancyAvgTue->bindParam(':id', $_GET['id']);
        $parkingOccupancyAvgTue->bindParam(':startDate', $startDate);
        $parkingOccupancyAvgTue->bindParam(':endDate', $endDate);
        $parkingOccupancyAvgTue->execute();
        $returnedRowTue = $parkingOccupancyAvgTue -> fetch();
        $avgForTue =  $returnedRowTue["avg"] == null ? 0 : $returnedRowTue["avg"];

        $parkingOccupancyAvgWed = $dbConnection->prepare("SELECT AVG(isOccupied) as avg FROM occupancy_log WHERE DAYOFWEEK(datetime) = 4 AND parking_lot_id = :id AND (datetime <= :endDate AND datetime >= :startDate)".$parkingSpotIDClause);
        $parkingOccupancyAvgWed->bindParam(':id', $_GET['id']);
        $parkingOccupancyAvgWed->bindParam(':startDate', $startDate);
        $parkingOccupancyAvgWed->bindParam(':endDate', $endDate);
        $parkingOccupancyAvgWed->execute();
        $returnedRowWed = $parkingOccupancyAvgWed -> fetch();
        $avgForWed =  $returnedRowWed["avg"] == null ? 0 : $returnedRowWed["avg"];

        $parkingOccupancyAvgThu = $dbConnection->prepare("SELECT AVG(isOccupied) as avg FROM occupancy_log WHERE DAYOFWEEK(datetime) = 5 AND parking_lot_id = :id AND (datetime <= :endDate AND datetime >= :startDate)".$parkingSpotIDClause);
        $parkingOccupancyAvgThu->bindParam(':id', $_GET['id']);
        $parkingOccupancyAvgThu->bindParam(':startDate', $startDate);
        $parkingOccupancyAvgThu->bindParam(':endDate', $endDate);
        $parkingOccupancyAvgThu->execute();
        $returnedRowThu = $parkingOccupancyAvgThu -> fetch();
        $avgForThu =  $returnedRowThu["avg"] == null ? 0 : $returnedRowThu["avg"];

        $parkingOccupancyAvgFri = $dbConnection->prepare("SELECT AVG(isOccupied) as avg FROM occupancy_log WHERE DAYOFWEEK(datetime) = 6 AND parking_lot_id = :id AND (datetime <= :endDate AND datetime >= :startDate)".$parkingSpotIDClause);
        $parkingOccupancyAvgFri->bindParam(':id', $_GET['id']);
        $parkingOccupancyAvgFri->bindParam(':startDate', $startDate);
        $parkingOccupancyAvgFri->bindParam(':endDate', $endDate);
        $parkingOccupancyAvgFri->execute();
        $returnedRowFri = $parkingOccupancyAvgFri -> fetch();
        $avgForFri =  $returnedRowFri["avg"] == null ? 0 : $returnedRowFri["avg"];

        $parkingOccupancyAvgSat = $dbConnection->prepare("SELECT AVG(isOccupied) as avg FROM occupancy_log WHERE DAYOFWEEK(datetime) = 7 AND parking_lot_id = :id AND (datetime <= :endDate AND datetime >= :startDate)".$parkingSpotIDClause);
        $parkingOccupancyAvgSat->bindParam(':id', $_GET['id']);
        $parkingOccupancyAvgSat->bindParam(':startDate', $startDate);
        $parkingOccupancyAvgSat->bindParam(':endDate', $endDate);
        $parkingOccupancyAvgSat->execute();
        $returnedRowSat = $parkingOccupancyAvgSat -> fetch();
        $avgForSat =  $returnedRowSat["avg"] == null ? 0 : $returnedRowSat["avg"];

        $parkingOccupancyAvgSun = $dbConnection->prepare("SELECT AVG(isOccupied) as avg FROM occupancy_log WHERE DAYOFWEEK(datetime) = 1 AND parking_lot_id = :id AND (datetime <= :endDate AND datetime >= :startDate)".$parkingSpotIDClause);
        $parkingOccupancyAvgSun->bindParam(':id', $_GET['id']);
        $parkingOccupancyAvgSun->bindParam(':startDate', $startDate);
        $parkingOccupancyAvgSun->bindParam(':endDate', $endDate);
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
    
    <form id="dateRangeForm" action="parking_lot.php" method="get">
        <label for="startDate">Start Date: </label>
        <input type="date" name="startDate" value="<?php echo substr($startDate, 0, 10); ?>" min="<?php $time = strtotime("-1 year", time()); echo date('Y-m-d', $time); ?>">
        <label for="endDate">End Date: </label>
        <input type="date" name="endDate" value="<?php echo substr($endDate, 0, 10); ?>" max="<?php echo date('Y-m-d'); ?>">
        
        <label for="parkingSpotID">Parking Spot(s): </label>
        <select name="parkingSpotID">
            <option>all</option>
            <?php
                foreach ($parkingSpotRows as $row) {
                    echo "<option value=".$row["parking_spot_id"]." ".($parkingSpotID == $row["parking_spot_id"] ? "selected" : "").">";
                    echo $row["parking_spot_desc"];
                    echo "</option>";
                }
            ?>
        </select>
        
        <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
        <input type="submit" value="Update Search Range"> 
    </form><br>
    
    <h3> Historical Usage Level by Day of Week</h3>
    
    <center><canvas id="weeklyChart" width="800" height="400"></canvas></center><br>
    <?php echo "<input type='hidden' class='weeklyUsageLevel' value=".number_format($avgForMon, 2, '.', '').">";?>
    <?php echo "<input type='hidden' class='weeklyUsageLevel' value=".number_format($avgForTue, 2, '.', '').">";?>
    <?php echo "<input type='hidden' class='weeklyUsageLevel' value=".number_format($avgForWed, 2, '.', '').">";?>
    <?php echo "<input type='hidden' class='weeklyUsageLevel' value=".number_format($avgForThu, 2, '.', '').">";?>
    <?php echo "<input type='hidden' class='weeklyUsageLevel' value=".number_format($avgForFri, 2, '.', '').">";?>
    <?php echo "<input type='hidden' class='weeklyUsageLevel' value=".number_format($avgForSat, 2, '.', '').">";?>
    <?php echo "<input type='hidden' class='weeklyUsageLevel' value=".number_format($avgForSun, 2, '.', '').">";?>

    <h3>Historical Usage Level by Hour of Day</h3>
    
    <center><canvas id="hourlyChart" width="800" height="400"></canvas></center>
    <?php
        for($i = 0; $i < 24; $i++) {
            $parkingOccupancyAvgByHr = $dbConnection->prepare("SELECT AVG(isOccupied) as avg FROM occupancy_log WHERE HOUR(datetime) = :hr AND parking_lot_id = :id AND (datetime <= :endDate AND datetime >= :startDate)".$parkingSpotIDClause);
            $parkingOccupancyAvgByHr->bindParam(':id', $_GET['id']);
            $parkingOccupancyAvgByHr->bindParam(':hr', $i);
            $parkingOccupancyAvgByHr->bindParam(':startDate', $startDate);
            $parkingOccupancyAvgByHr->bindParam(':endDate', $endDate);
            $parkingOccupancyAvgByHr->execute();
            $returnedRowHr = $parkingOccupancyAvgByHr -> fetch();
            $avgForHr =  $returnedRowHr["avg"] == null ? 0 : $returnedRowHr["avg"];

            echo "<input type='hidden' class='hourlyUsageLevel' value=".number_format($avgForHr, 2, '.', '').">";
        }
    ?>
    
    <h5>*Usage Level is calculated by averaging the occupancy (1 or 0) of all of the logged data (within the past 365 days) for a given time period.</h5>

    <footer>Designed by: Aaron Vizzini & Wu Weibo</footer>
</html>