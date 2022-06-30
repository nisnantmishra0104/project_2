<?php
require_once "connection.php";

$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
if ($_SERVER['REQUEST_METHOD'] == "POST"){

    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Username cannot be blank";
    }
    else{
        $sql = "SELECT id FROM retailers WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if($stmt)
        {
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set the value of param username
            $param_username = trim($_POST['username']);

            // Try to execute this statement
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1)
                {
                    $username_err = "This username is already taken"; 
                }
                else{
                    $username = trim($_POST['username']);
                }
            }
            else{
                echo "Something went wrong";
            }
        }
    }

    mysqli_stmt_close($stmt);


// Check for password
if(empty(trim($_POST['password']))){
    $password_err = "Password cannot be blank";
}
elseif(strlen(trim($_POST['password'])) < 5){
    $password_err = "Password cannot be less than 5 characters";
}
else{
    $password = trim($_POST['password']);
}

// Check for confirm password field
if(trim($_POST['password']) !=  trim($_POST['confirm_password'])){
    $password_err = "Passwords should match";
}


// If there were no errors, go ahead and insert into the database
if(empty($username_err) && empty($password_err) && empty($confirm_password_err))
{
    $sql = "INSERT INTO retailers (username, password) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt)
    {
        mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);

        // Set these parameters
        $param_username = $username;
        $param_password = password_hash($password, PASSWORD_DEFAULT);

        // Try to execute the query
        if (mysqli_stmt_execute($stmt))
        {
            header("location: login.php");
        }
        else{
            echo "Something went wrong... cannot redirect!";
        }
    }
    mysqli_stmt_close($stmt);
}
mysqli_close($conn);
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
      <ul>
        <li class="item"><a href="index.html">Home</a></li>
        <li class="item"><a href="notebook.html">NOTEBOOKS</a></li>
        <li class="item"><a href="pens.html">PENS & PENCILS</a></li>
        <li class="item"><a href="instruments.html">INSTRUMENTS</a></li>
        <li class="item"><a href="tapes.html">TAPES AND GUMS</a></li>
      </ul>
    </nav>
    <section id="login">
      <h1 class="h-primary center">SIGNUP</h1>
      <div class="loginpage">
      <form action="" method="post">
        <div id="userid" class="input">
          <label for="UserID">Username </label>
          <input type="text" id="UserID" name="username" placeholder="Enter Your UserID" />
        </div>
        <div id="address" class="input">
          <label for="address">Address</label>
          <input type="text" id="address"  placeholder="Enter Your Address" />
        </div>
        <div id="phone" class="input">
          <label for="phone">Enter your number:</label>
          <input
            type="tel"
            id="phone"
            name="phone"
            placeholder="Enter Your Phonenumber"
            pattern="[0-9]{3}-[0-9]{2}-[0-9]{3}"
          />
        </div>
        <div id="email" class="input">
          <label for="email">Email</label>
          <input type="email" id="email" placeholder="Enter Your email" />
        </div>
        <div id="password" class="input">
          <label for="Password">Password: </label>
          <input
            type="password"
            id="Password"
            name="password"
            placeholder="Enter Your Password"
          />
        </div>
        <div id="confirmpassword" class="input">
          <label for="Password">Confirm Password: </label>
          <input
            type="password"
            id="Password"
            name="confirm_password"
            placeholder="Confirm Your Password"
          />
        </div>
        <a>
          <button type = "submit" class="input btn" id="signin">Sign Up</button>
        </a>
        <div id="signup">
          <p>Already have an Account? <a href="login.html">login</a></p>
        </div>
        </form>
      </div>
    </section>
  </body>
</html>
