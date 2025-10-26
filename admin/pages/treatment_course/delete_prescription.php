<?php
session_start();
require_once(dirname(__DIR__, 2) . '/init.php');

if (!isset($_SESSION['username'])) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

require_once(APP_PATH . '/prescription_function.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if ($id > 0) {
        $prescription = getPrescriptionById($id);
        if ($prescription && deletePrescription($id)) {
            header("Location: prescriptions.php?treatment_session_id=" . $prescription['treatment_session_id'] . "&success=1&msg=" . urlencode("Xóa toa thuốc thành công!"));
            exit;
        } else {
            header("Location: prescriptions.php?error=1&msg=" . urlencode("Xóa thất bại!"));
            exit;
        }
    }
}

header("Location: prescriptions.php");
exit;