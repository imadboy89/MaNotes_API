<?php 
require_once("config.php");
require_once("class/class.db.php");
require_once("Note.php");

class manotes_mng{

    private $userid ;
    private $id_user ;
    private $password;
    private $isLoged = 0;//need to be 0 
    private $errors;
    private $command;
    private $data;
    private $db ;
    private $notes_db = array() ;
    private $notes_and = array(); 

	public function __construct($userid, $password, $command, $data="") {
        $this->userid = $userid;
        $this->password = $password;
        $this->command = $command;
        $this->data = $data;
        $this->login();
        $this->deleteNotes();
        $this->loadNotes_android();
        $this->loadNotes_db();
        //if($this->loadNotes_android()==-1) die("{\"error\":\"ERROR JSON format not valide !\"}");
        
        //print_r($this->notes_db);
    }
    private function login(){
        $db1 = new db("mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_NAME, DB_USERNAME, DB_PASSWORD);
        if(!$db1)die("{\"error\":\"Database service Not vailable for this moment !\"}");
        $user_rs = $db1->select("users","userid='".$this->userid."' and isActive");
		if ($user_rs)
			foreach ($user_rs as $user){
				$pass = $user["password"];
				if ($this->password == $pass){
					$this->db = $db1;//new db("mysql:host=127.0.0.1;port=3306;dbname=manotes", "root", "");
					$this->isLoged = 1; 
					$this->id_user = $user["id"];
					$this->db->insert("synch_history",array("id_user"=>$this->id_user,"ip"=>$_SERVER['REMOTE_ADDR'],"device"=>(isset($_GET["device"]))?$_GET["device"]:"","os"=>(isset($_GET["os"]))?$_GET["os"]:""));
				}
			}
	if($this->isLoged == 0)die("{\"error\":\"Password or Username or both not Correct !\"}");
    }
    private function deleteNotes(){
        if(isset($_GET["remove"])){
            $notes = json_decode($_GET["remove"]);
            if(is_null($notes)) return-1;
            foreach ($notes as $note){
                $this->delete($note);
            }
        }
    }
    public function delete($note){
        if ($note->id_online==0){
            return -1;
        }
        return $this->db->delete("notes","id_online=".$note->id_online." and date_updated <= '".$note->date_updated."' and id_user = ".$this->id_user);
    }

    public function getNotes(){
        if($this->isLoged == 0){return ;}
        return json_encode($this->notes_db);
    }
    public function loadNotes_db(){
        if($this->isLoged == 0){return ;}
        //$notes_rs = $this->db->select("notes","id_user='".$this->id_user."'");
        $notes_rs = $this->db->select("notes","id_user = ".$this->id_user,"","*","order by date_added");
        if($notes_rs){
            foreach ($notes_rs as $key => $value) {
                $note = new Note(json_encode($value));
                //unset($note->db);
                $this->notes_db[] = $note;
            }
            return count($notes_rs);
        }
        return 0;
    }
    /*
    public function loadNotes_android_old(){
        if($this->isLoged == 0){return ;}
        foreach($_GET as $key=>$value){
            $patt = "/^note_[0-9]+$/i";
            if(preg_match($patt, $key, $matches)){
                $nt = $_GET[$matches[0]];
                $note = new Note($nt);
                //unset($note->db);
                $this->notes_and[] = $note;
            }

        }
        return count($this->notes_and);
    }*/
    public function loadNotes_android(){
        if($this->isLoged == 0){return ;}
        if(!isset($_GET["notes"]))return -1;
        $notes = json_decode(trim($_GET["notes"]));
        if(is_null($notes)) return-1;

        foreach($notes as $nt){
                $note = new Note($nt);
                //unset($note->db);
                $note->id_user = $this->id_user;
                $this->notes_and[] = $note;
            }
        return count($this->notes_and);
    }
    public function setNotes($notes){
        
    }
    public function synch(){
        if($this->isLoged == 0){return ;}
        $returns="";
        $notes_and = array();
        if(isset($this->notes_and ))
            foreach($this->notes_and as $note){
                $res = $note->synch();
                if(is_object($res)){
                    //unset($res->db);
                    $returns[]=$res;
                    $notes_and[]=$note;
                }
            }
        foreach($this->notes_db as $n_db){
            $isExest = 0;
            foreach($notes_and as $n_and){
                if($n_and->id_online == $n_db->id_online){
                    $isExest = 1;
                    break;
                }
            }
            if(!$isExest)
                $returns[] =$n_db;
        }
        return (!empty($returns)) ? json_encode($returns) :"[]";
    }








}




?>
