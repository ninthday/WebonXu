<?php
session_start();
include 'inc/setup.inc.php';        //引入網頁設定檔

ini_set('display_error', 'On');
//error_reporting(E_ALL);
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <title><?php echo _WEB_NAME ?> - get Tweet's URL Content</title>
        <link href="common/styles-bases.css" type="text/css" rel="stylesheet" />
    </head>
    <body>
        <?php
        include _APP_PATH . 'cls/ParseContent.Class.php';
        
        try {
            $objTrans = new ParseContent();
            $objTrans->getURLContent();
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }

?>

    </body>
</html>
