<?php
require_once(dirname(__DIR__, 2) . '/init.php');

if (!isset($_GET['id'])) {
    die("Thiếu ID hóa đơn");
}
$invoice_id = intval($_GET['id']);

require_once(APP_PATH . '/customer_function.php');
require_once(APP_PATH . '/pet_function.php');
require_once(APP_PATH . '/invoice_function.php');
require_once(APP_PATH . '/service_type_function.php');
require_once(APP_PATH . '/general_setting_function.php');

$settings = getGeneralSettings();

// Lấy hóa đơn
$invoice = getInvoiceById($invoice_id);
if (!$invoice) die("Không tìm thấy hóa đơn");

// Lấy chi tiết hóa đơn
$details = getInvoiceDetailsByInvoiceId($invoice_id);

// Lấy thông tin khách hàng & thú cưng
$customer = getCustomerById($invoice['customer_id']);
$pet = getPetById($invoice['pet_id']);
?>

<div class="invoice-sheet">
    <div class="header">
        <h2>HÓA ĐƠN LƯU CHUỒNG</h2>
        <div class="clinic-name"><?= $settings['clinic_name']; ?></div>
        <div class="clinic-info">
            Đ/c: <?= $settings['clinic_address_1']; ?> •
            ĐT: <?= $settings['phone_number_1']; ?>, <?= $settings['phone_number_2']; ?>
        </div>
    </div>

    <div class="invoice-info">
        Mã HĐ: <strong><?= htmlspecialchars($invoice['invoice_id']); ?></strong> •
        Ngày: <span><?= date("d/m/Y", strtotime($invoice['invoice_date'])); ?></span>
    </div>
    <div class="invoice-info">
        Khách: <span><?= htmlspecialchars($customer['customer_name']); ?></span> •
        SĐT: <span><?= htmlspecialchars($customer['customer_phone_number']); ?></span>
    </div>
    <div class="invoice-info">
        Thú cưng: <span><?= htmlspecialchars($pet['pet_name']); ?></span> •
        Loài/Giống: <span><?= !empty($pet['pet_species']) ? htmlspecialchars($pet['pet_species']) : '-' ?></span>
    </div>

    <table class="invoice-table">
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên dịch vụ / Sản phẩm</th>
                <th>SL</th>
                <th>Đơn giá</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1;
            foreach ($details as $d): ?>
                <tr>
                    <td class="center"><?= $i++; ?></td>
                    <td class="center"><?= htmlspecialchars((getServiceTypeById($d['service_type_id']))['service_name']); ?></td>
                    <td class="center"><?= $d['quantity']; ?></td>
                    <td class="right"><?= number_format($d['unit_price'], 0, ",", "."); ?></td>
                    <td class="right"><?= number_format($d['total_price'], 0, ",", "."); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="right">Tạm tính</td>
                <td class="right"><?= number_format($invoice['subtotal'], 0, ",", "."); ?></td>
            </tr>
            <tr>
                <td colspan="4" class="right">Cọc</td>
                <td class="right"><?= number_format($invoice['deposit'], 0, ",", "."); ?></td>
            </tr>
            <tr>
                <td colspan="4" class="right">Giảm giá</td>
                <td class="right"><?= number_format($invoice['discount'], 0, ",", "."); ?></td>
            </tr>
            <tr class="total">
                <td colspan="4" class="right bold">Tổng thanh toán</td>
                <td class="right bold"><?= number_format($invoice['total_amount'], 0, ",", "."); ?></td>
            </tr>
        </tfoot>
    </table>

    <div class="thankyou">
        Cảm ơn Quý khách!
    </div>
</div>