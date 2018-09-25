<?php include "includes/header.php"?>


<?php include "includes/nav.php"?>
<?php
if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    return header("Location: login");
}
if (!isset($_SESSION["active"]) || empty($_SESSION["active"])) {
  return header("Location: activate");
}
?>
    <div class="account-wrapper">
        <div class="account-inner-wrapper">
            <div class="user-email">
                <h4 class="field-label"> Email:</h4>
                <p class="field" id="js-email"></p>
            </div>
            <div class="user-api-key">
                <h4 class="field-label">API key:</h4>
                <p class="field api-key" id="js-api-key"></p>
            </div>
            <div "account-section">
                <h4 class="field-label">Subscription Tier:</h4>
                <p class="field" id="js-email">Free tier</p>
                <h4 class="field-label">Image Capacity:</h4>
                <p class="field" id="js-image-cap">0</p>
            </div>
            <div class="reset-password">
                <button class="btn btn-default" data-toggle="collapse" data-target="#reset-password-collapse">Reset Password</button>
            </div>
            <div id="reset-password-collapse" class="collapse">
                <div class="alert alert-danger reset-error-message">
                    
                </div>
                <form id="reset-password-form-logged-in">
                    <div class="form-group">
                        <input type="password" name="current-password"  id="current-password" tabindex="2" class="form-control input-lg" placeholder="Current Password"
                            required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="new-password" id="new-password" tabindex="2" class="form-control input-lg" placeholder="New Password"
                            required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="confirm-password" id="confirm-password" tabindex="2" class="form-control input-lg" placeholder="Confirm New Password"
                            required>
                    </div>
                    <div class="reset-password">
                        <button id="js-reset-password" class="btn btn-primary">Submit</button>
                </form>
                </div>
            </div>

        </div>
    </div>
    <script src="js/account.js"></script>

    <?php include "includes/footer.php"?>