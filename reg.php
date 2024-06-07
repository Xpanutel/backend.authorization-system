<?php
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
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // проверяем пароли
    if ($password !== $confirm_password) {
        echo "Введенные пароли не совпадают.";
    } else {
        // теперь проверяем уникальность полей
        $stmt = $connectdb->prepare("SELECT * FROM users WHERE email = ? OR name = ? OR phone = ?");
        $stmt->bind_param("sss", $email, $name, $phone);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Пользователь с таким именем, почтой или телефоном уже существует.";
        } else {
            // хешируем пароли
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // закидываем данные с формы в бд
            $stmt = $connectdb->prepare("INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $phone, $hashed_password);

            // обрабатываем исключения
            try {
                $stmt->execute();
                echo "Регистрация успешно завершена.";
                header('Location: login.php');
                exit;
            } catch (Exception $e) {
                // а тут логируем ошибки
                error_log("Ошибка при отправке данных: " . $e->getMessage());
                echo "Произошла ошибка при регистрации.";
            }
        }
        $stmt->close();
    }
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

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    Имя: <input type="text" name="name" required>
    Телефон: <input type="text" name="phone" required>
    Почта: <input type="email" name="email" required>
    Пароль: <input type="password" name="password" required>
    Повторите пароль: <input type="password" name="confirm_password" required>
    <input type="submit" value="Регистрация">
</form>

</body>
</html>