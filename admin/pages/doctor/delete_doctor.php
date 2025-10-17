<?php
session_start();
require_once(dirname(__DIR__, 2) . '/init.php');

if (!isset($_SESSION['username'])) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

require_once(APP_PATH . '/doctor_function.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if ($id > 0) {
        if (deleteDoctor($id)) {
            header("Location: doctors.php?success=1&msg=" . urlencode("Xóa bác sĩ thành công!"));
            exit;
        } else {
            header("Location: doctors.php?error=1&msg=" . urlencode("Xóa bác sĩ thất bại!"));
            exit;
        }
    }
}
header("Location: doctors.php");
exit;
?>
