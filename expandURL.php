#!/usr/bin/php
<?php
session_start();
include 'inc/setup.inc.php';        //引入網頁設定檔

ini_set('display_error', 'On');
error_reporting(E_ALL);

include _APP_PATH . 'cls/ParseContent.Class.php';

try {
    $objParse = new ParseContent();
    $objParse->saveRegularURL();
//            $aryHeader = get_headers('http://t.co/VtRjbe9z',1);
//            echo $objParse->expendShortURL('http://t.co/ffeUPVfY');
//            var_dump($aryHeader);
} catch (Exception $exc) {
    echo $exc->getMessage();
}
?>
