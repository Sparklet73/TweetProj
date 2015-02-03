<?php
/**
 * Created by PhpStorm.
 * User: Chingya Lin
 * Date: 2015/1/28
 * Time: 下午 5:20
 */
require_once 'config.php';
require_once 'functions.php';

$dbh = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $dbuser, $dbpass);
$dbh ->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

require_once 'Diversity.class.php';
$diversity = new Diversity($dbh);



$dbh = NULL;