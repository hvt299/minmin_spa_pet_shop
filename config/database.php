<?php
$host = "localhost";
$dbname = "minmin_spa_pet_shop";
$user = "root";
$pass = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // 🕒 Thiết lập múi giờ Việt Nam cho MySQL
    $conn->exec("SET time_zone = '+07:00'");
} catch (PDOException $e) {
    die("Kết nối thất bại: " . $e->getMessage());
}
?>
