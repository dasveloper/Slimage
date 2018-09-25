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



if (isset($_GET['resetCode']) && !empty($_GET['resetCode'])) {

      global $conn;


      $resetCode = $_GET['resetCode'];
            } else{
				return header("Location: index.php");


            }
    
    ?>

    <?php include("includes/header.php") ?>



  <?php include("includes/nav.php") ?>


<div class="login-wrapper">
<div class="panel-wrapper">
<?php

if (isset($_SESSION["active"]) && !empty($_SESSION["active"])) {
	if (isset($_SESSION["fromActiveEmail"]) && !empty($_SESSION["fromActiveEmail"])) {

		echo '<div class="alert alert-success"><p>Account activated, please log in.</p></div>';
	
	}
}
?>
	<div class="alert alert-danger reset-error-message" role="alert">
</div>
				<div class="panel panel-login">
					<div class="panel-body">
							<div class="col-lg-12">
								<form id="reset-password-form"  method="post" role="form" style="display: block;">
                                <div class="form-group">
										<input type="password" name="password" id="resetPassword" tabindex="2" class="form-control" placeholder="Password" required>
									</div>
									<div class="form-group">
										<input type="password" name="confirmPassword" id="resetPasswordConfirm" tabindex="2" class="form-control" placeholder="Confirm Password" required>
                                    </div>
                                    <input id="resetCode" type="hidden" value="<?php echo $resetCode ?>">
									<div class="form-group">
										<div class="row">
											<div class="col-sm-6 col-sm-offset-3">
												<input type="submit" name="reset-submit" id="reset-submit" tabindex="4" class="form-control btn btn-login" value="Reset Password">
											</div>
										</div>
									</div>
								</form>

						</div>
					</div>
				</div>
			</div>
</div>
	<?php include("includes/footer.php") ?>
