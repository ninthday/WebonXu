<?php

/**
 * Description of TweetMarkup
 *
 * @author Jeffy_shih
 */
class TweetMarkup {

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

    public function markTweet() {
        $sql_get = 'SELECT `data_id`, `data_text` FROM `PE_forCount`';
        $rs_get = $this->mysqli->query($sql_get);
        $i = 1;
        while ($row_get = $rs_get->fetch_row()) {
            echo $i, '.', $row_get[0], '-->', $this->detectMarkup($row_get[1]), "\n";
            $i++;
            $this->saveMarkup($row_get[0], $this->detectMarkup($row_get[1]));
        }
    }

    private function saveMarkup($intTweetId, $strMarkup) {
        $sql_insert = "INSERT INTO `PE_TweetMark`(`Tweet_Id`, `Mark`) VALUES (?,?)";
        $stmt = $this->mysqli->prepare($sql_insert);
        $stmt->bind_param('is', $intTweetId, $strMarkup);
        $stmt->execute();
    }

    /**
     * 判斷 tweet 的內容是哪種標籤
     * @param string $strSource
     * @return string O: Origin, R: Retweet, M: Mention
     */
    private function detectMarkup($strSource) {
        $strRtn = '';
        $pattern = "([RT ]*@[0-9a-zA-Z_]+)";
        if (preg_match($pattern, $strSource)) {
            preg_match_all($pattern, $strSource, $out);
            foreach ($out[0] as $value) {
                if (preg_match("(RT[ ]*@[0-9a-zA-Z_]+)", $value)) {
                    //只要有一個符合RT就跳出不再檢查
                    $strRtn = 'R';
                    break;
                } else {
                    $strRtn = 'M';
                }
            }
        } else {
            $strRtn = 'O';
        }
        return $strRtn;
    }

    function __destruct() {
        if ($this->mysqli) {
            $this->mysqli->close();
        }
    }

}

?>
