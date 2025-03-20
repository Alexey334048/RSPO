<?php
// Подключение к базе данных
$host = 'MySQL-8.2'; // Хост (обычно localhost)
$dbname = 'dream'; // Имя базы данных
$username = 'root'; // Имя пользователя
$password = ''; // Пароль (если есть)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}

// Обработка отправки формы
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fio = trim($_POST["FIO"]);
    $description = trim($_POST["Description"]);
    $rating = isset($_POST["rating"]) ? (int)$_POST["rating"] : 0;

    if ($rating >= 1 && $rating <= 5 && !empty($fio) && !empty($description)) {
        $stmt = $pdo->prepare("INSERT INTO ratings (FIO, Description, rating, status) VALUES (?, ?, ?, 'pending')");
        if ($stmt->execute([$fio, $description, $rating])) {
            $message = "Спасибо! Ваш отзыв будет опубликован после проверки.";
        } else {
            $message = "Ошибка при сохранении отзыва.";
        }
    } else {
        $message = "Проверьте правильность введённых данных.";
    }
}

// Получение среднего рейтинга
$stmt = $pdo->query("SELECT AVG(rating) as avg_rating FROM ratings");
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$average = round($row['avg_rating'], 1);

// Получение списка всех отзывов
// Получение списка одобренных отзывов
$reviews = $pdo->query("SELECT * FROM ratings WHERE status = 'approved' ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DREAM COME TRUE</title>
    <link rel="stylesheet" href="/style.css">
    <link rel="menu" href="/menu.html">
    <!-- fontawesone link -->
    <script src="https://kit.fontawesome.com/467b90b200.js" crossorigin="anonymous"></script>
    <!-- font link -->
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
</head>

<body>
    <!-- header section start -->
    <header>
        <a href="/index.php" class="logo"><span>DREAM COME TRUE</span></a>
        <a href="/menu.html" class="fas fa-bars"></a>

    </header>
    <!-- header section end -->

    <!-- home selection start -->
    <section class="home">
        <div class="content">
            <h1>DREAM COME TRUE</h1>
            <h2>АРЕНДА КРАСИВЫХ ДОМОВ НА ОЗЕРЕ</h2>
        </div>

    </section>
    <!-- home selection end -->

    <!-- our house start -->
    <section class="hp-room-section">
        <h2 class="heading">НАШИ ДОМА</h2>
        <h3 class="heade">ВЫБЕРИТЕ ДОМ ПО СВОЕМУ ВКУСУ</h3>
        <div class="container-fluid">
            <div class="hp-room-items">
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <div class="hp-room-item set-bg">
                            <img src="/img/Home7.jpg" alt="">
                            <div class="hr-text">
                                <h3>ТРЕУГОЛЬНАЯ ГАВАНЬ</h3>
                                <h2>199$<span>/Ночь</span></h2>
                                <table>
                                    <tbody>
                                        <tr>
                                            <td class="r-o">Вмещаемость:</td>
                                            <td>Макс человек 5</td>
                                        </tr>
                                        <tr>
                                            <td class="r-o">Кровать:</td>
                                            <td>Кровать размера «king-size»</td>
                                        </tr>
                                        <tr>
                                            <td class="r-o">Сервисы:</td>
                                            <td>Wifi, Теловизор, Ванна,...</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <a href="house.php" class="primary-btn">Подробнее</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="hp-room-item set-bg" data-setbg="img/room/room-b2.jpg">
                            <img src="/img/home8.jpg" alt="">
                            <div class="hr-text">
                                <h3>ДОМ ПОД УГЛОМ</h3>
                                <h2>159$<span>/Ночь</span></h2>
                                <table>
                                    <tbody>
                                        <tr>
                                            <td class="r-o">Вмещаемость:</td>
                                            <td>Макс человек 5</td>
                                        </tr>
                                        <tr>
                                            <td class="r-o">Кровать:</td>
                                            <td>Кровать размера «king-size»</td>
                                        </tr>
                                        <tr>
                                            <td class="r-o">Сервисы:</td>
                                            <td>Wifi, Теловизор, Ванна,...</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <a href="house.php" class="primary-btn">Подробнее</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="hp-room-item set-bg" data-setbg="img/room/room-b3.jpg">
                            <img src="/img/home122.jpg" alt="">
                            <div class="hr-text">
                                <h3>ПИРАМИДАЛЬНЫЙ РЕТРИТ</h3>
                                <h2>198$<span>/Ночь</span></h2>
                                <table>
                                    <tbody>
                                        <tr>
                                            <td class="r-o">Вмещаемость:</td>
                                            <td>Макс человек 5</td>
                                        </tr>
                                        <tr>
                                            <td class="r-o">Кровать:</td>
                                            <td>Кровать размера «king-size»</td>
                                        </tr>
                                        <tr>
                                            <td class="r-o">Сервисы:</td>
                                            <td>Wifi, Телевизор, Ванна,...</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <a href="house.php" class="primary-btn">Подробнее</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button onclick="window.location.href='house.php'">Забронировать дом</button>
    </section>
    <!-- our house end -->

    <!-- Description of houses -->
    <div class="cont">
        <div class="gallery">
            <img src="/img/image 7.png" alt="Interior 1" class="image highlighted">
            <img src="/img/image 9.png" alt="Interior 2" class="image highlighted">
            <img src="/img/image 11.png" alt="Interior 3" class="image highlighted">
            <img src="/img/image 6.png" alt="Interior 4" class="image highlighted">
        </div>
        <div class="text-content">
            <h2 class="heading">КОМФОРТНЫЙ <br>И СТИЛЬНЫЙ ДОМАШНИЙ ИНТЕРЬЕР</h2>
            <h3 class="heade">Натуральное дерево создает уют, а панорамное окно открывает вид на природу прямо из дома. Современная мебель и техника обеспечивают комфорт и удобство.</h3>

        </div>
    </div>
    <!-- Description of houses end -->

    <div class="con">
        <div class="cont2">
            <h2 class="heading">РАЗВЛЕЧЕНИЕ</h2>
            <div class="gallery2">
                <div class="image">
                    <img src="/img/image 44.png" alt="Paddleboard">
                </div>
                <div class="image">
                    <img src="/img/image 45.png" alt="Hot Tub">
                </div>
                <div class="image">
                    <img src="/img/image 46.png" alt="Playground">
                </div>
                <div class="image">
                    <img src="/img/image 47.png" alt="Meerkats">
                </div>
            </div>
        </div>
    </div>


    <!-- ////////////////////////////// -->


    <div class="contant1">
        <h2 class="heading">УСЛОВИЯ АРЕНДЫ</h2>
        <div class="conditions">
            <div class="condition">
                <h2>ДОГОВОР АРЕНДЫ</h2>
                <p>Регистрация заезда с предоставлением паспортных данных и подписанием договора.</p>
            </div>
            <div class="condition">
                <h2>ВРЕМЯ ЗАЕЗДА/ВЫЕЗДА</h2>
                <p>Выезд - 12.00, заезд - 14.00; поздний выезд обсуждается в зависимости от наличия возможности; время работы сауны обсуждается индивидуально.</p>
            </div>
            <div class="condition">
                <h2>УСЛОВИЯ УБОКИ</h2>
                <p>Уборка включена в стоимость, при проживании более 3 дней включена смена постельного белья.</p>
            </div>
            <div class="condition">
                <h2>ТРЕБОВАНИЯ К ДЕПОЗИТУ</h2>
                <p>500 BYN до начала срока аренды, возвращается по окончании срока аренды, если все в порядке.</p>
            </div>
            <div class="condition">
                <h2>ПОЛИТИКА В ОТНОШЕНИИ ЖИВОТНЫХ</h2>
                <p>Разрешено проживание с домашними животными весом до 3 кг. Уборка облагается дополнительной платой в размере 50 BYN.</p>
            </div>
            <div class="condition">
                <h2>КОЛИЧЕСТВО ЛЮДЕЙ</h2>
                <p>В стоимость включено проживание не более 1 человека, не считая детей. Каждый последующий человек +125 BYN, максимум 5 человек.</p>
            </div>
        </div>
        <div class="amenities">
            <h2 class="heading">В КАЖДОМ ДОМЕ</h2>
            <div class="icons">
                <div class="icon">
                    <img src="img/free-icon-air-conditioner-1530297.png" alt="Air conditioner">
                    <p>Кондиционер</p>
                </div>
                <div class="icon">
                    <img src="img/free-icon-ventilation-2758315.png" alt="Supply ventilation">
                    <p>Приточная вентиляция</p>
                </div>
                <div class="icon">
                    <img src="img/free-icon-fridge-4352967.png" alt="Fridge">
                    <p>Холодильник</p>
                </div>
                <div class="icon">
                    <img src="img/free-icon-grill-114873.png" alt="Barbecue area">
                    <p>Зона барбекю</p>
                </div>
                <div class="icon">
                    <img src="img/free-icon-hair-dryer-5501590.png" alt="Hair dryer">
                    <p>Фен</p>
                </div>
                <div class="icon">
                    <img src="img/free-icon-heating-7107417.png" alt="Heated towel rail">
                    <p>Полотенцесушитель</p>
                </div>
                <div class="icon">
                    <img src="img/free-icon-kitchen-7232900.png" alt="Kitchen">
                    <p>Кухня</p>
                </div>
                <div class="icon">
                    <img src="img/free-icon-wifi-448891.png" alt="Wi-Fi">
                    <p>Wi-Fi</p>
                </div>
            </div>
            <p class="additional">и другие удобства</p>
        </div>
    </div>


    <!-- Отзывы -->
    <div class="contant2">
    <h2 class="heading">ОТЗЫВЫ</h2>

<div class="reviews">
    <?php foreach ($reviews as $review): ?>
        <div class="review">
            <strong><?= htmlspecialchars($review['FIO']) ?></strong> (<?= $review['rating'] ?> ★)<br>
            <?= nl2br(htmlspecialchars($review['Description'])) ?>
        </div>
    <?php endforeach; ?>
</div>
    <h3>Средний рейтинг: <?= $average ? $average . " ★" : "Нет оценок" ?></h3>

<!-- Кнопка для открытия модального окна -->
<button class="btn" onclick="openModal()">Оставить отзыв</button>

<!-- Модальное окно -->
<div id="reviewModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Оставьте свою оценку</h2>
        <form method="POST">
            <h4>ФИО:</h4><br>
            <input type="text" name="FIO" required><br><br>

            <h4>Комментарий:</h4><br>
            <textarea name="Description" required></textarea><br><br>

            <div class="stars">
                <input type="radio" name="rating" id="star5" value="5"><label for="star5">★</label>
                <input type="radio" name="rating" id="star4" value="4"><label for="star4">★</label>
                <input type="radio" name="rating" id="star3" value="3"><label for="star3">★</label>
                <input type="radio" name="rating" id="star2" value="2"><label for="star2">★</label>
                <input type="radio" name="rating" id="star1" value="1"><label for="star1">★</label>
            </div><br>

            <button type="submit" class="btnn">Отправить</button>
        </form>
    </div>
</div>
</div>



<script>
    function openModal() {
        document.getElementById("reviewModal").style.display = "flex";
    }

    function closeModal() {
        document.getElementById("reviewModal").style.display = "none";
    }

    // Закрытие модального окна при клике вне него
    window.onclick = function(event) {
        let modal = document.getElementById("reviewModal");
        if (event.target == modal) {
            closeModal();
        }
    }
</script>

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
<style>

    .contant2{
        background-color: #1e1e1e;
    }
    .contant2 h3{
        color: #FBF0E4;
        font-size: 15px;
        padding-left: 40px;
    }
    .stars {
    display: flex;
    flex-direction: row-reverse;
    justify-content: center;
    font-size: 40px;
}

.stars input {
    display: none;
}

.stars label {
    cursor: pointer;
    color: gray;
}

.stars input:checked~label,
.stars label:hover,
.stars label:hover~label {
    color: gold;
}

.reviews {
    margin-top: 20px;
    text-align: left;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}

.review {
    border-bottom: 1px solid #ddd;
    padding: 10px;
}

form {
    background-color: #3D3D3D;
}

 /* Модальное окно */
 .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background-color: #3D3D3D;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            width: 90%;
            max-width: 400px;
        }
        .modal-content h2{
            color: #FBF0E4;
            font-size: 16px;
            padding-bottom: 40px;

        }

        .close {
            float: right;
            font-size: 28px;
            cursor: pointer;
        }
        .btn {
            background-color: #3d3d3d;
            color: #FBF0E4;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            margin: 40px 40px;
            border-radius: 5px;
        }
        .btnn{
            background-color: #1e1e1e;
            color: #FBF0E4;
            padding: 15px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .review{
            color: #FBF0E4;
        }
        h4{
            color: #FBF0E4;
            font-size: 14px;
        }
</style>
</html>