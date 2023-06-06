<?php
    session_start();
    require_once "connection.php";
    
    $active_tab = "leaderboard.php";
    $sql = "SELECT name, correct_answers, wrong_answers, game_played, answers_accuracy FROM users
            ORDER BY answers_accuracy DESC
            LIMIT 100";
    $statement = $connection->query($sql);

    $table = "";
    $position = 1;
    $rank = 1;
    while ($row = $statement->fetch()) {
        if ($rank > 3) {
            $rank = "";
        } else {
            $odd_rank = "odd-" . $rank;
            $even_rank = "even-" . $rank;
            $rank += 1;
        }
        $tableRow = "<tr>
            <td class='$odd_rank'><b>" . $position . "</b></td>
            <td class='$even_rank'><b>" . $row['name'] . "</b></td>
            <td class='$odd_rank'>" . $row['correct_answers'] . "</td>
            <td class='$even_rank'>" . $row['wrong_answers'] . "</td>
            <td class='$odd_rank'>" . $row['game_played'] . "</td>
            <td class='$even_rank'>" . $row['answers_accuracy'] . "%" . "</td>
            </tr>";

        $odd_rank = "";
        $even_rank = "";
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
    <link rel="stylesheet" href="styles/default-style.css">
    <link rel="stylesheet" href="styles/leaderboard-style.css">
</head>

<body>
    <?php include "includes/login_nav.php";?>

    <main>
        <section>   
            <h1>LEADERBOARD</h1>
            <table>
                <thead>
                    <tr>
                        <th>Ranking</th>
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
        </section>
    </main>

    <footer>
        <address>
            <hr>
            <h4>Contact</h4>
            <p>myemail@gmail.com</p>
        </address>
    </footer>
</body>
</html>