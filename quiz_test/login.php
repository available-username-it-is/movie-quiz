<?php
session_start();
 
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/dark.css">
    <title>Log In</title>
</head>

<body>
    <main>
	<section>
		<h1>Log in</h1>
		
		<span>
		<?php echo $account_err;?>
		</span>
		
		<p>Please, input your data.</p>
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
			<input type="hidden" name="token" id="csrf_token" value="<?=$_SESSION['token']?>"> 
			<div>
				<label>Username</label>
				<input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>">
				<span><?php echo $username_err; ?></span>
			</div>    
			<div>
				<label>Password</label>
				<input type="password" name="password">
				<span><?php echo $password_err; ?></span>
			</div>
			<div>
				<button type="submit">Log in</button>
			</div>
			<p>Don't have an account? <a href="register.php">Create new account</a>.</p>
			<p><a href="index.php">Back</a></p>
		</form>
	</section>

	</main>
</body>
</html>