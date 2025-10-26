<?php
session_start();
require_once(dirname(__DIR__, 2) . '/init.php');

if (!isset($_SESSION['username'])) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

require_once(APP_PATH . '/medicine_function.php');
require_once(APP_PATH . '/prescription_function.php');

$medicines = getAllMedicines();

// Nếu không có treatment_session_id → quay lại danh sách
if (!isset($_GET['treatment_session_id'])) {
    header("Location: diagnoses.php");
    exit;
}

$treatment_session_id = intval($_GET['treatment_session_id']);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $medicine_id       = trim($_POST['medicine_id']);
    $treatment_type    = trim($_POST['treatment_type']);
    $dosage            = trim($_POST['dosage']);
    $unit              = trim($_POST['unit']);
    $frequency         = !empty($_POST['frequency']) ? trim($_POST['frequency']) : null;
    $notes             = !empty($_POST['notes']) ? trim($_POST['notes']) : null;
    $status            = isset($_POST['status']) ? $_POST['status'] : '1';

    if (!empty($medicine_id) && !empty($treatment_type) && !empty($dosage) && !empty($unit)) {
        $result = addPrescription($treatment_session_id, $medicine_id, $treatment_type, $dosage, $unit, $frequency, $status, $notes);

        if ($result) {
            header("Location: prescriptions.php?treatment_session_id=$treatment_session_id&success=1&msg=" . urlencode("Thêm toa thuốc thành công!"));
            exit;
        } else {
            header("Location: prescriptions.php?treatment_session_id=$treatment_session_id&error=1&msg=" . urlencode("Thêm thất bại!"));
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm toa thuốc - Spa Thú Cưng Min Min</title>
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
                <h2>Thêm toa thuốc</h2>
                <form action="" method="post">
                    <div class="form-group">
                        <label for="medicine_id">Thuốc <span class="required-field">*</span></label>
                        <select name="medicine_id" id="medicine_id" required>
                            <option value="">-- Chọn thuốc --</option>
                            <?php foreach ($medicines as $m): ?>
                                <option value="<?= $m['medicine_id'] ?>"><?= htmlspecialchars($m['medicine_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="treatment_type">Loại điều trị <span class="required-field">*</span></label>
                        <select name="treatment_type" id="treatment_type" required>
                            <option value="uống">Uống</option>
                            <option value="tiêm">Tiêm</option>
                            <option value="truyền">Truyền</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="dosage">Liều lượng <span class="required-field">*</span></label>
                        <input type="number" step="0.01" name="dosage" id="dosage" required>
                    </div>

                    <div class="form-group">
                        <label for="unit">Đơn vị <span class="required-field">*</span></label>
                        <select name="unit" id="unit" required>
                            <option value="ml">ml</option>
                            <option value="mg">mg</option>
                            <option value="mg/kg">mg/kg</option>
                            <option value="g">g</option>
                            <option value="viên">viên</option>
                            <option value="giọt">giọt</option>
                            <option value="%">%</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="frequency">Tần suất</label>
                        <input type="text" name="frequency" id="frequency">
                    </div>

                    <div class="form-group">
                        <label for="status">Trạng thái</label>
                        <select name="status" id="status">
                            <option value="1" selected>Đang thực hiện</option>
                            <option value="0">Đã làm</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="notes">Ghi chú</label>
                        <textarea name="notes" id="notes"></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> Lưu</button>
                        <a href="prescriptions.php?treatment_session_id=<?= $treatment_session_id ?>" class="btn btn-cancel">Hủy</a>
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