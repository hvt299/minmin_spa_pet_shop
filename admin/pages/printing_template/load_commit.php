<?php
require_once(dirname(__DIR__, 2) . '/init.php');

if (!isset($_GET['id'])) {
    die("Thiếu ID hóa đơn");
}
$invoice_id = intval($_GET['id']);

require_once(APP_PATH . '/customer_function.php');
require_once(APP_PATH . '/pet_function.php');
require_once(APP_PATH . '/pet_enclosure_function.php');
require_once(APP_PATH . '/invoice_function.php');
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
$enclosure = getPetEnclosureById($invoice['pet_enclosure_id']);
?>

<div class="commitment-sheet">
    <div class="commitment-sheet__header">
        <h2 class="commitment-sheet__title">GIẤY CAM KẾT LƯU CHUỒNG</h2>
        <div class="commitment-sheet__clinic-name"><?= $settings['clinic_name']; ?></div>
        <div class="commitment-sheet__clinic-info">
            Đ/c: <?= $settings['clinic_address_1']; ?> • ĐT: <?= $settings['phone_number_1']; ?>, <?= $settings['phone_number_2']; ?>
        </div>
        <div class="commitment-sheet__date">
            Ngày <?= date("d/m/Y", strtotime($invoice['invoice_date'])); ?>
        </div>
    </div>

    <ol class="commitment-sheet__list">
        <li class="commitment-sheet__item">
            <div class="commitment-sheet__section-title">THÔNG TIN CÁC BÊN</div>
            <div class="commitment-sheet__text">
                - Bên A (Phòng khám): <?= $settings['clinic_name']; ?> • Người đại diện: <?= $settings['representative_name']; ?>
            </div>
            <div class="commitment-sheet__text">
                - Bên B (Chủ nuôi): <?= htmlspecialchars($customer['customer_name']); ?>
                • CCCD: <?= htmlspecialchars($customer['customer_identity_card'] ?? '-'); ?>
                • SĐT: <?= htmlspecialchars($customer['customer_phone_number']); ?>
                • Đ/c: <?= htmlspecialchars($customer['customer_address'] ?? '-'); ?>
            </div>
        </li>

        <li class="commitment-sheet__item">
            <div class="commitment-sheet__section-title">THÔNG TIN THÚ CƯNG</div>
            <div class="commitment-sheet__text">
                - Tên thú cưng: <?= htmlspecialchars($pet['pet_name']); ?>
                • Loài/giống: <?= !empty($pet['pet_species']) ? htmlspecialchars($pet['pet_species']) : '-' ?>
                • Giới tính: <?= $pet['pet_gender'] == 1 ? 'Cái' : 'Đực'; ?>
            </div>
            <div class="commitment-sheet__text">
                - Tuổi/ngày sinh: <?= !empty($pet['pet_dob']) ? htmlspecialchars($pet['pet_dob']) : '-'; ?>
                • Cân nặng: <?= !empty($pet['pet_weight']) ? htmlspecialchars($pet['pet_weight']) . 'kg' : '-'; ?>
                • Đã triệt sản: <?= $pet['pet_sterilization'] ? "Có" : "Không"; ?>
            </div>
            <div class="commitment-sheet__text">
                - Đặc điểm/dị ứng:
                <?php
                $char = $pet['pet_characteristic'] ?? '';
                $allergy = $pet['pet_drug_allergy'] ?? '';
                if (!empty($char) || !empty($allergy)) {
                    echo htmlspecialchars(trim($char . (!empty($char) && !empty($allergy) ? " • " : "") . $allergy));
                } else {
                    echo "-";
                }
                ?>
            </div>
        </li>

        <li class="commitment-sheet__item">
            <div class="commitment-sheet__section-title">THỜI GIAN LƯU CHUỒNG & DỊCH VỤ</div>
            <div class="commitment-sheet__text">
                - Thời gian: từ <?= date("d/m/Y H:i", strtotime($enclosure['check_in_date'])); ?> đến <?= !empty($enclosure['check_out_date']) ? date("d/m/Y H:i", strtotime($enclosure['check_out_date'])) : "-"; ?>
            </div>
            <div class="commitment-sheet__text">
                - Ghi chú (dịch vụ, đồ gửi kèm...): <?= !empty($enclosure['pet_enclosure_note']) ? htmlspecialchars($enclosure['pet_enclosure_note']) : 'Không có'; ?>
            </div>
        </li>

        <li class="commitment-sheet__item">
            <div class="commitment-sheet__section-title">XỬ LÝ TÌNH HUỐNG KHẨN CẤP</div>
            <div class="commitment-sheet__text">
                - Khi thú cưng nguy cấp, Bên A ưu tiên liên hệ Bên B. Nếu không được, Bên A được quyền cấp cứu kịp thời.
            </div>
            <div class="commitment-sheet__text">
                - Giới hạn chi phí cấp cứu được phép: <?= number_format($enclosure['emergency_limit'], 0, ",", "."); ?> đ.
            </div>
        </li>

        <li class="commitment-sheet__item">
            <div class="commitment-sheet__section-title">PHÍ & THANH TOÁN</div>
            <div class="commitment-sheet__text">
                - Đơn giá: <?= number_format($enclosure['daily_rate'], 0, ",", "."); ?> đ/ngày. Phí phát sinh theo bảng giá/thỏa thuận.
            </div>
            <div class="commitment-sheet__text">
                - Đã cọc: <?= number_format($enclosure['deposit'], 0, ",", "."); ?> đ. Thanh toán đủ khi nhận thú cưng.
            </div>
            <div class="commitment-sheet__text">
                - Nhận trễ giờ quy định có thể phụ thu: <?= number_format($settings['overtime_fee_per_hour'] ?? 0, 0, ",", "."); ?> đ/giờ.
            </div>
        </li>
    </ol>

    <div class="commitment-sheet__note">
        * Bên B đã đọc, hiểu và đồng ý với các điều khoản về rủi ro, hành vi, an toàn... của phòng khám.
    </div>

    <table class="commitment-sheet__sign-table">
        <tr>
            <td><strong>BÊN A (Phòng khám)</strong><br>(Ký, ghi rõ họ tên)</td>
            <td><strong>BÊN B (Chủ nuôi)</strong><br>(Ký, ghi rõ họ tên)</td>
        </tr>
    </table>

    <div class="commitment-sheet__footer">
        <?= $settings['signing_place']; ?>, ngày <?= date("d/m/Y", strtotime($invoice['invoice_date'])); ?>
    </div>
</div>