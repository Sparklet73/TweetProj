<?php
/**
 * Created by PhpStorm.
 * User: Chingya Lin
 * Date: 2015/1/28
 * Time: 下午 5:20
 */
require_once 'config.php';

try {
    $db = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $dbuser, $dbpass);
} catch (PDOException $e) {
    $errorMessage = $e->getCode() . ': ' . $e->getMessage();
    return $errorMessage;
}

$usersForWord = $userDiversity = $distinctUsersForWord = array();

$sql = "SELECT LOWER(text) as hashtag, COUNT(from_user_id) as c, COUNT(DISTINCT(from_user_id)) as d ";
$sql .= "FROM HK831_hashtags, HK831_tweets";
$sql .= "GROUP BY `text`";
$sql .= "ORDER BY `c` DESC";

$rec = $db->prepare($sql);
