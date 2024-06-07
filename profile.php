<?php
session_start();

// Проверяем, авторизован ли пользователь
if ($_SESSION['auth'] == false) {
    header("Location: login.php");
    exit();
}

// Проверяем, установлена ли переменная сессии 'login'
if (!isset($_SESSION['login'])) {
    header('Location: login.php');
    exit;
}

// Подключаем файл с конфигурацией
require 'config.php';

// Подключаемся к базе данных
$connectdb = new mysqli(DB_SERVER, DB_LOGIN, DB_PASS, DB_DATABASE);

// Проверяем соединение
if ($connectdb->connect_error) {
    // Логируем ошибку подключения без раскрытия данных
    error_log("Ошибка подключения к базе данных: " . $connectdb->connect_error);
    die("Ошибка подключения к базе данных.");
}

// получаем логин пользователя
$login = $_SESSION['login'];

// получаем все данные по логину
$stmt = $connectdb->prepare("SELECT  *  FROM users WHERE email = ? OR phone = ?");
$stmt->bind_param("ss", $login, $login);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    die("Пользователь не найден.");
}

// обрабатываем форму
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];

    // проверяем пароли
    if (!empty($new_password) && $new_password == $_POST['confirm_new_password']) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // обновляем данные в бд
        $query_update = "UPDATE users SET name = '$name', phone = '$phone', email = '$email', password = '$hashed_password' WHERE name = '$name'";
        $result_update = $connectdb->query($query_update);

        if ($result_update) {
            echo "Данные успешно обновлены.";
        } else {
            echo "Ошибка при обновлении данных: " . $connectdb->error;
        }
    } else {
        echo "Пожалуйста, введите и подтвердите новый пароль.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль пользователя</title>
</head>
<body>
    <h1>Профиль пользователя</h1>

    <form action="profile.php" method="post">
      Имя: <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>"><br>
      Телефон: <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>"><br>
      Почта: <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>"><br>
      Новый пароль: <input type="password" name="new_password"><br>
      Повторите новый пароль: <input type="password" name="confirm_new_password"><br>
      <input type="submit" value="Обновить">
    </form>

</body>
</html>