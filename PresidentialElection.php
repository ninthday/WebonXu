<?php
require './inc/setup.inc.php';
require './cls/myPDOConn.Class.php';
require './cls/MentionStatic.Class.php';

$intTop = (int)filter_input(INPUT_GET, 'top',FILTER_SANITIZE_NUMBER_INT);
try {
    $pdoConn = \Floodfire\myPDOConn::getInstance('myPDOConnConfig.inc.php');
    $objMt = new \Floodfire\TwitterProcess\MentionStatic($pdoConn);
    $aryRS = $objMt->getMentionTop('PE_Relation', $intTop);
} catch (Exception $exc) {
    echo $exc->getMessage();
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="description" content="This Website is for Fire and Flood Project in NCCU">
        <meta name="author" content="Ninthday (jeffy@ninthday.info)">
        <title><?php echo _WEB_NAME ?></title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
        <!-- Custom styles for this template -->
        <link href="http://getbootstrap.com/examples/dashboard/dashboard.css" rel="stylesheet">
        <link href="css/myStyle.css" rel="stylesheet">
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
    </head>
    <body>
        <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Flood-Fire Project</a>
                </div>
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="#">Dashboard</a></li>
                        <li><a href="#">Settings</a></li>
                        <li><a href="#">Profile</a></li>
                        <li><a href="#">Help</a></li>
                    </ul>
                    <form class="navbar-form navbar-right">
                        <input type="text" class="form-control" placeholder="Search...">
                    </form>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-3 col-md-2 sidebar">
                    <ul class="nav nav-sidebar">
                        <li class="active"><a href="#">總統大選-Mention</a></li>
                        <li><a href="PEPoster.php">總統大選-Post</a></li>
                        <li><a href="#">Analytics</a></li>
                        <li><a href="#">Export</a></li>
                    </ul>
                    <ul class="nav nav-sidebar">
                        <li><a href="">Nav item</a></li>
                        <li><a href="">Nav item again</a></li>
                        <li><a href="">One more nav</a></li>
                        <li><a href="">Another nav item</a></li>
                        <li><a href="">More navigation</a></li>
                    </ul>
                    <ul class="nav nav-sidebar">
                        <li><a href="">Nav item again</a></li>
                        <li><a href="">One more nav</a></li>
                        <li><a href="">Another nav item</a></li>
                    </ul>
                </div>
                <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                    <h1 class="sub-header">Mention Static<button class="btn btn-primary pull-right" id="downloadExcelXml"><i class="fa fa-download"></i>&nbsp;Download Excel [ <span id="dl-name">None</span> ]</button></h1>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Mention</th>
                                            <th>Count</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        foreach ($aryRS as $row) {
                                            echo '<tr><td>No.', $i, '</td>';
                                            echo '<td><a class="tw-mention" href="#">', $row['to_user'], '</a></td>';
                                            echo '<td>', $row['CNT'], '</td></tr>';
                                            $i++;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div id="searchResult-grid" class="col-md-10 col-md-offset-1">
                                <h4>Mention user:&nbsp;<b><span id="sch-name">None</span></b></h4>
                                <ul class="list-unstyled" id="queryResult">
                                    <li class="well">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <img class="img-rounded user-pic" src="http://api.twitter.com/1/users/profile_image/twlatestnews">
                                                <strong>台灣新聞</strong>&nbsp;<code>@twlatestnews</code><br>
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
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script src="./js/PresidentialElection.js"></script>
        <!-- Latest compiled and minified JavaScript -->
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
        <script src="http://getbootstrap.com/assets/js/docs.min.js"></script>
    </body>
</html>
