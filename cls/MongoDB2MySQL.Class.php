<?php

/**
 * Description of MongoDB2MySQL
 * 將儲存於 Mongodb 的資料解開儲存至 MySQL
 * 
 * @author Jeffy_shih <jeffy@ninthday.info>
 * @since 1.0
 * @copyright (c) 2014, Ninthday
 */
class MongoDB2MySQL {

    private $pdoDB = NULL;
    private $dbh = NULL;
    private $Mongo_CONNECT;
    private $Mongo_DB;
    private $Mongo_COLLECTION;

    public function __construct(myPDOConn $pdoConn) {
        $this->pdoDB = $pdoConn;
        $this->dbh = $this->pdoDB->dbh;

        //連結至 mangodb
        $this->Mongo_CONNECT = new MongoClient();
    }

    public function transFacebook($strDBName, $strCollection) {
        $this->Mongo_DB = $this->Mongo_CONNECT->$strDBName;
        $this->Mongo_COLLECTION = $this->Mongo_DB->$strCollection;

        $cursor = $this->Mongo_COLLECTION->find();
        // 先把解開的資料放在 array 裏面
        $aryContent = array();
        $i = 1;
        foreach ($cursor as $fb_feed) {
            //清除前一次的內容
            unset($aryContent);
            //取得 Object ID (_id 的第二段)
            if (array_key_exists('_id', $fb_feed)) {
                $aryId = explode('_', $fb_feed['_id']);
                $aryContent[':crawltarget_id'] = (int) $aryId[0];
                $aryContent[':object_id'] = (int) $aryId[1];
            } else {
                $aryContent[':crawltarget_id'] = 0;
                $aryContent[':object_id'] = 0;
            }
            echo $i, '. POST: ', $aryContent[':object_id'], "\n";

            //取得 from 資料
            if (array_key_exists('from', $fb_feed)) {
                $aryContent[':from_id'] = (int) $fb_feed['from']['id'];
                $aryContent[':from_name'] = $fb_feed['from']['name'];
            } else {
                $aryContent[':from_id'] = 0;
                $aryContent[':from_name'] = '';
            }

            //計算 likes 數量有多少
            if (array_key_exists('likes', $fb_feed)) {
                $aryContent[':like_count'] = count($fb_feed['likes']['data']);
            } else {
                $aryContent[':like_count'] = 0;
            }

            //取得訊息內容（message）、建立時間（created_time）
            $aryContent[':message'] = (array_key_exists('message', $fb_feed)) ? $fb_feed['message'] : '';
            $aryContent[':created_time'] = (array_key_exists('created_time', $fb_feed)) ? $fb_feed['created_time'] : '';
            //計算 comment 數量
            if (array_key_exists('comments', $fb_feed)) {
                $aryContent[':comment_count'] = count($fb_feed['comments']['data']);
                $this->parseComments($aryContent[':object_id'], $fb_feed['comments']['data']);
            } else {
                $aryContent[':comment_count'] = 0;
            }
            //取得 shares 的數量、tyoe 內容、 link 內容和 description 內容
            $aryContent[':shares'] = (array_key_exists('shares', $fb_feed)) ? (int) $fb_feed['shares']['count'] : 0;
            $aryContent[':type'] = (array_key_exists('type', $fb_feed)) ? $fb_feed['type'] : '';
            $aryContent[':link'] = (array_key_exists('link', $fb_feed)) ? $fb_feed['link'] : '';
            $aryContent[':description'] = (array_key_exists('description', $fb_feed)) ? $fb_feed['description'] : '';

            try {
                //儲存拉出來的資料
                $this->saveTransData($aryContent);
            } catch (Exception $exc) {
                echo $exc->getMessage();
            }

            $i++;
        }
    }

    /**
     * 儲存解開放在 Array 裡的資料
     * 
     * @param array $aryContent
     * @throws Exception
     */
    private function saveTransData($aryContent) {
        $sql_insert = 'INSERT INTO `FB_feeds`(`crawltarget_id`, `object_id`, `like_count`, `created_time`, `message`, `from_id`, `from_name`, `comment_count`, `shares`, `type`, `link`, `description`) '
                . 'VALUES (:crawltarget_id,:object_id,:like_count,:created_time, :message, :from_id, :from_name, :comment_count, :shares, :type, :link, :description)';
        try {
            $sth = $this->dbh->prepare($sql_insert);
            $sth->execute($aryContent);
        } catch (PDOException $exc) {
            throw new Exception($exc->getMessage());
        }
    }

    private function parseComments($feed_id, $aryFeedCmts) {
        $aryContent = array();
        foreach ($aryFeedCmts as $comment) {
            unset($aryContent);
            $aryContent[':feed_id'] = (int) $feed_id;
            if (array_key_exists('id', $comment)) {
                $aryId = explode('_', $comment['id']);
                $aryContent[':object_id'] = (int) $aryId[0];
                $aryContent[':comment_id'] = (int) $aryId[1];
            } else {
                $aryContent[':object_id'] = 0;
                $aryContent[':comment_id'] = 0;
            }

            echo '-> Comment: ', $aryContent[':comment_id'], "\n";

            if (array_key_exists('from', $comment)) {
                $aryContent[':from_id'] = (int) $comment['from']['id'];
                $aryContent[':from_name'] = $comment['from']['name'];
            } else {
                $aryContent[':from_id'] = 0;
                $aryContent[':from_name'] = '';
            }

            $aryContent[':like_count'] = (array_key_exists('like_count', $comment)) ? (int) $comment['like_count'] : 0;
            $aryContent[':created_time'] = (array_key_exists('created_time', $comment)) ? $comment['created_time'] : '';
            $aryContent[':message'] = (array_key_exists('message', $comment)) ? $comment['message'] : '';

            try {
                $this->saveFeedComment($aryContent);
            } catch (Exception $exc) {
                echo $exc->getMessage();
            }
        }
    }

    private function saveFeedComment($aryContent) {
        $sql_insert = 'INSERT INTO `FB_feeds_Comment`(`feed_id`, `object_id`, `comment_id`, `from_id`, `from_name`, `like_count`, `created_time`, `message`) '
                . 'VALUES (:feed_id, :object_id, :comment_id, :from_id, :from_name, :like_count, :created_time, :message)';
        try {
            $sth = $this->dbh->prepare($sql_insert);
            $sth->execute($aryContent);
        } catch (PDOException $exc) {
            throw new Exception($exc->getTraceAsString());
        }
    }

    public function __destruct() {
        $this->pdoDB = NULL;
    }

}

?>