<?
    include "../php/functions.php";
    // session started in the functions.php file which is included
?>
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

    <div id="signInFail" class="modal fade">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Error</h4>
          </div>

          <div class="modal-body">
            <p id='account_details'></p>
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
              <h3 class="panel-title">Please Sign In</h3>
            </div>
            <div class="panel-body">
              <form data-toggle="validator" id='login' role="form" action="login.php" method="post">
                <fieldset>
                  <div class="form-group">
                    <input id="username" class="form-control" placeholder="E-mail" name="username" type="email" autofocus required>
                  </div>
                  <div class="form-group">
                    <input id="password" class="form-control" placeholder="Password" name="password" type="password" required>
                  </div>
                  <div class="checkbox">
                    <label>
                      <input name="remember" type="checkbox" value="Remember Me">Remember Me
                    </label>
                  </div>
                  <!-- Change this to a button or input when using this as a form -->
                  <input type="submit" class="btn btn-lg btn-success btn-block" value="Login">
                </fieldset>
              </form>
              <br>
              <p class="message text-center">Not registered? <a href="login_create.php">Create an account</a></p>
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
      $('#login').validator().on('submit', function(evt) { //prevents submission until form is validated
        if (evt.isDefaultPrevented()) {
          // handle the invalid form...
        } else {
          //evt.preventDefault(); // this prevents loading the page you post to
          // alert("hello");


          // // everything looks good!
          // var postData = $(this).serialize(); // postData is POST data with the string id of form elements
          // // like name,username, and password

          // var url = $(this).attr('action'); // in form html code as login_create.php, which is this page. using this page to reduce number of files
          // $.post(url, postData, function(data) {

          //   $('#account_details').html(data);
          //   dat
          //   if (data == "Sorry, but your username or password is invalid.") {
          //     $('#signInFail').modal('show');
          //   } else {
          //     window.location.href = "http://localhost/receipt_database/dashboard/pages/index.php";

          //   }

          // });


        }
      });
    </script>

  </body>

  </html>
  <?php // check database to see if username exists
    if (isset($_POST['username'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $validation = signIn($username,$password);
        
        if ($validation == "Sorry, but your username or password is invalid.")
        {
            echo "<script>$('#account_details').html('Sorry, but your username or password is invalid.'); $('#signInFail').modal('show'); </script>";
        }
        else {
            $_SESSION['username'] = $username;
            echo "<script> window.location.href = 'http://localhost/receipt_database/dashboard/pages/index.php';</script>";
            
        }
    }
    
    
    ?>