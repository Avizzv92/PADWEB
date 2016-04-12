<div id="loginBox">
    <?php
        session_start();
        
        //Based on whether or not the person is logged in, display the correct fields for login, logout, register, and profile viewing.
        if(!isset($_SESSION['valUser'])){
            echo "<form action=\"users/validateUser.php\" method=\"post\">
                    <input id=\"username\" type=\"text\" name=\"username\" placeholder=\"User\">
                    <input id=\"password\" type=\"password\" name=\"password\" placeholder=\"Pass\">
                    <input type=\"submit\" value=\"Login\">
                    <button type=\"button\" onclick=\"location.href='newUser.php';\">Register</button>
                    </form>";
            
            if($_SESSION['invalidLogin'] == true) {
                echo "<div id=\"failedLogin\">Login Failed</div>";
                $_SESSION['invalidLogin'] = false;
            }
        } else {
            echo "<p id=\"usernameLabel\">".$_SESSION['valUser']."</p> <input type=\"submit\" value=\"Admin\" onclick=\"window.location.href='admin.php'\"> 
                <form id=\"logoutForm\" action=\"users/logout.php\"> <input type=\"submit\" value=\"Logout\"> </form>";
        }
    ?>
</div>