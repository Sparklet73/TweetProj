<?php
/**
 * Created by PhpStorm.
 * User: IMkoba
 * Date: 2015/2/12
 * Time: 下午 5:29
 */

header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=example.csv");
header("Pragma: no-cache");
header("Expires: 0");

class User {
    private $dbh = NULL;
    private $strBinName = NULL;
    private $strUserTable = NULL;

    private $userName;
    private $userId;
    private $tweetCount;
    private $followerCount;
    private $friendCount;
    private $listedCount;

    private $description;
    private $lang;
    private $location;

    public function __construct($dbh, $strDBPrefix) {
        $this->dbh = $dbh;

        $this->setDBPrefixName($strDBPrefix);
        $this->initUserTable();
        $this->saveUserData();
    }

    // set database prefix word
    public function setDBPrefixName($strDBPrefix) {
        if (empty($strDBPrefix)) {
            throw new Exception('Database prefix name is empty!');
        }
        $this->strBinName = $strDBPrefix;
        $this->strUserTable = $strDBPrefix . '_UserTable';
    }

    public function initUserTable() {
        $sql_init = "CREATE TABLE IF NOT EXISTS `" . $this->strUserTable . "` (
            `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `user_Id` bigint(20) unsigned NOT NULL,
            `user_name` varchar(255) NOT NULL,
            `user_tweetCount` int(10) unsigned NOT NULL,
            `user_followerCount` int(10) unsigned NOT NULL,
            `user_friendCount` int(10) unsigned NOT NULL,
            `user_listedCount` int(10) unsigned NOT NULL,
            `user_description` varchar(255),
            `user_lang` varchar(16),
            `user_location` varchar(64),
			`user_maxRTcount` int(10) unsigned NOT NULL,
            PRIMARY KEY (`id`),
            KEY (`user_Id`),
            KEY (`user_tweetCount`),
            KEY (`user_followerCount`),
            KEY (`user_friendCount`),
            KEY (`user_listedCount`),
			KEY (`user_maxRTcount`),
            FULLTEXT KEY (`user_description`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
        try {
            $stmt = $this->dbh->prepare($sql_init);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function saveUserData() {
        $sql = "INSERT INTO ". $this->strUserTable.  "  (`user_Id`,`user_name`,`user_tweetCount`,`user_followerCount`,`user_friendCount`,`user_listedCount`,`user_description`,`user_lang`,`user_location`,`user_maxRTcount`)
                SELECT `from_user_id`,`from_user_name`, max(`from_user_tweetcount`), max(`from_user_followercount`) as indegree, max(`from_user_friendcount`), max(`from_user_listed`), `from_user_description`, `from_user_lang`, `location`, max(`retweet_count`)
                FROM `" . $this->strBinName . "_tweets`
                GROUP BY `from_user_id`
                ORDER BY `indegree` DESC;";
        try {
            $stmt = $this->dbh->prepare($sql);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    //將使用者依照追隨者數來排名，追隨者最多的是第一名，看1~100,101~200,201~300這三群的使用者散佈的推文
	public function UserRankLevel() {
        $f1 = fopen("output/UserRank1tweet.csv","w");
        $f2 = fopen("output/UserRank2tweet.csv","w");
        $f3 = fopen("output/UserRank3tweet.csv","w");

        $f1txt = fopen('output/f1txt.txt', 'w');
        $f2txt = fopen('output/f2txt.txt', 'w');
        $f3txt = fopen('output/f3txt.txt', 'w');


        //userIndegreeRank,rtID,UserName,tweets,createdTIME,RTcount
		$sql ="SELECT `Users`.`id`, `tweets`.`retweet_id`,`Users`.`user_name`, `tweets`.`text`,`tweets`.`created_at`,`tweets`.`retweet_count` rt
                FROM `" . $this->strBinName . "_UserTable` as Users, `" . $this->strBinName . "_tweets` as tweets
                where `Users`.`user_Id`=`tweets`.`from_user_id` AND `Users`.`id` < :rank2 AND `Users`.`id` > :rank1 ;";
        try {
            $rank1 = 0;
            $rank2 = 101;
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindParam(':rank1', $rank1,PDO::PARAM_INT);
            $stmt->bindParam(':rank2', $rank2,PDO::PARAM_INT);
            $stmt->execute();
            $result1 = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $rank1 = 100;
            $rank2 = 201;
            $stmt->execute();
            $result2 = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $rank1 = 200;
            $rank2 = 301;
            $stmt->execute();
            $result3 = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }

        foreach($result1 as $val) {
            fwrite($f1txt,$val['text']);
            fputcsv($f1, $val);
        }
        foreach($result2 as $val) {
            fwrite($f2txt,$val['text']);
            fputcsv($f2, $val);
        }
        foreach($result3 as $val) {
            fwrite($f3txt,$val['text']);
            fputcsv($f3, $val);
        }
        fclose($f1);
        fclose($f2);
        fclose($f3);
        fclose($f1txt);
        fclose($f2txt);
        fclose($f3txt);
	}
}