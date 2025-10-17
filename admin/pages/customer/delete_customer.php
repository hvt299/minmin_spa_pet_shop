<?php
session_start();
require_once(dirname(__DIR__, 2) . '/init.php');

if (!isset($_SESSION['username'])) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}
require_once(APP_PATH . '/customer_function.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if ($id > 0) {
        if (deleteCustomer($id)) {
            header("Location: customers.php?success=1&msg=" . urlencode("Xóa khách hàng thành công!"));
            exit;
        } else {
            header("Location: customers.php?error=1&msg=" . urlencode("Xóa khách hàng thất bại!"));
            exit;
        }
    }
}
header("Location: customers.php");
exit;
?>
