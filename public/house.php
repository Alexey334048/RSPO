<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <!-- <link rel="stylesheet" href="/house.css"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- fontawesone link -->
    <script src="https://kit.fontawesome.com/467b90b200.js" crossorigin="anonymous"></script>
    <!-- font link -->
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <title>Houses</title>
</head>

<body>
    <!-- header section start -->
    <header>
        <a href="/index.php" class="logo"><span>DREAM COME TRUE</span></a>
        <a href="/menu.html" class="fas fa-bars"></a>

    </header>
    <!-- header section end -->


    <div class="container4">
    <h2 class="heading">КРАСИВЫЕ ДОМА</h2>
    <div class="gallery">
        <div class="image-container">
            <img src="img/home1.jpg" alt="Triangular Haven">
            <a href="/Homes.php?id=1" class="title">ТРЕУГОЛЬНАЯ ГАВАНЬ</a>
        </div>
        <div class="image-container">
            <img src="img/home2.jpg" alt="Angle House">
            <a href="/Homes.php?id=2" class="title">УГЛОВОЙ ДОМ</a>
        </div>
        <div class="image-container">
            <img src="img/home3.jpg" alt="Pyramid Retreat">
            <a href="/Homes.php?id=3" class="title">ОТСТУПЛЕНИЕ ПИРАМИДЫ</a>
        </div>
        <div class="image-container">
            <img src="img/home4.jpg" alt="Peak Cottage">
            <a href="/Homes.php?id=4" class="title">КОТТЕДЖ «ПИК»</a>
        </div>
        <div class="image-container">
            <img src="img/home5.jpg" alt="Geometric Getaway">
            <a href="/Homes.php?id=5" class="title">ГЕОМЕТРИЧЕСКИЙ ОТДЫХ</a>
        </div>
        <div class="image-container">
            <img src="img/home6.jpg" alt="Apex Abode">
            <a href="/Homes.php?id=6" class="title">ОБИТЕЛЬ ВЕРШИНЫ</a>
        </div>
        <div class="image-container">
            <img src="img/home7.jpg" alt="Triangular Haven">
            <a href="/Homes.php?id=7" class="title">ТРЕУГОЛЬНАЯ ГАВАНЬ</a>
        </div>
        <div class="image-container">
            <img src="img/home8.jpg" alt="Vertex Villa">
            <a href="/Homes.php?id=8" class="title">ВИЛЛА ВЕРТЕКС</a>
        </div>
    </div>
</div>


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
    padding-top: 5rem;
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

.container4 {
    background-color: #1E1E1E;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.gallery {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
}

.image-container {
    width: 250px;
    height: 250px;
    margin: 20px;
    position: relative;
    overflow: hidden;
}

.image-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.title {
    position: absolute;
    bottom: 10px;
    left: 10px;
    background-color: rgba(0, 0, 0, 0.5);
    padding: 5px 10px;
    color: #FBF0E4;
    font-weight: bold;
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


/* media queries */

@media (max-width: 50000px) {
    html {
        font-size: 55%;
    }
    header .fa-bars {
        display: block;
    }
}
</style>
</html>