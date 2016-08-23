<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>SB Admin 2 - Bootstrap Admin Theme</title>

  <!-- Bootstrap Core CSS -->
  <link href="../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- MetisMenu CSS -->
  <link href="../bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

  <!-- Custom CSS -->
  <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

  <!-- Custom Fonts -->
  <link href="../bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>
  <!-- Successfully created account modal-->

  <div id="createAccountSuccess" class="modal fade">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Success</h4>
        </div>

        <div class="modal-body">
          <p class='account_details'></p>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline btn-success" data-dismiss="modal">Sign In</button>
        </div>

      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->



  <div id="createAccountFail" class="modal fade">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Account Already Exists</h4>
        </div>

        <div class="modal-body">
          <p class='account_details'></p>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline btn-danger" data-dismiss="modal">Try Again</button>
        </div>

      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->



  <div class="container">
    <div class="row">
      <div class="col-md-4 col-md-offset-4">
        <div class="login-panel panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Create An Account</h3>
          </div>
          <div class="panel-body">
            <form data-toggle="validator" id="create_account" action="../php/login_create_process.php" method="post" role="form">
              <fieldset>
                <div class="form-group has-feedback">
                  <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                  <input class="form-control" placeholder="Name" name="name" type="text" autofocus required>
                </div>
                <div class="form-group has-feedback">
                  <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                  <input id = "username" class="form-control" placeholder="E-mail" name="username" type="email" data-error="Email is invalid." required>
                  <div class="help-block with-errors"></div>
                </div>
                <div class="form-group has-feedback">
                  <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                  <input id="password" class="form-control" placeholder="Password" name="password" type="password" data-minlength="6" required>
                  <div class="help-block">Minimum of 6 characters</div>
                </div>
                <div class="form-group has-feedback">
                  <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                  <input class="form-control" placeholder="Confirm Password" name="confirm_password" type="password" data-match="#password" data-match-error="Passwords don't match" required>
                  <div class="help-block with-errors"></div>
                </div>
                <!-- Change this to a button or input when using this as a form -->
                <input type="submit" class="btn btn-lg btn-success btn-block" value="Create">
              </fieldset>
            </form>
            <br>
            <p class="message text-center">Already Registered? <a href="login.php">Sign In</a></p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- jQuery -->
  <script src="../bower_components/jquery/dist/jquery.min.js"></script>

  <!-- Bootstrap Core JavaScript -->
  <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

  <!-- Metis Menu Plugin JavaScript -->
  <script src="../bower_components/metisMenu/dist/metisMenu.min.js"></script>

  <!-- Custom Theme JavaScript -->
  <script src="../dist/js/sb-admin-2.js"></script>

  <!-- Form Validation JavaScript -->
  <script src="../bower_components/bootstrap-validator/dist/validator.js"></script>

  <script>
    $('#create_account').validator().on('submit', function(evt) {
      if (evt.isDefaultPrevented()) {
        // // handle the invalid form...
        // $('#createAccountFail').modal('show');
      } else {
        // everything looks good!
        evt.preventDefault();
         
        var postData = $(this).serialize(); // postData is POST data with the string id of form elements
        // like name,username, and password

        var url = $(this).attr('action'); // in form html code as login_create.php, which is this page. using this page to reduce number of files
        $.post(url, postData, function(data) {

          $('.account_details').html(data);
          //alert(data);
          if(data == "Sorry, but your username is already taken."){
              $('#createAccountFail').modal('show');
          }
          else {
                $('#createAccountSuccess').modal('show');
          }

        });

      }
    });

    $('#createAccountSuccess').on('hidden.bs.modal', function() { // when account created successfully modal closes, redirect to sign in page
      window.location.href = "http://localhost/receipt_database/dashboard/pages/login.php";
    });
  </script>

</body>

</html>