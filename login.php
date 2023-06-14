<?php
session_start();

$active_tab = "login.php";
if(isset($_SESSION["user_id"]) && (time() - $_SESSION["last_activity"] < 1800)){
    header("location: index.php");
    exit;
}

require_once "connection.php";

$username = $password = "";
$account_err = "";
$username_err = $password_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    require_once "includes/csrf_check.php";

    if(empty(trim($_POST["username"]))){
        $username_err = "Input the username.";
    } else{
        $username = trim($_POST["username"]);
    }

    if(empty(trim($_POST["password"]))){
        $password_err = "Input the password.";
    } else{
        $password = trim($_POST["password"]);
    }

    if(empty($username_err) && empty($password_err)){
        $sql = "SELECT id, name, password FROM users WHERE name = :username LIMIT 1";
        
        if($statement = $connection->prepare($sql)){
            $param_username = trim($_POST["username"]);

            $statement->bindParam(":username", $param_username, PDO::PARAM_STR);       
            
            if($statement->execute()){
                if($statement->rowCount() == 1){
                    if($row = $statement->fetch()){
						
                        $id = $row["id"];
                        $username = $row["name"];
                        $hashed_password = $row["password"];

                        if(password_verify($password, $hashed_password)){
                            $_SESSION["user_id"] = $id;
                            $_SESSION["user"] = $username;
                            $_SESSION["last_activity"] = time();

                            header("location: index.php");
							exit;
                        } else{
                            $account_err = "Something is wrong about your password or username.";
                        }
                    }
                } else{
                    $account_err = "Something is wrong about your password or username.";
                }
            } else{
                echo "Not a happy landing.";
            }
        }
        unset($statement);
    }
    
    unset($connection);
}

require_once "includes/csrf_token.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/default-style.css">
    <link rel="stylesheet" href="styles/login-style.css">
    <link rel="icon" type="image/x-icon" href="/Images/favicon.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <title>Log In</title>
</head>

<body>
    <?php include "includes/login_nav.php";?>

    <main>
	<section>
        <div class="login-container">
            <h1>Log in</h1>
            
            <span>
            <?php echo $account_err;?>
            </span>
            
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input type="hidden" name="token" id="csrf_token" value="<?=$_SESSION['token']?>"> 
                <div class="input-container">
                    <label>Username</label>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>">
                    <span><?php echo $username_err; ?></span>
                </div>    
                <div class="input-container">
                    <label>Password</label>
                    <div class="password-container">
                        <input type="password" name="password" id="password">
                        <span class="material-symbols-outlined" id="password-toggler">
                            visibility
                        </span>
                    </div>
                    <span><?php echo $password_err; ?></span>
                </div>
                <div class="button-container">
                    <button type="submit">Log in</button>
                </div>
                <p>Don't have an account?</p>
                <a href="register.php" class="register-link">Create new account</a>
            </form>
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

    <script src="scripts/password.js"></script>
</body>
</html>