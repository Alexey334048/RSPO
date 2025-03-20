<?php
$host = 'MySQL-8.2';
$db = 'dream';
$user = 'root';
$pass = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}

// Получаем id дома из URL (если он передан)
$houseId = isset($_GET['id']) ? (int) $_GET['id'] : 1; // По умолчанию 1, если id не передан

// Запрос для получения данных о доме по id
$query = "SELECT * FROM house WHERE id = :id LIMIT 1"; // Предполагается, что у каждого дома уникальный id
$stmt = $conn->prepare($query);
$stmt->bindParam(':id', $houseId, PDO::PARAM_INT);
$stmt->execute();
$house = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$house) {
    die("Дом не найден.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Homes.css">
    <title>Home</title>
    <script src="https://kit.fontawesome.com/467b90b200.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
</head>

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
        padding-top: 3rem;
        font-weight: 100;
    }
    
    .heade {
        color: #FFDAB2;
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
    
    .heading1 {
        font-size: 4rem;
        color: #FFDAB2;
        text-align: center;
        font-weight: 100;
    }
    
    .in {
        background-color: #1e1e1e;
        padding: 20px;
    }
    
    .intro p {
        text-align: center;
        margin: 10px 0;
        line-height: 1.5;
        font-size: 15px;
        color: #FBF0E4;
        width: 100%;
        justify-content: center;
    }
    
    .pricing {
        display: flex;
        justify-content: space-between;
        margin: 20px 0;
    }
    
    .price-box {
        background-color: #444;
        padding: 20px;
        border-radius: 10px;
        width: 45%;
        text-align: center;
        color: #FBF0E4;
        font-size: 15px;
    }
    
    .calendar {
        width: 45%;
    }
    
    .calendar table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .calendar th,
    .calendar td {
        text-align: center;
        padding: 10px;
        border: 1px solid #555;
    }
    
    .calendar .booked {
        background-color: #ff6b6b;
    }
    
    .calendar .free {
        background-color: #6bff6b;
    }
    
    .legend {
        margin-top: 10px;
        display: flex;
        justify-content: space-between;
    }
    
    .book-button {
        display: block;
        margin: 20px auto;
        padding: 10px 20px;
        background-color: #444;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    
    .galleryi {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-top: 20px;
    }
    
    .galleryi img {
        width: 100%;
        border-radius: 10px;
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

.brand,
.contacts {
    margin: 20px;
    text-align: left;
}

.links {
    margin: 10px;
    text-align: right;
}

.brand h2 {
    color: #FFDAB2;
    font-size: 20px;
    margin-bottom: 5px;
    font-weight: 100;
}

.brand p {
    font-size: 10px;
}

.contacts p strong {
    color: #FFDAB2;
    font-weight: 100;
}

.contacts p,
.links a {
    font-size: 14px;
    color: #FBF0E4;
    text-decoration: none;
}

.social-icons .icon-btn {
    background-color: #FFDAB2;
    width: 30px;
    height: 30px;
    margin: 0 5px;
    border: none;
    border-radius: 60%;
}

.links a {
    display: block;
    margin: 5px 0;
    color: #FBF0E4;
}

.links a:hover {
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

<body>
    <header>
        <a href="/index.php" class="logo"><span>DREAM COME TRUE</span></a>
        <a href="/menu.html" class="fas fa-bars"></a>
    </header>

    <section class="in">
        <section class="intro">
            <h2 class="heading">ДОМ</h2>
            <h2 class="heading1"><?= htmlspecialchars($house['Name']) ?></h2>
            <p><?= nl2br(htmlspecialchars($house['Description'])) ?></p>
        </section>

        <section class="pricing">
            <div class="price-box">
                <p>Цена на 2 человек</p>
                <p>Будни - <?= htmlspecialchars($house['Price']) ?> BYN в день</p>
                <p>Пятница, Воскресенье - <?= htmlspecialchars($house['Pice2']) ?> BYN в день</p>
                <p>Суббота - <?= htmlspecialchars($house['Pice3']) ?> BYN</p>
                <p>2 дня - <?= htmlspecialchars($house['Pice4']) ?> BYN</p>
            </div>
        </section>

        <button class="book-button" onclick="window.location.href='booking.php?id=<?= $houseId ?>'">Забронировать дом</button>



        <section class="galleryi">
            <?php for ($i = 1; $i <= 6; $i++): ?>
                <?php
                $fotoKey = 'Foto' . $i;
                if (!empty($house[$fotoKey])): 
                    $imageData = base64_encode($house[$fotoKey]); // Конвертируем в base64
                    $imageSrc = "data:image/jpeg;base64," . $imageData; // Создаем data URL
                ?>
                    <img src="<?= $imageSrc ?>" alt="House Image">
                <?php endif; ?>
            <?php endfor; ?>
        </section>
    </section>


    <!-- footer -->
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
</body>

</html>