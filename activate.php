<?php include("includes/header.php") ?>



  <?php include("includes/nav.php") ?>



<div class="login-wrapper">
<div class="panel-wrapper">
	<div class="alert alert-danger login-message" role="alert">
</div>
				<div class="panel panel-login">
					<?php 
					if (false) { ?>
					<div class="panel-heading">
						<div class="row">
							<h3>Enter your activation code</h3>
						</div>
					</div>
					<div class="panel-body">
							<div class="col-lg-12">
								<form id="activation-form"  method="post" role="form" style="display: block;">
									<div class="form-group">
										<input type="text" name="activation_code" id="activation_code" tabindex="1" class="form-control" placeholder="Activation code" required>
									</div>

									<div class="form-group">
										<div class="row">
											<div class="col-sm-6 col-sm-offset-3">
												<input type="submit" name="activation-submit" id="activation-submit" tabindex="4" class="form-control btn btn-login" value="Activate">
											</div>
										</div>
									</div>

								</form>

						</div>
					<?php } ?>
					<div class="panel-heading">
						<div class="row">
							<h3 class="text-primary-color">Awaiting activation</h3>
						</div>
					</div>
					<div class="panel-body">
							<div class="col-lg-12">
								<form id="activation-form"  method="post" role="form" style="display: block;">
									<h4>Thank you for registering! You should receive an activation email shortly. Once activated please click the link to login.</h4>
									<a href="login" class="btn btn-primary">Login</a>
								</form>

						</div>
					</div>
				</div>
			</div>
</div>
	<?php include("includes/footer.php") ?>
