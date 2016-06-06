<?php


//session_destroy();
//
//d
echo Login();
function Login(){
    global $db;
    if(isset($_POST["act"]) and $_POST["act"]=="Logout" and isset($_SESSION["username"])){
        session_destroy();
        unset($_SESSION);
        return Login();
        //header("Refresh: 1;");
    }
    if(isset($_SESSION["username"])) return "Hello ".$_SESSION["username"]."! <form action='' method='POST'><input name='act' type='submit' value='Logout'/></form>";
    if(isset($_POST["username"]) and isset($_POST["password"])){
    $username= $_POST["username"];
    $password= $_POST["password"];
    $bind = array(
    ":username"=>"$username",
    ":password"=>"$password"
    );
    $res = $db->select("users","userid=:username and password = :password",$bind);
    if($res && $res[0]["userid"] == $username && $res[0]["password"] == $password ){
        $_SESSION["username"] = $username;
        $_SESSION["id"] = $res[0]["id"];
        return Login();
        //header("Refresh: 1;");
    }else{
        echo "Incorrect username or password.";
    }
    }

echo '
<html>
<form action="" method="POST">
<input name="username" type="text"/></br>
<input name="password" type="password"/></br>
<input name="submit" type="submit" />
</form>
</html>';

   }
?>