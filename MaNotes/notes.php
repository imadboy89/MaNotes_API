<head>

<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
<link href='http://fonts.googleapis.com/css?family=Reenie+Beanie&subset=latin' rel='stylesheet' type='text/css'>
<link href='Notes.css?v=2' rel='stylesheet' type='text/css'>
<link href='notifi.css' rel='stylesheet' type='text/css'>
</head>
<?php 
if(isset($_POST["act_note"])){
    
}
  require_once("login.php");
if(!isset($_SESSION["id"]))return;


$notes = $db->select("notes","id_user=".intval($_SESSION["id"]),"","*","order by date_added");
$sared_notes__ = $db->query("select sh.id,sh.id_online from shared_notes sh,notes n where sh.id_online=n.id_online and n.id_user = ".intval($_SESSION["id"]));
$notes_html = "";
$count_notes = count($notes);
$shared_notes = array();
foreach($sared_notes__ as $sh_n){
    $shared_notes[$sh_n["id_online"]]=$sh_n["id"];
}

foreach($notes as $note){
    $title = base64_decode($note["title"]);
    $id = $note["id_online"];
    $share = (isset($shared_notes[$id]))?"<a href='shared_note.php?id=$shared_notes[$id]'  target='_blank' >Shared</a>":"<button>Share</button>";
    $btns = "<div class='note_date'><b>Create on </b>: ".$note["date_added"]."<div class='share'>$share</div></div>";//"<ul class=\"btns\"><li class=\"editeNote\"><a href=\"#\">.</a></li><li>".$note["date_added"]."</li> <li class=\"deleteNote\"><a href=\"#\" >x</a></li></ul>";
    $notes_html .= "<li class=\"".$note["color"]." Note\" id=\"$id\">$btns<p class=\"Note\">".$title."</p></li>\n";
}

?>

<div id="btn_add_div">
<button  id="add_note" class="btn btn-add">
</div>
<!--
<a  id="edit_note">edit note</a>
--> 
<div>
Total notes : <?php echo $count_notes;?>
</div>
<ul id="notes">

<?php echo $notes_html; ?> 
</ul>


<div id="note_show" class="note_module note_details"  hidden>
<table id="note_body" >
    <tr><td id = "note_date_added"></td></tr>
    
    <tr><td id = "note_title"></td></tr>
    <tr><td id = "note_note"><div id="note_body_p" ></div></td></tr>
</table>

<div class="control_btns" data="0">
<button id="show_back" class="btn btn-back" />
<button id="show_save" class="btn btn-save" disabled />
<button id="show_colors" class="btn-colors colors_blue" disabled />
<button id="show_delete" class="btn btn-delete" />
<button id="show_edit" class="btn btn-edit" />

</div>
</div>


<div id="addEditNote" class="note_module note_details" hidden>
<h2><b><center>New Note</center></b></h2>
<input id="ae_title" name="ae_title" placeholder="title" />
<textarea id="ae_note" name="ae_note" placeholder="note" ></textarea>

<div class="control_btns" data="0">
<button id="save_back" class="btn btn-back" />
<button id="save_save" class="btn btn-save" />
<button id="save_colors" class="btn-colors colors_blue" />
<button id="save_delete" class="btn btn-delete" />
<button id="save_edit" class="btn btn-edit" />

</div>
</div>