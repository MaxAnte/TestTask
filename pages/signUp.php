<?php
  session_start();
  if (!empty($_SESSION['userName'])){
    header ("location:profile.php");
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sign Up page</title>
    <link rel="stylesheet" type="text/css" media="screen" href="../styles/formLogIn.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="../styles/formSignUp.css" />
    <link rel="shortcut icon" type="image/png" href="../pictures/polygonal-hexagon.png">
  </head>
  <body>
    <div class="form-wrap">
      <? if(!empty($_SESSION['errors'])): ?>
        <div class="error-box">
          <ul>
            <? foreach($_SESSION['errors'] as $error): ?>
              <li><?= $error ?></li>
            <? endforeach ?>
          </ul>
        </div>
      <? endif ?>
      <div class="logo">
        <img src="../pictures/polygonal-hexagon.png">
      </div>
      <form id="signInForm" action="../handlers/app.php" method="post">
        <div class="inputCont">
          <img src="../pictures/notebook-icon.png">
          <div class="div"></div>
          <input type="text" name="Registration[firstName]" placeholder="First Name">
        </div>
        <span class="errorMessage"></span>
        <div class="inputCont">
          <img src="../pictures/notebook-icon.png">
          <div class="div"></div>
          <input type="text" name="Registration[lastName]" placeholder="Last Name">
        </div>
        <span class="errorMessage"></span>
        <div class="inputCont">
          <img src="../pictures/notebook-icon.png">
          <div class="div"></div>
          <input type="text" name="Registration[userName]" placeholder="User Name">
        </div>
        <span class="errorMessage"></span>
        <div class="inputCont">
          <img src="../pictures/email-icon.png">
          <div class="div"></div>
          <input type="email" name="Registration[email]" placeholder="E-mail">
        </div>
        <span class="errorMessage"></span>
        <div class="inputCont">
          <img src="../pictures/pass-icon.png">
          <div class="div"></div>
          <input type="password" name="Registration[password]" placeholder="Password" >
        </div>
        <span class="errorMessage"></span>
        <div class="inputCont">
          <img src="../pictures/confirmPass-icon.png">
          <div class="div"></div>
          <input type="password" class="conf" name="Registration[confirmPassword]" placeholder="Confirm password">
        </div>
        <span class="errorMessage"></span>
        <input type="submit" name="submit" value="Sign Up">
        <div class="differentWay">
          <a href="../index.php">Back</a>
        </div>
      </form>
    </div>

    <script src="http://code.jquery.com/jquery-3.3.1.min.js" 
    integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script text="text/javascript" src="../scripts/validation.js"></script>
  </body>
</html>