<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(-1);

require_once("config.php");
require_once("class/class.db.php");
$db = new db("mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_NAME, DB_USERNAME, DB_PASSWORD);
if(isset($_POST["submit"]) && isset($_POST["version"]) && isset($_POST["version_name"]) && isset($_POST["description"]) ){
    //echo $_POST["url"];
    $version = $_POST["version"];
    $version_name = $_POST["version_name"];
    $description = $_POST["description"];
    $uploaddir = 'download/';
    $uploadfile = $uploaddir . basename($_FILES['apkFile']['name']);
    if(isset($_FILES['apkFile']) && isset($_FILES['apkFile']['tmp_name']) && $_FILES['apkFile']['tmp_name']!=""){
        $apkFile = "http://manotesapi.coding-labs.com/".$uploadfile;
        if ( !move_uploaded_file($_FILES['apkFile']['tmp_name'], $uploadfile))
            die("error .");
    }else if (isset($_POST["url"])){
        $apkFile = $_POST["url"];
    }
    
    $fields = array(
                "version" => $version,
                "version_name" => $version_name,
                "url" => $apkFile,
                "description" => $description
                );
    $db->replace("releases",$fields);
    if($db){
        echo "<b style='color:green'>The update added !</b>";
    }else{
        echo "<b style='color:red'>The update didn't added !</b>";
    }
}
$res = $db->run("select id,max( version ) AS version, `version_name` , `date` , `description` ,`url` from releases group by 1 order by 2 desc");
$last_version = array();
if($res){
    $last_version = $res[0];
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
    "http://www.w3.org/TR/html4/strict.dtd"
    >
<html lang="en">
<head>
    <title>Register form with HTML5 using placeholder and CSS3</title>
</head>
<style type="text/css">
    #wrapper {
        width:450px;
        margin:0 auto;
        font-family:Verdana, sans-serif;
    }
    legend {
        color:#0481b1;
        font-size:16px;
        padding:0 10px;
        background:#fff;
        -moz-border-radius:4px;
        box-shadow: 0 1px 5px rgba(4, 129, 177, 0.5);
        padding:5px 10px;
        text-transform:uppercase;
        font-family:Helvetica, sans-serif;
        font-weight:bold;
    }
    fieldset {
        border-radius:4px;
        background: #fff; 
        background: -moz-linear-gradient(#fff, #f9fdff);
        background: -o-linear-gradient(#fff, #f9fdff);
        background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#fff), to(#f9fdff)); /
        background: -webkit-linear-gradient(#fff, #f9fdff);
        padding:20px;
        border-color:rgba(4, 129, 177, 0.4);
    }
    input,
    textarea {
        color: #003040;
        background: #fff;
        border: 1px solid #CCCCCC;
        font-size: 14px;
        line-height: 1.2em;
        margin-bottom:15px;

        -moz-border-radius:4px;
        -webkit-border-radius:4px;
        border-radius:4px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1) inset, 0 1px 0 rgba(255, 255, 255, 0.2);
    }
    input[type="text"],
    input[type="password"]{
        padding: 8px 6px;
        height: 22px;
        width:280px;
    }
    input[type="text"]:focus,
    input[type="password"]:focus {
        background:#f5fcfe;
        text-indent: 0;
        z-index: 1;
        color: #373737;
        -webkit-transition-duration: 400ms;
        -webkit-transition-property: width, background;
        -webkit-transition-timing-function: ease;
        -moz-transition-duration: 400ms;
        -moz-transition-property: width, background;
        -moz-transition-timing-function: ease;
        -o-transition-duration: 400ms;
        -o-transition-property: width, background;
        -o-transition-timing-function: ease;
        width: 380px;
        
        border-color:#ccc;
        box-shadow:0 0 5px rgba(4, 129, 177, 0.5);
        opacity:0.6;
    }
    input[type="submit"]{
        background: #222;
        border: none;
        text-shadow: 0 -1px 0 rgba(0,0,0,0.3);
        text-transform:uppercase;
        color: #eee;
        cursor: pointer;
        font-size: 15px;
        margin: 5px 0;
        padding: 5px 22px;
        -moz-border-radius: 4px;
        border-radius: 4px;
        -webkit-border-radius:4px;
        -webkit-box-shadow: 0px 1px 2px rgba(0,0,0,0.3);
        -moz-box-shadow: 0px 1px 2px rgba(0,0,0,0.3);
        box-shadow: 0px 1px 2px rgba(0,0,0,0.3);
    }
    textarea {
        padding:3px;
        width:96%;
        height:100px;
    }
    textarea:focus {
        background:#ebf8fd;
        text-indent: 0;
        z-index: 1;
        color: #373737;
        opacity:0.6;
        box-shadow:0 0 5px rgba(4, 129, 177, 0.5);
        border-color:#ccc;
    }
    .small {
        line-height:14px;
        font-size:12px;
        color:#999898;
        margin-bottom:3px;
    }
</style>
<body>
    <div id="wrapper">
        <form action="" method="POST" enctype="multipart/form-data">
            <fieldset>
                <legend>Register an update :</legend>
                <div>
                    <input type="text" name="version" placeholder="1.2" value="<?php echo (isset($last_version["version"]))?$last_version["version"]:""; ?>"/>
                </div>
                <div>
                    <input type="text" name="version_name" placeholder="Beta" value="<?php echo (isset($last_version["version_name"]))?$last_version["version_name"]:""; ?>"/>
                </div>
                <div>
                    <input disabled type="text" name="url" placeholder="http://MaNotesAPI.com" value="<?php echo (isset($last_version["url"]))?$last_version["url"]:""; ?>"/>
                    <input hidden type="text" name="url" placeholder="http://MaNotesAPI.com" value="<?php echo (isset($last_version["url"]))?$last_version["url"]:""; ?>"/>
                </div>
                <div>
                    <input name="apkFile" type="file" accept=".apk" />
                </div>
                <div>
                    <div class="small">description</div>
                    <textarea name="description" placeholder="description"><?php echo (isset($last_version["description"]))?$last_version["description"]:""; ?></textarea>
                </div>    
                <input type="submit" name="submit" value="Send"/>
            </fieldset>    
        </form>
    </div>
</body>
</html>
