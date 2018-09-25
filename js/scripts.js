Dropzone.autoDiscover = false;
// or disable for specific dropzone:
// Dropzone.options.myDropzone = false;

$(function () {
  $(window).resize(function() {
    if ($(window).width()  > 750) {
        $("#folder-list").removeClass('sticky');
    } else {
      $("#folder-list").addClass('sticky');
    }
}).resize(); 
  // Now that the DOM is fully loaded, create the dropzone, and setup the
  // event listeners
  if ($(".dropzone").length > 0) {
    var myDropzone = new Dropzone(".dropzone");
    myDropzone.on("success", function (file, responseText) {
      console.log(responseText);
      var results = JSON.parse(responseText);
      if (results.status == "success") {
        getImages($("#active-folder").val());
        getImageCount();
      } else {
        $(file.previewElement).find('.dz-error-message').text(ååresults.message);

      }
    });
    myDropzone.on("error", function (file, message, xhr) {
      var results = JSON.parse(message);
      if (results.status == "error") {

        console.log(message);
        $(file.previewElement).find('.dz-error-message').text(results.message);
      }
    });
    myDropzone.on("sending", function (file, xhr, formData) {
      formData.append("folderId", $("#active-folder").val());
    });
    $('#image-upload-modal').on('hidden.bs.modal', function () {
      myDropzone.removeAllFiles();
    })
  }

  if ($("#folder-list").length > 0) {
    //Get folders
    $.ajax({
      dataType: 'json',
      data: {
        'action': 'getFolders'
      },
      url: '../functions/ajax_functions.php'
    }).done(function (data) {
      if (data.status == 'success') {
        $.each(data.results, function (i, folder) {
          var el = $('<a/>')
            .attr("data-folder-id", folder.folderId)
            .addClass("folder-name")
            .text(folder.folderName)

            var arrow = $('<i/>')
            .text('keyboard_arrow_down')
            .addClass('material-icons')
            .appendTo(el)
          if (i === 0) {
            el.addClass("active");
            $("#active-folder").val(folder.folderId);

            getImages(folder.folderId);
          }
          $("#folder-list").append(el);
        });
        return;


      }
      // If successful
    }).fail(function (jqXHR, textStatus, errorThrown) {
      // If fail
      console.log(textStatus + ': ' + errorThrown);
    });
    //Get folders

    getImageCount();
  }

  function getImageCount() {
    $.ajax({
      dataType: 'json',
      data: {
        'action': 'getImageCount'
      },
      url: '../functions/ajax_functions.php'
    }).done(function (data) {
      console.log(data);
      if (data.status == 'success') {

        $("#image-count .count").text(data.count);


      }
      // If successful
    }).fail(function (jqXHR, textStatus, errorThrown) {
      // If fail
      console.log(textStatus + ': ' + errorThrown);
    });


  }
  $("#register_email").on('change', function () {


    var email = $(this).val();



    $.post("ajax_functions.php", {
      email: email
    }, function (data) {
      $(".db-feedback").html(data);
    });




  });



  $("#activation-form").submit(function (e) {

    e.preventDefault(); // avoid to execute the actual submit of the form.
    console.log($("#activation_code").val())
    $.ajax({
      dataType: 'json',

      type: "POST",
      data: {
        'activation_code': $("#activation_code").val(),

        'action': 'activateUser'
      },
      url: '../functions/ajax_functions.php'
    }).done(function (data) {
      if (data.status == "success") {
        window.location = "/dashboard";
      } else {
        $(".login-message").html(data.message).show();
      }
      return;

    }).fail(function (data) {
      // If fail
      $(".login-message").html("Something went wrong, please try again").show();
    });

  });


  $("#login-form").submit(function (e) {

    e.preventDefault(); // avoid to execute the actual submit of the form.
    $.ajax({
      dataType: 'json',

      type: "POST",
      data: {
        'email': $("#email").val(),
        'password': $("#login-password").val(),

        'action': 'login'
      },
      url: '../functions/ajax_functions.php'
    }).done(function (data) {
      if (data.status == "success") {
        if (data.active)
          window.location = "/dashboard";
        else
          window.location = "activate";

      } else {
        $(".login-message").html(data.message).show();
      }
      return;

    }).fail(function (data) {
      // If fail
      $(".login-message").html("Something went wrong, please try again").show();
    });

  });

  $("#forgot-form").submit(function (e) {

    e.preventDefault(); // avoid to execute the actual submit of the form.
    $.ajax({
      dataType: 'json',

      type: "POST",
      data: {
        'email': $("#email").val(),
        'action': 'forgotPassword'
      },
      url: '../functions/ajax_functions.php'
    }).done(function (data) {
      console.log(data);
      if (data.status == "success") {
        
        $(".forgot-message").show();

      } else {
        $(".forgot-error-message").html(data.message).show();
      }
      return;

    }).fail(function (data) {
      console.log(data);
      // If fail
      $(".login-message").html("Something went wrong, please try again").show();
    });

  });

  $("#reset-password-form").submit(function (e) {
    e.preventDefault(); // avoid to execute the actual submit of the form.
    $.ajax({
      dataType: 'json',

      type: "POST",
      data: {
        'password': $("#resetPassword").val(),
        'confirmPassword': $("#resetPasswordConfirm").val(),
        'resetCode': $("#resetCode").val(),
        'action': 'resetPassword'
      },
      url: '../functions/ajax_functions.php'
    }).done(function (data) {
      console.log(data);
      if (data.status == "success") {
        
        window.location = "/dashboard";

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

  $("#register-form").submit(function (e) {

    e.preventDefault(); // avoid to execute the actual submit of the form.
    $.ajax({
      dataType: 'json',

      type: "POST",
      data: {
        'email': $("#register-email").val(),
        'password': $("#password").val(),
        'confirm_password': $("#confirm-password").val(),
        'action': 'register'
      },
      url: '../functions/ajax_functions.php'
    }).done(function (data) {
      if (data.status == "success") {
        window.location = "activate";
      } else {
        $(".login-message").html(data.message).show();
      }
      return;

    }).fail(function (data) {
      // If fail
      $(".login-message").html("Something went wrong, please try again").show();
    });

  });

  // $('#login-form-link').click(function(e) {
  //     $("#login-form").delay(100).fadeIn(100);
  //     $("#register-form").fadeOut(100);
  //     $('#register-form-link').removeClass('active');
  //     $(this).addClass('active');
  //     e.preventDefault();
  // });
  // $('#register-form-link').click(function(e) {
  //     $("#register-form").delay(100).fadeIn(100);
  //     $("#login-form").fadeOut(100);
  //     $('#login-form-link').removeClass('active');
  //     $(this).addClass('active');
  //     e.preventDefault();
  // });



  // $('#register-form').on('submit',function(){



  //     if($('#password').val()!=$('#confirm-password').val()){




  //     alert("Passwords don't match");
  //     return false;
  // }

  //     return true;

  // });
  var editModeActive = false;
  $(document).on('change', '#editMode', function () {
    if ($(this).is(":checked")) {
      editModeActive = true;
      $('.delete-checkbox').show();
    } else {
      clearDelete();

    }
  });

  $(document).on('change', '.toggle-delete', function () {
    var checkedBoxes = $('.toggle-delete:checkbox:checked');
    $("#count").text(checkedBoxes.length);
    if (checkedBoxes.length > 0) {
      $(".delete-menu").addClass("open");

    } else {
      $(".delete-menu").removeClass("open");
    }

  });

  function getImages(folderId) {
    console.log("Asdsadsad");
    $(".img-panel").empty();

    $.ajax({
      dataType: 'json',
      data: {
        'folderId': folderId,
        'action': 'getFolderImages'
      },
      url: '../functions/ajax_functions.php'
    }).done(function (data) {

      if (data.status == 'success') {
        console.log(data.results);
        if (data.results.length == 0){
          var noresults = $('<p/>')
          .addClass("noresults")
          .text("No images")
          $(".img-panel").append(noresults)

        } else {
        $.each(data.results, function (i, image) {

          var thumbnailwrapper = $('<div/>')
            .addClass("thumbnail-wrapper");





          var checkboxround = $('<div/>')
            .addClass("round delete-checkbox")
            .appendTo(thumbnailwrapper);

          var checkbox = $('<input/>')
            .attr("type", "checkbox")
            .attr("name", image.imageId)
            .attr("id", "checkbox-" + image.imageId)

            .addClass("toggle-delete")
            .text('folderName')
            .appendTo(checkboxround);

          var checkboxlabel = $('<label/>')
            .attr("for", "checkbox-" + image.imageId)
            .appendTo(checkboxround);

          var checkboxround = $('<i/>')
            .addClass("material-icons delete-icon")
            .text("block")
            .appendTo(checkboxlabel);


          var thumbnailimg = $('<div/>')
            .addClass("thumbnail-img")
            .appendTo(thumbnailwrapper);



          var img = $('<img/>')
            .addClass("slimage")

            .attr("src", "//" + data.baseUrl + image.ownerApiKey + "/placeholder/" + image.imageUrl)
            .appendTo(thumbnailimg);

          var overlay = $('<div/>')
            .addClass("thumbnail-overlay")
            .attr("data-imgsrc", "//" + data.baseUrl + image.ownerApiKey + "/" + image.imageUrl)

            .appendTo(thumbnailimg);

          var overlaytext = $('<span/>')
            .text("View Larger")
            .appendTo(overlay);


          var filename = $('<p/>')
            .addClass("file-name")
            .text(image.imageUrl)
            .appendTo(thumbnailwrapper);

          var link = $('<input/>')
            .addClass("link")
            .attr("readonly", "readonly")
            .val(data.baseUrl + image.ownerApiKey + "/placeholder/" + image.imageUrl)
            .appendTo(thumbnailwrapper);

          var copylink = $('<p/>')
            .addClass("copy-link")
            .text("CLICK TO COPY")
            .appendTo(thumbnailwrapper);


          $(".img-panel").append(thumbnailwrapper)

        });

      }




      } else{
        var noresults = $('<p/>')
        .addClass("noresults")
        .text("No images")
        $(".main-panel .bottom").append(noresults)

      }
      // If successful
    }).fail(function (jqXHR, textStatus, errorThrown) {
      // If fail
      console.log(textStatus + ': ' + errorThrown);
    });
  }
  $(document).on('click', '.folder-name', function () {

    if ($(this).parent().hasClass("sticky") && $(this).hasClass("active")){
      console.log("asdasdasddddd");
      $(this).parent().toggleClass("open");
      return;
    }
    $(".folder-name.active").removeClass("active");
    $(this).addClass("active");

    var folderId = $(this).data("folderId");
    $("#active-folder").val(folderId);
    $(this).parent().removeClass("open");

    getImages(folderId);

  });
  $(document).on('click', '#create-folder', function () {
    $(this).addClass('loading');

    var $name = $('#folderNameInput');
    if ($name.val().length <= 0) {
      $name.addClass("error");
    } else {
      $name.removeClass("error");
      var folderName = $name.val();
      $.ajax({
        dataType: 'json',
        data: {
          'folderName': folderName,
          'action': 'createFolder'
        },
        url: '../functions/ajax_functions.php'
      }).done(function (data) {
        if (data.status == 'success') {
          var el = $('<a/>')
            .attr("data-folder-id", data.results)
            .addClass("folder-name")
            .text(folderName)
          var arrow = $('<i/>')
            .text('keyboard_arrow_down')
            .addClass('material-icons')
            .appendTo(el)
            
          if ($("#folder-list").children("a").length === 0) {
            el.addClass("active");
          }
          $("#folder-list").append(el)
          $('#new-folder-modal').modal('hide');

        }
        // If successful
      }).fail(function (jqXHR, textStatus, errorThrown) {
        // If fail
        console.log(textStatus + ': ' + errorThrown);
      });
    }
  })
  $(document).on('click', '.thumbnail-overlay', function () {
    $("#lightbox-img").attr("src", $(this).data('imgsrc'));
    $("#lightbox-modal").modal("show");

  });
  $(document).on('click', '.link', function () {
    $el = $(this);
    var copyText = $el.val();
    $el.select();
    if (document.execCommand("copy")) {
      $el.siblings(".copy-link").text("COPIED").addClass("copied");
      setTimeout(function () {
        $el.siblings(".copy-link").text("CLICK TO COPY").removeClass("copied");

      }, 1000);
    }
  });
  $(document).on('click', '.copy-code', function () {
    $el = $(".setup-js");
    var copyText = $el.val();
    $el.select();
    if (document.execCommand("copy")) {
      $el.addClass("copied");
      setTimeout(function () {
        $el.removeClass("copied");

      }, 1000);
    }
  });
  function clearDelete() {

    $(".delete-menu").removeClass("open");

    $('.toggle-delete').removeAttr('checked');
    $("#count").text(0);
    $('#editMode').removeAttr('checked');
    editModeActive = false;
    $('.delete-checkbox').hide();
  }
  $(document).on('click', '.upload-image', function(e){
    if ($("#folder-list").children().length > 0) {
      $('#image-upload-modal').modal('show');
    } else{
      $(".no-folder-alert").fadeIn('fast', function() {
        setTimeout(function(){ $(".no-folder-alert").fadeOut('fast'); }, 1500);

        
    });
    }
  })
  $(document).on('click', '#cancel-delete', function () {
    clearDelete();
  });
  $(document).on('click', '#delete-selected', function () {
    if (editModeActive) {
      var checkedBoxes = $('.toggle-delete:checkbox:checked');
      if (checkedBoxes.length > 0) {
        checkedBoxes.each(function () {
          var $checkbox = $(this);
          var key = $checkbox.attr("name");
          $.ajax({
            data: {
              'keyname': key,
              'action': 'deleteImage'
            },
            url: '../functions/ajax_functions.php'
          }).done(function (data) {
            $checkbox.closest(".thumbnail-wrapper").remove();
            var checkedBoxes = $('.toggle-delete:checkbox:checked');

            $("#count").text(checkedBoxes.length);
            getImageCount();

            // If successful
          }).fail(function (jqXHR, textStatus, errorThrown) {
            // If fail
            console.log(textStatus + ': ' + errorThrown);
          });
        });
      }

    }

  });
});