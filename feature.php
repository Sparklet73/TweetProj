<?php
/**
 * Created by PhpStorm.
 * User: Chingya Lin
 * Date: 2015/1/28
 * Time: 下午 5:20
 */
require_once 'config.php';
require_once 'Diversity.class.php';



$strDBPrefix = 'HK831';

try {
    $dbh = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $dbuser, $dbpass);
    $dbh ->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    $diversity = new Diversity($dbh);
    $diversity->setDBPrefixName($strDBPrefix);

} catch (PDOException $ex) {
    echo $ex->getMessage();
}

$dbh = NULL;