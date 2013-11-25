<?php
ini_set('display_error', 'On');
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="description" content="This Website is for Fire and Flood Project in NCCU">
        <meta name="author" content="Ninthday (jeffy@ninthday.info)" >
        <title>Fire and Flood Project | NCCU</title>
        <link href="css/bootstrap.css" rel="stylesheet">
        <style type="text/css">
            body {
                padding-top: 60px;
                padding-bottom: 40px;
            }
            .sidebar-nav {
                padding: 9px 0;
            }

            @media (max-width: 980px) {
                /* Enable use of floated navbar text */
                .navbar-text.pull-right {
                    float: none;
                    padding-left: 5px;
                    padding-right: 5px;
                }
            }
        </style>
        <link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">
        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="../assets/js/html5shiv.js"></script>
        <![endif]-->
    </head>
    <body>
        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="brand" href="#">Flood and Fire</a>
                    <div class="nav-collapse collapse">
                        <p class="navbar-text pull-right">
                            Logged in as <a href="#" class="navbar-link">Username</a>
                        </p>
                        <ul class="nav">
                            <li class="active"><a href="index.php">Home</a></li>
                            <li><a href="#about">About</a></li>
                            <li><a href="#contact">Contact</a></li>
                        </ul>
                    </div><!--/.nav-collapse -->
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row-fluid">
                <div class="span3">
                    <div class="well sidebar-nav">
                        <?php include './left_nav_menu.php'; ?>
                    </div><!--/.well -->
                </div><!--/span-->
                <div class="span9">
                    <div class="hero-unit">
                        <h1>Flood and Fire</h1>
                        <p>This project is to understanding public communications in times of crisis.</p>
                        <p><a href="#" class="btn btn-primary btn-large">Learn more &raquo;</a></p>
                    </div>
                    <div class="row-fluid">
                        <div class="span4">
                            <h2>Heading</h2>
                            <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
                            <p><a class="btn" href="#">View details &raquo;</a></p>
                        </div><!--/span-->
                        <div class="span4">
                            <h2>Heading</h2>
                            <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
                            <p><a class="btn" href="#">View details &raquo;</a></p>
                        </div><!--/span-->
                        <div class="span4">
                            <h2>Heading</h2>
                            <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
                            <p><a class="btn" href="#">View details &raquo;</a></p>
                        </div><!--/span-->
                    </div><!--/row-->
                </div><!--/span-->
            </div><!--/row-->

            <hr>

            <footer>
                <?php include 'footer.php'; ?>
            </footer>

        </div><!--/.fluid-container-->

        <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="../assets/js/jquery.js"></script>
        <script src="../assets/js/bootstrap-transition.js"></script>
        <script src="../assets/js/bootstrap-alert.js"></script>
        <script src="../assets/js/bootstrap-modal.js"></script>
        <script src="../assets/js/bootstrap-dropdown.js"></script>
        <script src="../assets/js/bootstrap-scrollspy.js"></script>
        <script src="../assets/js/bootstrap-tab.js"></script>
        <script src="../assets/js/bootstrap-tooltip.js"></script>
        <script src="../assets/js/bootstrap-popover.js"></script>
        <script src="../assets/js/bootstrap-button.js"></script>
        <script src="../assets/js/bootstrap-collapse.js"></script>
        <script src="../assets/js/bootstrap-carousel.js"></script>
        <script src="../assets/js/bootstrap-typeahead.js"></script>
    </body>
</html>
