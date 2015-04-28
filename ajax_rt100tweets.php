<?php
/**
 * Created by PhpStorm.
 * User: IMkoba
 * Date: 2015/3/6
 * Time: 上午 10:30
 */
require_once 'config.php';
//require_once 'class/User.class.php';

$strDBPrefix = 'HKALL';

/*$strUser = filter_input(INPUT_GET, 'usr', FILTER_SANITIZE_STRING);
$strContent = filter_input(INPUT_GET, 'ct', FILTER_SANITIZE_STRING);
$strTweetTime = filter_input(INPUT_GET,'tt', FILTER_SANITIZE_STRING);

if(!$strUser || !$strContent || !$strTweetTime) {
    $arrResult['rsStatus'] = false;
    $arrResult['rsAns'] = "The parameter has problem.";
}*/

try {
    $dbh = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $dbuser, $dbpass);
    $dbh ->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT `text` as content, DATE_FORMAT(`created_at`,'%Y-%m-%d %H')start FROM `HKALL_tweets_zh`
            where `retweet_count` > 99
            GROUP BY `retweet_id`";

    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    $arrResult['rsStatus'] = true;
    $arrTweets = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $arrResult['rsAns'] = $arrTweets;

} catch(PDOException $ex) {
    $arrResult['rsStatus'] = false;
    $arrResult['rsAns'] = $ex->getMessage();
} catch(Exception $exc){
    echo $exc->getMessage();
}

$dbh = NULL;

echo json_encode($arrResult);
