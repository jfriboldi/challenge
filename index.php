<?php
session_start();

?>
<!DOCTYPE html>
<html>
    <head>
    <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
        <div class="login">    
        <?php 
        if (isset($_POST['pin'])) {
            $pin = $_POST['pin'];
            $db = new PDO('mysql:host=localhost;dbname=challenge;charset=utf8mb4', 'jorge', 'challenge_accepted');
            $stmt = $db->prepare("SELECT * FROM users WHERE identification_token=?");
            $stmt->execute([$pin]);
            $user = $stmt->fetch();
            if ($user) { 
                $_SESSION['pin'] = $pin;
                echo '<div class="box"><h2>Welcome '.$user['first_name'].'   '.$user['last_name'].'</h2><a class="link" href="admin.php">Enter Admin</a></div>';

            }
            else { 
                session_destroy();
                echo '<div class="box"><h2 class="title">Please Enter Your PIN Again</h2>
                <div class="error"><p>PIN Not Found</p></div>
                <form action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'" method="post">
                    <input type="password" name="pin" id="pin">
                    <input type="submit" value="Submit">
                </form></div>';
             }
            
        } else { 
               echo '<div class="box"><h2 class="title">Please Enter Your Pin</h2>
               <form action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'" method="post">
                   <input type="password" name="pin" id="pin">
                   <input type="submit" value="Submit">
               </form></div>';
            }
        ?>
        </div>
    </body>
</html>
