<?php
session_start();
require_once(dirname(__DIR__, 2) . '/init.php');

if (!isset($_SESSION['username'])) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

require_once(APP_PATH . '/invoice_function.php');
require_once(APP_PATH . '/pet_enclosure_function.php');
require_once(APP_PATH . '/service_type_function.php');
require_once(APP_PATH . '/customer_function.php');
require_once(APP_PATH . '/pet_function.php');
require_once(APP_PATH . '/general_setting_function.php');

$settings = getGeneralSettings();

if (isset($_GET['enclosure_id'])) {
    $enclosure_id = intval($_GET['enclosure_id']);
    $enclosure = getPetEnclosureById($enclosure_id);

    if (!$enclosure) {
        header("Location: invoices.php?error=1&msg=" . urlencode("Chuồng thú cưng không hợp lệ!"));
        exit;
    }

    $invoice_date = $enclosure['check_out_date'];
    $customer_id  = $enclosure['customer_id'];
    $pet_id       = $enclosure['pet_id'];
    $deposit      = $enclosure['deposit'];

    $invoice_id = addInvoice($customer_id, $pet_id, $enclosure_id, $invoice_date, 0, 0, $deposit, 0);
    if (!$invoice_id) {
        header("Location: invoices.php?error=1&msg=" . urlencode("Tạo hóa đơn thất bại!"));
        exit;
    }

    $serviceType = getServiceTypeByName("Lưu chuồng theo ngày");
    $service_type_id = $serviceType ? $serviceType['service_type_id'] : addServiceType("Lưu chuồng theo ngày", "");

    $checkIn  = new DateTime($enclosure['check_in_date']);
    $checkOut = new DateTime($enclosure['check_out_date']);
    $quantity = max(1, $checkIn->diff($checkOut)->days);
    $unit_price = $enclosure['daily_rate'];
    $total_price = $quantity * $unit_price;

    addInvoiceDetail($invoice_id, $service_type_id, $quantity, $unit_price, $total_price);

    $checkout_hour = $settings['checkout_hour'] ?: '18:00:00';
    $overtime_fee_per_hour = floatval($settings['overtime_fee_per_hour'] ?: 0);
    $checkout_time_actual = date('H:i:s', strtotime($enclosure['check_out_date']));

    if (strtotime($checkout_time_actual) > strtotime($checkout_hour)) {
        $hours_late = ceil((strtotime($checkout_time_actual) - strtotime($checkout_hour)) / 3600);
        $lateService = getServiceTypeByName("Phụ thu trễ giờ");
        $late_service_id = $lateService ? $lateService['service_type_id'] : addServiceType("Phụ thu trễ giờ", "");
        $late_total = $hours_late * $overtime_fee_per_hour;

        addInvoiceDetail($invoice_id, $late_service_id, $hours_late, $overtime_fee_per_hour, $late_total);
        $total_price += $late_total;
    }

    $subtotal = $total_price;
    $total_amount = $subtotal - $deposit;
    updateInvoiceTotals($invoice_id, 0, $subtotal, $total_amount);

    // Thay vì xóa, truyền toast
    header("Location: invoices.php?success=1&msg=" . urlencode("Tạo hóa đơn từ chuồng thành công!"));
    exit;
}

header("Location: invoices.php?error=1&msg=" . urlencode("Không có chuồng thú cưng được chọn!"));
exit;