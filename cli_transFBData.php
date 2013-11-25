<?php

/*
 * Count Term freq
 * 單位為出現的次數（同一則出現兩次算兩次）
 */

include 'inc/setup.inc.php';        //引入網頁設定檔
include _APP_PATH . 'cls/FBMango2MySQL.Class.php';

$objTransFB = new FBMango2MySQL('facebook','newsandmarket');
$objTransFB->goTransMongo2SQL();
?>
