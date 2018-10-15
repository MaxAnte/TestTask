<?php
  //Файл страницы профиля. Сначала просходит проверка на то, есть ли доступ к этой странице из других страниц. А дальше верстка страницы со вставкой
  //инормации о пользователе из сессии.
  session_start();
  if($_SESSION['logged'] == false){
    header("Location: ../index.php");
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Profile page</title>
    <link rel="stylesheet" type="text/css" media="screen" href="../styles/formLogIn.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="../styles/profile.css" />
    <link rel="shortcut icon" type="image/png" href="../pictures/polygonal-hexagon.png">
  </head>
<body>
  <div class="form-wrap">
    <img class="profileLogo" src="../pictures/polygonal-hexagon.png">
    <div class="infoBlock">
      <div class="namesPlace">
        <p><?php echo $_SESSION['firstName'].' "'.$_SESSION['userName'].'" '.$_SESSION['lastName'];?></p>
      </div>
      <div class="infoPlace">
        <img src="../pictures/email-icon.png">
        <p>E-mail:</p><p class="sesInfo"><?php echo $_SESSION['email'];?></p>
      </div>
      <div class="infoPlace">
        <img src="../pictures/regdate-icon.png">
        <p>Registration Date:</p><p class="sesInfo"><?php echo $_SESSION['date'];?></p>
      </div>
    </div>
    <div class="logOut">
      <a href="../handlers/logOut.php">Log Out</a>
    </div>
  </div>
</body>
</html>