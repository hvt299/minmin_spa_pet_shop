<?php
session_start();
require_once(dirname(__DIR__, 2) . '/init.php');

if (!isset($_SESSION['username'])) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

require_once(APP_PATH . '/medicine_function.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $medicine_name = trim($_POST['medicine_name']);
    $medicine_route = trim($_POST['medicine_route']);

    if (!empty($medicine_name) && !empty($medicine_route)) {
        if (addMedicine($medicine_name, $medicine_route)) {
            header("Location: medicines.php?success=1&msg=" . urlencode("Thêm thuốc thành công!"));
            exit;
        } else {
            header("Location: medicines.php?error=1&msg=" . urlencode("Thêm thuốc thất bại!"));
            exit;
        }
    } else {
        header("Location: medicines.php?error=1&msg=" . urlencode("Tên thuốc không được để trống!"));
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm thuốc thú y - Spa Thú Cưng Min Min</title>
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
                <h2>Thêm thuốc thú y mới</h2>

                <form action="" method="post">
                    <div class="form-group">
                        <label for="medicine_name">Tên thuốc <span class="required-field">*</span></label>
                        <input type="text" id="medicine_name" name="medicine_name" placeholder="Nhập tên thuốc" required>
                    </div>

                    <div class="form-group">
                        <label for="medicine_route">Đường tiêm truyền <span class="required-field">*</span></label>
                        <select id="medicine_route" name="medicine_route" required>
                            <option value="">-- Chọn đường dùng --</option>
                            <option value="PO">Đường uống (PO)</option>
                            <option value="IM">Tiêm bắp (IM)</option>
                            <option value="IV">Tiêm tĩnh mạch (IV)</option>
                            <option value="SC">Tiêm dưới da (SC)</option>
                        </select>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> Lưu</button>
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