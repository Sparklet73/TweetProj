<?php
/**
 * Created by PhpStorm.
 * User: CYa
 * Date: 2015/4/30
 * Time: 下午 07:36
 */
require_once 'config.php';

/*$strDate = filter_input(INPUT_GET, 'dt', FILTER_SANITIZE_STRING);

if(!$strDate) {
    $arrResult['rsStat'] = false;
    $arrResult['rsGraph'] = "The parameter has problem.";
*/

try {
    $dbh = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $dbuser, $dbpass);
    $dbh ->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT `text`
            FROM `HKALL_tags_RT10cnt_date`";
            //WHERE `date`= " . $strDate . ";";

    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    $arrResult['rsStat'] = true;
    $id = 1;
    while($arrQue = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
        $arrKeywords = explode("/",$arrQue['text']);
        foreach($arrKeywords as $word)
            $id += 1;
            $arrNodes[$word] = $word;
    }
    $arrEdges;
    $arrNodes;
    $arrResult['rsGraph'] = $arrTweets;

} catch(PDOException $ex) {
    $arrResult['rsStatus'] = false;
    $arrResult['rsAns'] = $ex->getMessage();
} catch(Exception $exc){
    echo $exc->getMessage();
}

$dbh = NULL;

echo json_encode($arrResult);