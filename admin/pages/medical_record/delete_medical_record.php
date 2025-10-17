<?php
session_start();
require_once(dirname(__DIR__, 2) . '/init.php');

if (!isset($_SESSION['username'])) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

require_once(APP_PATH . '/medical_record_function.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if ($id > 0) {
        if (deleteMedicalRecord($id)) {
            header("Location: medical_records.php?success=1&msg=" . urlencode("Xóa hồ sơ khám bệnh thành công!"));
            exit;
        } else {
            header("Location: medical_records.php?error=1&msg=" . urlencode("Xóa hồ sơ thất bại!"));
            exit;
        }
    }
}

// Nếu không có id hợp lệ thì quay lại danh sách
header("Location: medical_records.php");
exit;
