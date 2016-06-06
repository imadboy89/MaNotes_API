<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(-1);

session_start();
if(!isset($_SESSION["id"])) return -100;
if(!isset($_POST["action"]) || !isset($_SESSION["id"])) die("ERROR");


require_once("../config.php");
require_once("../class/class.db.php");
$db = new db("mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_NAME, DB_USERNAME, DB_PASSWORD);

$action = $_POST["action"];

if($action=="getNote") echo json_encode(getNoteById_online());
if($action=="deleteNote") echo json_encode(deleteNoteById_online());
if($action=="editNote") echo json_encode(editeNoteById_online());
if($action=="addNote") echo json_encode(addNote_online());
if($action=="shareNote") echo json_encode(shareNote());


function getNoteById_online(){
    global $db;
    if(!is_numeric($_POST["id_online"]))die("ERROR");
    $id_online = intval($_POST["id_online"]);
    $bind = array(
        ":id_online"=>"$id_online",
        ":id"=>"".intval($_SESSION["id"])
        );
    $res = $db->select("notes","id_online= :id_online and id_user = :id",$bind);
    if ($res) return $res[0];
    
}
function deleteNoteById_online(){
    global $db;
    if(!is_numeric($_POST["id_online"]))die("ERROR");
    $id_online = intval($_POST["id_online"]);
    $res = $db->delete("notes","id_online=".$id_online." and id_user = ".$_SESSION["id"]);
    if ($res) return $res;
    else return 0;
    
}
function editeNoteById_online(){
    global $db;
    if(!is_numeric($_POST["id_online"]))die("ERROR");
    $id_online = intval($_POST["id_online"]);
    $title = base64_encode($_POST["title"]);
    $note = base64_encode($_POST["note"]);
    $color = (isset($_POST["color"]))?$_POST["color"]:"blue";
    $infs = array(
            "title" => $title,
            "note"  => $note,
            "color" => $color
            );
    $bind = array(
        ":id_online"=>"$id_online",
        ":id"=>"".intval($_SESSION["id"])
        );
    $res = $db->update("notes",$infs,"id_online=:id_online and id_user = :id",$bind);
    if ($res) return $res;
    
}
function addNote_online(){
    global $db;
    //if(!is_numeric($_POST["id_online"]))die("ERROR");
    //$id_online = intval($_POST["id_online"]);
    $title = base64_encode($_POST["title"]);
    $note = base64_encode($_POST["note"]);
    $color = (isset($_POST["color"]))?$_POST["color"]:"blue";
    $infs = array(
            "title"        => $title,
            "note"         => $note,
            "id_user"      => intval($_SESSION["id"]),
            "color"        => $color,
            "date_added"   => date("Y-m-d H:i:s"),
            "date_updated" => date("Y-m-d H:i:s"),
            "cat"             =>0,
            );
    $res = $db->insert("notes",$infs);
    if ($res) return $res;
    else return 0;
}
function user_has_note($user_id,$note_id){
    global $db;
    $bind = array(
        ":id_online"=>"$note_id",
        ":id_user"=>"".intval($user_id)
        );
    $res = $db->select("notes","id_online= :id_online and id_user = :id_user",$bind);
    if ($res) return 1;
    else return 0;
}
function shareNote(){
    global $db;
    if(!is_numeric($_POST["id_online"]))die("ERROR");
    $id_online = intval($_POST["id_online"]);
    #$title = base64_encode($_POST["title"]);
    #$note = base64_encode($_POST["note"]);
    #$color = (isset($_POST["color"]))?$_POST["color"]:"blue";
    if (!user_has_note(intval($_SESSION["id"]),$id_online )) return 0;
    $infs = array(
        "id_online"=>"$id_online",
        "shared_for"=>((isset($_POST["shared_for"]))?intval($_POST["shared_for"]):0)
        );
    $res = $db->insert("shared_notes",$infs);
    if ($res) return $db->getLastId();
    else return 0;
}

?>
