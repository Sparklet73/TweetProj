<?php
/**
 * Created by PhpStorm.
 * User: IMkoba
 * Date: 2015/3/6
 * Time: 上午 10:30
 */
require_once 'config.php';
require_once 'User.class.php';

$strDBPrefix = 'HK928';

$strUser = filter_input(INPUT_GET, 'usr', FILTER_SANITIZE_STRING);
$strContent = filter_input(INPUT_GET, 'ct', FILTER_SANITIZE_STRING);
$strStartDay = filter_input(INPUT_GET,'sd', FILTER_SANITIZE_STRING);

if(!$strUser || !$strContent || !$strStartDay) {
    $arrResult['rsStatus'] = false;
    $arrResult['rsAns'] = "The parameter has problem.";
}

try {
    $dbh = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $dbuser, $dbpass);
    $dbh ->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);


} catch(PDOException $ex) {
    $arrResult['rsStatus'] = false;
    $arrResult['rsAns'] = $ex->getMessage();
}

echo json_enode($arrResult);