<?php
$host = 'MySQL-8.2';
$db = 'dream';
$user = 'root';
$pass = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}

$houseId = isset($_GET['id']) ? (int) $_GET['id'] : 1;

$query = "SELECT * FROM house WHERE id = :id LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id', $houseId, PDO::PARAM_INT);
$stmt->execute();
$house = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$house) {
    die("Дом не найден.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $checkin_date = $_POST['checkin_date'] ?? null;
    $nights = $_POST['nights'] ?? null;
    $guests = $_POST['guests'] ?? null;
    $total_price = $_POST['total_price'] ?? null;

    if (!$checkin_date || !$nights || !$guests || !$total_price) {
        die("Ошибка: не все данные переданы.");
    }

    $stmt = $conn->prepare("INSERT INTO bookings (house_id, first_name, last_name, email, checkin_date, nights, guests, total_price) 
                            VALUES (:house_id, :first_name, :last_name, :email, :checkin_date, :nights, :guests, :total_price)");
    $stmt->execute([
        'house_id' => $_POST['house_id'],
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
        'email' => $_POST['email'],
        'checkin_date' => $checkin_date,
        'nights' => $nights,
        'guests' => $guests,
        'total_price' => $total_price
    ]);
    echo "Бронирование успешно!";
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/booking.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/467b90b200.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <title>Booking</title>
    <script>
        function updateTotal() {
            let basePrice = <?= (int) $house['Price'] ?>;
            let totalPrice = basePrice;
            
            if (document.getElementById('pets').checked) {
                totalPrice += 50;
            }
            if (document.getElementById('bathrobes').checked) {
                totalPrice += 100;
            }
            
            document.getElementById('total-price').innerText = totalPrice + ' р';
            document.getElementById('total_price_input').value = totalPrice;
        }

        document.addEventListener("DOMContentLoaded", function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById("checkin_date").setAttribute("min", today);
        });

    function redirectToPayment() {
        let totalPrice = document.getElementById('total_price_input').value;
        window.location.href = "payment.php?amount=" + totalPrice;
    }

    function submitForm(event) {
        event.preventDefault(); // предотвращает обновление страницы
        
        let formData = new FormData(document.getElementById('booking-form'));

        fetch('', {  // Оставьте пустым или укажите PHP-обработчик, например, "process_booking.php"
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            alert("Бронирование успешно!");  // Покажет уведомление об успешном бронировании
        })
        .catch(error => console.error('Ошибка:', error));
    }

    function redirectToPayment() {
        let totalPrice = document.getElementById('total_price_input').value;
        window.location.href = "payment.php?amount=" + totalPrice;
    }

    </script>
</head>
<body>
<div class="back">
    <!-- header section start -->
    <header>
        <a href="/index.php" class="logo"><span>DREAM COME TRUE</span></a>
        <a href="/menu.html" class="fas fa-bars"></a>
    </header>
    <!-- header section end -->

    <div class="container6">
        <book>
        <h2 class="heading">ОНЛАЙН РЕСЕПШН</h2>
        </book>
        <br>
        <section class="booking-details">
        
            <div class="room-info">
                <h2>Дом №<?= htmlspecialchars($house['id']) ?></h2>
                <p><?= htmlspecialchars($house['Name']) ?></p>
                <p><?= htmlspecialchars($house['Price']) ?> BYN</p>
            
                <div class="options">
                <label>
                    <input type="checkbox" id="pets" onclick="updateTotal()"> 🐾 С домашними животными <strong>50 BYN</strong>
                </label>
                <label>
                    <input type="checkbox" id="crib" onclick="updateTotal()"> 🛏️ Нужна детская кроватка <strong>Free</strong>
                </label>
                <label>
                    <input type="checkbox" id="bathrobes" onclick="updateTotal()"> 🛁 Халаты <strong>100 BYN</strong>
                </label>
                </div>
            </div>

            <div class="price-summary">
                <p>Проживание: <strong><?= htmlspecialchars($house['Price']) ?> BYN</strong></p>
                <p>Итого: <strong id="total-price"><?= htmlspecialchars($house['Price']) ?> BYN</strong></p>
            </div>

            <div class="policy">
            <p><strong>Гарантия бронирования:</strong> Для подтверждения бронирования требуется оплата полной суммы.</p>
            <p><strong>Отмена бронирования:</strong> Бронирование можно отменить без штрафных санкций за 7 дней до прибытия. Менее чем за 7 дней до прибытия взимается штраф в размере полной суммы.</p>
            </div>
        </section>
        <br>

    
        <aside>
            <div class="rules">
                <h3>Пожалуйста, ознакомьтесь с правилами пребывания на территории.</h3>
                <ul>
                <li><a href="publicOffer.html">Публичная оферта</a></li> 
                <li><a href="PrivacyPolicy.html">Политика конфиденциальности</a></li> 
                <li><a href="conditions.html">Условия бронирования, оплаты и отмены</a></li>
                </ul>
            </div>
            <div class="rules">
            <h1>Клиент</h1>
            <form id="booking-form" onsubmit="submitForm(event)">
    <input type="hidden" name="house_id" value="<?= $houseId ?>">
    <input type="hidden" id="total_price_input" name="total_price" value="<?= htmlspecialchars($house['Price']) ?>">

    <label>Дата прибытия</label>
    <br>
    <input type="date" name="checkin_date" id="checkin_date" required>
    <br>
    <label>Количество ночей</label>
    <br>
    <input type="number" name="nights" min="1" required>
    <br>
    <label>Имя</label>
    <br>
    <input type="text" name="first_name" required>
    <br>
    <label>Фамилия</label>
    <br>
    <input type="text" name="last_name" required>
    <br>
    <label>Email</label>
    <br>
    <input type="email" name="email" required>
    <br>
    <label>Количество гостей</label>
    <br>
    <input type="number" name="guests" min="1" max="10" required>
    
    <br>
    <button type="submit">Забронировать</button>
    <br>
    <button onclick="redirectToPayment()" class="book-button">Оплатить бронирование</button>
        </section>
        </form>
    </div>
    </div>
    
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

:root {
    --orange: #FFDAB2;
}

*::selection {
    background-color: var(--orange);
    color: #fff;
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

input { 
    border-radius: 10px;
    padding: 5px;
    border: 1px solid #ccc;
}

.back {
    background-color: #1E1E1E;
}

.search-bar {
    display: flex;
    gap: 10px;
}

.search-bar input,
.search-bar button {
    padding: 10px;
    border: none;
    border-radius: 5px;
}

.search-bar input {
    background-color: #d3b8a5;
    color: #1e1e1e;
}

.search-bar button {
    background-color: #FFDAB2;
    color: #fff;
    cursor: pointer;
}

.book {
    margin-top: 20px;
    display: flex;
    gap: 20px;
}

.booking-details {
    flex: 2;
    background-color: #d3b8a5;
    padding: 20px;
    border-radius: 10px;
    font-size: 14px;
}

.dates {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
}

.room-info {
    margin-bottom: 20px;
}

.options {
    display: flex;
    gap: 20px;
}

.price-summary {
    margin-bottom: 20px;
}

.policy {
    font-size: 10px;
}

aside {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.rules,
.customer-form {
    background-color: #d3b8a5;
    padding: 20px;
    border-radius: 10px;
}

.customer-form form {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.customer-form input,
.customer-form button {
    padding: 10px;
    border: none;
    border-radius: 5px;
}

.customer-form button {
    background-color: #444;
    color: #fff;
    cursor: pointer;
}

.agreement {
    display: flex;
    align-items: center;
    gap: 10px;
}

.checkin-label {
    font-size: 15px;
    color: #1E1E1E;
}

.check-label {
    font-size: 15px;
    color: #1E1E1E;
}

button{
    background-color: #1E1E1E;
    border: none;
    border-radius: 10px;
    color: white;
    padding: 10px 20px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.book-button {
    background-color: #D8BFAA;
    border: none;
    border-radius: 10px;
    color: white;
    padding: 10px 20px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.book-button:hover {
    background-color: #C9AD9F;
}
    </style>
</body>
</html>
