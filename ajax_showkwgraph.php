<?php
/**
 * Created by PhpStorm.
 * User: CYa
 * Date: 2015/4/30
 * Time: 下午 07:36
 */
require_once 'config.php';

$strStartDate = filter_input(INPUT_GET, 'sd', FILTER_SANITIZE_STRING);
$strEndDate = filter_input(INPUT_GET, 'ed', FILTER_SANITIZE_STRING);

if( !$strStartDate || !$strEndDate ) {
    $arrResult['rsStat'] = false;
    $arrResult['rsGraph'] = "The parameter has problem.";
}

try {
    $dbh = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $dbuser, $dbpass);
    $dbh ->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);


    $sql = "SELECT *
            FROM `HKALL_tags_RT10cnt_date`
            WHERE `date` between '" . $strStartDate . "' and '" . $strEndDate ."';";

    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    $arrResult['rsStat'] = true;
    //nodes:id,label,size(weight)
    //edges:id,source,target
    $arrNodeSize = array(); //arr[id]=weight
    $arrNodeLabel = array(); //arr[id]=label
    $arrNodeId = array(); //help build edge relation
    $arrEdge = array(); //arr['source']['target']
    $edge_id = 1;

    while($arrQue = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $arrKeywords = explode("/", $arrQue['text']);
        foreach($arrKeywords as $word) {
            if($key = array_search($word,$arrNodeLabel)) {
                $arrNodeSize[$key] += 1;
            } else {
                $c = count($arrNodeLabel);
                $arrNodeLabel[$c] = $word;
                $arrNodeSize[$c] = 1;
                $arrNodeId[] = $c;
            }
        }

        sort($arrNodeId);
        $len_text = count($arrNodeId);

        for($i=0; $i < $len_text; $i++){
            for($j=$i+1; $j < $len_text; $j++){
                array_push($arrEdge, array(
                    "id" => $edge_id,
                    "source" => $arrNodeId[$i],
                    "target" => $arrNodeId[$j]));
                $edge_id++;
            }
        }
        unset($arrNodeId);
    }

    foreach($arrNodeLabel as $key => $value){
        array_push($arrTweets['nodes'], array(
            "id" => $key,
            "label" => $value,
            "size" => $arrNodeSize[$key],
            "x" => rand(-40,40),
            "y" => rand(-20,20),
            "color" => "#333"));
    }

    $arrTweets['edges'] = $arrEdge;

    $arrResult['rsGraph'] = $arrTweets;

} catch(PDOException $ex) {
    $arrResult['rsStatus'] = false;
    $arrResult['rsGraph'] = $ex->getMessage();
} catch(Exception $exc){
    echo $exc->getMessage();
}

$dbh = NULL;

echo json_encode($arrResult);