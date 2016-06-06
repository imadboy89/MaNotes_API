<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(-1);

session_start();
require_once("../config.php");
require_once("../class/class.db.php");
$db = new db("mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_NAME, DB_USERNAME, DB_PASSWORD,1);
$id_online = intval($_GET["id"]);
if($id_online==0 )die("err");
$notes = $db->run("select n.title,n.note,n.date_added,n.color from notes n ,shared_notes sh where n.id_online=sh.id_online and sh.id = ".$id_online);
if(!$notes)die("not exist");
$title = base64_decode ($notes[0]["title"]);
$note = base64_decode ($notes[0]["note"]);
?>

<head>

<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
<link href='http://fonts.googleapis.com/css?family=Reenie+Beanie&subset=latin' rel='stylesheet' type='text/css'>
<link href='Notes.css?v=2' rel='stylesheet' type='text/css'>
<link href='notifi.css' rel='stylesheet' type='text/css'>
</head>


<div id="note_show" class="note_module note_details <?php echo $notes[0]["color"]; ?>" >
<table id="note_body" >
    <tr><td id = "note_date_added"><?php echo $notes[0]["date_added"] ;?></td></tr>
    
    <tr><td id = "note_title"><?php echo $title; ?></td></tr>
    <tr><td id = "note_note"><div id="note_body_p" ><?php echo nl2br($note); ?></div></td></tr>
</table>

</div>
</div>