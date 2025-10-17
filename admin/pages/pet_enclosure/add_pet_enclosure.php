<?php
session_start();
require_once(dirname(__DIR__, 2) . '/init.php');

if (!isset($_SESSION['username'])) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

require_once(APP_PATH . '/customer_function.php');
require_once(APP_PATH . '/pet_function.php');
require_once(APP_PATH . '/pet_enclosure_function.php');
require_once(APP_PATH . '/general_setting_function.php');

$customers = getAllCustomers();
$pets = getAllPets();
$settings = getGeneralSettings();
$default_rate = $settings['default_daily_rate'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $customer_id      = trim($_POST['customer_id']);
    $pet_id           = trim($_POST['pet_id']);
    $enclosure_number = trim($_POST['pet_enclosure_number']);
    $check_in_date    = trim($_POST['check_in_date']);
    $check_out_date   = $_POST['check_out_date'] ?? null;
    $daily_rate = isset($_POST['daily_rate']) && $_POST['daily_rate'] !== ''
        ? (int)$_POST['daily_rate']
        : (int)$default_rate;
    $deposit          = $_POST['deposit'] ?? null;
    $emergency_limit  = $_POST['emergency_limit'] ?? null;
    $note             = $_POST['pet_enclosure_note'] ?? null;
    $status           = trim($_POST['pet_enclosure_status']);

    if (!empty($customer_id) && !empty($pet_id) && !empty($enclosure_number) && !empty($check_in_date) && !empty($daily_rate) && !empty($status)) {
        if (addPetEnclosure($customer_id, $pet_id, $enclosure_number, $check_in_date, $check_out_date, $daily_rate, $deposit, $emergency_limit, $note, $status)) {
            header("Location: pet_enclosures.php?success=1&msg=" . urlencode("Thêm chuồng thành công!"));
            exit;
        } else {
            header("Location: pet_enclosures.php?error=1&msg=" . urlencode("Thêm chuồng thất bại!"));
            exit;
        }
    } else {
        header("Location: pet_enclosures.php?error=1&msg=" . urlencode("Vui lòng điền đầy đủ thông tin bắt buộc!"));
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm chuồng thú - Spa Thú Cưng Min Min</title>
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
                <h2>Thêm chuồng thú</h2>
                <form action="" method="post">
                    <div class="form-group">
                        <label for="customer_id">Chủ nuôi <span class="required-field">*</span></label>
                        <select name="customer_id" id="customer_id" required>
                            <option value="">-- Chọn chủ nuôi --</option>
                            <?php foreach ($customers as $c): ?>
                                <option value="<?php echo $c['customer_id']; ?>">
                                    <?php echo htmlspecialchars($c['customer_name']) . " - " . $c['customer_phone_number']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="pet_id">Tên thú cưng <span class="required-field">*</span></label>
                        <select name="pet_id" id="pet_id" required>
                            <option value="">-- Chọn thú cưng --</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="pet_enclosure_number">Số chuồng <span class="required-field">*</span></label>
                        <input type="number" name="pet_enclosure_number" id="pet_enclosure_number" min="0" required>
                    </div>

                    <div class="form-group">
                        <label for="check_in_date">Check-in <span class="required-field">*</span></label>
                        <input type="datetime-local" name="check_in_date" id="check_in_date" required>
                    </div>

                    <div class="form-group">
                        <label for="check_out_date">Check-out</label>
                        <input type="datetime-local" name="check_out_date" id="check_out_date">
                    </div>

                    <div class="form-group">
                        <label for="daily_rate">Đơn giá/ngày (₫)
                            <?php if ($default_rate == 0): ?>
                                <span class="required-field">*</span>
                            <?php endif; ?>
                        </label>
                        <input type="number" name="daily_rate" id="daily_rate" min="0"
                            <?php if ($default_rate == 0): ?>required<?php endif; ?>
                            placeholder="<?php echo $default_rate > 0 ? 'Mặc định: ' . number_format($default_rate) . ' ₫' : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="deposit">Tiền cọc (₫)</label>
                        <input type="number" name="deposit" id="deposit" min="0">
                    </div>

                    <div class="form-group">
                        <label for="emergency_limit">Giới hạn cấp cứu</label>
                        <select name="emergency_limit" id="emergency_limit">
                            <option value="">-- Chọn --</option>
                            <option value="500000">500.000 ₫</option>
                            <option value="1000000">1.000.000 ₫</option>
                            <option value="3000000">3.000.000 ₫</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="pet_enclosure_note">Ghi chú</label>
                        <textarea name="pet_enclosure_note" id="pet_enclosure_note" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="pet_enclosure_status">Trạng thái <span class="required-field">*</span></label>
                        <select name="pet_enclosure_status" id="pet_enclosure_status" required style="pointer-events:none; background-color:#eee;">
                            <option value="Check In">Check In</option>
                            <option value="Check Out">Check Out</option>
                        </select>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> Lưu</button>
                        <a href="pet_enclosures.php" class="btn btn-cancel">Hủy</a>
                    </div>
                </form>
            </div>
        </main>
        <!-- Footer -->
        <?php include_once(ADMIN_INC . '/footer.php'); ?>
    </main>

    <script src="../../assets/js/script.js" defer></script>

    <script>
        const pets = <?php echo json_encode($pets); ?>;
        const customerSelect = document.getElementById("customer_id");
        const petSelect = document.getElementById("pet_id");

        customerSelect.addEventListener("change", function() {
            const customerId = this.value;
            petSelect.innerHTML = "<option value=''>-- Chọn thú cưng --</option>";
            pets.filter(p => p.customer_id == customerId)
                .forEach(p => {
                    const opt = document.createElement("option");
                    opt.value = p.pet_id;
                    opt.textContent = p.pet_name;
                    petSelect.appendChild(opt);
                });
        });
    </script>
</body>

</html>