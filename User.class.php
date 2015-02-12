<?php
/**
 * Created by PhpStorm.
 * User: IMkoba
 * Date: 2015/2/12
 * Time: 下午 5:29
 */

class User {
    private $dbh = NULL;

    private $userName;
    private $userId;
    private $tweetCount;
    private $followerCount;
    private $friendCount;
    private $listedCount;

    private $description;
    private $lang;
    private $location;

    public function __construct($dbh) {
        $this->dbh = $dbh;
    }

    public function initUserTable() {

    }

}