#!/usr/bin/php
<?php
session_start();
include 'inc/setup.inc.php';        //引入網頁設定檔
include _APP_PATH . 'cls/FromAmazon.Class.php';

try {
//    $objTrans = new FromAmazon('fandf_earthquake_taiwan10thJune2012','backup_from_amazon_server');
    $objTrans = new FromAmazon('backup_from_amazon_server_taiwan_election','unique_tweets');
    $objTrans->goTransMongo2SQL();
} catch (Exception $exc) {
    echo $exc->getMessage();
}
?>
