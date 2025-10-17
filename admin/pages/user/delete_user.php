<?php
session_start();
require_once(dirname(__DIR__, 2) . '/init.php');

if (!isset($_SESSION['username'])) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

require_once(APP_PATH . '/user_function.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if ($id > 0) {
        if (deleteUser($id)) {
            header("Location: users.php?success=4&msg=" . urlencode("Xóa người dùng thành công!"));
            exit;
        } else {
            header("Location: users.php?error=1&msg=" . urlencode("Xóa người dùng thất bại!"));
            exit;
        }
    }
}

header("Location: users.php");
exit;
?>
