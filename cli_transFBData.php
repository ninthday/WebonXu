<?php

/*
 * Count Term freq
 * 單位為出現的次數（同一則出現兩次算兩次）
 */

include 'inc/setup.inc.php';        //引入網頁設定檔
//include _APP_PATH . 'cls/FBMango2MySQL.Class.php';
require _APP_PATH . 'cls/MongoDB2MySQL.Class.php';
require _APP_PATH . 'cls/myPDOConn.Class.php';

//$objTransFB = new FBMango2MySQL('facebook','newsandmarket');
//$objTransFB->goTransMongo2SQL();
try {
    $pdoConn = myPDOConn::getInstance();
    $objTransFB = new MongoDB2MySQL($pdoConn);
    $objTransFB->transFacebook('colisa_fb', 'feed');
    $objTransFB->transFacebook('colisa_fb2', 'feed');
} catch (Exception $exc) {
    echo $exc->getMessage();
}
?>
