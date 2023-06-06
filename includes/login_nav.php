<header>
    <div class="header-container">
      <div class="logo-container">
        <a href="index.html"><img src="Images/Logo_2_0.png"></a>
        <a href="index.html"><p>MOVIE QUIZ</p></a>
      </div>

      <nav>
        <a href="index.html" class="<?php echo ($active_tab == "index.html") ? "active-tab" : ""; ?>">Home</a>
        <a href="index.php" class="<?php echo ($active_tab == "index.php") ? "active-tab" : ""; ?>">Quizzes</a>
        <a href="leaderboard.php" class="<?php echo ($active_tab == "leaderboard.html") ? "active-tab" : ""; ?>">Leaderboard</a>
        <?php 
            if (isset($_SESSION["user_id"])) {
                echo "<a href='logout.php'>Log out </a>";
            } else {
                if ($active_tab == "login.php") {
                  echo '<a href="login.php" class="active-tab">Log In </a>';
                } else {
                  echo '<a href="login.php">Log In </a>';
                }
            }
        ?>
      </nav>
    </div>  
  </header>