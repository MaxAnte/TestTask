<?php
//В этом файле происходит "уничтожение" сессии, для того что бы выйти из профиля на страницу авторизации.
session_start();
session_unset();
session_destroy();

header("location:../index.php");
?>