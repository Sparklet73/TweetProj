<?php
/**
 * Created by PhpStorm.
 * User: IMkoba
 * Date: 2015/3/9
 * Time: 上午 11:20
 */

require_once '../config.php';

//找出每天每時的最終超過10個RT的推文
try {
    $dbh = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $dbuser, $dbpass);
    $dbh ->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
} catch (PDOException $ex) {
    echo $ex->getMessage();
}

$sql = "SELECT DATE_FORMAT(created_at,'%Y-%m-%d %H') as date,`text`,`retweet_id`, `retweet_count`
        FROM `HKALL_tweets_zh`
        WHERE `retweet_count` >9
        GROUP BY `id`,date
        ORDER BY date ASC;";

try {
    $stmt = $this->dbh->prepare($sql);
    $stmt->execute();
} catch (PDOException $e) {
    throw new Exception($e->getMessage());
}

$tweetscsv = fopen('HKALL_tweets_RT10count.csv', 'w');
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach($result as $val) {
    #fwrite($tweetscsv,$val['text']);
    fputcsv($tweetscsv, $val);
}

fclose($tweetscsv);

/*$arrUserTweets = array();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $user = $row['username'];
    $hashtag = $row['h1'];
    $arrUsersForHashtag[$date][$hashtag] = $row['c'];
    $arrDistinctUsersForHashtag[$date][$hashtag] = $row['d'];
}*/


$dbh = NULL;