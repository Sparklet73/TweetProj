<?php
/**
 * Created by PhpStorm.
 * User: Chingya Lin
 * Date: 2015/1/28
 * Time: 下午 5:20
 */
require_once 'config.php';
require_once 'Diversity.class.php';
require_once 'User.class.php';

$strDBPrefix = 'HK928';

try {
    $dbh = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $dbuser, $dbpass);
    $dbh ->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    //$diversity = new Diversity($dbh, $strDBPrefix);
    //$user = new User($dbh, $strDBPrefix);

} catch (PDOException $ex) {
    echo $ex->getMessage();
}
//SELECT DATE_FORMAT(created_at,'%Y-%m-%d') datepart,count(*) as tweets,max(`retweet_count`) as maxRT, avg(`retweet_count`) as avgRT FROM `HK928_tweets`
//GROUP BY `datepart`
//ORDER BY `datepart` ASC

$dbh = NULL;