 <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/">Slimage</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <?php if(!logged_in()):?>

            <li><a href="login">Login</a></li>
            <li><a href="register">Register</a></li>
            <li><a href="setup">Setup</a></li>
            <li><a href="help">Help</a></li>

          <?php endif; ?>

           <?php if(logged_in()):?>
             <li><a href="/dashboard">Dashboard</a></li>

             <li><a href="setup">Setup</a></li>
             <li><a href="account">Account</a></li>
             <li><a href="help">Help</a></li>
             <li><a href="logout">Logout</a></li>

             <li class="upgrade-nav"><a href="account" data-toggle="modal" data-target="#doesntexist-modal">Upgrade</a></li>



         <?php endif; ?>


          </ul>
        </div><!--/.nav-collapse -->
    </nav>
    <div class="modal fade" id="doesntexist-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog modal">
            <div class="modal-content">
              <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

               <h3>Sorry this feature does not yet exist.</h3>
              </div>

            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>