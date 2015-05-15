<?php
/* 後來決定先不用vertical tweets line，這個沒寫完*/
require_once 'config.php';

$strNodeLabel = filter_input(INPUT_GET, 'nl', FILTER_SANITIZE_STRING);
$strNeighbors = filter_input(INPUT_GET, 'sn', FILTER_SANITIZE_STRING);

if( !$strUser || !$strNeighbors) {
    $arrResult['rsStatus'] = false;
    $arrResult['rsAns'] = "The parameter has problem.";
}

$arrN = explode("//", $strNeighbors);

try {
    $dbh = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $dbuser, $dbpass);
    $dbh ->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    $sql = "";

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
