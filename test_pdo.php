<?php 
require './inc/setup.inc.php';
require './cls/myPDOConn.Class.php';
require './cls/TestPDO.Class.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        try {
            $pdoConn = myPDOConn::getInstance();
            $objTestPDO = new TestPDO($pdoConn);
            $objTestPDO->getTweet();
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
                ?>
    </body>
</html>
