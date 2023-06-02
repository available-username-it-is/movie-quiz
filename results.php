<?php
    session_start();
    require_once "connection.php";

    $score = $_SESSION["quiz_score"];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Results</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>

<body>
    <h1>Congratulations!</h1>
    <h2>Your score is: <?php echo $score . "/8"; ?></h2>
    <a href="index.php"><button>Home</button></a>
</body>
</html>