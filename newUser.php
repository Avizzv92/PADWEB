<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Create New User</title>
        <link rel="stylesheet" href="styles.css">
        <script src="https://code.jquery.com/jquery-2.1.4.min.js "></script>
        <script src="scripts.js"></script>
    </head>
    <body>
        <header>
          <div id="leftHeaderSection">
              <h1>New User</h1>
          </div>

          <div id="rightHeaderSection">
              <?php include 'loginBox.php'; ?><br>
          </div>
        </header>

        <div id="formBox">
            <form id="standardForm" action="users/register.php" method="post">
                <h3>Create New User</h3>
                <?php 
                    session_start();
                    //Print possible error messages that occured in the user creation process
                    if($_SESSION['error_msg']){
                        echo "<div id=\"failedLogin\">".$_SESSION['error_msg']."</div>";
                        unset($_SESSION['error_msg']);
                    }
                ?>
                <input type="text" name="username" placeholder="username" maxlength="35" required ><br><br>
                <input type="password" name="password" placeholder="password" maxlength="50" required ><br><br>
                <input type="password" name="confirm" placeholder="confirm" maxlength="50" required ><br><br>

                <input type="button" value="Cancel" onclick="window.location.href='index.php';">
                <input type="submit" value="Create">
            </form> 
        </div>
    </body>
</html>