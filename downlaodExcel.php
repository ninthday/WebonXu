<?php

require './inc/setup.inc.php';
require './cls/myPDOConn.Class.php';
require './cls/ShowExistTweet.Class.php';
/** PHPExcel */
require './resource/PHPExcel.php';

$aryQuery = array();
foreach ($_GET as $key => $value) {
    if (!empty($value)) {
        $aryQuery[$key] = $value;
    }
}

try {
    $pdoConn = myPDOConn::getInstance();
    $objShowTweet = new ShowExistTweet($pdoConn);
    $result = $objShowTweet->getAllTweets($aryQuery);

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
            ->setCellValue('A1', 'TweetId')
            ->setCellValue('B1', 'FromUser')
            ->setCellValue('C1', 'UserName')
            ->setCellValue('D1', 'Content')
            ->setCellValue('E1', 'Language')
            ->setCellValue('F1', 'Type')
            ->setCellValue('G1', 'Time');

    $strColumn = 'A';
    $intRow = 2;

    foreach ($result as $row) {
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
        $objPHPExcel->getActiveSheet()->setCellValue($strColumn . $intRow, $row['Mark']);
        $strColumn++;
        $objPHPExcel->getActiveSheet()->setCellValue($strColumn . $intRow, $row['TWTime']);
        $intRow++;
    }

    // Rename sheet
    $objPHPExcel->getActiveSheet()->setTitle('TweetData');

    //Excel 2007+
    ob_end_clean();
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . date('Y-m-d') . '_DownloadTweets.xlsx"');
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    exit;
} catch (Exception $exc) {
    echo $exc->getMessage();
}
?>
