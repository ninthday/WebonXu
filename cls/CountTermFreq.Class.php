<?php

/**
 * Description of CountTermFreq
 *
 * @author Jeffy_shih
 */
class CountTermFreq {

    private $mysqli;

    function __construct() {
        include _APP_PATH . 'inc/ConFF.inc.php';
        $this->mysqli = @new mysqli($mysqlHost, $mysqlUser, $mysqlPassword, $mysqlDB);
        if (mysqli_connect_errno()) {
            throw new Exception('無法建立資料庫連線！' . mysqli_connect_error(), 101);
            $this->mysqli = false;
            exit();
        }

        $this->mysqli->query("SET NAMES 'utf8'");
    }

    /**
     * 計算 term 出現的次數
     * 
     * @param string $strTerm Term名稱
     * @param string $strDBName 資料庫名稱
     * @return int 次數
     */
    public function getCountNum($strTerm, $strDBName) {
        $intCount = 0;
        $sql_get = 'SELECT `data_text` FROM `' . $strDBName . '` WHERE `data_text` LIKE \'%' . $strTerm . '%\'';
        $rs_get = $this->mysqli->query($sql_get);

        while ($row_get = $rs_get->fetch_row()) {
            $intCount += mb_substr_count($row_get[0], $strTerm);
            echo $intCount . "\n";
        }
        return $intCount;
    }
    
    /**
     * Count Term frequency per day
     * 
     * @param string $strTerm The term want to Count 
     * @param string $strDBName Which Database to Count
     * @param string $strLang Which Language to Count
     */
    public function getCountNumPerDay($strTerm, $strDBName, $strLang) {
        $intCount = 0;
        $intDayCount = 0;
        $intTotalCount = 0;
        $strDate = '';
        
        if($strLang == 'all'){
            $sql_get = "SELECT `data_text`, DATE_FORMAT(`TWTime`, '%Y-%m-%d') FROM `" . $strDBName . "` WHERE `data_text` LIKE ? ORDER BY `TWTime` ASC";
        }else{
            $sql_get = "SELECT `data_text`, DATE_FORMAT(`TWTime`, '%Y-%m-%d') FROM `" . $strDBName . "` WHERE `data_text` LIKE ? AND `lang_detection` = '" . $strLang . "' ORDER BY `TWTime` ASC";
        }
        
        if ($strStmt = $this->mysqli->prepare($sql_get)) {
            
            /* bind parameters for markers */
            $strStmt->bind_param('s', $strCondition);
            $strCondition = '%' . $strTerm . '%';
            
            /* execute query */
            $strStmt->execute();
            
            /* bind result variables */
            $strStmt->bind_result($strCol1, $strCol2);
            
            while ($strStmt->fetch()) {
                $intCount = mb_substr_count($strCol1, $strTerm);
                // check the first result row
                if ($intTotalCount) {
                    // Not first result row
                    if ($strDate == $strCol2) {
                        // At the same day
                        $intDayCount += $intCount;
                    } else {
                        //Change date, print out day count and reset value
                        echo $strDate, ' -> ', $intDayCount , "\n";
                        $intDayCount = $intCount;
                        $strDate = $strCol2;
                    }
                } else {
                    // First Result
                    $strDate = $strCol2;
                    $intDayCount += $intCount;
                }
                $intTotalCount += $intCount;
            }
            echo 'Total Count->: ', $intTotalCount , "\n";
            $strStmt->close();
        }else{
            echo $this->mysqli->error;
        }
    }

    function __destruct() {
        if ($this->mysqli) {
            $this->mysqli->close();
        }
    }

}

?>
