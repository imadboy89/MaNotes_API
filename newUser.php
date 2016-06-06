<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(-1);


if(!isset($_GET["username"]) || !isset($_GET["password"]) || $_GET["password"]=="" || $_GET["username"]=="" )die("{'error':'not password or username given !'}");

require_once("config.php");
require_once("class/class.db.php");

$userid = $_GET["username"];
$password  = $_GET["password"];
$device = (isset($_GET["device"]))?$_GET["device"]:"";
$os = (isset($_GET["os"]))?$_GET["os"]:"";
$imei = (isset($_GET["imei"]))?$_GET["imei"]:"";
class User{
    private $userid;
    private $password;
    private $device ;
    private $os;
    private $ip;
    private $db ;
    private $imei;
    public function __construct($userid,$password,$device,$os,$imei){
        $this->db = new db("mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_NAME, DB_USERNAME, DB_PASSWORD);
        $this->userid = $userid;
        $this->password = $password;
        $this->device = $device;
        $this->os = $os;
        $this->imei = $imei;
        $this->ip = $_SERVER['REMOTE_ADDR'];
    }
    
    public function createNewUser(){
        $res = $this->db->select("users","userid='".$this->userid."'");
        if($res and isset($res[0]["id"]))
            die($this->toJsonMSG("This id is already used by another user please choose another ."));
        $this->check();
        $re = $this->db->insert("users",array("userid"=>$this->userid,"password"=>$this->password,"ip"=>$this->ip,"device"=>$this->device,"os"=>$this->os,"imei"=>$this->imei));
        if ($re)
            return $this->toJsonMSG("User account created !");
    }
    private function check(){
        if($this->imei=="") return 1;
        $res = $this->db->select("users","imei='".$this->imei."'","","count(*) as count");
        if($res and intval($res[0]["count"])>=2)
            die($this->toJsonMSG("you reached accounts limit ."));
    }
    private function toJsonMSG($msg){
        return "{\"error\":\"$msg\"}";
    }
}

$user = new User($userid,$password,$device,$os,$imei);
echo $user->createNewUser();
?>