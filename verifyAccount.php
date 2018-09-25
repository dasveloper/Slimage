<?php ob_start();


session_start();

include("db.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "9JhJkhewj";
$dbname = "login_db";
$cloudFrontUri = 'd2yp72sq5kkz8x.cloudfront.net/';

//$con = mysqli_connect('localhost', 'root', '9JhJkhewj', 'login_db');
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



if (isset($_GET['verificationId']) && !empty($_GET['verificationId'])) {

      global $conn;


      $verificationId = $_GET['verificationId'];

              $stmt = $conn->prepare("UPDATE users set active = true WHERE validation_code = :validation_code");
              $stmt->bindParam(':validation_code', $verificationId);
              $stmt->execute();


              $_SESSION['active'] = true;
              $_SESSION['fromActiveEmail'] = true;

              return header("Location: login");


            }
    
    ?>