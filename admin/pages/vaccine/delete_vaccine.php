<?php
session_start();
require_once(dirname(__DIR__, 2) . '/init.php');

if (!isset($_SESSION['username'])) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

require_once(APP_PATH . '/vaccine_function.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if ($id > 0) {
        if (deleteVaccine($id)) {
            header("Location: vaccines.php?success=1&msg=" . urlencode("Xóa vaccine thành công!"));
            exit;
        } else {
            header("Location: vaccines.php?error=1&msg=" . urlencode("Xóa vaccine thất bại!"));
            exit;
        }
    }
}

header("Location: vaccines.php");
exit;
?>