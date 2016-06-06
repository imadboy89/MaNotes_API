<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(-1);

session_start();
require_once("config.php");
require_once("class/class.db.php");
$db = new db("mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_NAME, DB_USERNAME, DB_PASSWORD);

  require_once("login.php");

  

  
  
?>