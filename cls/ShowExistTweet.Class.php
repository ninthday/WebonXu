<?php

/**
 * Description of ShowExistTweet
 *
 * @author Jeffy_shih
 */
class ShowExistTweet {

    private $pdoDB = NULL;
    private $dbh = NULL;

    public function __construct(myPDOConn $pdoConn) {
        $this->pdoDB = $pdoConn;
        $this->dbh = $pdoConn->dbh;
    }

    /**
     * Get Tweets by Page Control
     * 
     * @param array $aryQuery
     * @return array Result
     */
    public function getTweets($aryQuery, PageControl $objPage) {
        $aryRtn = array();
        $aryCondition = $this->combineCondition($aryQuery);
        $strWhere = $aryCondition[0];
        $aryParam = $aryCondition[1];

        $sql_get = 'SELECT * FROM `VIEW_ShowTweet` ' . $strWhere . ' LIMIT ' . $objPage->getBeginRow() . ', ' . $objPage->getPerPageSize();
//        echo $sql_get, '<br>';
//        var_dump($aryParam);
        $stmt = $this->dbh->prepare($sql_get);
        $stmt->execute($aryParam);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($aryRtn, $row);
        }
        return $aryRtn;
    }
    
    /**
     * Get All Tweets
     * 
     * @param array $aryQuery
     * @return array Result
     */
    public function getAllTweets($aryQuery) {
        $aryRtn = array();
        $aryCondition = $this->combineCondition($aryQuery);
        $strWhere = $aryCondition[0];
        $aryParam = $aryCondition[1];
        
        $sql_get = 'SELECT * FROM `VIEW_ShowTweet` ' . $strWhere . 'LIMIT 0,3000';
        $stmt = $this->dbh->prepare($sql_get);
        $stmt->execute($aryParam);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($aryRtn, $row);
        }
        return $aryRtn;
    }

    /**
     * Get total number of query result
     * @param type $aryQuery
     * @return int Query total result count
     */
    public function getTotalNum($aryQuery) {
        $aryCondition = $this->combineCondition($aryQuery);
        $strWhere = $aryCondition[0];
        $aryParam = $aryCondition[1];
        $sql_get = 'SELECT COUNT(*) FROM `VIEW_ShowTweet` ' . $strWhere;
        $stmt = $this->dbh->prepare($sql_get);
        $stmt->execute($aryParam);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $intRtn = (int) $row[0];
        return $intRtn;
    }

    /**
     * To Combine SQL SeteWhere Condition
     * @param type $aryQuery
     * @return array [0]:Condition string, [1]:Parameter array
     */
    private function combineCondition($aryQuery) {
        $aryRtn = array();
        $strWhere = 'WHERE 1';
        $aryParam = array();
        //If Twitter User Account is set to query
        if (array_key_exists('twid', $aryQuery)) {
            $strWhere .= ' AND `data_from_user`=:twid';
            $aryParam[':twid'] = $aryQuery['twid'];
        }
        //If Twitter User Name is set to query
        if (array_key_exists('unme', $aryQuery)) {
            $strWhere .= ' AND `data_from_user_name`=:unme';
            $aryParam[':unme'] = $aryQuery['unme'];
        }
        //If Begin date and End Date is set to query
        if (array_key_exists('bdate', $aryQuery) && array_key_exists('edate', $aryQuery)) {
            $strWhere .= ' AND (`TWTime` BETWEEN :bdate AND :edate)';
            $aryParam[':bdate'] = $aryQuery['bdate'] . ' 00:00:00';
            $aryParam[':edate'] = $aryQuery['edate'] . ' 23:59:59';
        }
        //If Language is set to query
        if (array_key_exists('lang', $aryQuery)) {
            $aryLangs = explode('+', $aryQuery['lang']);
            //If Count < 5 means NOT select all tpyes, set WHERE condition
            if (count($aryLangs) == 1 && $aryLangs[0] == 'other') {
                $strWhere .= ' AND `lang_detection` NOT IN (\'zh-TW\', \'zh\', \'en\', \'jp\')';
            } elseif (count($aryLangs) < 5) {
//                $strWhere .= ' AND `lang_detection` IN (:lang)';
                $strOtherLang = ')';
                $aryNewLang = array();

                foreach ($aryLangs as $strLang) {
                    switch ($strLang) {
                        case 'zh-TW':
                            array_push($aryNewLang, '\'zh-TW\'');
                            break;
                        case 'zh':
                            array_push($aryNewLang, '\'zh\'');
                            break;
                        case 'ja':
                            array_push($aryNewLang, '\'ja\'');
                            break;
                        case 'en':
                            array_push($aryNewLang, '\'en\'');
                            break;
                        case 'other':
                            $strOtherLang = ' OR `lang_detection` NOT IN (\'zh-TW\', \'zh\', \'en\', \'ja\'))';
                            break;
                        default:
                            break;
                    }
                }
                $strWhere .= ' AND (`lang_detection` IN (' . implode(', ', $aryNewLang) . ')';
                $strWhere .= $strOtherLang;
//                $aryParam[':lang'] = implode(', ', $aryNewLang);
            }
        }
        //If Tweet Type (Mention, Retweet, Original) is set to query
        if (array_key_exists('typ', $aryQuery)) {
            $aryTypes = explode('+', $aryQuery['typ']);
            //If Count=3 means select all tpyes, set WHERE condition
            if (count($aryTypes) < 3) {
//                $strWhere .= ' AND `Mark` IN (:typ)';

                $aryNewTypes = array();
                foreach ($aryTypes as $strType) {
                    switch ($strType) {
                        case 'or':
                            array_push($aryNewTypes, '\'O\'');
                            break;
                        case 'rt':
                            array_push($aryNewTypes, '\'R\'');
                            break;
                        case 'mt':
                            array_push($aryNewTypes, '\'M\'');
                            break;
                        default:
                            break;
                    }
                }
                $strWhere .= ' AND `Mark` IN (' . implode(', ', $aryNewTypes) . ')';
//                $aryParam[':typ'] = implode(', ', $aryNewTypes);
            }
        }

        $aryRtn[0] = $strWhere;
        $aryRtn[1] = $aryParam;
        return $aryRtn;
    }

    public function __destruct() {
        $this->pdoDB = NULL;
    }

}

?>
