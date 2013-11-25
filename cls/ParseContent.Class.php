<?php

/**
 * Description of ParseContent
 *
 * @author Jeffy_shih
 */
class ParseContent {

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

    public function getURLContent() {
        // The Regular Expression filter
        $reg_exUrl = "/(http|https|ftp|sftp)\:\/\/[a-zA-Z0-9\-\.]+\.\w{2,3}(\/\w*)?/";

        $sql_get = 'SELECT `data_id`, `data_text` FROM `PresidentialElection`';
        $rs_get = $this->mysqli->query($sql_get);
        $i = 0;
        $j = 1;
        $aryURLs = array();
        while ($row_get = $rs_get->fetch_row()) {
            preg_match_all($reg_exUrl, $row_get[1], $out);

            foreach ($out[0] as $url) {
                echo $j, '. ', $row_get[0], ' ====> ', $url, '<br>';
                if (count($aryURLs) >= 10) {
                    $this->saveURLs($aryURLs);
                    echo 'Before Save: ', Count($aryURLs), '....................................';
                    unset($aryURLs);
                    $aryURLs = array();
                    echo 'After Save: ', Count($aryURLs);
                    array_push($aryURLs, '(' . $row_get[0] . ' ,\'' . $url . '\')');
                } else {
//                    var_dump($aryURLs);
                    array_push($aryURLs, '(' . $row_get[0] . ' ,\'' . $url . '\')');
                }
                $j++;
            }
        }
        $this->saveURLs($aryURLs);
    }

    private function saveURLs($aryURLs) {
        $sql_insert = 'INSERT INTO `URLinTweets`( `TweetId`, `ShortenURL`) VALUES';
        $sql_insert .= implode(', ', $aryURLs);
        $this->mysqli->query($sql_insert);
    }

    public function saveRegularURL() {
        $sql_get = 'SELECT `URTId`, `ShortenURL` FROM `URLinTweets`';
        $rs_get = $this->mysqli->query($sql_get);
        while ($row_get = $rs_get->fetch_row()) {
            $strRegularURL = $this->expendShortURL2($row_get[1]);
            $sql_update = 'UPDATE `URLinTweets` SET `regularURL`=\'' . $strRegularURL . '\' WHERE `URTId`=' . $row_get[0];
            if (!$this->mysqli->query($sql_update)) {
                throw new Exception('Something Error in Record: ' . $row_get[0]);
            } else {
                echo 'Shorten URL is: ', $row_get[1], ' ------------> Regular URL: ', $strRegularURL, "\n";
            }
        }
    }

    public function expendShortURL($strURL) {
        $aryHeader = get_headers($strURL, 1);
        $intCount = count($aryHeader['Location']);
        return $aryHeader['Location'][$intCount - 1];
    }

    public function expendShortURL2($strURL) {
        //Get response headers
        $response = get_headers($strURL, 1);
        //Get the location property of the response header. If failure, return original url
        if (array_key_exists('Location', $response)) {
            $location = $response["Location"];
            if (is_array($location)) {
            // t.co gives Location as an array
                return $this->expendShortURL2($location[count($location) - 1]);
            } else {
                return $this->expendShortURL2($location);
            }
        }
        return $strURL;
    }

    function __destruct() {
        if ($this->mysqli) {
            $this->mysqli->close();
        }
    }

}

?>
