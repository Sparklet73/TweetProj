<?php
/**
 * Created by PhpStorm.
 * User: Chingya Lin
 * Date: 2015/1/28
 * Time: 下午 5:20
 */
require_once 'config.php';
require_once 'functions.php';

$db = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $dbuser, $dbpass);
$db -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

require_once 'Diversity.class.php';
$diversity = new Diversity();

$usersForWord = $userDiversity = $distinctUsersForWord = array();

$sql = "SELECT LOWER(h.text) as h1, COUNT(t.from_user_id) as c, COUNT(DISTINCT(t.from_user_id)) AS d ";
$sql .= "FROM HK831_hashtags h, HK831_tweets t ";
$where = "h.tweet_id = t.id AND ";
$sql .= sqlSubset($where);
$sql .= "GROUP BY h1";
$stmt = $db->prepare($sql);
while ($res = mysql_fetch_assoc($sqlresults)) {
    $word = $res['h1'];
    $coword->distinctUsersForWord[$word] = $res['d'];
    $coword->userDiversity[$word] = round(($res['d'] / $res['c']) * 100, 2);
    $coword->wordFrequency[$word] = $res['c'];
    $coword->wordFrequencyDividedByUniqueUsers[$word] = round($res['c'] / $res['d'], 2);
    $coword->wordFrequencyMultipliedByUniqueUsers[$word] = $res['c'] * $res['d'];
}

