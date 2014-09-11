<?php

require './inc/setup.inc.php';
require './cls/myPDOConn.Class.php';
require './cls/MentionStatic.Class.php';

$strUserName = filter_input(INPUT_GET, 'uname',FILTER_SANITIZE_STRING);

try {
    $pdoConn = \Floodfire\myPDOConn::getInstance('myPDOConnConfig.inc.php');
    $objMt = new \Floodfire\TwitterProcess\MentionStatic($pdoConn);
    
    $aryResult = $objMt->showTweetByName($strUserName);
   
    echo json_encode($aryResult);
} catch (Exception $exc) {
    echo $exc->getMessage();
}
?>
