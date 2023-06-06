<?php
    session_start();
    require_once "includes/auth_check.php";
    require_once "connection.php";
    $question_number = $_SESSION["question_number"];

    $questions = array(
        "Guess the movie from the picture",
        "What's the name of this character?",
        "Guess the actor from the picture",
        "Guess the movie by quote?(text)",
        "Guess the movie by quote?(audio)",
        "Guess the movie from its soundtrack",
        "Guess the director from the picture",
        "Guess the director of this movie"
    );

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

    function directorAnswers($connection, $question_type, $additional_data) {
        $sql = "SELECT * FROM `" . $question_type . "`";
        $statement = $connection->query($sql);
        $row = $statement->fetchall();
        $indexes = generateRandomIndexes(0, count($row));
        $answers = array($row[$indexes[0]]["name"],
                        $row[$indexes[1]]["name"],
                        $row[$indexes[2]]["name"],
                        $row[$indexes[3]]["name"]);
        $correct_answer = random_int(0, 3);
        $sql = "SELECT movie_shot FROM `movies` 
                WHERE director='$answers[$correct_answer]'
                ORDER BY RAND();";
        $statement = $connection->query($sql);
        $row = $statement->fetchall();

        array_push($answers, $row[0][$additional_data], $correct_answer);
        return $answers;
    }

    $movie_shots = makeAnswers($connection, "movies", "movie_shot");
    $characters = makeAnswers($connection, "characters", "picture");
    $actors = makeAnswers($connection, "actors", "picture");
    $text_quote = makeAnswers($connection, "movies", "text_quote");
    $audio_quote = makeAnswers($connection, "movies", "audio_quote");
    $soundtrack = makeAnswers($connection, "movies", "soundtrack");
    $director = makeAnswers($connection, "directors", "picture");
    $movie_director = directorAnswers($connection, "directors", "movie_shot");

    $all_answers = array($movie_shots, $characters, $actors, $text_quote, $audio_quote, $soundtrack, $director, $movie_director);
    $score = 0;

    if (isset($_POST["nextQuestion"]) && $question_number <= count($questions) - 1) {
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
                    <h1 class="question-text"><?php echo $questions[$question_number]; ?></h1>
                </div>

                <div class="question-visual"><?php 
                    if ($question_number == 3) {
                        echo "<p class='quote-question'>" . $all_answers[$question_number][4] . "</p>";
                    } elseif ($question_number == 4) {
                        echo "<img src='Images/audio_quote_question.png' class='question-image'>";
                        echo "
                        <audio autoplay>
                            <source src='" . $all_answers[$question_number][4] . "' type='audio/mp3'>
                        </audio>
                    ";
                    } elseif ($question_number == 5) {
                        echo "<img src='Images/soundtrack_question.png' class='question-image'>";
                        echo "
                        <audio autoplay>
                            <source src='" . $all_answers[$question_number][4] . "' type='audio/mp3'>
                        </audio>
                    ";
                    } else {
                        echo "<img src='". $all_answers[$question_number][4] ."' class='question-image'>";
                    }
                ?></div>
                
                <div class="answers-container">
                        <button type="button" class="answer-button column-1 row-1">
                            <?php echo $all_answers[$question_number][0]; ?>
                        </button>
                        <button type="button" class="answer-button column-2 row-1">
                            <?php echo $all_answers[$question_number][1]; ?>
                        </button>
                        <button type="button" class="answer-button column-1 row-2">
                            <?php echo $all_answers[$question_number][2]; ?>
                        </button>
                        <button type="button" class="answer-button column-2 row-2">
                            <?php echo $all_answers[$question_number][3]; ?>
                        </button>
                </div>
                
                <p class="question-number"><?php echo $_SESSION["question_number"] + 1 ?>/8</p>
                <input type="hidden" id="secret" value="<?php echo $all_answers[$question_number][5]; ?>">
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