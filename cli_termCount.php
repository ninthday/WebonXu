<?php

/*
 * Count Term freq
 * 單位為出現的次數（同一則出現兩次算兩次）
 */

include 'inc/setup.inc.php';        //引入網頁設定檔
include _APP_PATH . 'cls/CountTermFreq.Class.php';

$strTerm = '马英九';
$strDBName = 'PE_forCount';

$objTermCount = new CountTermFreq();
//echo $objTermCount->getCountNum($strTerm, $strDBName);
$objTermCount->getCountNumPerDay($strTerm, $strDBName, 'all');
?>
