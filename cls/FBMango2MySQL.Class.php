<?php

/**
 * Description of FBMango2MySQL
 * 由 Facebook 粉絲專頁 Crawl 回來存在 MangoDB 的資料轉至 MySQL
 *
 * @author Jeffy_shih (jeffy@ninthday.info)
 */
class FBMango2MySQL {

    private $mysqli;
    private $Mongo_CONNECT;
    private $Mongo_DB;
    private $Mongo_COLLECTION;

    /**
     * Weight value Of Relation
     * 
     * @var array C2P: Commenter to Poster
     * C2C: Commenter to Commenter,
     * PL2P: Post Liker to Poster,
     * PL2PL: Poster Liker to Post Liker, 
     * CL2C: Comment Liker to Commenter
     * CL2CL: Comment Liker to Comment Liker
     */
    private $aryWeight = array(
        'C2P' => 1,
        'C2C' => 1,
        'PL2P' => 1,
        'PL2PL' => 1,
        'CL2C' => 1,
        'CL2CL' => 1
    );

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

    public function goTransMongo2SQL() {
        $cursor = $this->Mongo_COLLECTION->find();
        $aryContent = array();
        $i = 1;
        foreach ($cursor as $document) {
            unset($aryContent);
            if (array_key_exists('_id', $document)) {
                $aryId = explode('_', $document['_id']);
                $aryContent['object_id'] = (int) $aryId[1];
            } else {
                $aryContent['object_id'] = 0;
            }

            echo $i, '. POST: ', $aryContent['object_id'], "\n";

            if (array_key_exists('from', $document)) {
                $aryContent['from_id'] = (int) $document['from']['id'];
                $aryContent['from_name'] = $document['from']['name'];
            } else {
                $aryContent['from_id'] = 0;
                $aryContent['from_name'] = '';
            }

            $aryContent['partial_like_count'] = (array_key_exists('partial_like_count', $document)) ? (int) $document['partial_like_count'] : 0;
            if (!empty($aryContent['partial_like_count'])) {
                $this->buildRelation($aryContent['object_id'], $aryContent['from_id'], $document['likes'], 0);
            }

            $aryContent['message'] = (array_key_exists('message', $document)) ? $document['message'] : '';
            $aryContent['created_time'] = (array_key_exists('created_time', $document)) ? $document['created_time'] : '';
            $aryContent['comment_count'] = (array_key_exists('comment_count', $document)) ? (int) $document['comment_count'] : 0;
            if (!empty($aryContent['comment_count'])) {
                // Parse Comment here
                $this->parseComment($aryContent['from_id'], $document['comments']);
            }
            $aryContent['shares'] = (array_key_exists('shares', $document)) ? (int) $document['shares']['count'] : 0;
            $aryContent['type'] = (array_key_exists('type', $document)) ? $document['type'] : '';
            $aryContent['link'] = (array_key_exists('link', $document)) ? $document['link'] : '';

            $this->saveTransData($aryContent);
            $this->saveUserData($aryContent['from_id'], $aryContent['from_name']);
            $i++;
        }
    }

    /**
     * 
     * @param array $aryComments
     */
    private function parseComment($intPostUser, $aryComments) {
        $aryContent = array();
        $aryCmterIds = array();
        foreach ($aryComments as $comment) {
            unset($aryContent);
            if (array_key_exists('id', $comment)) {
                $aryId = explode('_', $comment['id']);
                $aryContent['object_id'] = (int) $aryId[1];
                $aryContent['comment_id'] = (int) $aryId[2];
            } else {
                $aryContent['object_id'] = 0;
                $aryContent['comment_id'] = 0;
            }

            echo '-> Comment: ', $aryContent['comment_id'], "\n";
            if (array_key_exists('from', $comment)) {
                $aryContent['from_id'] = (int) $comment['from']['id'];
                $aryContent['from_name'] = $comment['from']['name'];
            } else {
                $aryContent['from_id'] = 0;
                $aryContent['from_name'] = '';
            }

            $aryContent['like_count'] = (array_key_exists('like_count', $comment)) ? (int) $comment['like_count'] : 0;
            if (!empty($aryContent['like_count'])) {
                $this->buildRelation($aryContent['object_id'], $aryContent['from_id'], $comment['likes']['data'], 1);
            }
            $aryContent['created_time'] = (array_key_exists('created_time', $comment)) ? $comment['created_time'] : '';
            $aryContent['message'] = (array_key_exists('message', $comment)) ? $comment['message'] : '';

            $this->saveCommentData($aryContent);
            $this->buildCmterPosterRelation($aryContent['object_id'], $intPostUser, $aryContent['from_id']);
            $aryCmterIds[] = $aryContent['from_id'];
            
            $this->saveUserData($aryContent['from_id'], $aryContent['from_name']);
        }

        $this->buildCmterRelation($aryContent['object_id'], $aryCmterIds);
    }

