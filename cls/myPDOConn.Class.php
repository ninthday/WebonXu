<?php

/**
 * Description of myPDOConn
 *
 * @author Jeffy_shih
 */

class myPDOConn {

    protected static $instance = NULL;
    // Handle of the database connexion
    public $dbh;

    final private function __construct() {
        try {
            include _APP_PATH . 'inc/myPDOConnConfig.inc.php';
            $dsn = $pdoConfig['DB_DRIVER'] . ':host=' . $pdoConfig['DB_HOST'] .
                    ';dbname=' . $pdoConfig['DB_NAME'] .
                    ';port=' . $pdoConfig['DB_PORT'] .
                    ';connect_timeout=30';
            $this->dbh = new PDO($dsn, $pdoConfig['DB_USER'], $pdoConfig['DB_PASSWD'], $pdoConfig['DB_OPTIONS']);
            $this->dbh->exec("SET NAMES 'utf8'");
        } catch (PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    /**
     * To avoid copies
     */
    final private function __clone() {}

    public static function getInstance() {
        if(!isset(self::$instance)){
            $object = __CLASS__;
            self::$instance = new $object;
        }
        return self::$instance;
    }

    final public function __destruct() {
        $this->dbh = NULL;
        self::$instance = NULL;
    }

}

?>
