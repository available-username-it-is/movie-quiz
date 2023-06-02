<?php 
if (!isset($_SESSION['token']) || !isset($_POST["token"]) || $_SESSION['token'] != $_POST['token']) {
    exit("CSRF-token error.");
}
?>