    /**
     * Save Trans Post data to database
     * 
     * @param array $aryContent
     */
    private function saveTransData($aryContent) {
        $sql_insert = "INSERT INTO `FB_newsandmarket`(`object_id`, `partial_like_count`, `created_time`, `message`, `from_id`, `from_name`, `comment_count`, `shares`, `type`, `link`) 
            VALUES (?,?,?,?,?,?,?,?,?,?)";
        $strStmt = $this->mysqli->prepare($sql_insert);
        $strStmt->bind_param('iissisiiss', $aryContent['object_id'], $aryContent['partial_like_count'], $aryContent['created_time'], $aryContent['message'], $aryContent['from_id'], $aryContent['from_name'], $aryContent['comment_count'], $aryContent['shares'], $aryContent['type'], $aryContent['link']
        );
        $strStmt->execute();
        $strStmt->close();
    }

    /**
     * Save Trans comment data to database
     * 
     * @param array $aryContent
     */
    private function saveCommentData($aryContent) {
        $sql_insert = "INSERT INTO `FB_newsandmarket_Comment`(`object_id`, `comment_id`, `from_id`, `from_name`, `like_count`, `created_time`, `message`) 
            VALUES (?,?,?,?,?,?,?)";
        $strStmt = $this->mysqli->prepare($sql_insert);
        $strStmt->bind_param('iiisiss', $aryContent['object_id'], $aryContent['comment_id'], $aryContent['from_id'], $aryContent['from_name'], $aryContent['like_count'], $aryContent['created_time'], $aryContent['message']
        );
        $strStmt->execute();
        $strStmt->close();
    }

    /**
     * Build User Relationship with Direct
     * 
     * @param int $intObjectId Facebook Object ID
     * @param int $intUserId From User Id
     * @param array $aryLiker likers array
     * @param int $intCategory Category: 0:Post 1:Comment
     */
    private function buildRelation($intObjectId, $intUserId, $aryLikers, $intCategory) {
        $sql_insert = "INSERT INTO `FB_newsandmarket_Relation`(`object_id`, `relation_category`, `source`, `target`, `weight`) VALUES (?,?,?,?,?)";

        $strStmt = $this->mysqli->prepare($sql_insert);
        $strStmt->bind_param('isiii', $intObjectId, $strCategory, $intSourceId, $intTargetId, $intWeight);

        $intTargetId = $intUserId;

        switch ($intCategory) {
            case 0:
                $strCategory = 'PL2P';
                break;
            case 1:
                $strCategory = 'CL2C';
                break;
            default:
                break;
        }

        echo '---> Build ', $strCategory, " Relation. \n";

        $intWeight = $this->aryWeight[$strCategory];
        $aryLikerIds = array(); //Change multi-layer array to single-layer
        foreach ($aryLikers as $user) {
            $intSourceId = $user['id'];
            $strStmt->execute();
            
            $this->saveUserData($user['id'], $user['name']);
//            $aryLikerIds[] = $user['id'];
        }

//        $this->buildLikerRelation($intObjectId, $aryLikerIds, $intCategory);

        $strStmt->close();
    }

