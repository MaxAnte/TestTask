<?php
//Это файл - обработчик запросов, которые приходят из двух форм. К каждой форме прилогается соответствующий метод(функция) обработки данных.

//Данный блок, это проверки на метод, получаемый из формы, а также определение какую функцию обработки нужно использовать для формы отправившей данные.
  if($_SERVER["REQUEST_METHOD"] == "POST"){  
    $dbh; //Переменная, которой позже будет присвоена база данных. Объявлена она здесь для решения проблем с видимостью переменной.
    db_connection();  //Функция подключения к базе данных. Создание этой функции будет описано ниже.
    session_start();  //Функция запуска сессии.
    $_SESSION['message'] = [];  //Массив в сессии, в который запишется сообщение о удачном прохождении регистрации.
    $_SESSION['errors'] = [];   //Массив, в который будут записыватся различные ошибки при прохождении авторизации или регистрации.

    if($_POST['LogIn']) { //Проверка на тип прибывших данных. В данном случае проверяется или данные пришли из формы авторизации.
      $_SESSION['user'] = $_POST['LogIn'];  //Данные из формы записываются в сессию.
      logIn();  //Функция обработки данных из формы авторизации.
    }elseif($_POST['Registration']) { //Проверка на тип прибывших данных. В данном случае проверяется или данные пришли из формы регистрации.
      $_SESSION['user'] = $_POST['Registration']; 
      signUp();
    }
  }

  //Функция для подключение к базе данных.
  function db_connection (){
    $host = 'localhost';
    $db = 'accounts';
    $user = 'root';
    $pass = '';

    $GLOBALS['dbh'] = new PDO('mysql:host='.$host.';dbname='.$db, $user, $pass);
  }

  //Функция на обработку данных из полей ввода относительно пробелов, спецсимволов и т.д.
  function clean($value) {
    $value = trim($value);
    $value = stripslashes($value);
    $value = strip_tags($value);
    $value = htmlspecialchars($value);

    return $value;
  }

  //Функция обработки данных для авторизации.
  function logIn(){
    //Заносим полученные отформатированные данные в массив.
    $authorization['userName'] = clean($_POST['LogIn']['userName']);
    $authorization['password'] = clean($_POST['LogIn']['password']);
    
    //Валидация на длинну данных в поле ввода. Здесь мы не допускаем пустое поле и данные короче двух символов и больше 25.
    foreach ($authorization as $val) {
      if(strlen($val) <= 2 || strlen($val) >= 25) {
        $_SESSION['errors'][] = 'Error - Enter more than 2 symbols';  //Таким способом записывается ошибка в массив. Она будет показана на странице авторизации.
        header("Location:../index.php");
        die();
      }
    }

    //Запрос в БД для получения даты регистрации пользователя. Это нужно для того, что бы отобразить ее в профиле.
    $dateQuery = $GLOBALS['dbh']->prepare("SELECT registrationDate FROM users WHERE userName=?");
    $dateQuery->execute([$authorization['userName']]);
    $dateResult = $dateQuery->fetchAll(PDO::FETCH_ASSOC);
    $_SESSION['date'] = $dateResult[0]['registrationDate']; //Записываем полученную дату в сессию.

    //Запрос в БД для получения информации о том, есть ли такое имя пользователя.
    $sth = $GLOBALS['dbh']->prepare("SELECT * FROM users WHERE userName=?");
    $sth->execute([$authorization['userName']]);
    $result = $sth->rowCount();

    //Проверка. Если введенный в форму логин и логин из БД не совпадают, то появится ошибка. Если же совпали, дальше идет проверка совпадения паролей.
    //При успешном прохождении проверок, мы перейдем на страницу профиля.
    if($result == 0){
      $_SESSION['errors'][] = 'Error - User not exist';
      header("Location:../index.php");
    }else{
      $user = $sth->fetch(PDO::FETCH_ASSOC);

      if(($authorization['password'] == $user['password']) ){

        $_SESSION['firstName'] = $user['firstName'];
        $_SESSION['lastName'] = $user['lastName'];
        $_SESSION['userName'] = $user['userName'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['password'] = $user['password'];

        $_SESSION['logged'] = true;

        header("location:../pages/profile.php");

      }else{
        $_SESSION['errors'][] = 'Error - Wrong password';
        header("Location:../index.php");
      }
    }
  }
  
  //Функция обработки данных для регистрации.
  function signUp(){
    $registration['firstName'] = clean($_POST['Registration']['firstName']);
    $registration['lastName'] = clean($_POST['Registration']['lastName']);
    $registration['userName'] = clean($_POST['Registration']['userName']);
    $registration['email'] = clean($_POST['Registration']['email']);
    $registration['password'] = clean($_POST['Registration']['password']);
    $registration['confirmPassword'] = clean($_POST['Registration']['confirmPassword']);

    foreach ($registration as $val) {
      if(strlen($val) <= 2 || strlen($val) >= 25) {
        $_SESSION['errors'][] = 'Error - Enter more than 2 symbols'; 
        header("Location:../pages/signUp.php");
        die();
      }
    }

    //Запрос в БД для того, что бы проверить зарегистрирован ли уже пользователь с таким именем(Username).
    $userNameQuery = $GLOBALS['dbh']->prepare("SELECT * FROM users WHERE userName=?");
    $userNameQuery->execute([$registration['userName']]);
    $userNameQueryResult = $userNameQuery->rowCount();

    //Запрос в БД для того, что бы проверить зарегистрирован ли уже пользователь с такой почтой.
    $emailQuery = $GLOBALS['dbh']->prepare("SELECT * FROM users WHERE email=?");
    $emailQuery->execute([$registration['email']]);
    $emailQueryResult = $emailQuery->rowCount();

    if($userNameQueryResult > 0){
      $_SESSION['errors'][] = 'Error - This username already taken';
      header("Location:../pages/signUp.php");
    }elseif($emailQueryResult > 0){
      $_SESSION['errors'][] = 'Error - This e-mail already taken';
      header("Location:../pages/signUp.php");
    }else{
      if(($_POST['Registration']['password'] == $_POST['Registration']['confirmPassword']) ){     //Проверка на соответствие паролей.
        $addQuery = $GLOBALS['dbh']->prepare("INSERT INTO users (firstName,lastName,userName,email,password)  
        VALUES (?, ?, ?, ?, ?)"); //Запрос на добавление пользователя в БД.
        $addQuery->execute([$registration['firstName'], $registration['lastName'], 
        $registration['userName'], $registration['email'], $registration['password']]);

        $_SESSION['message'][] = 'Log in to complete registration';
        header ("location:../pages/profile.php");
      }else{
        $_SESSION['errors'][] = 'Error - Passwords did not match';
        header("Location:../pages/signUp.php");
      }
    }
  }

?>