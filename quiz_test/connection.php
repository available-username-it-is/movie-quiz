<?php 
ini_set('display_errors', 1);
ini_set("error_reporting", E_ALL);

define("HOST", "localhost");
define("USERNAME", "root"); 
define("PASSWORD", "");
define("DBNAME",  "movie_quiz");
define("DSN",   "mysql:host=". HOST .";dbname=". DBNAME .";charset=UTF8");

try {
	$connection = new PDO(DSN, USERNAME, PASSWORD);
	$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $error) {
	die("I've got a bad feeling about this" . $error->getMessage());
}

?>