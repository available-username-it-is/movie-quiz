<?php
    session_start();
    require_once "includes/auth_check.php";
    require_once "connection.php";
    $question_number = $_SESSION["question_number"];

    $question = "Guess the movie from its soundtrack";

    function generateRandomIndexes($smallest, $largest) {
        $random_id_array_all = range($smallest, $largest - 1);
        shuffle($random_id_array_all);
        $random_id_array = array_slice($random_id_array_all, 0, 4);
        return $random_id_array;
    }

    function makeAnswers($connection, $question_type, $additional_data) {
        $sql = "SELECT * FROM `" . $question_type . "`";
        $statement = $connection->query($sql);
        $row = $statement->fetchall();
        $indexes = generateRandomIndexes(0, count($row));
        $answers = array($row[$indexes[0]]["name"],
                        $row[$indexes[1]]["name"],
                        $row[$indexes[2]]["name"],
                        $row[$indexes[3]]["name"]);
        $correct_answer = random_int(0, 3);
        $correct_answer_id = $row[$indexes[$correct_answer]]["id"] - 1;
        array_push($answers, $row[$correct_answer_id][$additional_data], $correct_answer);
        return $answers;
    }

    $soundtrack = makeAnswers($connection, "movies", "soundtrack");
    if (in_array($soundtrack[4], $_SESSION["past_soundtracks"])) {
        while (in_array($soundtrack[4], $_SESSION["past_soundtracks"])) {
            $soundtrack = makeAnswers($connection, "movies", "soundtrack");
        }
    }
    array_push($_SESSION["past_soundtracks"], $soundtrack[4]);
    $score = 0;

    if (isset($_POST["nextQuestion"]) && $question_number <= 7) {
        $score = htmlspecialchars($_POST["score"]);
        $_SESSION["quiz_score"] = $score;
        if ($question_number == 7) {        
            $id = $_SESSION["user_id"];
            $score = $_SESSION["quiz_score"];
            
            $sql = "SELECT correct_answers, wrong_answers, game_played, answers_accuracy FROM users
                    WHERE id = ?";
            $statement = $connection->prepare($sql);
            $statement->execute([$id]);
            $row = $statement->fetchAll();
            
            $correct_answers = $row[0]["correct_answers"];
            $wrong_answers = $row[0]["wrong_answers"];
            $game_played = $row[0]["game_played"];
            $answers_accuracy = $row[0]["answers_accuracy"];
            
            $correct_answers += $score;
            $wrong_answers += 8 - $score;
            $game_played += 1;
            $answers_accuracy = round($correct_answers / ($correct_answers + $wrong_answers), 2) * 100;
        
            $sql = "UPDATE users SET correct_answers = ?, wrong_answers = ?, game_played = ?, answers_accuracy = ?
                    WHERE id = ?";
            $statement = $connection->prepare($sql);
            $statement->execute([$correct_answers, $wrong_answers, $game_played, $answers_accuracy, $id]);
            header("location: results.php");
        }

        $question_number += 1;
        $_SESSION["question_number"] = $question_number;
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles/game-style.css" />
    <link rel="icon" type="image/x-icon" href="/Images/favicon.png">
    <link rel="stylesheet" href="styles/default-style.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
</head>

<body>
    <?php include "includes/login_nav.php";?>
    <main>
        <section>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="line-container">
                    <div class="controls-container">
                        <p>Score: <input type="text" id="score" name="score" value="<?php echo $score ?>" readonly></p>
                        <div class="timer-container">
                            <span class="material-symbols-outlined">
                                timer
                            </span>
                            <span id="timeLeft"></span>
                        </div>
                        <button type="submit" name="nextQuestion" id="nextQuestionButton" disabled>Next</button>
                    </div>
                </div>

                <div class="question-container">
                    <h1 class="question-text"><?php echo $question; ?></h1>
                </div>

                <div class="question-visual"><?php 
                        echo "<img src='Images/soundtrack_question.png' class='question-image'>";
                        echo "
                        <audio autoplay>
                            <source src='" . $soundtrack[4] . "' type='audio/mp3'>
                        </audio>
                    ";
                ?></div>
                
                <div class="answers-container">
                        <button type="button" class="answer-button column-1 row-1">
                            <?php echo $soundtrack[0]; ?>
                        </button>
                        <button type="button" class="answer-button column-2 row-1">
                            <?php echo $soundtrack[1]; ?>
                        </button>
                        <button type="button" class="answer-button column-1 row-2">
                            <?php echo $soundtrack[2]; ?>
                        </button>
                        <button type="button" class="answer-button column-2 row-2">
                            <?php echo $soundtrack[3]; ?>
                        </button>
                </div>
                
                <p class="question-number"><?php echo $_SESSION["question_number"] + 1 ?>/8</p>
                <input type="hidden" id="secret" value="<?php echo $soundtrack[5]; ?>">
            </form>  
        </section>
    </main>
    
    <footer>
        <address>
            <hr>
            <h4>Contact</h4>
            <p>myemail@gmail.com</p>
        </address>
    </footer>
    <script src="scripts/script.js"></script>
</body>
</html>