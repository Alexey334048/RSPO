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

// Удаление бронирования
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = (int)$_POST['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM visits WHERE id = ?");
    $stmt->execute([$delete_id]);
    header("Location: PonelAdminBath.php");
    exit;
}

// Редактирование бронирования
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {
    $edit_id = (int)$_POST['edit_id'];
    $visit_date = htmlspecialchars($_POST['visit_date']);
    $visit_time = htmlspecialchars($_POST['visit_time']);
    $full_name = htmlspecialchars($_POST['full_name']);
    $people_count = (int)$_POST['people_count'];

    $stmt = $pdo->prepare("UPDATE visits SET visit_date = ?, visit_time = ?, full_name = ?, people_count = ? WHERE id = ?");
    $stmt->execute([$visit_date, $visit_time, $full_name, $people_count, $edit_id]);
    header("Location: PonelAdminBath.php");
    exit;
}

// Получение данных из таблицы visits
$sql = "SELECT * FROM visits";
$stmt = $pdo->query($sql);
$visits = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    /* width: 100%; */
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
            background-color:rgb(86, 86, 86);
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
    background-color:rgb(86, 86, 86);
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

.dropdown a:hover {background-color: #ddd;}

.show {display: block;}

.modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5);
            padding-top: 50px;
        }

        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 20px;
            border-radius: 10px;
            width: 50%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }

        .modal input {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .close-btn {
            float: right;
            font-size: 20px;
            cursor: pointer;
        }

        .close-btn:hover {
            color: red;
        }

    </style>
</head>

<body>
<header>
    <a href="#" class="logo"><span>DREAM COME TRUE ADMIN</span></a>
    <div class="dropdown">
        <button onclick="myFunction()" class="dropbtn"><strong>МЕНЮ</strong></button>
        <div id="myDropdown" class="dropdown-content">
            <a href="PonelAdmin.php">БРОНЬ ДОМОВ</a>
            <a href="PonelAdminHome.php">ДОМА</a>
            <a href="PonelAdminBath.php"><strong>БАНЯ</strong></a>
            <a href="PonelAdminTransfer.php">ТРАНСФЕР</a>
            <a href="PonelAdminRaiting.php">ОТЗЫВЫ</a>
        </div>
    </div>
</header>

<section id="bookings">
    <h1 align="center">Список забронированной бани</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Дата посещения</th>
                <th>Время посещения</th>
                <th>ФИО</th>
                <th>Количество гостей</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($visits as $visit): ?>
                <tr>
                    <td><?= htmlspecialchars($visit['id']) ?></td>
                    <td><?= htmlspecialchars($visit['visit_date']) ?></td>
                    <td><?= htmlspecialchars($visit['visit_time']) ?></td>
                    <td><?= htmlspecialchars($visit['full_name']) ?></td>
                    <td><?= htmlspecialchars($visit['people_count']) ?></td>
                    <td>
                        <form method="post" onsubmit="return confirm('Вы уверены, что хотите удалить это бронирование?');" style="display:inline;">
                            <input type="hidden" name="delete_id" value="<?= $visit['id'] ?>">
                            <button type="submit">Удалить</button>
                        </form>
                        <button onclick="openEditForm(<?= $visit['id'] ?>, '<?= $visit['visit_date'] ?>', '<?= $visit['visit_time'] ?>', '<?= $visit['full_name'] ?>', <?= $visit['people_count'] ?>)">Редактировать</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

<div id="editModal" class="modal">
<div class="modal-content">
    <h2>Редактирование бронирования</h2>
    <form method="post">
        <input type="hidden" name="edit_id" id="edit_id">
        <label>Дата посещения: <input type="date" name="visit_date" id="edit_visit_date" required></label><br>
        <label>Время посещения: <input type="time" name="visit_time" id="edit_visit_time" required></label><br>
        <label>ФИО: <input type="text" name="full_name" id="edit_full_name" required></label><br>
        <label>Количество гостей: <input type="number" name="people_count" id="edit_people_count" required></label><br>
        <button type="submit">Сохранить</button>
        <button type="button" onclick="closeEditForm()">Отмена</button>
    </form>
</div>
</div>

<footer>
    <p>&copy; 2025 Административная панель</p>
</footer>

<script>
    function myFunction() {
        document.getElementById("myDropdown").classList.toggle("show");
    }

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

    function openEditForm(id, date, time, name, count) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_visit_date').value = date;
        document.getElementById('edit_visit_time').value = time;
        document.getElementById('edit_full_name').value = name;
        document.getElementById('edit_people_count').value = count;
        document.getElementById('editModal').style.display = 'block';
    }

    function closeEditForm() {
        document.getElementById('editModal').style.display = 'none';
    }
</script>
</body>
</html>
