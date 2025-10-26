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
        $treatment_course = getTreatmentCourseById($id);
        if ($treatment_course) {
            $end_date = $treatment_course['end_date'] ?: date('Y-m-d');
            $result = updateTreatmentCourseStatus($id, $end_date);
            if ($result) {
                header("Location: treatment_courses.php?success=1&msg=" . urlencode("Đã hoàn tất đợt điều trị!"));
                exit;
            }
        }
    }
}

header("Location: treatment_courses.php?error=1&msg=" . urlencode("Không thể hoàn tất đợt điều trị!"));
exit;