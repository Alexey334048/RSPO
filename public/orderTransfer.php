<?php
// Подключение к базе данных
$host = 'MySQL-8.2'; // хост
$dbname = 'dream'; // имя базы данных
$username = 'root'; // имя пользователя
$password = ''; // пароль (если есть)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection error: " . $e->getMessage());
}

// Проверяем, были ли отправлены данные из формы
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получаем данные из формы
    $full_name = $_POST['full_name'];
    $address = $_POST['address'];
    $transfer_date = $_POST['transfer_date'];
    $transfer_time = $_POST['transfer_time'];

    // Запрос на вставку данных в таблицу
    $sql = "INSERT INTO Transfers (full_name, address, transfer_date, transfer_time) 
            VALUES (:full_name, :address, :transfer_date, :transfer_time)";
    
    $stmt = $pdo->prepare($sql);
    
    // Привязываем параметры
    $stmt->bindParam(':full_name', $full_name);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':transfer_date', $transfer_date);
    $stmt->bindParam(':transfer_time', $transfer_time);
    
    // Выполняем запрос
    // Выполняем запрос
    if ($stmt->execute()) {
        // Устанавливаем сообщение об успешном заказе
        $message = "Transfer ordered successfully!";
    } else {
        // Устанавливаем сообщение об ошибке
        $message = "Error while ordering transfer.";
    }
    
    // Перенаправление на текущую страницу для отображения попапа
    header("Location: " . $_SERVER['PHP_SELF'] . "?message=" . urlencode($message));
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- fontawesone link -->
    <script src="https://kit.fontawesome.com/467b90b200.js" crossorigin="anonymous"></script>
    <title>Заказ трансфера</title>
    <style>
        /* Стиль для попапа */
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 20px;
            border-radius: 10px;
            font-size: 16px;
            z-index: 999;
        }

        .popup button {
            background-color:rgb(235, 212, 186);
            color: #1E1E1E;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }

        .popup button:hover {
            background-color: #FBF0E4;
        }
    </style>
</head>
<body>

<div class="container">
 <h2>Заказ трансфера</h2>
 <form action="orderTransfer.php" method="POST">
 <label for="full_name">ФИО</label>
 <input type="text" id="full_name" name="full_name" required>

 <label for="address">Адрес:</label>
 <input type="text" id="address" name="address" required>

 <label for="transfer_date">Дата заказа трансфера:</label>
 <input type="date" id="transfer_date" name="transfer_date" required>

 <label for="transfer_time">Время заказа трансфера:</label>
 <input type="time" id="transfer_time" name="transfer_time" required>

 <button type="submit">Заказать трансфер</button>
 </form>
 </div>
     <!-- Попап для сообщения -->
     <div id="popup" class="popup">
        <p id="popupMessage"></p>
        <button onclick="closePopup('')">Закрыть</button>
    </div>

    <script>
        // Проверка, если в URL есть параметр 'message'
        const urlParams = new URLSearchParams(window.location.search);
        const message = urlParams.get('message');

        // Если есть сообщение, показываем попап
        if (message) {
            document.getElementById('popupMessage').textContent = message;
            document.getElementById('popup').style.display = 'block';
        }

        // Функция для закрытия попапа
        function closePopup() {
            document.getElementById('popup').style.display = 'none';
        }
    </script>

</body>
<style>
    /* styles.css */
body {
    font-family: Nunito, sans-serif;
    background-color: #1E1E1E;
    margin: 0;
    padding: 0;
}

.container {
    width: 400px;
    margin: 100px auto;
    padding: 20px;
    background-color: #D8BFAA;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

h2 {
    text-align: center;
    color: #333;
}

form {
    display: flex;
    flex-direction: column;
}

label {
    margin-bottom: 5px;
    color: #333;
}

input {
    margin-bottom: 15px;
    padding: 8px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

button {
    padding: 10px;
    background-color: #3D3D3D;
    color: #FBF0E4;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

button:hover {
    background-color:rgb(103, 103, 103);
}

</style>
</html>
