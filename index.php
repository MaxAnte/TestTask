<?php
  //В этом блоке происходит проверка на то, что бы с этой страницы не было доступа к другим страницам, в зависимости от состояния сессии.
  //К примеру: Попасть на страницу регистрации с страницы профиля не будет возможным, так как для этого предварительно нужно будет выйти из профиля.
  session_start();
  if (!empty($_SESSION['userName'])){
      header ("location:/pages/profile.php");
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Log In page</title>
    <link rel="stylesheet" type="text/css" media="screen" href="styles/formLogIn.css" />
    <link rel="shortcut icon" type="image/png" href="pictures/polygonal-hexagon.png">
  </head>
  <body>
    <div class="form-wrap">
      <!--Блок, в котором будут отображаться ошибки валидации на стороне сервера-->
      <? if(!empty($_SESSION['errors'])): ?>
        <div class="error-box">
          <ul>
            <? foreach($_SESSION['errors'] as $error): ?>
              <li><?= $error ?></li>
            <? endforeach ?>
          </ul>
        </div>
      <? endif ?>
      <!--Блок, в котором отобразится информация об удачном прохождении регистрации-->
      <? if(!empty($_SESSION['message'])): ?>
        <div class="message-box">
          <? foreach($_SESSION['message'] as $message): ?>
            <p><?= $message ?></p>
          <? endforeach ?>
        </div>
      <? endif ?>
      <!--Далее идет верстка полей ввода Логина и Пароля пользователя-->
      <div class="logo">
          <img src="pictures/polygonal-hexagon.png">
      </div>
      <form id="logInForm" action="/handlers/app.php" method="post">
        <div class="inputCont">
          <img src="pictures/user-icon.png">
          <div class="div"></div>
          <input type="text" name="LogIn[userName]" placeholder="User name" >
        </div>
        <span class="errorMessage"></span>
        <div class="inputCont">
          <img src="pictures/pass-icon.png">
          <div class="div"></div>
          <input type="password" name="LogIn[password]" placeholder="Password">
        </div>
        <span class="errorMessage"></span>
        <input type="submit" name="submit" value="Log in">
        <div class="differentWay">
          <p>Don't have an account? <a href="pages/signUp.php">Sign Up!</a></p>
        </div>  
      </form>
    </div>
      
    <!--Подключение скриптов JavaScript. Первый это минимизированый код библиотеки jQuery. Второй это скрипт, в котором происходит Front-end валидация-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script text="text/javascript" src="scripts/validation.js"></script>
  </body>
</html>