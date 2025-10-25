<?php
session_start();
require_once(dirname(__DIR__, 2) . '/init.php');

if (!isset($_SESSION['username'])) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

require_once(APP_PATH . '/medicine_function.php');

// Nếu không có id thì quay lại danh sách
if (!isset($_GET['id'])) {
    header("Location: medicines.php");
    exit;
}

$medicine_id = $_GET['id'];
$medicine = getMedicineById($medicine_id);

if (!$medicine) {
    header("Location: medicines.php?error=1&msg=" . urlencode("ID thuốc không hợp lệ!"));
    exit;
}

// Xử lý cập nhật
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $medicine_name = trim($_POST['medicine_name']);
    $medicine_route = trim($_POST['medicine_route']);

    if (!empty($medicine_name) && !empty($medicine_route)) {
        $updated = updateMedicine($medicine_id, $medicine_name, $medicine_route);
        if ($updated) {
            header("Location: medicines.php?success=1&msg=" . urlencode("Cập nhật thuốc thành công!"));
            exit;
        } else {
            header("Location: medicines.php?error=1&msg=" . urlencode("Cập nhật thất bại!"));
            exit;
        }
    } else {
        header("Location: medicines.php?error=1&msg=" . urlencode("Vui lòng nhập đầy đủ thông tin bắt buộc!"));
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa thông tin thuốc - Spa Thú Cưng Min Min</title>
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
                <h2>Sửa thông tin thuốc</h2>

                <form action="" method="post">
                    <div class="form-group">
                        <label for="medicine_name">Tên thuốc <span class="required-field">*</span></label>
                        <input type="text" id="medicine_name" name="medicine_name"
                            value="<?= htmlspecialchars($medicine['medicine_name']) ?>"
                            placeholder="Nhập tên thuốc" required>
                    </div>

                    <div class="form-group">
                        <label for="medicine_route">Đường tiêm truyền <span class="required-field">*</span></label>
                        <select id="medicine_route" name="medicine_route" required>
                            <option value="">-- Chọn đường dùng --</option>
                            <option value="PO" <?= $medicine['medicine_route'] === 'PO' ? 'selected' : '' ?>>Đường uống (PO)</option>
                            <option value="IM" <?= $medicine['medicine_route'] === 'IM' ? 'selected' : '' ?>>Tiêm bắp (IM)</option>
                            <option value="IV" <?= $medicine['medicine_route'] === 'IV' ? 'selected' : '' ?>>Tiêm tĩnh mạch (IV)</option>
                            <option value="SC" <?= $medicine['medicine_route'] === 'SC' ? 'selected' : '' ?>>Tiêm dưới da (SC)</option>
                        </select>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> Cập nhật</button>
                        <a href="medicines.php" class="btn btn-cancel">Hủy</a>
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