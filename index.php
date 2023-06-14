<?php
    session_start();
    $_SESSION["question_number"] = 0;
    $_SESSION["past_soundtracks"] = array();
    $_SESSION["past_quotes"] = array();
    $active_tab = "index.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/default-style.css">
    <link rel="icon" type="image/x-icon" href="/Images/favicon.ico?v=2">
    <link rel="stylesheet" href="styles/lobby-style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <title>Movie Quiz</title>
</head>

<body>
    <?php include "includes/login_nav.php";?>
    
    <main>
        <section>
            <div class="main-container">
                <div class="left-column">
                    <h2>Basic Quiz</h2>
                    <div class="rule-container">
                        <span class="material-symbols-outlined">info</span>
                        <p>Rules are simple: 8 questions, 15 second for every question.
                        See your result after. Good luck!</p>
                    </div>
                    <div class="rule-container">
                        <span class="material-symbols-outlined">headphones</span>
                        <p>Quiz includes also audio questions. 
                        Use your headphones if you are in public place.</p>
                    </div>
                    <div class="play-container">
                        <form action="game.php" method="POST">
                            <button>Start Playing</button>
                        </form>
                        <div>
                            <span class="material-symbols-outlined">mail</span>
                            <p>Contact me in case you have a problem</p>
                        </div>
                    </div>
                </div>

                <div class="right-column">
                    <img src="Images/lobby_illustration.png" alt="">
                </div>
            </div>

            <div class="main-container">
                <div class="left-column">
                    <h2>Soundtrack Quiz</h2>
                    <div class="rule-container">
                        <span class="material-symbols-outlined">info</span>
                        <p>Rules are simple: 8 soundtracks to guess, 15 second for every question.
                        See your result after. Good luck!</p>
                    </div>
                    <div class="rule-container">
                        <span class="material-symbols-outlined">headphones</span>
                        <p>Quiz includes audio questions. 
                        Use your headphones if you are in public place.</p>
                    </div>
                    <div class="play-container">
                        <form action="game_soundtrack.php" method="POST">
                            <button>Start Playing</button>
                        </form>
                        <div>
                            <span class="material-symbols-outlined">mail</span>
                            <p>Contact me in case you have a problem</p>
                        </div>
                    </div>
                </div>

                <div class="right-column">
                    <img src="Images/soundtrack_question.png" alt="">
                </div>
            </div>

            <div class="main-container">
                <div class="left-column">
                    <h2>Audio Quotes Quiz</h2>
                    <div class="rule-container">
                        <span class="material-symbols-outlined">info</span>
                        <p>Rules are simple: 8 audio quotes to guess, 15 second for every question.
                        See your result after. Good luck!</p>
                    </div>
                    <div class="rule-container">
                        <span class="material-symbols-outlined">headphones</span>
                        <p>Quiz includes audio questions. 
                        Use your headphones if you are in public place.</p>
                    </div>
                    <div class="play-container">
                        <form action="game_audio_quote.php" method="POST">
                            <button>Start Playing</button>
                        </form>
                        <div>
                            <span class="material-symbols-outlined">mail</span>
                            <p>Contact me in case you have a problem</p>
                        </div>
                    </div>
                </div>

                <div class="right-column">
                    <img src="Images/audio_quote_question.png" alt="">
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