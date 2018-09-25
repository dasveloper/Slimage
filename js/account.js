$(function () {

    $.ajax({
        dataType: 'json',
        data: {
            'action': 'getAccount'
        },
        url: '../functions/ajax_functions.php'
    }).done(function (data) {
        if (data.status == 'success') {
            $("#js-email").text(data.user.email);
            $("#js-api-key").text(data.user.apiKey);
            $("#js-image-cap").text(data.user.imageCap);
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        // If fail
        console.log(textStatus + ': ' + errorThrown);
      });;

});
$("#reset-password-form-logged-in").submit(function (e) {
    e.preventDefault(); // avoid to execute the actual submit of the form.
    $.ajax({
      dataType: 'json',

      type: "POST",
      data: {
        'currentPassword': $("#current-password").val(),
        'newPassword': $("#new-password").val(),
        'confirmPassword': $("#confirm-password").val(),
        'action': 'resetPasswordLoggedIn'
      },
      url: '../functions/ajax_functions.php'
    }).done(function (data) {
      console.log(data);
      if (data.status == "success") {
        

      } else {
        $(".reset-error-message").html(data.message).show();
      }
      return;

    }).fail(function (data) {
      console.log(data);
      // If fail
      $(".login-message").html("Something went wrong, please try again").show();
    });

  });
