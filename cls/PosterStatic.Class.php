<?php

/**
 * Description of PosterStatic
 * 2014-09-09
 * 統計與分析 Poster 資料
 * 
 * @author ninthday <jeffy@ninthday.info>
 * @version 1.0
 * @copyright (c) 2014, ninthday
 */

namespace Floodfire\TwitterProcess;

class PosterStatic {

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

    public function getPosterByLang($aryLang, $strSort) {
        $aryRtn = array();
        $intCount = count($aryLang);
        $strLang = "'" . implode("', '", $aryLang) . "'";
        $sql = "SELECT `VIEW_PE_Static`.* FROM `VIEW_PE_Static`
            INNER JOIN (SELECT `data_from_user` FROM `VIEW_PE_Static`
            WHERE `lang_detection` IN (" . $strLang . ")
            GROUP BY `data_from_user`
            HAVING COUNT(*) >= " . $intCount . ") AS `T`
            ON `T`.`data_from_user` = `VIEW_PE_Static`.`data_from_user`
            WHERE `lang_detection` IN (" . $strLang . ")";
        
        if($strSort == 'uname'){
            $sql .= ' ORDER BY `data_from_user`';
        }elseif ($strSort == 'cnt') {
            $sql .= ' ORDER BY `CNT` DESC';
        }
        
        try {
            $stmt = $this->dbh->prepare($sql);
            $stmt->execute();
        } catch (PDOException $exc) {
            throw new Exception($exc->getMessage());
        }
        
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            array_push($aryRtn, $row);
        }
        
        return $aryRtn;
    }
    
    public function showTweetsByPoster($strName, $strLang){
        $aryRtn = array();
        $sql = 'SELECT `data_id`, `data_text`, `data_from_user`, `data_from_user_name`, `lang_detection`, `TWTime` FROM `PresidentialElection` 
               WHERE `data_from_user` = :from_user_name AND `lang_detection` = :lang_detection;';
        try {
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindParam(':from_user_name', $strName, \PDO::PARAM_STR);
            $stmt->bindParam(':lang_detection', $strLang, \PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $exc) {
            throw new Exception($exc->getMessage());
        }
        
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            array_push($aryRtn, $row);
        }
        return $aryRtn;
    }
    
    public function showTweetsByLang($strLang){
        $aryRtn = array();
        $aryLang = explode('+', $strLang);
        $intCount = count($aryLang);
        $strLangCondition = "'" . implode("', '", $aryLang) . "'";
        $sql = "SELECT `data_id`, `data_text`, `PresidentialElection`.`data_from_user`, `data_from_user_name`, `lang_detection`, `TWTime` FROM `PresidentialElection` 
            INNER JOIN (SELECT `data_from_user` FROM `VIEW_PE_Static`
            WHERE `lang_detection` IN (" . $strLangCondition . ")
            GROUP BY `data_from_user`
            HAVING COUNT(*) >= " . $intCount . ") AS `T`
            ON `T`.`data_from_user` = `PresidentialElection`.`data_from_user`
            WHERE `lang_detection` IN (" . $strLangCondition . ") ORDER BY `PresidentialElection`.`data_from_user`";
        
        try {
            $stmt = $this->dbh->prepare($sql);
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
