<?php
session_start();

$db = new PDO('mysql:host=localhost;dbname=challenge;charset=utf8mb4', 'jorge', 'challenge_accepted');

// Function to generate a Random Pin Number With 4 Numbers
function generateRdnPin() {
    $unique = false;
    
    while ($unique == false) {
        global $db;
        $newPin = rand (0, 9999);
        $newPin = sprintf( '%04d', $newPin );
        $stmt = $db->prepare("SELECT * FROM users WHERE identification_token=?");
        $stmt->execute([$newPin]);
        $pinExist = $stmt->fetch();
        if (!$pinExist) {
            $unique = true;
        }
    }
  
    return $newPin;
}

?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
    <div class="admin">
        <div class="main">    
            <?php
                // Tests if it is logged or redirect to index
                if (isset($_SESSION['pin'])){
                    $pin = $_SESSION['pin'];
                    $stmt = $db->prepare("SELECT * FROM users WHERE identification_token=?");
                    $stmt->execute([$pin]);
                    $user = $stmt->fetch();
                    echo '<h2>Welcome '.$user['first_name'].' '.$user['last_name'].'!</h2>';
                }
                else {
                    header("Location:index.php"); 
                }
                if (isset($_GET['id'])) {

                    $id = $_GET['id'];
                    $pin = generateRdnPin();
                    $stmt = $db->prepare("UPDATE users SET identification_token=?  WHERE id=?");
                    $stmt->execute([$pin, $id]);
                    $stmt = $db->prepare("SELECT * FROM users WHERE id=?");
                    $stmt->execute([$id]);
                    $user = $stmt->fetch();
                    echo '<div class="modal"><div class="announce"><span class="close">&times;</span><h2>The new Pin of '.$user['first_name'].' '.$user['last_name'].' is </h2><br><h1>'.$pin.'</h1></div></div>';
                }
                if (isset($_POST['first_name']) && isset($_POST['last_name']) ) {
                    $pin = generateRdnPin();
                    $firstName = $_POST['first_name'];
                    $lastName = $_POST['last_name'];
                    $stmt = $db->prepare("INSERT INTO users (first_name, last_name, identification_token) VALUES (?,?,?)");
                    $stmt->execute([$firstName, $lastName, $pin]);

                }
            ?>
            
                <table>
                    <thead><th class="cell">First Name</th><th class="cell">Last Name</th><th class="cell">PIN</th><th class="dif">New PIN</th></thead>    
                    <?php 

                    // Retrieve all Users from DB

                        $stmt = $db->query('SELECT * FROM users');
                        foreach ($stmt as $row){
                            echo '<tr><td class="cell">'.$row['first_name'].'</td><td class="cell">'.$row['last_name'].'</td><td class="cell">'.$row['identification_token'].'</td><td class="dif"><a href="admin.php?id='.$row['id'].'" class="chgpin">Change PIN</a></td></tr>';
                        }
                    ?>
                </table>
                <a class="link" href="logout.php">Log Out</a>
                <div>
                    <h2>Insert new User</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="inputs"><span>First Name</span><input type="text" name="first_name" id="first_name"><br></div>
                        <div class="inputs"><span>Last Name</span><input type="text" name="last_name" id="last_name"><br></div>
                        <div class="btn"><input type="submit" value="Send"></div>
                    </form>
                </div>    
            </div>    
        </div>
        <script>
            let modal = document.querySelector(".modal");
            let close = document.querySelector(".close");
            
            /* Close Modal if Click on the X */
            close.onclick = function() {
                modal.style.display = "none";
            }
            /* Close Modal if Click Everywhere Outside the Content */ 
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        </script>
    </body>
</html>