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
    $newPin = rand (0, 9999);
    $newPin = sprintf( '%04d', $newPin );
    return $newPin;
}

echo generateRdnPin();

?>
<html>
    <body>
    <?php
        if (!empty($_POST['first_name']) && !empty($_POST['last_name']) ) {
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
                    echo "<tr><td>".$row['first_name']."</td><td>".$row['last_name']."</td><td>".$row['identification_token']."</td><td><button id='".$row['id']."'>Change</button></td></tr>";
                }
            ?>
        </table>
        <h2>Insert new User</h2>
            <form method="post">
                <div class="inputs"><span>First Name</span><input type="text" name="first_name" id="first_name"><br></div>
                <div class="inputs"><span>Last Name</span><input type="text" name="last_name" id="last_name"><br></div>
                <div class="btn"><input type="submit" value="Send"></div>

            </form>
        
    </body>
</html>