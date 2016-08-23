<?php
include "../php/functions.php";
include '../php/connection.php';
// this updates the table after editing
// session started in functions.php

if(!isset($_SESSION['username'])) { // logout is session ends
  header('Location: login.php');
  }

if(isset($_POST['delete'])){
    //echo "<h1>hello</h1>";
    deleteReceipt($_POST['id_receipt']);
    
}

else if (isset($_POST['update'])){
    $id = $_POST['id_receipt'];
    $name = $_POST['name_receipt'];
    $total = $_POST['total_receipt'];
    $date = $_POST['date_receipt'];
    updateReceipt($id,$name,$total,$date);
    
}

else if (isset($_POST['add'])){
    $name = $_POST['add_name_receipt'];
    $total = $_POST['add_total_receipt'];
    $date = $_POST['add_date_receipt'];
    $description = $_POST['description_receipt'];
    addReceipt($name,$total,$date,$description);
    
    
}

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

    <!-- DataTables CSS -->
    <link href="../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="../bower_components/datatables-responsive/css/responsive.dataTables.scss" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- Datetime picker -->
    <link rel="stylesheet" href="../bower_components/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

  </head>

  <body>
    <!-- Instructions Modal and Carousel -->
    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">

            <!-- Indicators -->
            <ol class="carousel-indicators">
              <li data-target="#carousel-example-generic" data-slide-to="0" style="color:blue;" class="active"></li>
              <li data-target="#carousel-example-generic" data-slide-to="1" style="color:blue;"></li>
              <li data-target="#carousel-example-generic" data-slide-to="2" style="color:blue;"></li>
            </ol>


            <!-- Wrapper for slides -->
            <div class="carousel-inner">
              <div class="item active">
                <img class="img-responsive" src="../images/refresh.png" alt="...">
                <div class="carousel-caption" style="color:red;">
                  Click the refresh button to load receipts from your email. The page will load for a maximum of around 10 minutes to sync a maximum of 170 receipts at a time. If an error shows up, log back in and click the refresh button again. Hopefully it works!
                </div>
              </div>
              <div class="item">
                <img class="img-responsive" src="../images/dashboard.png" alt="...">
                <div class="carousel-caption">

                </div>
              </div>
              <div class="item">
                <img class="img-responsive" src="../images/table.png" alt="...">
                <div class="carousel-caption">

                </div>
              </div>
            </div>

            <!-- Controls -->
            <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
              <span class="glyphicon glyphicon-chevron-left"></span>
            </a>
            <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
              <span class="glyphicon glyphicon-chevron-right"></span>
            </a>
          </div>
        </div>
      </div>
    </div>

    <!--Add receipts modal-->
    <div id="addTableModal" class="modal fade">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button id='close' type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title">Add</h4>
          </div>


          <br>

          <div class="modal-body">
            <form method="post" id="add_table" class="form" action="receipts.php">
              <input type="hidden" name="add_id_receipt" id="add_id_receipt" class="form-control">

              <label>Name:</label>
              <div class="form-group">
                <input type="text" name="add_name_receipt" id="add_name_receipt" class="form-control" placeholder="Name of Company" required>
              </div>

              <label>Total:</label>
              <div class="form-group">
                <input type="text" name="add_total_receipt" id="add_total_receipt" class="form-control" placeholder="Receipt Total" required>
              </div>

              <label>Date:</label>
              <div class="form-group">
                <div class='input-group date' id='datetimepicker1'>
                  <input type="text" name="add_date_receipt" id="add_date_receipt" class="form-control" placeholder="Receipt Date" required>
                  <span class="input-group-addon">
    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>
              </div>

              <label>Receipt (Description):</label>
              <div class="form-group">
                <input type="text" name="description_receipt" id="description_receipt" class="form-control" placeholder="Description of Purchase">
              </div>


              <div class="form-group text-right">
                <input type="submit" class="btn btn-outline btn-success" name="add" value="Add">
              </div>

            </form>

          </div>

        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->




    <!--Edit or delete receipts modal-->
    <div id="updateTableModal" class="modal fade">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button id='close' type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title">Update</h4>
          </div>


          <br>

          <div class="modal-body">
            <form method="post" id="update_table" class="form" action="receipts.php">
              <input type="hidden" name="id_receipt" id="id_receipt" class="form-control">
              <label>Name:</label>
              <div class="form-group">
                <input type="text" name="name_receipt" id="name_receipt" class="form-control" placeholder="Name of Company" required>
              </div>
              <label>Total:</label>
              <div class="form-group">
                <input type="text" name="total_receipt" id="total_receipt" class="form-control" placeholder="Receipt Total" required>
              </div>
              <label>Date:</label>
              <div class="form-group">
                <div class='input-group date' id='datetimepicker2'>
                  <input type="text" name="date_receipt" id="date_receipt" class="form-control" placeholder="Receipt Date" required>
                  <span class="input-group-addon">
    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>

              </div>

              <div class="form-group text-right">
                <input type="submit" class="btn btn-outline btn-danger" name="delete" value="Delete">
                <input type="submit" class="btn btn-outline btn-success" name="update" value="Update">
              </div>

            </form>

          </div>

        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <div id="wrapper">

      <!-- Navigation -->
      <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php">SB Admin v2.0</a>
        </div>
        <!-- /.navbar-header -->

        <ul class="nav navbar-top-links navbar-right">
          <button type="button" class="btn btn-outline btn-primary btn-sm" data-toggle="modal" data-target=".bs-example-modal-lg">Tour</button>
          <li class="dropdown">
            <a id='refreshButton' href="#">
              <i class="fa fa-refresh fa-fw"></i>
            </a>

          </li>
          <!-- /.dropdown -->

          <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
              <i class="fa fa-bell fa-fw"></i> <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-alerts">
              <li>
                <a href="#">
                  <div>
                    <i class="fa fa-comment fa-fw"></i> New Comment
                    <span class="pull-right text-muted small">4 minutes ago</span>
                  </div>
                </a>
              </li>
              <li class="divider"></li>
              <li>
                <a href="#">
                  <div>
                    <i class="fa fa-twitter fa-fw"></i> 3 New Followers
                    <span class="pull-right text-muted small">12 minutes ago</span>
                  </div>
                </a>
              </li>
              <li class="divider"></li>
              <li>
                <a href="#">
                  <div>
                    <i class="fa fa-envelope fa-fw"></i> Message Sent
                    <span class="pull-right text-muted small">4 minutes ago</span>
                  </div>
                </a>
              </li>
              <li class="divider"></li>
              <li>
                <a href="#">
                  <div>
                    <i class="fa fa-tasks fa-fw"></i> New Task
                    <span class="pull-right text-muted small">4 minutes ago</span>
                  </div>
                </a>
              </li>
              <li class="divider"></li>
              <li>
                <a href="#">
                  <div>
                    <i class="fa fa-upload fa-fw"></i> Server Rebooted
                    <span class="pull-right text-muted small">4 minutes ago</span>
                  </div>
                </a>
              </li>
              <li class="divider"></li>
              <li>
                <a class="text-center" href="#">
                  <strong>See All Alerts</strong>
                  <i class="fa fa-angle-right"></i>
                </a>
              </li>
            </ul>
            <!-- /.dropdown-alerts -->
          </li>
          <!-- /.dropdown -->
          <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
              <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
              <li><a href="profile.php"><i class="fa fa-user fa-fw"></i> User Profile</a>
              </li>
              <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
              </li>
              <li class="divider"></li>
              <li><a href="login.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
              </li>
            </ul>
            <!-- /.dropdown-user -->
          </li>
          <!-- /.dropdown -->
        </ul>
        <!-- /.navbar-top-links -->

        <div class="navbar-default sidebar" role="navigation">
          <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
              <li class="sidebar-search">
                <div class="input-group custom-search-form">
                  <input type="text" class="form-control" placeholder="Search...">
                  <span class="input-group-btn">
        <button class="btn btn-default" type="button">
        <i class="fa fa-search"></i>
        </button>
        </span>
                </div>
                <!-- /input-group -->
              </li>
              <li>
                <a href="index.php"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
              </li>

              <li>
                <a href="receipts.php"><i class="fa fa-table fa-fw"></i> Receipts</a>
              </li>
            </ul>
          </div>
          <!-- /.sidebar-collapse -->
        </div>
        <!-- /.navbar-static-side -->
      </nav>

      <div id="page-wrapper">
        <div class="row">
          <div class="col-lg-12">
            <h1 class="page-header">Receipts</h1>
            <button type="button" class="btn btn-outline btn-success" data-toggle="modal" data-target="#addTableModal">Add Receipt</button>
          </div>
          <!-- /.col-lg-12 -->
        </div>
        <br>
        <!-- /.row -->
        <div class="row">
          <div class="col-lg-12">
            <div class="panel panel-default">
              <div class="panel-heading">
                DataTables Advanced Tables
              </div>
              <!-- /.panel-heading -->
              <div class="panel-body">
                <div class="dataTable_wrapper">
                  <table width="100%" class="table table-striped table-bordered table-hover" id="receipts_table">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Total</th>
                        <th>Date</th>
                        <th>Receipt</th>
                        <th>Edit</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
        global $connection;
        $username = $_SESSION['username'];
        $query = "SELECT * FROM receipts WHERE username = '$username' ";
        $result = mysqli_query($connection, $query); //making it a variable to check if it works
        
        if(!$result){
            die('Query Failed' . mysqli_error($connection));
    }
    while($row = mysqli_fetch_array($result)){
        echo "<tr class='table' id={$row['id']}>";
        echo "<td>{$row['name']}</td>";
        echo "<td>{$row['total']}</td>";
        $date = date('m/d/Y', $row['dates']); // put date into format for table
        echo "<td>{$date}</td>";
        echo "<td><button type='button' class='center-block btn btn-primary btn-circle receipt'><i class='fa fa-list'></i>
        </button></td>";
        echo "<td><input type='button' class='center-block btn btn-outline btn-primary edit' value='Edit'></td>";
        echo "</tr>";
        
    }
    
    ?>
                    </tbody>

                  </table>
                </div>
                <!-- /.table-responsive -->
              </div>
              <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
          </div>
          <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->


      </div>
      <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="../bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- DataTables JavaScript -->
    <script src="../bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

    <!-- Datetime picker -->
    <script type="text/javascript" src="../bower_components/moment/min/moment.min.js"></script>
    <script type="text/javascript" src="../bower_components/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
      $(document).ready(function() {
        $('#receipts_table').DataTable({ // makes receipt table responsive
          responsive: true
        });

      });

      $('.receipt').click(function(evt) { // when someone click on receipt icon to see the receipt this goes to another page to show the receipt
        $.ajax({
          url: "view_receipt.php",
          type: 'POST',
          data: {
            receipt_id: $(this).closest('tr').attr('id')
          },
          success: function(data) {
            window.open("view_receipt.php", '_blank');
           // window.location.href = ;
          }
        });



      })

      $('.edit').click(function(evt) { // when the edit button is clicked
        $('#id_receipt').val($(this).closest('tr').attr('id'));
        $('#name_receipt').val($(this).closest('tr').find('td:nth-child(1)').text());
        $('#total_receipt').val($(this).closest('tr').find('td:nth-child(2)').text());
        $('#date_receipt').val($(this).closest('tr').find('td:nth-child(3)').text());
        $('#updateTableModal').modal('show');

      });

      $(function() {
        $('#datetimepicker1').datetimepicker();
      });
      $(function() {
        $('#datetimepicker2').datetimepicker();
      });

      $('#refreshButton').click(function(evt) { // link for dropdown item does not work so this function takes care of redirecting to new page
        window.location.href = '../php/oauth2callback.php';
      });
    </script>
    <style>
      .carousel-indicators li {
        /*  changes the color of the tour slide show indicators */
        background-color: #999;
        background-color: rgba(70, 70, 70, .25);
      }
      
      .carousel-indicators .active {
        background-color: #444;
      }
    </style>

  </body>

  </html>