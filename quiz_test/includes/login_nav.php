<header>
    <nav>
        <?php 
            if (isset($_SESSION["user_id"])) {
                echo "<a href='logout.php'>Log out</a>";
            } else {
                echo '<a href="login.php">Log in</a>';
                echo '<a href="register.php">Register</a>';
            }
        ?>
    </nav>
</header>