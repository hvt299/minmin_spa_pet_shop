<?php
session_start();
require_once(dirname(__DIR__, 2) . '/init.php');

if (!isset($_SESSION['username'])) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

require_once(APP_PATH . '/treatment_course_function.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if ($id > 0) {
        if (deleteTreatmentCourse($id)) {
            header("Location: treatment_courses.php?success=1&msg=" . urlencode("Xóa đợt khám thành công!"));
            exit;
        } else {
            header("Location: treatment_courses.php?error=1&msg=" . urlencode("Xóa đợt khám thất bại!"));
            exit;
        }
    }
}

// Nếu không có id hợp lệ thì quay lại danh sách
header("Location: treatment_courses.php");
exit;
?>