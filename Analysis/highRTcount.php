<?php
/**
 * Created by PhpStorm.
 * User: IMkoba
 * Date: 2015/3/9
 * Time: 上午 11:20
 */

require_once '../config.php';

try {
    $dbh = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $dbuser, $dbpass);
    $dbh ->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
} catch (PDOException $ex) {
    echo $ex->getMessage();
}

$sql = "SELECT  `from_user_name`,`from_user_description`,`from_user_followercount` follower,`from_user_listed` listed,`text`,`retweet_id`,max(`retweet_count`)
        FROM `HK928_tweets`
        GROUP BY DATE_FORMAT(created_at,'%Y-%m-%d')";

try {
    $stmt = $this->dbh->prepare($sql);
    $stmt->execute();
} catch (PDOException $e) {
    throw new Exception($e->getMessage());
}

$arrUserTweets = array();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $user = $row['username'];
    $hashtag = $row['h1'];
    $arrUsersForHashtag[$date][$hashtag] = $row['c'];
    $arrDistinctUsersForHashtag[$date][$hashtag] = $row['d'];
}


$dbh = NULL;