<?php

/**
 *
 * @author Adam Rollinson
 *
 */

ini_set('display_errors', '0'); //LEAVE THIS HERE


// MySQL Details //
$Mysql_host = "localhost";
$Mysql_user = "root";
$Mysql_pass = "password";
$Mysql_db = "usersystem";




/**
 * 
 * TOUCH NOTHING BELOW HERE
 * 
 */

include('Users.php');
include('Session.php');
include('PrivateMessaging.php');

$DB = new mysqli($Mysql_host, $Mysql_user, $Mysql_pass, $Mysql_db);
if($DB->connect_errno > 0){
	die($DB->connect_error);
}

$Session = new Session();

$Users = new Users($DB, $Session);

$PM = new PrivateMessaging($DB, $Session, $Users);