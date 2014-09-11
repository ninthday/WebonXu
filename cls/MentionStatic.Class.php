<?php

/**
 * Description of MentionStatic
 * 2013-08-15
 * 統計計算 Mention 資料
 *
 * @author ninthday <jeffy@ninthday.info>
 * @version 1.0
 * @copyright (c) 2014, Jeffy Shih
 */

namespace Floodfire\TwitterProcess;

class MentionStatic {

    private $pdoDB = NULL;
    private $dbh = NULL;

    /**
     * 連線設定
     * @param \Floodfire\myPDOConn $pdoConn myPDOConn object
     */
    public function __construct(\Floodfire\myPDOConn $pdoConn) {
        $this->pdoDB = $pdoConn;
        $this->dbh = $this->pdoDB->dbh;
    }

    public function getMentionTop($strDBName, $intLimit) {
        $aryRtn = array();
        $sql = 'SELECT `to_user`, COUNT(*) AS `CNT` FROM `' . $strDBName . '`
            GROUP BY `to_user` ORDER BY `CNT` DESC LIMIT 0,:limit';
        try {
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindParam(':limit', $intLimit, \PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $exc) {
            throw new Exception($exc->getMessage());
        }
        
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            array_push($aryRtn, $row);
        }
        return $aryRtn;
    }
    
    public function showTweetByName($strName){
        $aryRtn = array();
        $sql = 'SELECT `data_id`,`data_text`, `data_from_user`, `data_from_user_name`, `lang_detection`, `TWTime`,`markup` FROM `PE_Relation` 
            INNER JOIN `PresidentialElection` ON `PresidentialElection`.`data_id` = `PE_Relation`.`tweet_id`
            WHERE `to_user` = :from_user_name;';
        try {
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindParam(':from_user_name', $strName, \PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $exc) {
            throw new Exception($exc->getMessage());
        }
        
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            array_push($aryRtn, $row);
        }
        return $aryRtn;
    }

    public function __destruct() {
        $this->pdoDB = NULL;
    }

}
