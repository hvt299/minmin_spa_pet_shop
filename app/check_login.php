<?php
session_start();
require '../config/database.php';

$username = trim($_POST['username']);
$password = trim($_POST['password']);

// Sử dụng MD5 để so sánh với DB (demo)
$stmt = $conn->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
$stmt->execute([
    'username' => $username,
    'password' => md5($password)
]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $_SESSION['username'] = $user['username'];
    $_SESSION['fullname'] = $user['fullname'];
    $_SESSION['success'] = 'Đăng nhập thành công!';
    header("Location: ../admin/index.php");
    // header("Location: ../admin/pages/dashboard.php");
} else {
    $_SESSION['error'] = "Sai tên đăng nhập hoặc mật khẩu!";
    header("Location: ../admin/index.php");
}
exit;
