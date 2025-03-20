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

// Редактирование бронирования
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {
    $edit_id = (int)$_POST['edit_id'];
    $Name = htmlspecialchars($_POST['Name']);
    $Description = htmlspecialchars($_POST['Description']);;
    $Price = (int)$_POST['Price'];
    $Pice2 = (int)$_POST['Pice2'];
    $Pice3 = (int)$_POST['Pice3'];
    $Pice4 = (int)$_POST['Pice4'];

    $stmt = $pdo->prepare("UPDATE house SET Name = ?, Description = ?, Price = ?, Pice2 = ?, Pice3 = ?, Pice4 = ? WHERE id = ?");
    $stmt->execute([$Name, $Description, $Price, $Pice2, $Pice3, $Pice4]);
    header("Location: PonelAdminHome.php");
    exit;
}

// Получение данных из таблицы 
$sql = "SELECT * FROM house";
$stmt = $pdo->query($sql);
$house = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список домов</title>

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
            <a href="PonelAdminHome.php"><strong>ДОМА</strong></a>
            <a href="PonelAdminBath.php">БАНЯ</a>
            <a href="PonelAdminTransfer.php">ТРАНСФЕР</a>
            <a href="PonelAdminRaiting.php">ОТЗЫВЫ</a>
        </div>
    </div>
</header>

<section id="bookings">
    <h1>Список домов</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Наименование</th>
                <th>Описание</th>
                <th>Цена 1</th>
                <th>Цена 2</th>
                <th>Цена 3</th>
                <th>Цена 4</th>
                <th>Фото 1</th>
                <th>Фото 2</th>
                <th>Фото 3</th>
                <th>Фото 4</th>
                <th>Фото 5</th>
                <th>Фото 6</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($house as $houses): ?>
                <tr>
                    <td><?= htmlspecialchars($houses['id']) ?></td>
                    <td><?= htmlspecialchars($houses['Name']) ?></td>
                    <td><?= htmlspecialchars($houses['Description']) ?></td>
                    <td><?= htmlspecialchars($houses['Price']) ?></td>
                    <td><?= htmlspecialchars($houses['Pice2']) ?></td>
                    <td><?= htmlspecialchars($houses['Pice3']) ?></td>
                    <td><?= htmlspecialchars($houses['Pice4']) ?></td>

                    <td><button onclick="openEditForm(<?= $visit['id'] ?>, '<?= $visit['visit_date'] ?>', '<?= $visit['visit_time'] ?>', '<?= $visit['full_name'] ?>', <?= $visit['people_count'] ?>)">Редактировать</button></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

<div id="editModal" class="modal">
    <div class="modal-content">
        <h2>Редактирование дома</h2>
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="edit_id" id="edit_id">

            <label for="name">Наименование:</label>
            <input type="text" name="name" id="edit_name" required><br>

            <label for="description">Описание:</label>
            <textarea name="description" id="edit_description" required></textarea><br>

            <label for="price">Цена 1:</label>
            <input type="number" name="Price" id="edit_price" step="0.01" required><br>

            <label for="price2">Цена 2:</label>
            <input type="number" name="Pice2" id="edit_price2" step="0.01" required><br>

            <label for="price3">Цена 3:</label>
            <input type="number" name="Pice3" id="edit_price3" step="0.01" required><br>

            <label for="price4">Цена 4:</label>
            <input type="number" name="Pice4" id="edit_price4" step="0.01" required><br>

            <!-- Форма для редактирования фотографий -->
            <!-- <label>Фото 1:</label>
            <input type="file" name="foto1" id="edit_foto1"><br>

            <label>Фото 2:</label>
            <input type="file" name="foto2" id="edit_foto2"><br>

            <label>Фото 3:</label>
            <input type="file" name="foto3" id="edit_foto3"><br>

            <label>Фото 4:</label>
            <input type="file" name="foto4" id="edit_foto4"><br>

            <label>Фото 5:</label>
            <input type="file" name="foto5" id="edit_foto5"><br>

            <label>Фото 6:</label>
            <input type="file" name="foto6" id="edit_foto6"><br> -->

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

    function openEditForm(id, Name, Description, Price, Pice2, Pice3, Pice4) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_name').value = Name;
    document.getElementById('edit_description').value = Description;
    document.getElementById('edit_price').value = Price;
    document.getElementById('edit_price2').value = Pice2;
    document.getElementById('edit_price3').value = Pice3;
    document.getElementById('edit_price4').value = Pice4;

    // Открытие модального окна
    document.getElementById('editModal').style.display = 'block';
}

function closeEditForm() {
    // Закрытие модального окна
    document.getElementById('editModal').style.display = 'none';
}
</script>
</body>
</html>
