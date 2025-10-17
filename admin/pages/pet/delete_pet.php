<?php
session_start();
require_once(dirname(__DIR__, 2) . '/init.php');

if (!isset($_SESSION['username'])) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

require_once(APP_PATH . '/pet_function.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if ($id > 0) {
        if (deletePet($id)) {
            header("Location: pets.php?success=1&msg=" . urlencode("Xóa thú cưng thành công!"));
            exit;
        } else {
            header("Location: pets.php?error=1&msg=" . urlencode("Xóa thú cưng thất bại!"));
            exit;
        }
    }
}

header("Location: pets.php");
exit;
?>
