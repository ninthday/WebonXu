<?php
require './inc/setup.inc.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="description" content="This Website is for Fire and Flood Project in NCCU">
        <meta name="author" content="Ninthday (jeffy@ninthday.info)">
        <title><?php echo _WEB_NAME ?></title>
        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/myStyle.css" rel="stylesheet">
        <link href="css/datepicker.css" rel="stylesheet">
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
                    <div class="well">
                        <legend>Search Values and Type</legend>
                        <div class="controls">
                            <div class="input-prepend">
                                <span class="add-on">@</span>
                                <input class="input-small" id="twi_id" type="text" placeholder="Twitter ID">
                            </div>
                            &nbsp;<span class="divider">/</span>&nbsp;
                            <input type="text" id="uname" class="input-medium" placeholder="Username">&nbsp;&nbsp;<span class="divider">/</span>&nbsp;
                            <input type="text" class="input-small" id="begin_date" placeholder="Begin Date">&nbsp;~&nbsp;<input type="text" class="input-small" id="end_date" placeholder="End Date">
                        </div>
                        <div class="control-group">
                            <label class="checkbox inline">Language:</label> 
                            <label class="checkbox inline">
                                <input type="checkbox" name="lang" value="zh-TW"> 繁體中文
                            </label>
                            <label class="checkbox inline">
                                <input type="checkbox" name="lang" value="zh"> 簡體中文
                            </label>
                            <label class="checkbox inline">
                                <input type="checkbox" name="lang" value="en"> 英文
                            </label>
                            <label class="checkbox inline">
                                <input type="checkbox" name="lang" value="ja"> 日文
                            </label>
                            <label class="checkbox inline">
                                <input type="checkbox" name="lang" value="other"> 其他
                            </label>
                        </div>
                        <fieldset>
                            <div class="control-group">
                                <label class="checkbox inline">Tweet Type:</label>
                                <label class="checkbox inline">
                                    <input type="checkbox" name="type" value="or"> Original
                                </label>
                                <label class="checkbox inline">
                                    <input type="checkbox" name="type" value="rt"> Retweet
                                </label>
                                <label class="checkbox inline">
                                    <input type="checkbox" name="type" value="mt"> Mention
                                </label>
                            </div>
                        </fieldset>
                        <button id="btn_sch" class="btn btn-primary"><i class="icon-search icon-white"></i>&nbsp;Search</button>
                    </div>
                    <div class="btn-toolbar">
                        <div class="btn-group">
                            <a class="btn" href="#"><i class="icon-align-justify"></i>&nbsp;Table</a>
                            <a class="btn" href="#"><i class="icon-th"></i>&nbsp;Grid</a>
                            <a class="btn" href="#" id="downloadExcelXml"><i class="icon-download-alt"></i>&nbsp;Download Excel</a>
                        </div>
                    </div>
                    <div id="searchResult-table">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Tweet Content</th>
                                    <th>From User</th>
                                    <th>Lang.</th>
                                    <th>Type</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1.</td>
                                    <td>小英：台灣有民主  陸客就會來: 民主進步黨總統候選人蔡英文今天指出，只要台灣民主持續、幸福安定，大陸觀光客一定會持續再來看台灣的民主，如果台灣與中國愈來愈像，沒有優質民主...</td>
                                    <td>台灣新聞</td>
                                    <td>zh-TW</td>
                                    <td>Original</td>
                                    <td>2012-01-11 23:45:53</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div id="searchResult-grid">
                        <ul class="unstyled" id="queryResult">
                            <li class="well">
                                <div class="row">
                                    <div class="span12"><img class="img-rounded user-pic" src="http://api.twitter.com/1/users/profile_image/twlatestnews">
                                        <strong>台灣新聞</strong>&nbsp;<small class="muted">@twlatestnews</small><br>
                                        <small>2012-01-11 23:45:53</small>&nbsp;
                                        <span class="pull-right"><span class="label label-success">zh-TW</span>&nbsp;<span class="label label-info">Original</span></span>
                                    </div>
                                </div>
                                <div class="row result_content">
                                    小英：台灣有民主  陸客就會來: 民主進步黨總統候選人蔡英文今天指出，只要台灣民主持續、幸福安定，大陸觀光客一定會持續再來看台灣的民主，如果台灣與中國愈來愈像，沒有優質民主...
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="pagination pagination-centered">
                        <ul id="page-list">
                            <li class="disabled"><a href="#">&laquo;</a></li>
                            <li class="disabled"><a href="#">1</a></li>
                            <li><a href="#">2</a></li>
                            <li><a href="#">3</a></li>
                            <li><a href="#">4</a></li>
                            <li><a href="#">5</a></li>
                            <li><a href="#">6</a></li>
                            <li><a href="#">7</a></li>
                            <li><a href="#">8</a></li>
                            <li><a href="#">9</a></li>
                            <li><a href="#">10</a></li>
                            <li><a href="#">&raquo;</a></li>
                        </ul>
                        <input class="input-mini" type="text" id="now-page" value="">
                    </div>
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
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap-datepicker.js"></script>
        <script>
            $(function() {
                $("#begin_date").datepicker({
                    format: 'yyyy-mm-dd'
                });
                $("#end_date").datepicker({
                    format: 'yyyy-mm-dd'
                });
            });
        </script>
        <script src="js/bootstrap-modal.js"></script>
        <script src="js/bootstrap-tab.js"></script>
        <script src="js/data_download.js"></script>
    </body>
</html>
