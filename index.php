<?php
session_start();

?>
<!DOCTYPE html>
<html>
    <body>
        
        <?php 
        if (isset($_POST['pin'])) {
            $pin = $_POST['pin'];
            $db = new PDO('mysql:host=localhost;dbname=challenge;charset=utf8mb4', 'jorge', 'challenge_accepted');
            $stmt = $db->prepare("SELECT * FROM users WHERE identification_token=?");
            $stmt->execute([$pin]);
            $user = $stmt->fetch();
            if ($user) { 
                $_SESSION['pin'] = $pin;
                echo '<a href="admin.php">Enter Admin</a><br>Welcome '.$user['first_name'].'   '.$user['last_name'];

            }
            else { 
                session_destroy();
                echo '<span>PIN Not Found</span>
                <h2>Please Enter Your PIN Again</h2>
                <form action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'" method="post">
                    <input type="password" name="pin" id="pin">
                    <input type="submit">
                </form>';
             }
            
        } else { 
               echo '<h2>Please Enter Your Pin</h2>
               <form action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'" method="post">
                   <input type="password" name="pin" id="pin">
                   <input type="submit">
               </form>';
            }
        ?>
    </body>
</html>
