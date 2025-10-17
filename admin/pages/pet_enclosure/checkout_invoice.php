<?php
session_start();
require_once(dirname(__DIR__, 2) . '/init.php');

if (!isset($_SESSION['username'])) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

require_once(APP_PATH . '/pet_enclosure_function.php');

// Lấy id chuồng thú
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if ($id > 0) {
        $enclosure = getPetEnclosureById($id);

        if ($enclosure) {
            $check_out_date = $enclosure['check_out_date'];

            // Nếu chưa có check_out_date -> lấy thời gian hiện tại (GMT+7)
            if (empty($check_out_date) || $check_out_date === '0000-00-00 00:00:00') {
                date_default_timezone_set('Asia/Ho_Chi_Minh');
                $check_out_date = date("Y-m-d H:i:s");
            }

            // Cập nhật trạng thái + check_out_date
            if (updatePetEnclosureCheckOut($id, $check_out_date)) {
                // Sau khi checkout thành công -> chuyển qua tạo hóa đơn
                header("Location: " . ADMIN_URL . "/pages/invoice/add_invoice.php?enclosure_id=" . $id . "&msg=" . urlencode("Checkout thành công!") . "&success=1");
                exit;
            } else {
                header("Location: pet_enclosures.php?error=1&msg=" . urlencode("Checkout thất bại!"));
                exit;
            }
        }
    }
}

// Nếu không có id hợp lệ thì quay lại danh sách
header("Location: pet_enclosures.php?error=1&msg=" . urlencode("ID chuồng không hợp lệ!"));
exit;