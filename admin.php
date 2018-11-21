<?php
session_start();
?>
<?php 

$db = new PDO('mysql:host=localhost;dbname=challenge;charset=utf8mb4', 'jorge', 'challenge_accepted');

$stmt = $db->query('SELECT * FROM users');
foreach ($stmt as $row)
{
    echo $row['first_name']."  ".$row['last_name']."   ".$row['identification_token'] . "\n";
}