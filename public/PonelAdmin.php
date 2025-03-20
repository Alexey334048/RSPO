<?php
// Подключение к базе данных
$host = 'MySQL-8.2';
$dbname = 'dream';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}

// Обработка удаления бронирования
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $deleteId = intval($_POST['delete_id']);
    $deleteSql = "DELETE FROM bookings WHERE id = :id";
    $deleteStmt = $pdo->prepare($deleteSql);
    $deleteStmt->bindParam(':id', $deleteId, PDO::PARAM_INT);
    if ($deleteStmt->execute()) {
        echo "<script>alert('Бронирование успешно удалено!'); window.location.href = 'PonelAdmin.php';</script>";
    } else {
        echo "<script>alert('Ошибка при удалении бронирования.');</script>";
    }
}

// Обработка обновления бронирования
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {
    $editId = intval($_POST['edit_id']);
    $firstName = htmlspecialchars($_POST['edit_first_name']);
    $lastName = htmlspecialchars($_POST['edit_last_name']);
    $email = htmlspecialchars($_POST['edit_email']);
    $checkinDate = htmlspecialchars($_POST['edit_checkin_date']);
    $nights = intval($_POST['edit_nights']);
    $guests = intval($_POST['edit_guests']);
    $totalPrice = floatval($_POST['edit_total_price']);

    $updateSql = "UPDATE bookings SET first_name = :first_name, last_name = :last_name, email = :email, 
                  checkin_date = :checkin_date, nights = :nights, guests = :guests, total_price = :total_price
                  WHERE id = :id";

    $updateStmt = $pdo->prepare($updateSql);
    $updateStmt->bindParam(':first_name', $firstName);
    $updateStmt->bindParam(':last_name', $lastName);
    $updateStmt->bindParam(':email', $email);
    $updateStmt->bindParam(':checkin_date', $checkinDate);
    $updateStmt->bindParam(':nights', $nights, PDO::PARAM_INT);
    $updateStmt->bindParam(':guests', $guests, PDO::PARAM_INT);
    $updateStmt->bindParam(':total_price', $totalPrice);
    $updateStmt->bindParam(':id', $editId, PDO::PARAM_INT);

    if ($updateStmt->execute()) {
        echo "<script>alert('Бронирование успешно обновлено!'); window.location.href = 'PonelAdmin.php';</script>";
    } else {
        echo "<script>alert('Ошибка при обновлении бронирования.');</script>";
    }
}

// Обработка экспорта в Excel
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['export_excel'])) {
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];

    if ($startDate && $endDate) {
        $exportSql = "SELECT * FROM bookings WHERE checkin_date BETWEEN :start_date AND :end_date";
        $exportStmt = $pdo->prepare($exportSql);
        $exportStmt->bindParam(':start_date', $startDate);
        $exportStmt->bindParam(':end_date', $endDate);
        $exportStmt->execute();
        $bookings = $exportStmt->fetchAll(PDO::FETCH_ASSOC);

        if ($bookings) {
            // Заголовки для скачивания Excel
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=bookings_export_{$startDate}_to_{$endDate}.xls");

            // Добавляем BOM для корректной кодировки UTF-8
            echo "\xEF\xBB\xBF"; 

            // Заголовки таблицы
            echo "ID;ID дома;Имя;Фамилия;Email;Дата заезда;Ночей;Гостей;Стоимость (руб.)\n";

            // Заполнение данными
            // Заполнение данными
            foreach ($bookings as $booking) {
                echo "{$booking['id']};{$booking['house_id']};{$booking['first_name']};{$booking['last_name']};{$booking['email']};{$booking['checkin_date']};{$booking['nights']};{$booking['guests']};{$booking['total_price']}\n";
            }
            exit;
        } else {
            echo "<script>alert('Нет данных за выбранный период.');</script>";
        }
    }
}

