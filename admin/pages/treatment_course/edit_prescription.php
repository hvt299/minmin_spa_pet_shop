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

// Nếu không có id → quay lại danh sách
if (!isset($_GET['id'])) {
    header("Location: prescriptions.php");
    exit;
}

$id = intval($_GET['id']);
$prescription = getPrescriptionById($id);

if (!$prescription) {
    header("Location: prescriptions.php?error=1&msg=" . urlencode("Không tìm thấy toa thuốc!"));
    exit;
}

$treatment_session_id = $prescription['treatment_session_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $medicine_id       = trim($_POST['medicine_id']);
    $treatment_type    = trim($_POST['treatment_type']);
    $dosage            = trim($_POST['dosage']);
    $unit              = trim($_POST['unit']);
    $frequency         = !empty($_POST['frequency']) ? trim($_POST['frequency']) : null;
    $status            = isset($_POST['status']) ? $_POST['status'] : '1';
    $notes             = !empty($_POST['notes']) ? trim($_POST['notes']) : null;

    if (!empty($medicine_id) && !empty($treatment_type) && !empty($dosage) && !empty($unit)) {
        $result = updatePrescription($id, $medicine_id, $treatment_type, $dosage, $unit, $frequency, $status, $notes);

        if ($result) {
            header("Location: prescriptions.php?treatment_session_id=$treatment_session_id&success=1&msg=" . urlencode("Cập nhật toa thuốc thành công!"));
            exit;
        } else {
            header("Location: prescriptions.php?treatment_session_id=$treatment_session_id&error=1&msg=" . urlencode("Cập nhật thất bại!"));
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<!-- Giống form add_prescription nhưng value điền sẵn -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa toa thuốc - Spa Thú Cưng Min Min</title>
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
                <h2>Sửa toa thuốc</h2>
                <form action="" method="post">
                    <div class="form-group">
                        <label for="medicine_id">Thuốc <span class="required-field">*</span></label>
                        <select name="medicine_id" id="medicine_id" required>
                            <?php foreach ($medicines as $m): ?>
                                <option value="<?= $m['medicine_id'] ?>" <?= $m['medicine_id'] == $prescription['medicine_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($m['medicine_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="treatment_type">Loại điều trị <span class="required-field">*</span></label>
                        <select name="treatment_type" id="treatment_type" required>
                            <option value="uống" <?= $prescription['treatment_type'] == 'uống' ? 'selected' : '' ?>>Uống</option>
                            <option value="tiêm" <?= $prescription['treatment_type'] == 'tiêm' ? 'selected' : '' ?>>Tiêm</option>
                            <option value="truyền" <?= $prescription['treatment_type'] == 'truyền' ? 'selected' : '' ?>>Truyền</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="dosage">Liều lượng</label>
                        <input type="number" step="0.01" name="dosage" id="dosage" value="<?= $prescription['dosage'] ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="unit">Đơn vị</label>
                        <select name="unit" id="unit" required>
                            <?php
                            $units = ['ml', 'mg', 'mg/kg', 'g', 'viên', 'giọt', '%'];
                            foreach ($units as $u):
                            ?>
                                <option value="<?= $u ?>" <?= $u == $prescription['unit'] ? 'selected' : '' ?>><?= $u ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="frequency">Tần suất</label>
                        <input type="text" name="frequency" id="frequency" value="<?= htmlspecialchars($prescription['frequency']) ?>">
                    </div>

                    <div class="form-group">
                        <label for="status">Trạng thái</label>
                        <select name="status" id="status">
                            <option value="1" <?= $prescription['status'] == '1' ? 'selected' : '' ?>>Đang thực hiện</option>
                            <option value="0" <?= $prescription['status'] == '0' ? 'selected' : '' ?>>Đã làm</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="notes">Ghi chú</label>
                        <textarea name="notes" id="notes"><?= htmlspecialchars($prescription['notes']) ?></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> Cập nhật</button>
                        <a href="prescriptions.php?treatment_session_id=<?= $treatment_session_id ?>" class="btn btn-cancel">Hủy</a>
                    </div>
                </form>
            </div>
        </main>

        <!-- Footer -->
        <?php include_once(ADMIN_INC . '/footer.php'); ?>
    </main>
</body>

</html>