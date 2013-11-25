<?php
session_start();
include 'inc/setup.inc.php';        //引入網頁設定檔
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <title><?php echo _WEB_NAME ?> - Transfer Local Amazon Data to MySQL</title>
        <link href="common/styles-bases.css" type="text/css" rel="stylesheet" />
    </head>
    <body>
        <?php
        include _APP_PATH . 'cls/FromAmazon.Class.php';
        
        try {
            $objTrans = new FromAmazon();
            $objTrans->goTransMongo2SQL();
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }

        
        
        ?>
    </body>
</html>
