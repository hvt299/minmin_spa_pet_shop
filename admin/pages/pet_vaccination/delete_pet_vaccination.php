<?php
session_start();
require_once(dirname(__DIR__, 2) . '/init.php');

if (!isset($_SESSION['username'])) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

require_once(APP_PATH . '/pet_vaccination_function.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if ($id > 0) {
        if (deletePetVaccination($id)) {
            header("Location: pet_vaccinations.php?success=3&msg=" . urlencode("Xóa lịch tiêm vaccine thành công!"));
            exit;
        } else {
            header("Location: pet_vaccinations.php?error=1&msg=" . urlencode("Xóa lịch tiêm vaccine thất bại!"));
            exit;
        }
    }
}

header("Location: pet_vaccinations.php");
exit;
?>