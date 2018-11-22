<?php
session_start();

$db = new PDO('mysql:host=localhost;dbname=challenge;charset=utf8mb4', 'jorge', 'challenge_accepted');

// Tests if it is logged or redirect to index
if (isset($_SESSION['pin'])){
    echo 'Welcome!';
}
else {
    header("Location:index.php"); 
}
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

//echo generateRdnPin();

?>
<html>
    <head>
        
    </head>
    <body>
    <?php
    
        if (isset($_GET['id'])) {
                       
            $id = $_GET['id'];
            $pin = generateRdnPin();
            $stmt = $db->prepare("UPDATE users SET identification_token=?  WHERE id=?");
            $stmt->execute([$pin, $id]);
            $stmt = $db->prepare("SELECT * FROM users WHERE id=?");
            $stmt->execute([$id]);
            $user = $stmt->fetch();
            echo '<div id="annouce">The new Pin of '.$user['first_name'].' '.$user['last_name'].' is <br><h3>'.$pin.'</h3></div>';
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
            <thead><th>First Name</th><th>Last Name</th><th>PIN</th><th>New PIN</th></thead>    
            <?php 
            
            // Retrieve all Users from DB
                
                $stmt = $db->query('SELECT * FROM users');
                foreach ($stmt as $row){
                    echo "<tr><td>".$row['first_name']."</td><td>".$row['last_name']."</td><td>".$row['identification_token']."</td><td><a href='admin.php?id=".$row['id']."'>Change PIN</a></td></tr>";
                }
            ?>
        </table>
        <h2>Insert new User</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="inputs"><span>First Name</span><input type="text" name="first_name" id="first_name"><br></div>
            <div class="inputs"><span>Last Name</span><input type="text" name="last_name" id="last_name"><br></div>
            <div class="btn"><input type="submit" value="Send"></div>
        </form>
    
    </body>
</html>