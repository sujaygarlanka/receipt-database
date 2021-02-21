<?php
include "../php/functions.php";
include '../php/connection.php';

if(!isset($_SESSION['username'])) {
    header('Location: login.php'); // logout if user session ends
}
$user = $_SESSION['username'];
$user = getUser($user);
$numberOfReceipts = $user['receipts_left']; // number of receipts left to sync
if ($numberOfReceipts == 500){
    $numberOfReceipts = $numberOfReceipts . '+';
}
if(isset($_POST['save'])){
    
    $edit_budget_date = $_POST['edit_budget_date'];
    $edit_budget = floatval($_POST['edit_budget']);
    editBudget($edit_budget_date,$edit_budget);
    
}
$recentReceipts = recentReceipts();
$budgetData = getBudgetData();
$spent = $budgetData[0];
$budget = $budgetData[1];
$budget_date = $budgetData[2];

?>

  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Midas</title>

    <!-- Bootstrap Core CSS -->
    <link href="../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Timeline CSS -->
    <link href="../dist/css/timeline.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="../bower_components/morrisjs/morris.css" rel="stylesheet">

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
    <meta name="google-signin-client_id" content="847988252764-9e1mcnedo52037n0l0scf3hdo2bpgnmv.apps.googleusercontent.com">
    <script src="https://apis.google.com/js/platform.js?onload=onLoad" async defer></script>
  </head>

  <body>

    <!-- Instructions Modal and Carousel -->
    <div id='tour' class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
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
                  Click the refresh button to load receipts from your email. The page will load for a maximum of around 5 minutes to sync a test of around 30 receipts at a time. Allow it to load and don't click anything. If an error shows up, log back in and click the refresh
                  button again. Hopefully it works as it should!
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


    <!--Update budget modal-->
    <div id="editBudgetModal" class="modal fade">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button id='close' type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title">Edit Monthly Budget</h4>
          </div>
          <br>
          <div class="modal-body">
            <form method="post" class="form" action="index.php">

              <label>Budget:</label>
              <div class="form-group">
                <input type="text" name="edit_budget" id="edit_budget" class="form-control" placeholder="Budget" required>
              </div>

              <label>Recurring Date:</label>
              <div class="form-group">
                <div class='input-group date' id='datetimepicker1'>
                  <input type="text" name="edit_budget_date" id="edit_budget_date" class="form-control" placeholder="Starting Date for Budget" required>
                  <span class="input-group-addon">
    <span class="glyphicon glyphicon-calendar"></span>
                  </span>
                </div>
              </div>


              <div class="form-group text-right">
                <input type="submit" class="btn btn-outline btn-success" name="save" value="Save">
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
          <a class="navbar-brand" href="index.php">Midas</a>
        </div>
        <!-- /.navbar-header -->

        <ul class="nav navbar-top-links navbar-right">
          <button type="button" class="btn btn-outline btn-primary btn-sm" data-toggle="modal" data-target=".bs-example-modal-lg">Tour</button>
          <li class="dropdown">
            <a id='refreshButton' href="#">
              <i class="fa fa-refresh fa-fw"></i>
            </a>
          </li>

          <!--<li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="../php/oauth2callback.php">
        <i class="fa fa-refresh fa-fw"></i> <i class="fa fa-caret-down"></i>
        </a>
        <ul class="dropdown-menu dropdown-tasks">
        <li>
        <a href="../php/oauth2callback.php">
        <div>
        <p>
        <strong>Refresh Progress</strong>
        <span class="pull-right text-muted">80% Complete</span>
        </p>
        <div class="progress progress-striped active">
        <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
        <span class="sr-only">80% Complete (danger)</span>
        </div>
        </div>
        </div>
        </a>
        </li>
        </ul>-->
          <!-- /.dropdown-tasks -->
          <!-- </li> -->
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
            <h1 class="page-header">Dashboard</h1>
          </div>
          <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">

          <div class="col-lg-3 col-md-6">
            <div class="panel panel-red">
              <div class="panel-heading">
                <div class="row">
                  <div class="col-xs-3">
                    <i class="fa fa-dollar fa-5x"></i>
                  </div>
                  <div class="col-xs-9 text-right">
                    <div class="huge">
                      <?php echo $spent; ?>
                    </div>
                    <div>Spent This Month</div>
                  </div>
                </div>
              </div>
              <a href="#">
                <div class="panel-footer">
                  <span class="pull-left">View Details</span>
                  <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                  <div class="clearfix"></div>
                </div>
              </a>
            </div>
          </div>

          <div class="col-lg-3 col-md-6">
            <div class="panel panel-green">
              <div class="panel-heading">
                <div class="row">
                  <div class="col-xs-3">
                    <i class="fa fa-money fa-5x"></i>
                  </div>
                  <div class="col-xs-9 text-right">
                    <div class="huge">
                      <?php echo $budget; ?>
                    </div>
                    <div>Monthly Budget</div>
                  </div>
                </div>
              </div>
              <a id='edit_budget_calendar'>
                <div class="panel-footer">

                  <span class="pull-left">Edit Budget</span>
                  <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                  <div class="clearfix"></div>
                </div>
              </a>
            </div>
          </div>
          <div class="col-lg-3 col-md-6">
            <div class="panel panel-yellow">
              <div class="panel-heading">
                <div class="row">
                  <div class="col-xs-3">
                    <i class="fa fa-shopping-cart fa-5x"></i>
                  </div>
                  <div class="col-xs-9 text-right">
                    <div class="huge">Look</div>
                    <div>New Receipts</div>
                  </div>
                </div>
              </div>
              <a href="receipts.php">
                <div class="panel-footer">
                  <span class="pull-left">View Receipts</span>
                  <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                  <div class="clearfix"></div>
                </div>
              </a>
            </div>
          </div>

          <div class="col-lg-3 col-md-6">
            <div class="panel panel-primary">
              <div class="panel-heading">
                <div class="row">
                  <div class="col-xs-3">
                    <i class="fa fa-comments fa-5x"></i>
                  </div>
                  <div class="col-xs-9 text-right">
                    <div class="huge">
                      <?php echo $numberOfReceipts; ?>
                    </div>
                    <div>Receipts Left To Sync</div>
                  </div>
                </div>
              </div>
              <a href="#">
                <div class="panel-footer">
                  <span class="pull-left">View Details</span>
                  <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                  <div class="clearfix"></div>
                </div>
              </a>
            </div>
          </div>

        </div>
        <!-- /.row -->
        <div class="row">
          <div class="col-lg-12">

            <div class="panel panel-default">
              <div class="panel-heading">
                <i class="fa fa-bar-chart-o fa-fw"></i> Budget Progress
              </div>
              <!-- /.panel-heading -->
              <div class="panel-body">

                <div class="progress" style="margin:auto;">
                  <?php
        
        $spent_percent = $spent/$budget * 100;
        $spent_percent = 'width:' . $spent_percent . '%';
        $budget_left = $budget - $spent;
        $budget_left_percent = 100 - ($spent/$budget * 100);
        $budget_left_percent = 'width:' . $budget_left_percent . '%';
        
        
        ?>
                    <div class="progress-bar progress-bar-danger progress-bar-striped active" role="progressbar" style=<?php echo $spent_percent;?> >
                      <?php echo '$' .$spent . ' Spent'; ?>
                    </div>
                    <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" style=<?php echo $budget_left_percent; ?>>
                      <?php  echo '$' . $budget_left . ' Available'; ?>
                    </div>

                </div>

              </div>
              <!-- /.panel-body -->
            </div>




            <div class="panel panel-default">
              <div class="panel-heading">
                <i class="fa fa-bar-chart-o fa-fw"></i> Expenditure Graph
                <div class="pull-right">
                  <div class="btn-group">
                    <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                      Graph Views
                      <span class="caret"></span>
                    </button>

                  </div>
                </div>
              </div>
              <!-- /.panel-heading -->
              <div class="panel-body">
                <ul class="nav nav-tabs">
                  <li role="presentation"><a href="#pastDays" aria-controls="pastDays" data-toggle="tab" role="tab" id="barDays">Past 7 Days</a>
                  </li>
                  <li role="presentation"><a href="#pastWeeks" aria-controls="pastWeeks" data-toggle="tab" role="tab" id="barWeeks">Past 7 Weeks</a>
                  </li>
                  <li role="presentation" class="active"><a href="#pastMonths" class="pastMonths" data-toggle="tab" role="tab" id="barMonths">Past 7 Months</a>
                  </li>
                </ul>
                <div class="tab-content">
                  <div role="tabpanel" class="tab-pane fade" id="pastDays">
                    <div id="bar-days"></div>
                  </div>

                  <div role="tabpanel" class="tab-pane fade" id="pastWeeks">
                    <div id="bar-weeks"></div>
                  </div>

                  <div role="tabpanel" class="tab-pane fade in active" id="pastMonths">
                    <div id="bar-months"></div>
                  </div>
                </div>
              </div>
              <!-- /.panel-body -->
            </div>

            <div class="panel panel-default">
              <div class="panel-heading">
                <i class="fa fa-clock-o fa-fw"></i> Recent Receipts
              </div>
              <!-- /.panel-heading -->
              <div class="panel-body">
                <ul class="timeline">
                  <li>
                    <div class="timeline-badge warning"><i class="fa fa-credit-card"></i>
                    </div>
                    <div class="timeline-panel">
                      <div class="timeline-heading">
                        <h4 class="timeline-title"><?php print_r($recentReceipts[0]['name']); ?></h4>
                        <p><small class="text-muted"><i class="fa fa-clock-o"></i> <?php $date = date('m/d/Y', $recentReceipts[0]['dates']); echo $date;?></small>
                        </p>
                      </div>
                      <div class="timeline-body">
                        <?php print_r("Total: $" . $recentReceipts[0]['total']); ?>
                      </div>
                    </div>
                  </li>
                  <li class="timeline-inverted">
                    <div class="timeline-panel">
                      <div class="timeline-heading">
                        <h4 class="timeline-title"><?php print_r($recentReceipts[1]['name']); ?></h4>
                        <p><small class="text-muted"><i class="fa fa-clock-o"></i> <?php $date = date('m/d/Y', $recentReceipts[1]['dates']); echo $date;?></small>
                        </p>
                      </div>
                      <div class="timeline-body">
                        <?php print_r("Total: $" . $recentReceipts[1]['total']); ?>
                      </div>
                    </div>
                  </li>
                  <li>
                    <div class="timeline-badge danger"><i class="fa fa-credit-card"></i>
                    </div>
                    <div class="timeline-panel">
                      <div class="timeline-heading">
                        <h4 class="timeline-title"><?php print_r($recentReceipts[2]['name']); ?></h4>
                        <p><small class="text-muted"><i class="fa fa-clock-o"></i> <?php $date = date('m/d/Y', $recentReceipts[2]['dates']); echo $date;?></small>
                        </p>
                      </div>
                      <div class="timeline-body">
                        <?php print_r("Total: $" . $recentReceipts[2]['total']); ?>
                      </div>
                    </div>
                  </li>
                  <li class="timeline-inverted">
                    <div class="timeline-panel">
                      <div class="timeline-heading">
                        <h4 class="timeline-title"><?php print_r($recentReceipts[3]['name']); ?></h4>
                        <p><small class="text-muted"><i class="fa fa-clock-o"></i> <?php $date = date('m/d/Y', $recentReceipts[3]['dates']); echo $date;?></small>
                        </p>
                      </div>
                      <div class="timeline-body">
                        <?php print_r("Total: $" . $recentReceipts[3]['total']); ?>
                      </div>
                    </div>
                  </li>
                  <li>
                    <div class="timeline-badge info"><i class="fa fa-credit-card"></i>
                    </div>
                    <div class="timeline-panel">
                      <div class="timeline-heading">
                        <h4 class="timeline-title"><?php print_r($recentReceipts[4]['name']); ?></h4>
                        <p><small class="text-muted"><i class="fa fa-clock-o"></i> <?php $date = date('m/d/Y', $recentReceipts[4]['dates']); echo $date;?></small>
                        </p>
                      </div>
                      <div class="timeline-body">
                        <?php print_r("Total: $" . $recentReceipts[4]['total']); ?>

                      </div>
                    </div>
                  </li>
                  <li class="timeline-inverted">
                    <div class="timeline-panel">
                      <div class="timeline-heading">
                        <h4 class="timeline-title"><?php print_r($recentReceipts[5]['name']); ?></h4>
                        <p><small class="text-muted"><i class="fa fa-clock-o"></i> <?php $date = date('m/d/Y', $recentReceipts[5]['dates']); echo $date;?></small>
                        </p>
                      </div>
                      <div class="timeline-body">
                        <?php print_r("Total: $" . $recentReceipts[5]['total']); ?>
                      </div>
                    </div>
                  </li>
                  <li>
                    <div class="timeline-badge success"><i class="fa fa-credit-card"></i>
                    </div>
                    <div class="timeline-panel">
                      <div class="timeline-heading">
                        <h4 class="timeline-title"><?php print_r($recentReceipts[6]['name']); ?></h4>
                        <p><small class="text-muted"><i class="fa fa-clock-o"></i> <?php $date = date('m/d/Y', $recentReceipts[6]['dates']); echo $date;?></small>
                        </p>
                      </div>
                      <div class="timeline-body">
                        <?php print_r("Total: $" . $recentReceipts[6]['total']); ?>
                      </div>
                    </div>
                  </li>
                </ul>
              </div>
              <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
          </div>
          <!-- /.col-lg-8 -->
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

    <!-- Morris Charts JavaScript -->
    <script src="../bower_components/raphael/raphael-min.js"></script>
    <script src="../bower_components/morrisjs/morris.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

    <!-- Datetime Picker -->
    <script type="text/javascript" src="../bower_components/moment/min/moment.min.js"></script>
    <script type="text/javascript" src="../bower_components/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>

    <script>
      var barDays = Morris.Bar({ // graph for past 7 days
        <?php $graphData = dashBarGraph(0);?>
        element: 'bar-days',
          data: [{
            <?php echo $graphData[0]; ?>
          }, {
            <?php echo $graphData[1]; ?>
          }, {
            <?php echo $graphData[2]; ?>
          }, {
            <?php echo $graphData[3]; ?>
          }, {
            <?php echo $graphData[4]; ?>
          }, {
            <?php echo $graphData[5]; ?>
          }, {
            <?php echo $graphData[6]; ?>
          }],
          xkey: 'y',
          stacked: true,
          resize: true,
          ykeys: [<?php print_r($graphData[7]); ?>],
          labels: [<?php print_r($graphData[7]); ?>],
          hoverCallback: function(index, options, content, row) {
            while (content.indexOf(' -') != -1) {
              var loc = content.indexOf(' -') + 2;
              var loc2 = content.lastIndexOf('>', loc) + 1;
              var sub = content.substring(loc2, loc);
              content = content.replace(sub, '');

            }
            return content;
          }
      });

      var barWeeks = Morris.Bar({ // graph for past 7 weeks
        <?php $graphData = dashBarGraph(1);?>
        element: 'bar-weeks',
          data: [{
            <?php echo $graphData[0]; ?>
          }, {
            <?php echo $graphData[1]; ?>
          }, {
            <?php echo $graphData[2]; ?>
          }, {
            <?php echo $graphData[3]; ?>
          }, {
            <?php echo $graphData[4]; ?>
          }, {
            <?php echo $graphData[5]; ?>
          }, {
            <?php echo $graphData[6]; ?>
          }],
          xkey: 'y',
          stacked: true,
          resize: true,
          ykeys: [<?php print_r($graphData[7]); ?>],
          labels: [<?php print_r($graphData[7]); ?>],
          hoverCallback: function(index, options, content, row) {
            while (content.indexOf(' -') != -1) { // cleans up the hover legend by removing the purchases do not exist in a certain time period
              var loc = content.indexOf(' -') + 2;
              var loc2 = content.lastIndexOf('>', loc) + 1;
              var sub = content.substring(loc2, loc);
              content = content.replace(sub, '');

            }
            return content;
          }
      });

      var barMonths = Morris.Bar({ // graph for past 7 months
        <?php $graphData = dashBarGraph(2);?>
        element: 'bar-months',
          data: [{
            <?php echo $graphData[0]; ?>
          }, {
            <?php echo $graphData[1]; ?>
          }, {
            <?php echo $graphData[2]; ?>
          }, {
            <?php echo $graphData[3]; ?>
          }, {
            <?php echo $graphData[4]; ?>
          }, {
            <?php echo $graphData[5]; ?>
          }, {
            <?php echo $graphData[6]; ?>
          }],
          xkey: 'y',
          resize: true,
          stacked: true,
          ykeys: [<?php print_r($graphData[7]); ?>],
          labels: [<?php print_r($graphData[7]); ?>],
          hoverCallback: function(index, options, content, row) {
            while (content.indexOf(' -') != -1) {
              var loc = content.indexOf(' -') + 2;
              var loc2 = content.lastIndexOf('>', loc) + 1;
              var sub = content.substring(loc2, loc);
              content = content.replace(sub, '');

            }
            return content;
          }
      });



      $('#barDays').on('shown.bs.tab', function(evt) { // these .on functions are attached to the tabs in the dashboard graphs
        // the redraw function draws the graphs in the tabpanel for the tab that is clicked
        barDays.redraw();

      });

      $('#barWeeks').on('shown.bs.tab', function(evt) {
        barWeeks.redraw();

      });
      $('#barMonths').on('shown.bs.tab', function(evt) {
        barMonths.redraw();

      });

      $('#refreshButton').click(function(evt) { // link for dropdown item does not work so this function takes care of redirecting to new page
        window.location.href = '../php/oauth2callback.php';
      });

      $('a#edit_budget_calendar').click(function(evt) { // when the edit button is clicked for budget
        var budget = "<?php echo $budget;?>";
        var budget_date = "<?php echo date('m/d/Y', $budget_date);?>";
        $('#edit_budget').val(budget);
        $('#edit_budget_date').val(budget_date);
        // $('#name_receipt').val($(this).closest('tr').find('td:nth-child(1)').text());
        // $('#total_receipt').val($(this).closest('tr').find('td:nth-child(2)').text());
        // $('#date_receipt').val($(this).closest('tr').find('td:nth-child(3)').text());
        $('#editBudgetModal').modal('show');

      });

      $(function() {
        $('#datetimepicker1').datetimepicker({

        });
      });

      //   <?php
      // if ( 5 == 5){
      //     ?>

      //   alert('hello');

      //   }
      // function signOut() { // to sign out from google account
      //   var auth2 = gapi.auth2.getAuthInstance();
      //   auth2.signOut().then(function() {
      //     console.log("signed out");
      //   });
      // }

      // function onLoad() { // loads gapi library
      //   gapi.load('auth2', function() {
      //     gapi.auth2.init();
      //   });
      // }
    </script>
    <?php if ($_SESSION['first_login'] == 1) {
        $_SESSION['first_login'] = 0;
        echo '<script>$("#tour").modal("show");</script>';
    }
    ?>
      <!-- Opens the modal that shows the tour when a user first logs in -->
      <style>
        svg {
          /* check this if any images are not displaying properly  */
          width: 100% !important
        }
        
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