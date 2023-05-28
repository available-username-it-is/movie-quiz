<?php 
    if(!isset($_SESSION["user_id"]) || (time() - $_SESSION["last_activity"] > 1800)){
        $_SESSION = array();
        session_destroy();  

        header("location: login.php");
        exit;
    } else { 
        $_SESSION["last_activity"] = time();
    }
?>