// Получение данных из таблицы bookings
$sql = "SELECT * FROM bookings";
$stmt = $pdo->query($sql);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Администратор</title>
    <script src="https://kit.fontawesome.com/467b90b200.js" crossorigin="anonymous"></script>
    <style>
        /* Основные стили */
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

        .heading {
            font-size: 4rem;
            color: #FFDAB2;
            text-align: center;
            padding: 1rem;
            padding-top: 3rem;
            font-weight: 100;
        }

        html {
            font-size: 62.5%;
            overflow-x: hidden;
        }

        header {
            background-color: #3D3D3D;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 8%;
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

        section {
            padding: 20px;
            margin: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
        }

        .actions {
            margin-bottom: 20px;
        }

        button {
            padding: 10px 15px;
            margin-right: 10px;
            border: none;
            border-radius: 5px;
            background-color: #3D3D3D;
            color: #FFDAB2;
            cursor: pointer;
            align-items: right;
        }

        button:hover {
            background-color: rgb(86, 86, 86);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        table th {
            background-color: #f8f9fa;
        }

        footer {
            text-align: center;
            padding: 10px;
            background-color: #333;
            color: #fff;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        .dropbtn {
            background-color: #1e1e1e;
            color: #FFDAB2;
            width: 120px;
            font-size: 10px;
            border: none;
            cursor: pointer;
        }

        .dropbtn:hover, .dropbtn:focus {
            background-color: rgb(86, 86, 86);
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f1f1f1;
            min-width: 160px;
            overflow: auto;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown a:hover {
            background-color: #ddd;
        }

        .show {
            display: block;
        }

        /* Стили формы экспорта */
        .export-form {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f8f8f8;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .export-form input {
            padding: 8px;
            margin-right: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .export-form button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }

        .export-form button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
<header>
    <a href="#" class="logo"><span>DREAM COME TRUE ADMIN</span></a>
    <div class="dropdown">
        <button onclick="myFunction()" class="dropbtn"><strong>МЕНЮ</strong></button>
        <div id="myDropdown" class="dropdown-content">
            <a href="PonelAdmin.php"><strong>БРОНЬ ДОМОВ</strong></a>
            <a href="PonelAdminHome.php">ДОМА</a>
            <a href="PonelAdminBath.php">БАНЯ</a>
            <a href="PonelAdminTransfer.php">ТРАНСФЕР</a>
            <a href="PonelAdminRaiting.php">ОТЗЫВЫ</a>
        </div>
    </div>
</header>

<!-- Форма экспорта -->
<section class="export-form">
    <h2>Экспорт бронирований в Excel</h2>
    <form method="post">
        <label for="start_date">Дата начала:</label>
        <input type="date" name="start_date" required>

        <label for="end_date">Дата окончания:</label>
        <input type="date" name="end_date" required>

        <button type="submit" name="export_excel">Экспорт в Excel</button>
    </form>
</section>

<!-- Таблица бронирований -->
<section id="bookings">
    <h1 align="center">Список забронированных домов</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>ID дома</th>
                <th>Имя</th>
                <th>Фамилия</th>
                <th>Email</th>
                <th>Дата заезда</th>
                <th>Ночей</th>
                <th>Гостей</th>
                <th>Стоимость</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bookings as $booking): ?>
                <tr>
                    <td><?= htmlspecialchars($booking['id']) ?></td>
                    <td><?= htmlspecialchars($booking['house_id']) ?></td>
                    <td><?= htmlspecialchars($booking['first_name']) ?></td>
                    <td><?= htmlspecialchars($booking['last_name']) ?></td>
                    <td><?= htmlspecialchars($booking['email']) ?></td>
                    <td><?= htmlspecialchars($booking['checkin_date']) ?></td>
                    <td><?= htmlspecialchars($booking['nights']) ?></td>
                    <td><?= htmlspecialchars($booking['guests']) ?></td>
                    <td><?= htmlspecialchars($booking['total_price']) ?> руб.</td>
                    <td>
                        <button onclick="openEditModal(
                            '<?= $booking['id'] ?>',
                            '<?= $booking['first_name'] ?>',
                            '<?= $booking['last_name'] ?>',
                            '<?= $booking['email'] ?>',
                            '<?= $booking['checkin_date'] ?>',
                            '<?= $booking['nights'] ?>',
                            '<?= $booking['guests'] ?>',
                            '<?= $booking['total_price'] ?>'
                        )">Редактировать</button>

                        <form method="post" style="display:inline;" onsubmit="return confirm('Вы уверены, что хотите удалить это бронирование?');">
                            <input type="hidden" name="delete_id" value="<?= $booking['id'] ?>">
                            <button type="submit">Удалить</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

<footer>
    <p>&copy; 2025 Административная панель</p>
</footer>

<script>
    function myFunction() {
        document.getElementById("myDropdown").classList.toggle("show");
    }

    window.onclick = function(event) {
        if (!event.target.matches('.dropbtn')) {
            var dropdowns = document.getElementsByClassName("dropdown-content");
            for (var i = 0; i < dropdowns.length; i++) {
                if (dropdowns[i].classList.contains('show')) {
                    dropdowns[i].classList.remove('show');
                }
            }
        }
    }
</script>

</body>
</html>
