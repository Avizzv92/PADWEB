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
    <h2> Select the parking lot of interest. </h2>
    
    <table>
        <tr>
            <th>ID</th>
            <th>Location</th>
        </tr>
        <?php
            include("dbConnection.php");
            $dbConnection = database_connection();
            $parkingLotSelect = $dbConnection->prepare("SELECT * FROM parking_lot");
            $parkingLotSelect->execute();

            while ($row = $parkingLotSelect->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td><a href=\"parking_lot.php?id=".$row['id']."\">".$row['id']."</a></td>";
                echo "<td><a href=\"parking_lot.php?id=".$row['id']."\">".$row['location']."</a></td>";
                echo "</tr>";
            }
        ?>
    </table>
    
    <footer>Designed by: Aaron Vizzini & Wu Weibo</footer>
</html>