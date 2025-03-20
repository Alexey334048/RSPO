<?php
// Подключение к базе данных
$host = 'MySQL-8.2'; // Хост (обычно localhost)
$dbname = 'dream'; // Имя базы данных
$username = 'root'; // Имя пользователя
$password = ''; // Пароль (если есть)

$conn = new mysqli($host, $username, $password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Проверяем, был ли отправлен POST-запрос
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получение данных из формы
    $full_name = $_POST['full_name'] ?? '';
    $visit_date = $_POST['visit_date'] ?? '';
    $visit_time = $_POST['visit_time'] ?? '';
    $people_count = $_POST['people_count'] ?? 0;

    // Проверка данных на пустоту
    if (!empty($full_name) && !empty($visit_date) && !empty($visit_time) && $people_count > 0) {
        // Подготовка SQL-запроса
        $sql = "INSERT INTO visits (full_name, visit_date, visit_time, people_count) 
                VALUES (?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $full_name, $visit_date, $visit_time, $people_count);

        // Выполнение запроса
        if ($stmt->execute()) {
            echo "<h3>Бронирование успешно!</h3><a href='index.html'>Вернуться</a>";
        } else {
            echo "Ошибка: " . $stmt->error;
        }

        // Закрытие запроса
        $stmt->close();
    } else {
        echo "<h3>Ошибка! Заполните все поля.</h3><a href='index.html'>Вернуться</a>";
    }
}

// Закрытие соединения
$conn->close();
?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking a bathhouse</title>
</head>
<body>
<div class="container">
 <h2>Бронирование бани</h2>
 <form action="bathhouse.php" method="post">
 <label for="full_name">ФИО:</label>
 <input type="text" id="full_name" name="full_name" required>

 <label for="visit_date">Дата посещения:</label>
 <input type="date" id="visit_date" name="visit_date" required>

 <label for="visit_time">Время посещения:</label>
 <input type="time" id="visit_time" name="visit_time" required>

 <label for="people_count">Количество человек:</label>
 <input type="number" id="people_count" name="people_count" min="1" required>

 <button type="submit">Забронировать</button>
 </form>
</div>
</body>
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #1E1E1E;
    text-align: center;
}

.container {
    background: #D8BFAA;
    width: 350px;
    padding: 20px;
    margin: 50px auto;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

h2 {
    margin-bottom: 20px;
}

label {
    display: block;
    margin-top: 10px;
    font-weight: bold;
}

input {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

button {
    margin-top: 15px;
    padding: 10px;
    background: #1E1E1E;
    color: white;
    border: none;
    width: 100%;
    cursor: pointer;
    font-size: 16px;
}

button:hover {
    background:rgb(82, 82, 82);
}

    </style>
</html>
