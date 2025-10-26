<?php
session_start();
require_once(dirname(__DIR__, 2) . '/init.php');

if (!isset($_SESSION['username'])) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

require_once(APP_PATH . '/treatment_session_function.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if ($id > 0) {
        $treatment_session = getTreatmentSessionById($id);
        if ($treatment_session && deleteTreatmentSession($id)) {
            header("Location: treatment_sessions.php?treatment_course_id=" . $treatment_session['treatment_course_id'] . "&success=1&msg=" . urlencode("Xóa lần khám thành công!"));
            exit;
        } else {
            header("Location: treatment_sessions.php?error=1&msg=" . urlencode("Xóa thất bại!"));
            exit;
        }
    }
}

header("Location: treatment_sessions.php");
exit;