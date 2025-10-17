<?php
session_start();
require_once(dirname(__DIR__, 2) . '/init.php');

if (!isset($_SESSION['username'])) {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

require_once(APP_PATH . '/user_function.php');

if (!isset($_GET['id'])) {
    header("Location: users.php");
    exit;
}

$user_id = intval($_GET['id']);
$user = getUserById($user_id);

if (!$user) {
    header("Location: users.php?error=1&msg=" . urlencode("ID người dùng không hợp lệ!"));
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($new_password === '' || $confirm_password === '') {
        header("Location: users.php?error=1&msg=" . urlencode("Vui lòng nhập đầy đủ thông tin!"));
        exit;
    }

    if ($new_password !== $confirm_password) {
        header("Location: users.php?error=1&msg=" . urlencode("Mật khẩu nhập lại không khớp!"));
        exit;
    }

    $hashed_password = md5($new_password);
    if (updateUserPassword($user_id, $hashed_password)) {
        header("Location: users.php?success=3&msg=" . urlencode("Cập nhật mật khẩu thành công!"));
        exit;
    } else {
        header("Location: users.php?error=1&msg=" . urlencode("Cập nhật mật khẩu thất bại!"));
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đổi mật khẩu - Spa Thú Cưng Min Min</title>
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
                <h2>Đổi mật khẩu người dùng</h2>

                <form action="" method="post">
                    <div class="form-group">
                        <label>Tên đăng nhập</label>
                        <input type="text" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label for="new_password">Mật khẩu mới <span class="required-field">*</span></label>
                        <input type="password" id="new_password" name="new_password" placeholder="Nhập mật khẩu mới" required>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Nhập lại mật khẩu mới <span class="required-field">*</span></label>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Nhập lại mật khẩu mới" required>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-save"><i class="fas fa-key"></i> Cập nhật mật khẩu</button>
                        <a href="users.php" class="btn btn-cancel">Hủy</a>
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