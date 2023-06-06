<?php
    require_once "connection.php";

    // $movie = "Whiplash";
    // $director = "Damien Chazelle";
    // $question = array(
    //     $movie, 
    //     "2014", 
    //     $director, 
    //     "movie_data/soundtrack/" . strtolower(str_replace(" ", "_", $movie)) . ".mp3", 
    //     "movie_data/movie_shots/" . strtolower(str_replace(" ", "_", $movie)) . ".webp", 
    //     "", 
    //     "movie_data/audio_quotes/" . strtolower(str_replace(" ", "_", $movie)) . ".mp3"
    // );

    // $sql = "INSERT INTO movies (name, release_date, director, soundtrack, movie_shot, text_quote, audio_quote)
    //         VALUES ('$question[0]', '$question[1]', '$question[2]', '$question[3]', '$question[4]', '$question[5]', '$question[6]')";
    // $connection->exec($sql);

    // $lastName = explode(" ", $director);
    // $image = "movie_data/directors/" . strtolower($lastName[1]) . ".webp";

    // $sql = "INSERT INTO directors (name, picture)
    //         VALUES ('$director', '$image')";
    // $connection->exec($sql);

    // $character = "Yoda";
    // $lastName = explode(" ", $character);
    // $picture = "movie_data/characters/" . strtolower($lastName[0]) . ".webp";

    // $sql = "INSERT INTO characters (name, picture)
    // VALUES ('$character', '$picture')";
    // $connection->exec($sql);

    $actor = "Joaquin Phoenix";
    $lastName = explode(" ", $actor);
    $picture = "movie_data/actors/" . strtolower($lastName[1]) . ".webp";

    $sql = "INSERT INTO actors (name, picture)
    VALUES ('$actor', '$picture')";
    $connection->exec($sql);
?>