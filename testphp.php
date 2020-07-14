<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta content="" name="description" />
    <meta content="webthemez" name="author" />
    <title>DBMS Term Project</title>
    <!-- Bootstrap Styles-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FontAwesome Styles-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- Morris Chart Styles-->
    <link href="assets/js/morris/morris-0.4.3.min.css" rel="stylesheet" />
    <!-- Custom Styles-->
    <link href="assets/css/custom-styles.css" rel="stylesheet" />
    <!-- Google Fonts-->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
</head>

<body>
<?php

$plabels = array('Jan', 'hahahahaha', 'Mar', 'Apr', 'May', 'Jun', 'Jul');

?>

<script>
var a = <?php echo json_encode($plabels) ?>;
</script>

<div id="wrapper">
    <nav class="navbar navbar-default top-navbar" role="navigation">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="test.html"><strong><i class="icon fa fa-plane"></i> Group 5 project </strong></a>
        </div>

        <ul class="nav navbar-top-links navbar-right">
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                    <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li><a href="login.html"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
    <!--/. NAV TOP  -->
    <nav class="navbar-default navbar-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav" id="main-menu">
                <li>
                    <a href="test.html"><i class="fa fa-dashboard"></i> Home </a>
                </li>

                <li>
                    <a href="1st_query.html"><i class="fa fa-desktop"></i> Query 1 </a>
                </li>

                <li>
                    <a href="2nd_query.html"><i class="fa fa-desktop"></i> Query 2 </a>
                </li>

                <li>
                    <a href="3rd_query.html"><i class="fa fa-desktop"></i> Query 3 </a>
                </li>

                <li>
                    <a href="4th_query.html"><i class="fa fa-desktop"></i> Query 4 </a>
                </li>

                <li>
                    <a href="5th_query.html"><i class="fa fa-desktop"></i> Query 5 </a>
                </li>

                <li>
                    <a href="6th_query.html"><i class="fa fa-desktop"></i> Query 6 </a>
                </li>

                <li>
                    <a href="7th_query.html"><i class="fa fa-desktop"></i> Query 7 </a>
                </li>
            </ul>
        </div>
    </nav>
    <!-- /. NAV SIDE  -->
    <div id="page-wrapper">
        <div class="header">
            <h1 class="page-header">
                First Query
            </h1>
        </div>
        <div id="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="jumbotron">
                        <h2>Query Content</h2>
                        <p> Trend of sales value proportion, profit proportion and sales volume proportion of the products type in a certain period of time </p>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="jumbotron">
                        <div class="panel panel-default chartJs">
                            <div class="panel-heading">
                                <div class="card-title">
                                    <div class="title">Line Chart</div>
                                </div>
                            </div>
                            <div class="panel-body" >
                                <canvas id="line-chart" class="chart" style="position: relative; height:40vh; width:80vw"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /. PAGE INNER  -->
    </div>
    <!-- /. PAGE WRAPPER  -->
</div>
    <!-- /. WRAPPER  -->
    <!-- JS Scripts-->
    <!-- jQuery Js -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- Bootstrap Js -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- Metis Menu Js -->
    <script src="assets/js/jquery.metisMenu.js"></script>
    <!-- Chart Js -->
    <script type="text/javascript" src="assets/js/Chart.min.js"></script>
    <script type="text/javascript" src="assets/js/chartjs.js"></script>
    <!-- Morris Chart Js -->
    <script src="assets/js/morris/raphael-2.1.0.min.js"></script>
    <script src="assets/js/morris/morris.js"></script>
    <!-- Custom Js -->
    <script src="assets/js/custom-scripts.js"></script>

</div>
</body>
</html>
