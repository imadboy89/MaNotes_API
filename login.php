<?php


//session_destroy();
//
echo Login();
function Login(){
    global $db;
    if(isset($_POST["act"]) and $_POST["act"]=="Logout" and isset($_SESSION["username"])){
        session_destroy();
        header("Refresh: 1;");
    }
    if(isset($_SESSION["username"])) return "already connected <form action='' method='POST'><input name='act' type='submit' value='Logout'/></form>";
    if(isset($_POST["username"]) and isset($_POST["password"])){
    $username= $_POST["username"];
    $password= $_POST["password"];
        $res = $db->select("users","userid='".$username."' and password = '".$password."'");
        if($res){
            $_SESSION["username"] = $username;
            $_SESSION["id"] = $res[0]["id"];
            header("Refresh: 1;");
        }else{
            echo "Incorrect username or password.";
        }
    }

echo '
<html>
<form action="" method="POST">
<input name="username" /></br>
<input name="password" /></br>
<input name="submit" type="submit" />
</form>
</html>';

   }
?>