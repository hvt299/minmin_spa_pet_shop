<?php
session_start();
require_once(dirname(__DIR__, 2) . '/init.php');

if (!isset($_SESSION['username'])) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

require_once(APP_PATH . '/general_setting_function.php');

$settings = getGeneralSettings();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $setting_id = $settings['setting_id'];

    $clinic_name           = trim($_POST['clinic_name']);
    $clinic_address_1      = trim($_POST['clinic_address_1']);
    $clinic_address_2      = trim($_POST['clinic_address_2']) ?: null;
    $phone_number_1        = trim($_POST['phone_number_1']);
    $phone_number_2        = trim($_POST['phone_number_2']) ?: null;
    $representative_name   = trim($_POST['representative_name']);
    $checkout_hour         = !empty($_POST['checkout_hour']) ? $_POST['checkout_hour'] : '18:00';
    $overtime_fee_per_hour = isset($_POST['overtime_fee_per_hour']) ? (int)$_POST['overtime_fee_per_hour'] : 0;
    $default_daily_rate    = isset($_POST['default_daily_rate']) ? (int)$_POST['default_daily_rate'] : 0;
    $signing_place         = trim($_POST['signing_place']);

    if (!empty($clinic_name) && !empty($clinic_address_1) && !empty($phone_number_1) && !empty($representative_name) && !empty($signing_place)) {
        if (updateGeneralSettings(
            $setting_id,
            $clinic_name,
            $clinic_address_1,
            $clinic_address_2,
            $phone_number_1,
            $phone_number_2,
            $representative_name,
            $checkout_hour,
            $overtime_fee_per_hour,
            $default_daily_rate,
            $signing_place
        )) {
            // Thành công
            header("Location: general_setting.php?success=1&msg=" . urlencode("Cập nhật cài đặt thành công!"));
            exit;
        } else {
            // Lỗi update
            header("Location: general_setting.php?error=1&msg=" . urlencode("Cập nhật cài đặt thất bại!"));
            exit;
        }
    } else {
        // Thiếu thông tin bắt buộc
        header("Location: general_setting.php?error=1&msg=" . urlencode("Vui lòng điền đầy đủ thông tin bắt buộc."));
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cài đặt chung - Spa Thú Cưng Min Min</title>
    <link rel="stylesheet" href="../../assets/css/base.css">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="../../assets/css/grid.css">
    <link rel="stylesheet" href="../../assets/css/responsive.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>

<body>
    <div class="overlay" id="overlay"></div>

    <!-- Sidebar -->
    <?php include_once(ADMIN_INC . '/sidebar.php'); ?>

    <main class="main">
        <!-- Header -->
        <?php include_once(ADMIN_INC . '/header.php'); ?>
        <!-- Main content -->
        <main class="content">
            <div class="form-container">
                <h2>Cài đặt chung</h2>

                <form method="post">
                    <div class="form-group">
                        <label for="clinic_name">Tên phòng khám <span class="required-field">*</span></label>
                        <input type="text" id="clinic_name" name="clinic_name" value="<?= htmlspecialchars($settings['clinic_name'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="clinic_address_1">Địa chỉ phòng khám 1 <span class="required-field">*</span></label>
                        <input type="text" id="clinic_address_1" name="clinic_address_1" value="<?= htmlspecialchars($settings['clinic_address_1'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="clinic_address_2">Địa chỉ phòng khám 2</label>
                        <input type="text" id="clinic_address_2" name="clinic_address_2" value="<?= htmlspecialchars($settings['clinic_address_2'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="phone_number_1">Số điện thoại 1 <span class="required-field">*</span></label>
                        <input type="text" id="phone_number_1" name="phone_number_1" value="<?= htmlspecialchars($settings['phone_number_1'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="phone_number_2">Số điện thoại 2</label>
                        <input type="text" id="phone_number_2" name="phone_number_2" value="<?= htmlspecialchars($settings['phone_number_2'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="representative_name">Người đại diện <span class="required-field">*</span></label>
                        <input type="text" id="representative_name" name="representative_name" value="<?= htmlspecialchars($settings['representative_name'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="checkout_hour">Giờ quy định trả (24h)</label>
                        <input type="time" id="checkout_hour" name="checkout_hour" value="<?= htmlspecialchars($settings['checkout_hour'] ?? '18:00') ?>">
                    </div>
                    <div class="form-group">
                        <label for="overtime_fee_per_hour">Phụ thu (đ/giờ)</label>
                        <input type="number" id="overtime_fee_per_hour" name="overtime_fee_per_hour" min="0" value="<?= htmlspecialchars($settings['overtime_fee_per_hour'] ?? 0) ?>">
                    </div>
                    <div class="form-group">
                        <label for="default_daily_rate">Đơn giá lưu chuồng mặc định (đ/ngày)</label>
                        <input type="number" id="default_daily_rate" name="default_daily_rate" min="0" value="<?= htmlspecialchars($settings['default_daily_rate'] ?? 0) ?>">
                    </div>
                    <div class="form-group">
                        <label for="signing_place">Nơi ký (thành phố) <span class="required-field">*</span></label>
                        <input type="text" id="signing_place" name="signing_place" value="<?= htmlspecialchars($settings['signing_place'] ?? '') ?>" required>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </main>
        <!-- Footer -->
        <?php include_once(ADMIN_INC . '/footer.php'); ?>
    </main>

    <script src="../../assets/js/script.js" defer></script>
</body>

</html>