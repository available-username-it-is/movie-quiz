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
    <link rel="stylesheet" href="styles/default-style.css">
    <link rel="stylesheet" href="styles/results-style.css">
    <link rel="icon" type="image/x-icon" href="/Images/favicon.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
</head>

<body>
    <?php include "includes/login_nav.php";?>

    <main>
        <section>
            <h2>Thank you for taking the quiz</h2>

            <h3>Your result is:</h3>
            <h3 class="result-value"><?php echo $score; ?></h3>
            <h3>Out of 8</h3>

            <div class="buttons-container">
                <a href="index.php"><button>Play again</button></a>
                <a href="leaderboard.php"><button>Leaderboard</button></a>
            </div>

            <div class="contact-container">
                <h4>Experienced  any problems?</h4>
                <div>
                    <span class="material-symbols-outlined">
                        mail
                    </span>
                    <p>Contact me in case you have a problem</p>
                </div>
            </div>
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