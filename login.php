<?php
session_start();

// закидываем конфиг с данными от бд
require 'config.php';

// подключаемся к базе данных
$connectdb = new mysqli(DB_SERVER, DB_LOGIN, DB_PASS, DB_DATABASE);

// проверяем коннект
if ($connectdb->connect_error) {
  // Логирование ошибок подключения без раскрытия данных
  error_log("Ошибка подключения к базе данных: " . $connectdb->connect_error);
  die("Ошибка подключения к базе данных.");
}

// обрабатываем форму
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  // Фильтрация ввода
  $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING);
  $password = $_POST['password'];

  // проверяем данные
  $stmt = $connectdb->prepare("SELECT  *  FROM users WHERE email = ? OR phone = ?");
  $stmt->bind_param("ss", $login, $login);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();

  if ($user) {
    // а тут проверяем пароль
    if (password_verify($password, $user['password'])) {
      $_SESSION['login'] = $login;
      $_SESSION['auth'] = true;
      // перенаправляем пользователя на страницу профиля
      header('Location: profile.php');
      exit;
    } else {
      echo "Неверный пароль.";
    }
  } else {
    echo "Пользователь с таким телефоном или почтой не найден.";
  }
  $stmt->close();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>
<body>

<form action="login.php" method="post">
	Телефон или почта: <input type="text" name="login" required><br>
	Пароль: <input type="password" name="password" required><br>
	<input type="submit" value="Войти">
</form>

</body>
</html>
