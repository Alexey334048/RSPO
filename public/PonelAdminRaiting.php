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

// Получение всех отзывов для модерации
$sql = "SELECT * FROM ratings ORDER BY status, id DESC";
$stmt = $pdo->query($sql);
$ratings = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Обработка одобрения или удаления
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'], $_POST['id'])) {
    $action = $_POST['action'];
    $id = (int)$_POST['id'];

    if ($action === 'approve') {
        $pdo->prepare("UPDATE ratings SET status = 'approved' WHERE id = ?")->execute([$id]);
    } elseif ($action === 'delete') {
        $pdo->prepare("DELETE FROM ratings WHERE id = ?")->execute([$id]);
    }

    // Перенаправление для предотвращения повторной отправки
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Администратор</title>
    <script src="https://kit.fontawesome.com/467b90b200.js" crossorigin="anonymous"></script>
    <style>
        * {
            font-family: Nunito, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            text-decoration: none;
            list-style: none;
            line-height: 1.5;
            transition: all 0.7s;
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
            box-shadow: 0 0.1rem 0.3rem rgba(0, 0, 0, 0.3);
        }

        header .logo {
            font-size: 2rem;
            color: #FFDAB2;
        }

        header .fa-bars {
            font-size: 3rem;
            color: #FBF0E4;
            cursor: pointer;
        }

        section {
            padding: 20px;
            margin: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
            font-size: 3rem;
            margin-bottom: 20px;
        }

        button {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            background-color: #3D3D3D;
            color: #FFDAB2;
            cursor: pointer;
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

        .dropbtn:hover {
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
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #ddd;
        }

        .show {
            display: block;
        }
    </style>
</head>

<body>
    <header>
        <a href="#" class="logo"><span>DREAM COME TRUE ADMIN</span></a>
        <div class="dropdown">
            <button onclick="toggleMenu()" class="dropbtn"><strong>МЕНЮ</strong></button>
            <div id="menuDropdown" class="dropdown-content">
                <a href="PonelAdmin.php">БРОНЬ ДОМОВ</a>
                <a href="PonelAdminHome.php">ДОМА</a>
                <a href="PonelAdminBath.php">БАНЯ</a>
                <a href="PonelAdminTransfer.php">ТРАНСФЕР</a>
                <a href="PonelAdminRaiting.php"><strong>ОТЗЫВЫ</strong></a>
            </div>
        </div>
    </header>

    <section id="ratings">
        <h1>Список отзывов</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ФИО</th>
                    <th>Описание</th>
                    <th>Рейтинг</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ratings as $rating): ?>
                    <tr>
                        <td><?= htmlspecialchars($rating['id']) ?></td>
                        <td><?= htmlspecialchars($rating['FIO']) ?></td>
                        <td><?= htmlspecialchars($rating['Description']) ?></td>
                        <td><?= htmlspecialchars($rating['rating']) ?> ★</td>
                        <td><?= htmlspecialchars($rating['status']) ?></td>
                        <td>
                            <?php if ($rating['status'] === 'pending'): ?>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $rating['id'] ?>">
                                    <button type="submit" name="action" value="approve">Одобрить</button>
                                </form>
                            <?php endif; ?>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $rating['id'] ?>">
                                <button type="submit" name="action" value="delete" onclick="return confirm('Удалить отзыв?');">Удалить</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <footer>
        <p>&copy; <?= date("Y") ?> Административная панель</p>
    </footer>

    <script>
        // Открытие/закрытие меню
        function toggleMenu() {
            document.getElementById("menuDropdown").classList.toggle("show");
        }

        // Закрытие меню при клике вне его
        window.onclick = function(event) {
            if (!event.target.matches('.dropbtn')) {
                let dropdowns = document.getElementsByClassName("dropdown-content");
                for (let i = 0; i < dropdowns.length; i++) {
                    let openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>
</body>
</html>
