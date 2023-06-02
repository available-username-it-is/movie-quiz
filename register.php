<?php 
    require_once "connection.php";
    session_start();

    $username = $password = $confirm_password = "";
    $username_err = $password_err = $confirm_password_err = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        require_once "includes/csrf_check.php";

        if (empty(trim($_POST["username"]))) {
            $username_err = "Please, enter the username.";
        } else {
            $sql = "SELECT id FROM users WHERE name = :username";
            
            $statement = $connection->prepare($sql);
            $param_username = trim($_POST["username"]);
            
            $statement->bindParam(":username", $param_username, PDO::PARAM_STR);
            
            if($statement->execute()){
                if($statement->rowCount() == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "I have a bad feeling about this.";
            }

            unset($statement);
        }

        if(empty(trim($_POST["password"]))){
            $password_err = "Input your password.";     
        } elseif(strlen(trim($_POST["password"])) < 6){
            $password_err = "Password's length must be 6 characters minimum.";
        } else{
            $password = trim($_POST["password"]);
        }

        if(empty(trim($_POST["confirm_password"]))){
            $confirm_password_err = "Confirm your password.";     
        } else{
            $confirm_password = trim($_POST["confirm_password"]);
            if(empty($password_err) && ($password != $confirm_password)){
                $confirm_password_err = "Passwords don't match.";
            }
        }

        if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
            $sql = "INSERT INTO users (name, password) VALUES (:username, :password)";
             
            if($statement = $connection->prepare($sql)){
                $param_username = $username;

                $param_password = password_hash($password, PASSWORD_DEFAULT);
    
                $statement->bindParam(":username", $param_username, PDO::PARAM_STR);
                $statement->bindParam(":password", $param_password, PDO::PARAM_STR);
                
                if($statement->execute()){
                    header("location: login.php");
                    exit;
                } else{
                    echo "Not a happy landing.";
                }
            }

            unset($statement);
        }
    }

    require_once "includes/csrf_token.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/dark.css">
</head>

<body>
    <main>
        <section>
            <h1>Create new account</h1>
            <p>Please, input required information</p>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input type="hidden" name="token" id="csrf_token" value="<?=$_SESSION['token']?>">
                <div>
                    <label>Username</label>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>">
                    <span><?php echo $username_err; ?></span>
			    </div> 
                <div>
                    <label>Password</label>
                    <input type="password" name="password" value="<?php echo htmlspecialchars($password); ?>">
                    <span><?php echo $password_err; ?></span>
			    </div>
                <div>
                    <label>Confirm your password</label>
                    <input type="password" name="confirm_password" value="<?php htmlspecialchars($confirm_password); ?>">
                    <span><?php echo $confirm_password_err; ?></span>
			    </div>
                <div>
				    <input type="submit" value="Register">
                </div>
                <p>Already have an account? <a href="login.php">Sign in here</a>.</p>
                <p><a href="index.php">Back</a></p>
            </form>
        </section>
    </main>
</body>
</html>