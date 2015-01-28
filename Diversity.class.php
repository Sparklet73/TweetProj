<?php
/**
 * Created by PhpStorm.
 * User: Chingya Lin
 * Date: 2015/1/27
 * Time: 下午 4:48
 */

class Diversity {

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
