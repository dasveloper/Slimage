<?php include "includes/header.php"?>
<?php
if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    return header("Location: login");
}
if (!isset($_SESSION["active"]) || empty($_SESSION["active"])) {
  return header("Location: activate");
}
?>

  <?php include "includes/nav.php"?>
  <div class="index-wrapper">

    <div class="sidebar">
      <div class="add-folder-wrapper">
        <input type="text" class="hidden" id="active-folder" />

        <p class="folder-header">Folders</p>
        <button class="add-folder-button" data-toggle="modal" data-target="#new-folder-modal">
          <i class="material-icons">create_new_folder</i>
        </button>
      </div>

      <div id="folder-list" class="bottom">

      </div>
    </div>
    <div class="main-panel">
      <div class="upload-header">
        <div class="image-capacity">
          <h4 id="image-count">
            <span class="count"></span> Images Remaining</h4>

        </div>
        <div class="image-wrapper">
          <label class="btn btn-default btn-lg edit-mode">

            <input type="checkbox" class="hidden" id="editMode" />
            <i class="material-icons">delete</i>
          </label>

          <!-- Button trigger modal -->
          <button class="btn btn-primary btn-lg upload-image">
            Upload Images
          </button>
        </div>
      </div>
      <div class="bottom">
        <div class="alert alert-danger no-folder-alert">
          You must create a folder first.
        </div>
        <div class="img-panel">
        </div>
        <!-- Modal -->
        <div class="modal fade" id="image-upload-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <form action="functions/upload.php" class="dropzone styled" id="my-awesome-dropzone"></form>
                <p>Supported upload JPEG, PNG, WebP, TIFF, GIF.</p>

              </div>

            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        <!-- Modal -->

        <!-- Modal -->
        <div class="modal fade" id="new-folder-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                <div class="form-group new-folder-form">
                  <label for="folderNameInput">Folder Name:</label>
                  <input type="text" class="form-control input-lg" id="folderNameInput" placeholder="Enter name for new folder">
                </div>
                <div class="footer-modal">
                  <button type="button" class="btn btn-default btn-lg" data-dismiss="modal">Cancel</button>
                  <button type="button" class="btn btn-default btn-lg" id="create-folder">Create folder</button>
                </div>
              </div>

            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        <!-- Modal -->

        <div class="modal lightbox fade" id="lightbox-modal" tabindex="-1" role="dialog">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
              <div class="modal-body">
                <img id="lightbox-img">
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
  <div class="delete-menu">
    <p class="delete-count">
      <span id="count">0</span> selected</p>
    <div class="delete-actions">
      <a id="cancel-delete" href="javascript:;">Cancel</a>
      <button id="delete-selected" class="btn btn-danger">Delete All</button>
    </div>
  </div>
  <?php include "includes/footer.php"?>