    /**
     * Relation Between Likers Undirect
     * 
     * @param int $intObjectId Facebook Object ID
     * @param array $aryLiker likers IDs array
     * @param int $intCategory Category: 0:Post 1:Comment
     */
    private function buildLikerRelation($intObjectId, $aryLikerIds, $intCategory) {

        asort($aryLikerIds);
        $intLen = count($aryLikerIds);

        $sql_insert = "INSERT INTO `FB_newsandmarket_Relation`(`object_id`, `relation_category`, `source`, `target`, `weight`) VALUES (?,?,?,?,?)";
        $strStmt = $this->mysqli->prepare($sql_insert);
        $strStmt->bind_param('isiii', $intObjectId, $strCategory, $intSourceId, $intTargetId, $intWeight);

        switch ($intCategory) {
            case 0:
                $strCategory = 'PL2PL';
                break;
            case 1:
                $strCategory = 'CL2CL';
                break;
            default:
                break;
        }

        echo '---> Build ', $strCategory, " Liker Relation. \n";

        $intWeight = $this->aryWeight[$strCategory];
        for ($i = 0; $i < $intLen - 1; $i++) {
            for ($j = $i + 1; $j < $intLen; $j++) {
                $intSourceId = $aryLikerIds[$i];
                $intTargetId = $aryLikerIds[$j];
                $strStmt->execute();
            }
        }
        $strStmt->close();
    }

    /**
     * Build relation between commenter and poster
     * 
     * @param int $intObjectId Facebook Object ID
     * @param int $intPosterId Poster User Facebook ID
     * @param int $intCmterId Comment User Facebook ID
     */
    private function buildCmterPosterRelation($intObjectId, $intPosterId, $intCmterId) {
        $sql_insert = "INSERT INTO `FB_newsandmarket_Relation`(`object_id`, `relation_category`, `source`, `target`, `weight`) VALUES (?,?,?,?,?)";
        $strStmt = $this->mysqli->prepare($sql_insert);
        $strStmt->bind_param('isiii', $intObjectId, $strCategory, $intCmterId, $intPosterId, $intWeight);
        $strCategory = 'C2P';
        $intWeight = $this->aryWeight[$strCategory];

        echo '---> Build ', $strCategory, " Commenter and Poster Relation. \n";

        $strStmt->execute();
        $strStmt->close();
    }

    /**
     * Build relation between all commenter in the same post
     * 
     * @param int $intObjectId Facebook Object ID
     * @param array $aryCmterIds All Commenter Ids
     */
    private function buildCmterRelation($intObjectId, $aryCmterIds) {
        asort($aryCmterIds);
        $intLen = count($aryCmterIds);
        $sql_insert = "INSERT INTO `FB_newsandmarket_Relation`(`object_id`, `relation_category`, `source`, `target`, `weight`) VALUES (?,?,?,?,?)";
        $strStmt = $this->mysqli->prepare($sql_insert);
        $strStmt->bind_param('isiii', $intObjectId, $strCategory, $intSourceId, $intTargetId, $intWeight);
        $strCategory = 'C2C';

        echo '---> Build ', $strCategory, " Commenter Relation. \n";

        $intWeight = $this->aryWeight[$strCategory];
        for ($i = 0; $i < $intLen - 1; $i++) {
            for ($j = $i + 1; $j < $intLen; $j++) {
                $intSourceId = $aryCmterIds[$i];
                $intTargetId = $aryCmterIds[$j];
                $strStmt->execute();
            }
        }
        $strStmt->close();
    }

    private function saveUserData($intUserId, $strUserName) {
        $sql_insert = "INSERT INTO `FB_newsandmarket_User`(`user_id`, `user_name`) VALUES (?,?)";
        $strStmt = $this->mysqli->prepare($sql_insert);
        $strStmt->bind_param('is',$intUserId,$strUserName);
        $strStmt->execute();
        $strStmt->close();
    }

    function __destruct() {
        if ($this->mysqli) {
            $this->mysqli->close();
        }
    }

}

?>
