<?php
// Подключение к базе данных
$host = 'MySQL-8.2'; // Хост
$dbname = 'dream'; // Имя базы данных
$username = 'root'; // Имя пользователя
$password = ''; // Пароль (если есть)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}

// Обработка формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем данные из формы
    $fullName = $_POST['full-name'];
    $email = $_POST['email'];
    
    // Разделим полное имя на имя и фамилию
    // Если имя состоит из нескольких слов, ограничим разделение на два
    list($firstName, $lastName) = explode(' ', $fullName, 2); // Разделяем только на два слова
    
    // SQL-запрос для удаления из таблицы bookings
    $sql = "DELETE FROM bookings WHERE first_name = :first_name AND last_name = :last_name AND email = :email";
    
    // Подготовка и выполнение запроса
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':email', $email);
        
        $stmt->execute();
        
        // Проверяем, сколько строк было затронуто
        if ($stmt->rowCount() > 0) {
            $message = "Ваше бронирование успешно отменено.";
        } else {
            $message = "Ошибка: не удалось найти бронирование с такими данными.";
        }
    } catch (PDOException $e) {
        $message = "Ошибка выполнения запроса: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Отмена бронирования</title>

    <script src="https://kit.fontawesome.com/467b90b200.js" crossorigin="anonymous"></script>
</head>
<body>
    <!-- header section start -->
    <header>
        <a href="/index.php" class="logo"><span>DREAM COME TRUE</span></a>
        <a href="/menu.html" class="fas fa-bars"></a>
    </header>
    <!-- header section end -->

    <div class="container">
        <h1>Отмена бронирования дома</h1>
        <p>Пожалуйста, введите информацию для отмены бронирования:</p>
        
        <!-- Вывод сообщения о результате операции -->
        <?php if (isset($message)): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>

        <!-- Форма для отмены бронирования -->
        <form action="" method="POST">
            <label for="full-name">Ф.И.О.:</label>
            <input type="text" id="full-name" name="full-name" required>

            <label for="email">Электронная почта:</label>
            <input type="email" id="email" name="email" required>

            <label for="reason">Причина отмены:</label>
            <textarea id="reason" name="reason" rows="4" required></textarea>

            <button type="submit" class="btn-submit">Отменить бронирование</button>
        </form>
    </div>

    <footer>
        <div class="footer-content">
            <div class="brand">
                <h2>DREAM COME TRUE</h2>
                <p>АРЕНДА КРАСИВЫХ ДОМОВ НА ОЗЕРЕ</p>
            </div>
            <div class="contacts">
                <p><strong>КОНТАКТЫ</strong></p>
                <p>Минская область, Воложенский район, село Васильевское,<br> территория "Dream come true"</p>
                <p>+375 (29) 123-53-53</p>
                <p>hello@dream_come_true.ru</p>
                <div class="social-icons">
                    <button class="icon-btn"><i class="fab fa-youtube"></i></button>
                    <button class="icon-btn"><i class="fab fa-telegram"></i></button>
                    <button class="icon-btn"><i class="fab fa-whatsapp"></i></button>
                </div>
            </div>
            <div class="links">
                <a href="#">Публичная оферта</a>
                <a href="#">Политика конфиденциальности</a>
                <a href="#">Условия бронирования, оплаты и аннулирования</a>
                <a href="#">Пользовательское соглашение</a>
                <a href="#">Правила пожарной безопасности</a>
                <a href="#">Правила проживания</a>
            </div>
        </div>
    </footer>
    <!-- footer end -->
</body>
</html>

<style>
    * {
        font-family: Nunito, sans-serif;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        text-decoration: none;
        list-style: none;
        line-height: 1.5;
        transition: all .7s;
    }

    body {
        margin: 0;
        padding: 0;
        background-color: #1E1E1E;
    }

    header {
        width: 100%;
        background-color: #3D3D3D;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 8%;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
        box-shadow: 0 .1rem .3rem rgb(0, 0, 0, .3);
    }

    header .logo {
        font-size: 2rem;
        color: #FFDAB2;
    }

    header .fa-bars {
        font-size: 3rem;
        color: #FBF0E4;
        cursor: pointer;
        display: none;
    }

    .container {
        max-width: 500px;
        margin: 0 auto;
        background-color: #FBF0E4;
        padding: 100px;

        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .container h1 {
        text-align: center;
        font-size: 24px;
        color: #333;
        margin-bottom: 20px;
    }

    .container p {
        font-size: 16px;
        color: #666;
        text-align: center;
        margin-bottom: 20px;
    }

    .container label {
        display: block;
        font-size: 14px;
        margin-bottom: 8px;
        color: #555;
    }

    .container input,
    textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 16px;
    }

    .container button {
        background-color: #1e1e1e;
        color: #FBF0E4;
        border: none;
        padding: 12px 20px;
        font-size: 16px;
        cursor: pointer;
        border-radius: 4px;
        width: 100%;
    }

    .container button:hover {
        background-color:rgb(63, 63, 63);
    }

/* footer */

footer {
    background-color: #3D3D3D;
    padding: 20px 0;
}

.footer-content {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    color: #FBF0E4;
}

footer .brand,
.contacts {
    margin: 20px;
    text-align: left;
}

footer .links {
    margin: 10px;
    text-align: right;
}

footer .brand h2 {
    color: #FFDAB2;
    font-size: 20px;
    margin-bottom: 5px;
    font-weight: 100;
}

footer .brand p {
    font-size: 10px;
}

footer .contacts p strong {
    color: #FFDAB2;
    font-weight: 100;
}

footer .contacts p,
.links a {
    font-size: 14px;
    color: #FBF0E4;
    text-decoration: none;
}

footer .social-icons .icon-btn {
    background-color: #FFDAB2;
    width: 30px;
    height: 30px;
    margin: 0 5px;
    border: none;
    border-radius: 60%;
}

footer .links a {
    display: block;
    margin: 5px 0;
    color: #FBF0E4;
}

footer .links a:hover {
    color: #ffffff;
}

@media (max-width: 50000px) {
    html {
        font-size: 55%;
    }
    header .fa-bars {
        display: block;
    }
}
</style>
