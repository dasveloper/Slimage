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
	<div class="alert alert-danger login-message" role="alert">
</div>
				<div class="panel panel-login">

					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-6">
								<a href="login" class="active" id="login-form-link">Login</a>
							</div>
							<div class="col-xs-6">
								<a href="register" id="">Register</a>
							</div>
						</div>
						<hr>
					</div>
					<div class="panel-body">
							<div class="col-lg-12">
								<form id="login-form"  method="post" role="form" style="display: block;">
									<div class="form-group">
										<input type="text" name="email" id="email" tabindex="1" class="form-control" placeholder="Email" required>
									</div>
									<div class="form-group">
										<input type="password" name="password" id="login-password" tabindex="2" class="form-control" placeholder="Password" required>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-sm-6 col-sm-offset-3">
												<input type="submit" name="login-submit" id="login-submit" tabindex="4" class="form-control btn btn-login" value="Log In">
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-lg-12">
												<div class="text-center">
													<a href="" tabindex="5" class="forgot-password" data-toggle="modal" data-target="#doesntexist-modal">Forgot Password?</a>
												</div>
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
