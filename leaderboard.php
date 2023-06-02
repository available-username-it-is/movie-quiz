<?php
    session_start();
    require_once "connection.php";

    $sql = "SELECT name, correct_answers, wrong_answers, game_played, answers_accuracy FROM users
            ORDER BY answers_accuracy DESC
            LIMIT 100";
    $statement = $connection->query($sql);

    $table = "";
    $position = 1;
    while ($row = $statement->fetch()) {
        $tableRow = "<tr>
            <td><b>" . $position . "</b></td>
            <td><b>" . $row['name'] . "</b></td>
            <td>" . $row['correct_answers'] . "</td>
            <td>" . $row['wrong_answers'] . "</td>
            <td>" . $row['game_played'] . "</td>
            <td>" . $row['answers_accuracy'] . "%" . "</td>
            </tr>";
        $table .= $tableRow;
        $position++;
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>

<body>
    <?php include "includes/login_nav.php";?>

    <table>
        <thead>
            <tr>
                <th>Position</th>
                <th>Player</th>
                <th>Correct answers</th>
                <th>Wrong answers</th>
                <th>Game played</th>
                <th>Answers accuracy</th>
            </tr>
        </thead>

        <tbody>
            <?php echo $table; ?>
        </tbody>
    </table>
</body>
</html>