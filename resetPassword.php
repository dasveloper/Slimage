<?php include("includes/header.php") ?>

<?php

	if(logged_in()) {

		//redirect("index.php");

	}


 ?>


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
	<div class="alert alert-success forgot-message" role="alert">
     An email has been sent to the provided email address.
</div>
<div class="alert alert-error forgot-error-message" role="alert">
   
</div>
				<div class="panel panel-login">
					<div class="panel-body">
							<div class="col-lg-12">
								<form id="forgot-form"  method="post" role="form" style="display: block;">
                                    <p>Enter your email to recieve a password reset link.</p>
									<div class="form-group">
										<input type="text" name="email" id="email" tabindex="1" class="form-control" placeholder="Email" required>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-sm-6 col-sm-offset-3">
												<input type="submit" name="forgot-submit" id="forgot-submit" tabindex="4" class="form-control btn btn-login" value="Log In">
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
