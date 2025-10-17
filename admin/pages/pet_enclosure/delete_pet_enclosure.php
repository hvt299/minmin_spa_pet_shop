<?php
session_start();
require_once(dirname(__DIR__, 2) . '/init.php');

if (!isset($_SESSION['username'])) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

require_once(APP_PATH . '/pet_enclosure_function.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if ($id > 0) {
        if (deletePetEnclosure($id)) {
            header("Location: pet_enclosures.php?success=1&msg=" . urlencode("Xóa chuồng thành công!"));
            exit;
        } else {
            header("Location: pet_enclosures.php?error=1&msg=" . urlencode("Xóa chuồng thất bại!"));
            exit;
        }
    }
}

// Nếu không có id hợp lệ thì quay lại danh sách
header("Location: pet_enclosures.php");
exit;
