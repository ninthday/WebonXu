<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include 'inc/setup.inc.php';        //引入網頁設定檔
include _APP_PATH . 'cls/TweetMarkup.Class.php';

try {
    $objMarkup = new TweetMarkup();
    $objMarkup->markTweet();
} catch (Exception $exc) {
    echo $exc->getMessage();
}

?>
