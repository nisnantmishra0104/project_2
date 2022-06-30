<?php
//This script will handle login
session_start();

// check if the user is already logged in
if(isset($_SESSION['username']))
{
    header("location: home.php");
    exit;
}
require_once "connection.php";

$username = $password = "";
$err = "";

// if request method is post
if ($_SERVER['REQUEST_METHOD'] == "POST"){
    if(empty(trim($_POST['username'])) || empty(trim($_POST['password'])))
    {
        $err = "Please enter username + password";
    }
    else{
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
    }


if(empty($err))
{
    $sql = "SELECT id, username, password FROM retailers WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $param_username);
    $param_username = $username;
    
    
    // Try to execute this statement
    if(mysqli_stmt_execute($stmt)){
        mysqli_stmt_store_result($stmt);
        if(mysqli_stmt_num_rows($stmt) == 1)
                {
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt))
                    {
                        if(password_verify($password, $hashed_password))
                        {
                            // this means the password is corrct. Allow user to login
                            session_start();
                            $_SESSION["username"] = $username;
                            $_SESSION["id"] = $id;
                            $_SESSION["loggedin"] = true;

                            //Redirect user to home page
                            header("location: home.php");
                            
                        }
                    }

                }

    }
}    


}


?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>YourStore</title>
    <link rel="stylesheet" href="index.css" />
    <link
      rel="stylesheet"
      media="screen and (max-width: 1170px)"
      href="css/phone.css"
    />
    <link
      href="https://fonts.googleapis.com/css?family=Baloo+Bhai|Bree+Serif&display=swap"
      rel="stylesheet"
    />
  </head>

  <body>
    <nav id="navbar">
      <div id="logo">
        <img src="logo.jpg" alt="YourStore.com" />
      </div>
    </nav>
    <section id="login">
      <h1 class="h-primary center">LOGIN</h1>
      <form action = "" method = "POST">
      <div class="loginpage">
        <div id="userid" class="input">
          <label for="UserID">username </label>
          <input type="text" id="UserID" placeholder="Enter Your UserID" name="username"/>
        </div>
        <div id="password" class="input">
          <label for="Password">Password: </label>
          <input
            type="password"
            id="Password"
            placeholder="Enter Your Password"
            name="password"
          />
        </div>
        <div class="input">
          <label>
            <input type="checkbox" /><nbsp></nbsp><nbsp></nbsp
            ><nbsp></nbsp> Remember me
          </label>
        </div>
        <a>
          <button class="input btn" id="signin">
            <a href="/home.html">Sign in</a>
          </button>
        </a>
        <div id="signup">
          <p>Don't have an Account? <a href="signup.php">Sign up</a></p>
        </div>
      </div>
      </form>
    </section>
  </body>
</html>
