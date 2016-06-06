<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(-1);


require_once("config.php");
require_once("class/class.db.php");
class updater{
    private $version;
    private $version_name;
    private $desc;
    private $device;
    private $os;
    private $date ;
    private $version_db;
    
    public function __construct($version,$userid,$device,$os){
        $this->db = new db("mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_NAME, DB_USERNAME, DB_PASSWORD);
        if(!$version)die($this->toJsonMSG("Parameteres Missing :( ."));
        $this->version = $version;
        $this->userid = $userid;
        $this->device = $device;
        $this->os = $os;
        echo $this->check();
    }
    private function check(){
        $id_user = ($this->userid!="")?"(select id from users where userid='".$this->userid."')" : 0;
        $this->db->run("insert into updates_history(id_user,ip,old_release,device,os) values($id_user,'".$_SERVER['REMOTE_ADDR']."',".$this->version.",'".$this->device."','".$this->os."')");
        //$res = $this->db->select("releases","","","id,max( version ) AS version, `version_name` , `date` , `desc` ,`url`");
        $res = $this->db->run("select id,max( version ) AS version, `version_name` , `date` , `description` ,`url` from releases group by 1 order by 2 desc");
        if($res){
            $this->version_db = $res[0]["version"];
        }
        if(floatval($this->version_db) <= floatval($this->version)) die($this->toJsonMSG("you have the lastest version :) ."));
        return json_encode($res[0]);
    }
    private function toJsonMSG($msg){
        return "{\"error\":\"$msg\"}";
    }
}
$device = (isset($_GET["device"]))?$_GET["device"]:"";
$os = (isset($_GET["os"]))?$_GET["os"]:"";
$userid = (isset($_GET["username"]))?$_GET["username"]:"";
$version = $_GET["version"];
$up = new updater($version,$userid,$device,$os);

?>