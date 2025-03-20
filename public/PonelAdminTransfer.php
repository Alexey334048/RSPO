<?php
// Подключение к базе данных
$host = 'MySQL-8.2'; // хост
$dbname = 'dream'; // имя базы данных
$username = 'root'; // имя пользователя
$password = ''; // пароль (если есть)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}

// Удаление заказа трансфера
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = (int)$_POST['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM Transfers WHERE transfer_id = ?");
    $stmt->execute([$delete_id]);
    header("Location: PonelAdminTransfer.php");
    exit;
}

// Редактирование заказа трансфера
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {
    $edit_id = (int)$_POST['edit_id'];
    $transfer_date = htmlspecialchars($_POST['transfer_date']);
    $transfer_time = htmlspecialchars($_POST['transfer_time']);
    $full_name = htmlspecialchars($_POST['full_name']);
    $address = htmlspecialchars($_POST['address']);
    
    $stmt = $pdo->prepare("UPDATE Transfers SET transfer_date = ?, transfer_time = ?, full_name = ?, address = ? WHERE transfer_id = ?");
    $stmt->execute([$transfer_date, $transfer_time, $full_name, $address, $edit_id]);
    header("Location: PonelAdminTransfer.php");
    exit;
}

// Получение данных из таблицы Transfers
$sql = "SELECT * FROM Transfers"; 
$stmt = $pdo->query($sql);
$Transfers = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                <a href="PonelAdminBath.php">БАНЯ</a>
                <a href="PonelAdminTransfer.php"><strong>ТРАНСФЕР</strong></a>
                <a href="PonelAdminRaiting.php">ОТЗЫВЫ</a>
            </div>
        </div>
    </header>

    <section id="bookings">
        <h1 align="center">Список заказов трансфера</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Дата доставки</th>
                    <th>Время доставки</th>
                    <th>ФИО</th>
                    <th>Адрес</th>
                    <th>Дата заказа</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($Transfers as $transfer): ?>
                    <tr>
                        <td><?= htmlspecialchars($transfer['transfer_id']) ?></td>
                        <td><?= htmlspecialchars($transfer['transfer_date']) ?></td>
                        <td><?= htmlspecialchars($transfer['transfer_time']) ?></td>
                        <td><?= htmlspecialchars($transfer['full_name']) ?></td>
                        <td><?= htmlspecialchars($transfer['address']) ?></td>
                        <td><?= htmlspecialchars($transfer['created_at']) ?></td>
                        <td>
                        <!-- Кнопка удаления -->
                        <form method="post" onsubmit="return confirm('Вы уверены, что хотите удалить этот заказ?');" style="display:inline;">
                            <input type="hidden" name="delete_id" value="<?= $transfer['transfer_id'] ?>">
                            <button type="submit">Удалить</button>
                        </form>
                        <!-- Кнопка редактирования -->
                        <button onclick="openEditForm(<?= $transfer['transfer_id'] ?>, '<?= $transfer['transfer_date'] ?>', '<?= $transfer['transfer_time'] ?>', '<?= $transfer['full_name'] ?>', '<?= $transfer['address'] ?>')">Редактировать</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
    <!-- Модальное окно редактирования -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <h2>Редактирование заказа трансфера</h2>
        <form method="post">
            <input type="hidden" name="edit_id" id="edit_id">
            <label>Дата доставки: <input type="date" name="transfer_date" id="edit_transfer_date" required></label><br>
            <label>Время доставки: <input type="time" name="transfer_time" id="edit_transfer_time" required></label><br>
            <label>ФИО: <input type="text" name="full_name" id="edit_full_name" required></label><br>
            <label>Адрес: <input type="text" name="address" id="edit_address" required></label><br>
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

    // Открытие модального окна редактирования
    function openEditForm(id, date, time, name, address) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_transfer_date').value = date;
        document.getElementById('edit_transfer_time').value = time;
        document.getElementById('edit_full_name').value = name;
        document.getElementById('edit_address').value = address;
        document.getElementById('editModal').style.display = 'block';
    }

    // Закрытие модального окна
    function closeEditForm() {
        document.getElementById('editModal').style.display = 'none';
    }

// Menue
window.onclick = function(event) {
  if (!event.target.matches('.dropbtn')) {

    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}
    </script>
</body>
</html>