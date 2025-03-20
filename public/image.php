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

if (isset($_GET['id']) && isset($_GET['foto'])) {
    $id = intval($_GET['id']);
    $fotoColumn = preg_replace("/[^a-zA-Z0-9]/", "", $_GET['foto']); // Безопасность от SQL-инъекций

    if (in_array($fotoColumn, ['Foto1', 'Foto2', 'Foto3', 'Foto4', 'Foto5', 'Foto6'])) {
        $sql = "SELECT $fotoColumn FROM house WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $house = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($house && !empty($house[$fotoColumn])) {
            header("Content-Type: image/jpeg");
            echo $house[$fotoColumn];
        } else {
            header("Content-Type: image/png");
            readfile("no-image.png");
        }
    } else {
        echo "Некорректный запрос.";
    }
} else {
    echo "Недостаточно данных.";
}
?>
