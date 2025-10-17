<?php
session_start();
require_once(dirname(__DIR__, 2) . '/init.php');

if (!isset($_SESSION['username'])) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

require_once(APP_PATH . '/invoice_function.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if ($id > 0) {

        if (deleteInvoice($id)) {
            header("Location: invoices.php?success=1&msg=" . urlencode("Xóa hóa đơn thành công!"));
            exit;
        } else {
            header("Location: invoices.php?error=1&msg=" . urlencode("Xóa hóa đơn thất bại!"));
            exit;
        }
    }
}

// Nếu không có id hợp lệ thì quay lại danh sách
header("Location: invoices.php");
exit;
