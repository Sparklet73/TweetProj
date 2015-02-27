<?php
/**
 * Created by PhpStorm.
 * User: Chingya Lin
 * Date: 2015/1/27
 * Time: 下午 4:48
 */

class Diversity {

    private $dbh = NULL;
    private $strBinName = NULL;
    private $strUserDivName = NULL;
    private $strMentionDivName = NULL;
    private $arrUserDiversity = array();
    private $arrMentionDiversity = array();

    public function __construct($dbh, $strDBPrefix) {
        $this->dbh = $dbh;

        $this->setDBPrefixName($strDBPrefix);
        $this->initTableUserDiv();
        $this->userDiversity();
        //$this->initTableMentionDiv();
        //$this->mentionDiversity();
    }

    // set database prefix word
    public function setDBPrefixName($strDBPrefix) {
        if (empty($strDBPrefix)) {
            throw new Exception('Database prefix name is empty!');
        }
        $this->strBinName = $strDBPrefix;
        $this->strUserDivName = $strDBPrefix . '_UserDiv';
        $this->strMentionDivName = $strDBPrefix . '_MentionDiv';
    }

    //initial table for storing UserDiversity
    public function initTableUserDiv() {
        $sql_init = "CREATE TABLE IF NOT EXISTS `" . $this->strUserDivName . "` (
            `UDId` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `hashtag` varchar(255) NOT NULL,
            `date` datetime NOT NULL,
            `userDiv` float unsigned NOT NULL,
            `userCount` int(10) unsigned NOT NULL,
            `uniqueUserCount` int(10) unsigned NOT NULL,
            PRIMARY KEY (`UDId`),
            KEY (`hashtag`),
            KEY (`date`),
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
        try {
            $stmt = $this->dbh->prepare($sql_init);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    //initial table for storing MentionDiversity
    public function initTableMentionDiv() {
        $sql_init = "CREATE TABLE IF NOT EXISTS `" . $this->strMentionDivName . "` (
            `MDId` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `starUsrId` bigint(20) unsigned NOT NULL,
            `starUsrName` varchar(255) NOT NULL,
            `date` datetime NOT NULL,
            `mentionDiv` float unsigned NOT NULL,
            `rtUsrCount` int(10) unsigned NOT NULL,
            `uniqueRtUsrCount` int(10) unsigned NOT NULL,
            PRIMARY KEY (`MDId`),
            KEY (`starUsrId`),
            KEY (`date`),
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
        try {
            $stmt = $this->dbh->prepare($sql_init);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    // get user diversity per hashtag
    public function userDiversity () {
        $sql = "SELECT LOWER(h.text) h1, DATE_FORMAT(created_at,'%Y-%m-%d') datepart, COUNT(from_user_id) c, COUNT(DISTINCT(from_user_id)) d
                FROM `" . $this->strBinName . "_hashtags` h
                GROUP BY h1, datepart;";
        try {
            $stmt = $this->dbh->prepare($sql);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }

        $arrUsersForHashtag = $arrDistinctUsersForHashtag = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $date = $row['datepart'];
            $hashtag = $row['h1'];
            //if (!isset($arrUsersForHashtag[$date][$hashtag])) //先檢查這一天是否有這個hashtag
            //    $arrUsersForHashtag[$date][$hashtag] = 0;
            //$arrUsersForHashtag[$date][$hashtag] += $row['c'];
            //if (!isset($arrDistinctUsersForHashtag[$date][$hashtag]))
            //    $arrDistinctUsersForHashtag[$date][$hashtag] = 0;
            //$arrDistinctUsersForHashtag[$date][$hashtag] += $row['d'];
            $arrUsersForHashtag[$date][$hashtag] = $row['c'];
            $arrDistinctUsersForHashtag[$date][$hashtag] = $row['d'];
        }
        $sql_write = "REPLACE INTO `" . $this->strUserDivName . "`
                        VALUES(NULL,:hashtag,:date,:userDiv,:userCount,:uniqueUserCount);";
        try {
            $stmt = $this->dbh->prepare($sql_write);
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }

        foreach ($arrDistinctUsersForHashtag as $date => $hashtags) {
            foreach ($hashtags as $hashtag => $distinctUserCount) {
                // (number of unique users using the hashtag) / (frequency of use)
                $this->arrUserDiversity[$date][$hashtag] = round(($arrDistinctUsersForHashtag[$date][$hashtag] / $arrUsersForHashtag[$date][$hashtag]), 2);

                $stmt->bindParam(':hashtag', $hashtag, PDO::PARAM_STR);
                $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                $stmt->bindParam(':userDiv', $this->arrUserDiversity[$date][$hashtag], PDO::PARAM_STR);
                $stmt->bindParam(':userCount', $arrUsersForHashtag[$date][$hashtag], PDO::PARAM_STR);
                $stmt->bindParam(':uniqueUserCount', $arrDistinctUsersForHashtag[$date][$hashtag], PDO::PARAM_STR);
                $stmt->execute();
            }
        }
    }

	// get rt/mention diversity per user
    public function mentionDiversity() {
        $sql = "SELECT `to_user` usr, DATE_FORMAT(created_at,'%Y-%m-%d') datepart, count(`from_user_name`) c, count(distinct(`from_user_name`)) d
                FROM `" . $this->strBinName . "_mentions`
                GROUP BY `to_user_id`;";
        try {
            $stmt = $this->dbh->prepare($sql);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
        $arrRtUsersCount = $arrDistinctRtUsersCount = array();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $date = $row['datepart'];
            $usr = $row['usr'];
            $arrRtUsersCount[$date][$usr] = $row['c'];
            $arrDistinctRtUsersCount[$date][$usr] = $row['d'];
        }

        $sql_write = "REPLACE INTO `" . $this->strMentionDivName . "`
                        VALUES(NULL,:User,:date,:mentionDiv,:RtUserCount,:uniqueRtUserCount);";
        try {
            $stmt = $this->dbh->prepare($sql_write);
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }

        foreach ($arrDistinctRtUsersCount as $date => $usrs) {
            foreach ($usrs as $usr => $distinctUserCount) {
                $this->arrMentionDiversity[$date][$usr] = round(($arrDistinctRtUsersCount[$date][$usr] / $arrRtUsersCount[$date][$usr]), 2);

                $stmt->bindParam(':User', $usr, PDO::PARAM_STR);
                $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                $stmt->bindParam(':mentionDiv', $this->arrMentionDiversity[$date][$usr], PDO::PARAM_STR);
                $stmt->bindParam(':RtUserCount', $arrRtUsersCount[$date][$usr], PDO::PARAM_STR);
                $stmt->bindParam(':uniqueRtUserCount', $arrDistinctRtUsersCount[$date][$usr], PDO::PARAM_STR);
                $stmt->execute();
            }
        }

    }

    public function vocabularyDiversity() {

    }

    // link hashtags and mentionee with tweet
    public function relationHashtagMentionee() {
        $sql = "SELECT  h.`tweet_id`, h.`text`,h.`from_user_name`,m.`to_user`
                FROM `" . $this->strBinName . "_hashtags` h, `" . $this->strBinName . "_mentions` m
                WHERE h.`tweet_id`=m.`tweet_id`
                GROUP BY h.`tweet_id`";

    }
}
