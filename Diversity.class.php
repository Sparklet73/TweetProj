<?php
/**
 * Created by PhpStorm.
 * User: Chingya Lin
 * Date: 2015/1/27
 * Time: 下午 4:48
 */

class Diversity {

    private $dbh = NULL;
    private $arrUsersForWord = NULL;
    private $arrDistinctUsersForWord = NULL;
    private $arrUserDiversity = NULL;

    public function __constructor($dbh) {
        $this->dbh = $dbh;

    }

    public function countUsers () {
        $sql = "SELECT LOWER(h.text) as h1, COUNT(t.from_user_id) as c, COUNT(DISTINCT(t.from_user_id)) AS d
                FROM HK831_hashtags h, HK831_tweets t
                h.tweet_id = t.id AND ";
        $sql .= sqlSubset($where);
        $sql .= "GROUP BY h1";
        try {
            $stmt = $this->dbh->prepare($sql);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }

    }

    // get user diversity per hashtag
    public function userDiversity($usersForWord, $distinctUsersForWord) {
        //$userDiversity[$date][$word] = round(($distinctUsersForWord[$date][$word] / $usersForWord[$date][$word]) * 100, 2);

    }

	// get mention diversity per user in this period
	//SELECT `to_user`, count(`from_user_id`), count(distinct(`from_user_id`)) as c FROM `HK831_mentions` GROUP BY `to_user_id` ORDER BY c DESC
    public function mentionDiversity() {
		//$mentionDiversity[$date][$user] = round(($distinctUsers[$date][$rtUser] / $users[$date][$rtUser]) * 100, 2);
    }

    public function languageDiversity() {

    }

    public function VocabularyDiversity() {

    }
}
