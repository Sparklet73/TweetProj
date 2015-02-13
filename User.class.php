<?php
/**
 * Created by PhpStorm.
 * User: IMkoba
 * Date: 2015/2/12
 * Time: 下午 5:29
 */

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
            `user_Id` bigint(20) unsigned NOT NULL,
            `user_name` varchar(255) NOT NULL,
            `user_tweetCount` int(10) unsigned NOT NULL,
            `user_followerCount` int(10) unsigned NOT NULL,
            `user_friendCount` int(10) unsigned NOT NULL,
            `user_listedCount` int(10) unsigned NOT NULL,
            `user_description` varchar(255),
            `user_lang` varchar(16),
            `user_location` varchar(64),
            PRIMARY KEY (`user_Id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
        try {
            $stmt = $this->dbh->prepare($sql_init);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function saveUserData() {
        $sql = "INSERT INTO ". $this->strUserTable.  "  (`user_Id`,`user_name`,`user_tweetCount`,`user_followerCount`,`user_friendCount`,`user_listedCount`,`user_description`,`user_lang`,`user_location`)
                SELECT `from_user_id`,`from_user_name`, max(`from_user_tweetcount`),max(`from_user_followercount`),max(`from_user_friendcount`),max(`from_user_listed`),`from_user_description`,`from_user_lang`,`location`
                FROM `" . $this->strBinName . "_tweets`
                GROUP BY `from_user_id`;";
        try {
            $stmt = $this->dbh->prepare($sql);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }
}