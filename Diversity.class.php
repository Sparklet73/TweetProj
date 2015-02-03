<?php
/**
 * Created by PhpStorm.
 * User: Chingya Lin
 * Date: 2015/1/27
 * Time: 下午 4:48
 */

class Diversity {

    private $dbh = NULL;
    private $arrUserDiversity = array(array());

    public function __constructor($dbh) {
        $this->dbh = $dbh;

    }

    // get user diversity per hashtag
    public function userDiversity () {
        $sql = "SELECT LOWER(h.text) h1, DATE_FORMAT(created_at,'%Y-%m-%d') datepart, COUNT(from_user_id) c, COUNT(DISTINCT(from_user_id)) d
                FROM HK831_hashtags h
                GROUP BY h1, datepart";
        try {
            $stmt = $this->dbh->prepare($sql);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }

        $arrUsersForHashtag = $arrDistinctUsersForHashtag = array(array());
        $arrHashtags = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $date = $row['datepart'];
            $arrHashtags[] = $row['h1'];
            $arrUsersForHashtag[$date][$row['h1']] = $row['c'];
            $arrDistinctUsersForHashtag[$date][$row['h1']] = $row['d'];
        }
        foreach ($arrDistinctUsersForHashtag as $date => $hashtag) {
            foreach ($arrHashtags as $hashtag => $distinctUserCount) {
                // (number of unique users using the hashtag) / (frequency of use)
                $this->arrUserDiversity[$date][$hashtag] = round(($arrDistinctUsersForHashtag[$date][$hashtag] / $arrUsersForHashtag[$date][$hashtag]) * 100, 2);
            }
        }
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
