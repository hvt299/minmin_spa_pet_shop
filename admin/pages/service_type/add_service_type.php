<?php
session_start();
require_once(dirname(__DIR__, 2) . '/init.php');

if (!isset($_SESSION['username'])) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

require_once(APP_PATH . '/service_type_function.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $service_name = trim($_POST['service_name']);
    $description  = trim($_POST['description']) ?: null;

    if (!empty($service_name)) {
        if (addServiceType($service_name, $description)) {
            header("Location: service_types.php?success=1&msg=" . urlencode("Thêm dịch vụ thành công!"));
            exit;
        } else {
            header("Location: service_types.php?error=1&msg=" . urlencode("Thêm dịch vụ thất bại!"));
            exit;
        }
    } else {
        header("Location: service_types.php?error=1&msg=" . urlencode("Tên dịch vụ không được để trống!"));
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm loại dịch vụ - Spa Thú Cưng Min Min</title>
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
                <h2>Thêm loại dịch vụ mới</h2>

                <form action="" method="post">
                    <div class="form-group">
                        <label for="service_name">Tên loại dịch vụ <span class="required-field">*</span></label>
                        <input type="text" id="service_name" name="service_name" placeholder="Nhập tên loại dịch vụ" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Mô tả</label>
                        <textarea id="description" name="description" rows="3" placeholder="Nhập mô tả..."></textarea>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> Lưu</button>
                        <a href="service_types.php" class="btn btn-cancel">Hủy</a>
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