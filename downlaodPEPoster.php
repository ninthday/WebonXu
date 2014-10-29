<?php

require './inc/setup.inc.php';
require './cls/myPDOConn.Class.php';
require './cls/PosterStatic.Class.php';
/** PHPExcel */
require './resource/PHPExcel.php';
$strLang = filter_input(INPUT_GET, 'lang', FILTER_SANITIZE_STRING);

try {
    $pdoConn = \Floodfire\myPDOConn::getInstance('myPDOConnConfig.inc.php');
    $objPoster = new \Floodfire\TwitterProcess\PosterStatic($pdoConn);

    $aryResult = $objPoster->showTweetsByLang($strLang);

    // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();

    // Set properties
    $objPHPExcel->getProperties()->setCreator("Nccu CS")
            ->setLastModifiedBy("Nccu CS")
            ->setTitle("水火計畫 Twitter 資料")
            ->setSubject("水火計畫 Twitter 分析資料")
            ->setDescription("<" . date('Y-m-d H:i:s') . "> 下載水火計畫分析資料")
            ->setKeywords("Flood And Fire Project")
            ->setCategory("Flood Fire");

    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'tweet_id')
            ->setCellValue('B1', 'from_user')
            ->setCellValue('C1', 'from_user_name')
            ->setCellValue('D1', 'text')
            ->setCellValue('E1', 'language')
            ->setCellValue('F1', 'tw_time');

    $strColumn = 'A';
    $intRow = 2;
    
    foreach ($aryResult as $row) {
        $strColumn = 'A';
        $objPHPExcel->getActiveSheet()->setCellValue($strColumn . $intRow, $row['data_id']);
        $strColumn++;
        $objPHPExcel->getActiveSheet()->setCellValue($strColumn . $intRow, $row['data_from_user']);
        $strColumn++;
        $objPHPExcel->getActiveSheet()->setCellValue($strColumn . $intRow, $row['data_from_user_name']);
        $strColumn++;
        $objPHPExcel->getActiveSheet()->setCellValue($strColumn . $intRow, $row['data_text']);
        $strColumn++;
        $objPHPExcel->getActiveSheet()->setCellValue($strColumn . $intRow, $row['lang_detection']);
        $strColumn++;
        $objPHPExcel->getActiveSheet()->setCellValue($strColumn . $intRow, $row['TWTime']);
        $intRow++;
    }

    // Rename sheet
    $objPHPExcel->getActiveSheet()->setTitle($strLang);

    //Excel 2007+
    ob_end_clean();
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . date('Y-m-d') . '_' . $strLang . '_Post.xlsx"');
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    exit;
} catch (Exception $exc) {
    echo $exc->getMessage();
}
?>
