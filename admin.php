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
        <script src="jquery-2.1.4.js"></script>
        <script src="scripts.js"></script>
    </head>
 
<body>
    <a id="headerLink" href="index.php"><h1>Parking Availability Detection</h1></a>
    <h2> Administration of Your Parking Lots. </h2>
    
    <?php include 'loginBox.php'; ?>
    <br>
    <h3>Create New Parking Lot</h3>
    <div id="formBox">
        <form id="standardForm" action="lots/addLot.php" method="post">
            <input type="text" name="location" placeholder="location" maxlength="255" required ><br>
            <input type="submit" value="Create">
        </form><br>
    </div>
    
    <h3>My Parking Lots</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Location</th>
            <th>Private Key</th>
            <th>Modify ROI Thresholds</th>
            <th></th>
        </tr>
        <?php
            include("dbConnection.php");
            $dbConnection = database_connection();
            $parkingLotSelect = $dbConnection->prepare("SELECT * FROM parking_lot WHERE user_id = :user_id");
            $parkingLotSelect->bindParam(':user_id', $_SESSION['user_id']);
            $parkingLotSelect->execute();

            while ($row = $parkingLotSelect->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td><a href=\"parking_lot.php?id=".$row['id']."\">".$row['id']."</a></td>";
                echo "<td><a href=\"parking_lot.php?id=".$row['id']."\">".$row['location']."</a></td>";
                echo "<td><a href=\"parking_lot.php?id=".$row['id']."\">".$row['pKey']."</a></td>";
                echo "<td><form action=\"roiModify.php\" method=\"get\"> <input type=\"hidden\" name=\"id\" value=\"".$row['id']."\"> <input type=\"submit\" value=\"Manage\"> </form> </td>";
                echo "<td><form action=\"lots/deleteLot.php\" method=\"post\"> <input type=\"hidden\" name=\"id\" value=\"".$row['id']."\"> <input type=\"submit\" value=\"Delete\"> </form> </td>";
                echo "</tr>";
            }
        ?>
    </table>
    
    <footer>Designed by: Aaron Vizzini & Wu Weibo</footer>
</html>