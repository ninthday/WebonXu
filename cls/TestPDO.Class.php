<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TestPDO
 *
 * @author Jeffy_shih
 */
class TestPDO {
    private $pdoDB = NULL;
    private $dbh= NULL;
    public function __construct(myPDOConn $pdoConn) {
        $this->pdoDB = $pdoConn;
        $this->dbh = $pdoConn->dbh;
    }
    
    public function getTweet(){
        $sql_get = 'SELECT `data_text`, `TWTime` FROM `PresidentialElection` LIMIT 0,30';
        $stmt = $this->dbh->prepare($sql_get);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo $row['data_text'] , '<br>';
        }
    }


    public function __destruct() {
        $this->pdoDB = NULL;
        echo "TestPDO::__destruct";
    }
}

?>
