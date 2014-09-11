<?php

require './inc/setup.inc.php';
require './cls/myPDOConn.Class.php';
require './cls/PageControl.Class.php';
require './cls/ShowExistTweet.Class.php';

$aryQuery = array();
foreach ($_GET as $key => $value) {
    if (!empty($value)) {
        $aryQuery[$key] = $value;
    }
}

$intPagesize = 30;

try {
    $pdoConn = myPDOConn::getInstance();
    $objShowTweet = new ShowExistTweet($pdoConn);
    $intNowPage = (int) $aryQuery['pg'];
    unset($aryQuery['pg']);
    $objPage = new PageControl($intNowPage, $intPagesize, $objShowTweet->getTotalNum($aryQuery));

    $result = $objShowTweet->getTweets($aryQuery, $objPage);
    
//    foreach ($result as $row) {
//        foreach ($row as $key => $value) {
//            echo $key, '==>', $value, '<br>';
//        }
//
//        echo '---------------------------------------------<br>';
//    }
    $aryResultWithPage = array(
        'page_list' => $objPage->getPagelist(),
        'query_result' => $result
    );
    echo json_encode($aryResultWithPage);
} catch (Exception $exc) {
    echo $exc->getMessage();
}
?>
