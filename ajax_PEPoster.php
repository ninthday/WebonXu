<?php

require './inc/setup.inc.php';
require './cls/myPDOConn.Class.php';
require './cls/PosterStatic.Class.php';

$strType = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_STRING);

try {
    $pdoConn = \Floodfire\myPDOConn::getInstance('myPDOConnConfig.inc.php');
    $objPoster = new \Floodfire\TwitterProcess\PosterStatic($pdoConn);
    switch ($strType) {
        case 'ptlist':
            $strLang = filter_input(INPUT_GET, 'lang', FILTER_SANITIZE_STRING);
            $strSort = filter_input(INPUT_GET, 'ob', FILTER_SANITIZE_STRING);
            $aryLang = explode('+', $strLang);
            $aryRS = $objPoster->getPosterByLang($aryLang, $strSort);
            $aryList = array();
            foreach ($aryRS as $row) {
                array_push($aryList, array(
                    'from_user' => $row['data_from_user'],
                    'language' => $row['lang_detection'],
                    'cnt' => $row['CNT']
                ));
            }
            $aryResult['rsStat'] = true;
            $aryResult['rsContents'] = $aryList;
            break;

        case 'pcont':
            $strUserName = filter_input(INPUT_GET, 'uname',FILTER_SANITIZE_STRING);
            $strLang =  filter_input(INPUT_GET, 'lang',FILTER_SANITIZE_STRING);
            $aryTweets = $objPoster->showTweetsByPoster($strUserName, $strLang);
            $aryResult['rsStat'] = true;
            $aryResult['rsContents'] = $aryTweets;
            break;
        default:
            break;
    }
} catch (Exception $exc) {
    $aryResult['rsStat'] = false;
    $aryResult['rsContents'] = $exc->getMessage();
}

echo json_encode($aryResult);
?>
