<?php 
//require_once("class/class.db.php");
date_default_timezone_set('Europe/London');
class Note{
    public  $title;
    public  $note;
    public  $date_added;
    public  $date_updated;
    public  $color;

    public  $id_online;
    public  $cat;

    public $id_user=0;
    private $db;


    public function __construct($json){
        $this->db = new db("mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_NAME, DB_USERNAME, DB_PASSWORD);
        $note = (is_string($json)) ? json_decode($json) : $json;
        
        foreach($note as $key => $value) {
            $key = trim($key);
            $value = trim($value);
            if(strtolower($key)!="issynch")$this->$key = $value;
        }
    }
    //-2 the same ,1 just updated ,0 error,-1
    public function synch(){
        //if ($this->id_online==0)return $this->insert();
        $check_res = $this->check();//echo $check_res;
        if(isset($check_res)) return $check_res;
        $date_updated_this = new DateTime(($this->date_updated=="null") ? "0000-00-00 00:00:00" : $this->date_updated);
        $date_updated_db = new DateTime($this->getDate_updated());
        
        if($date_updated_this > $date_updated_db){
            if($this->update())
                return $this->updateThis();
        }elseif($date_updated_this < $date_updated_db){
            return $this->updateThis();
        }elseif($date_updated_this == $date_updated_db){
            return $this;
        }
    }
    //return 1 if inserted new note ,2 if this note was deleted before
    private function check(){
        if($this->id_online==0){//if this is new note insert it .
            if($this->insert())
                return $this;
        }
        $res = $this->db->select("notes","id_online=".$this->id_online." and id_user =".$this->id_user,"date_updated");
        if(!$res){// this note not in th notes table
        
            $res_tr = $this->db->select("notes_trash","id_online=".$this->id_online," and id_user = ".$this->id_user,"date_updated");
            if(!$res_tr){//this note not in notes_trash too .so insert it ☺
                $this->id_online=0;
                $this->insert(1);
                return $this;
            }else{//this note in notes_trash , if updated after moved to trash recovry it ,otherwize return updated date 0000 to delete it from manotes APP
                $date_updated_this = new DateTime($this->date_updated) ;
                $date_updated_db = new DateTime($res_tr[0]["date_updated"]);
                if($date_updated_this > $date_updated_db){
                    if($this->insert(1)){
                        $this->db->delete("notes_trash","id_online=".$this->id_online." and id_user = ".$this->id_user);
                        return $this;
                    }
                }
                $note = $this;
                $note->date_added = "0000-00-00 00:00:00";
                return $note;
            }
        }
    }

    private function getDate_updated(){
        $res = $this->db->select("notes","id_online=".$this->id_online ." and id_user = ".$this->id_user,"","date_updated");
        if($res){
            return $res[0]["date_updated"];
        }
    }
    public function insert($force=null){
        if ($this->id_online!=0 and $force!=1){
            return -1;
        }
        $isDup = $this->db->select("notes","note='".$this->note."' and title='".$this->title."' and id_user=".$this->id_user);
        if($isDup){ 
            $this->date_added = "0000-00-00 00:00:00";
            return -1;
        }
        $insert = $this->toInfos();
        
        if($this->db->insert("notes",$insert)){
            return ($this->id_online = $this->db->lastInsertId());
        }
        //return ($this->db->insert("notes",$insert)===1) ? : ;
    }
    private function toInfos(){
        $infos = array();
        foreach($this as $key => $value) {
            if($key!="id" and $key!="id_online" and strtolower($key)!="issynch") $infos[$key] = $value;
            if($key=="id_online" and intval($value) != 0) $infos[$key] = $value;
            
        }
        return $infos;
    }
    public function delete($force = 0){
        if ($this->id_online==0 && $force==0){
            return -1;
        }
        return $this->db->delete("notes","id_online=".$this->id_online." and id_user = ".$this->id_user);
    }
    public function update(){
        if ($this->id_online==0){
            return -1;
        }
        $upd = $this->toInfos();
        return $this->db->update("notes",$upd,"id_online=".$this->id_online." and id_user = ".$this->id_user);
    }
    public function toJSON(){
        $note = $this;
        unset($note->db);
        $note->title = base64_encode($note->title);
        $note->note = base64_encode($note->note);
        return $note;
    }
    private function updatedToJSON(){
        //return json_encode($this->getUpdatedNote());
    }
    private function updateThis(){//echo "updatethis";
        $res = $this->db->select("notes","id_online=".$this->id_online." and id_user = ".$this->id_user);
        if($res){
            foreach ($res[0] as $key => $value) {
                $key = trim($key);
                $value = trim($value);
                if($key!="id" and strtolower($key)!="issynch")$this->$key = $value;
                //if($key=="title" or $key=="note")$this->$key = base64_encode($value);
            }
            $note = $this;
            //unset($note->db);
            //print_r($note);
            return $note;
        }
    }

}

?>