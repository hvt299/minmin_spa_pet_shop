<?php
session_start();
require_once(dirname(__DIR__, 2) . '/init.php');

if (!isset($_SESSION['username'])) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

require_once(APP_PATH . '/service_type_function.php');

// Kiểm tra id
if (!isset($_GET['id'])) {
    header("Location: service_types.php");
    exit;
}

$service_type_id = intval($_GET['id']);
$serviceType = getServiceTypeById($service_type_id);

if (!$serviceType) {
    header("Location: service_types.php?error=1&msg=" . urlencode("ID dịch vụ không hợp lệ!"));
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $service_name = trim($_POST['service_name']);
    $description  = trim($_POST['description']) ?: null;

    if (!empty($service_name)) {
        if (updateServiceType($service_type_id, $service_name, $description)) {
            header("Location: service_types.php?success=2&msg=" . urlencode("Cập nhật dịch vụ thành công!"));
            exit;
        } else {
            header("Location: service_types.php?error=1&msg=" . urlencode("Cập nhật dịch vụ thất bại!"));
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
    <title>Chỉnh sửa loại dịch vụ - Spa Thú Cưng Min Min</title>
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
                <h2>Chỉnh sửa loại dịch vụ</h2>

                <form action="" method="post">
                    <div class="form-group">
                        <label for="service_name">Tên loại dịch vụ <span class="required-field">*</span></label>
                        <input type="text" id="service_name" name="service_name" placeholder="Nhập tên loại dịch vụ"
                            value="<?php echo htmlspecialchars($serviceType['service_name']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Mô tả</label>
                        <textarea id="description" name="description" rows="3" placeholder="Nhập mô tả..."><?php echo htmlspecialchars($serviceType['description']); ?></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> Cập nhật</button>
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