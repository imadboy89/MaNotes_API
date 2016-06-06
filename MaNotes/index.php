<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(-1);

session_start();
require_once("../config.php");
require_once("../class/class.db.php");
$db = new db("mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_NAME, DB_USERNAME, DB_PASSWORD,1);

?>
<html>

<?php


  
require_once("notes.php");
  
  
?>

<div class="msg_info message">
 <h3></h3>
</div>

<div class="msg_error message">
 <h3></h3>
</div>

<div class="msg_warning message">
 <h3></h3>
</div>

<div class="msg_success message">
 <h3></h3>
</div>
<script type="text/JavaScript" src="http://code.jquery.com/jquery-1.11.0.min.js"></script> 
<script type="text/JavaScript" src="Functions.js?version=2"></script> 
<script>
//$( document ).ready(function() {
    msg = "<?php echo (isset($_GET["msg_to_show"]))?addslashes($_GET["msg_to_show"]):""; ?>";
    if (msg!=""){
        showMsg(msg,"info");
    }
//}
</script>
</html>