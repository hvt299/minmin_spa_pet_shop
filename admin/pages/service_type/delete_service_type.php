<?php
session_start();
require_once(dirname(__DIR__, 2) . '/init.php');

if (!isset($_SESSION['username'])) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

require_once(APP_PATH . '/service_type_function.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if ($id > 0) {
        if (deleteServiceType($id)) {
            header("Location: service_types.php?success=1&msg=" . urlencode("Xóa dịch vụ thành công!"));
            exit;
        } else {
            header("Location: service_types.php?error=1&msg=" . urlencode("Xóa dịch vụ thất bại!"));
            exit;
        }
    }
}

header("Location: service_types.php");
exit;
?>
