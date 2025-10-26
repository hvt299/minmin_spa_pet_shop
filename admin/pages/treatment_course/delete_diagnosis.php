<?php
session_start();
require_once(dirname(__DIR__, 2) . '/init.php');

if (!isset($_SESSION['username'])) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

require_once(APP_PATH . '/diagnosis_function.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if ($id > 0) {
        $diagnosis = getDiagnosisById($id);
        if ($diagnosis && deleteDiagnosis($id)) {
            header("Location: diagnoses.php?treatment_session_id=" . $diagnosis['treatment_session_id'] . "&success=1&msg=" . urlencode("Xóa chẩn đoán thành công!"));
            exit;
        } else {
            header("Location: diagnoses.php?error=1&msg=" . urlencode("Xóa thất bại!"));
            exit;
        }
    }
}

header("Location: diagnoses.php");
exit;