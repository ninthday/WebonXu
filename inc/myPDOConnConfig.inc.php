<?php

/*
 * Configuration of PDO Database Connection
 */
$pdoConfig = array(
    'DB_DRIVER' => 'mysql',
    'DB_HOST' => 'localhost',
    'DB_NAME' => 'FFProject',
    'DB_PORT' => '3306',
    'DB_USER' => 'wfp',
    'DB_PASSWD' => 'cSwfP88',
    'DB_OPTIONS' => array(
        PDO::ATTR_PERSISTENT => TRUE,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8';"
    )
);
?>
