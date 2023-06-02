<?php
    session_start();
    $_SESSION["question_number"] = 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Quiz</title><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>

<body>
    <?php include "includes/login_nav.php";?>
    <h1>Start playing right now</h1>
    <form action="game.php" method="POST">
        <button>Play</button>
    </form>
</body>
</html>