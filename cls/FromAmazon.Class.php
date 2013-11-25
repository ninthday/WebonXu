<?php


/**
 * Description of FromAmazon
 * 將 Amazon 上抓回來 mangodb 的資料轉到 mysql 資料庫
 *
 * @author Jeffy_shih (jeffy@ninthday.info)
 */
class FromAmazon {

    private $mysqli;
    private $Mongo_CONNECT;
    private $Mongo_DB;
    private $Mongo_COLLECTION;

    function __construct($strDBName, $strCollection) {
        include _APP_PATH . 'inc/ConFF.inc.php';
        $this->mysqli = @new mysqli($mysqlHost, $mysqlUser, $mysqlPassword, $mysqlDB);
        if (mysqli_connect_errno()) {
            throw new Exception('無法建立資料庫連線！' . mysqli_connect_error(), 101);
            $this->mysqli = false;
            exit();
        }

        $this->mysqli->query("SET NAMES 'utf8'");

        //連結至 mangodb
        $this->Mongo_CONNECT = new MongoClient();
        $this->Mongo_DB = $this->Mongo_CONNECT->$strDBName;
        $this->Mongo_COLLECTION = $this->Mongo_DB->$strCollection;
    }

    /**
     * 取出 mangodb 中的 tweet 資料解析後留下必要欄位，取出存至 mySQL 中
     */
    public function goTransMongo2SQL() {
        $i = 1;
        $cursor = $this->Mongo_COLLECTION->find();
        $aryContent = array();
        foreach ($cursor as $document) {
//            if ($i > 15)
//                break;
            unset($aryContent);
            $aryContent['task_id'] = (array_key_exists('task_id', $document)) ? $document['task_id'] : '';
            $aryContent['sns'] = (array_key_exists('sns', $document)) ? $document['sns'] : '';
            $aryContent['project_id'] = (array_key_exists('project_id', $document)) ? $document['project_id'] : '';
            $aryContent['tweet_id'] = (array_key_exists('id', $document)) ? $document['id'] : '';
            $aryContent['data_iso_language_code'] = (array_key_exists('iso_language_code', $document['data'])) ? $document['data']['iso_language_code'] : NULL;
            $aryContent['data_text'] = $document['data']['text'];
            if (array_key_exists('from_user_id', $document['data'])) {
                $aryContent['data_from_user_id'] = $document['data']['from_user_id'];
                $aryContent['data_from_user'] = $document['data']['from_user'];
                $aryContent['data_from_user_name'] = $document['data']['from_user_name'];
            }else{
                $aryContent['data_from_user_id'] = $document['data']['user']['id'];
                $aryContent['data_from_user'] = $document['data']['user']['screen_name'];
                $aryContent['data_from_user_name'] = $document['data']['user']['name'];
            }

            $aryContent['data_id'] = $document['data']['id_str'];
            $aryContent['data_created_at'] = $document['data']['created_at'];
            $aryContent['data_geo'] = $document['data']['geo'];
            $aryContent['lang_detection'] = $document['languagedetection']['data']['detections'][0]['language'];

            $this->saveTweetData($aryContent);
            echo $i, '...';
            if ($i % 10 == 0)
                echo "\n";
            $i++;
        }

        echo '<br>Total Count: ', $i;
    }

    /**
     * 儲存轉換的Tweet內容
     * 
     * @param array $aryContent 要存入的資料陣列
     * @throws Exception
     * @access private
     */
    private function saveTweetData($aryContent) {
        $sql_save = 'INSERT INTO `fromAmazon` VALUES(NULL, 
            \'' . $aryContent['task_id'] . '\', 
            \'' . $aryContent['sns'] . '\', 
            \'' . $aryContent['project_id'] . '\', 
            \'' . $aryContent['tweet_id'] . '\', 
            \'' . $aryContent['data_iso_language_code'] . '\', 
            \'' . addslashes($aryContent['data_text']) . '\', 
            \'' . $aryContent['data_from_user_id'] . '\', 
            \'' . $aryContent['data_from_user'] . '\', 
            \'' . addslashes($aryContent['data_from_user_name']) . '\', 
            \'' . $aryContent['data_id'] . '\', 
            \'' . $aryContent['data_created_at'] . '\', 
            \'' . $aryContent['data_geo'] . '\', 
            \'' . $aryContent['lang_detection'] . '\')';
        $rs_save = $this->mysqli->query($sql_save);
        if (!$rs_save) {
            throw new Exception('It has problem on save tweet_id = ' . $aryContent['data_id'] . '<br>' . $sql_save);
        }
    }

    function __destruct() {
        if ($this->mysqli) {
            $this->mysqli->close();
        }
    }

}

?>
