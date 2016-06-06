<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(-1);

$full_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$f = fopen("Manote.log","a");
fwrite($f,$full_url."__\n");
fclose($f);


if(!isset($_GET["username"]) || !isset($_GET["password"]) || $_GET["password"]=="" || $_GET["username"]==""){
$f = fopen("Manote.log","a");
fwrite($f,"no pass or username provided \n\n");
fclose($f);
    die("{'error':'not password or username given !'}");
    
}

require_once("notes_manager.php");

$mysql_cnx = mysql_connect(DB_HOST, DB_USERNAME, DB_PASSWORD);
$userid=mysql_real_escape_string($_GET["username"]);
$pass=mysql_real_escape_string($_GET["password"]);
mysql_close($mysql_cnx);
/*
if(isset($_GET["notes"])){
	$f = fopen("note","a");
	fwrite($f, $_GET["notes"]."\n");
	fclose($f);
}
*/
$mng = new manotes_mng($userid,$pass,"get");
//print_r($mng->getNotes());

//echo "\n<br/>____________________<br>\n";
$output = $mng->synch();
echo $output;
$f = fopen("Manote.log","a");
fwrite($f,$output."\n\n");
fclose($f);
//$mng->synch();
//echo "<pre>";
//print_r(json_decode($results_notes));
//echo "</pre>";
//$str = "I'm\"aô";
//echo base64_encode($str);
?>
