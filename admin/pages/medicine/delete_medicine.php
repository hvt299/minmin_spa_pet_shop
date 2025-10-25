<?php
session_start();
require_once(dirname(__DIR__, 2) . '/init.php');

if (!isset($_SESSION['username'])) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

require_once(APP_PATH . '/medicine_function.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if ($id > 0) {
        if (deleteMedicine($id)) {
            header("Location: medicines.php?success=1&msg=" . urlencode("Xóa thuốc thành công!"));
            exit;
        } else {
            header("Location: medicines.php?error=1&msg=" . urlencode("Xóa thuốc thất bại!"));
            exit;
        }
    }
}

header("Location: medicines.php");
exit;
?>