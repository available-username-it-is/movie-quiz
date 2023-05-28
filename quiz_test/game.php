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
        $answers = array($row[$indexes[0]]["director"],
                        $row[$indexes[1]]["director"],
                        $row[$indexes[2]]["director"],
                        $row[$indexes[3]]["director"]);
        $correct_answer = random_int(0, 3);
        $correct_answer_id = $row[$indexes[$correct_answer]]["id"] - 1;
        array_push($answers, $row[$correct_answer_id][$additional_data], $correct_answer);
        return $answers;
    }

    $movie_shots = makeAnswers($connection, "movies", "movie_shot");
    $characters = makeAnswers($connection, "characters", "picture");
    $actors = makeAnswers($connection, "actors", "picture");
    $text_quote = makeAnswers($connection, "movies", "text_quote");
    $audio_quote = makeAnswers($connection, "movies", "audio_quote");
    $soundtrack = makeAnswers($connection, "movies", "soundtrack");
    $director = makeAnswers($connection, "directors", "picture");
    $movie_director = directorAnswers($connection, "movies", "movie_shot");

    $all_answers = array($movie_shots, $characters, $actors, $text_quote, $audio_quote, $soundtrack, $director, $movie_director);
    $score = 0;

    if (isset($_POST["nextQuestion"]) && $question_number <= count($questions) - 1) {
        $score = htmlspecialchars($_POST["score"]);
        $_SESSION["quiz_score"] = $score;
        if ($question_number == 7) {        
            $id = $_SESSION["user_id"];
            $score = $_SESSION["quiz_score"];
            
            $sql = "SELECT correct_answers, wrong_answers, game_played FROM users
                    WHERE id = ?";
            $statement = $connection->prepare($sql);
            $statement->execute([$id]);
            $row = $statement->fetchAll();
            
            $correct_answers = $row[0]["correct_answers"];
            $wrong_answers = $row[0]["wrong_answers"];
            $game_played = $row[0]["game_played"];
            
            $correct_answers += $score;
            $wrong_answers += 8 - $score;
            $game_played += 1;
        
            $sql = "UPDATE users SET correct_answers = ?, wrong_answers = ?, game_played = ?
                    WHERE id = ?";
            $statement = $connection->prepare($sql);
            $statement->execute([$correct_answers, $wrong_answers, $game_played, $id]);
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
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>

<body>
    <h1>Question Number: <?php echo $question_number; ?></h1>
    <h1><?php echo $questions[$question_number]; ?></h1>
    <p>Time left: <span id="timeLeft"></span></p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <div><?php echo $all_answers[$question_number][4]; ?></div>
        <p>Your score: <input type="text" id="score" name="score" value="<?php echo $score ?>" readonly></p>
        <div>
            <button type="button" class="answer-button">
                <?php echo $all_answers[$question_number][0]; ?>
            </button>
            <button type="button" class="answer-button">
                <?php echo $all_answers[$question_number][1]; ?>
            </button>
            <button type="button" class="answer-button">
                <?php echo $all_answers[$question_number][2]; ?>
            </button>
            <button type="button" class="answer-button">
                <?php echo $all_answers[$question_number][3]; ?>
            </button>
        </div>
        <button type="submit" name="nextQuestion" id="nextQuestionButton" disabled>Next</button><br>
        <a href="index.php"><button>Home</button></a>
        <input type="hidden" id="secret" value="<?php echo $all_answers[$question_number][5]; ?>">
    </form>  
    
    <script src="scripts/script.js"></script>
</body>
</html>